<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class MailController extends Controller
{
    public function index()
    {
        return view('admin-views.business-settings.mail.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            "name" => 'required',
            "host" => 'required',
            "driver" => 'required',
            "port" => 'required',
            "username" => 'required',
            "email" => 'required',
            "encryption" => 'required',
            "password" => 'required',
        ]);
        BusinessSetting::where(['type' => 'mail_config'])->update([
            'value' => json_encode([
                "name" => $request['name'],
                "host" => $request['host'],
                "driver" => $request['driver'],
                "port" => $request['port'],
                "username" => $request['username'],
                "email_id" => $request['email'],
                "encryption" => $request['encryption'],
                "password" => $request['password']
            ])
        ]);
        Toastr::success('Configuration updated successfully!');
        return back();
    }
}
