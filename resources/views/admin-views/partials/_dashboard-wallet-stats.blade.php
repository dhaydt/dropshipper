<div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
    <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #22577A;">
        <h1 class="p-2 text-white">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['commission_earned']))}}</h1>
        <div class="text-uppercase">{{\App\CPU\translate('commission_earned')}}</div>
    </div>
</div>

<div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
    <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #a66f2e;">
        <h1 class="p-2 text-white">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['delivery_charge_earned']))}}</h1>
        <div class="text-uppercase">{{\App\CPU\translate('delivery_charge_earned')}}</div>
    </div>
</div>

<div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-3">
    <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #6D9886;">
        <h1 class="p-2 text-white">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['pending_amount']))}}</h1>
        <div class="text-uppercase">{{\App\CPU\translate('pending_amount')}}</div>
    </div>
</div>

<div class="col-lg-6 col-md-4 col-sm-6 col-12 mb-3 mb-lg-0">
    <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #4E9F3D;">
        <h1 class="p-2 text-white">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['inhouse_earning']))}}</h1>
        <div class="text-uppercase">{{\App\CPU\translate('in-house_earning')}}</div>
    </div>
</div>

<div class="col-lg-6 col-md-4 col-sm-6 col-12 mb-3 mb-lg-0">
    <div class="card card-body card-hover-shadow h-100 text-white text-center" style="background-color: #6E85B2;">
        <h1 class="p-2 text-white">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($data['total_tax_collected']))}}</h1>
        <div class="text-uppercase">{{\App\CPU\translate('total_tax_collected')}}</div>
    </div>
</div>
