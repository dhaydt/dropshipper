<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\CartManager;
use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function add_to_cart(Request $request)
    {
        $check = Helpers::get_seller_by_token($request);
        if ($check['success'] == 1) {
            $validator = Validator::make($request->all(), [
                'id' => 'required',
                'quantity' => 'required',
            ], [
                'id.required' => translate('Product ID is required!'),
            ]);

            if ($validator->errors()->count() > 0) {
                return response()->json(['errors' => Helpers::error_processor($validator)]);
            }

            $cart = CartManager::add_to_cart($request);

            return response()->json($cart, 200);
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }
    }
}
