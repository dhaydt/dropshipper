<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\Helpers;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    public function getDistrict(Request $request)
    {
        $district = Helpers::district($request->city_id);

        return response()->json(['status' => 'success get District', 'data' => $district]);
    }

    public function getCity(Request $request)
    {
        $city = Helpers::city($request->province_id);

        return response()->json(['status' => 'success get City', 'data' => $city]);
    }

    public function getProvince()
    {
        $prov = Helpers::province();

        return response()->json(['status' => 'success get Province', 'data' => $prov]);
    }

    public function add_new_address(Request $request)
    {
        $check = Helpers::get_seller_by_token($request);
        $user = $check['data'];
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'phone' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $states = $request->province;
        $stat = explode(',', $states);
        $state = $stat[1];

        $cities = $request->city;
        $cit = explode(',', $cities);
        $city_id = $cit[0];
        $city = $cit[1];
        $city_type = $cit[2];

        $districts = $request->district;
        $dis = explode(',', $districts);
        $district_id = $dis[0];
        $district = $dis[1];

        $slug = $request->contact_person_name.now();

        $address = [
            'customer_id' => $user->id,
            'contact_person_name' => $request->contact_person_name,
            'address_type' => 'dropship',
            'address' => $request->address,
            'city' => $city,
            'city_id' => $city_id,
            'city_type' => $city_type,
            'district' => $district,
            'district_id' => $district_id,
            'zip' => $request->zip,
            'phone' => $request->phone,
            'state' => $state,
            'province' => $state,
            'country' => 'ID',
            'user_is' => 'dropship',
            'slug' => $slug,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('shipping_addresses')->insert($address);

        return response()->json(['message' => translate('successfully added!'), 'slug' => $slug], 200);
    }

    public function delete_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if (DB::table('shipping_addresses')->where(['id' => $request['address_id'], 'customer_id' => $request->user()->id])->first()) {
            DB::table('shipping_addresses')->where(['id' => $request['address_id'], 'customer_id' => $request->user()->id])->delete();

            return response()->json(['message' => 'successfully removed!'], 200);
        }

        return response()->json(['message' => translate('No such data found!')], 404);
    }
}
