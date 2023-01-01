<?php

namespace App\Http\Controllers\Seller\Auth;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Seller;
use App\Model\SellerWallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:seller', ['except' => ['logout']]);
    }

    public function login()
    {
        return view('seller-views.auth.login');
    }

    public function submit(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'password' => 'required|min:6',
        ]);

        $user_id = $request->user_id;
        if (filter_var($user_id, FILTER_VALIDATE_EMAIL)) {
            $medium = 'email';
        } else {
            $count = strlen(preg_replace("/[^\d]/", '', $user_id));
            if ($count >= 9 && $count <= 15) {
                $medium = 'phone';
            } else {
                Toastr::error('Invalid user email or phone number.');
            }
        }

        $se = Seller::where($medium, 'like', "%{$user_id}%")->first();

        if (isset($se)) {
            $phone_verification = Helpers::get_business_settings('phone_verification');
            $email_verification = Helpers::get_business_settings('email_verification');
            if ($phone_verification && !$se->is_phone_verified) {
                return redirect(route('seller.auth.check', [$se->id]));
            }
            if ($email_verification && !$se->is_email_verified) {
                return redirect(route('seller.auth.check', [$se->id]));
            }
        }

        if (isset($se) && $se['status'] == 'approved' && auth('seller')->attempt(['email' => $se['email'], 'password' => $request->password], $request->remember)) {
            Toastr::info('Welcome to Dropshipper!');
            if (SellerWallet::where('seller_id', auth('seller')->id())->first() == false) {
                DB::table('seller_wallets')->insert([
                    'seller_id' => auth('seller')->id(),
                    'withdrawn' => 0,
                    'commission_given' => 0,
                    'total_earning' => 0,
                    'pending_withdraw' => 0,
                    'delivery_charge_earned' => 0,
                    'collected_cash' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            auth()->guard('customer')->logout();
            session()->forget('wish_list');
            session()->forget('user_is');
            session()->put('user_is', 'dropship');
            $request->session()->put('user_is', 'dropship');
            // return redirect()->route('seller.dashboard.index');
            return redirect()->route('home');
        } elseif (isset($se) && $se['status'] == 'pending') {
            return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors(['Your account is not approved yet.']);
        } elseif (isset($se) && $se['status'] == 'suspended') {
            return redirect()->back()->withInput($request->only('email', 'remember'))
                ->withErrors(['Your account has been suspended!.']);
        }

        return redirect()->back()->withInput($request->only('email', 'remember'))
            ->withErrors(['No Handphone atau password salah.']);
    }

    public function logout(Request $request)
    {
        auth()->guard('seller')->logout();

        $request->session()->invalidate();

        return redirect()->route('home');
    }
}
