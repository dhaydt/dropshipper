<?php

namespace App\Http\Controllers\Customer;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\CartShipping;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
            'customer_id' => 'required',
        ], [
            'address_id.required' => 'Shipping address id is required!',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        session()->put('customer_id', $request['customer_id']);
        session()->put('address_id', $request['address_id']);
        session()->put('coupon_code', $request['coupon_code']);
        session()->put('payment_mode', 'app');

        $discount = Helpers::coupon_discount($request);
        if ($discount > 0) {
            session()->put('coupon_code', $request['coupon_code']);
            session()->put('coupon_discount', $discount);
        }

        $cart_group_ids = CartManager::get_cart_group_ids();
        if (CartShipping::whereIn('cart_group_id', $cart_group_ids)->count() != count($cart_group_ids)) {
            return response()->json(['errors' => ['code' => 'shipping-method', 'message' => 'Data not found']], 403);
        }

        $customer = User::find($request['customer_id']);

        if (isset($customer)) {
            return view('web-views.mobile-app-view.payment');
        }

        return response()->json(['errors' => ['code' => 'order-payment', 'message' => 'Data not found']], 403);
    }

    public function success()
    {
        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function fail()
    {
        return response()->json(['message' => 'Payment failed'], 403);
    }
}
