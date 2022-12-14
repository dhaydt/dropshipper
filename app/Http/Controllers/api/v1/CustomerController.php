<?php

namespace App\Http\Controllers\api\v1;

use App\CPU\CustomerManager;
use App\CPU\Helpers;
use App\CPU\ImageManager;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\ShippingAddress;
use App\Model\SupportTicket;
use App\Model\SupportTicketConv;
use App\Model\Wishlist;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function checkDropship(Request $request)
    {
        $user = $request->user();

        dd($user);
    }

    public function info(Request $request)
    {
        return response()->json($request->user(), 200);
    }

    public function create_support_ticket(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subject' => 'required',
            'type' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $request['customer_id'] = $request->user()->id;
        $request['priority'] = 'low';
        $request['status'] = 'pending';

        try {
            CustomerManager::create_support_ticket($request);
        } catch (\Exception $e) {
            return response()->json([
                'errors' => [
                    'code' => 'failed',
                    'message' => 'Something went wrong',
                ],
            ], 422);
        }

        return response()->json(['message' => 'Support ticket created successfully.'], 200);
    }

    public function reply_support_ticket(Request $request, $ticket_id)
    {
        $support = new SupportTicketConv();
        $support->support_ticket_id = $ticket_id;
        $support->admin_id = 1;
        $support->customer_message = $request['message'];
        $support->save();

        return response()->json(['message' => 'Support ticket reply sent.'], 200);
    }

    public function get_support_tickets(Request $request)
    {
        return response()->json(SupportTicket::where('customer_id', $request->user()->id)->get(), 200);
    }

    public function get_support_ticket_conv($ticket_id)
    {
        return response()->json(SupportTicketConv::where('support_ticket_id', $ticket_id)->get(), 200);
    }

    public function add_to_wishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $wishlist = Wishlist::where('customer_id', $request->user()->id)->where('product_id', $request->product_id)->first();

        if (empty($wishlist)) {
            $wishlist = new Wishlist();
            $wishlist->customer_id = $request->user()->id;
            $wishlist->product_id = $request->product_id;
            $wishlist->save();

            return response()->json(['message' => translate('successfully added!')], 200);
        }

        return response()->json(['message' => translate('Already in your wishlist')], 200);
    }

    public function remove_from_wishlist(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $wishlist = Wishlist::where(['customer_id' => $request->user()->id, 'user_is' => 'customer'])->where('product_id', $request->product_id)->first();

        if (!empty($wishlist)) {
            Wishlist::where(['customer_id' => $request->user()->id, 'user_is' => 'customer', 'product_id' => $request->product_id])->delete();

            return response()->json(['message' => translate('successfully removed!')], 200);
        }

        return response()->json(['message' => translate('No such data found!')], 404);
    }

    public function wish_list(Request $request)
    {
        return response()->json(Wishlist::whereHas('product')->where(['customer_id' => $request->user()->id, 'user_is' => 'customer'])->get(), 200);
    }

    public function address_list(Request $request)
    {
        return response()->json(ShippingAddress::where(['customer_id' => $request->user()->id, 'user_is' => 'customer'])->get(), 200);
    }

    public function add_new_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_type' => 'required',
            'contact_person_name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'phone' => 'required',
            'province' => 'required',
            'city' => 'required',
            'district' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $user = $request->user();

        $states = $request->province;
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

        $address = [
            'customer_id' => $user->id,
            'contact_person_name' => $request->contact_person_name,
            'address_type' => $request->address_type,
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

        DB::table('users')->where('id', $user->id)->limit(1)->update([
                'province' => $state,
                'country' => $request->country,
                'city' => $city,
                'city_type' => $city_type,
                'zip' => $request->zip,
                'city_id' => $city_id,
                'district' => $district,
                'district_id' => $district_id,
            ]);

        return response()->json(['message' => translate('successfully added address!')], 200);
    }

    public function delete_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'address_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if (DB::table('shipping_addresses')->where(['id' => $request['address_id'], 'customer_id' => $request->user()->id])->first()) {
            DB::table('shipping_addresses')->where(['id' => $request['address_id'], 'customer_id' => $request->user()->id])->delete();

            return response()->json(['message' => 'successfully removed!'], 200);
        }

        return response()->json(['message' => translate('No such data found!')], 404);
    }

    public function get_order_list(Request $request)
    {
        $orders = Order::where(['customer_id' => $request->user()->id, 'user_is' => 'customer'])->get();
        $orders->map(function ($data) {
            $data['shipping_address_data'] = json_decode($data['shipping_address_data']);

            return $data;
        });

        return response()->json($orders, 200);
    }

    public function get_order_details(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $details = OrderDetail::where(['order_id' => $request['order_id']])->get();
        $details->map(function ($query) use ($request) {
            $query['variation'] = json_decode($query['variation'], true);
            $query['product_details'] = Helpers::product_data_formatting(json_decode($query['product_details'], true));
            $order = Order::find($request['order_id']);
            $query['shipping_address'] = $order['shipping_address_data'];
            $query['shipping'] = $order['shipping'];
            $query['no_resi'] = $order['no_resi'];

            return $query;
        });

        return response()->json($details, 200);
    }

    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required',
        ], [
            'password.required' => 'Password field is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        $pass = bcrypt($request['password']);

        $change = [
            'password' => $pass,
        ];

        User::where(['id' => $request->user()->id])->update($change);

        return response()->json(['message' => translate('successfully updated!')], 200);
    }

    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'phone' => 'required',
        ], [
            'name.required' => 'Name field is required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if ($request->has('image')) {
            $imageName = ImageManager::update('profile/', $request->user()->image, 'png', $request->file('image'));
        } else {
            $imageName = $request->user()->image;
        }

        if ($request['password'] != null && strlen($request['password']) > 5) {
            $pass = bcrypt($request['password']);
        } else {
            $pass = $request->user()->password;
        }

        $userDetails = [
            'name' => $request->name,
            'l_name' => null,
            'phone' => $request->phone,
            'email' => $request->email,
            'image' => $imageName,
            'password' => $pass,
            'updated_at' => now(),
        ];

        User::where(['id' => $request->user()->id])->update($userDetails);

        return response()->json(['message' => translate('successfully updated!')], 200);
    }

    public function update_cm_firebase_token(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cm_firebase_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        DB::table('users')->where('id', $request->user()->id)->update([
            'cm_firebase_token' => $request['cm_firebase_token'],
        ]);

        return response()->json(['message' => translate('successfully updated!')], 200);
    }
}
