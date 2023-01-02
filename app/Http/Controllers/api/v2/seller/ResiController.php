<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\CartShipping;
use App\Model\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ResiController extends Controller
{
    public function get_kurir(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $cart = CartShipping::where('cart_group_id', $request->cart_group_id)->first();
        if ($cart) {
            return response()->json(['status' => 'success', 'data' => $cart]);
        } else {
            $address = ShippingAddress::where('slug', $request->slug_address)->first();
            $c = new CartShipping();
            $c->cart_group_id = $request->cart_group_id;
            $c->shipping_method_id = 0;
            $c->address_id = $address->id;
            $c->resi_kurir = null;
            $c->invoice_kurir = null;
            $c->shipping_cost = 0;
            $c->shipping_service = null;
            $c->save();

            return response()->json(['status' => 'success', 'data' => $c]);
        }
    }

    public function delete_kurir(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $cart = CartShipping::where('cart_group_id', $request->cart_group_id)->first();
        ImageManager::delete('/resi/'.$cart['resi_kurir']);
        $cart->resi_kurir = null;
        $cart->invoice_kurir = null;
        $cart->save();

        return response()->json(['status' => 'success', 'message' => 'resi kurir deleted successfully']);
    }

    public function set_kurir(Request $request)
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
            'invoice_kurir' => 'required',
            'cart_group_id' => 'required',
            'slug_address' => 'required',
            'type' => 'required',
        ]);

        if ($request->file() == null) {
            return response()->json(['errors' => [
                'code' => 'resi_kurir',
                'message' => 'resi kurir required',
            ]]);
        }

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        $c = CartShipping::where('cart_group_id', $request->cart_group_id)->first();
        if ($c) {
            $c = $c;
        } else {
            $address = ShippingAddress::where('slug', $request->slug_address)->first();
            $c = new CartShipping();
            $c->cart_group_id = $request->cart_group_id;
            $c->shipping_method_id = 0;
            $c->address_id = $address->id;
        }
        $c->resi_kurir = ImageManager::update('resi/', $c->resi_kurir, 'png', $request->file('resi_kurir'));
        $c->invoice_kurir = $request->invoice_kurir;
        $c->shipping_cost = 0;
        $c->shipping_service = null;
        $c->save();

        return response()->json(['status' => 'success', 'message' => 'resi kurir added successfully']);
    }
}
