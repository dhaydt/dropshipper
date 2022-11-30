<?php

namespace App\Http\Controllers;

use App\CPU\Helpers;
use App\Model\AdminWallet;
use App\Model\BusinessSetting;
use App\Model\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class UpdateController extends Controller
{
    public function update_software_index()
    {
        return view('update.update-software');
    }

    public function update_software(Request $request)
    {
        Helpers::setEnvironmentValue('SOFTWARE_ID', 'MzE0NDg1OTc=');
        Helpers::setEnvironmentValue('BUYER_USERNAME', $request['username']);
        Helpers::setEnvironmentValue('PURCHASE_CODE', $request['purchase_key']);
        Helpers::setEnvironmentValue('SOFTWARE_VERSION', '5.2');
        Helpers::setEnvironmentValue('APP_MODE', 'live');
        Helpers::setEnvironmentValue('APP_NAME', '6valley-web');
        Helpers::setEnvironmentValue('SESSION_LIFETIME', '60');

        $data = Helpers::requestSender();
        if (!$data['active']) {
            return redirect(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'));
        }

        Artisan::call('migrate', ['--force' => true]);
        $previousRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.php');
        $newRouteServiceProvier = base_path('app/Providers/RouteServiceProvider.txt');
        copy($newRouteServiceProvier, $previousRouteServiceProvier);
        Artisan::call('cache:clear');
        Artisan::call('view:clear');

        /*if (BusinessSetting::where(['type' => 'fcm_topic'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'fcm_topic',
                'value' => ''
            ]);
        }
        if (BusinessSetting::where(['type' => 'fcm_project_id'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'fcm_project_id',
                'value' => ''
            ]);
        }
        if (BusinessSetting::where(['type' => 'push_notification_key'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'push_notification_key',
                'value' => ''
            ]);
        }
        if (BusinessSetting::where(['type' => 'order_pending_message'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'order_pending_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }
        if (BusinessSetting::where(['type' => 'order_confirmation_msg'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'order_confirmation_msg',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }
        if (BusinessSetting::where(['type' => 'order_processing_message'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'order_processing_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }
        if (BusinessSetting::where(['type' => 'out_for_delivery_message'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'out_for_delivery_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }
        if (BusinessSetting::where(['type' => 'order_delivered_message'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'order_delivered_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }
        if (BusinessSetting::where(['type' => 'order_returned_message'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'order_returned_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }
        if (BusinessSetting::where(['type' => 'order_failed_message'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'order_failed_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }
        if (BusinessSetting::where(['type' => 'delivery_boy_assign_message'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'delivery_boy_assign_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }
        if (BusinessSetting::where(['type' => 'delivery_boy_start_message'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'delivery_boy_start_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }
        if (BusinessSetting::where(['type' => 'delivery_boy_delivered_message'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'delivery_boy_delivered_message',
                'value' => json_encode([
                    'status' => 0,
                    'message' => ''
                ])
            ]);
        }
        if (BusinessSetting::where(['type' => 'terms_and_conditions'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'terms_and_conditions',
                'value' => ''
            ]);
        }
        if (BusinessSetting::where(['type' => 'minimum_order_value'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'minimum_order_value'], [
                'value' => 1
            ]);
        }
        if (BusinessSetting::where(['type' => 'about_us'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'about_us'], [
                'value' => ''
            ]);
        }
        if (BusinessSetting::where(['type' => 'privacy_policy'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'privacy_policy'], [
                'value' => ''
            ]);
        }
        if (BusinessSetting::where(['type' => 'terms_and_conditions'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'terms_and_conditions'], [
                'value' => ''
            ]);
        }*/

        if (BusinessSetting::where(['type' => 'seller_registration'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'seller_registration'], [
                'value' => 1
            ]);
        }
        if (BusinessSetting::where(['type' => 'pnc_language'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'pnc_language'], [
                'value' => json_encode(['en']),
            ]);
        }

        if (BusinessSetting::where(['type' => 'language'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'language'], [
                'value' => '[{"id":"1","name":"english","direction":"ltr","code":"en","status":1,"default":true}]',
            ]);
        }

        if (BusinessSetting::where(['type' => 'razor_pay'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'razor_pay',
                'value' => '{"status":"1","razor_key":"","razor_secret":""}'
            ]);
        }
        if (BusinessSetting::where(['type' => 'paystack'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'paystack',
                'value' => '{"status":"0","publicKey":"","secretKey":"","paymentUrl":"","merchantEmail":""}'
            ]);
        }
        if (BusinessSetting::where(['type' => 'senang_pay'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'senang_pay',
                'value' => '{"status":"0","secret_key":"","merchant_id":""}'
            ]);
        }
        if (BusinessSetting::where(['type' => 'paymob_accept'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'paymob_accept',
                'value' => '{"status":"0","api_key":"","iframe_id":"","integration_id":"","hmac":""}'
            ]);
        }
        if (BusinessSetting::where(['type' => 'bkash'])->first() == false) {
            BusinessSetting::insert([
                'type' => 'bkash',
                'value' => '{"status":"0","api_key":"","api_secret":"","username":"","password":""}'
            ]);
        }
        if (BusinessSetting::where(['type' => 'social_login'])->first() == false) {
            DB::table('business_settings')->insert([
                'type' => 'social_login',
                'value' => '[{"login_medium":"google","client_id":"","client_secret":"","status":""},{"login_medium":"facebook","client_id":"","client_secret":"","status":""}]',
            ]);
        }
        if (BusinessSetting::where(['type' => 'digital_payment'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'digital_payment'], [
                'value' => '{"status":"1"}',
            ]);
        }

        if (BusinessSetting::where(['type' => 'currency_model'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'currency_model'], [
                'value' => 'multi_currency',
            ]);
        }

        if (BusinessSetting::where(['type' => 'phone_verification'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'phone_verification'], [
                'value' => 0
            ]);
        }
        if (BusinessSetting::where(['type' => 'email_verification'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'email_verification'], [
                'value' => 0
            ]);
        }
        if (BusinessSetting::where(['type' => 'order_verification'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'order_verification'], [
                'value' => 0
            ]);
        }

        if (BusinessSetting::where(['type' => 'country_code'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'country_code'], [
                'value' => 'BD'
            ]);
        }

        if (BusinessSetting::where(['type' => 'pagination_limit'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'pagination_limit'], [
                'value' => 10
            ]);
        }

        if (BusinessSetting::where(['type' => 'shipping_method'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'shipping_method'], [
                'value' => 'inhouse_shipping'
            ]);
        }

        if (Color::where(['name' => 'Cyan'])->first() == true) {
            Color::where(['name' => 'Cyan'])->delete();
        }

        if (BusinessSetting::where(['type' => 'forgot_password_verification'])->first() == false) {
            DB::table('business_settings')->updateOrInsert(['type' => 'forgot_password_verification'], [
                'value' => 'email'
            ]);
        }

        if (BusinessSetting::where(['type' => 'paytabs'])->first() == false) {
            DB::table('business_settings')->insert([
                'type' => 'paytabs',
                'value' => json_encode([
                    'status' => 0,
                    'profile_id' => '',
                    'server_key' => '',
                    'base_url' => 'https://secure-egypt.paytabs.com/'
                ]),
                'updated_at' => now()
            ]);
        }


        if (AdminWallet::where(['admin_id' => 1])->first() == false) {
            DB::table('admin_wallets')->insert([
                'admin_id' => 1,
                'withdrawn' => 0,
                'commission_earned' => 0,
                'inhouse_earning' => 0,
                'delivery_charge_earned' => 0,
                'pending_amount' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect('/admin/auth/login');
    }
}
