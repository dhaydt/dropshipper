@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Currency'))

@push('css_or_js')

@endpush

@section('content')
    @php($currency_model=\App\CPU\Helpers::get_business_settings('currency_model'))
    @php($default=\App\CPU\Helpers::get_business_settings('system_default_currency'))
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a
                        href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Currency')}}</li>
            </ol>
        </nav>
        <!-- Page Heading -->

        <div class="row">
            <div class="col-12">
                <div class="alert alert-danger mb-3" role="alert">
                    {{\App\CPU\translate('changing_some_settings_will_take_time_to_show_effect_please_clear_session_or_wait_for_60_minutes_else_browse_from_incognito_mode')}}
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center">
                            <i class="tio-money"></i>
                            {{\App\CPU\translate('Add New Currency')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{route('admin.currency.store')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" name="name" class="form-control"
                                               id="name" placeholder="{{\App\CPU\translate('Enter currency Name')}}">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="symbol" class="form-control"
                                               id="symbol" placeholder="{{\App\CPU\translate('Enter currency symbol')}}">
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="text" name="code" class="form-control"
                                               id="code" placeholder="{{\App\CPU\translate('Enter currency code')}}">
                                    </div>
                                    @if($currency_model=='multi_currency')
                                        <div class="col-md-6">
                                            <input type="number" min="0" max="1000000"
                                                   name="exchange_rate" step="0.00000001"
                                                   class="form-control" id="exchange_rate"
                                                   placeholder="{{\App\CPU\translate('Enter currency exchange rate')}}">
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" id="add" class="btn btn-primary text-capitalize"
                                        style="color: white">
                                    <i class="tio-add"></i> {{\App\CPU\translate('add')}}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="text-center">
                            <i class="tio-settings"></i>
                            {{\App\CPU\translate('system_default_currency')}}
                        </h5>
                    </div>
                    <div class="card-body">
                        <form class="form-inline_" action="{{route('admin.currency.system-currency-update')}}"
                              method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    @php($default=\App\Model\BusinessSetting::where('type', 'system_default_currency')->first())
                                    <div class="form-group mb-2">
                                        <select style="width: 100%" class="form-control js-example-responsive"
                                                name="currency_id">
                                            @foreach (App\Model\Currency::where('status', 1)->get() as $key => $currency)
                                                <option
                                                    value="{{ $currency->id }}" {{$default->value == $currency->id?'selected':''}} >{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group mb-2">
                                        <button type="submit"
                                                class="btn btn-primary mb-2">{{\App\CPU\translate('Save')}}</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>


        <div class="row" style="margin-top: 20px">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-lg-3 mb-3 mb-lg-0">
                                <div class="flex-start">
                                    <div><h5>{{\App\CPU\translate('Currency')}} {{\App\CPU\translate('table')}}</h5>
                                    </div>
                                    <div class="mx-1"><h5 style="color: red;">({{ $currencies->total() }})</h5></div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <!-- Search -->
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                               placeholder="{{\App\CPU\translate('Search Currency Name or Currency Code')}}"
                                               aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                </form>
                                <!-- End Search -->
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                <tr class="text-center">
                                    <th scope="col">{{\App\CPU\translate('SL')}}#</th>
                                    <th scope="col">{{\App\CPU\translate('currency_name')}}</th>
                                    <th scope="col">{{\App\CPU\translate('currency_symbol')}}</th>
                                    <th scope="col">{{\App\CPU\translate('currency_code')}}</th>
                                    @if($currency_model=='multi_currency')
                                        <th scope="col">{{\App\CPU\translate('exchange_rate')}}
                                            (1 {{App\Model\Currency::where('id', $default->value)->first()->code}}= ?)
                                        </th>
                                    @endif
                                    <th scope="col">{{\App\CPU\translate('status')}}</th>
                                    <th scope="col">{{\App\CPU\translate('action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($currencies as $key =>$data)
                                    <tr class="text-center">
                                        <td>{{$currencies->firstitem()+ $key }}</td>
                                        <td>{{$data->name}}</td>
                                        <td>{{$data->symbol}}</td>
                                        <td>{{$data->code}}</td>
                                        @if($currency_model=='multi_currency')
                                            <td>{{$data->exchange_rate}}</td>
                                        @endif
                                        <td>
                                            @if($default['value']!=$data->id)
                                                <label class="switch">
                                                    <input type="checkbox" class="status"
                                                           id="{{$data->id}}" <?php if ($data->status == 1) echo "checked" ?>><span
                                                        class="slider round">
                                                    </span>
                                                </label>
                                            @else
                                                <label class="badge badge-info">{{\App\CPU\translate('Default')}}</label>
                                            @endif
                                        </td>
                                        <td>
                                            @if($data->code!='USD')
                                                <a type="button" class="btn btn-primary btn-sm btn-xs edit"
                                                   href="{{route('admin.currency.edit',[$data->id])}}">
                                                    <i class="tio-edit"></i> {{\App\CPU\translate('Edit')}}
                                                </a>
                                                <a type="button" class="btn btn-danger btn-sm btn-xs"
                                                   href="{{route('admin.currency.delete',[$data->id])}}">
                                                    <i class="tio-edit"></i> {{\App\CPU\translate('Delete')}}
                                                </a>
                                            @else
                                                <button class="btn btn-primary btn-sm btn-xs edit" disabled>
                                                    <i class="tio-edit"></i> {{\App\CPU\translate('Edit')}}
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{$currencies->links()}}
                    </div>
                    @if(count($currencies)==0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg"
                                 alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <!-- Page level custom scripts -->
    <script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
    <script>
        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });
    </script>

    <script>
        $('#add').on('click', function () {
            var name = $('#name').val();
            var symbol = $('#symbol').val();
            var code = $('#code').val();
            var exchange_rate = $('#exchange_rate').val();
            if (name == "" || symbol == "" || code == "" || exchange_rate == "") {
                alert('{{\App\CPU\translate('All input field is required')}}');
                return false;
            } else {
                return true;
            }
        });
        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.currency.status')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function (response) {
                    if (response.status === 1) {
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                    location.reload();
                }
            });
        });
    </script>
@endpush
