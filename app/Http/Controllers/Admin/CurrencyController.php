<?php

namespace App\Http\Controllers\Admin;

use App\CPU\Helpers;
use App\Http\Controllers\Controller;
use App\Model\BusinessSetting;
use App\Model\Currency;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index(Request $request)
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $currencies = Currency::where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->Where('name', 'like', "%{$value}%");
                }
            });
            $query_param = ['search' => $request['search']];
        } else {
            $currencies = new Currency();
        }
        $currencies = $currencies->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.currency.view', compact('currencies', 'search'));
    }

    public function store(Request $request)
    {
        $currency = new Currency;
        $currency->name = $request->name;
        $currency->symbol = $request->symbol;
        $currency->code = $request->code;
        $currency->exchange_rate = $request->has('exchange_rate') ? $request->exchange_rate : 1;
        $currency->save();
        Toastr::success('New Currency inserted successfully!');
        return redirect()->back();
    }

    public function edit($id)
    {
        $data = Currency::find($id);
        return view('admin-views.currency.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $currency = Currency::find($id);
        if ($currency['code'] == 'BDT' && $request->code != 'BDT') {
            $config = Helpers::get_business_settings('ssl_commerz_payment');
            if ($config['status']) {
                Toastr::warning('Before update BDT, disable the SSLCOMMERZ payment gateway.');
                return back();
            }
        } elseif ($currency['code'] == 'INR' && $request->code != 'INR') {
            $config = Helpers::get_business_settings('razor_pay');
            if ($config['status']) {
                Toastr::warning('Before update INR, disable the RAZOR PAY payment gateway.');
                return back();
            }
        } elseif ($currency['code'] == 'MYR' && $request->code != 'MYR') {
            $config = Helpers::get_business_settings('senang_pay');
            if ($config['status']) {
                Toastr::warning('Before update MYR, disable the SENANG PAY payment gateway.');
                return back();
            }
        } elseif ($currency['code'] == 'ZAR' && $request->code != 'ZAR') {
            $config = Helpers::get_business_settings('paystack');
            if ($config['status']) {
                Toastr::warning('Before update ZAR, disable the PAYSTACK payment gateway.');
                return back();
            }
        }
        $currency->name = $request->name;
        $currency->symbol = $request->symbol;
        $currency->code = $request->code;
        $currency->exchange_rate = $request->has('exchange_rate') ? $request->exchange_rate : 1;
        $currency->save();
        Toastr::success('Currency updated successfully!');
        return redirect()->back();
    }

    public function delete($id)
    {
        if (!in_array($id, [1, 2, 3, 4, 5])) {
            Currency::where('id', $id)->delete();
            Toastr::success('Currency removed successfully!');
        } else {
            Toastr::warning('This Currency cannot be removed due to payment gateway dependency!');
        }
        return back();
    }

    public function status(Request $request)
    {
        if ($request->status == 0) {
            $count = Currency::where(['status' => 1])->count();
            if ($count == 1) {
                return response()->json([
                    'status' => 0,
                    'message' => 'You can not disable all currencies.'
                ]);
            }
        }
        $currency = Currency::find($request->id);
        $currency->status = $request->status;
        $currency->save();
        return response()->json([
            'status' => 1,
            'message' => 'Currency status successfully changed.'
        ]);
    }

    public function systemCurrencyUpdate(Request $request)
    {
        $business_settings = BusinessSetting::where('type', 'system_default_currency')->first();
        $business_settings->value = $request['currency_id'];
        $business_settings->save();

        $currency_model = Helpers::get_business_settings('currency_model');
        if ($currency_model == 'multi_currency') {
            $default = Currency::find($request['currency_id']);
            foreach (Currency::all() as $data) {
                Currency::where(['id' => $data['id']])->update([
                    'exchange_rate' => ($data['exchange_rate'] / $default['exchange_rate']),
                    'updated_at' => now()
                ]);
            }
        }

        Toastr::success('System Default currency updated successfully!');
        return redirect()->back();
    }
}
