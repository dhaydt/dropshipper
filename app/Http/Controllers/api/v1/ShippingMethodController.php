<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CartManager;
use App\CPU\Convert;
use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\CartShipping;
use App\Model\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingMethodController extends Controller
{
    public function get_rajaongkir(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_group_id' => 'required',
            'address_id' => 'required',
        ], [
            // 'id.required' => translate('shipping_id_is_required'),
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
        $user = $request->user();
        try {
            // $cart = Cart::where('cart_group_id', $request->cart_group_id)->get();
            // if (count($cart) < 1) {
            //     return response()->json(['status' => 'fail', 'message' => 'Cart not found']);
            // }
            $shipping = Helpers::get_shipping_methods_api($request->cart_group_id, $user->id, $request->address_id);
            if ($shipping == 'fail') {
                return response()->json(['status' => 'fail', 'message' => 'User or Admin address is empty!']);
            } elseif ($shipping == 'no_cart') {
                return response()->json(['status' => 'fail', 'message' => 'Cart Group ID Not Found!']);
            }

            $jne = $shipping[0][0];
            $tiki = $shipping[0][1];
            $cepat = $shipping[0][2];
            $weight = $shipping[2];

            $shippings = [
            'title' => 'Raja Ongkir',
            'Cart_Weight' => $weight.' gram',
            'address_id' => $request->address_id,
            'JNE' => $jne,
            'TIKI' => $tiki,
            'siCepat' => $cepat,
        ];

            // dd($shippings);
            return response()->json($shippings, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function get_shipping_method_info($id)
    {
        try {
            $shipping = ShippingMethod::find($id);

            return response()->json($shipping, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function shipping_methods_by_seller($id, $seller_is)
    {
        $seller_is = $seller_is == 'admin' ? 'admin' : 'seller';
        $type = 'customer';

        return response()->json(Helpers::get_shipping_methods($id, $type, $seller_is), 200);
    }

    public function choose_for_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_group_id' => 'required',
            'id' => 'required_if:service,""',
            'service' => 'required_if:id,""',
            'address_id' => 'required',
        ], [
            // 'id.required' => translate('shipping_id_is_required'),
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        if ($request['cart_group_id'] == 'all_cart_group') {
            foreach (CartManager::get_cart_group_ids($request) as $group_id) {
                $request['cart_group_id'] = $group_id;
                self::insert_into_cart_shipping($request);
            }
        } else {
            self::insert_into_cart_shipping($request);
        }

        return response()->json(translate('successfully_added'));
    }

    public static function insert_into_cart_shipping($request)
    {
        $shipping = CartShipping::where(['cart_group_id' => $request['cart_group_id']])->first();
        if (isset($shipping) == false) {
            $shipping = new CartShipping();
        }

        $shipp = $request['id'];
        $ship = explode(',', $shipp);
        $service = $ship[0];
        $cost = $ship[1];
        $price = Convert::idrTousd($cost);
        $ship_method = 'NULL';
        // $customer =
        // dd($customer);

        // dd($cost);
        // dd($request);
        $shipping['cart_group_id'] = $request['cart_group_id'];
        $shipping['shipping_method_id'] = $ship_method;
        $shipping['shipping_service'] = $service;
        $shipping['shipping_cost'] = round($price, 2);
        $shipping['address_id'] = $request['address_id'];
        $shipping->save();

        // old shipping
        // if (isset($request['id'])) {
        //     $shipping['cart_group_id'] = $request['cart_group_id'];
        //     $shipping['shipping_method_id'] = $request['id'];
        //     $shipping['shipping_cost'] = ShippingMethod::find($request['id'])->cost;
        //     $shipping->save();
        // } else {
        //     $shipping['cart_group_id'] = $request['cart_group_id'];
        //     $shipping['shipping_service'] = $request['service'];
        //     $shipping['shipping_cost'] = $request['cost'];
        //     $shipping->save();
        // }
    }

    public function chosen_shipping_methods(Request $request)
    {
        $group_ids = CartManager::get_cart_group_ids($request);

        return response()->json(CartShipping::whereIn('cart_group_id', $group_ids)->get(), 200);
    }
}
