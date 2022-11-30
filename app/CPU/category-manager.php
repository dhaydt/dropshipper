<?php

namespace App\CPU;

use App\Model\Category;
use App\Model\Product;

class CategoryManager
{
    public static function parents()
    {
        $x = Category::with(['childes.childes'])->where('position', 0)->get();
        return $x;
    }

    public static function child($parent_id)
    {
        $x = Category::where(['parent_id' => $parent_id])->get();
        return $x;
    }

    public static function products($category_id)
    {
        return Product::active()->whereJsonContains('category_ids', ["id" => (string)$category_id])->get();
    }
    
     public static function short_products($category_id, $country)
    {
        return Product::active()->where('country', $country)->whereJsonContains('category_ids', ['id' => (string) $category_id])->get();
    }
}
