@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Track Order'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="{{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="{{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.css"/>
@endpush

@section('content')
    <!-- Page Title (Dark)-->
    <div class="container rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">

        <div class="pt-3 pb-3">
            <h2>{{\App\CPU\translate('my_order')}}</h2>
        </div>
        <div class="btn-primary">
            <div class="container d-lg-flex justify-content-between py-2 py-lg-3">

                <div
                    class="order-lg-1 {{Session::get('direction') === "rtl" ? 'pl-lg-4' : 'pr-lg-4'}} text-center text-lg-left">
                    <h4 class="text-light mb-0">{{\App\CPU\translate('order_id')}}:
                        <span class="h4 font-weight-normal text-light">{{$orderDetails['id']}}</span></h4>
                </div>
            </div>
        </div>

    </div>
    <!-- Page Content-->
    <div class="container mb-md-3 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <!-- Details-->
        <div class="row" style="background: #e2f0ff; margin: 0; width: 100%;">
            <div class="col-sm-4">
                <div class="pt-2 pb-2 text-center rounded-lg">
                    <span
                        class="font-weight-medium text-dark {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">{{\App\CPU\translate('order_status')}}:</span><br>
                    <span class="text-uppercase ">{{str_replace('_',' ',$orderDetails['order_status'])}}</span>
                    {{-- <span class="text-uppercase ">Courier</span> --}}
                </div>
            </div>
            <div class="col-sm-4">
                <div class="pt-2 pb-2 text-center rounded-lg">
                    <span
                        class="font-weight-medium text-dark {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">{{\App\CPU\translate('payment_status')}}:</span>
                    <br>
                    <span class="text-uppercase">{{$orderDetails['payment_status']}}</span>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="pt-2 pb-2 text-center rounded-lg">
                    <span
                        class="font-weight-medium text-dark {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">{{\App\CPU\translate('estimated_delivery_date')}}: </span>
                    <br>
                    <span class="text-uppercase"
                          style="font-weight: 600; color: {{$web_config['primary_color']}}">{{Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$orderDetails['updated_at'])->format('Y-m-d')}}</span>
                </div>
            </div>
        </div>
        <!-- Progress-->
        <div class="card border-0 box-shadow-lg mt-5">
            <div class="card-body pb-2">
                <ul class="nav nav-tabs media-tabs nav-justified">
                    @if ($orderDetails['order_status']!='returned' && $orderDetails['order_status']!='failed' && $orderDetails['order_status']!='canceled')

                        <li class="nav-item">
                            <div class="nav-link">
                                <div class="align-items-center">
                                    <div class="media-tab-media"
                                         style="margin: 0 auto; background: #4bcc02; color: white;">
                                        <i class="czi-check"></i>
                                    </div>
                                    <div class="media-body" style="text-align: center;">
                                        <div class="media-tab-subtitle text-muted font-size-xs mb-1">{{\App\CPU\translate('first_step')}}</div>
                                        <h6 class="media-tab-title text-nowrap mb-0">{{\App\CPU\translate('order_placed')}}</h6>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item ">
                            <div class="nav-link ">
                                <div class="align-items-center">
                                    <div class="media-tab-media"
                                         style="margin: 0 auto; @if(($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')) background: #4bcc02; color: white; @endif ">
                                        @if(($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered'))
                                            <i class="czi-check"></i>
                                        @endif
                                    </div>
                                    <div class="media-body" style="text-align: center;">
                                        <div class="media-tab-subtitle text-muted font-size-xs mb-1">{{\App\CPU\translate('second_step')}}</div>
                                        <h6 class="media-tab-title text-nowrap mb-0">{{\App\CPU\translate('processing_order')}}</h6>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="nav-link  ">
                                <div class="align-items-center">
                                    <div class="media-tab-media"
                                         style="margin: 0 auto; @if(($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='delivered')) background: #4bcc02; color: white; @endif ">
                                        @if(($orderDetails['order_status']=='out_for_delivery') || ($orderDetails['order_status']=='processing') || ($orderDetails['order_status']=='processed') || ($orderDetails['order_status']=='delivered'))
                                            <i class="czi-check"></i>
                                        @endif
                                    </div>
                                    <div class="media-body" style="text-align: center;">
                                        <div class="media-tab-subtitle text-muted font-size-xs mb-1">{{\App\CPU\translate('third_step')}}</div>
                                        <h6 class="media-tab-title text-nowrap mb-0">{{\App\CPU\translate('product_on_delivery')}}</h6>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <li class="nav-item">
                            <div class="nav-link ">
                                <div class="align-items-center">
                                    <div class="media-tab-media"
                                         style="margin: 0 auto; @if(($orderDetails['order_status']=='delivered')) background: #4bcc02; color: white; @endif">
                                        @if(($orderDetails['order_status']=='delivered'))
                                            <i class="czi-check"></i>
                                        @endif
                                    </div>
                                    <div class="media-body" style="text-align: center;">
                                        <div class="media-tab-subtitle text-muted font-size-xs mb-1">{{\App\CPU\translate('fourth_step')}}</div>
                                        <h6 class="media-tab-title text-nowrap mb-0">{{\App\CPU\translate('product_dispatched')}}</h6>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @elseif($orderDetails['order_status']=='returned')
                        <li class="nav-item">
                            <div class="nav-link" style="text-align: center;">
                                <h1 class="text-warning">{{\App\CPU\translate('order_successfully_returned')}}</h1>
                            </div>
                        </li>
                    @elseif($orderDetails['order_status']=='canceled')
                        <li class="nav-item">
                            <div class="nav-link" style="text-align: center;">
                                <h1 class="text-danger">{{\App\CPU\translate('order_hasbeen_cancelled')}}</h1>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <div class="nav-link" style="text-align: center;">
                                <h1 class="text-danger">{{\App\CPU\translate('sorry_we_can_not_complete_your_order')}}</h1>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
        <!-- Footer-->
        <div class="d-sm-flex flex-wrap justify-content-between align-items-center text-center pt-3">
            <div class="custom-control custom-checkbox mt-1 {{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">
            </div>
            <a class="btn btn-primary btn-sm mt-2 mb-2"
               href="{{ route('account-order-details', ['id'=>$orderDetails->id]) }}">{{\App\CPU\translate('View')}} {{\App\CPU\translate('Order')}} {{\App\CPU\translate('Details')}}</a>
        </div>
    </div>

@endsection

@push('script')

@endpush
