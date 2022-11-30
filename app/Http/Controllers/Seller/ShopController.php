<?php

namespace App\Http\Controllers\Seller;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Model\Seller;
use Illuminate\Support\Facades\DB;

class ShopController extends Controller
{
    public function view()
    {
        $shop = Shop::where(['seller_id' => auth('seller')->id()])->first();
        if (isset($shop) == false) {
            DB::table('shops')->insert([
                'seller_id' => auth('seller')->id(),
                'name' => auth('seller')->user()->f_name,
                'address' => '',
                'contact' => auth('seller')->user()->phone,
                'image' => 'def.png',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            $shop = Shop::where(['seller_id' => auth('seller')->id()])->first();
        }

        return view('seller-views.shop.shopInfo', compact('shop'));
    }

    public function edit($id)
    {
        $shop = Shop::where(['seller_id' =>  auth('seller')->id()])->first();
        return view('seller-views.shop.edit', compact('shop'));
    }

    public function update(Request $request, $id)
    {
         if ($request->country == 'ID') {
            $provinces = $request->state;
            $province = explode(',', $provinces);
             if (count($province) > 1) {
                $prov = $province[1];
            } else {
                $prov = $province[0];
            }

            $cities = $request->city;
            $cit = explode(',', $cities);
            $city_id = $cit[0];
            $city = $cit[1];
            $districts = $request->district;
            $dis = explode(',', $districts);
            $district_id = $dis[0];
            $district = $dis[1];
        // dd($request);
        } else {
            $prov = $request->state;
            $city = $request->city;
            $city_id = 'NULL';
        }

        $shop = Shop::find($id);
        $seller = Seller::find($shop->seller->id);
        // dd($seller);

        $seller->province = $prov;
        $seller->city = $city;
        $seller->district = $district;
        $seller->city_id = $city_id;
        $seller->district_id = $district_id;
        $seller->save();

        $shop->name = $request->name;
        $shop->country = $request->country;
        $shop->province = $prov;
        $shop->city = $city;
        $shop->district = $district;
        $shop->city_id = $city_id;
        $shop->district_id = $district_id;
        $shop->address = $request->address;
        $shop->contact = $request->contact;
        if ($request->image) {
            $shop->image = ImageManager::update('shop/', $shop->image, 'png', $request->file('image'));
        }
        if ($request->banner) {
            $shop->banner = ImageManager::update('shop/banner/', $shop->banner, 'png', $request->file('banner'));
        }
        $shop->save();

        Toastr::info('Shop updated successfully!');
        return redirect()->route('seller.shop.view');
    }

}
