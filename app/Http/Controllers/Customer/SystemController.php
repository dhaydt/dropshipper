<?php

namespace App\Http\Controllers\Customer;

use App\CPU\CartManager;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\CartShipping;
use App\Model\ShippingMethod;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SystemController extends Controller
{
    public function set_payment_method($name)
    {
        if (auth('customer')->check() || session()->has('mobile_app_payment_customer_id')) {
            session()->put('payment_method', $name);

            return response()->json([
                'status' => 1,
            ]);
        }

        return response()->json([
            'status' => 0,
        ]);
    }

    public function set_shipping_method(Request $request)
    {
        if ($request['cart_group_id'] == 'all_cart_group') {
            foreach (CartManager::get_cart_group_ids() as $group_id) {
                $request['cart_group_id'] = $group_id;
                self::insert_into_cart_shipping($request);
            }
        } else {
            self::insert_into_cart_shipping($request);
        }

        return response()->json([
            'status' => 1,
        ]);
    }

    public static function insert_into_cart_shipping($request)
    {
        $shipping = CartShipping::where(['cart_group_id' => $request['cart_group_id']])->first();
        if (isset($shipping) == false) {
            $shipping = new CartShipping();
        }

        $seller = Cart::where('cart_group_id', $request['cart_group_id'])->first();
        $selCountry = $seller->product->country;

        $customer = User::find($seller->customer_id);
        $cusCountry = $customer->country;

        if ($selCountry && $cusCountry == 'ID') {
            $shipp = $request['id'];
            $ship = explode(',', $shipp);
            $service = $ship[0];
            $cost = $ship[1];
            $price = Convert::idrTousd($cost);
            $ship_method = 'NULL';
        } else {
            $service = 'NULL';
            $cost = ShippingMethod::find($request['id'])->cost;
            $ship_method = $request['id'];
        }
        // $customer =
        // dd($customer);

        // dd($cost);
        // dd($request);
        $shipping['cart_group_id'] = $request['cart_group_id'];
        $shipping['shipping_method_id'] = $ship_method;
        $shipping['shipping_service'] = $service;
        $shipping['shipping_cost'] = round($price, 2);
        $shipping->save();
    }

    public function choose_shipping_address(Request $request)
    {
        if ($request['country'] == 'ID') {
            $provinces = $request['state'];
            $province = explode(',', $provinces);
            if (count($province) > 1) {
                $prov = $province[1];
            } else {
                $prov = $province[0];
            }
            // dd($prov);

            $cities = $request['city'];
            $cit = explode(',', $cities);
            if (count($cit) > 1) {
                $city = $cit[1];
                $city_id = $cit[0];
            } else {
                $city = $province[0];
                $city_id = '';
            }
            $districts = $request->district;
            $dis = explode(',', $districts);
            if (count($dis) > 1) {
                $district = $dis[1];
                $district_id = $dis[0];
            } else {
                $district_id = '';
                $district = $dis[0];
            }

            if ($request->save_address == 'on') {
                $address_id = DB::table('shipping_addresses')->insertGetId([
                    'customer_id' => auth('customer')->id(),
                    'contact_person_name' => $request['contact_person_name'],
                    'address_type' => $request['address_type'],
                    'address' => $request['address'],
                    'city' => $city,
                    'state' => $prov,
                    'province' => $prov,
                    'city_id' => $city_id,
                    'district' => $district,
                    'district_id' => $district_id,
                    'country' => $request['country'],
                    'zip' => $request['zip'],
                    'phone' => $request['phone'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } elseif ($request['shipping_method_id'] == 0) {
                $validator = Validator::make($request->all(), [
                    'contact_person_name' => 'required',
                    'address_type' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'country' => 'required',
                    'state' => 'required',
                    'district' => 'required',
                    'zip' => 'required',
                    'phone' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }

                $address_id = DB::table('shipping_addresses')->insertGetId([
                    'customer_id' => 0,
                    'contact_person_name' => $request['contact_person_name'],
                    'address_type' => $request['address_type'],
                    'address' => $request['address'],
                    'city' => $city,
                    'province' => $prov,
                    'city_id' => $city_id,
                    'district' => $district,
                    'district_id' => $district_id,
                    'country' => $request['country'],
                    'zip' => $request['zip'],
                    'phone' => $request['phone'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $address_id = $request['shipping_method_id'];
            }
        } else {
            if ($request->save_address == 'on') {
                $address_id = DB::table('shipping_addresses')->insertGetId([
                    'customer_id' => auth('customer')->id(),
                    'contact_person_name' => $request['contact_person_name'],
                    'address_type' => $request['address_type'],
                    'address' => $request['address'],
                    'city' => $request['city'],
                    'country' => $request['country'],
                    'zip' => $request['zip'],
                    'phone' => $request['phone'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } elseif ($request['shipping_method_id'] == 0) {
                $validator = Validator::make($request->all(), [
                    'contact_person_name' => 'required',
                    'address_type' => 'required',
                    'address' => 'required',
                    'city' => 'required',
                    'country' => 'required',
                    'zip' => 'required',
                    'phone' => 'required',
                ]);

                if ($validator->fails()) {
                    return response()->json(['errors' => Helpers::error_processor($validator)]);
                }

                $address_id = DB::table('shipping_addresses')->insertGetId([
                    'customer_id' => 0,
                    'contact_person_name' => $request['contact_person_name'],
                    'address_type' => $request['address_type'],
                    'address' => $request['address'],
                    'city' => $request['city'],
                    'country' => $request['country'],
                    'zip' => $request['zip'],
                    'phone' => $request['phone'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                $address_id = $request['shipping_method_id'];
            }
        }

        $oldAddress = session()->get('address_id');

        session(['old_address' => $oldAddress]);

        session()->put('address_id', $address_id);

        $old = session()->get('old_address');
        $new = session()->get('address_id');

        if (auth('customer')->user()->country == 'ID') {
            if ($new == $old) {
            } else {
                session(['address_changed' => 1]);
                CartShipping::where('cart_group_id', session()->get('cart_group_id'))
                        ->update([
                            'shipping_cost' => 0.00,
                        ]);
            }
        }

        return response()->json([], 200);
    }
}
