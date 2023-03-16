<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ResiController extends Controller
{
    public function index(Request $request, $status, $user_is)
    {
        $query_param = [];
        $search = $request['search'];
        if ($status != 'all') {
            $orders = Order::with(['customer'])->where(['order_status' => 'processing', 'payment_status' => 'paid'])->where('user_is', $user_is);
        } else {
            $orders = Order::with(['customer'])->where(['order_status' => 'processing', 'payment_status' => 'paid'])->where('user_is', $user_is);
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

        $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('admin-views.resi.list', compact('orders', 'search'));
    }

    public function printResi(Request $request){
        $id = $request->id;
        $order = Order::with('details')->find($id);
        if($order){
            $mpdf_view = \View::make('admin-views.resi.cetak-resi')->with('order', $order);
            // $pdf = Pdf::loadView('admin-views.resi.cetak-resi', compact('order'));
            
            return view('admin-views.resi.cetak-resi', compact('order'));
            Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $id);
        }

        return 'order not found';

    }
    public function status(Request $request)
    {
        $order = Order::find($request['id']);
        if (!$order) {
            return response()->json(['success' => 0, 'message' => 'Order tidak ditemukan']);
        } else {
            if ($order->user_is == 'customer') {
                $fcm_token = $order->customer->cm_firebase_token;
            } else {
                $fcm_token = $order->seller->cm_firebase_token;
            }

            $value = Helpers::order_status_update_message('delivered');

            $order->no_resi = $request['no_resi'];
            $order->order_status = 'delivered';
            $order->save();

            $data = [
                'title' => 'Pesanan anda dalam perjalanan',
                'description' => $value,
                'order_id' => $order['id'],
                'image' => '',
            ];
            Helpers::send_push_notif_to_device($fcm_token, $data);

            return response()->json(['success' => 1, 'message' => 'No Resi berhasil diPerbarui!']);
        }
    }
}
