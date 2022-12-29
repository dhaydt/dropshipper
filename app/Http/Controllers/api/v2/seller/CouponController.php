<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function apply(Request $request)
    {
        $check = Helpers::get_seller_by_token($request);
        if ($check['success'] == 1) {
            try {
                $couponLimit = Order::where(['customer_id' => $check['data']['id'], 'user_is' => 'dropship'])
                    ->where('coupon_code', $request['code'])->count();

                $coupon = Coupon::where(['code' => $request['code']])
                    ->where('limit', '>', $couponLimit)
                    ->where('status', '=', 1)
                    ->whereDate('start_date', '<=', Carbon::parse()->toDateString())
                    ->whereDate('expire_date', '>=', Carbon::parse()->toDateString())->first();
                //$coupon = Coupon::where(['code' => $request['code']])->first();
            } catch (\Exception $e) {
                return response()->json(['errors' => $e], 403);
            }

            if ($coupon == null) {
                return response()->json(['status' => 'fail', 'message' => 'coupon not found']);
            }

            return response()->json($coupon, 200);
        } else {
        }
    }
}
