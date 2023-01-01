@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Shipping Method'))

@push('css_or_js')
    <style>
        .btn-outline {
            border-color: {{$web_config['primary_color']}} ;
        }

        .btn-outline {
            color: #020512;
            border-color: {{$web_config['primary_color']}} !important;
        }

        .btn-outline:hover {
            color: white;
            background: {{$web_config['primary_color']}};

        }

        .btn-outline:focus {
            /* border-color: {{$web_config['primary_color']}}   !important; */
        }
        .disabled-link{
            pointer-events: none;
        }
    </style>
@endpush

@section('content')
    <div class="container pb-5 mb-2 mb-md-4 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        @php($shippingMethod=\App\CPU\Helpers::get_business_settings('shipping_method'))
        @php($cart=\App\Model\Cart::where(['customer_id' => auth('customer')->id()])->get()->groupBy('cart_group_id'))
        @if (auth('seller')->check())
        @php($cart=\App\Model\Cart::where(['customer_id' => auth('seller')->id()])->get()->groupBy('cart_group_id'))
        @endif
        <div class="row">
            <div class="col-md-12 mb-5 pt-5">
                <div class="feature_header" style="background: #dcdcdc;line-height: 1px">
                    <span>{{ \App\CPU\translate('Metode_Pengiriman')}}</span>
                </div>
            </div>
            <div class="row">
            </div>
            <section class="col-lg-8">
                <hr>
                <div class="checkout_details mt-3">
                    <!-- Steps-->
                @include('web-views.partials._checkout-steps',['step'=>2])
                <!-- Shipping methods table-->
                    <h2 class="h4 pb-3 mb-2 mt-5">{{ \App\CPU\translate('Pilih_metode_pengiriman')}}</h2>
                    @if (auth('seller')->check())
                    @php($shipping_addresses=\App\Model\ShippingAddress::where('slug',session()->get('customer_address'))->get())
                    @else
                    @php($shipping_addresses=\App\Model\ShippingAddress::where('customer_id',auth('customer')->id())->get())
                    @endif
                    {{-- <section class="col-lg-8"> --}}
                        @if(auth('seller')->check())
                        <div class="card mb-4">
                            @foreach($cart as $group_key=>$group)
                            <form action="{{ route('cart.upload_resi') }}" method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                    @csrf
                                    <input type="hidden" name="cart_group_id" value="{{ $group_key }}">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <label for="" class="d-block w-100">Upload kode booking kurir untuk bebas ongkir</label>
                                            @php($shipping = \App\Model\CartShipping::where('cart_group_id', $group_key)->first())
                                            @if($shipping['resi_kurir'] !== null)
                                            <img src="{{ asset('storage/resi'.'/'.$shipping['resi_kurir']) }}" alt="" id="preview" class="" style="height: 200px; width: 150px;">
                                            @else
                                            <img src="" alt="" id="preview" class="d-none" style="height: 200px; width: 150px;">
                                            @endif
                                            <input type="file" class="form-control" name="resi_kurir" id="resi_kurir"/>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <label for="">Kode invoice pesanan di Marketplace</label>
                                            @if ($shipping['resi_kurir'] !== null)
                                            <input type="text" class="form-control" name="invoice_kurir" id="invoice_kurir" value="{{ $shipping['invoice_kurir'] }}"/>
                                            @else
                                            <input type="text" class="form-control" name="invoice_kurir" id="invoice_kurir" disabled/>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer d-flex justify-content-end">
                                    @if($shipping['resi_kurir'] !== null)
                                    <a href="{{ route('cart.delete_resi', [$group_key]) }}" onclick="loading()" class="btn btn-danger mr-2 btn-sm" type="submit">Hapus resi</a>
                                    @endif
                                    <button class="btn btn-primary btn-sm" type="submit">Simpan resi</button>
                                </div>
                            </form>
                            @endforeach
                        </div>
                        @endif
                        <div class="cart_information">
                            @foreach($cart as $group_key=>$group)
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-1">Nama: </div>
                                        <div class="col-md-6">{{ $address['contact_person_name'] }}</div>
                                        <div class="col-md-6 mb-1">Handphone: </div>
                                        <div class="col-md-6">{{ $address['phone'] }}</div>
                                        <div class="col-md-6 mb-1">Alamat: </div>
                                        <div class="col-md-6">{{ $address['address'] }}</div>
                                        <div class="col-md-6 mb-1">Kecamatan: </div>
                                        <div class="col-md-6">{{ $address['district'] }}</div>
                                        <div class="col-md-6 mb-1">Kota: </div>
                                        <div class="col-md-6">{{ $address['city'] }}</div>
                                        <div class="col-md-6 mb-1">Provinsi: </div>
                                        <div class="col-md-6">{{ $address['province'] }}</div>
                                    </div>
                                </div>
                            </div>
                                @foreach($group as $cart_key=>$cartItem)
                                    {{-- @if($cart_key==0)
                                        @if($cartItem->seller_is=='admin')
                                            {{\App\CPU\Helpers::get_business_settings('company_name')}}
                                        @else
                                            {{\App\Model\Shop::where(['seller_id'=>$cartItem['seller_id']])->first()->name}}
                                        @endif
                                    @endif --}}
                                    {{-- {{ dd($address) }} --}}

                                    @if($cart_key==$group->count()-1)
                                    <!-- choosen shipping method-->
                            @php($choosen_shipping=\App\Model\CartShipping::where(['cart_group_id'=>$cartItem['cart_group_id']])->first())
                            @if(isset($choosen_shipping)==false)
                            @php($choosen_shipping['shipping_method_id']=0)
                            @endif

                            @if($shippingMethod=='sellerwise_shipping')
                            @php($shipping = \App\Model\CartShipping::where('cart_group_id', $group_key)->first())
                            @if ($shipping['resi_kurir'] == null)
                            @php($shippings=\App\CPU\Helpers::get_shipping_methods($group_key))
                            <div class="row">
                                @php($chosen = $choosen_shipping['shipping_service'])
                                {{-- {{ dd($chosen) }} --}}
                                <div class="col-12">
                                    <select class="form-control"
                                        onchange="set_shipping_id(this.value,'{{$cartItem['cart_group_id']}}')">
                                        <option {{ $chosen == NULL ? 'selected':''}}>{{\App\CPU\translate('Pilih_metode_pengiriman')}}</option>
                                        @if ($shippings[0][0][0]['costs'])
                                        @foreach($shippings[0][0][0]['costs'] as $ship)
                                        {{-- {{ dd($ship) }} --}}
                                        <option value="{{'JNE- '.$ship['service'].','.$ship['cost'][0]['value']}}"
                                            {{$chosen=='JNE- '.$ship['service']?'selected':''}}>
                                            {{"JNE - ".''.$ship['service'].' ( '.$ship['cost'][0]['etd'].' Days)
                                            '.\App\CPU\Helpers::currency_converter(\App\CPU\Convert::idrTousd($ship['cost'][0]['value']))}}
                                        </option>
                                        @endforeach
                                        @endif

                                        @if ($shippings[0][1][0]['costs'])
                                        @foreach($shippings[0][1][0]['costs'] as $ship)
                                        {{-- {{ dd($ship) }} --}}
                                       <option value="{{'TIKI- '.$ship['service'].','.$ship['cost'][0]['value']}}"
                                            {{$chosen=='TIKI- '.$ship['service']?'selected':''}}>
                                            {{"TIKI - ".''.$ship['service'].' ( '.$ship['cost'][0]['etd'].' Days)
                                           '.\App\CPU\Helpers::currency_converter($ship['cost'][0]['value'])}}
                                        </option>
                                        @endforeach
                                        @endif

                                        @if ($shippings[0][2][0]['costs'])
                                        @foreach($shippings[0][2][0]['costs'] as $ship)
                                        {{-- {{ dd($ship) }} --}}
                                        <option value="{{'SiCepat- '.$ship['service'].','.$ship['cost'][0]['value']}}"
                                            {{$chosen=='SiCepat- '.$ship['service']?'selected':''}}>
                                            {{"SiCepat - ".''.$ship['service'].' ( '.$ship['cost'][0]['etd'].' Days)
                                           '.\App\CPU\Helpers::currency_converter(\App\CPU\Convert::idrTousd($ship['cost'][0]['value']))}}
                                        </option>
                                        @endforeach

                                        @endif
                                        {{-- @foreach($shippings[1] as $shipping)
                                        <option value="{{$shipping['id']}}"
                                            {{$chosen==$shipping['id']?'selected':''}}>
                                            {{$shipping['title'].' ( '.$shipping['duration'].' )
                                            '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}
                                        </option>
                                        @endforeach --}}
                                    </select>
                                </div>
                            </div>
                            @endif
                            @endif
                            @endif
                            @endforeach
                            <div class="mt-3"></div>
                            @endforeach

                            @if($shippingMethod=='inhouse_shipping')
                                @php($shippings=\App\CPU\Helpers::get_shipping_methods(1,'admin', $cartItem['product_id']))
                            <div class="row">
                                <div class="col-12">
                                    <select class="form-control" onchange="set_shipping_id(this.value,'all_cart_group')">
                                        @foreach($shippings[1] as $shipping)
                                        <option value="{{$shipping['id']}}"
                                            {{$choosen_shipping['shipping_method_id']==$shipping['id']?'selected':''}}>
                                            {{$shipping['title'].' ( '.$shipping['duration'].' )
                                            '.\App\CPU\Helpers::currency_converter($shipping['cost'])}}
                                        </option>
                                        @endforeach
                                    </select>
                                    </div>
                                </div>
                            @endif

                            @if( $cart->count() == 0)
                                <div class="d-flex justify-content-center align-items-center">
                                    <h4 class="text-danger text-capitalize">{{\App\CPU\translate('cart_empty')}}</h4>
                                </div>
                            @endif
                        </div>
                    {{-- </section> --}}
                    <div class="row">
                        <div class="col-6">
                            <a class="btn btn-secondary btn-block" href="{{route('checkout-details')}}">
                                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} mt-sm-0 mx-1"></i>
                                <span class="d-none d-sm-inline">{{ \App\CPU\translate('alamat_pengiriman')}}</span>
                                <span class="d-inline d-sm-none">{{ \App\CPU\translate('Alamat')}}</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a class="btn btn-primary btn-block" id="buttonOrder" href="{{ route('checkout-complete') }}" onclick="process()">
                                <span class="d-none d-sm-inline">{{ \App\CPU\translate('Proses_order')}}</span>
                                <span class="d-inline d-sm-none">{{ \App\CPU\translate('Proses')}}</span>
                                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}} mt-sm-0 mx-1"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Sidebar-->
                </div>
            </section>
            @include('web-views.partials._order-summary')
        </div>
    </div>
@endsection

@push('script')
<script>
    $(document).ready(function (e) {
        $('#resi_kurir').change(function(){
            $('#preview').removeClass('d-none');
            let reader = new FileReader();
            reader.onload = (e) => {
                $('#preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
            $('#invoice_kurir').removeAttr('disabled');
        });
    });
    cartQuantityInitialize();
    function process(){
        $('#loading').show();
        $('#buttonOrder').addClass('disabled-link');
    }
    function loading(){
        $('#loading').show();
    }

function set_shipping_id(id, cart_group_id) {
    $.get({
        url: '{{url('/')}}/customer/set-shipping-method',
        dataType: 'json',
        data: {
            id: id,
            cart_group_id: cart_group_id
        },
        beforeSend: function () {
            $('#loading').show();
        },
        success: function (data) {
            location.reload();
        },
        complete: function () {
        },
    });
}
</script>
@endpush
