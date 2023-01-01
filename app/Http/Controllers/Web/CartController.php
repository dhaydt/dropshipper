<?php

namespace App\Http\Controllers\Web;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\CartShipping;
use App\Model\Color;
use App\Model\Product;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function delete_resi($group_id)
    {
        $cart = CartShipping::where('cart_group_id', $group_id)->first();
        ImageManager::delete('/resi/'.$cart['resi_kurir']);
        $cart->resi_kurir = null;
        $cart->invoice_kurir = null;
        $cart->save();

        Toastr::success('Resi berhasil dihapus!');

        return redirect()->back();
    }

    public function upload_resi(Request $request)
    {
        $cart = CartShipping::where('cart_group_id', $request['cart_group_id'])->get();
        foreach ($cart as $c) {
            $c->resi_kurir = ImageManager::update('resi/', $c->resi_kurir, 'png', $request->file('resi_kurir'));
            $c->invoice_kurir = $request->invoice_kurir;
            $c->shipping_cost = 0;
            $c->shipping_service = null;
            $c->save();
        }
        Toastr::success('Resi Kurir berhasil ditambahkan!');

        return redirect()->back();
    }

    public function variant_price(Request $request)
    {
        $type = session()->get('user_is');
        $product = Product::find($request->id);
        $str = '';
        $quantity = 0;
        $price = 0;

        if ($request->has('color')) {
            $str = Color::where('code', $request['color'])->first()->name;
        }

        foreach (json_decode(Product::find($request->id)->choice_options) as $key => $choice) {
            if ($str != null) {
                $str .= '-'.str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if ($str != null) {
            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; ++$i) {
                if (json_decode($product->variation)[$i]->type == $str) {
                    $tax = Helpers::tax_calculation(json_decode($product->variation)[$i]->price, $product['tax'], $product['tax_type']);
                    $discount = Helpers::get_product_discount($product, json_decode($product->variation)[$i]->price);
                    $price = json_decode($product->variation)[$i]->price - $discount + $tax;
                    $quantity = json_decode($product->variation)[$i]->qty;
                }
            }
        } else {
            $tax = Helpers::tax_calculation($product->unit_price, $product['tax'], $product['tax_type']);
            $discount = Helpers::get_product_discount($product, $product->unit_price);
            $price = $product->unit_price - $discount + $tax;

            if ($type == 'dropship') {
                $discount = Helpers::get_product_discount($product, $product->dropship);
                $price = $product->dropship - $discount + $tax;
            }
            $quantity = $product->current_stock;
        }

        return [
            'price' => \App\CPU\Helpers::currency_converter($price * $request->quantity),
            'discount' => \App\CPU\Helpers::currency_converter($discount),
            'tax' => \App\CPU\Helpers::currency_converter($tax),
            'quantity' => $quantity,
        ];
    }

    public function addToCart(Request $request)
    {
        $cart = CartManager::add_to_cart($request);
        session()->forget('coupon_code');
        session()->forget('coupon_discount');

        return response()->json($cart);
    }

    public function updateNavCart()
    {
        return response()->json(['data' => view('layouts.front-end.partials.cart')->render()]);
    }

    //removes from Cart
    public function removeFromCart(Request $request)
    {
        $user = Helpers::get_customer();
        // dd($user->id);
        if ($user == 'offline') {
            if (session()->has('offline_cart') == false) {
                session()->put('offline_cart', collect([]));
            }
            $cart = session('offline_cart');

            $new_collection = collect([]);
            foreach ($cart as $item) {
                if ($item['id'] != $request->key) {
                    $new_collection->push($item);
                }
            }

            session()->put('offline_cart', $new_collection);

            return response()->json($new_collection);
        } else {
            if (session()->get('user_is') == 'dropship') {
                Cart::where(['id' => $request->key, 'customer_id' => $user->id])->delete();
            } else {
                Cart::where(['id' => $request->key, 'customer_id' => auth('customer')->id()])->delete();
            }
        }

        session()->forget('coupon_code');
        session()->forget('coupon_discount');
        session()->forget('shipping_method_id');

        return response()->json(['data' => view('layouts.front-end.partials.cart_details')->render()]);
    }

    //updated the quantity for a cart item
    public function updateQuantity(Request $request)
    {
        $response = CartManager::update_cart_qty($request);

        // return response()->json($response);

        session()->forget('coupon_code');
        session()->forget('coupon_discount');

        if ($response['status'] == 0) {
            return response()->json($response);
        }

        return response()->json(view('layouts.front-end.partials.cart_details')->render());
    }
}
