<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Order;

class ResiController extends Controller
{
    public function index()
    {
        $order = Order::where('order_status', 'processing')->get();
    }
}
