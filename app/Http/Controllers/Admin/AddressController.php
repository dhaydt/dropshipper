<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function index()
    {
        $address = BusinessSetting::where('type', 'address')->first();
        if (!$address) {
            $data = [
                'province' => 'DKI Jakarta',
                'province_id' => '6',
                'city' => 'Jakarta Barat',
                'city_id' => '151',
                'district' => 'Cengkareng',
                'district_id' => '2087',
            ];

            $address = new BusinessSetting();
            $address->type = 'address';
            $address->value = json_encode($data);
            $address->save();
        }

        return view('admin-views.address.index', compact('address'));
    }

    public function update(Request $request)
    {
        $states = $request->state;
        $stat = explode(',', $states);
        $state_id = $stat[0];
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

        $data = [
            'province' => $state,
            'province_id' => $state_id,
            'city' => $city,
            'city_id' => $city_id,
            'district' => $district,
            'district_id' => $district_id,
            'address' => $request->address,
        ];

        $address = BusinessSetting::where('type', 'address')->first();
        $address->value = json_encode($data);
        $address->save();

        Toastr::success('Alamat berhasil di update!');

        return redirect()->back();
    }
}
