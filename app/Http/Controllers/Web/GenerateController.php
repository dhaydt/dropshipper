<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Model\Product;
use App\Model\Shop;

class GenerateController extends Controller
{
    public function generate($seller_id, $seller_name, $product_slug)
    {
        $shop = Shop::where('seller_id', $seller_id)->first();
        $data['title'] = 'Toko '.$shop->name;
        $data['product'] = Product::where('slug', $product_slug)->first();

        // dd($seller_id, $seller_name, $product_slug);
        return view('web-views.generated', $data);
    }
}
