<?php

namespace App\CPU;

use App\Model\Cart;
use App\Model\CartShipping;
use App\Model\Color;
use App\Model\Product;
use App\Model\Shop;
use Illuminate\Support\Str;

class CartManager
{
    public static function cart_to_db()
    {
        $user = Helpers::get_customer();
        if (session()->has('offline_cart')) {
            $cart = session('offline_cart');
            $storage = [];
            foreach ($cart as $item) {
                $db_cart = Cart::where(['customer_id' => $user->id, 'seller_id' => $item['seller_id'], 'seller_is' => $item['seller_is']])->first();
                $storage[] = [
                    'customer_id' => $user->id,
                    'cart_group_id' => isset($db_cart) ? $db_cart['cart_group_id'] : str_replace('offline', $user->id, $item['cart_group_id']),
                    'product_id' => $item['product_id'],
                    'color' => $item['color'],
                    'choices' => $item['choices'],
                    'variations' => $item['variations'],
                    'variant' => $item['variant'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'tax' => $item['tax'],
                    'discount' => $item['discount'],
                    'slug' => $item['slug'],
                    'name' => $item['name'],
                    'thumbnail' => $item['thumbnail'],
                    'seller_id' => $item['seller_id'],
                    'seller_is' => $item['seller_is'],
                    'shop_info' => $item['shop_info'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            Cart::insert($storage);
            session()->put('offline_cart', collect([]));
        }
    }

    public static function get_cart($group_id = null, $user_is = null)
    {
        $user = Helpers::get_customer();
        // if ($user_is == 'dropship') {
        //     $cart = Cart::where('cart_group_id', $group_id)->get();
        // }
        // dd(session()->has('offline_cart') && $user == 'offline' && $user_is !== 'dropship');
        if ($user_is !== 'dropship') {
            // dd($check);
            if ($user_is !== 'dropship' && session()->has('offline_cart') && $user == 'offline') {
                $cart = session('offline_cart');
                if ($group_id != null) {
                    return $cart->where('cart_group_id', $group_id)->get();
                } else {
                    return $cart;
                }
            }
        }

        if ($group_id == null) {
            $cart = Cart::whereIn('cart_group_id', CartManager::get_cart_group_ids())->get();
        } else {
            $cart = Cart::where('cart_group_id', $group_id)->get();
        }

        return $cart;
    }

    public static function get_cart_group_ids($request = null)
    {
        $user = Helpers::get_customer($request);
        if ($user == 'offline') {
            if (session()->has('offline_cart') == false) {
                session()->put('offline_cart', collect([]));
            }
            $cart = session('offline_cart');
            $cart_ids = array_unique($cart->pluck('cart_group_id')->toArray());
        } else {
            $cart_ids = Cart::where(['customer_id' => $user->id])->groupBy('cart_group_id')->pluck('cart_group_id')->toArray();
        }

        return $cart_ids;
    }

    public static function get_shipping_cost($group_id = null)
    {
        if ($group_id == null) {
            $cost = CartShipping::whereIn('cart_group_id', CartManager::get_cart_group_ids())->sum('shipping_cost');
        } else {
            $data = CartShipping::where('cart_group_id', $group_id)->first();
            $cost = isset($data) ? $data->shipping_cost : 0;
        }

        return $cost;
    }

    public static function cart_total($cart)
    {
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product_subtotal = $item['price'] * $item['quantity'];
                $total += $product_subtotal;
            }
        }

        return $total;
    }

    public static function cart_total_applied_discount($cart)
    {
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product_subtotal = ($item['price'] - $item['discount']) * $item['quantity'];
                $total += $product_subtotal;
            }
        }

        return $total;
    }

    public static function cart_total_with_tax($cart)
    {
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product_subtotal = ($item['price'] * $item['quantity']) + ($item['tax'] * $item['quantity']);
                $total += $product_subtotal;
            }
        }

        return $total;
    }

    public static function cart_grand_total($cart_group_id = null, $user_is = null)
    {
        $cart = CartManager::get_cart($cart_group_id, $user_is);
        // dd($cart);
        $shipping_cost = CartManager::get_shipping_cost($cart_group_id);
        $total = 0;
        if (!empty($cart)) {
            foreach ($cart as $item) {
                $product_subtotal = ($item['price'] * $item['quantity'])
                    + ($item['tax'] * $item['quantity'])
                    - $item['discount'] * $item['quantity'];
                $total += $product_subtotal;
            }
            $total += $shipping_cost;
        }

        return $total;
    }

    public static function cart_clean($request = null)
    {
        $cart_ids = CartManager::get_cart_group_ids($request);
        CartShipping::whereIn('cart_group_id', $cart_ids)->delete();
        Cart::whereIn('cart_group_id', $cart_ids)->delete();

        session()->forget('coupon_code');
        session()->forget('coupon_discount');
        session()->forget('payment_method');
        session()->forget('shipping_method_id');
        session()->forget('order_id');
        session()->forget('cart_group_id');
    }

    public static function add_to_cart($request, $from_api = false)
    {
        $str = '';
        $variations = [];
        $price = 0;

        $user = Helpers::get_customer($request);
        $type = session()->get('user_is') ? session()->get('user_is') : $request['type'];
        $product = Product::find($request->id);

        //check the color enabled or disabled for the product
        if ($request->has('color')) {
            $str = Color::where('code', $request['color'])->first()->name;
            $variations['color'] = $str;
        }

        //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
        $choices = [];
        foreach (json_decode($product->choice_options) as $key => $choice) {
            $choices[$choice->name] = $request[$choice->name];
            $variations[$choice->title] = $request[$choice->name];
            if ($str != null) {
                $str .= '-'.str_replace(' ', '', $request[$choice->name]);
            } else {
                $str .= str_replace(' ', '', $request[$choice->name]);
            }
        }

        if ($user == 'offline') {
            if (session()->has('offline_cart')) {
                $cart = session('offline_cart');
                $check = $cart->where('product_id', $request->id)->where('variant', $str)->first();
                if (isset($check) == false) {
                    $cart = collect();
                    $cart['id'] = time();
                } else {
                    return [
                        'status' => 0,
                        'message' => translate('already_added!'),
                    ];
                }
            } else {
                $cart = collect();
                session()->put('offline_cart', $cart);
            }
        } else {
            $cart = Cart::where(['product_id' => $request->id, 'customer_id' => $user->id, 'variant' => $str])->first();
            if (isset($cart) == false) {
                $cart = new Cart();
            } else {
                return [
                    'status' => 0,
                    'message' => translate('already_added!'),
                ];
            }
        }

        $cart['color'] = $request->has('color') ? $request['color'] : null;
        $cart['product_id'] = $product->id;
        $cart['choices'] = json_encode($choices);

        //chek if out of stock
        if ($product['current_stock'] < $request['quantity']) {
            return [
                'status' => 0,
                'message' => translate('out_of_stock!'),
            ];
        }

        $cart['variations'] = json_encode($variations);
        $cart['variant'] = $str;

        //Check the string and decreases quantity for the stock
        if ($str != null) {
            $count = count(json_decode($product->variation));
            for ($i = 0; $i < $count; ++$i) {
                if (json_decode($product->variation)[$i]->type == $str) {
                    $price = json_decode($product->variation)[$i]->price;
                    if (json_decode($product->variation)[$i]->qty < $request['quantity']) {
                        return [
                            'status' => 0,
                            'message' => translate('out_of_stock!'),
                        ];
                    }
                }
            }
        } else {
            $price = $product->unit_price;
        }

        if ($type == 'dropship' || $user[0] == 'dropship') {
            $price = $product->dropship;
        }

        $tax = Helpers::tax_calculation($price, $product['tax'], 'percent');

        //generate group id
        if ($user == 'offline') {
            $check = session('offline_cart');
            $cart_check = $check->where('seller_id', $product->user_id)->where('seller_is', $product->added_by)->first();
        } else {
            $cart_check = Cart::where([
                'customer_id' => $user->id,
                'seller_id' => $product->user_id,
                'seller_is' => $product->added_by, ])->first();
        }

        if (isset($cart_check)) {
            $cart['cart_group_id'] = $cart_check['cart_group_id'];
        } else {
            $cart['cart_group_id'] = ($user == 'offline' ? 'offline' : $user->id).'-'.Str::random(5).'-'.time();
        }
        //generate group id end
        if ($type == 'dropship' || $user[0] == 'dropship') {
            $price = $product->dropship;
        }

        $disc = Helpers::get_product_discount($product, $product->unit_price);

        if ($type == 'dropship' || $user[0] == 'dropship') {
            $disc = Helpers::get_product_discount($product, $product->dropship);
        }

        $cart['customer_id'] = $user->id ?? 0;
        $cart['quantity'] = $request['quantity'];
        /*$data['shipping_method_id'] = $shipping_id;*/
        $cart['price'] = $price;
        $cart['tax'] = $tax;
        $cart['slug'] = $product->slug;
        $cart['name'] = $product->name;
        $cart['discount'] = $disc;
        /*$data['shipping_cost'] = $shipping_cost;*/
        $cart['thumbnail'] = $product->thumbnail;
        $cart['seller_id'] = $product->user_id;
        $cart['seller_is'] = $product->added_by;
        if ($product->added_by == 'seller') {
            $cart['shop_info'] = Shop::where(['seller_id' => $product->user_id])->first()->name;
        } else {
            $cart['shop_info'] = Helpers::get_business_settings('company_name');
        }

        if ($user == 'offline') {
            $offline_cart = session('offline_cart');
            $offline_cart->push($cart);
            session()->put('offline_cart', $offline_cart);
        } else {
            $cart['buyer_is'] = $type;
            $cart->save();
        }

        return [
            'status' => 1,
            'message' => translate('successfully_added!'),
        ];
    }

    public static function update_cart_qty($request)
    {
        $user = Helpers::get_customer($request);
        if ($request->type == 'dropship') {
            $check = Helpers::get_seller_by_token($request);
            if ($check['success'] == 1) {
                $user = $check['data'];
            } elseif (auth('seller')->check()) {
                $user = auth('seller')->user();
            } else {
                return response()->json([
                    'auth-001' => translate('Your existing session token does not authorize you any more'),
                ], 401);
            }
        }
        $status = 1;
        $qty = 0;
        if ($request->type == 'dropship') {
            $cart = Cart::where(['id' => $request->key, 'customer_id' => $user->id, 'buyer_is' => 'dropship'])->first();
        } elseif ($request->type == 'customer') {
            $cart = Cart::where(['id' => $request->key, 'customer_id' => $user->id, 'buyer_is' => 'customer'])->first();
        } else {
            $cart = Cart::where(['id' => $request->key, 'customer_id' => $user->id, 'buyer_is' => null])->first();
        }

        $product = Product::find($cart['product_id']);
        $count = count(json_decode($product->variation));
        if ($count) {
            for ($i = 0; $i < $count; ++$i) {
                if (json_decode($product->variation)[$i]->type == $cart['variant']) {
                    if (json_decode($product->variation)[$i]->qty < $request->quantity) {
                        $status = 0;
                        $qty = $cart['quantity'];
                    }
                }
            }
        } elseif ($product['current_stock'] < $request->quantity) {
            $status = 0;
            $qty = $cart['quantity'];
        }

        if ($status) {
            $qty = $request->quantity;
            $cart['quantity'] = $request->quantity;
        }

        $cart->save();

        return [
            'status' => $status,
            'qty' => $qty,
            'message' => $status == 1 ? translate('successfully_updated!') : translate('sorry_stock_is_limited'),
        ];
    }
}
