<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionDetail;
use Illuminate\Http\Request;

class SubscriptionDetailController extends Controller
{
    public function index()
    {
        $subscription = SubscriptionDetail::all();
        return response()->json($subscription);
    }
    public function store(Request $request)
    {
        $subscription = new SubscriptionDetail();
        $customer = SubscriptionDetail::where('customer_detail_id', $request->user_id)->first();
        if ($customer)
        {
            $sub = SubscriptionDetail::where('customer_detail_id', $request->user_id)->firstOrFail();
            $sub->email = $request->email;
            $sub->subscription_name = $request->subscription_name;
            $sub->subscribe_amount = $request->subscribe_amount;
            $sub->starting_date = $request->starting_date;
            $sub->ending_date = $request->ending_date;
            $sub->latest_purchase_date = $request->latest_purchase_date;
            $sub->request_date = $request->request_date;
            if ($sub->save()) {
                return response()->json('Subscription updated successfully', 200);
            } else {
                return response()->json('Subscription is not updated, please try again.', 500);
            }
        }
        else
        {
            $subscription->customer_detail_id = $request->user_id;
            $subscription->email = $request->email;
            $subscription->subscription_name = $request->subscription_name;
            $subscription->subscribe_amount = $request->subscribe_amount;
            $subscription->starting_date = $request->starting_date;
            $subscription->ending_date = $request->ending_date;
            $subscription->latest_purchase_date = $request->latest_purchase_date;
            $subscription->request_date = $request->request_date;

            if ($subscription->save()) {
                return response()->json('Subscription save successfully', 200);
            } else {
                return response()->json('Subscription is not successful, please try again.', 500);
            }
        }
    }
}
