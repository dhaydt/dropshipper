@extends('layouts.back-end.app-seller')
@section('title',\App\CPU\translate('Order Details'))

@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush
@section('content')
    <!-- Page Heading -->
    <div class="content container-fluid">

        <div class="page-header d-print-none p-3" style="background: white">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="{{route('seller.orders.list',['all'])}}">{{\App\CPU\translate('Orders')}}</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">{{\App\CPU\translate('Order details')}}</li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('Order')}} #{{$order['id']}}</h1>
                        {{-- {{ $order}} --}}

                        @if($order['payment_status']=='paid')
                            <span
                                class="badge badge-soft-success {{Session::get('direction') === "rtl" ? 'mr-sm-3' : 'ml-sm-3'}}">
                            <span class="legend-indicator bg-success"
                                  style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate('Paid')}}
                        </span>
                        @else
                            <span
                                class="badge badge-soft-danger {{Session::get('direction') === "rtl" ? 'mr-sm-3' : 'ml-sm-3'}}">
                            <span class="legend-indicator bg-danger"
                                  style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{\App\CPU\translate('Unpaid')}}
                        </span>
                        @endif

                        @if($order['order_status']=='pending')
                            <span
                                class="badge badge-soft-info {{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}} text-capitalize">
                          <span class="legend-indicator bg-info text"
                                style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{str_replace('_',' ',$order['order_status'])}}
                        </span>
                        @elseif($order['order_status']=='failed')
                            <span
                                class="badge badge-danger {{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}} text-capitalize">
                          <span class="legend-indicator bg-info"
                                style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{str_replace('_',' ',$order['order_status'])}}
                        </span>
                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                            <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-warning"></span>{{str_replace('_',' ',$order['order_status'])}}
                            </span>

                        @elseif($order['order_status']=='delivered' || $order['order_status']=='confirmed')
                            <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-success"></span>{{str_replace('_',' ',$order['order_status'])}}
                            </span>
                        @else
                            <span
                                class="badge badge-soft-danger {{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}} text-capitalize">
                          <span class="legend-indicator bg-danger"
                                style="{{Session::get('direction') === "rtl" ? 'margin-right: 0;margin-left: .4375rem;' : 'margin-left: 0;margin-right: .4375rem;'}}"></span>{{str_replace('_',' ',$order['order_status'])}}
                        </span>
                        @endif
                        <span class="{{Session::get('direction') === "rtl" ? 'mr-2 mr-sm-3' : 'ml-2 ml-sm-3'}}">
                                <i class="tio-date-range"></i> {{date('d M Y H:i:s',strtotime($order['created_at']))}}
                        </span>
                        @if(\App\CPU\Helpers::get_business_settings('order_verification'))
                            <span class="ml-2 ml-sm-3">
                                <b>
                                    {{\App\CPU\translate('order_verification_code')}} : {{$order['verification_code']}}
                                </b>
                            </span>
                        @endif
                    </div>

                    <div class="col-md-6 mt-2">
                        <a class="text-body" target="_blank"
                           href={{route('seller.orders.generate-invoice',[$order->id])}}>
                            <i class="tio-print"></i> {{\App\CPU\translate('Print invoice')}}
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-6 mt-4">
                        </div>
                        <div class="col-6">
                            <div class="hs-unfold float-right">
                                <div class="dropdown">
                                    <select name="order_status" onchange="order_status(this.value)" disabled
                                            class="status form-control"
                                            data-id="{{$order['id']}}">

                                        <option
                                            value="pending" {{$order->order_status == 'pending'?'selected':''}} > {{\App\CPU\translate('Pending')}}</option>
                                        <option
                                            value="confirmed" {{$order->order_status == 'confirmed'?'selected':''}} > {{\App\CPU\translate('Confirmed')}}</option>
                                        <option
                                            value="processing" {{$order->order_status == 'processing'?'selected':''}} >{{\App\CPU\translate('Processing')}} </option>
                                        {{-- @if( $order->shipping->creator_type != 'admin') --}}
                                            <option
                                                value="out_for_delivery" {{$order->order_status == 'out_for_delivery'?'selected':''}} >{{\App\CPU\translate('out_for_delivery')}} </option>
                                            <option
                                                value="delivered" {{$order->order_status == 'delivered'?'selected':''}} >{{\App\CPU\translate('Delivered')}} </option>
                                            <option
                                                value="returned" {{$order->order_status == 'returned'?'selected':''}} > {{\App\CPU\translate('Returned')}}</option>
                                            <option
                                                value="failed" {{$order->order_status == 'failed'?'selected':''}} >{{\App\CPU\translate('Failed')}} </option>
                                            <option
                                                value="canceled" {{$order->order_status == 'canceled'?'selected':''}} >{{\App\CPU\translate('Canceled')}} </option>
                                        {{-- @endif --}}
                                    </select>
                                </div>
                            </div>

                            @php($shipping=\App\CPU\Helpers::get_business_settings('shipping_method'))
                            @if($order['payment_method']=='cash_on_delivery' && $shipping=='sellerwise_shipping')
                                <div class="hs-unfold float-right pr-2">
                                    <div class="dropdown">
                                        <select name="payment_status" class="payment_status form-control"
                                                data-id="{{$order['id']}}">
                                            <option
                                                onclick="route_alert('{{route('admin.orders.payment-status',['id'=>$order['id'],'payment_status'=>'paid'])}}','{{\App\CPU\translate('Change status to paid')}} ?')"
                                                href="javascript:"
                                                value="paid" {{$order->payment_status == 'paid'?'selected':''}} >
                                                {{\App\CPU\translate('Paid')}}
                                            </option>
                                            <option
                                                value="unpaid" {{$order->payment_status == 'unpaid'?'selected':''}} >
                                                {{\App\CPU\translate('Unpaid')}}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>


        <div class="row" id="printableArea">
            <div class="col-lg-8 mb-3  mb-lg-0">
                <!-- Card -->
                <div class="card mb-3  mb-lg-5"
                     style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                    <!-- Header -->
                    <div class="card-header" style="display: block!important;">
                        <div class="row">
                            <div class="col-12 pb-2 border-bottom">
                                <h4 class="card-header-title">
                                   {{\App\CPU\translate('Order details')}}
                                    <span
                                        class="badge badge-soft-dark rounded-circle {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">{{$order->details->count()}}</span>
                                </h4>
                            </div>
                            <div class="col-6 pt-2">
                                <h6 style="color: #8a8a8a;">

                                </h6>
                            </div>
                            <div class="col-6 pt-2">
                                <div class="flex-end">
                                    <h6 class="text-capitalize"
                                        style="color: #8a8a8a;">{{\App\CPU\translate('payment_method')}} :</h6>
                                    <h6 class="mx-1"
                                        style="color: #8a8a8a;">{{str_replace('_',' ',$order['payment_method'])}}</h6>
                                </div>
                                {{-- <div class="flex-end">
                                    <h6 style="color: #8a8a8a;">{{\App\CPU\translate('Payment')}} {{\App\CPU\translate('reference')}}
                                        :</h6>
                                    <h6 class="mx-1"
                                        style="color: #8a8a8a;">{{str_replace('_',' ',$order['transaction_ref'])}}</h6>
                                </div> --}}
                                <div class="flex-end">
                                    <h6 style="color: #8a8a8a;">{{\App\CPU\translate('shipping')}} {{\App\CPU\translate('method')}}
                                        :</h6>
                                    <h6 class="mx-1" style="color: #8a8a8a;">{{$order->shipping}}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <div class="media">
                            <div class="avatar avatar-xl {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">
                                <p>{{\App\CPU\translate('image')}}</p>
                            </div>

                            <div class="media-body">
                                <div class="row">
                                    <div class="col-md-4 product-name">
                                        <p> {{\App\CPU\translate('Name')}}</p>
                                    </div>

                                    <div class="col col-md-2 align-self-center p-0 ">
                                        <p> {{\App\CPU\translate('price')}}</p>
                                    </div>

                                    <div class="col col-md-1 align-self-center">
                                        <p>{{\App\CPU\translate('Q')}}</p>
                                    </div>
                                    <div class="col col-md-1 align-self-center  p-0 product-name">
                                        <p> {{\App\CPU\translate('TAX')}}</p>
                                    </div>
                                    <div class="col col-md-2 align-self-center  p-0 product-name">
                                        <p> {{\App\CPU\translate('Discount')}}</p>
                                    </div>
                                    <div
                                        class="col col-md-2 align-self-center text-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                        <p> {{\App\CPU\translate('Subtotal')}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @php($subtotal=0)
                    @php($total=0)
                    @php($shipping=0)
                    @php($discount=0)
                    @php($tax=0)

                    @foreach($order->details as $detail)
                        @if($detail->product)

                            <!-- Media -->
                                <div class="media">
                                    <div
                                        class="avatar avatar-xl {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">
                                        <img class="img-fluid"
                                             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                             src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$detail->product['thumbnail']}}"
                                             alt="Image Description">
                                    </div>

                                    <div class="media-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3 mb-md-0 product-name">
                                                <p>
                                                    {{substr($detail->product['name'],0,10)}}{{strlen($detail->product['name'])>10?'...':''}}
                                                </p>
                                                <strong><u>{{\App\CPU\translate('variation')}} : </u></strong>

                                                <div class="font-size-sm text-body">
                                                    <span class="font-weight-bold">{{$detail['variant']}}</span>
                                                </div>
                                            </div>

                                            <div class="col col-md-2 align-self-center p-0 ">
                                                <h6>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['price']))}}</h6>
                                            </div>

                                            <div class="col col-md-1 align-self-center">

                                                <h5>{{$detail->qty}}</h5>
                                            </div>
                                            <div class="col col-md-1 align-self-center  p-0 product-name">

                                                <h5>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['tax']))}}</h5>
                                            </div>
                                            <div class="col col-md-2 align-self-center  p-0 product-name">
                                                <h5>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['discount']))}}</h5>
                                            </div>


                                            <div
                                                class="col col-md-2 align-self-center text-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                                @php($subtotal=$detail['price']*$detail->qty+$detail['tax']-$detail['discount'])

                                                <h5>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @php($discount+=$detail['discount'])
                            @php($tax+=$detail['tax'])
                            @php($shipping+=$detail->shipping ? $detail->shipping->cost :0)

                            @php($total+=$subtotal)
                            <!-- End Media -->
                                <hr>
                            @endif
                        @endforeach
                        @php($shipping=$order['shipping_cost'])
                        @php($coupon_discount=$order['discount_amount'])
                        <div class="row justify-content-md-end mb-3">
                            <div class="col-md-9 col-lg-8">
                                <dl class="row text-sm-right">
                                    <dt class="col-sm-6">{{\App\CPU\translate('Shipping')}}</dt>
                                    <dd class="col-sm-6 border-bottom">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($shipping))}}</strong>
                                    </dd>

                                    <dt class="col-sm-6">{{\App\CPU\translate('coupon_discount')}}</dt>
                                    <dd class="col-sm-6 border-bottom">
                                        <strong>- {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($coupon_discount))}}</strong>
                                    </dd>

                                    <dt class="col-sm-6">{{\App\CPU\translate('Total')}}</dt>
                                    <dd class="col-sm-6">
                                        <strong>{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($total+$shipping-$coupon_discount))}}</strong>
                                    </dd>
                                </dl>
                                <!-- End Row -->
                            </div>
                        </div>

                        <!-- End Row -->
                    </div>
                    <!-- End Body -->
                </div>
                <!-- End Card -->


            </div>

            <div class="col-lg-4">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">{{\App\CPU\translate('Customer')}}</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    @if($order->shipping_address_data !== NULL)
                    @php($user = json_decode($order->shipping_address_data))
                        <div class="card-body"
                             style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            <div class="media align-items-center" href="javascript:">
                                <div
                                    class="avatar avatar-circle {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">
                                    <img
                                        class="avatar-img"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src=""
                                        alt="Image Description">
                                </div>
                                <div class="media-body">
                            <span
                                class="text-body text-hover-primary">{{$user->contact_person_name}}</span>
                                </div>
                                <div class="media-body text-right">
                                    {{--<i class="tio-chevron-right text-body"></i>--}}
                                </div>
                            </div>

                            <hr>

                            <div class="media align-items-center" href="javascript:">
                                <div
                                    class="icon icon-soft-info icon-circle {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">
                                    <i class="tio-shopping-basket-outlined"></i>
                                </div>
                                <div class="media-body">
                                    <span class="text-body text-hover-primary"> {{$user->address.', '.$user->district.', '.$user->city.', '.$user->province}}</span>
                                </div>
                                <div class="media-body text-right">
                                    {{--<i class="tio-chevron-right text-body"></i>--}}
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('Contact info')}}</h5>
                            </div>

                            {{-- <div class="flex-start">
                                <div>
                                    <i class="tio-online {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"></i>
                                </div>
                                <div class="mx-1"><a class="text-dark"
                                                     href="mailto: {{$order->customer['email']}}">{{$order->customer['email']}}</a>
                                </div>
                            </div> --}}
                            <div class="flex-start">
                                <div>
                                    <i class="tio-android-phone-vs {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"></i>
                                </div>
                                <div class="mx-1"><a class="text-dark"
                                                     href="tel:{{$user->phone}}">{{$user->phone}}</a>
                                </div>
                            </div>

                            <hr>

                            {{-- <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('shipping_address')}}</h5>

                            </div> --}}

                            {{-- @if($order->shippingAddress)
                                @php($shipping=$order->shippingAddress)
                            @else
                                @php($shipping=json_decode($order['shipping_address_data']))
                            @endif

                            <span class="d-block">{{\App\CPU\translate('Name')}} :
                                <strong>{{$shipping? $shipping->contact_person_name : \App\CPU\translate('empty')}}</strong><br>
                                 {{\App\CPU\translate('Country')}}:
                                <strong>{{$shipping ? $shipping->country : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('City')}}:
                                <strong>{{$shipping ? $shipping->city : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('zip_code')}} :
                                <strong>{{$shipping ? $shipping->zip  : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('address')}} :
                                <strong>{{$shipping ? $shipping->address  : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('Phone')}}:
                                <strong>{{$shipping ? $shipping->phone  : \App\CPU\translate('empty')}}</strong>
                            </span> --}}
                        </div>
                @endif
                <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $(document).on('change', '.payment_status', function () {
            var id = $(this).attr("data-id");
            var value = $(this).val();
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure Change this?')}}',
                text: "{{\App\CPU\translate('You wont be able to revert this!')}}",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Yes, Change it')}}!'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.orders.payment-status')}}",
                        method: 'POST',
                        data: {
                            "id": id,
                            "payment_status": value
                        },
                        success: function (data) {
                            toastr.success('{{\App\CPU\translate('Status Change successfully')}}');
                            location.reload();
                        }
                    });
                }
            })
        });

        function order_status(status) {
            var value = status;
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure Change this?')}}',
                text: "{{\App\CPU\translate('You wont be able to revert this!')}}",
                showCancelButton: true,
                confirmButtonColor: '#377dff',
                cancelButtonColor: 'secondary',
                confirmButtonText: '{{\App\CPU\translate('Yes, Change it!')}}'
            }).then((result) => {
                if (result.value) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "{{route('seller.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": value
                        },
                        success: function (data) {
                            if (data.success == 0) {
                                toastr.success('{{\App\CPU\translate('Order is already delivered, You can not change it !!')}}');
                                location.reload();
                            } else {
                                toastr.success('{{\App\CPU\translate('Status Change successfully !')}}');
                                location.reload();
                            }
                        }
                    });
                }
            })
        }
    </script>
@endpush
