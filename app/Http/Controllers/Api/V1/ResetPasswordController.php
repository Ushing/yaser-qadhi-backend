<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\SendVerificationCodeEmail;
use App\Models\CustomerDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{
    public function sendEmail(Request $request): JsonResponse
    {

        $checkEmail = CustomerDetail::where('email', $request->email)->exists();
        if ($checkEmail) {
            $code = rand(10000, 99999);
            $storeRequestedData = DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $code,
                'created_at' => now()
            ]);

            if ($storeRequestedData) {
                $sendMail = Mail::to($request->email)->send(new SendVerificationCodeEmail($code));
                if ($sendMail) {
                    return response()->json(['status' => 'success', 'message' => 'Please check your email for a 5-digit pin to verify your email'], 200);
                } else {
                    return response()->json(['status' => 'failed', 'message' => 'Failed to send email'], 200);
                }
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Failed to generate verification code'], 200);
            }
        } else {
            return response()->json(['status' => 'failed', 'message' => 'Your given email is not exist in our records'], 200);
        }
    }

    public function checkVerificationCode(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'max:255'],
            'token' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        }
        $records = DB::table('password_resets')->where([['email', $request->email], ['token', $request->token]]);
        if ($records->exists()) {
            $difference = Carbon::now()->diffInSeconds($records->first()->created_at);
            if ($difference > 6000) {
                return response()->json(['success' => false, 'message' => "Token Expired"], 400);
            }
            DB::table('password_resets')->where([['email', $request->email], ['token', $request->token]])->delete();
            return response()->json(['success' => true, 'message' => "You can now reset your password"], 200);
        } else {
            return response()->json(['success' => false, 'message' => "Invalid token"], 401);
        }
    }

    public function resetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        }
        $user = CustomerDetail::where('email', $request->email);
      $user->update([
            'password' => Hash::make($request->password)
        ]);
        $token = $user->first()->createToken('Personal Access Token')->plainTextToken;
        if ($token){
            return response()->json(['success' => true, 'message' => "Your password has been reset", 'token' => $token], 200);
        }else{
            return response()->json(['success' => false, 'message' => "Failed to reset password"], 400);
        }
    }
}
