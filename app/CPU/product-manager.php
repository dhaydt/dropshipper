<?php

namespace App\CPU;

use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\Review;
use App\Model\ShippingMethod;
use App\Model\Translation;
use Illuminate\Support\Facades\DB;

class ProductManager
{
    public static function get_product($id)
    {
        return Product::active()->with(['rating'])->where('id', $id)->first();
    }

    public static function get_latest_products($limit = 10, $offset = 1)
    {
        $paginator = Product::active()->with(['rating'])->latest()->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $paginator->items(),
        ];
    }

    public static function get_featured_products($limit = 10, $offset = 1)
    {
        $paginator = Product::with(['reviews'])->active()
            ->where('featured', 1)
            ->withCount(['order_details'])->orderBy('order_details_count', 'DESC')
            ->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $paginator->items(),
        ];
    }

    public static function get_top_rated_products($limit = 10, $offset = 1)
    {
        $reviews = Review::with('product')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('AVG(rating) as count'))
            ->groupBy('product_id')
            ->orderBy('count', 'desc')
            ->paginate($limit, ['*'], 'page', $offset);

        $data = [];
        foreach ($reviews as $review) {
            array_push($data, $review->product);
        }

        return [
            'total_size' => $reviews->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $data,
        ];
    }

    public static function get_best_selling_products($limit = 10, $offset = 1)
    {
        $paginator = OrderDetail::with('product.reviews')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy('count', 'desc')
            ->paginate($limit, ['*'], 'page', $offset);

        $data = [];
        foreach ($paginator as $order) {
            array_push($data, $order->product);
        }

        return [
            'total_size' => $paginator->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $data,
        ];
    }

    public static function get_related_products($product_id)
    {
        $product = Product::find($product_id);

        return Product::active()->with(['rating'])->where('category_ids', $product->category_ids)
            ->where('id', '!=', $product->id)
            ->limit(10)
            ->get();
    }

    public static function search_products($name, $limit = 10, $offset = 1)
    {
        $key = explode(' ', $name);
        $paginator = Product::active()->with(['rating'])->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $paginator->items(),
        ];
    }

    public static function translated_product_search($name, $limit = 10, $offset = 1)
    {
        $product_ids = Translation::where('translationable_type', 'App\Model\Product')
            ->where('key', 'name')
            ->where('value', 'like', "%{$name}%")
            ->pluck('translationable_id');

        $paginator = Product::WhereIn('id', $product_ids)->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $paginator->items(),
        ];
    }

    public static function product_image_path($image_type)
    {
        $path = '';
        if ($image_type == 'thumbnail') {
            $path = asset('storage/product/thumbnail');
        } elseif ($image_type == 'product') {
            $path = asset('storage/product');
        }

        return $path;
    }

    public static function get_product_review($id)
    {
        $reviews = Review::where('product_id', $id)
            ->where('status', 1)->get();

        return $reviews;
    }

    public static function get_rating($reviews)
    {
        $rating5 = 0;
        $rating4 = 0;
        $rating3 = 0;
        $rating2 = 0;
        $rating1 = 0;
        foreach ($reviews as $key => $review) {
            if ($review->rating == 5) {
                ++$rating5;
            }
            if ($review->rating == 4) {
                ++$rating4;
            }
            if ($review->rating == 3) {
                ++$rating3;
            }
            if ($review->rating == 2) {
                ++$rating2;
            }
            if ($review->rating == 1) {
                ++$rating1;
            }
        }

        return [$rating5, $rating4, $rating3, $rating2, $rating1];
    }

    public static function get_overall_rating($reviews)
    {
        $totalRating = count($reviews);
        $rating = 0;
        foreach ($reviews as $key => $review) {
            $rating += $review->rating;
        }
        if ($totalRating == 0) {
            $overallRating = 0;
        } else {
            $overallRating = number_format($rating / $totalRating, 2);
        }

        return [$overallRating, $totalRating];
    }

    public static function get_shipping_methods($product)
    {
        if ($product['added_by'] == 'seller') {
            $methods = ShippingMethod::where(['creator_id' => $product['user_id']])->where(['status' => 1])->get();
            if ($methods->count() == 0) {
                $methods = ShippingMethod::where(['creator_type' => 'admin'])->where(['status' => 1])->get();
            }
        } else {
            $methods = ShippingMethod::where(['creator_type' => 'admin'])->where(['status' => 1])->get();
        }

        return $methods;
    }

    public static function get_seller_products($seller_id, $limit = 10, $offset = 1)
    {
        $paginator = Product::active()->with(['rating'])
            ->where(['user_id' => $seller_id, 'added_by' => 'seller'])
            ->paginate($limit, ['*'], 'page', $offset);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $paginator->items(),
        ];
    }

    public static function short_latest_products($limit = 10, $offset = 1, $country)
    {
        $paginator = Product::active()->with(['rating'])
        ->where('country', $country)
        ->latest()->paginate($limit, ['*'], 'page', $offset);
        // dd($paginator);
        /*$paginator->count();*/
        return [
            'total_size' => $paginator->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $paginator->items(),
        ];
    }

    public static function short_featured_products($limit = 10, $offset = 1, $country)
    {
        $paginator = Product::with(['reviews'])->active()
            ->where('featured', 1)
            ->where('country', $country)
            ->withCount(['order_details'])->orderBy('order_details_count', 'DESC')
            ->paginate($limit, ['*'], 'page', $offset);

        return [
            'total_size' => $paginator->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $paginator->items(),
        ];
    }

    public static function short_top_rated_products($limit = 10, $offset = 1, $country)
    {
        $reviews = Review::with('product')
            ->whereHas('product', fn ($query) => $query->where('country', $country))
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('AVG(rating) as count'))
            ->groupBy('product_id')
            ->orderBy('count', 'desc')
            ->paginate($limit, ['*'], 'page', $offset);

        $data = [];
        foreach ($reviews as $review) {
            array_push($data, $review->product);
        }

        return [
            'total_size' => $reviews->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $data,
        ];
    }

    public static function short_best_selling_products($limit = 10, $offset = 1, $country)
    {
        $paginator = OrderDetail::with('product.reviews')
        ->whereHas('product', fn ($query) => $query->where('country', $country))
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy('count', 'desc')
            ->paginate($limit, ['*'], 'page', $offset);

        $data = [];
        foreach ($paginator as $order) {
            array_push($data, $order->product);
        }

        return [
            'total_size' => $paginator->total(),
            'limit' => (int) $limit,
            'offset' => (int) $offset,
            'products' => $data,
        ];
    }
}
