<?php

namespace App\Http\Controllers\api\v2\seller;

use App\CPU\BackEndHelper;
use App\CPU\Convert;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\OrderTransaction;
use App\Model\Product;
use App\Model\Review;
use App\Model\Seller;
use App\Model\SellerWallet;
use App\Model\Shop;
use App\Model\Wishlist;
use App\Model\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SellerController extends Controller
{
    public function put_fcm(Request $request)
    {
        $check = Helpers::get_seller_by_token($request);
        if ($check['success'] == 1) {
            if ($request['cm_firebase_token'] == null) {
                return response()->json('cm_firebase_token required!');
            }
            $seller = Seller::find($check['data']['id']);
            if (!$seller) {
                return response()->json(['status' => 'fail', 'message' => 'dropship not found']);
            } else {
                $seller->cm_firebase_token = $request['cm_firebase_token'];
                $seller->save();

                return response()->json(['status' => 'success', 'message' => 'firebase_token updated successfully']);
            }
        } else {
            return response()->json(['status' => 'fail', 'message' => 'auth-001, unauthorized']);
        }
    }

    public function add_to_wishlist(Request $request)
    {
        $user = Helpers::get_seller_by_token($request);
        if ($user['success'] == 1) {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => Helpers::error_processor($validator)], 403);
            }

            $wishlist = Wishlist::where('customer_id', $user['data']['id'])->where('product_id', $request->product_id)->first();

            if (empty($wishlist)) {
                $wishlist = new Wishlist();
                $wishlist->customer_id = $user['data']['id'];
                $wishlist->product_id = $request->product_id;
                $wishlist->user_is = 'dropship';
                $wishlist->save();

                return response()->json(['message' => translate('successfully added!')], 200);
            }

            return response()->json(['message' => translate('Already in your wishlist')], 200);
        } else {
            return response()->json(['message' => translate('Already in your wishlist')], 200);
        }
    }

    public function remove_from_wishlist(Request $request)
    {
        $user = Helpers::get_seller_by_token($request);
        if ($user['success'] == 1) {
            $validator = Validator::make($request->all(), [
                'product_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => Helpers::error_processor($validator)], 403);
            }

            $wishlist = Wishlist::where(['customer_id' => $user['data']['id'], 'user_is' => 'dropship'])->where('product_id', $request->product_id)->first();

            if (!empty($wishlist)) {
                Wishlist::where(['customer_id' => $user['data']['id'], 'user_is' => 'dropship', 'product_id' => $request->product_id])->delete();

                return response()->json(['message' => translate('successfully removed!')], 200);
            }

            return response()->json(['message' => translate('No such data found!')], 404);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'auth-001, unauthorized']);
        }
    }

    public function wish_list(Request $request)
    {
        $user = Helpers::get_seller_by_token($request);
        if ($user['success'] == 1) {
            $wish = Wishlist::whereHas('product')->where(['customer_id' => $user['data']['id'], 'user_is' => 'dropship'])->get();

            return response()->json($wish, 200);
        } else {
            return response()->json(['status' => 'fail', 'message' => 'auth-001, unauthorized']);
        }
    }

    public function shop_info(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $product_ids = Product::where(['user_id' => $seller['id'], 'added_by' => 'seller'])->pluck('id')->toArray();
            $order = Order::where(['customer_id' => $data['data']['id'], 'user_is' => 'dropship', 'payment_status' => 'paid']);
            $shop = Shop::where(['seller_id' => $seller['id']])->first();
            $shop['total_paid_transaction'] = $order->count();
            $shop['total_selling'] = array_sum($order->pluck('order_amount')->toArray());
            $oreder_id = $order->pluck('id');
            $earning = [];
            foreach ($oreder_id as $id) {
                $details = OrderDetail::where('order_id', $id)->get();
                foreach ($details as $d) {
                    $price = json_decode($d->product_details)->unit_price;
                    $dropship = json_decode($d->product_details)->dropship;

                    array_push($earning, $price - $dropship * $d->qty);
                }
            }

            $shop['total_earning'] = array_sum($earning);
            // $shop['total_selling'] = BackEndHelper::set_symbol(array_sum($order->pluck('order_amount')->toArray()));
            // $shop['total_earning'] = BackEndHelper::set_symbol(0);
            $shop['rating'] = round(Review::whereIn('product_id', $product_ids)->avg('rating'), 3);
            $shop['rating_count'] = Review::whereIn('product_id', $product_ids)->count();
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        return response()->json($shop, 200);
    }

    public function shop_product_reviews(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
            $product_ids = Product::where(['user_id' => $seller['id'], 'added_by' => 'seller'])->pluck('id')->toArray();
            $reviews = Review::whereIn('product_id', $product_ids)->with(['product', 'customer'])->get();
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        return response()->json($reviews, 200);
    }

    public function seller_info(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        return response()->json(Seller::with(['wallet', 'shop'])->withCount(['product', 'orders'])->where(['id' => $seller['id']])->first(), 200);
    }

    public function update_password(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $validator = Validator::make($request->all(), [
                'password' => 'required',
            ], [
                'password.required' => 'Password field is required',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => Helpers::error_processor($validator)], 403);
            }
            $seller = $data['data'];

            Seller::where(['id' => $seller['id']])->update([
                'password' => $request['password'] != null ? bcrypt($request['password']) : Seller::where(['id' => $seller['id']])->first()->password,
            ]);

            return response()->json(translate('Password changed successfully!'), 200);
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }
    }

    public function shop_info_update(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $old_image = Shop::where(['seller_id' => $seller['id']])->first()->image;
        $image = $request->file('image');
        if ($image != null) {
            $imageName = ImageManager::update('shop/', $old_image, 'png', $request->file('image'));
        } else {
            $imageName = $old_image;
        }

        Shop::where(['seller_id' => $seller['id']])->update([
            'name' => $request['name'],
            'address' => $request['address'],
            'contact' => $request['contact'],
            'image' => $imageName,
            'updated_at' => now(),
        ]);

        return response()->json(translate('Shop info updated successfully!'), 200);
    }

    public function seller_info_update(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);

        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $old_image = Seller::where(['id' => $seller['id']])->first()->image;
        $image = $request->file('image');
        if ($image != null) {
            $imageName = ImageManager::update('seller/', $old_image, 'png', $request->file('image'));
        } else {
            $imageName = $old_image;
        }

        $shop = Shop::where(['seller_id' => $seller['id']])->first();
        $shop->name = $request->shop_name;

        Seller::where(['id' => $seller['id']])->update([
            'f_name' => $request['name'],
            'l_name' => null,
            'bank_name' => $request['bank_name'],
            'branch' => $request['branch'],
            'phone' => $request['phone'],
            'email' => $request['email'],
            'account_no' => $request['account_no'],
            'holder_name' => $request['holder_name'],
            'password' => $request['password'] != null ? bcrypt($request['password']) : Seller::where(['id' => $seller['id']])->first()->password,
            'image' => $imageName,
            'updated_at' => now(),
        ]);

        $shop->save();

        if ($request['password'] != null) {
            Seller::where(['id' => $seller['id']])->update([
                'auth_token' => Str::random('50'),
            ]);
        }

        return response()->json(translate('Info updated successfully!'), 200);
    }

    public function withdraw_request(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $wallet = SellerWallet::where('seller_id', $seller['id'])->first();
        if (($wallet->total_earning) >= Convert::usd($request['amount']) && $request['amount'] > 1) {
            DB::table('withdraw_requests')->insert([
                'seller_id' => $seller['id'],
                'amount' => Convert::usd($request['amount']),
                'transaction_note' => null,
                'approved' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $wallet->total_earning -= BackEndHelper::currency_to_usd($request['amount']);
            $wallet->pending_withdraw += BackEndHelper::currency_to_usd($request['amount']);
            $wallet->save();

            return response()->json(translate('Withdraw request sent successfully!'), 200);
        }

        return response()->json(translate('Invalid withdraw request'), 400);
    }

    public function close_withdraw_request(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $withdraw_request = WithdrawRequest::find($request['id']);
        $wallet = SellerWallet::where('seller_id', $seller['id'])->first();

        if (isset($withdraw_request) && $withdraw_request->approved == 0) {
            $wallet->total_earning += BackEndHelper::currency_to_usd($withdraw_request['amount']);
            $wallet->pending_withdraw -= BackEndHelper::currency_to_usd($request['amount']);
            $wallet->save();
            $withdraw_request->delete();

            return response()->json(translate('Withdraw request has been closed successfully!'), 200);
        }

        return response()->json(translate('Withdraw request is invalid'), 400);
    }

    public function transaction(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $transaction = WithdrawRequest::where('seller_id', $seller['id'])->latest()->get();

        return response()->json($transaction, 200);
    }

    public function monthly_earning(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');
        $seller_data = '';
        $seller_earnings = OrderTransaction::where([
            'seller_is' => 'seller',
            'seller_id' => $seller['id'],
            'status' => 'disburse',
        ])->select(
            DB::raw('IFNULL(sum(seller_amount),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; ++$inc) {
            $default = 0;
            foreach ($seller_earnings as $match) {
                if ($match['month'] == $inc) {
                    $default = $match['sums'];
                }
            }
            $seller_data .= $default.',';
        }

        return response()->json($seller_data, 200);
    }

    public function monthly_commission_given(Request $request)
    {
        $data = Helpers::get_seller_by_token($request);
        if ($data['success'] == 1) {
            $seller = $data['data'];
        } else {
            return response()->json([
                'auth-001' => translate('Your existing session token does not authorize you any more'),
            ], 401);
        }

        $from = \Carbon\Carbon::now()->startOfYear()->format('Y-m-d');
        $to = Carbon::now()->endOfYear()->format('Y-m-d');

        $commission_data = '';
        $commission_earnings = OrderTransaction::where([
            'seller_is' => 'seller',
            'seller_id' => $seller['id'],
            'status' => 'disburse',
        ])->select(
            DB::raw('IFNULL(sum(admin_commission),0) as sums'),
            DB::raw('YEAR(created_at) year, MONTH(created_at) month')
        )->whereBetween('created_at', [$from, $to])->groupby('year', 'month')->get()->toArray();
        for ($inc = 1; $inc <= 12; ++$inc) {
            $default = 0;
            foreach ($commission_earnings as $match) {
                if ($match['month'] == $inc) {
                    $default = $match['sums'];
                }
            }
            $commission_data .= $default.',';
        }

        return response()->json($commission_data, 200);
    }
}
