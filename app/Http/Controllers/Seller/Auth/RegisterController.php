<?php

namespace App\Http\Controllers\Seller\Auth;

use App\CPU\ImageManager;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    public function create()
    {
        $country = DB::table('country')->get();

        return view('seller-views.auth.register', compact('country'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|unique:sellers',
            'password' => 'required|min:8',
        ]);

        DB::transaction(function ($r) use ($request) {
            $seller = new Seller();
            $seller->f_name = $request->f_name;
            $seller->l_name = $request->l_name;
            $seller->country = $request->country;
            $seller->phone = $request->phone;
            $seller->email = $request->email;
            $seller->image = ImageManager::upload('seller/', 'png', $request->file('image'));
            $seller->password = bcrypt($request->password);
            $seller->status = 'pending';
            $seller->save();

            $shop = new Shop();
            $shop->seller_id = $seller->id;
            $shop->name = $request->shop_name;
            $shop->country = $request->country;
            $shop->address = $request->shop_address;
            $shop->contact = $request->phone;
            $shop->image = ImageManager::upload('shop/', 'png', $request->file('logo'));
            $shop->banner = ImageManager::upload('shop/banner/', 'png', $request->file('banner'));
            $shop->save();

            DB::table('seller_wallets')->insert([
                'seller_id' => $seller['id'],
                'withdrawn' => 0,
                'commission_given' => 0,
                'total_earning' => 0,
                'pending_withdraw' => 0,
                'delivery_charge_earned' => 0,
                'collected_cash' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        Toastr::success('Dropshipper apply successfully!');
        Toastr::success('Please wait for admin to review!');

        return redirect()->route('seller.auth.login');
    }
}
