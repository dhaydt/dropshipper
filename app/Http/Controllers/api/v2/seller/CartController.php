<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\CartManager;
use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function cart(Request $request)
    {
        $user = Helpers::get_seller_by_token($request);
        if ($user['success'] == 1) {
            $cart = Cart::where(['customer_id' => $user['data']->id, 'buyer_is' => 'dropship'])->get();
            $cart->map(function ($data) {
                $data['choices'] = json_decode($data['choices']);
                $data['variations'] = json_decode($data['variations']);

                return $data;
            });

            return response()->json($cart, 200);
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }
    }

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

    public function update_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
            'quantity' => 'required',
        ], [
            'key.required' => translate('Cart key or ID is required!'),
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $response = CartManager::update_cart_qty($request);

        return response()->json($response);
    }

    public function remove_from_cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required',
        ], [
            'key.required' => translate('Cart key or ID is required!'),
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $check = Helpers::get_seller_by_token($request);
        if ($check['success'] == 1) {
            $user = $check['data'];
            Cart::where(['id' => $request->key, 'customer_id' => $user->id, 'buyer_is' => 'dropship'])->delete();

            return response()->json(translate('successfully_removed'));
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }
    }
}
