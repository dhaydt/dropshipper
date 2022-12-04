<?php

namespace App\Http\Controllers\Web;

use App\CPU\CartManager;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\ShippingAddress;
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
        $customer = auth('customer')->user();
        $discount = session()->has('coupon_discount') ? session('coupon_discount') : 0;
        $value = CartManager::cart_grand_total() - $discount;
        $tran = OrderManager::gen_unique_id();
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
            $address = $customer->district.', '.$customer->city.', '.$customer->province;
            $id = $customer->id;
            $email = $customer->email;
        }
        if (session()->get('user_is') == 'dropship') {
            $custom = ShippingAddress::where('slug', session()->get('customer_address'))->first();
            $name = $custom->contact_person_name;
            $phone = $custom->phone;
            $address = $custom->district.', '.$custom->city.', '.$custom->province;
            $id = $custom->customer_id;
            $email = 'dropshipper@ezren.id';
        }

        $user = [
            'given_names' => $name,
            'email' => $email,
            'mobile_number' => $phone,
            'address' => $address,
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
            'success_redirect_url' => env('APP_URL').'/xendit-payment/success/'.$type,
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

    public function success($type)
    {
        // dd($type);
        // $order = Order::find($request->id);

        $unique_id = OrderManager::gen_unique_id();
        $order_ids = [];
        foreach (CartManager::get_cart_group_ids() as $group_id) {
            $data = [
                'payment_method' => 'Virtual Account'.$type,
                'order_status' => 'confirmed',
                'payment_status' => 'paid',
                'transaction_ref' => session('transaction_ref'),
                'order_group_id' => $unique_id,
                'cart_group_id' => $group_id,
            ];
            $order_id = OrderManager::generate_order($data);
            array_push($order_ids, $order_id);
        }
        CartManager::cart_clean();
        session()->forget('customer_address');
        if (auth('customer')->check() || session()->get('user_is') == 'dropship') {
            Toastr::success('Payment success.');

            return view('web-views.checkout-complete');
        }

        return response()->json(['message' => 'Payment succeeded'], 200);
    }
}
