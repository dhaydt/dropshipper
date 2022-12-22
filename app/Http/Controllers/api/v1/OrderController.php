<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function track_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json(OrderManager::track_order($request['order_id']), 200);
    }

    public function place_order(Request $request)
    {
        $unique_id = $request->user()->id.'-'.rand(000001, 999999).'-'.time();
        $order_ids = [];
        foreach (CartManager::get_cart_group_ids($request) as $group_id) {
            $data = [
                'payment_method' => 'cash_on_delivery',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'transaction_ref' => '',
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
                'request' => $request,
                'api' => true,
                'user_is' => 'customer',
            ];
            $order_id = OrderManager::generate_order($data);
            if ($order_id == 'no_shipping') {
                return response()->json(['status' => 'fail', 'message' => 'No shipping address selected']);
            }
            array_push($order_ids, $order_id);
        }

        CartManager::cart_clean($request);

        return response()->json(['status' => 'success', 'order_id' => $order_id, 'message' => 'order_placed_successfully!']);
    }

    public function cancel(Request $request)
    {
        $id = $request['order_id'];
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['Order tidak ditemukan'], 200);
        }
        if ($order['payment_method'] == 'cash_on_delivery' && $order['order_status'] == 'pending') {
            OrderManager::stock_update_on_order_status_change($order, 'canceled');
            Order::where('id', $id)->update([
                'order_status' => 'canceled',
            ]);

            return response()->json(['Order berhasil dibatalkan'], 200);
        }

        return response()->json(['Gagal membatalkan order karena sudah diproses atau sudah dibayar'], 200);
    }
}
