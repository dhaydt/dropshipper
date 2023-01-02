<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\BackEndHelper;
use App\CPU\CartManager;
use App\CPU\Convert;
use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\CartShipping;
use App\Model\ShippingAddress;
use App\Model\ShippingMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ShippingMethodController extends Controller
{
    public function get_rajaongkir(Request $request)
    {
        $check = Helpers::get_seller_by_token($request);
        if ($check['success'] == 1) {
            $validator = Validator::make($request->all(), [
                'cart_group_id' => 'required',
                'slug_address' => 'required',
            ], [
                // 'id.required' => translate('shipping_id_is_required'),
            ]);

            if ($validator->errors()->count() > 0) {
                return response()->json(['errors' => Helpers::error_processor($validator)]);
            }

            $user = $check['data'];
            try {
                $shipping = Helpers::get_shipping_methods_api($request->cart_group_id, $request->slug_address);
                $jne = $shipping[0][0];
                $tiki = $shipping[0][1];
                $cepat = $shipping[0][2];
                $weight = $shipping[2];

                $shippings = [
                    'title' => 'Raja Ongkir',
                    'Cart_Weight' => $weight.' gram',
                    'JNE' => $jne,
                    'TIKI' => $tiki,
                    'siCepat' => $cepat,
                ];

                // dd($shippings);
                return response()->json($shippings, 200);
            } catch (\Exception $e) {
                return response()->json(['errors' => $e], 403);
            }
        } else {
            return response()->json(['status' => 'auth-001', 'message' => 'unauthorized']);
        }
    }

    public function choose_for_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cart_group_id' => 'required',
            'id' => 'required_if:service,""',
            'service' => 'required_if:id,""',
            'type' => 'required',
            'slug_address' => 'required',
        ], [
            'type' => 'Required user type customer or dropshipper',
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
        if ($shipping !== null) {
            $cost = 0;
        }
        $price = Convert::idrTousd($cost);
        $ship_method = 'NULL';
        // $customer =
        // dd($customer);

        // dd($cost);
        // dd($request);
        $address = ShippingAddress::where('slug', $request['slug_address'])->first();
        $shipping['cart_group_id'] = $request['cart_group_id'];
        $shipping['shipping_method_id'] = $ship_method;
        $shipping['shipping_service'] = $service;
        $shipping['shipping_cost'] = round($price, 2);
        $shipping['address_id'] = $address['id'];
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
        $validator = Validator::make($request->all(), [
            'type' => 'required',
        ], [
            'type' => 'Required user type customer or dropshipper',
        ]);
        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }
        $group_ids = CartManager::get_cart_group_ids($request);

        return response()->json(CartShipping::whereIn('cart_group_id', $group_ids)->get(), 200);
    }

    public function store(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'duration' => 'required',
            'cost' => 'numeric',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        DB::table('shipping_methods')->insert([
            'creator_id' => $seller['id'],
            'creator_type' => 'seller',
            'title' => $request['title'],
            'duration' => $request['duration'],
            'cost' => BackEndHelper::currency_to_usd($request['cost']),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => translate('successfully_added_shipping_address')], 200);
    }

    public function list(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        return response()->json(ShippingMethod::where(['creator_type' => 'seller', 'creator_id' => $seller['id']])->get(), 200);
    }

    public function status_update(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'status' => 'required|in:1,0',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        ShippingMethod::where(['id' => $request['id'], 'creator_id' => $seller['id']])->update([
            'status' => $request['status'],
        ]);

        return response()->json(['message' => translate('successfully_status_updated')], 200);
    }

    public function edit(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }
        $method = ShippingMethod::where(['id' => $id, 'creator_id' => $seller['id']])->first();
        if (isset($method)) {
            return response()->json($method, 200);
        }

        return response()->json(['message' => translate('data_not_found')], 200);
    }

    public function update(Request $request, $id)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|max:200',
            'duration' => 'required',
            'cost' => 'numeric',
        ]);

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        DB::table('shipping_methods')->where(['id' => $id, 'creator_id' => $seller['id']])->update([
            'title' => $request['title'],
            'duration' => $request['duration'],
            'cost' => BackEndHelper::currency_to_usd($request['cost']),
            'status' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => translate('successfully_updated')], 200);
    }

    public function delete(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        ShippingMethod::where(['id' => $request->id, 'creator_id' => $seller['id']])->delete();

        return response()->json(['message' => translate('successfully_deleted')], 200);
    }
}
