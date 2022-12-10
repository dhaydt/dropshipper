<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $bank = Helpers::payment();

        return response()->json(['status' => 'success', 'data' => $bank]);
    }

    public function invoice(Request $request)
    {
        $invoice = Helpers::invoice($request);

        return response()->json(['payment_url' => $invoice]);
    }
}
