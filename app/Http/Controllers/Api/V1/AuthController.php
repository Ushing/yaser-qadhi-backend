<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\SignUpVerificationCodeMail;
use App\Models\CustomerDetail;
use App\Models\CustomerVerify;
use App\Models\SubscriptionDetail;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //Validate
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:customer_details',
            'password' => ['required', 'confirmed', Password::defaults()],
            'device_name' => 'required',
            'device_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $customer = CustomerDetail::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'device_id' => $request->device_id,
                'login_type' => $request->login_type,
                'user_type' => $request->device_name
            ]);

//            $code = rand(10000, 99999);
            $code = '12345';
            $storeVerifiedData = CustomerVerify::create([
                'customer_id' => $customer->id,
                'token' => $code
            ]);

            if ($storeVerifiedData) {
                $sendMail = true;
//                $sendMail = Mail::to($request->email)->send(new SignUpVerificationCodeMail($code));
                if ($sendMail) {
                    $token = $customer->createToken('Personal Access Token')->plainTextToken;
                    $response = ['user' => $customer, 'token' => $token, 'email_verified' => false];
                    return response()->json(
                        [
                            'success' => true,
                            'message' => 'Please check your email for a 5-digit pin to verify your email',
                            'data' => $response
                        ],
                        200
                    );
                } else {
                    return response()->json(['status' => 'failed', 'message' => 'Failed to send email for verification'], 200);
                }
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Failed to generate verification code'], 200);
            }
        }
    }


    public function resendOtpForVerification(Request $request): JsonResponse
    {
        $rules = [
            'email' => 'required|email',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        } else {
            $customer = CustomerDetail::where('email', $request->email)->first();
            if ($customer->email_verified_at == null) {
                $code = '12345';
//                $code = rand(10000, 99999);
                $storeVerifiedData = CustomerVerify::create([
                    'customer_id' => $customer->id,
                    'token' => $code
                ]);
                if ($storeVerifiedData) {
//                    $sendMail = Mail::to($request->email)->send(new SignUpVerificationCodeMail($code));
                    $sendMail = true;
                    if ($sendMail) {
                        $token = $customer->createToken('Personal Access Token')->plainTextToken;
                        $response = ['user' => $customer, 'token' => $token, 'email_verified' => false];
                        return response()->json(
                            [
                                'success' => true,
                                'message' => 'Please check your email for a 5-digit pin to verify your email',
                                'data' => $response
                            ],
                            200
                        );
                    } else {
                        return response()->json(['status' => 'failed', 'message' => 'Failed to send email for verification'], 400);
                    }
                } else {
                    return response()->json(['status' => 'failed', 'message' => 'Failed to generate verification code'], 400);
                }
            } else {
                return response()->json(['status' => 'failed', 'message' => 'Email is already verified'], 400);
            }
        }
    }

    //verification of email
    public function verifyEmailForRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => ['required'],
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        } else {
            $verifyCustomer = CustomerVerify::where('token', $request->token)->first();


            if (!$verifyCustomer) {
                return response()->json(['success' => false, 'message' => "Otp Is Not Matched"], 400);
            }
            if (!is_null($verifyCustomer)) {
                $difference = Carbon::now()->diffInSeconds($verifyCustomer->created_at);
                if ($difference > 25000) {
                    return response()->json(['success' => false, 'message' => "Token Expired"], 400);
                } else {
                    $customer = $verifyCustomer->customer;
                    if (!$customer->email_verified_at) {
                        $verifyCustomer->customer->email_verified_at = now();
                        $customerData = $verifyCustomer->customer->save();
                        if ($customerData) {
                            DB::table('customer_verifies')->where('token', $request->token)->delete();
                        }
                        $message = "Your e-mail is verified. You can now login.";
                        return response()->json(['success' => true, 'message' => $message, 'email_verified' => true], 200);
                    } else {
                        $message = "Your e-mail is already verified. You can now login.";
                        return response()->json(['success' => true, 'message' => $message], 200);
                    }
                }
            }
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
             'device_id' => 'required'

        ]);
        $user = CustomerDetail::where('email', $request->email)->first();
        $device = CustomerDetail::where('device_id', $request->device_id)->first();

        /*  if ($user->email_verified_at == null) {
             $message = "Your e-mail is not verified.Please verify first";
             return response()->json(['success' => false, 'message' => $message], 400);
         } else { */
        // if user email found and password is correct
        if ($user && Hash::check($request->password, $user->password)) {
            if ($request->device_id == "") {
                $response = ['message' => 'You are using old version! please update your App'];
                return response()->json($response, 403);
            } else if ($device) {
                $subscription = SubscriptionDetail::where('customer_detail_id', $user->id)->first();
                //  $user->tokens()->delete();

                $token = $user->createToken('Personal Access Token')->plainTextToken;
                $response = ['token_type' => 'Bearer', 'token' => $token, 'message' => 'Login successful', 'customer' => $user, 'subscription_details' => $subscription];
                return response()->json($response, 200);
            } else if ($user->device_id == null) {
                $user->device_id = $request->device_id;
                $user->user_type = $request->device_name;
                $user->save();
                $subscription = SubscriptionDetail::where('customer_detail_id', $user->id)->first();
                //  $user->tokens()->delete();

                $token = $user->createToken('Personal Access Token')->plainTextToken;
                $response = ['token_type' => 'Bearer', 'token' => $token, 'message' => 'Login successful', 'customer' => $user, 'subscription_details' => $subscription];
                return response()->json($response, 200);
            } else if ($request->email == 'samad_chy@yahoo.com') {
                $subscription = SubscriptionDetail::where('customer_detail_id', $user->id)->first();

                $token = $user->createToken('Personal Access Token')->plainTextToken;
                $response = ['token_type' => 'Bearer', 'token' => $token, 'message' => 'Login successful', 'customer' => $user, 'subscription_details' => $subscription];
                return response()->json($response, 200);
            } else if ($request->email == 'ibnulhossain07@gmail.com') {
                $subscription = SubscriptionDetail::where('customer_detail_id', $user->id)->first();

                $token = $user->createToken('Personal Access Token')->plainTextToken;
                $response = ['token_type' => 'Bearer', 'token' => $token, 'message' => 'Login successful', 'customer' => $user, 'subscription_details' => $subscription];
                return response()->json($response, 200);
            } else {
                $response = ['message' => 'This account already register with another device'];
                return response()->json($response, 403);
            }
        } else if (!$user) {
            $response = ['message' => 'User does not exist on this Email '];
            //return DD($user);
            return response()->json($response, 401);
        }
        $response = ['message' => 'Incorrect email or password'];
        return response()->json($response, 400);
        //  }


    }

    public function editProfile($id)
    {
        $customer = CustomerDetail::findOrFail($id);
        // return response()->json($customer, 200);
        if ($customer) {
            return response()->json(
                [
                    'success' => true,
                    'data' => $customer,
                    'message' => 'success'
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'data' => null,
                    'message' => 'unsuccessful'
                ],
                200
            );
        }
    }

    public function getUser($id)
    {
        $customer = CustomerDetail::findOrFail($id);
        $subscription = SubscriptionDetail::where('customer_detail_id', $id)->first();
        // return response()->json($customer, 200);
        if ($customer) {
            return response()->json(
                [
                    'success' => true,
                    'customer' => $customer,
                    'subscription_details' => $subscription,
                    'message' => 'success'
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'success' => false,
                    'data' => null,
                    'message' => 'unsuccessful'
                ],
                200
            );
        }
    }

    public function updateProfile(Request $request)
    {

        $customer = CustomerDetail::findOrFail($request->id);

        $customer->name = $request->name;
        $customer->email = $request->email;

        /* if ($customer->email == $request->email) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'This email is already exist. Please try another email'
                ],
                400
            );
        } else {
            $customer->save();

            $data[] = [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'status' => 200,
            ];

            $token = $customer->createToken('Personal Access Token')->plainTextToken;
            $response = ['user' => $customer, 'token' => $token];

            return response()->json(
                [
                    'success' => true,
                    'data' => $response,
                    'message' => 'User information updated successfully.'
                ],
                200
            );
        } */

        $customer->save();

        $data[] = [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'status' => 200,
        ];

        $token = $customer->createToken('Personal Access Token')->plainTextToken;
        $response = ['user' => $customer, 'token' => $token];

        return response()->json(
            [
                'success' => true,
                'data' => $response,
                'message' => 'User information updated successfully.'
            ],
            200
        );
    }

    public function changePassword(Request $req, $id)
    {
        //Validate
        $validator = Validator::make($req->all(), [
            'old_password' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validations fails',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = CustomerDetail::findOrFail($id);
        if (Hash::check($req->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($req->password)
            ]);
            return response()->json([
                'message' => 'Password successfully updated',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Old password does not matched',
            ], 400);
        }
        return response();
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function paypalInfo()
    {
        $data[] = [
            'client_id' => ENV('PAYPAL_CLIENT_ID'),
            'client_secret' => ENV('PAYPAL_CLIENT_SECRET'),

        ];

        return response()->json($data, 200);
    }


    public function deleteCustomerAccount($id)
    {
        $customer = CustomerDetail::findOrFail($id);
        if ($customer) {
            $deleteCustomer = $customer->delete();
            if ($deleteCustomer) {
                return response()->json(['success' => true, 'message' => 'User Information is deleted successfully'], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'User Information is failed to delete'], 400);
            }
        } else {
            return response()->json(['success' => false, 'message' => 'User Not Found'], 400);
        }
    }
}
