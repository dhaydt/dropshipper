<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;
use App\Model\Product;

class CustomBannerController extends Controller
{
    public function bardy_banner()
    {
        try {
            $flash_deals = FlashDeal::where(['status' => 1, 'deal_type' => 'unggulan'])->first();

            return response()->json($flash_deals, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function get_products($bardy_id)
    {
        $p_ids = FlashDealProduct::with(['product'])
            ->where(['flash_deal_id' => $bardy_id])
            ->pluck('product_id')->toArray();
        if (count($p_ids) > 0) {
            return response()->json(Helpers::product_data_formatting(Product::with(['rating'])->whereIn('id', $p_ids)->get(), true), 200);
        }

        return response()->json([], 200);
    }
}
