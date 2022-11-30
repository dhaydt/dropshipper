<?php

namespace App\Providers;

use App\Model\BusinessSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            $data = BusinessSetting::where(['type' => 'mail_config'])->first();
            $emailServices = json_decode($data['value'], true);

            if ($emailServices) {
                $config = array(
                    'driver' => $emailServices['driver'],
                    'host' => $emailServices['host'],
                    'port' => $emailServices['port'],
                    'username' => $emailServices['username'],
                    'password' => $emailServices['password'],
                    'encryption' => $emailServices['encryption'],
                    'from' => array('address' => $emailServices['email_id'], 'name' => $emailServices['name']),
                    'sendmail' => '/usr/sbin/sendmail -bs',
                    'pretend' => false,
                );
                Config::set('mail', $config);
            }
        } catch (\Exception $ex) {

        }

    }
}
