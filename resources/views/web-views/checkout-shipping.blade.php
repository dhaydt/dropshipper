@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Shipping Address Choose'))

@push('css_or_js')
    <style>
        .btn-outline {
            border-color: {{$web_config['primary_color']}} ;
        }

        .btn-outline {
            color: #020512;
            border-color: {{$web_config['primary_color']}}   !important;
        }

        .btn-outline:hover {
            color: white;
            background: {{$web_config['primary_color']}};

        }

        .btn-outline:focus {
            /* border-color: {{$web_config['primary_color']}}   !important; */
        }
    </style>
@endpush

@section('content')
    <div class="container pb-5 mb-2 mb-md-4 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <div class="col-md-12 mb-5 pt-5">
                <div class="feature_header" style="background: #dcdcdc;line-height: 1px">
                    <span>{{ \App\CPU\translate('shipping_address')}}</span>
                </div>
            </div>
            <section class="col-lg-8">
                <hr>
                <div class="checkout_details mt-3">
                    <!-- Steps-->
                @include('web-views.partials._checkout-steps',['step'=>2])
                <!-- Shipping methods table-->
                    <h2 class="h4 pb-3 mb-2 mt-5">{{ \App\CPU\translate('shipping_address')}} {{ \App\CPU\translate('choose_shipping_address')}}</h2>
                    @php($shipping_addresses=\App\Model\ShippingAddress::where('customer_id',auth('customer')->id())->get())
                    <form method="post" action="" id="address-form">
                        @csrf
                        <div class="card-body" style="padding: 0!important;">
                            <ul class="list-group">
                                @foreach($shipping_addresses as $key=>$address)
                                    <li class="list-group-item mb-2 mt-2"
                                        style="cursor: pointer;background: rgba(245,245,245,0.51)"
                                        onclick="$('#sh-{{$address['id']}}').prop( 'checked', true )">
                                        <input type="radio" name="shipping_method_id"
                                               id="sh-{{$address['id']}}"
                                               value="{{$address['id']}}" {{$key==0?'checked':''}}>
                                        <span class="checkmark" style="margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 10px"></span>
                                        <label class="badge"
                                               style="background: {{$web_config['primary_color']}}; color:white !important;">{{$address['address_type']}}</label>
                                        <small>
                                            <i class="fa fa-phone"></i> {{$address['phone']}}
                                        </small>
                                        <hr>
                                        <span>{{ \App\CPU\translate('contact_person_name')}}: {{$address['contact_person_name']}}</span><br>
                                        <span>{{ \App\CPU\translate('address')}} : {{$address['address']}}, {{$address['city']}}, {{$address['zip']}}, {{$address['country']}}.</span>
                                    </li>
                                @endforeach
                                {{-- {{ dd(session()) }} --}}
                                <li class="list-group-item mb-2 mt-2" onclick="anotherAddress()">
                                    <input type="radio" name="shipping_method_id"
                                           id="sh-0" value="0" data-toggle="collapse"
                                           data-target="#collapseThree" {{$shipping_addresses->count()==0?'checked':''}}>
                                    <span class="checkmark" style="margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 10px"></span>
                                    <label class="badge"
                                           style="background: {{$web_config['primary_color']}}; color:white !important;">
                                        <i class="fa fa-plus-circle"></i></label>
                                    <button type="button" class="btn btn-outline" data-toggle="collapse"
                                            data-target="#collapseThree">{{ \App\CPU\translate('Another')}} {{ \App\CPU\translate('address')}}
                                    </button>
                                    <div id="accordion">
                                        <div id="collapseThree"
                                             class="collapse {{$shipping_addresses->count()==0?'show':''}}"
                                             aria-labelledby="headingThree"
                                             data-parent="#accordion">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label
                                                        for="exampleInputEmail1">{{ \App\CPU\translate('contact_person_name')}}
                                                        <span style="color: red">*</span></label>
                                                    <input type="text" class="form-control"
                                                           name="contact_person_name" {{$shipping_addresses->count()==0?'required':''}}>
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">{{ \App\CPU\translate('Phone')}}<span
                                                            style="color: red">*</span></label>
                                                    <input type="text" class="form-control"
                                                           name="phone" {{$shipping_addresses->count()==0?'required':''}}>
                                                </div>
                                                <div class="form-group">
                                                    <label
                                                        for="exampleInputPassword1">{{ \App\CPU\translate('address')}} {{ \App\CPU\translate('Type')}}</label>
                                                    <select class="form-control" name="address_type">
                                                        <option
                                                            value="permanent">{{ \App\CPU\translate('Permanent')}}</option>
                                                        <option value="home">{{ \App\CPU\translate('Home')}}</option>
                                                        <option value="others">{{ \App\CPU\translate('Others')}}</option>
                                                    </select>
                                                </div>
                                                {{-- {{ dd() }} --}}
                                                @if (auth('customer')->user()->country == 'ID')

                                                <div class="form-group">
                                                    <label>{{ \App\CPU\translate('Country')}} <span
                                                            style="color: red">*</span></label>
                                                            <input type="hidden" name="country" value="ID">
                                                    <input type="text" class="form-control"
                                                           name="country" {{$shipping_addresses->count()==0?'required':''}} value="Indonesia" disabled>
                                                </div>

                                                <div class="form-group">
                                                    @php($province = App\CPU\Helpers::province())
                                                    <label for="province">{{ \App\CPU\translate('Province')}} <span
                                                            style="color: red">*</span></label>
                                                            <select class="form-control" name="state" {{$shipping_addresses->count()==0?'required':''}}>
                                                                <option value="">Select your Province Address</option>
                                                                @foreach($province as $p)
                                                                <option value="{{$p['province_id'].','. $p['province']}}"
                                                                    provincename="{{$p['province']}}">
                                                                    {{$p['province']}}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="city">{{ \App\CPU\translate('City')}} <span
                                                            style="color: red">*</span></label>
                                                            <select disabled class="form-control" name="city" id="city" placeholder="Select your city address" {{$shipping_addresses->count()==0?'required':''}}
                                                            style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left'
                                                            }};">
                                                            <option value="">Select your city address</option>
                                                        </select>
                                                </div>

                                                <div class="form-group">
                                                    <label for="district">{{ \App\CPU\translate('District')}} <span
                                                            style="color: red">*</span></label>
                                                            <select disabled class="form-control" name="district" id="address-district" placeholder="Select your District address" {{$shipping_addresses->count()==0?'required':''}}
                                                            style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left'
                                                            }};">
                                                                <option value="">Select your district address</option>
                                                        </select>
                                                </div>
                                                @else
                                                @php($country = App\Country::all())
                                                <div class="form-group">
                                                    <label>{{ \App\CPU\translate('Country')}} <span
                                                            style="color: red">*</span></label>
                                                            <select id="country" name="country" class="form-control" {{$shipping_addresses->count()==0?'required':''}}>
                                                                <option value="0" selected>---select country---</option>
                                                                @foreach($country as $r)
                                                                <option value="{{$r->country}}">{{$r->country_name}}</option>
                                                                @endforeach
                                                            </select>
                                                </div>
                                                <div class="form-group">
                                                    <label for="city">{{ \App\CPU\translate('City')}} <span
                                                            style="color: red">*</span></label>
                                                    <input id="city" type="text" class="form-control"
                                                           name="city" {{$shipping_addresses->count()==0?'required':''}}>
                                                </div>
                                                @endif

                                                <div class="form-group">
                                                    <label
                                                        for="exampleInputEmail1">{{ \App\CPU\translate('zip_code')}} <span
                                                            style="color: red">*</span></label>
                                                    <input type="number" class="form-control"
                                                           name="zip" {{$shipping_addresses->count()==0?'required':''}}>
                                                </div>

                                                <div class="form-group">
                                                    <label
                                                        for="exampleInputEmail1">{{ \App\CPU\translate('address')}} <span
                                                            style="color: red">*</span></label>
                                                    <textarea class="form-control"
                                                              name="address" {{$shipping_addresses->count()==0?'required':''}}></textarea>
                                                </div>
                                                <div class="form-check" style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 1.25rem;">
                                                    <input type="checkbox" name="save_address" class="form-check-input"
                                                           id="exampleCheck1">
                                                    <label class="form-check-label" for="exampleCheck1" style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 1.09rem">
                                                        {{ \App\CPU\translate('save_this_address')}}
                                                    </label>
                                                </div>
                                                <button type="submit" class="btn btn-primary" style="display: none"
                                                        id="address_submit"></button>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </form>
                    <!-- Navigation (desktop)-->
                    <div class="row">
                        <div class="col-6">
                            <a class="btn btn-secondary btn-block" href="{{route('shop-cart')}}">
                                <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} mt-sm-0 mx-1"></i>
                                <span class="d-none d-sm-inline">{{ \App\CPU\translate('shop_cart')}}</span>
                                <span class="d-inline d-sm-none">{{ \App\CPU\translate('shop_cart')}}</span>
                            </a>
                        </div>
                        <div class="col-6">
                            <a class="btn btn-primary btn-block" href="javascript:" onclick="proceed_to_next()">
                                <span class="d-none d-sm-inline">{{ \App\CPU\translate('proceed_payment')}}</span>
                                <span class="d-inline d-sm-none">{{ \App\CPU\translate('Next')}}</span>
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
    $(document).ready(function(){
        //ini ketika provinsi tujuan di klik maka akan eksekusi perintah yg kita mau
        //name select nama nya "provinve_id" kalian bisa sesuaikan dengan form select kalian

        $('select[name="state"]').on('change', function(){
            $('#loading').show();
            // kita buat variable provincedid untk menampung data id select province
            console.log($(this).val())
            let prov = $(this).val();
            var array = prov.split(",");
            let provin = $.each(array,function(i){
                console.log(prov);
            // console.log(array[0]);
            return array[0]
            });
            let provinceid = provin[0]
            //kita cek jika id di dpatkan maka apa yg akan kita eksekusi
            if(provinceid){
                // jika di temukan id nya kita buat eksekusi ajax GET
                jQuery.ajax({
                    // url yg di root yang kita buat tadi
                    url:"/city/"+provinceid,
                    // aksion GET, karena kita mau mengambil data
                    type:'GET',
                    // type data json
                    dataType:'json',
                    // jika data berhasil di dapat maka kita mau apain nih
                    success:function(data){
                        console.log(provinceid);
                        // jika tidak ada select dr provinsi maka select kota kososng / empty
                        $('select[name="city"]').empty();
                        // // jika ada kita looping dengan each
                        $.each(data, function(key, value){
                            // console.log(key, value)
                            kota = value.city_name
                            type = value.type
                            id = value.city_id
                            type = value.type
                        // // perhtikan dimana kita akan menampilkan data select nya, di sini saya memberi name select kota adalah kota_id
                        $('select[name="city"]').append(`<option value="${id},${kota},${type}">
                            ${kota} (${type})
                        </option>`);

                        $('select[name="city"]').removeAttr('disabled');
                        $('#loading').hide();
                        });
                    }
                });
            }
        });

        $('select[name="city"]').on('change', function(){
            $('#loading').show();
            // kita buat variable provincedid untk menampung data id select province
            console.log($(this).val())
            let cities = $(this).val();
            var array = cities.split(",");
            let city = $.each(array,function(i){
            // console.log(array[0]);
            return array[0]
            });
            let cityId = city[0]
            //kita cek jika id di dpatkan maka apa yg akan kita eksekusi
            if(cityId){
                // jika di temukan id nya kita buat eksekusi ajax GET
                jQuery.ajax({
                    // url yg di root yang kita buat tadi
                    url:"/district/"+cityId,
                    // aksion GET, karena kita mau mengambil data
                    type:'GET',
                    // type data json
                    dataType:'json',
                    // jika data berhasil di dapat maka kita mau apain nih
                    success:function(data){
                        console.log(cityId);
                        // jika tidak ada select dr provinsi maka select kota kososng / empty
                        $('select[name="district"]').empty();
                        // // jika ada kita looping dengan each
                        $.each(data, function(key, value){
                            // console.log(key, value)
                            district = value.subdistrict_name
                            id = value.subdistrict_id
                        // // perhtikan dimana kita akan menampilkan data select nya, di sini saya memberi name select kota adalah kota_id
                        $('select[name="district"]').append(`<option value="${id},${district}">
                            ${district}
                        </option>`);

                        $('select[name="district"]').removeAttr('disabled');
                        $('#loading').hide();
                        });
                    }
                });
            }
        });
    });

</script>
    <script>
        function anotherAddress() {
            $('#sh-0').prop('checked', true);
            $("#collapseThree").collapse();
        }
    </script>

    <script>
        function proceed_to_next() {

            let allAreFilled = true;
            document.getElementById("address-form").querySelectorAll("[required]").forEach(function (i) {
                if (!allAreFilled) return;
                if (!i.value) allAreFilled = false;
                if (i.type === "radio") {
                    let radioValueCheck = false;
                    document.getElementById("address-form").querySelectorAll(`[name=${i.name}]`).forEach(function (r) {
                        if (r.checked) radioValueCheck = true;
                    });
                    allAreFilled = radioValueCheck;
                }
            });

            // console.log($('#address-form').serialize());


            if (allAreFilled) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.post({
                    url: '{{route('customer.choose-shipping-address')}}',
                    dataType: 'json',
                    data: $('#address-form').serialize(),
                    beforeSend: function () {
                        $('#loading').show();
                    },
                    success: function (data) {
                        if (data.errors) {
                            for (var i = 0; i < data.errors.length; i++) {
                                toastr.error(data.errors[i].message, {
                                    CloseButton: true,
                                    ProgressBar: true
                                });
                            }
                        } else {
                            location.href = '{{route('checkout-payment')}}';
                        }
                    },
                    complete: function () {
                        $('#loading').hide();
                    },
                    error: function () {
                        toastr.error('{{\App\CPU\translate('Something went wrong!')}}', {
                            CloseButton: true,
                            ProgressBar: true
                        });
                    }
                });
            } else {
                toastr.error('{{\App\CPU\translate('Please fill all required fields')}}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        }
    </script>
@endpush
