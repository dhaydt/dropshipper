<?php

namespace App\Http\Controllers\Web;

use App\CPU\CartManager;
use App\CPU\Helpers;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\Cart;
use App\Model\Order;
use App\Model\Seller;
use App\Model\ShippingAddress;
use App\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Xendit\Xendit;

class XenditPaymentController extends Controller
{
    public function index()
    {
        Xendit::setApiKey(config('xendit.apikey'));

        // $createVA = \Xendit\VirtualAccounts::create($params);
        // var_dump($createVA);
        $bank = \Xendit\VirtualAccounts::getVABanks();

        return view('admin-views.business-settings.payment-method.xendit', compact('bank'));
    }

    public function getListVa()
    {
        Xendit::setApiKey(config('xendit.apikey'));

        // $createVA = \Xendit\VirtualAccounts::create($params);
        // var_dump($createVA);
        $getVABank = \Xendit\VirtualAccounts::getVABanks();

        return response()->json([
            'data' => $getVABank,
        ])->setStatusCode('200');
    }

    public function createVa(Request $request)
    {
        // dd($request);
        Xendit::setApiKey(config('xendit.apikey'));

        $params = ['external_id' => \uniqid(),
        'bank_code' => $request->bank,
        'name' => $request->name,
        'expected_amount' => (int) $request->price,
        'is_closed' => true,
        'is_single_use' => true,
        'expiration_date' => Carbon::now()->addDay(1)->toISOString(),
    ];

        $virtual = \Xendit\VirtualAccounts::create($params);
        dd($virtual);

        return view('web-views.finish-payment', compact('virtual'));

        // return view('admin-views.business-settings.payment-method.xendit-virtual-account', compact('virtual'));
    }

    public function invoice(Request $request)
    {
        // dd($request);
        $order = Order::find($request->order_id);
        if ($order['order_status'] == 'processing') {
            Toastr::success('Order ini sudah dibayar!');

            return redirect()->back();
        }

        if ($order['order_status'] !== 'pending') {
            Toastr::warning('Status order ini sudah berubah, tidak dapat melakukan pembayaran!');

            return redirect()->back();
        }
        $customer = auth('customer')->user();
        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $value = $order['order_amount'];
        $tran = OrderManager::gen_unique_id();
        $order = Order::find($request->order_id);
        $order_address = json_decode($order->shipping_address_data);
        $type = strtoupper($request['type']);

        session()->put('transaction_ref', $tran);

        Xendit::setApiKey(config('xendit.apikey'));

        $products = [];
        foreach (CartManager::get_cart() as $detail) {
            array_push($products, [
                'name' => $detail->product['name'],
            ]);
        }
        // dd($products);
        if (auth('customer')->check()) {
            $name = $customer->name ? $customer->name : $customer->f_name;
            $phone = $customer->phone;
            $address = $order_address->district.', '.$order_address->city.', '.$order_address->province;
            $id = $customer->id;
            $email = $customer->email ? $customer->email : 'invalid@customer.email';
        }
        if (session()->get('user_is') == 'dropship') {
            $customer = auth('seller')->user();
            $custom = ShippingAddress::where('slug', session()->get('customer_address'))->first();
            $name = $custom->contact_person_name;
            $phone = $custom->phone;
            $address = $order_address->district.', '.$order_address->city.', '.$order_address->province;
            $id = $custom->customer_id;
            $email = $customer->email ? $customer->email : 'invalid@dropship.email';
        }

        $user = [
            'given_names' => $name ? $name : 'invalid name',
            'email' => $email,
            'mobile_number' => $phone ? $phone : '0000',
            'address' => $address ? $address : 'Invalid address data',
        ];

        // dd($user);

        $params = [
            'external_id' => 'ezren'.$phone.$id,
            'amount' => $value,
            'payer_email' => $email,
            'description' => env('APP_NAME'),
            'payment_methods' => [$type],
            'fixed_va' => true,
            'should_send_email' => true,
            'customer' => $user,
            // 'items' => $products,
            'success_redirect_url' => env('APP_URL').'/xendit-payment/success/'.$type.'/'.$request->order_id,
        ];

        // dd($params);

        $checkout_session = \Xendit\Invoice::create($params);
        // $order_ids = [];
        // foreach (CartManager::get_cart_group_ids() as $group_id) {
        //     $data = [
        //         'payment_method' => 'xendit_payment',
        //         'order_status' => 'pending',
        //         'payment_status' => 'unpaid',
        //         'transaction_ref' => session('transaction_ref'),
        //         'order_group_id' => $tran,
        //         'cart_group_id' => $group_id,
        //     ];
        //     $order_id = OrderManager::generate_order($data);
        //     array_push($order_ids, $order_id);
        // }

        return redirect()->away($checkout_session['invoice_url']);
    }

    public function success($type, $group)
    {
        $order = Order::find($group);

        $order->order_status = 'processing';
        $order->payment_status = 'paid';
        $order->transaction_ref = session('transaction_ref');
        $order->payment_method = $type;
        $order->save();

        $order_id = $group;

        $user_is = $order->user_is;

        if ($user_is == 'dropship') {
            $user = Seller::where('id', $order->customer_id)->first();
        } else {
            $user = User::where('id', $order->customer_id)->first();
        }
        if ($user) {
            if ($user->cm_firebase_token !== null) {
                $fcm_token = $user->cm_firebase_token;

                $data = [
                    'title' => 'Pembayaran berhasil!',
                    'description' => 'Order anda sedang diproses!',
                    'order_id' => $order_id,
                    'image' => 'def.png',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        }
        session()->forget('customer_address');
        if (auth('customer')->check() || auth('seller')->check()) {
            Toastr::success('Pembayaran Berhasil.');

            if (auth('customer')->check()) {
                return redirect()->route('account-oder');
            } else {
                return redirect()->route('seller.orders.list', ['all']);
            }
        }

        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function OldsuccessApi($type, $group, $user_is)
    {
        // $order = Order::find($request->id);

        $unique_id = OrderManager::gen_unique_id();
        $cart = Cart::where('cart_group_id', $group);
        // dd($cart);
        $cartGen = $cart->pluck('cart_group_id')->toArray();
        $order_ids = [];
        foreach ($cartGen as $group_id) {
            $data = [
                'payment_method' => 'Virtual Account'.$type,
                'order_status' => 'processing',
                'payment_status' => 'paid',
                'transaction_ref' => session('transaction_ref'),
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
                'api' => true,
                'user_is' => $user_is,
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }
        foreach ($cart->get() as $c) {
            $c->delete();
        }
        session()->forget('customer_address');
        if (auth('customer')->check() || session()->get('user_is') == 'dropship') {
            Toastr::success('Payment success.');

            return view('web-views.checkout-complete');
        }

        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function successApi($type, $order_id, $user_is)
    {
        $order = Order::find($order_id);

        $order->order_status = 'processing';
        $order->payment_status = 'paid';
        $order->transaction_ref = session('transaction_ref');
        $order->payment_method = $type;
        $order->save();

        if ($user_is == 'dropship') {
            $user = Seller::where('id', $order->customer_id)->first();
        } else {
            $user = User::where('id', $order->customer_id)->first();
        }
        if ($user) {
            if ($user->cm_firebase_token !== null) {
                $fcm_token = $user->cm_firebase_token;

                $data = [
                    'title' => 'Pembayaran berhasil!',
                    'description' => 'Pembayaran telah diterima pesanan segera diproses!',
                    'order_id' => $order_id,
                    'image' => 'def.png',
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        }
        session()->forget('customer_address');
        if (auth('customer')->check() || session()->get('user_is') == 'dropship') {
            Toastr::success('Payment success.');

            return view('web-views.checkout-complete');
        }

        return response()->json(['message' => 'Payment succeeded'], 200);
    }
}
