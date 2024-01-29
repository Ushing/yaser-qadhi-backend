<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\SubscriptionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CouponController extends Controller
{
    public function checkCoupon($coupon)
    {
        $checkCoupon = Coupon::where('code', $coupon)->where('status', 'true')->first();

        if ($checkCoupon == null) {
            return response()->json('invalid coupon', 200);
        } else {
            return response()->json($checkCoupon, 200);
        }
    }

    public function updateCoupon(Request $request)
    {

        /* $coupon = Coupon::where('code', $request->code)->limit(1);
        $coupon->email = $request->email;
        $coupon->status = 'false';

        $coupon->save();

        $data[] = [
            'code' => $coupon->code,
            'email' => $coupon->email,
            'status' => $coupon->status,
        ];
 */
        $data = DB::table('coupons')
            ->where('code', $request->code)
            ->update(['status' => 'false', 'email' => $request->email]);
        return response()->json(
            [
                'success' => true,
                'message' => 'Your coupon code applied.'
            ],
            200
        );

        /*     if ($coupon->save()) {
            return response()->json('Coupon applied successfully', 200);
        } else {
            return response()->json('Coupon not applied, please try again.', 500);
        } */
    }
}
