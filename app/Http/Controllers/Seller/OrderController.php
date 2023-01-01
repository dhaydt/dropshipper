<?php

namespace App\Http\Controllers\Seller;

use App\CPU\Helpers;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\Seller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function list(Request $request, $status)
    {
        $sellerId = auth('seller')->id();
        if ($status != 'all') {
            $orders = Order::where(['user_is' => 'dropship', 'customer_id' => auth('seller')->id()])->where(['order_status' => $status]);
        } else {
            $orders = Order::where(['user_is' => 'dropship', 'customer_id' => auth('seller')->id()]);
        }

        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $orders = $orders->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('id', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }
        $payment = Helpers::payment();
        $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('seller-views.order.list', compact('orders', 'search', 'payment'));
    }

    public function details($id)
    {
        $sellerId = auth('seller')->id();
        $order = Order::with(['details', 'customer', 'shipping'])->where(['id' => $id, 'user_is' => 'dropship'])->first();

        return view('seller-views.order.order-details', compact('order'));
    }

    public function generate_invoice($id)
    {
        $sellerId = auth('seller')->id();
        $seller = Seller::findOrFail($sellerId);

        $order = Order::with(['details' => function ($query) use ($sellerId) {
            $query->where('seller_id', $sellerId);
        }])->with('customer', 'shipping')
            ->with('seller')
            ->where('id', $id)->first();

        // $data['email'] = $order->customer['email'];
        // $data['client_name'] = $order->customer['f_name'].' '.$order->customer['l_name'];
        $data['order'] = $order;

        // $mpdf_view = \View::make('seller-views.order.invoice')->with('order', $order)->with('seller', $seller);
        // Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $order->id);
        return view('seller-views.order.invoice')->with('order', $order)->with('seller', $seller);
    }

    public function payment_status(Request $request)
    {
        if ($request->ajax()) {
            $order = Order::find($request->id);
            $order->payment_status = $request->payment_status;
            $order->save();
            $data = $request->payment_status;

            return response()->json($data);
        }
    }

    public function status(Request $request)
    {
        $order = Order::find($request->id);
        $fcm_token = $order->customer->cm_firebase_token;
        $value = Helpers::order_status_update_message($request->order_status);
        try {
            if ($value) {
                $data = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        } catch (\Exception $e) {
            return response()->json([]);
        }

        if ($order->order_status == 'delivered') {
            return response()->json(['success' => 0, 'message' => 'order is already delivered.'], 200);
        }
        $order->order_status = $request->order_status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'seller');
        }

        $order->save();
        $data = $request->order_status;

        return response()->json($data);
    }
}
