<?php

namespace App\Http\Controllers\Seller;

use App\Country;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Brand;
use App\Model\Category;
use App\Model\Color;
use App\Model\DealOfTheDay;
use App\Model\FlashDealProduct;
use App\Model\Product;
use App\Model\Review;
use App\Model\Shop;
use App\Model\Translation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Rap2hpoutre\FastExcel\FastExcel;

class ProductController extends Controller
{
    public function add_new()
    {
        $seller = auth('seller')->user();
        if ($seller->city == null) {
            $shop = Shop::where(['seller_id' => $seller->id])->first();
            // dd($shop->country);
            if ($shop->country == 'ID') {
                $country = Country::all();
                // dd($country);

                Toastr::warning('Fill your shop address first!');

                return redirect('/seller/shop/edit/'.$shop->id);
            }

            Toastr::warning('Fill your shop address first!');

            return redirect('/seller/shop/edit/'.$shop->id);
        }

        $cat = Category::where(['parent_id' => 0])->get();
        $br = Brand::orderBY('name', 'ASC')->get();

        return view('seller-views.product.add-new', compact('cat', 'br'));
    }

    public function status_update(Request $request)
    {
        if ($request['status'] == 0) {
            Product::where(['id' => $request['id'], 'added_by' => 'seller', 'user_id' => \auth('seller')->id()])->update([
                'status' => $request['status'],
            ]);

            return response()->json([
                'success' => 1,
            ], 200);
        } elseif ($request['status'] == 1) {
            if (Product::find($request['id'])->request_status == 1) {
                Product::where(['id' => $request['id']])->update([
                    'status' => $request['status'],
                ]);

                return response()->json([
                    'success' => 1,
                ], 200);
            } else {
                return response()->json([
                    'success' => 0,
                ], 200);
            }
        }
    }

    public function featured_status(Request $request)
    {
        if ($request->ajax()) {
            $product = Product::find($request->id);
            $product->featured_status = $request->status;
            $product->save();
            $data = $request->status;

            return response()->json($data);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'unit' => 'required',
            'images' => 'required',
            'image' => 'required',
            'tax' => 'required|min:0',
            'unit_price' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:1',
        ], [
            'name.required' => 'Product name is required!',
            'category_id.required' => 'category  is required!',
            'images.required' => 'Product images is required!',
            'image.required' => 'Product thumbnail is required!',
            'brand_id.required' => 'brand  is required!',
            'unit.required' => 'Unit  is required!',
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['unit_price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['unit_price'] <= $dis) {
            $validator->after(function ($validator) {
                $validator->errors()->add(
                    'unit_price', 'Discount can not be more or equal to the price!'
                );
            });
        }

        $seller = auth('seller')->user();
        $country = $seller->country;
        $city = $seller->city;
        $city_id = $seller->city_id;
        // dd($request);
        // dd($user->country);

        $product = new Product();
        $product->user_id = $seller->id;
        $product->country = $country;
        $product->city = $city;
        $product->city_id = $city_id;
        $product->weight = $request->weight;
        $product->added_by = 'seller';
        $product->name = $request->name[array_search('en', $request->lang)];
        $product->slug = Str::slug($request->name[array_search('en', $request->lang)], '-').'-'.Str::random(6);

        $category = [];

        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }

        $product->category_ids = json_encode($category);
        $product->brand_id = $request->brand_id;
        $product->unit = $request->unit;
        $product->details = $request->description[array_search('en', $request->lang)];

        if ($request->file('images')) {
            foreach ($request->file('images') as $img) {
                $product_images[] = ImageManager::upload('product/', 'png', $img);
            }
            $product->images = json_encode($product_images);
        }
        $product->thumbnail = ImageManager::upload('product/thumbnail/', 'png', $request->file('image'));

        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $product->colors = json_encode($request->colors);
        } else {
            $colors = [];
            $product->colors = json_encode($colors);
        }
        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;
                $item['name'] = 'choice_'.$no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', $request[$str]));
                array_push($choice_options, $item);
            }
        }
        $product->choice_options = json_encode($choice_options);
        //combinations start
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        $variations = [];
        $stock_count = 0;
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-'.str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = Convert::usd(abs($request['price_'.str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                $item['qty'] = abs($request['qty_'.str_replace('.', '_', $str)]);
                array_push($variations, $item);
                $stock_count += $item['qty'];
            }
        } else {
            $stock_count = (int) $request['current_stock'];
        }

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        //combinations end
        $product->variation = json_encode($variations);
        $product->unit_price = Convert::usd($request->unit_price);
        $product->purchase_price = Convert::usd($request->purchase_price);
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount_type == 'flat' ? Convert::usd($request->discount) : $request->discount;
        $product->discount_type = $request->discount_type;
        $product->attributes = json_encode($request->choice_attributes);
        $product->current_stock = abs($stock_count);

        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_image = ImageManager::upload('product/meta/', 'png', $request->meta_image);

        $product->video_provider = 'youtube';
        $product->video_url = $request->video_link;
        $product->request_status = 0;
        $product->status = 0;

        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            $product->save();
            $data = [];
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    array_push($data, [
                        'translationable_type' => 'App\Model\Product',
                        'translationable_id' => $product->id,
                        'locale' => $key,
                        'key' => 'name',
                        'value' => $request->name[$index],
                    ]);
                }
                if ($request->description[$index] && $key != 'en') {
                    array_push($data, [
                        'translationable_type' => 'App\Model\Product',
                        'translationable_id' => $product->id,
                        'locale' => $key,
                        'key' => 'description',
                        'value' => $request->description[$index],
                    ]);
                }
            }
            Translation::insert($data);
            Toastr::success('Product added successfully!');

            return redirect()->route('seller.product.list');
        }
    }

    public function list(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $products = Product::where(['added_by' => 'admin'])
                ->where(function ($q) use ($key) {
                    foreach ($key as $value) {
                        $q->Where('name', 'like', "%{$value}%");
                    }
                });
            $query_param = ['search' => $request['search']];
        } else {
            $products = Product::where(['added_by' => 'admin']);
        }
        $products = $products->orderBy('id', 'DESC')->paginate(Helpers::pagination_limit())->appends($query_param);

        return view('seller-views.product.list', compact('products', 'search'));
    }

    public function get_categories(Request $request)
    {
        $cat = Category::where(['parent_id' => $request->parent_id])->get();
        $res = '<option value="'. 0 .'" disabled selected>---Select---</option>';
        foreach ($cat as $row) {
            if ($row->id == $request->sub_category) {
                $res .= '<option value="'.$row->id.'" selected >'.$row->name.'</option>';
            } else {
                $res .= '<option value="'.$row->id.'">'.$row->name.'</option>';
            }
        }

        return response()->json([
            'select_tag' => $res,
        ]);
    }

    public function sku_combination(Request $request)
    {
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        } else {
            $colors_active = 0;
        }

        $unit_price = $request->unit_price;
        $product_name = $request->name[array_search('en', $request->lang)];

        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }

        $combinations = Helpers::combinations($options);

        return response()->json([
            'view' => view('admin-views.product.partials._sku_combinations', compact('combinations', 'unit_price', 'colors_active', 'product_name'))->render(),
        ]);
    }

    public function edit($id)
    {
        $product = Product::withoutGlobalScopes()->with('translations')->find($id);
        $product_category = json_decode($product->category_ids);
        $product->colors = json_decode($product->colors);
        $categories = Category::where(['parent_id' => 0])->get();
        $br = Brand::orderBY('name', 'ASC')->get();

        return view('seller-views.product.edit', compact('categories', 'br', 'product', 'product_category'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => 'required',
            'brand_id' => 'required',
            'unit' => 'required',
            'tax' => 'required|min:0',
            'unit_price' => 'required|numeric|min:1',
            'purchase_price' => 'required|numeric|min:1',
        ], [
            'name.required' => 'Product name is required!',
            'category_id.required' => 'category  is required!',
            'brand_id.required' => 'brand  is required!',
            'unit.required' => 'Unit  is required!',
        ]);

        if ($request['discount_type'] == 'percent') {
            $dis = ($request['unit_price'] / 100) * $request['discount'];
        } else {
            $dis = $request['discount'];
        }

        if ($request['unit_price'] <= $dis) {
            $validator->after(function ($validator) {
                $validator->errors()->add('unit_price', 'Discount can not be more or equal to the price!');
            });
        }

        $product = Product::find($id);
        $product->name = $request->name[array_search('en', $request->lang)];

        $category = [];
        if ($request->category_id != null) {
            array_push($category, [
                'id' => $request->category_id,
                'position' => 1,
            ]);
        }
        if ($request->sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_category_id,
                'position' => 2,
            ]);
        }
        if ($request->sub_sub_category_id != null) {
            array_push($category, [
                'id' => $request->sub_sub_category_id,
                'position' => 3,
            ]);
        }
        $product->category_ids = json_encode($category);
        $product->brand_id = $request->brand_id;
        $product->unit = $request->unit;
        $product->details = $request->description[array_search('en', $request->lang)];

        $product_images = json_decode($product->images);
        if ($request->file('images')) {
            foreach ($request->file('images') as $img) {
                $product_images[] = ImageManager::upload('product/', 'png', $img);
            }
            $product->images = json_encode($product_images);
        }

        if ($request->file('image')) {
            $product->thumbnail = ImageManager::update('product/thumbnail/', $product->thumbnail, 'png', $request->file('image'));
        }

        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $product->colors = json_encode($request->colors);
        } else {
            $colors = [];
            $product->colors = json_encode($colors);
        }
        $choice_options = [];
        if ($request->has('choice')) {
            foreach ($request->choice_no as $key => $no) {
                $str = 'choice_options_'.$no;
                $item['name'] = 'choice_'.$no;
                $item['title'] = $request->choice[$key];
                $item['options'] = explode(',', implode('|', $request[$str]));
                array_push($choice_options, $item);
            }
        }
        $product->choice_options = json_encode($choice_options);
        $variations = [];
        //combinations start
        $options = [];
        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
            $colors_active = 1;
            array_push($options, $request->colors);
        }
        if ($request->has('choice_no')) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_'.$no;
                $my_str = implode('|', $request[$name]);
                array_push($options, explode(',', $my_str));
            }
        }
        //Generates the combinations of customer choice options
        $combinations = Helpers::combinations($options);
        $variations = [];
        $stock_count = 0;
        if (count($combinations[0]) > 0) {
            foreach ($combinations as $key => $combination) {
                $str = '';
                foreach ($combination as $k => $item) {
                    if ($k > 0) {
                        $str .= '-'.str_replace(' ', '', $item);
                    } else {
                        if ($request->has('colors_active') && $request->has('colors') && count($request->colors) > 0) {
                            $color_name = Color::where('code', $item)->first()->name;
                            $str .= $color_name;
                        } else {
                            $str .= str_replace(' ', '', $item);
                        }
                    }
                }
                $item = [];
                $item['type'] = $str;
                $item['price'] = Convert::usd(abs($request['price_'.str_replace('.', '_', $str)]));
                $item['sku'] = $request['sku_'.str_replace('.', '_', $str)];
                $item['qty'] = abs($request['qty_'.str_replace('.', '_', $str)]);
                array_push($variations, $item);
                $stock_count += $item['qty'];
            }
        } else {
            $stock_count = (int) $request['current_stock'];
        }

        if ($validator->errors()->count() > 0) {
            return response()->json(['errors' => Helpers::error_processor($validator)]);
        }

        //combinations end
        $product->variation = json_encode($variations);
        $product->unit_price = Convert::usd($request->unit_price);
        $product->purchase_price = Convert::usd($request->purchase_price);
        $product->tax = $request->tax;
        $product->tax_type = $request->tax_type;
        $product->discount = $request->discount_type == 'flat' ? Convert::usd($request->discount) : $request->discount;
        $product->attributes = json_encode($request->choice_attributes);
        $product->discount_type = $request->discount_type;
        $product->current_stock = abs($stock_count);

        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        if ($request->file('meta_image')) {
            $product->meta_image = ImageManager::update('product/meta/', $product->meta_image, 'png', $request->file('meta_image'));
        }

        $product->video_provider = 'youtube';
        $product->video_url = $request->video_link;
        if ($product->request_status == 2) {
            $product->request_status = 0;
        }

        if ($request->ajax()) {
            return response()->json([], 200);
        } else {
            $product->save();
            foreach ($request->lang as $index => $key) {
                if ($request->name[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Model\Product',
                            'translationable_id' => $product->id,
                            'locale' => $key,
                            'key' => 'name', ],
                        ['value' => $request->name[$index]]
                    );
                }
                if ($request->description[$index] && $key != 'en') {
                    Translation::updateOrInsert(
                        ['translationable_type' => 'App\Model\Product',
                            'translationable_id' => $product->id,
                            'locale' => $key,
                            'key' => 'description', ],
                        ['value' => $request->description[$index]]
                    );
                }
            }
            Toastr::success('Product updated successfully.');

            return back();
        }
    }

    public function view($id)
    {
        $product = Product::with(['reviews'])->where(['id' => $id])->first();
        $reviews = Review::where(['product_id' => $id])->paginate(Helpers::pagination_limit());

        return view('seller-views.product.view', compact('product', 'reviews'));
    }

    public function remove_image(Request $request)
    {
        ImageManager::delete('/product/'.$request['image']);
        $product = Product::find($request['id']);
        $array = [];
        if (count(json_decode($product['images'])) < 2) {
            Toastr::warning('You cannot delete all images!');

            return back();
        }
        foreach (json_decode($product['images']) as $image) {
            if ($image != $request['name']) {
                array_push($array, $image);
            }
        }
        Product::where('id', $request['id'])->update([
            'images' => json_encode($array),
        ]);
        Toastr::success('Product image removed successfully!');

        return back();
    }

    public function delete($id)
    {
        $product = Product::find($id);
        foreach (json_decode($product['images'], true) as $image) {
            ImageManager::delete('/product/'.$image);
        }
        ImageManager::delete('/product/thumbnail/'.$product['thumbnail']);
        $product->delete();
        FlashDealProduct::where(['product_id' => $id])->delete();
        DealOfTheDay::where(['product_id' => $id])->delete();
        Toastr::success('Product removed successfully!');

        return back();
    }

    public function bulk_import_index()
    {
        return view('seller-views.product.bulk-import');
    }

    public function bulk_import_data(Request $request)
    {
        try {
            $collections = (new FastExcel())->import($request->file('products_file'));
        } catch (\Exception $exception) {
            Toastr::error('You have uploaded a wrong format file, please upload the right file.');

            return back();
        }
        $data = [];
        $skip = ['youtube_video_url', 'details'];
        foreach ($collections as $collection) {
            foreach ($collection as $key => $value) {
                if ($value === '' && !in_array($key, $skip)) {
                    Toastr::error('Please fill '.$key.' fields');

                    return back();
                }
            }

            array_push($data, [
                'name' => $collection['name'],
                'slug' => Str::slug($collection['name'], '-').'-'.Str::random(6),
                'category_ids' => json_encode([['id' => $collection['category_id'], 'position' => 0], ['id' => $collection['sub_category_id'], 'position' => 1]]),
                'brand_id' => $collection['brand_id'],
                'unit' => $collection['unit'],
                'min_qty' => $collection['min_qty'],
                'refundable' => $collection['refundable'],
                'unit_price' => $collection['unit_price'],
                'purchase_price' => $collection['purchase_price'],
                'tax' => $collection['tax'],
                'discount' => $collection['discount'],
                'discount_type' => $collection['discount_type'],
                'current_stock' => $collection['current_stock'],
                'details' => $collection['details'],
                'video_provider' => 'youtube',
                'video_url' => $collection['youtube_video_url'],
                'images' => json_encode(['def.png']),
                'thumbnail' => 'def.png',
                'status' => 0,
                'colors' => json_encode([]),
                'attributes' => json_encode([]),
                'choice_options' => json_encode([]),
                'variation' => json_encode([]),
                'featured_status' => 1,
                'added_by' => 'seller',
                'user_id' => auth('seller')->id(),
            ]);
        }
        DB::table('products')->insert($data);
        Toastr::success(count($data).' - Products imported successfully!');

        return back();
    }

    public function bulk_export_data()
    {
        $products = Product::where(['added_by' => 'seller', 'user_id' => \auth('seller')->id()])->get();
        //export from product
        $storage = [];
        foreach ($products as $item) {
            $category_id = 0;
            $sub_category_id = 0;
            $sub_sub_category_id = 0;
            foreach (json_decode($item->category_ids, true) as $category) {
                if ($category['position'] == 1) {
                    $category_id = $category['id'];
                } elseif ($category['position'] == 2) {
                    $sub_category_id = $category['id'];
                } elseif ($category['position'] == 3) {
                    $sub_sub_category_id = $category['id'];
                }
            }
            $storage[] = [
                'name' => $item->name,
                'category_id' => $category_id,
                'sub_category_id' => $sub_category_id,
                'sub_sub_category_id' => $sub_sub_category_id,
                'brand_id' => $item->brand_id,
                'unit' => $item->unit,
                'min_qty' => $item->min_qty,
                'refundable' => $item->refundable,
                'youtube_video_url' => $item->video_url,
                'unit_price' => $item->unit_price,
                'purchase_price' => $item->purchase_price,
                'tax' => $item->tax,
                'discount' => $item->discount,
                'discount_type' => $item->discount_type,
                'current_stock' => $item->current_stock,
                'details' => $item->details,
            ];
        }

        return (new FastExcel($storage))->download('products.xlsx');
    }
}
