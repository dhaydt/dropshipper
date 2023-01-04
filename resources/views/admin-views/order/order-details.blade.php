@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Order Details'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .sellerName {
            height: fit-content;
            margin-top: 10px;
            margin-left: 10px;
            font-size: 16px;
            border-radius: 25px;
            text-align: center;
            padding-top: 10px;
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="page-header d-print-none p-3" style="background: white">
            <div class="row align-items-center">
                <div class="col-sm mb-2 mb-sm-0">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-no-gutter">
                            <li class="breadcrumb-item"><a class="breadcrumb-link"
                                                           href="{{route('admin.orders.list',['status'=>'all'])}}">{{\App\CPU\translate('Orders')}}</a>
                            </li>
                            <li class="breadcrumb-item active"
                                aria-current="page">{{\App\CPU\translate('Order')}} {{\App\CPU\translate('details')}} </li>
                        </ol>
                    </nav>

                    <div class="d-sm-flex align-items-sm-center">
                        <h1 class="page-header-title">{{\App\CPU\translate('Order')}} #{{$order['id']}}</h1>

                        @if($order['payment_status']=='paid')
                            <span class="badge badge-soft-success ml-sm-3">
                                <span class="legend-indicator bg-success"></span>{{\App\CPU\translate('Paid')}}
                            </span>
                        @else
                            <span class="badge badge-soft-danger ml-sm-3">
                                <span class="legend-indicator bg-danger"></span>{{\App\CPU\translate('Unpaid')}}
                            </span>
                        @endif

                        @if($order['order_status']=='pending')
                            <span class="badge badge-soft-info ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-info text"></span>Menunggu Pembayaran
                            </span>
                        @elseif($order['order_status']=='failed')
                            <span class="badge badge-danger ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-info"></span>{{str_replace('_',' ',$order['order_status'])}}
                            </span>
                        @elseif($order['order_status']=='processing' || $order['order_status']=='out_for_delivery')
                            <span class="badge badge-soft-warning ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-warning"></span>Diproses
                            </span>
                        @elseif($order['order_status']=='delivered' || $order['order_status']=='confirmed')
                            <span class="badge badge-soft-success ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-success"></span>Dikirim
                            </span>
                        @else
                            <span class="badge badge-soft-danger ml-2 ml-sm-3 text-capitalize">
                              <span class="legend-indicator bg-danger"></span>Dibatalkan
                            </span>
                        @endif
                        <span class="ml-2 ml-sm-3">
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
                        <a class="text-body mr-3" target="_blank"
                           href={{route('admin.orders.generate-invoice',[$order['id']])}}>
                            <i class="tio-print mr-1"></i> {{\App\CPU\translate('Print')}} {{\App\CPU\translate('invoice')}}
                        </a>
                    </div>

                    <div class="row">
                        <div class="col-6 mt-4">
                            <label class="badge badge-info">{{\App\CPU\translate('linked_orders')}}
                                : {{$linked_orders->count()}}</label><br>
                            @foreach($linked_orders as $linked)
                                <a href="{{route('admin.orders.details',[$linked['id']])}}" class="btn btn-secondary">{{\App\CPU\translate('ID')}}
                                    :{{$linked['id']}}</a>
                            @endforeach
                        </div>

                        <div class="col-6">
                            <div class="hs-unfold float-right">
                                <div class="dropdown">
                                    <select name="order_status" onchange="order_status(this.value)"
                                            class="status form-control"
                                            data-id="{{$order['id']}}">

                                        <option
                                            value="pending" {{$order->order_status == 'pending'?'selected':''}} > {{\App\CPU\translate('Menunggu_Pembayaran')}}</option>
                                        {{-- <option
                                            value="confirmed" {{$order->order_status == 'confirmed'?'selected':''}} > {{\App\CPU\translate('Confirmed')}}</option> --}}
                                        <option
                                            value="processing" {{$order->order_status == 'processing'?'selected':''}} >{{\App\CPU\translate('Diproses')}} </option>
                                        {{-- <option class="text-capitalize"
                                                value="out_for_delivery" {{$order->order_status == 'out_for_delivery'?'selected':''}} >{{\App\CPU\translate('out_for_delivery')}} </option> --}}
                                        <option
                                            value="delivered" {{$order->order_status == 'delivered'?'selected':''}} >{{\App\CPU\translate('Dikirim')}} </option>
                                        {{-- <option
                                            value="returned" {{$order->order_status == 'returned'?'selected':''}} > {{\App\CPU\translate('Returned')}}</option> --}}
                                        {{-- <option
                                            value="failed" {{$order->order_status == 'failed'?'selected':''}} >{{\App\CPU\translate('Failed')}} </option> --}}
                                        <option
                                            value="canceled" {{$order->order_status == 'canceled'?'selected':''}} >{{\App\CPU\translate('Dibatalkan')}} </option>
                                    </select>
                                </div>
                            </div>
                            <div class="hs-unfold float-right pr-2">
                                <div class="dropdown">
                                    <select name="payment_status" class="payment_status form-control"
                                            data-id="{{$order['id']}}">

                                        <option
                                            onclick="route_alert('{{route('admin.orders.payment-status',['id'=>$order['id'],'payment_status'=>'paid'])}}','Change status to paid ?')"
                                            href="javascript:"
                                            value="paid" {{$order->payment_status == 'paid'?'selected':''}} >
                                            {{\App\CPU\translate('Paid')}}
                                        </option>
                                        <option value="unpaid" {{$order->payment_status == 'unpaid'?'selected':''}} >
                                            {{\App\CPU\translate('Unpaid')}}
                                        </option>

                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Unfold -->
                </div>
            </div>
        </div>

        <!-- End Page Header -->

        <div class="row" id="printableArea">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <!-- Card -->
                <div class="card mb-3 mb-lg-5">
                    <!-- Header -->
                    <div class="card-header" style="display: block!important;">
                        <div class="row">
                            <div class="col-12 pb-2 border-bottom d-flex justify-content-between">
                                <h4 class="card-header-title">
                                    {{\App\CPU\translate('Order')}} {{\App\CPU\translate('details')}}
                                    <span
                                        class="badge badge-soft-dark rounded-circle ml-1">{{$order->details->count()}}</span>
                                </h4>
                                @if (isset($order->resi_kurir))
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#resi_kurir">Resi Marketplace</button>
                                    <div class="modal fade" id="resi_kurir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                        <div class="modal-content">
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="col-8 px-0">
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <img src="{{ asset('storage/resi'.'/'.$order->resi_kurir) }}" class="w-100" alt="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-4 px-0">
                                                        <div class="card p-2">
                                                            <form action="{{ route('admin.orders.printout-resi') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="order_id" value="{{ $order['id'] }}">
                                                                <div class="d-flex flex-column">
                                                                    <label for="" class="d-block" style="font-weight: 600">Ukuran kertas</label>
                                                                    <div class="d-flex">
                                                                        <span class="mr-5 ml-2">
                                                                            <input type="radio" class="mr-2" name="kertas" value="a4"><span> A4</span
                                                                        </span>
                                                                        <span>
                                                                            <input type="radio" class="mr-2" name="kertas" value="a6"><span> A6</span>
                                                                        </span>
                                                                    </div>
                                                                    <label for="" class="d-block mt-5" style="font-weight: 600">Info Tambahan</label>
                                                                    <span>
                                                                        <input type="checkbox" class="mr-2" name="product_list"><span>Cetak dengan daftar produk</span>
                                                                    </span>
                                                                </div>
                                                                <div class="d-flex w-100 mt-3">
                                                                    <button type="submit" class="btn btn-success w-100">Cetak label</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                @endif
                            </div>


                            <div class="col-6 pt-2">
                            </div>
                            <div class="col-6 col-md-4 pt-2 flex-end">
                                <div class="d-flex justify-content-between">
                                    <span class="text-capitalize"
                                        style="color: #8a8a8a;">{{\App\CPU\translate('payment_method')}} :</span>
                                    <span class="mx-1"
                                        style="color: #8a8a8a;">
                                    @if ($order->payment_method !== 'cash_on_delivery')
                                    {{str_replace('_',' ',$order['payment_method'])}}
                                    @else
                                    Belum dipilih
                                    @endif
                                    </span>
                                </div>
                                {{-- <div class="flex-end">
                                    <h6 style="color: #8a8a8a;">{{\App\CPU\translate('Payment')}} {{\App\CPU\translate('reference')}}
                                        :</h6>
                                    <h6 class="mx-1"
                                        style="color: #8a8a8a;">{{str_replace('_',' ',$order['transaction_ref'])}}</h6>
                                </div> --}}
                                <div class="d-flex justify-content-between">
                                    <span style="color: #8a8a8a;">{{\App\CPU\translate('shipping')}} {{\App\CPU\translate('method')}}
                                        :</span>
                                    <span class="mx-1 text-uppercase" style="color: #8a8a8a;">{{$order->shipping}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    <div class="card-body">
                        <div class="media">
                            <div class="avatar avatar-xl mr-3">
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
                                        <p>Q</p>
                                    </div>
                                    <div class="col col-md-1 align-self-center  p-0 product-name">
                                        <p> {{\App\CPU\translate('TAX')}}</p>
                                    </div>
                                    <div class="col col-md-2 align-self-center  p-0 product-name">
                                        <p> {{\App\CPU\translate('Discount')}}</p>
                                    </div>

                                    <div class="col col-md-2 align-self-center text-right  ">
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
                        @foreach($order->details as $key=>$detail)

                            @if($detail->product)
                                @if ($key==0)
                                    @if($detail->product->added_by=='admin')
                                        <div class="row">
                                            <img
                                                class="avatar-img" style="width: 55px;height: 55px; border-radius: 50%;"
                                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                src="{{asset('storage/company/'.\App\Model\BusinessSetting::where(['type' => 'company_web_logo'])->first()->value)}}"
                                                alt="Image">
                                            <p class="sellerName">
                                                <a style="color: black;"
                                                   href="javascript:">
                                                    {{ \App\Model\BusinessSetting::where(['type' => 'company_name'])->first()->value }}
                                                </a>
                                            </p>
                                        </div>
                                    @else
                                        <div class="row">
                                            <img
                                                class="avatar-img" style="width: 55px;height: 55px; border-radius: 50%;"
                                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                src="{{asset('storage/shop/'.\App\Model\Shop::where('seller_id','=',$detail->seller_id)->first()->image)}}"
                                                alt="Image">
                                            <p class="sellerName">
                                                <a style="color: black;"
                                                   href="{{route('admin.sellers.view',$detail->seller_id)}}">{{ \App\Model\Shop::where('seller_id','=',$detail->seller_id)->first()->name}}</a>
                                                <i class="tio tio-info-outined ml-4" data-toggle="collapse"
                                                   style="font-size: 20px; cursor: pointer"
                                                   data-target="#sellerInfoCollapse-{{ $detail->id }}"
                                                   aria-expanded="false"></i>
                                            </p>
                                        </div>

                                        @php($seller = App\Model\Seller::with('shop')->find($detail->seller_id))
                                        <div class="collapse" id="sellerInfoCollapse-{{ $detail->id }}">
                                            <div class="row card-body mb-3">
                                                <div class="col-6">
                                                    <h4>
                                                        {{\App\CPU\translate('Status')}}
                                                        : {!! $seller->status=='approved'?'<label class="badge badge-success">Active</label>':'<label class="badge badge-danger">In-Active</label>' !!}
                                                    </h4>
                                                    <h5>{{\App\CPU\translate('Email')}} : <a
                                                            class="text-dark"
                                                            href="mailto:{{ $seller->email }}">{{ $seller->email }}</a>
                                                    </h5>
                                                </div>
                                                <div class="col-6">
                                                    <h5>{{\App\CPU\translate('name')}} : <a
                                                            class="text-dark"
                                                            href="{{ route('admin.sellers.view', [$seller['id']]) }}">{{ $seller['shop']->name }}</a>
                                                    </h5>
                                                    <h5>{{\App\CPU\translate('Phone')}} : <a
                                                            class="text-dark"
                                                            href="tel:{{ $seller->phone }}">{{ $seller->phone }}</a>
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            <!-- Media -->
                                <div class="media">
                                    <div class="avatar avatar-xl mr-3">
                                        <img class="img-fluid"
                                             onerror="this.src='{{asset('public/assets/back-end/img/160x160/img2.jpg')}}'"
                                             src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$detail->product['thumbnail']}}"
                                             alt="Image Description">
                                    </div>

                                    <div class="media-body">
                                        <div class="row">
                                            <div class="col-md-4 mb-3 mb-md-0 product-name">
                                                <p>
                                                    {{substr($detail->product['name'],0,30)}}{{strlen($detail->product['name'])>10?'...':''}}</p>
                                                <strong><u>{{\App\CPU\translate('Variation')}} : </u></strong>

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

                                                <h5>
                                                    {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($detail['discount']))}}</h5>
                                            </div>

                                            <div class="col col-md-2 align-self-center text-right  ">
                                                @php($subtotal=$detail['price']*$detail->qty+$detail['tax']-$detail['discount'])

                                                <h5 style="font-size: 12px">{{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($subtotal))}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- seller info old --}}

                                @php($discount+=$detail['discount'])
                                @php($tax+=$detail['tax'])
                                @php($total+=$subtotal)
                            <!-- End Media -->
                                <hr>
                            @endif
                            @php($sellerId=$detail->seller_id)
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

            @if ($order->user_is == 'dropship')
            <div class="col-lg-4">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">{{\App\CPU\translate('Dropship')}}</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    @if($order->seller)
                        <div class="card-body">
                            <div class="media align-items-center" href="javascript:">
                                <div class="avatar avatar-circle mr-3">
                                    <img
                                        class="avatar-img" style="width: 75px;height: 42px"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/profile/'.$order->seller->image)}}"
                                        alt="Image">
                                </div>
                                <div class="media-body">
                                <span
                                    class="text-body text-hover-primary">{{$order->seller['f_name']}}</span>
                                </div>
                                <div class="media-body text-right">
                                    {{--<i class="tio-chevron-right text-body"></i>--}}
                                </div>
                            </div>

                            <hr>

                            <div class="media align-items-center" href="javascript:">
                                <div class="icon icon-soft-info icon-circle mr-3">
                                    <i class="tio-shopping-basket-outlined"></i>
                                </div>
                                <div class="media-body">
                                    <span class="text-body text-hover-primary"> {{\App\Model\Order::where(['customer_id' => $order['customer_id'], 'user_is' => 'dropship'])->count()}} {{\App\CPU\translate('orders')}}</span>
                                </div>
                                <div class="media-body text-right">
                                    {{--<i class="tio-chevron-right text-body"></i>--}}
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('Contact')}} {{\App\CPU\translate('info')}} </h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>
                                    <i class="tio-online mr-2"></i>
                                    {{$order->seller['email']}}
                                </li>
                                <li>
                                    <i class="tio-android-phone-vs mr-2"></i>
                                    {{$order->seller['phone']}}
                                </li>
                            </ul>

                            <hr>


                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('shipping_address_customer_dropship')}}</h5>

                            </div>

                            @if($order->shippingAddress)
                                @php($shipping=$order->shippingAddress)
                            @else
                                @php($shipping=json_decode($order['shipping_address_data']))
                            @endif

                            <span class="d-block">{{\App\CPU\translate('Contact_person_name')}} :
                                <strong>{{$shipping? $shipping->contact_person_name : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('District')}}:
                                <strong>{{$shipping ? $shipping->district : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('City')}}:
                                <strong>{{$shipping ? $shipping->city : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('Province')}}:
                                <strong>{{$shipping ? $shipping->province : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('zip_code')}} :
                                <strong>{{$shipping ? $shipping->zip  : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('address')}} :
                                <strong>{{$shipping ? $shipping->address  : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('Phone')}}:
                                <strong>{{$shipping ? $shipping->phone  : \App\CPU\translate('empty')}}</strong>
                            </span>
                        </div>
                        @endif
            @elseif($order->user_is == 'customer')
            <div class="col-lg-4">
                <!-- Card -->
                <div class="card">
                    <!-- Header -->
                    <div class="card-header">
                        <h4 class="card-header-title">{{\App\CPU\translate('Customer')}}</h4>
                    </div>
                    <!-- End Header -->

                    <!-- Body -->
                    @if($order->customer)
                        <div class="card-body">
                            <div class="media align-items-center" href="javascript:">
                                <div class="avatar avatar-circle mr-3">
                                    <img
                                        class="avatar-img" style="width: 75px;height: 42px"
                                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                        src="{{asset('storage/profile/'.$order->customer->image)}}"
                                        alt="Image">
                                </div>
                                <div class="media-body">
                                <span
                                    class="text-body text-hover-primary">{{$order->customer['name']}}</span>
                                </div>
                                <div class="media-body text-right">
                                    {{--<i class="tio-chevron-right text-body"></i>--}}
                                </div>
                            </div>

                            <hr>

                            <div class="media align-items-center" href="javascript:">
                                <div class="icon icon-soft-info icon-circle mr-3">
                                    <i class="tio-shopping-basket-outlined"></i>
                                </div>
                                <div class="media-body">
                                    <span class="text-body text-hover-primary"> {{\App\Model\Order::where('customer_id',$order['customer_id'])->count()}} {{\App\CPU\translate('orders')}}</span>
                                </div>
                                <div class="media-body text-right">
                                    {{--<i class="tio-chevron-right text-body"></i>--}}
                                </div>
                            </div>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('Contact')}} {{\App\CPU\translate('info')}} </h5>
                            </div>

                            <ul class="list-unstyled list-unstyled-py-2">
                                <li>
                                    <i class="tio-online mr-2"></i>
                                    {{$order->customer['email']}}
                                </li>
                                <li>
                                    <i class="tio-android-phone-vs mr-2"></i>
                                    {{$order->customer['phone']}}
                                </li>
                            </ul>

                            <hr>


                            <div class="d-flex justify-content-between align-items-center">
                                <h5>{{\App\CPU\translate('shipping_address')}}</h5>

                            </div>

                            @if($order->shippingAddress)
                                @php($shipping=$order->shippingAddress)
                            @else
                                @php($shipping=json_decode($order['shipping_address_data']))
                            @endif

                            <span class="d-block">{{\App\CPU\translate('Name')}} :
                                <strong>{{$shipping? $shipping->contact_person_name : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('District')}}:
                                <strong>{{$shipping ? $shipping->district : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('City')}}:
                                <strong>{{$shipping ? $shipping->city : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('Province')}}:
                                <strong>{{$shipping ? $shipping->province : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('zip_code')}} :
                                <strong>{{$shipping ? $shipping->zip  : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('address')}} :
                                <strong>{{$shipping ? $shipping->address  : \App\CPU\translate('empty')}}</strong><br>
                                {{\App\CPU\translate('Phone')}}:
                                <strong>{{$shipping ? $shipping->phone  : \App\CPU\translate('empty')}}</strong>
                            </span>
                        </div>
                @endif
                <!-- End Body -->
                </div>
                <!-- End Card -->
            </div>
            @endif
        </div>
        <!-- End Row -->
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('change', '.payment_status', function () {
            var id = $(this).attr("data-id");
            var value = $(this).val();
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure Change this')}}?',
                text: "{{\App\CPU\translate('You will not be able to revert this')}}!",
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
                        url: "{{route('admin.orders.payment-status')}}",
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
            @if($order['order_status']=='delivered')
            Swal.fire({
                title: '{{\App\CPU\translate('Order is already delivered, and transaction amount has been disbursed, changing status can be the reason of miscalculation')}}!',
                text: "{{\App\CPU\translate('Think before you proceed')}}.",
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
                        url: "{{route('admin.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": status
                        },
                        success: function (data) {
                            if (data.success == 0) {
                                toastr.success('{{\App\CPU\translate('Order is already delivered, You can not change it')}} !!');
                                location.reload();
                            } else {
                                toastr.success('{{\App\CPU\translate('Status Change successfully')}}!');
                                location.reload();
                            }

                        }
                    });
                }
            })
            @else
            Swal.fire({
                title: '{{\App\CPU\translate('Are you sure Change this')}}?',
                text: "{{\App\CPU\translate('You will not be able to revert this')}}!",
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
                        url: "{{route('admin.orders.status')}}",
                        method: 'POST',
                        data: {
                            "id": '{{$order['id']}}',
                            "order_status": status
                        },
                        success: function (data) {
                            if (data.success == 0) {
                                toastr.success('{{\App\CPU\translate('Order is already delivered, You can not change it')}} !!');
                                location.reload();
                            } else {
                                toastr.success('{{\App\CPU\translate('Status Change successfully')}}!');
                                location.reload();
                            }

                        }
                    });
                }
            })
            @endif
        }
    </script>
@endpush
