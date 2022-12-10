<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function index()
    {
        $bank = Helpers::payment();

        return response()->json(['status' => 'success', 'data' => $bank]);
    }

    public function invoice(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'user_is' => 'required',
            'cart_group_id' => 'required',
        ]);
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
        $invoice = Helpers::invoice($request, 'dropship');

        return response()->json(['payment_url' => $invoice]);
    }
}
