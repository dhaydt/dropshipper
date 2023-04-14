@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Order Sukses'))

@push('css_or_js')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Montserrat&display=swap');

        body {
            font-family: 'Montserrat', sans-serif
        }

        .card {
            border: none
        }

        .totals tr td {
            font-size: 13px
        }

        .footer span {
            font-size: 12px
        }

        .product-qty span {
            font-size: 14px;
            color: #6A6A6A;
        }

        .spanTr {
            color: {{$web_config['primary_color']}};
            font-weight: 700;

        }

        .spandHeadO {
            color: #030303;
            font-weight: 500;
            font-size: 20px;

        }

        .font-name {
            font-weight: 600;
            font-size: 13px;
        }

        .amount {
            font-size: 17px;
            color: {{$web_config['primary_color']}};
        }

        @media (max-width: 600px) {
            .orderId {
                margin- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 91px;
            }

            .p-5 {
                padding: 2% !important;
            }

            .spanTr {

                font-weight: 400 !important;
                font-size: 12px;
            }

            .spandHeadO {

                font-weight: 300;
                font-size: 12px;

            }

            .table th, .table td {
                padding: 5px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container mt-5 mb-5 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row d-flex justify-content-center">
            <div class="col-md-10 col-lg-10">
                <div class="card">
                    @if(auth('customer')->check())
                        <div class=" p-5">
                            <div class="row">
                                <div class="col-md-6">
                                    @if (isset($order_id))                                        
                                    <h5 style="font-size: 20px; font-weight: 900">{{\App\CPU\translate('Order_kamu_berhasil_disimpan!')}} !</h5>
                                    @else
                                    <h5 style="font-size: 20px; font-weight: 900">{{\App\CPU\translate('Pembayaran berhasil!')}}
                                    !</h5>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-12">
                                    <center>
                                        <i style="font-size: 100px; color: #0f9d58" class="fa fa-check-circle"></i>
                                    </center>
                                </div>
                            </div>

                            <span class="font-weight-bold d-block mt-4 text-capitalize" style="font-size: 17px;">{{\App\CPU\translate('Halo')}}
                                @if (isset($order_id))
                                , {{auth('customer')->user()->f_name}}
                                @endif
                            </span>
                            @if (isset($order_id))
                            <span>{{\App\CPU\translate('Order_kamu_dengan_nomor_'.$order_id.'_berhasil_disimpan, Mohon_selesaikan_pembayaran_agar_order_kamu_diproses!')}}</span>
                            @endif

                            <div class="row mt-4">
                                <div class="col-4">
                                    <a href="{{route('home')}}" class="btn btn-primary">
                                        {{\App\CPU\translate('pergi_belanja')}}
                                    </a>
                                </div>
                                @if (isset($order_id))
                                <div class="col-4">
                                    <button type="button" class="btn btn-success w-100" data-toggle="modal" data-target="#pay{{ $order_id }}">
                                    Bayar Sekarang
                                    </button>
                                </div>
                                <div class="col-4">
                                    <a href="{{route('account-oder')}}"
                                    class="btn btn-secondary pull-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                        {{\App\CPU\translate('cek_order')}}
                                    </a>
                                </div>
                                @endif

                            </div>
                        </div>
                    @elseif (auth('seller')->check())
                    <div class=" p-5">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 style="font-size: 20px; font-weight: 900">{{\App\CPU\translate('your_order_has_been_placed_successfully_with_id!')}}
                                    !</h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <center>
                                    <i style="font-size: 100px; color: #0f9d58" class="fa fa-check-circle"></i>
                                </center>
                            </div>
                        </div>

                        <span class="font-weight-bold d-block mt-4" style="font-size: 17px;">{{\App\CPU\translate('Hello')}}, {{auth('seller')->user()->f_name}}</span>
                        <span>{{\App\CPU\translate('You order has been confirmed and will be shipped according to the method you selected!')}}</span>

                        <div class="row mt-4 justify-content-between">
                            <div class="col-4">
                                <a href="{{route('home')}}" class="btn btn-primary">
                                    {{\App\CPU\translate('go_to_shopping')}}
                                </a>
                            </div>
                            @if (isset($order_id))
                            <div class="col-4">
                                <button type="button" class="btn btn-success w-100" data-toggle="modal" data-target="#pay{{ $order_id }}">
                                Bayar Sekarang
                                </button>
                            </div>
                            @endif

                            <div class="col-4 d-flex justify-content-end">
                                <a href="{{route('seller.orders.list', ['all'])}}"
                                   class="btn btn-secondary pull-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                    {{\App\CPU\translate('Check_Order')}}
                                </a>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class=" p-5">
                        <div class="row">
                            <div class="col-md-6">
                                <h5 style="font-size: 20px; font-weight: 900">{{\App\CPU\translate('Pembayaran berhasil!')}}
                                    !</h5>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <center>
                                    <i style="font-size: 100px; color: #0f9d58" class="fa fa-check-circle"></i>
                                </center>
                            </div>
                        </div>

                        <span class="font-weight-bold d-block mt-4" style="font-size: 17px;">{{\App\CPU\translate('Halo')}}</span>
                        <span>{{\App\CPU\translate('Order_kamu_sedang_diproses!')}}, Terima kasih..</span>

                        {{-- <div class="row mt-4">
                            <div class="col-4">
                                <a href="{{route('home')}}" class="btn btn-primary">
                                    {{\App\CPU\translate('pergi_belanja')}}
                                </a>
                            </div>

                            <div class="col-4">
                                <button type="button" class="btn btn-success w-100" data-toggle="modal" data-target="#pay{{ $order_id }}">
                                Bayar Sekarang
                                </button>
                            </div>

                            <div class="col-4">
                                <a href="{{route('account-oder')}}"
                                   class="btn btn-secondary pull-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                    {{\App\CPU\translate('cek_order')}}
                                </a>
                            </div>
                        </div> --}}
                    </div>
                    @endif
                    @if (isset($order_id))
                    <div class="modal fade" id="pay{{ $order_id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Pilih metode pembayaran untuk order {{ $order_id }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </div>
                            <div class="modal-body">
                            <div class="row">
                                @foreach ($payment as $p)
                                    <div class="col-md-6 mb-4" style="cursor: pointer">
                                        <div class="card">
                                            <div class="card-body" style="height: 100px">
                                                <form class="needs-validation" target="_blank" method="POST" id="payment-form"
                                                    action="{{route('xendit-payment.vaInvoice')}}">

                                                    <input type="hidden" name="type" value="{{ $p['code'] }}">
                                                    <input type="hidden" name="order_id" value="{{ $order_id }}">
                                                    {{-- <input class="price" type="hidden" name="price" value="price"> --}}
                                                    {{ csrf_field() }}
                                                    <button class="btn btn-block" type="submit">
                                                        <img width="150" style="margin-top: -10px"
                                                        src="{{asset('assets/front-end/img/'.strtolower($p['code']).'.png')}}" />
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            </div>
                            <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                            </div>
                        </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

@endpush
