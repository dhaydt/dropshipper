<?php

namespace App\Http\Controllers\Web;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use App\CPU\ProductManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Brand;
use App\Model\BusinessSetting;
use App\Model\Cart;
use App\Model\CartShipping;
use App\Model\Category;
use App\Model\Contact;
use App\Model\DealOfTheDay;
use App\Model\FlashDeal;
use App\Model\FlashDealProduct;
use App\Model\HelpTopic;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\Model\RequestProduct;
use App\Model\Review;
use App\Model\Seller;
use App\Model\ShippingAddress;
use App\Model\Shop;
use App\Model\Wishlist;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WebController extends Controller
{
    public function reminder_flash_deal()
    {
        $date = Carbon::now()->addDay(3)->format('Y-m-d');
        $check = FlashDeal::where('status', 1)->get();
        if ($check) {
            foreach ($check as $c) {
                if ($c->end_date !== null) {
                    if ($c->end_date->format('Y-m-d') == $date) {
                        $users = User::where('is_phone_verified', 1)->get();
                        $seller = Seller::where(['is_phone_verified' => 1, 'status' => 'approved'])->get();
                        $data = [
                            'title' => 'Flash deal '.$c->title.' akan berakhir!',
                            'description' => $c->title.' akan berakhir besok!',
                            'image' => 'assets/front-end/img/ezren_logo.png',
                            'order_id' => 0,
                        ];

                        foreach ($users as $u) {
                            $fcm = $u->cm_firebase_token;
                            if ($fcm !== null) {
                                Helpers::send_push_notif_to_device($fcm, $data);
                            }
                        }

                        foreach ($seller as $s) {
                            $fcms = $s->cm_firebase_token;
                            if ($fcms !== null) {
                                Helpers::send_push_notif_to_device($fcms, $data);
                            }
                        }
                    }
                }
            }

            return 'work';
        }

        return 'no';
    }

    public function request_product(Request $request)
    {
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

        Toastr::success('Berhasil mengirim permintaan produk!');

        return redirect()->back();
    }

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

    public function maintenance_mode()
    {
        $maintenance_mode = Helpers::get_business_settings('maintenance_mode') ?? 0;
        if ($maintenance_mode) {
            return view('web-views.maintenance-mode');
        }

        return redirect()->route('home');
    }

    public function close_banner()
    {
        session()->put('hide_banner', true);
    }

    public function home()
    {
        session()->forget('category');
        $home_categories = Category::where('home_status', true)->get();
        $home_categories->map(function ($data) {
            $data['products'] = Product::active()->whereJsonContains('category_ids', ['id' => (string) $data['id']])->inRandomOrder()->take(12)->get();
        });
        //products based on top seller
        $top_sellers = Seller::approved()->with('shop')
            ->withCount(['orders'])->orderBy('orders_count', 'DESC')->take(15)->get();
        //end

        //feature products finding based on selling
        $featured_products = Product::with(['reviews'])->active()
            ->where('featured', 1)
            ->withCount(['order_details'])->orderBy('order_details_count', 'DESC')
            ->take(12)
            ->get();
        //end

        $latest_products = Product::with(['reviews'])->active()->orderBy('id', 'desc')->take(10)->get();
        $categories = Category::where('position', 0)->take(12)->get();
        $brands = Brand::take(15)->get();
        //best sell product
        $bestSellProduct = OrderDetail::with('product.reviews')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('COUNT(product_id) as count'))
            ->groupBy('product_id')
            ->orderBy('count', 'desc')
            ->take(4)
            ->get();
        //Top rated
        $topRated = Review::with('product')
            ->whereHas('product', function ($query) {
                $query->active();
            })
            ->select('product_id', DB::raw('AVG(rating) as count'))
            ->groupBy('product_id')
            ->orderBy('count', 'desc')
            ->take(4)
            ->get();

        if ($bestSellProduct->count() == 0) {
            $bestSellProduct = $latest_products;
        }

        if ($topRated->count() == 0) {
            $topRated = $bestSellProduct;
        }

        $deal_of_the_day = DealOfTheDay::join('products', 'products.id', '=', 'deal_of_the_days.product_id')->select('deal_of_the_days.*', 'products.unit_price')->where('deal_of_the_days.status', 1)->first();
        // if (env('APP_ENV') == 'production') {
        //     return 'ON MAINTENANCE';
        // } else {
        //     return view('web-views.home', compact('featured_products', 'topRated', 'bestSellProduct', 'latest_products', 'categories', 'brands', 'deal_of_the_day', 'top_sellers', 'home_categories'));
        // }
        return view('web-views.home', compact('featured_products', 'topRated', 'bestSellProduct', 'latest_products', 'categories', 'brands', 'deal_of_the_day', 'top_sellers', 'home_categories'));
    }

    public function flash_deals($id)
    {
        $deal = FlashDeal::with(['products.product.reviews'])->where(['id' => $id, 'status' => 1])->whereDate('start_date', '<=', date('Y-m-d'))->whereDate('end_date', '>=', date('Y-m-d'))->first();

        $discountPrice = FlashDealProduct::with(['product'])->whereHas('product', function ($query) {
            $query->active();
        })->get()->map(function ($data) {
            return [
                'discount' => $data->discount,
                'sellPrice' => $data->product->unit_price,
                'discountedPrice' => $data->product->unit_price - $data->discount,
            ];
        })->toArray();
        // dd($deal->toArray());

        if (isset($deal)) {
            return view('web-views.deals', compact('deal', 'discountPrice'));
        }
        Toastr::warning(translate('not_found'));

        return back();
    }

    public function search_shop(Request $request)
    {
        $key = explode(' ', $request['shop_name']);
        $sellers = Shop::where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('name', 'like', "%{$value}%");
            }
        })->whereHas('seller', function ($query) {
            return $query->where(['status' => 'approved']);
        })->paginate(30);

        return view('web-views.sellers', compact('sellers'));
    }

    public function all_categories()
    {
        $categories = Category::all();

        return view('web-views.categories', compact('categories'));
    }

    public function categories_by_category($id)
    {
        $category = Category::with(['childes.childes'])->where('id', $id)->first();

        return response()->json([
            'view' => view('web-views.partials._category-list-ajax', compact('category'))->render(),
        ]);
    }

    public function all_brands()
    {
        $brands = Brand::paginate(24);

        return view('web-views.brands', compact('brands'));
    }

    public function all_sellers()
    {
        $sellers = Shop::whereHas('seller', function ($query) {
            return $query->approved();
        })->paginate(24);

        return view('web-views.sellers', compact('sellers'));
    }

    public function seller_profile($id)
    {
        $seller_info = Seller::find($id);

        return view('web-views.seller-profile', compact('seller_info'));
    }

    public function searched_products(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'Product name is required!',
        ]);

        $result = ProductManager::search_products($request['name']);
        $products = $result['products'];

        if ($products == null) {
            $result = ProductManager::translated_product_search($request['name']);
            $products = $result['products'];
        }

        return response()->json([
            'result' => view('web-views.partials._search-result', compact('products'))->render(),
        ]);
    }

    public function checkout_details(Request $request)
    {
        $data = [
            'name' => 'Keranjang Belanja',
        ];
        session()->put('category', $data);

        if (auth('seller')->check()) {
            $country = DB::table('country')->get();

            return view('web-views.addAddress', compact('country'));
        }
        $check = session()->has('customer_address');
        if (session()->get('user_is') == 'customer') {
            $userShipp = ShippingAddress::where('customer_id', auth('customer')->id())->get();
            if (count($userShipp) < 1) {
                // dd('no distrcit');
                $country = DB::table('country')->get();

                Toastr::warning(translate('Please fill your address first'));

                return view('web-views.addAddress', compact('country'));
            }
        } elseif (session()->get('user_is') == 'dropship' && !$check) {
            $country = 'ID';
            Toastr::warning(translate('Please fill your customer address first'));

            return view('web-views.addAddress', compact('country'));
        }

        $cart_group_ids = CartManager::get_cart_group_ids();
        // dd($cart_group_ids);
        // if (CartShipping::whereIn('cart_group_id', $cart_group_ids)->count() != count($cart_group_ids)) {
        //     Toastr::info(translate('select_shipping_method_first'));

        //     return redirect('shop-cart');
        // }

        if (count($cart_group_ids) > 0) {
            // session(['address_changed' => 0]);
            return view('web-views.shop-cart');
        }

        Toastr::info(translate('no_items_in_basket'));

        return redirect('/');
    }

    public function checkout_shipping(Request $request)
    {
        $data = [
            'name' => 'Alamat Pengiriman',
        ];
        session()->put('category', $data);

        if (auth('seller')->check()) {
            $country = DB::table('country')->get();

            return view('web-views.addAddress', compact('country'));
        }
        $check = session()->has('customer_address');
        if (session()->get('user_is') == 'customer') {
            $userShipp = ShippingAddress::where('customer_id', auth('customer')->id())->get();
            if (count($userShipp) < 1) {
                // dd('no distrcit');
                $country = DB::table('country')->get();

                Toastr::warning(translate('Please fill your address first'));

                return view('web-views.addAddress', compact('country'));
            }
        } elseif (session()->get('user_is') == 'dropship' && !$check) {
            $country = 'ID';
            Toastr::warning(translate('Please fill your customer address first'));

            return view('web-views.addAddress', compact('country'));
        }

        $cart_group_ids = CartManager::get_cart_group_ids();
        // dd($cart_group_ids);
        // if (CartShipping::whereIn('cart_group_id', $cart_group_ids)->count() != count($cart_group_ids)) {
        //     Toastr::info(translate('select_shipping_method_first'));

        //     return redirect('shop-cart');
        // }

        if (count($cart_group_ids) > 0) {
            // session(['address_changed' => 0]);
            return view('web-views.checkout-shipping');
        }

        Toastr::info(translate('no_items_in_basket'));

        return redirect('/');
    }

    public function shipping_method()
    {
        $data['name'] = 'Metode Pengiriman';
        session()->put('category', $data);
        $cart = \App\CPU\CartManager::get_cart();

        $cart_id = $cart[0]['cart_group_id'];
        $shipping = CartShipping::where('cart_group_id', $cart_id)->first();
        if (auth('customer')->check()) {
            session()->put('address_id', $shipping->address_id);
            $address = ShippingAddress::find($shipping->address_id);
        } elseif (auth('seller')->check()) {
            $address = ShippingAddress::where('slug', session()->get('customer_address'))->first();
        }

        return view('web-views.checkout-shipping-method', compact('address'));
    }

    public function checkout_payment()
    {
        $cart_group_ids = CartManager::get_cart_group_ids();
        if (CartShipping::whereIn('cart_group_id', $cart_group_ids)->count() != count($cart_group_ids)) {
            Toastr::info(translate('select_shipping_method_first'));

            return redirect('shop-cart');
        }

        if (session()->has('address_id') && count($cart_group_ids) > 0) {
            return view('web-views.checkout-payment');
        }

        Toastr::error(translate('incomplete_info'));

        return back();
    }

    public function checkout_complete(Request $request)
    {
        $data['name'] = 'Order Berhasil!';
        session()->put('category', $data);
        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        $order_id = '';
        foreach (CartManager::get_cart_group_ids() as $group_id) {
            $data = [
                'payment_method' => 'cash_on_delivery',
                'order_status' => 'pending',
                'payment_status' => 'unpaid',
                'transaction_ref' => '',
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
                'api' => false,
                'user_is' => 'customer',
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }
        CartManager::cart_clean();
        if ($order_id == '') {
            Toastr::success('Order Not Found');

            return redirect()->route('home');
        }

        $check = auth('seller')->check();

        $data = [
            'title' => 'Order kamu berhasil!',
            'description' => 'Silahkan lakukan pembayaran agar order dapat diproses',
            'order_id' => $order_id,
            'image' => 'def.png',
        ];
        $user_is = session()->get('user_is');

        if ($user_is == 'customer') {
            $fcm_token = auth('customer')->user()->cm_firebase_token;
        } else {
            $fcm_token = auth('seller')->user()->cm_firebase_token;
        }
        Helpers::send_push_notif_to_device($fcm_token, $data);

        $payment = Helpers::payment();
        session()->forget('customer_address');

        return view('web-views.checkout-complete', compact('order_id', 'payment'));
    }

    public function order_placed()
    {
        return view('web-views.checkout-complete');
    }

    public function shop_cart()
    {
        $cart = Cart::where(['customer_id' => auth('customer')->id(), 'buyer_is' => null])->get();
        // dd($cart)
        if (auth('customer')->check() && count($cart) > 0) {
            if (auth('customer')->user()->district == null) {
                // dd('no distrcit');
                $country = DB::table('country')->get();

                Toastr::warning(translate('Please fill your address first'));

                return view('web-views.addAddress', compact('country'));
            }

            $user = auth('customer')->id();
            $address = ShippingAddress::where('customer_id', $user)->first();

            // dd($address);
            session()->put('address_id', $address->id);

            return view('web-views.shop-cart');
        }

        $cart = Cart::where(['customer_id' => auth('seller')->id(), 'buyer_is' => 'dropship'])->get();

        if (auth('seller')->check() && count($cart) > 0) {
            $user = auth('seller')->id();
            $address = ShippingAddress::where('customer_id', $user)->orderBy('created_at', 'desc')->first();

            // dd($address);
            session()->put('address_id', $address->id);

            return view('web-views.shop-cart');
        }
        Toastr::info(translate('no_items_in_basket'));

        return redirect('/');
    }

    //for seller Shop

    public function seller_shop(Request $request, $id)
    {
        $product_ids = Product::active()
            ->when($id == 0, function ($query) {
                return $query->where(['added_by' => 'admin']);
            })
            ->when($id != 0, function ($query) use ($id) {
                return $query->where(['added_by' => 'seller'])
                    ->where('user_id', $id);
            })
            ->pluck('id')->toArray();

        $avg_rating = Review::whereIn('product_id', $product_ids)->avg('rating');
        $total_review = Review::whereIn('product_id', $product_ids)->count();
        $total_order = OrderDetail::whereIn('product_id', $product_ids)->groupBy('order_id')->count();

        //finding category ids
        $products = Product::whereIn('id', $product_ids)->paginate(12);

        $category_info = [];
        foreach ($products as $product) {
            array_push($category_info, $product['category_ids']);
        }

        $category_info_decoded = [];
        foreach ($category_info as $info) {
            array_push($category_info_decoded, json_decode($info));
        }

        $category_ids = [];
        foreach ($category_info_decoded as $decoded) {
            foreach ($decoded as $info) {
                array_push($category_ids, $info->id);
            }
        }

        $categories = [];
        foreach ($category_ids as $category_id) {
            $category = Category::with(['childes.childes'])->where('position', 0)->find($category_id);
            if ($category != null) {
                array_push($categories, $category);
            }
        }
        $categories = array_unique($categories);
        //end

        //products search
        if ($request->product_name) {
            $products = Product::active()
                ->when($id == 0, function ($query) {
                    return $query->where(['added_by' => 'admin']);
                })
                ->when($id != 0, function ($query) use ($id) {
                    return $query->where(['added_by' => 'seller'])
                        ->where('user_id', $id);
                })
                ->where('name', 'like', $request->product_name.'%')
                ->paginate(12);
        } elseif ($request->category_id) {
            $products = Product::active()
                ->when($id == 0, function ($query) {
                    return $query->where(['added_by' => 'admin']);
                })
                ->when($id != 0, function ($query) use ($id) {
                    return $query->where(['added_by' => 'seller'])
                        ->where('user_id', $id);
                })
                ->whereJsonContains('category_ids', [
                    ['id' => strval($request->category_id)],
                ])->paginate(12);
        }

        if ($id == 0) {
            $shop = [
                'id' => 0,
                'name' => Helpers::get_business_settings('company_name'),
            ];
        } else {
            $shop = Shop::where('seller_id', $id)->first();
            if (isset($shop) == false) {
                Toastr::error(translate('shop_does_not_exist'));

                return back();
            }
        }

        return view('web-views.shop-page', compact('products', 'shop', 'categories'))
            ->with('seller_id', $id)
            ->with('total_review', $total_review)
            ->with('avg_rating', $avg_rating)
            ->with('total_order', $total_order);
    }

    //ajax filter (category based)
    public function seller_shop_product(Request $request, $id)
    {
        $products = Product::active()->with('shop')->where(['added_by' => 'seller'])
            ->where('user_id', $id)
            ->whereJsonContains('category_ids', [
                ['id' => strval($request->category_id)],
            ])
            ->paginate(12);
        $shop = Shop::where('seller_id', $id)->first();
        if ($request['sort_by'] == null) {
            $request['sort_by'] = 'latest';
        }

        if ($request->ajax()) {
            return response()->json([
                'view' => view('web-views.products._ajax-products', compact('products'))->render(),
            ], 200);
        }

        return view('web-views.shop-page', compact('products', 'shop'))->with('seller_id', $id);
    }

    public function quick_view(Request $request)
    {
        $product = ProductManager::get_product($request->product_id);
        $order_details = OrderDetail::where('product_id', $product->id)->get();
        $wishlists = Wishlist::where('product_id', $product->id)->get();
        $countOrder = count($order_details);
        $countWishlist = count($wishlists);
        $relatedProducts = Product::with(['reviews'])->where('category_ids', $product->category_ids)->where('id', '!=', $product->id)->limit(12)->get();

        return response()->json([
            'success' => 1,
            'view' => view('web-views.partials._quick-view-data', compact('product', 'countWishlist', 'countOrder', 'relatedProducts'))->render(),
        ]);
    }

    public function product($slug)
    {
        $product = Product::active()->with(['reviews'])->where('slug', $slug)->first();
        if ($product != null) {
            $countOrder = OrderDetail::where('product_id', $product->id)->count();
            $countWishlist = Wishlist::where('product_id', $product->id)->count();
            $relatedProducts = Product::with(['reviews'])->active()->where('category_ids', $product->category_ids)->where('id', '!=', $product->id)->limit(12)->get();
            $deal_of_the_day = DealOfTheDay::where('product_id', $product->id)->where('status', 1)->first();
            $data['name'] = 'Detail Produk';
            session()->put('category', $data);

            return view('web-views.products.details', compact('product', 'countWishlist', 'countOrder', 'relatedProducts', 'deal_of_the_day'));
        }

        Toastr::error(translate('not_found'));

        return back();
    }

    public function products(Request $request)
    {
        $request['sort_by'] == null ? $request['sort_by'] == 'latest' : $request['sort_by'];

        $porduct_data = Product::active()->with(['reviews']);

        if ($request['data_from'] == 'category') {
            $products = $porduct_data->get();
            $product_ids = [];
            foreach ($products as $product) {
                foreach (json_decode($product['category_ids'], true) as $category) {
                    if ($category['id'] == $request['id']) {
                        array_push($product_ids, $product['id']);
                    }
                }
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'brand') {
            $query = $porduct_data->where('brand_id', $request['id']);
        }

        if ($request['data_from'] == 'latest') {
            $query = $porduct_data->orderBy('id', 'DESC');
        }

        if ($request['data_from'] == 'top-rated') {
            $reviews = Review::select('product_id', DB::raw('AVG(rating) as count'))
                ->groupBy('product_id')
                ->orderBy('count', 'desc')->get();
            $product_ids = [];
            foreach ($reviews as $review) {
                array_push($product_ids, $review['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'best-selling') {
            $details = OrderDetail::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy('count', 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'most-favorite') {
            $details = Wishlist::with('product')
                ->select('product_id', DB::raw('COUNT(product_id) as count'))
                ->groupBy('product_id')
                ->orderBy('count', 'desc')
                ->get();
            $product_ids = [];
            foreach ($details as $detail) {
                array_push($product_ids, $detail['product_id']);
            }
            $query = $porduct_data->whereIn('id', $product_ids);
        }

        if ($request['data_from'] == 'featured') {
            $query = Product::with(['reviews'])->active()->where('featured', 1);
        }

        if ($request['data_from'] == 'search') {
            $key = explode(' ', $request['name']);
            $query = $porduct_data->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%");
                }
            });
        }

        if ($request['sort_by'] == 'latest') {
            $fetched = $query->latest();
        } elseif ($request['sort_by'] == 'low-high') {
            $fetched = $query->orderBy('unit_price', 'ASC');
        } elseif ($request['sort_by'] == 'high-low') {
            $fetched = $query->orderBy('unit_price', 'DESC');
        } elseif ($request['sort_by'] == 'a-z') {
            $fetched = $query->orderBy('name', 'ASC');
        } elseif ($request['sort_by'] == 'z-a') {
            $fetched = $query->orderBy('name', 'DESC');
        } else {
            $fetched = $query;
        }

        if ($request['min_price'] != null || $request['max_price'] != null) {
            $fetched = $fetched->whereBetween('unit_price', [Helpers::convert_currency_to_usd($request['min_price']), Helpers::convert_currency_to_usd($request['max_price'])]);
        }

        $data = [
            'id' => $request['id'],
            'name' => $request['name'],
            'data_from' => $request['data_from'],
            'sort_by' => $request['sort_by'],
            'page_no' => $request['page'],
            'min_price' => $request['min_price'],
            'max_price' => $request['max_price'],
        ];

        $products = $fetched->paginate(20)->appends($data);

        if ($request->ajax()) {
            return response()->json([
                'view' => view('web-views.products._ajax-products', compact('products'))->render(),
            ], 200);
        }
        if ($request['data_from'] == 'category') {
            $cat = Category::find((int) $request['id']);
            $data['brand_name'] = $cat ? $cat->name : 'Kesalahan data kategori';
        }
        if ($request['data_from'] == 'brand') {
            $data['brand_name'] = Brand::find((int) $request['id'])->name;
        }

        session()->put('category', $data);

        return view('web-views.products.view', compact('products', 'data'), $data);
    }

    public function viewWishlist()
    {
        $wishlists = Wishlist::where('customer_id', auth('customer')->id())->get();
        $data = [
            'name' => 'wishlist',
        ];
        session()->put('category', $data);
        session()->put('ordered', true);

        return view('web-views.users-profile.account-wishlist', compact('wishlists'));
    }

    public function storeWishlist(Request $request)
    {
        if ($request->ajax()) {
            if (auth('customer')->check()) {
                $wishlist = Wishlist::where('customer_id', auth('customer')->id())->where('product_id', $request->product_id)->first();
                if (empty($wishlist)) {
                    $wishlist = new Wishlist();
                    $wishlist->customer_id = auth('customer')->id();
                    $wishlist->product_id = $request->product_id;
                    $wishlist->save();

                    $countWishlist = Wishlist::where('customer_id', auth('customer')->id())->get();
                    $data = 'Product has been added to wishlist';

                    $product_count = Wishlist::where(['product_id' => $request->product_id])->count();
                    session()->put('wish_list', Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());

                    return response()->json(['success' => $data, 'value' => 1, 'count' => count($countWishlist), 'id' => $request->product_id, 'product_count' => $product_count]);
                } else {
                    $data = 'Product already added to wishlist';

                    return response()->json(['error' => $data, 'value' => 2]);
                }
            } else {
                $data = translate('login_first');

                return response()->json(['error' => $data, 'value' => 0]);
            }
        }
    }

    public function deleteWishlist(Request $request)
    {
        Wishlist::where(['product_id' => $request['id'], 'customer_id' => auth('customer')->id()])->delete();
        $data = 'Product has been remove from wishlist!';
        $wishlists = Wishlist::where('customer_id', auth('customer')->id())->get();
        session()->put('wish_list', Wishlist::where('customer_id', auth('customer')->user()->id)->pluck('product_id')->toArray());

        return response()->json([
            'success' => $data,
            'count' => count($wishlists),
            'id' => $request->id,
            'wishlist' => view('web-views.partials._wish-list-data', compact('wishlists'))->render(),
        ]);
    }

    //for HelpTopic
    public function helpTopic()
    {
        $helps = HelpTopic::Status()->latest()->get();

        return view('web-views.help-topics', compact('helps'));
    }

    //for Contact US Page
    public function contacts()
    {
        return view('web-views.contacts');
    }

    public function about_us()
    {
        $about_us = BusinessSetting::where('type', 'about_us')->first();

        return view('web-views.about-us', [
            'about_us' => $about_us,
        ]);
    }

    public function termsandCondition()
    {
        $terms_condition = BusinessSetting::where('type', 'terms_condition')->first();

        return view('web-views.terms', compact('terms_condition'));
    }

    public function privacy_policy()
    {
        $privacy_policy = BusinessSetting::where('type', 'privacy_policy')->first();

        return view('web-views.privacy-policy', compact('privacy_policy'));
    }

    //order Details

    public function orderdetails()
    {
        return view('web-views.orderdetails');
    }

    public function chat_for_product(Request $request)
    {
        return $request->all();
    }

    public function supportChat()
    {
        return view('web-views.users-profile.profile.supportTicketChat');
    }

    public function error()
    {
        return view('web-views.404-error-page');
    }

    public function contact_store(Request $request)
    {
        $request->validate([
            'mobile_number' => 'required',
            'subject' => 'required',
            'message' => 'required',
        ], [
            'mobile_number.required' => 'Mobile Number is Empty!',
            'subject.required' => ' Subject is Empty!',
            'message.required' => 'Message is Empty!',
        ]);
        $contact = new Contact();
        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->mobile_number = $request->mobile_number;
        $contact->subject = $request->subject;
        $contact->message = $request->message;
        $contact->save();
        Toastr::success(translate('Your Message Send Successfully'));

        return back();
    }
}
