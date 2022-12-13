<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\RequestProduct;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    public function view($id)
    {
        $product = RequestProduct::where(['id' => $id])->first();
        $reviews = [];

        return view('admin-views.product.request.view', compact('product', 'reviews'));
    }

    public function list(Request $request, $status)
    {
        $query_param = [];
        $search = $request['search'];
        if ($status !== 'all') {
            $pro = RequestProduct::where('status', $request->status);
        } else {
            $pro = new RequestProduct();
        }

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $pro = $pro->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        }

        $request_status = $status;
        $pro = $pro->orderBy('id', 'DESC')->paginate(Helpers::pagination_limit())->appends(['status' => $status])->appends($query_param);

        return view('admin-views.product.request.list', compact('pro', 'search', 'request_status'));
    }

    public function approve_status(Request $request)
    {
        $product = RequestProduct::find($request->id);
        $product->status = 'accepted';
        $product->save();

        Toastr::success('Request Produk '.$product->name.' berhasil diterima');

        return redirect()->route('admin.request.list', ['all']);
    }
}
