<?php

namespace App\Http\Controllers\Seller\Auth;

use App\CPU\Helpers;
use App\CPU\ImageManager;
use App\CPU\SMS_module;
use function App\CPU\translate;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\PhoneOrEmailVerification;
use App\Model\Seller;
use App\Model\Shop;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

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
            'phone' => 'required|unique:sellers',
            'password' => 'required|min:8',
        ], [
            'email.unique' => 'email sudah digunakan toko lain!',
            'phone.unique' => 'Nomor handphone sudah digunakan toko lain!',
        ]);

        $seller = new Seller();
        DB::transaction(function ($r) use ($request, $seller) {
            $seller->f_name = $request->f_name;
            $seller->l_name = $request->l_name;
            $seller->country = $request->country;
            $seller->phone = $request->phone;
            $seller->email = $request->email;
            $seller->image = ImageManager::upload('seller/', 'png', $request->file('image'));
            $seller->password = bcrypt($request->password);
            $seller->status = 'pending';
            $seller->is_phone_verified = 0;
            $seller->save();

            $shop = new Shop();
            $shop->seller_id = $seller->id;
            $shop->name = $request->shop_name;
            $shop->country = $request->country;
            $shop->address = 'NULL';
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
        $phone_verification = Helpers::get_business_settings('phone_verification');
        if ($phone_verification && $seller->is_phone_verified == 0) {
            return redirect()->route('seller.auth.check', [$seller->id]);
        }

        Toastr::success('Toko berhasil didaftarkan!');
        // Toastr::success('Please wait for admin to review!');

        return redirect()->route('home');
    }

    public static function check($id)
    {
        $user = Seller::find($id);

        $token = rand(1000, 9999);
        DB::table('phone_or_email_verifications')->insert([
            'phone_or_email' => $user->email,
            'token' => $token,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $phone_verification = Helpers::get_business_settings('phone_verification');
        $email_verification = Helpers::get_business_settings('email_verification');
        if ($phone_verification && !$user->is_phone_verified) {
            SMS_module::send($user->phone, $token);
            $response = translate('please_check_your_SMS_for_OTP');
            Toastr::success($response);
        }
        if ($email_verification && !$user->is_email_verified) {
            try {
                Mail::to($user->email)->send(new \App\Mail\EmailVerification($token));
                $response = translate('check_your_email');
                Toastr::success($response);
            } catch (\Exception $exception) {
                $response = translate('email_failed');
                Toastr::error($response);
            }
        }

        return view('seller-views.auth.verify', compact('user'));
    }

    public static function verify(Request $request)
    {
        Validator::make($request->all(), [
            'token' => 'required',
        ]);

        $email_status = BusinessSetting::where('type', 'email_verification')->first()->value;
        $phone_status = BusinessSetting::where('type', 'phone_verification')->first()->value;

        $user = Seller::find($request->id);
        $verify = PhoneOrEmailVerification::where(['phone_or_email' => $user->email, 'token' => $request['token']])->first();

        if ($email_status == 1 || ($email_status == 0 && $phone_status == 0)) {
            if (isset($verify)) {
                try {
                    $user->is_email_verified = 1;
                    $user->save();
                    $verify->delete();
                } catch (\Exception $exception) {
                    Toastr::info('Try again');
                }

                Toastr::success(translate('verification_done_successfully'));
            } else {
                Toastr::error(translate('Verification_code_or_OTP mismatched'));

                return redirect()->back();
            }
        } else {
            if (isset($verify)) {
                try {
                    $user->is_phone_verified = 1;
                    $user->save();
                    $verify->delete();
                } catch (\Exception $exception) {
                    Toastr::info('Try again');
                }

                Toastr::success('Verification Successfully Done');
            } else {
                Toastr::error('Verification code/ OTP mismatched');
            }
        }

        Toastr::success('Toko berhasil didaftarkan. Mohon menunggu konfimasi kami');

        return redirect(route('home'));
    }
}
