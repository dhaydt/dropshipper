<?php

namespace App\Http\Controllers\Web;

use App\CPU\CustomerManager;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\OrderManager;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\ShippingAddress;
use App\Model\SupportTicket;
use App\Model\Wishlist;
use App\User;
use Barryvdh\DomPDF\Facade as PDF;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use function App\CPU\translate;

class UserProfileController extends Controller
{
    public function user_account(Request $request)
    {
        if (auth('customer')->check()) {
            $customerDetail = User::where('id', auth('customer')->id())->first();
            return view('web-views.users-profile.account-profile', compact('customerDetail'));
        } else {
            return redirect()->route('home');
        }
    }

    public function user_update(Request $request)
    {

        $image = $request->file('image');

        if ($image != null) {
            $imageName = ImageManager::update('profile/', auth('customer')->user()->image, 'png', $request->file('image'));
        } else {
            $imageName = auth('customer')->user()->image;
        }

        User::where('id', auth('customer')->id())->update([
            'image' => $imageName,
        ]);

        if ($request['password'] != $request['con_password']) {
            Toastr::error('Password did not match.');
            return back();
        }

        $userDetails = [
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'phone' => $request->phone,
            'password' => strlen($request->password) > 5 ? bcrypt($request->password) : auth('customer')->user()->password,
        ];
        if (auth('customer')->check()) {
            User::where(['id' => auth('customer')->id()])->update($userDetails);
            Toastr::info(translate('updated_successfully'));
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }
    
    public function getCity($id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => config('rajaongkir.url').'/city?&province='.$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'key:'.config('rajaongkir.api_key'),
            ],
        ]);

        $resp = curl_exec($curl);
        $err = curl_error($curl);
        $resp = json_decode($resp, true);
        $data = $resp['rajaongkir']['results'];

        // dd($prov);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:'.$err;
        } else {
            return $data;
        }
    }

    public function getDistrict($id)
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => config('rajaongkir.url').'/subdistrict?city='.$id,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'key:'.config('rajaongkir.api_key'),
            ],
        ]);

        $resp = curl_exec($curl);
        $err = curl_error($curl);
        $resp = json_decode($resp, true);
        $data = $resp['rajaongkir']['results'];

        // dd($prov);

        curl_close($curl);

        if ($err) {
            echo 'cURL Error #:'.$err;
        } else {
            return $data;
        }
    }
    
    public function address_store_first(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'phone' => 'required',
            'state' => 'required',
            'country' => 'required',
            'district' => 'required',
        ],
            [
                'name' => 'Contact person name is required',
                'address' => 'Address is required',
                'zip' => 'ZIP code is required',
                'phone' => 'Phone is required',
                'state' => 'State / Province is required',
                'country' => 'Country is required',
                'District' => 'District is required',
            ]);

        $id = auth('customer')->check() ? auth('customer')->id() : null;

        if ($request->country == 'ID') {
            $states = $request->state;
            $stat = explode(',', $states);
            $state = $stat[1];

            $cities = $request->city;
            $cit = explode(',', $cities);
            $city_id = $cit[0];
            $city = $cit[1];
            $city_type = $cit[2];

            $districts = $request->district;
            $dis = explode(',', $districts);
            $district_id = $dis[0];
            $district = $dis[1];
        } else {
            $state = $request->state;
            $city = $request->city;
            $district = $request->district;
            $city_id = null;
            $district_id = null;
        }

        $address = [
            'customer_id' => $id,
            'contact_person_name' => $request->name,
            'address_type' => $request->addressAs,
            'address' => $request->address,
            'city' => $city,
            'city_id' => $city_id,
            'city_type' => $city_type,
            'district' => $district,
            'district_id' => $district_id,
            'zip' => $request->zip,
            'phone' => $request->phone,
            'state' => $state,
            'province' => $state,
            'country' => $request->country,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('shipping_addresses')->insert($address);
        DB::table('users')->where('id', $id)->limit(1)->update([
            'province' => $state,
            'country' => $request->country,
            'city' => $city,
            'city_type' => $city_type,
            'zip' => $request->zip,
            'city_id' => $city_id,
            'district' => $district,
            'district_id' => $district_id,
        ]);

        return redirect('/shop-cart');
    }

    public function account_address()
    {
        if (auth('customer')->check()) {
            $shippingAddresses = \App\Model\ShippingAddress::where('customer_id', auth('customer')->id())->get();
            return view('web-views.users-profile.account-address', compact('shippingAddresses'));
        } else {
            return redirect()->route('home');
        }
    }

    public function address_store(Request $request)
    {
        $address = [
            'customer_id' => auth('customer')->check() ? auth('customer')->id() : null,
            'contact_person_name' => $request->name,
            'address_type' => $request->addressAs,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'phone' => $request->phone,
            'state' => $request->state,
            'country' => $request->country,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('shipping_addresses')->insert($address);
        return back();
    }

    public function address_update(Request $request)
    {
        $updateAddress = [
            'contact_person_name' => $request->name,
            'address_type' => $request->addressAs,
            'address' => $request->address,
            'city' => $request->city,
            'zip' => $request->zip,
            'phone' => $request->phone,
            'state' => $request->state,
            'country' => $request->country,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        if (auth('customer')->check()) {
            ShippingAddress::where('id', $request->id)->update($updateAddress);
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function address_delete(Request $request)
    {
        if (auth('customer')->check()) {
            ShippingAddress::destroy($request->id);
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function account_payment()
    {
        if (auth('customer')->check()) {
            return view('web-views.users-profile.account-payment');

        } else {
            return redirect()->route('home');
        }

    }

    public function account_oder()
    {
        $orders = Order::where('customer_id', auth('customer')->id())->orderBy('id','DESC')->get();
        return view('web-views.users-profile.account-orders', compact('orders'));
    }

    public function account_order_details(Request $request)
    {
        $order = Order::find($request->id);
        return view('web-views.users-profile.account-order-details', compact('order'));
    }

    public function account_wishlist()
    {
        if (auth('customer')->check()) {
            $wishlists = Wishlist::where('customer_id', auth('customer')->id())->get();
            return view('web-views.products.wishlist', compact('wishlists'));
        } else {
            return redirect()->route('home');
        }
    }

    public function account_tickets()
    {
        if (auth('customer')->check()) {
            $supportTickets = SupportTicket::where('customer_id', auth('customer')->id())->get();
            return view('web-views.users-profile.account-tickets', compact('supportTickets'));
        } else {
            return redirect()->route('home');
        }
    }

    public function ticket_submit(Request $request)
    {
        $ticket = [
            'subject' => $request['ticket_subject'],
            'type' => $request['ticket_type'],
            'customer_id' => auth('customer')->check() ? auth('customer')->id() : null,
            'priority' => $request['ticket_priority'],
            'description' => $request['ticket_description'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
        DB::table('support_tickets')->insert($ticket);
        return back();
    }

    public function single_ticket(Request $request)
    {
        $ticket = SupportTicket::where('id', $request->id)->first();
        return view('web-views.users-profile.ticket-view', compact('ticket'));
    }

    public function comment_submit(Request $request, $id)
    {
        DB::table('support_tickets')->where(['id' => $id])->update([
            'status' => 'open',
            'updated_at' => now(),
        ]);

        DB::table('support_ticket_convs')->insert([
            'customer_message' => $request->comment,
            'support_ticket_id' => $id,
            'position' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back();
    }

    public function support_ticket_close($id)
    {
        DB::table('support_tickets')->where(['id' => $id])->update([
            'status' => 'close',
            'updated_at' => now(),
        ]);
        Toastr::success('Ticket closed!');
        return redirect('/account-tickets');
    }

    public function account_transaction()
    {
        $customer_id = auth('customer')->id();
        $customer_type = 'customer';
        if (auth('customer')->check()) {
            $transactionHistory = CustomerManager::user_transactions($customer_id, $customer_type);
            return view('web-views.users-profile.account-transaction', compact('transactionHistory'));
        } else {
            return redirect()->route('home');
        }
    }

    public function support_ticket_delete(Request $request)
    {

        if (auth('customer')->check()) {
            $support = SupportTicket::find($request->id);
            $support->delete();
            return redirect()->back();
        } else {
            return redirect()->back();
        }

    }

    public function account_wallet_history($user_id, $user_type = 'customer')
    {
        $customer_id = auth('customer')->id();
        if (auth('customer')->check()) {
            $wallerHistory = CustomerManager::user_wallet_histories($customer_id);
            return view('web-views.users-profile.account-wallet', compact('wallerHistory'));
        } else {
            return redirect()->route('home');
        }

    }

    public function track_order()
    {
        return view('web-views.order-tracking-page');
    }

    public function track_order_result(Request $request)
    {
        $orderDetails = Order::where('id',$request['order_id'])->whereHas('details',function ($query){
            $query->where('customer_id',auth('customer')->id());
        })->first();

        if (isset($orderDetails)){
            return view('web-views.order-tracking', compact('orderDetails'));
        }

        return redirect()->route('track-order.index')->with('Error', 'Invalid Order Id or Phone Number');
    }

    public function track_last_order()
    {
        $orderDetails = OrderManager::track_order(Order::where('customer_id', auth('customer')->id())->latest()->first()->id);

        if ($orderDetails != null) {
            return view('web-views.order-tracking', compact('orderDetails'));
        } else {
            return redirect()->route('track-order.index')->with('Error', 'Invalid Order Id or Phone Number');
        }

    }

    public function order_cancel($id)
    {
        $order = Order::where(['id' => $id])->first();
        if ($order['payment_method'] == 'cash_on_delivery' && $order['order_status'] == 'pending') {
            OrderManager::stock_update_on_order_status_change($order, 'canceled');
            Order::where(['id' => $id])->update([
                'order_status' => 'canceled'
            ]);
            Toastr::success(translate('successfully_canceled'));
            return back();
        }
        Toastr::error(translate('status_not_changable_now'));
        return back();
    }

    public function generate_invoice($id)
    {
        $order = Order::with('seller')->with('shipping')->where('id', $id)->first();
        $data["email"] = $order->customer["email"];
        $data["order"] = $order;

        $mpdf_view = \View::make('web-views.invoice')->with('order', $order);
        Helpers::gen_mpdf($mpdf_view, 'order_invoice_', $order->id);
    }
}
