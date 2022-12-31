<?php

namespace App\CPU;

use App\Country;
use App\Model\Admin;
use App\Model\BusinessSetting;
use App\Model\Cart;
use App\Model\Category;
use App\Model\Color;
use App\Model\Coupon;
use App\Model\Currency;
use App\Model\FlashDealProduct;
use App\Model\Order;
use App\Model\Product;
use App\Model\Review;
use App\Model\Seller;
use App\Model\ShippingAddress;
use App\Model\ShippingMethod;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Xendit\Xendit;

class Helpers
{
    public static function directWa($data)
    {
        $main = 'https://api.whatsapp.com/send?phone=';
        $phone = $data['phone'];
        $product = ucwords($data['product']);
        $product = urlencode(substr($product, 0, 50));
        $price = 'Rp.'.number_format($data['price']);
        $link = $data['link'];
        $url = $main.$phone.'&text='.$product.'%0A'.$price;

        return $url;
    }

    public static function getDropship($id)
    {
        $dropship = Seller::find($id);

        return $dropship;
    }

    public static function Oldinvoice($request, $user_is)
    {
        if ($request->user_is == 'customer') {
            $customer = $request->user();
        } elseif ($request->user_is == 'dropship') {
            $check = Helpers::get_seller_by_token($request);
            if ($check['success'] == 1) {
                $customer = $check['data'];
            } else {
                return response()->json(['status' => 'auth-001', 'message' => 'not authorized user']);
            }
        }
        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $value = CartManager::cart_grand_total($request->cart_group_id) - $discount;
        $tran = OrderManager::gen_unique_id();
        $type = strtoupper($request['type']);
        session()->put('transaction_ref', $tran);

        Xendit::setApiKey(config('xendit.apikey'));

        $products = [];
        foreach (CartManager::get_cart() as $detail) {
            array_push($products, [
                'name' => $detail->product['name'],
            ]);
        }
        // dd($products);
        if ($request->user_is == 'customer') {
            $name = $customer->name ? $customer->name : $customer->f_name;
            $phone = $customer->phone;
            $address = $customer->district.', '.$customer->city.', '.$customer->province;
            $id = $customer->id;
            $email = $customer->email;
        }
        if ($request->user_is == 'dropship') {
            $custom = ShippingAddress::where('slug', session()->get('customer_address'))->first();
            $name = $custom->contact_person_name;
            $phone = $custom->phone;
            $address = $custom->district.', '.$custom->city.', '.$custom->province;
            $id = $custom->customer_id;
            $email = 'dropshipper@ezren.id';
        }

        $user = [
            'given_names' => $name ? $name : 'ezren_customer',
            'email' => $email,
            'mobile_number' => $phone,
            'address' => $address,
        ];

        // dd($user);
        $group = $request->cart_group_id;

        $params = [
            'external_id' => 'ezren'.$phone.$id,
            'amount' => $value,
            'payer_email' => $email,
            'description' => env('APP_NAME'),
            'payment_methods' => [$type],
            'fixed_va' => true,
            'should_send_email' => true,
            'customer' => $user,
            // 'items' => $products,
            'success_redirect_url' => 'https://ezren.id'.'/xendit-payment/success-api/'.$type.'/'.$group.'/'.$user_is,
        ];
        // dd($params);
        $checkout_session = \Xendit\Invoice::create($params);
        // $order_ids = [];
        // foreach (CartManager::get_cart_group_ids() as $group_id) {
        //     $data = [
        //         'payment_method' => 'xendit_payment',
        //         'order_status' => 'pending',
        //         'payment_status' => 'unpaid',
        //         'transaction_ref' => session('transaction_ref'),
        //         'order_group_id' => $tran,
        //         'cart_group_id' => $group_id,
        //     ];
        //     $order_id = OrderManager::generate_order($data);
        //     array_push($order_ids, $order_id);
        // }

        return response()->json($checkout_session['invoice_url']);
    }

    public static function invoice($request, $user_is)
    {
        if ($request->user_is == 'customer') {
            $customer = $request->user();
        } elseif ($request->user_is == 'dropship') {
            $check = Helpers::get_seller_by_token($request);
            if ($check['success'] == 1) {
                $customer = $check['data'];
            } else {
                return response()->json(['status' => 'auth-001', 'message' => 'not authorized user']);
            }
        }
        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        // $value = CartManager::cart_grand_total($request->cart_group_id) - $discount;
        $value = Order::find($request->order_id)['order_amount'];
        // dd($value);
        $tran = OrderManager::gen_unique_id();
        $type = strtoupper($request['type']);
        session()->put('transaction_ref', $tran);

        Xendit::setApiKey(config('xendit.apikey'));

        $products = [];
        foreach (CartManager::get_cart() as $detail) {
            array_push($products, [
                'name' => $detail->product['name'],
            ]);
        }
        if ($request->user_is == 'customer') {
            $name = $customer->name ? $customer->name : $customer->f_name;
            $phone = $customer->phone;
            $address = $customer->district.', '.$customer->city.', '.$customer->province;
            $id = $customer->id;
            $email = $customer->email;
        }
        if ($request->user_is == 'dropship') {
            $custom = ShippingAddress::where('slug', session()->get('customer_address'))->first();
            $name = $customer->f_name;
            $phone = $customer->phone;
            $address = 'no_data';
            $id = $customer->id;
            $email = $customer->email ? $customer->email : 'invalid@customer.email';
        }

        $user = [
            'given_names' => $name ? $name : 'ezren_customer',
            'email' => $email,
            'mobile_number' => $phone,
            'address' => $address,
        ];

        $group = $request->order_id;

        $params = [
            'external_id' => 'ezren'.$phone.$id,
            'amount' => $value,
            'payer_email' => $email,
            'description' => env('APP_NAME') ? env('APP_NAME') : 'Ezren Paymnet',
            'payment_methods' => [$type],
            'fixed_va' => true,
            'should_send_email' => true,
            'customer' => $user,
            // 'items' => $products,
            'success_redirect_url' => 'https://ezren.id'.'/xendit-payment/success-api/'.$type.'/'.$group.'/'.$user_is,
        ];
        // dd($params);
        $checkout_session = \Xendit\Invoice::create($params);
        // $order_ids = [];
        // foreach (CartManager::get_cart_group_ids() as $group_id) {
        //     $data = [
        //         'payment_method' => 'xendit_payment',
        //         'order_status' => 'pending',
        //         'payment_status' => 'unpaid',
        //         'transaction_ref' => session('transaction_ref'),
        //         'order_group_id' => $tran,
        //         'cart_group_id' => $group_id,
        //     ];
        //     $order_id = OrderManager::generate_order($data);
        //     array_push($order_ids, $order_id);
        // }

        return response()->json($checkout_session['invoice_url']);
    }

    public static function payment()
    {
        Xendit::setApiKey(config('xendit.apikey'));

        // $createVA = \Xendit\VirtualAccounts::create($params);
        // var_dump($createVA);
        $bank = \Xendit\VirtualAccounts::getVABanks();

        return $bank;
    }

    public static function district($id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => config('rajaongkir.url').'/subdistrict?city='.$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'key:'.config('rajaongkir.api_key'),
            ],
        ]);

        $resp = curl_exec($curl);
        $err = curl_error($curl);
        $resp = json_decode($resp, true);
        $data = $resp['rajaongkir']['results'];

        // dd($prov);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:'.$err;
        } else {
            return $data;
        }
    }

    public static function city($id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => config('rajaongkir.url').'/city?&province='.$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'key:'.config('rajaongkir.api_key'),
            ],
        ]);

        $resp = curl_exec($curl);
        $err = curl_error($curl);
        $resp = json_decode($resp, true);
        $data = $resp['rajaongkir']['results'];

        // dd($prov);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:'.$err;
        } else {
            return $data;
        }
    }

    public static function status($id)
    {
        if ($id == 1) {
            $x = 'active';
        } elseif ($id == 0) {
            $x = 'in-active';
        }

        return $x;
    }

    public static function getProductDealsImg($cat_id, $deal_id)
    {
        $deal = FlashDealProduct::where(['flash_deal_id' => $deal_id, 'category_id' => $cat_id])->first();

        return $deal;
    }

    public static function country()
    {
        // $country = Product::with('country')->whereIn('country', 'country');
        $country = Country::with('product')->has('product')->get();

        // all()->unique('country');
        // ->pluck('country');
        // dd($country);

        return $country;
    }

    public static function province()
    {
        session()->put('keep_return_url', url()->previous());
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => config('rajaongkir.url').'/province',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'key:'.config('rajaongkir.api_key'),
            ],
        ]);

        $resp = curl_exec($curl);
        $err = curl_error($curl);
        $resp = json_decode($resp, true);
        $prov = $resp['rajaongkir']['results'];

        // dd($prov);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:'.$err;
        } else {
            return $prov;
        }
    }

    public static function transaction_formatter($transaction)
    {
        if ($transaction['paid_by'] == 'customer') {
            $user = User::find($transaction['payer_id']);
            $payer = $user->f_name.' '.$user->l_name;
        } elseif ($transaction['paid_by'] == 'seller') {
            $user = Seller::find($transaction['payer_id']);
            $payer = $user->f_name.' '.$user->l_name;
        } elseif ($transaction['paid_by'] == 'admin') {
            $user = Admin::find($transaction['payer_id']);
            $payer = $user->name;
        }

        if ($transaction['paid_to'] == 'customer') {
            $user = User::find($transaction['payment_receiver_id']);
            $receiver = $user->f_name.' '.$user->l_name;
        } elseif ($transaction['paid_to'] == 'seller') {
            $user = Seller::find($transaction['payment_receiver_id']);
            $receiver = $user->f_name.' '.$user->l_name;
        } elseif ($transaction['paid_to'] == 'admin') {
            $user = Admin::find($transaction['payment_receiver_id']);
            $receiver = $user->name;
        }

        $transaction['payer_info'] = $payer;
        $transaction['receiver_info'] = $receiver;

        return $transaction;
    }

    public static function get_customer($request = null)
    {
        $user = null;
        if (auth('customer')->check()) {
            $user = auth('customer')->user(); // for web
        } elseif ($request != null && $request->user() != null) {
            $user = $request->user(); //for api
        } elseif (session()->has('customer_id')) {
            $user = User::find(session('customer_id'));
        } elseif (session()->get('user_is') == 'dropship') {
            $user = auth('seller')->user();
        }

        if ($request != null) {
            $type = $request ? $request->type : null;
            if (session()->get('user_is') == 'dropship' || $type == 'dropship') {
                if (auth('seller')->check() == false) {
                    $check = Helpers::get_seller_by_token($request);
                    if ($check['success'] == 1) {
                        $user = $check['data'];
                    }
                }
            }
        }

        if ($user == null) {
            $user = 'offline';
        }

        return $user;
    }

    public static function coupon_discount($request)
    {
        $discount = 0;
        $check = Helpers::get_seller_by_token($request);
        $is = 'customer';
        $user = Helpers::get_customer($request);
        if ($check['success'] == 1) {
            $user = $check['data'];
            $is = 'dropship';
        }
        $couponLimit = Order::where(['customer_id' => $user->id, 'user_is' => $is])
            ->where('coupon_code', $request['coupon_code'])->count();

        $coupon = Coupon::where(['code' => $request['coupon_code']])
            ->where('limit', '>', $couponLimit)
            ->where('status', '=', 1)
            ->whereDate('start_date', '<=', Carbon::parse()->toDateString())
            ->whereDate('expire_date', '>=', Carbon::parse()->toDateString())->first();

        if (isset($coupon)) {
            $total = 0;
            foreach (CartManager::get_cart(CartManager::get_cart_group_ids($request)) as $cart) {
                $product_subtotal = $cart['price'] * $cart['quantity'];
                $total += $product_subtotal;
            }
            if ($total >= $coupon['min_purchase']) {
                if ($coupon['discount_type'] == 'percentage') {
                    $discount = (($total / 100) * $coupon['discount']) > $coupon['max_discount'] ? $coupon['max_discount'] : (($total / 100) * $coupon['discount']);
                } else {
                    $discount = $coupon['discount'];
                }
            }
        }

        return $discount;
    }

    public static function default_lang()
    {
        if (strpos(url()->current(), '/api')) {
            $lang = App::getLocale();
        } elseif (session()->has('local')) {
            $lang = session('local');
        } else {
            $data = Helpers::get_business_settings('language');
            $code = 'en';
            $direction = 'ltr';
            foreach ($data as $ln) {
                if (array_key_exists('default', $ln) && $ln['default']) {
                    $code = $ln['code'];
                    if (array_key_exists('direction', $ln)) {
                        $direction = $ln['direction'];
                    }
                }
            }
            session()->put('local', $code);
            Session::put('direction', $direction);
            $lang = $code;
        }

        return $lang;
    }

    public static function rating_count($product_id, $rating)
    {
        return Review::where(['product_id' => $product_id, 'rating' => $rating])->count();
    }

    public static function get_business_settings($name)
    {
        $config = null;
        $check = ['currency_model', 'currency_symbol_position', 'system_default_currency', 'language', 'company_name'];

        if (in_array($name, $check) == true && session()->has($name)) {
            $config = session($name);
        } else {
            $data = BusinessSetting::where(['type' => $name])->first();
            if (isset($data)) {
                $config = json_decode($data['value'], true);
                if (is_null($config)) {
                    $config = $data['value'];
                }
            }

            if (in_array($name, $check) == true) {
                session()->put($name, $config);
            }
        }

        return $config;
    }

    public static function get_settings($object, $type)
    {
        $config = null;
        foreach ($object as $setting) {
            if ($setting['type'] == $type) {
                $config = $setting;
            }
        }

        return $config;
    }

    public static function get_shipping_methods($cart_group_id = null)
    {
        $admin = BusinessSetting::where('type', 'address')->first();
        $admin = json_decode($admin->value);
        if ($admin->district_id == '') {
            return 'fail';
        }
        $id = auth('customer')->id();
        // dd($id);
        // $user = User::find($id);
        $user = ShippingAddress::find(session()->get('address_id'));
        if ($user->district_id == null || $user->district_id == '') {
            return 'fail';
        }
        $to_district = $user->district_id ? $user->district_id : null;
        $to_type = $user->city_type;
        $carts = Cart::where('cart_group_id', $cart_group_id)->get();
        if (count($carts) < 1) {
            return 'no_cart';
        }
        $product_ids = $carts->pluck('product_id');
        $qty_cart = $carts->pluck('quantity');

        $weights = [];
        foreach ($product_ids as $key => $id) {
            $weight = Product::find($id)->weight * $qty_cart[$key];
            array_push($weights, $weight);
        }
        $weight = count($weights) > 0 ? array_sum($weights) : 1;
        $weight = $weight == 0 ? 1 : $weight;
        // dd($weight);

        $from_city = $admin->city_id ? $admin->city_id : '151';
        $from_type = 'Kota';
        $from_type = 'Kota';
        // $from_state = '21';
        $ShippingMethod = 'Raja Ongkir';

        $curl = curl_init();
        // JNE
        curl_setopt_array($curl, [
            CURLOPT_URL => config('rajaongkir.url').'/cost',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            // CURLOPT_POSTFIELDS => 'origin='.$from_city.'&originType='.$from_type.'&destination='.$to_city.'&destinationType='.$to_type.'&weight='.$weight.'&courier=jne',
            CURLOPT_POSTFIELDS => 'origin='.$from_city.'&originType=city&destination='.$to_district.'&destinationType=subdistrict&weight='.$weight.'&courier=jne',
            CURLOPT_HTTPHEADER => [
                'content-type: application/x-www-form-urlencoded',
                'key:'.config('rajaongkir.api_key'),
            ],
        ]);

        $responseJne = curl_exec($curl);
        $errJne = curl_error($curl);

        curl_close($curl);

        if ($errJne) {
            echo 'cURL Error #:'.$errJne;
        } else {
            $response = json_decode($responseJne, true);
            $data_ongkir = $response['rajaongkir']['results'];
            // $data_ongkir = $response;

            // $jne = json_encode($data_ongkir);
            // dd($data_ongkir);

            // return with([$data_ongkir, $ShippingMethod]);
        }

        $curl = curl_init();
        // SICEPAT
        curl_setopt_array($curl, [
            CURLOPT_URL => config('rajaongkir.url').'/cost',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            // CURLOPT_POSTFIELDS => 'origin='.$from_city.'&originType='.$from_type.'&destination='.$to_city.'&destinationType='.$to_type.'&weight='.$weight.'&courier=jne',
            CURLOPT_POSTFIELDS => 'origin='.$from_city.'&originType=city&destination='.$to_district.'&destinationType=subdistrict&weight='.$weight.'&courier=sicepat',
            CURLOPT_HTTPHEADER => [
                'content-type: application/x-www-form-urlencoded',
                'key:'.config('rajaongkir.api_key'),
            ],
        ]);

        $responseSicepat = curl_exec($curl);
        $errSicepat = curl_error($curl);

        curl_close($curl);

        if ($errSicepat) {
            echo 'cURL Error #:'.$errSicepat;
        } else {
            $response = json_decode($responseSicepat, true);
            $sicepat = $response['rajaongkir']['results'];
            // $data_ongkir = $response;

            // $jne = json_encode($data_ongkir);
            // dd($sicepat);

            // return with([$data_ongkir, $ShippingMethod]);
        }

        // TIKI
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => config('rajaongkir.url').'/cost',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'origin='.$from_city.'&originType=city&destination='.$to_district.'&destinationType=subdistrict&weight='.$weight.'&courier=tiki',
            CURLOPT_HTTPHEADER => [
                'content-type: application/x-www-form-urlencoded',
                'key:'.config('rajaongkir.api_key'),
            ],
        ]);

        $responseTiki = curl_exec($curl);
        $errTiki = curl_error($curl);

        curl_close($curl);

        if ($errTiki) {
            echo 'cURL Error #:'.$errTiki;
        } else {
            $response = json_decode($responseTiki, true);
            $tiki = $response['rajaongkir']['results'];

            // $jne = json_encode($data_ongkir);
            // dd($data_ongkir);

            return with([[$data_ongkir, $tiki, $sicepat], $ShippingMethod]);
        }
    }

    public static function get_shipping_methods_api($cart_group_id, $user_id, $address_id = null)
    {
        $admin = BusinessSetting::where('type', 'address')->first();
        $admin = json_decode($admin->value);
        if ($admin->district_id == '') {
            return 'fail';
        }

        $user = User::find($user_id);
        if ($address_id == null) {
            $user = ShippingAddress::where('slug', $user_id)->first();
        } else {
            $user = ShippingAddress::find($address_id);
        }
        if ($user->district_id == null || $user->district_id == '') {
            return 'fail';
        }
        $to_district = $user->district_id;
        $carts = Cart::where('cart_group_id', $cart_group_id)->get();
        if (count($carts) < 1) {
            return 'no_cart';
        }
        $product_ids = $carts->pluck('product_id');
        $qty_cart = $carts->pluck('quantity');

        $weights = [];
        foreach ($product_ids as $key => $id) {
            $weight = Product::find($id)->weight * $qty_cart[$key];
            array_push($weights, $weight);
        }
        $weight = count($weights) > 0 ? array_sum($weights) : '1';

        $from_city = $admin->city_id ? $admin->city_id : '151';

        // $ShippingMethod = ShippingMethod::where(['status' => 1])->where(['creator_id' => $seller_id, 'creator_type' => $type])->get();
        $ShippingMethod = 'Raja Ongkir';

        $curl = curl_init();
        // JNE
        curl_setopt_array($curl, [
            CURLOPT_URL => config('rajaongkir.url').'/cost',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            // CURLOPT_POSTFIELDS => 'origin='.$from_city.'&originType='.$from_type.'&destination='.$to_city.'&destinationType='.$to_type.'&weight='.$weight.'&courier=jne',
            CURLOPT_POSTFIELDS => 'origin='.$from_city.'&originType=city&destination='.$to_district.'&destinationType=subdistrict&weight='.$weight.'&courier=jne',
            CURLOPT_HTTPHEADER => [
                'content-type: application/x-www-form-urlencoded',
                'key:'.config('rajaongkir.api_key'),
            ],
        ]);

        $responseJne = curl_exec($curl);
        $errJne = curl_error($curl);

        curl_close($curl);

        if ($errJne) {
            echo 'cURL Error #:'.$errJne;
        } else {
            $response = json_decode($responseJne, true);
            $data_ongkir = $response['rajaongkir']['results'];
            // $data_ongkir = $response;

            // $jne = json_encode($data_ongkir);
            // dd($data_ongkir);

            // return with([$data_ongkir, $ShippingMethod]);
        }

        $curl = curl_init();
        // SICEPAT
        curl_setopt_array($curl, [
            CURLOPT_URL => config('rajaongkir.url').'/cost',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            // CURLOPT_POSTFIELDS => 'origin='.$from_city.'&originType='.$from_type.'&destination='.$to_city.'&destinationType='.$to_type.'&weight='.$weight.'&courier=jne',
            CURLOPT_POSTFIELDS => 'origin='.$from_city.'&originType=city&destination='.$to_district.'&destinationType=subdistrict&weight='.$weight.'&courier=sicepat',
            CURLOPT_HTTPHEADER => [
                'content-type: application/x-www-form-urlencoded',
                'key:'.config('rajaongkir.api_key'),
            ],
        ]);

        $responseSicepat = curl_exec($curl);
        $errSicepat = curl_error($curl);

        curl_close($curl);

        if ($errSicepat) {
            echo 'cURL Error #:'.$errSicepat;
        } else {
            $response = json_decode($responseSicepat, true);
            $sicepat = $response['rajaongkir']['results'];
            // $data_ongkir = $response;

            // $jne = json_encode($data_ongkir);
            // dd($sicepat);

            // return with([$data_ongkir, $ShippingMethod]);
        }

        // TIKI
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => config('rajaongkir.url').'/cost',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'origin='.$from_city.'&originType=city&destination='.$to_district.'&destinationType=subdistrict&weight='.$weight.'&courier=tiki',
            CURLOPT_HTTPHEADER => [
                'content-type: application/x-www-form-urlencoded',
                'key:'.config('rajaongkir.api_key'),
            ],
        ]);

        $responseTiki = curl_exec($curl);
        $errTiki = curl_error($curl);

        curl_close($curl);

        if ($errTiki) {
            echo 'cURL Error #:'.$errTiki;
        } else {
            $response = json_decode($responseTiki, true);
            $tiki = $response['rajaongkir']['results'];

            // $jne = json_encode($data_ongkir);
            // dd($data_ongkir);

            return with([[$data_ongkir, $tiki, $sicepat], $ShippingMethod, $weight]);
        }
    }

    public static function get_image_path($type)
    {
        $path = asset('storage/app/public/brand');

        return $path;
    }

    public static function categoryDataFormatting($data, $id)
    {
        foreach ($data as $d) {
            $product = \App\CPU\Helpers::getProductDealsImg($d['id'], $id);
            $d['banner'] = $product['images'];
        }

        return $data;
    }

    public static function product_data_formatting($data, $multi_data = false, $check = null)
    {
        $storage = [];
        if ($multi_data == true) {
            foreach ($data as $item) {
                $variation = [];
                $item['category_ids'] = json_decode($item['category_ids']);
                $item['images'] = json_decode($item['images']);
                $item['colors'] = Color::whereIn('code', json_decode($item['colors']))->get(['name', 'code']);
                $attributes = [];
                if (json_decode($item['attributes']) != null) {
                    foreach (json_decode($item['attributes']) as $attribute) {
                        array_push($attributes, (int) $attribute);
                    }
                }
                $item['attributes'] = $attributes;
                $item['choice_options'] = json_decode($item['choice_options']);
                foreach (json_decode($item['variation'], true) as $var) {
                    array_push($variation, [
                        'type' => $var['type'],
                        'price' => round($var['price']),
                        // 'dropship' => round($var['dropship']),
                        'sku' => $var['sku'],
                        'qty' => (int) $var['qty'],
                    ]);
                }
                $item['variation'] = $variation;
                array_push($storage, $item);
            }
            $data = $storage;
        } else {
            $variation = [];
            $data['category_ids'] = json_decode($data['category_ids']);
            $data['images'] = json_decode($data['images']);
            $data['colors'] = Color::whereIn('code', json_decode($data['colors']))->get(['name', 'code']);
            $attributes = [];
            if (json_decode($data['attributes']) != null) {
                foreach (json_decode($data['attributes']) as $attribute) {
                    array_push($attributes, (int) $attribute);
                }
            }
            $data['attributes'] = $attributes;
            $data['choice_options'] = json_decode($data['choice_options']);
            foreach (json_decode($data['variation'], true) as $var) {
                array_push($variation, [
                    'type' => $var['type'],
                    'price' => (float) $var['price'],
                    'sku' => $var['sku'],
                    'qty' => (int) $var['qty'],
                ]);
            }
            $data['variation'] = $variation;
            $wa = BusinessSetting::where('type', 'company_phone')->first();
            $direct = [
                        'phone' => '62'.(int) $wa->value,
                        'product' => $data['name'],
                        'price' => $data['unit_price'],
                        'link' => URL::to('/product/'.$data['slug']),
                    ];
            $data['direct_wa'] = Helpers::directWa($direct);
        }

        return $data;
    }

    public static function units()
    {
        $x = ['kg', 'pc', 'gms', 'ltrs'];

        return $x;
    }

    public static function remove_invalid_charcaters($str)
    {
        return str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $str);
    }

    public static function saveJSONFile($code, $data)
    {
        ksort($data);
        $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents(base_path('resources/lang/en/messages.json'), stripslashes($jsonData));
    }

    public static function combinations($arrays)
    {
        $result = [[]];
        foreach ($arrays as $property => $property_values) {
            $tmp = [];
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, [$property => $property_value]);
                }
            }
            $result = $tmp;
        }

        return $result;
    }

    public static function error_processor($validator)
    {
        $err_keeper = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            array_push($err_keeper, ['code' => $index, 'message' => $error[0]]);
        }

        return $err_keeper;
    }

    public static function currency_load()
    {
        $default = Helpers::get_business_settings('system_default_currency');
        $current = \session('system_default_currency_info');
        if (session()->has('system_default_currency_info') == false || $default != $current['id']) {
            $id = Helpers::get_business_settings('system_default_currency');
            $currency = Currency::find($id);
            session()->put('system_default_currency_info', $currency);
            session()->put('currency_code', $currency->code);
            session()->put('currency_symbol', $currency->symbol);
            session()->put('currency_exchange_rate', $currency->exchange_rate);
        }
    }

    public static function currency_converter($amount)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            if (session()->has('usd')) {
                $usd = session('usd');
            } else {
                $usd = Currency::where(['code' => 'USD'])->first()->exchange_rate;
                session()->put('usd', $usd);
            }
            $my_currency = \session('currency_exchange_rate');
            $rate = $my_currency / $usd;
        } else {
            $rate = 1;
        }

        // return Helpers::set_symbol(round($amount * $rate, 2));
        return Helpers::set_symbol(round($amount));
    }

    public static function language_load()
    {
        if (\session()->has('language_settings')) {
            $language = \session('language_settings');
        } else {
            $language = BusinessSetting::where('type', 'language')->first();
            \session()->put('language_settings', $language);
        }

        return $language;
    }

    public static function tax_calculation($price, $tax, $tax_type)
    {
        $amount = ($price / 100) * $tax;

        return $amount;
    }

    public static function get_price_range($product)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        $type = session()->get('user_is');
        if ($type == 'dropship') {
            $lowest_price = $product->dropship;
            $highest_price = $product->dropship;
        }

        foreach (json_decode($product->variation) as $key => $variation) {
            if ($lowest_price > $variation->price) {
                $lowest_price = round($variation->price, 2);
            }
            if ($highest_price < $variation->price) {
                $highest_price = round($variation->price, 2);
            }
        }

        $lowest_price = Helpers::currency_converter($lowest_price - Helpers::get_product_discount($product, $lowest_price));
        $highest_price = Helpers::currency_converter($highest_price - Helpers::get_product_discount($product, $highest_price));

        if ($lowest_price == $highest_price) {
            return $lowest_price;
        }

        return $lowest_price.' - '.$highest_price;
    }

    public static function get_product_discount($product, $price)
    {
        $discount = 0;
        if ($product->discount_type == 'percent') {
            $discount = ($price * $product->discount) / 100;
        } elseif ($product->discount_type == 'flat') {
            $discount = $product->discount;
        }

        return floatval($discount);
    }

    public static function module_permission_check($mod_name)
    {
        $permission = auth('admin')->user()->role->module_access;
        if (isset($permission) && in_array($mod_name, (array) json_decode($permission)) == true) {
            return true;
        }

        if (auth('admin')->user()->admin_role_id == 1) {
            return true;
        }

        return false;
    }

    public static function convert_currency_to_usd($price)
    {
        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            Helpers::currency_load();
            $code = session('currency_code') == null ? 'USD' : session('currency_code');
            $currency = Currency::where('code', $code)->first();
            $price = floatval($price) / floatval($currency->exchange_rate);
        } else {
            $price = floatval($price);
        }

        return $price;
    }

    public static function order_status_update_message($status)
    {
        if ($status == 'pending') {
            $data = BusinessSetting::where('type', 'order_pending_message')->first()->value;
        } elseif ($status == 'confirmed') {
            $data = BusinessSetting::where('type', 'order_confirmation_msg')->first()->value;
        } elseif ($status == 'processing') {
            $data = BusinessSetting::where('type', 'order_processing_message')->first()->value;
        } elseif ($status == 'out_for_delivery') {
            $data = BusinessSetting::where('type', 'out_for_delivery_message')->first()->value;
        } elseif ($status == 'delivered') {
            $data = BusinessSetting::where('type', 'order_delivered_message')->first()->value;
        } elseif ($status == 'returned') {
            $data = BusinessSetting::where('type', 'order_returned_message')->first()->value;
        } elseif ($status == 'failed') {
            $data = BusinessSetting::where('type', 'order_failed_message')->first()->value;
        } elseif ($status == 'delivery_boy_delivered') {
            $data = BusinessSetting::where('type', 'delivery_boy_delivered_message')->first()->value;
        } elseif ($status == 'del_assign') {
            $data = BusinessSetting::where('type', 'delivery_boy_assign_message')->first()->value;
        } elseif ($status == 'ord_start') {
            $data = BusinessSetting::where('type', 'delivery_boy_start_message')->first()->value;
        } else {
            $data = '{"status":"0","message":""}';
        }

        $res = json_decode($data, true);

        if ($res['status'] == 0) {
            return 0;
        }

        return $res['message'];
    }

    public static function send_push_notif_to_device($fcm_token, $data)
    {
        $key = BusinessSetting::where(['type' => 'push_notification_key'])->first()->value;
        $url = 'https://fcm.googleapis.com/fcm/send';
        $header = ['authorization: key='.$key.'',
            'content-type: application/json',
        ];

        if (isset($data['order_id']) == false) {
            $data['order_id'] = null;
        }

        $img = asset('assets/front-end/img/notif.png');

        $notif = [
            'title' => $data['title'],
            'body' => $data['description'],
            'image' => $img,
            'order_id' => $data['order_id'],
            'title_loc_key' => $data['order_id'],
            'is_read' => 0,
            'icon' => 'new',
            'sound' => 'default',
        ];

        $postdata = '{
            "to" : "'.$fcm_token.'",
            "data" : {
                "title" :"'.$data['title'].'",
                "body" : "'.$data['description'].'",
                "image" : "'.$img.'",
                "order_id":"'.$data['order_id'].'",
                "is_read": 0
                },
            "notification" : '.json_encode($notif).'
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function send_push_notif_to_topic($token, $data)
    {
        $key = BusinessSetting::where(['type' => 'push_notification_key'])->first()->value;

        $url = 'https://fcm.googleapis.com/fcm/send';
        $header = ['authorization: key='.$key.'',
            'content-type: application/json',
        ];

        if (isset($data['order_id']) == false) {
            $data['order_id'] = null;
        }

        $img = asset('assets/front-end/img/ezren_logo.png');
        $images = asset('storage/notification').'/'.$data['image'];

        $notif = [
            'title' => $data['title'],
            'body' => $data['description'],
            'image' => $images,
            'order_id' => $data['order_id'],
            'title_loc_key' => $data['order_id'],
            'is_read' => 0,
            'icon' => $img,
            'sound' => 'default',
        ];

        $postdata = '{
            "to" : "'.$token.'",
            "data" : {
                "title" :"'.$data['title'].'",
                "body" : "'.$data['description'].'",
                "image" : "'.$images.'",
                "icon" : "'.$img.'",
                "order_id":"'.$data['order_id'].'",
                "is_read": 0
                },
            "notification" : '.json_encode($notif).'
        }';

        $ch = curl_init();
        $timeout = 120;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        // Get URL content
        $result = curl_exec($ch);
        // close handle to release resources
        curl_close($ch);

        return $result;
    }

    public static function get_seller_by_token($request)
    {
        $data = '';
        $success = 0;

        $token = explode(' ', $request->header('authorization'));
        if (count($token) > 1 && strlen($token[1]) > 30) {
            $seller = Seller::where(['auth_token' => $token['1']])->first();
            if (isset($seller)) {
                $data = $seller;
                $success = 1;
            }
        }

        return [
            'success' => $success,
            'data' => $data,
        ];
    }

    public static function remove_dir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($dir.'/'.$object) == 'dir') {
                        Helpers::remove_dir($dir.'/'.$object);
                    } else {
                        unlink($dir.'/'.$object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public static function currency_code()
    {
        Helpers::currency_load();
        if (session()->has('currency_symbol')) {
            $symbol = session('currency_symbol');
            $code = Currency::where(['symbol' => $symbol])->first()->code;
        } else {
            $system_default_currency_info = session('system_default_currency_info');
            $code = $system_default_currency_info->code;
        }

        return $code;
    }

    public static function get_language_name($key)
    {
        $values = Helpers::get_business_settings('language');
        foreach ($values as $value) {
            if ($value['code'] == $key) {
                $key = $value['name'];
            }
        }

        return $key;
    }

    public static function setEnvironmentValue($envKey, $envValue)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);
        $oldValue = env($envKey);
        if (strpos($str, $envKey) !== false) {
            $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
        } else {
            $str .= "{$envKey}={$envValue}\n";
        }
        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);

        return $envValue;
    }

    public static function requestSender()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt_array($curl, [
            CURLOPT_URL => route(base64_decode('YWN0aXZhdGlvbi1jaGVjaw==')),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ]);
        $response = curl_exec($curl);
        $data = json_decode($response, true);

        return $data;
    }

    public static function sales_commission($order)
    {
        $order_summery = OrderManager::order_summary($order);
        $order_total = $order_summery['subtotal'] - $order_summery['total_discount_on_product'] - $order['discount_amount'];
        $commission_amount = 0;

        if ($order['seller_is'] == 'seller') {
            $seller = Seller::find($order['seller_id']);
            if (isset($seller) && $seller['sales_commission_percentage'] !== null) {
                $commission = $seller['sales_commission_percentage'];
            } else {
                $commission = Helpers::get_business_settings('sales_commission');
            }
            $commission_amount = (($order_total / 100) * $commission);
        }

        return $commission_amount;
    }

    public static function categoryName($id)
    {
        return Category::select('name')->find($id)->name;
    }

    public static function set_symbol($amount)
    {
        $position = Helpers::get_business_settings('currency_symbol_position');
        if (!is_null($position) && $position == 'left') {
            $string = currency_symbol().''.number_format($amount);
        } else {
            $string = number_format($amount).''.currency_symbol();
        }

        return $string;
    }

    public static function pagination_limit()
    {
        $pagination_limit = BusinessSetting::where('type', 'pagination_limit')->first();
        if ($pagination_limit != null) {
            return $pagination_limit->value;
        } else {
            return 25;
        }
    }

    public static function gen_mpdf($view, $file_prefix, $file_postfix)
    {
        $mpdf = new \Mpdf\Mpdf(['default_font' => 'FreeSerif', 'mode' => 'utf-8', 'format' => [190, 236]]);
        $mpdf->AddPage('L', '', '', '', '', 0, 0, 0, '', '', '');
        $mpdf->autoScriptToLang = true;
        $mpdf->autoLangToFont = true;

        $mpdf_view = $view;
        $mpdf_view = $mpdf_view->render();
        $mpdf->WriteHTML($mpdf_view);
        $mpdf->Output($file_prefix.$file_postfix.'.pdf', 'D');
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        Helpers::currency_load();
        if (\session()->has('currency_symbol')) {
            $symbol = \session('currency_symbol');
        } else {
            $system_default_currency_info = \session('system_default_currency_info');
            $symbol = $system_default_currency_info->symbol;
        }

        return $symbol;
    }
}
//formats currency
if (!function_exists('format_price')) {
    function format_price($price)
    {
        return number_format($price, 2).currency_symbol();
    }
}

function translate($key)
{
    $local = Helpers::default_lang();
    App::setLocale($local);

    $lang_array = include base_path('resources/lang/'.$local.'/messages.php');
    $processed_key = ucfirst(str_replace('_', ' ', Helpers::remove_invalid_charcaters($key)));

    if (!array_key_exists($key, $lang_array)) {
        $lang_array[$key] = $processed_key;
        $str = '<?php return '.var_export($lang_array, true).';';
        file_put_contents(base_path('resources/lang/'.$local.'/messages.php'), $str);
        $result = $processed_key;
    } else {
        $result = __('messages.'.$key);
    }

    return $result;
}
