<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\CPU\OrderManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\OrderTransaction;
use App\Model\Seller;
use Barryvdh\DomPDF\Facade\Pdf;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function cetak_resi(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kertas' => 'required',
        ], [
            'kertas.required' => 'Pilih ukuran kertas!',
        ]);

        if ($validator->errors()->count() > 0) {
            $err = Helpers::error_processor($validator);
            foreach ($err as $e) {
                Toastr::error($e['message']);
            }

            return redirect()->back();
        }

        $order = Order::find($request->order_id);
        if (!$order) {
            Toastr::error('Order not found');

            return redirect()->back();
        }
        $kertas = $request->kertas;
        $list = $request->product_list;
        $url = 'storage/resi/';

        $file = 'resi_kurir-order-'.$order['id'].'.pdf';

        // return view('admin-views.order.resi_kurir', ['data' => $url.$order['resi_kurir']);
        $pdf = PDF::loadView('admin-views.order.resi_kurir', ['data' => $url.$order['resi_kurir']])->setPaper('a4');

        return $pdf->download($file);
    }

    public function list(Request $request, $status)
    {
        $query_param = [];
        $search = $request['search'];
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            $query = Order::whereHas('details', function ($query) {
                $query->whereHas('product', function ($query) {
                    $query->where('added_by', 'admin')->where('user_is', 'customer');
                });
            })->with(['customer']);

            if ($status != 'all') {
                $orders = $query->where(['order_status' => $status])->where('user_is', 'customer');
            } else {
                $orders = $query;
            }

            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $orders = $orders->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }
                });
                $query_param = ['search' => $request['search']];
            }
        } else {
            if ($status != 'all') {
                $orders = Order::with(['customer'])->where(['order_status' => $status])->where('user_is', 'customer');
            } else {
                $orders = Order::with(['customer'])->where('user_is', 'customer');
            }

            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $orders = $orders->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }
                });
                $query_param = ['search' => $request['search']];
            }
        }

        $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.order.list', compact('orders', 'search'));
    }

    public function listDropship(Request $request, $status)
    {
        $query_param = [];
        $search = $request['search'];
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            $query = Order::whereHas('details', function ($query) {
                $query->whereHas('product', function ($query) {
                    $query->where('added_by', 'admin')->where('user_is', 'dropship');
                });
            })->with(['customer']);

            if ($status != 'all') {
                $orders = $query->where(['order_status' => $status]);
            } else {
                $orders = $query;
            }

            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $orders = $orders->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }
                });
                $query_param = ['search' => $request['search']];
            }
        } else {
            if ($status != 'all') {
                $orders = Order::with(['customer'])->where(['order_status' => $status])->where('user_is', 'dropship');
            } else {
                $orders = Order::with(['customer'])->where('user_is', 'dropship');
            }

            if ($request->has('search')) {
                $key = explode(' ', $request['search']);
                $orders = $orders->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('id', 'like', "%{$value}%")
                            ->orWhere('order_status', 'like', "%{$value}%")
                            ->orWhere('transaction_ref', 'like', "%{$value}%");
                    }
                });
                $query_param = ['search' => $request['search']];
            }
        }

        $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.order.list', compact('orders', 'search'));
    }

    public function details($id)
    {
        $order = Order::with('details', 'shipping', 'seller')->where(['id' => $id])->first();
        $linked_orders = Order::where(['order_group_id' => $order['order_group_id']])
            ->whereNotIn('order_group_id', ['def-order-group'])
            ->whereNotIn('id', [$order['id']])
            ->get();

        return view('admin-views.order.order-details', compact('order', 'linked_orders'));
    }

    public function status(Request $request)
    {
        $order = Order::find($request->id);
        if ($order->user_is == 'customer') {
            $fcm_token = $order->customer->cm_firebase_token;
        } else {
            $fcm_token = $order->seller->cm_firebase_token;
        }
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
        }

        $order->order_status = $request->order_status;
        OrderManager::stock_update_on_order_status_change($order, $request->order_status);
        $order->save();

        $transaction = OrderTransaction::where(['order_id' => $order['id']])->first();
        if (isset($transaction) && $transaction['status'] == 'disburse') {
            return response()->json($request->order_status);
        }

        if ($request->order_status == 'delivered' && $order['seller_id'] != null) {
            OrderManager::wallet_manage_on_order_status_change($order, 'admin');
        }

        return response()->json($request->order_status);
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

    public function generate_invoice($id)
    {
        $order = Order::with('seller')->with('shipping')->with('details')->where('id', $id)->first();
        $seller = Seller::findOrFail($order->details->first()->seller_id);
        $data['email'] = $order->customer['email'];
        $data['client_name'] = $order->customer['f_name'].' '.$order->customer['l_name'];
        $data['order'] = $order;

        $mpdf_view = \View::make('admin-views.order.invoice')->with('order', $order)->with('seller', $seller);
        Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $order->id);
    }

    public function inhouse_order_filter()
    {
        if (session()->has('show_inhouse_orders') && session('show_inhouse_orders') == 1) {
            session()->put('show_inhouse_orders', 0);
        } else {
            session()->put('show_inhouse_orders', 1);
        }

        return back();
    }
}
