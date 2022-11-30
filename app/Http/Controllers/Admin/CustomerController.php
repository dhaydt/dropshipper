<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function customer_list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $customers = User::with(['orders'])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->orWhere('f_name', 'like', "%{$value}%")
                            ->orWhere('l_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%")
                            ->orWhere('email', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $customers = User::with(['orders']);
        }
        $customers = $customers->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.customer.list', compact('customers', 'search'));
    }

    public function status_update(Request $request)
    {
        User::where(['id' => $request['id']])->update([
            'is_active' => $request['status']
        ]);

        DB::table('oauth_access_tokens')
            ->where('user_id', $request['id'])
            ->delete();

        return response()->json([], 200);
    }

    public function view(Request $request, $id)
    {

        $customer = User::find($id);
        if (isset($customer)) {
            $query_param = [];
            $search = $request['search'];
            $orders = Order::where(['customer_id' => $id]);
            if ($request->has('search')) {

                $orders = $orders->where('id', 'like', "%{$search}%");
                $query_param = ['search' => $request['search']];
            }
            $orders = $orders->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
            return view('admin-views.customer.customer-view', compact('customer', 'orders', 'search'));
        }
        Toastr::error('Customer not found!');
        return back();
    }
}
