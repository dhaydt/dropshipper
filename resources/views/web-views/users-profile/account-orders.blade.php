@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Daftar Order Saya'))

@push('css_or_js')
    <style>
        .widget-categories .accordion-heading > a:hover {
            color: #FFD5A4 !important;
        }

        .widget-categories .accordion-heading > a {
            color: #FFD5A4;
        }

        body {
            font-family: 'Titillium Web', sans-serif
        }

        .card {
            border: none
        }

        .totals tr td {
            font-size: 13px
        }

        .product-qty span {
            font-size: 14px;
            color: #6A6A6A;
        }

        .spandHeadO {
            color: #FFFFFF !important;
            font-weight: 600 !important;
            font-size: 14px;

        }

        .tdBorder {
            border- {{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 1px solid #f7f0f0;
            text-align: center;
        }

        .bodytr {
            text-align: center;
            vertical-align: middle !important;
        }

        .sidebar h3:hover + .divider-role {
            border-bottom: 3px solid {{$web_config['primary_color']}}                                   !important;
            transition: .2s ease-in-out;
        }

        tr td {
            padding: 3px 5px !important;
        }

        td button {
            padding: 3px 13px !important;
        }

        @media (max-width: 600px) {
            .sidebar_heading {
                background: {{$web_config['primary_color']}};
            }

            .orderDate {
                display: none;
            }

            .sidebar_heading h1 {
                text-align: center;
                color: aliceblue;
                padding-bottom: 17px;
                font-size: 19px;
            }
        }
    </style>
@endpush

@section('content')

    <div class="container rtl d-none d-md-block" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-9 sidebar_heading">
                <h1 class="h3  mb-0 float-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} headerTitle">{{\App\CPU\translate('order_saya')}}</h1>
            </div>
        </div>
    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
        @include('web-views.partials._profile-aside')
        <!-- Content  -->
            <section class="col-lg-9 mt-2 col-md-9">
                <div class="card box-shadow-sm">
                    <div style="overflow: auto">
                        <table class="table">
                            <thead>
                            <tr style="background-color: #6b6b6b;">
                                <td class="tdBorder">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO ">{{\App\CPU\translate('Order#')}}</span></div>
                                </td>

                                <td class="tdBorder orderDate">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO">{{\App\CPU\translate('Tanggal')}} {{\App\CPU\translate('Order')}}</span>
                                    </div>
                                </td>
                                <td class="tdBorder">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO"> {{\App\CPU\translate('Status')}}</span></div>
                                </td>
                                <td class="tdBorder">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO"> {{\App\CPU\translate('Total')}}</span></div>
                                </td>
                                <td class="tdBorder">
                                    <div class="py-2"><span
                                            class="d-block spandHeadO"> {{\App\CPU\translate('Aksi')}}</span></div>
                                </td>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td class="bodytr font-weight-bold">
                                        {{\App\CPU\translate('ID')}}: {{$order['id']}}
                                    </td>
                                    <td class="bodytr orderDate"><span class="">{{$order['created_at']}}</span></td>
                                    <td class="bodytr">
                                        @if($order['order_status']=='failed' || $order['order_status']=='canceled')
                                            <span class="badge badge-danger text-capitalize">
                                                {{-- {{\App\CPU\translate($order['order_status'])}} --}}
                                                Dibatalkan
                                            </span>
                                        @elseif($order['order_status']=='confirmed' || $order['order_status']=='processing' || $order['order_status']=='delivered')
                                            <span class="badge badge-success text-capitalize">
                                                @if ($order['order_status']=='processing')
                                                Diproses
                                                @elseif($order['order_status']=='delivered')
                                                Dikirim
                                                @else
                                                Dikonfirmasi
                                                @endif
                                            </span>
                                        @else
                                            <span class="badge badge-info text-capitalize">
                                                @if($order['order_status'] == 'pending')
                                                    Menunggu Pembayaran
                                                @else
                                                    {{\App\CPU\translate($order['order_status'])}}
                                                @endif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="bodytr">
                                        {{\App\CPU\Helpers::currency_converter($order['order_amount'])}}
                                    </td>
                                    <td class="bodytr" style="width: 162px">
                                        <a href="{{ route('account-order-details', ['id'=>$order->id]) }}"
                                           class="btn btn-secondary btn-sm">
                                            <i class="fa fa-eye"></i> {{\App\CPU\translate('Lihat')}}
                                        </a>
                                        @if ($order->order_status == 'pending')
                                        <button type="button" class="btn btn-primary btn-sm p-2" data-toggle="modal" data-target="#pay{{ $order->id }}">
                                            <i class="fa fa-dollar"></i> Bayar
                                        </button>
                                        @endif
                                        @if($order['payment_method']=='cash_on_delivery' && $order['order_status']=='pending')
                                            <a href="javascript:"
                                               onclick="route_alert('{{ route('order-cancel',[$order->id]) }}','{{\App\CPU\translate('want_to_cancel_this_order?')}}')"
                                               class="btn btn-danger btn-sm p-2 top-margin">
                                                <i class="fa fa-trash"></i> {{\App\CPU\translate('Batal')}}
                                            </a>
                                        @else
                                            <button class="btn btn-danger btn-sm p-2 top-margin" onclick="cancel_message()">
                                                <i class="fa fa-trash"></i> {{\App\CPU\translate('Batal')}}
                                            </button>
                                        @endif
                                    </td>
                                    <!-- Modal -->
                                </tr>
                                 <!-- Modal -->
                                <div class="modal fade" id="pay{{ $order->id }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Pilih metode pembayaran untuk order {{ $order->id }}</h5>
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
                                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
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
                            @endforeach
                            </tbody>
                        </table>
                        @if($orders->count()==0)
                            <center class="mt-3 mb-2">{{\App\CPU\translate('no_order_found')}}</center>
                        @endif
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function cancel_message() {
            toastr.info('{{\App\CPU\translate('order_can_be_canceled_only_when_pending.')}}', {
                CloseButton: true,
                ProgressBar: true
            });
        }
    </script>
@endpush
