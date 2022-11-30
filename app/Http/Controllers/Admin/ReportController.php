<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function order_index()
    {
        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        return view('admin-views.report.order-index');
    }

    public function earning_index()
    {
        if (session()->has('from_date') == false) {
            session()->put('from_date', date('Y-m-01'));
            session()->put('to_date', date('Y-m-30'));
        }
        return view('admin-views.report.earning-index');
    }

    public function set_date(Request $request)
    {
        session()->put('from_date', date('Y-m-d', strtotime($request['from'])));
        session()->put('to_date', date('Y-m-d', strtotime($request['to'])));
        return back();
    }
}
