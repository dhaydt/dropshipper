<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use Illuminate\Http\Request;

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

    public function status(Request $request)
    {
        $order = Order::find($request['id']);
        if (!$order) {
            return response()->json(['success' => 0, 'message' => 'Order tidak ditemukan']);
        } else {
            $order->no_resi = $request['no_resi'];
            $order->order_status = 'delivered';
            $order->save();

            return response()->json(['success' => 1, 'message' => 'No Resi berhasil diPerbarui!']);
        }
    }
}
