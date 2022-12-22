<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\Order;
use App\Model\OrderDetail;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function list(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $order_ids = OrderDetail::whereHas('order', function ($q) use ($seller) {
            $q->where(['user_is' => 'dropship', 'customer_id' => $seller['id']]);
        })->pluck('order_id')->toArray();

        return response()->json(Order::whereIn('id', $order_ids)->get(), 200);
    }

    public function details(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $details = OrderDetail::whereHas('order', function ($q) use ($data) {
            $q->where(['customer_id' => $data['data']['id'], 'user_is' => 'dropship']);
        })->where(['order_id' => $id])->get();
        foreach ($details as $det) {
            $det['product_details'] = Helpers::product_data_formatting(json_decode($det['product_details'], true));
        }

        return response()->json($details, 200);
    }

    public function order_detail_status(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $order = Order::find($request->id);

        try {
            $fcm_token = $order->customer->cm_firebase_token;
            $value = Helpers::order_status_update_message($request->order_status);
            if ($value) {
                $notif = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $notif);
            }
        } catch (\Exception $e) {
            return response()->json([]);
        }

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => translate('order is already delivered')], 200);
        }
        $order->order_status = $request->order_status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'seller');
        }

        $order->save();

        return response()->json(['success' => 1, 'message' => translate('order_status_updated_successfully')], 200);
    }

    public function putOrder(Request $request)
    {
        $check = Helpers::get_seller_by_token($request);
        if ($check['success'] == 1) {
            $unique_id = $check['data']['id'].'-'.rand(000001, 999999).'-'.time();
            $order_ids = [];
            $group = Cart::where('cart_group_id', $request->cart_group_id)->get();
            // dd($group);

            foreach ($group as $group_id) {
                $data = [
                    'payment_method' => 'cash_on_delivery',
                    'order_status' => 'pending',
                    'payment_status' => 'unpaid',
                    'transaction_ref' => '',
                    'order_group_id' => $unique_id,
                    'cart_group_id' => $group_id['cart_group_id'],
                    'request' => $request,
                    'api' => true,
                    'user_is' => 'dropship',
                ];
                $order_id = OrderManager::generate_order($data);
                if ($order_id == 'no_shipping') {
                    return response()->json(['status' => 'fail', 'message' => 'No shipping address selected']);
                }
                array_push($order_ids, $order_id);
            }

            foreach ($group as $g) {
                $g->delete();
            }

            return response()->json(['status' => 'success', 'order_id' => $order_id, 'message' => 'order_placed_successfully!']);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'auth-001 unauthorized']);
        }
    }

    public function cancel(Request $request)
    {
        $check = Helpers::get_seller_by_token($request);
        if ($check['success'] == 1) {
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
        } else {
            return response()->json(['status' => 'fail', 'message' => 'auth-001 unauthorized']);
        }
    }
}
