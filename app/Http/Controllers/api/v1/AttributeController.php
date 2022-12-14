<?php

namespace App\Http\Controllers\api\v1;

use App\Country;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Attribute;
use App\Model\RequestProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttributeController extends Controller
{
    public function request_product(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required',
            'jumlah' => 'required',
            'phone' => 'required',
            'link' => 'required',
            'deskripsi' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        $image = $request->file('image');
        if ($image != null) {
            $img = ImageManager::upload('product/request/', 'png', $image);
        }
        $new = RequestProduct::create([
            'name' => $request->nama,
            'qty' => $request->jumlah,
            'phone' => $request->phone,
            'link' => $request->link,
            'description' => $request->deskripsi,
            'status' => 'pending',
            'image' => $img,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Permintaan produk berhasil diajukan']);
    }

    public function get_attributes()
    {
        $attributes = Attribute::all();

        return response()->json($attributes, 200);
    }

    public function short_country()
    {
        $country = Country::with('product')->has('product')->get();
        $count = $country->map(function ($country) {
            return ['country' => $country->country, 'country_name' => $country->country_name];
        });

        return response()->json($count, 200);
    }

    public function country()
    {
        $country = DB::table('country')->get();
        $count = $country->map(function ($country) {
            return ['country' => $country->country, 'country_name' => $country->country_name, 'phone' => $country->phonecode];
        });
        // $country['country'] = Helpers::product_data_formatting($country['country'], true);
        $response = [
            'title' => 'location store',
            'country_list' => $count,
        ];

        return response()->json($response, 200);
    }
}
