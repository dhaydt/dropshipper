<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function get_banners(Request $request){
        $validator = Validator::make($request->all(), [
            'banner_type' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        try {
            if ($request['banner_type']=='all'){
                $banners = Banner::where(['published'=>1])->get();
            }elseif ($request['banner_type']=='main_banner'){
                $banners = Banner::where(['published'=>1,'banner_type'=>'Main Banner'])->get();
            }else{
                $banners = Banner::where(['published'=>1,'banner_type'=>'Footer Banner'])->get();
            }
        } catch (\Exception $e) {

        }

        return response()->json($banners,200);
    }
}
