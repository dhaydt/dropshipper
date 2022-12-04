@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Shopping Cart'))

@push('css_or_js')
    <meta property="og:image" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="og:title" content="{{$web_config['name']->value}} "/>
    <meta property="og:url" content="{{env('APP_URL')}}">
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    <meta property="twitter:card" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}"/>
    <meta property="twitter:title" content="{{$web_config['name']->value}}"/>
    <meta property="twitter:url" content="{{env('APP_URL')}}">
    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">
    <link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/shop-cart.css"/>
     <style>
        .headerTitle {
            font-size: 24px;
            font-weight: 600;
            margin-top: 1rem;
        }

        body {
            font-family: 'Titillium Web', sans-serif
        }

         #navbarCollapse>ul.navbar-nav.w-100>li:nth-child(6) {
        display: none;
    }

        .product-qty span {
            font-size: 14px;
            color: #6A6A6A;
        }

        .font-nameA {

            display: inline-block;
            margin-top: 5px !important;
            font-size: 13px !important;
            color: #030303;
        }

        .font-name {
            font-weight: 600;
            font-size: 15px;
            padding-bottom: 6px;
            color: #030303;
        }

        .modal-footer {
            border-top: none;
        }

        .cz-sidebar-body h3:hover + .divider-role {
            border-bottom: 3px solid {{$web_config['primary_color']}} !important;
            transition: .2s ease-in-out;
        }

        label {
            font-size: 15px;
            margin-bottom: 8px;
            color: #030303;

        }

        .nav-pills .nav-link.active {
            box-shadow: none;
            color: #ffffff !important;
        }

        .modal-header {
            border-bottom: none;
        }

        .nav-pills .nav-link {
            padding-top: .575rem;
            padding-bottom: .575rem;
            background-color: #ffffff;
            color: #050b16 !important;
            font-size: .9375rem;
            border: 1px solid #e4dfdf;
        }

        .nav-pills .nav-link :hover {
            padding-top: .575rem;
            padding-bottom: .575rem;
            background-color: #ffffff;
            color: #050b16 !important;
            font-size: .9375rem;
            border: 1px solid #e4dfdf;
        }

        .nav-pills .nav-link.active, .nav-pills .show > .nav-link {
            color: #fff;
            background-color: {{$web_config['primary_color']}};
        }

        .iconHad {
            color: {{$web_config['primary_color']}};
            padding: 4px;
        }

        .iconSp {
            margin-top: 0.70rem;
        }

        .fa-lg {
            padding: 4px;
        }

        .fa-trash {
            color: #FF4D4D;
        }

        .namHad {
            color: #030303;
            position: absolute;
            padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 13px;
            padding-top: 8px;
        }

        .donate-now {
            list-style-type: none;
            margin: 25px 0 0 0;
            padding: 0;
        }

        .donate-now li {
            float: left;
            margin: {{Session::get('direction') === "rtl" ? '0 0 0 5px' : '0 5px 0 0'}};
            width: 100px;
            height: 40px;
            position: relative;
            padding: 22px;
            text-align: center;
        }

        .donate-now label,
        .donate-now input {
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .donate-now input[type="radio"] {
            opacity: 0.01;
            z-index: 100;
        }

        .donate-now input[type="radio"]:checked + label,
        .Checked + label {
            background: {{$web_config['primary_color']}};
            color: white !important;
            border-radius: 7px;
        }

        .donate-now label {
            padding: 5px;
            border: 1px solid #CCC;
            cursor: pointer;
            z-index: 90;
        }

        .donate-now label:hover {
            background: #DDD;
        }

        #edit{
            cursor: pointer;
        }

        @media (max-width: 600px) {
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
    <div class="container pb-5 mb-2 mt-3 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" id="cart-summary">
       <div class="feature_header">
        <span>{{ \App\CPU\translate('fill_address')}}</span>

        <div class="container">
            <div class="modal-dialog  modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="modal-title font-name ">{{\App\CPU\translate('add_new_address')}}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <form action="{{route('address-store-first')}}" method="post">
                            @csrf

                            <div class="col-md-12" style="display: flex">
                                <!-- Nav pills -->

                                <ul class="donate-now">
                                    @if (session()->get('user_is') !== 'dropship')
                                    <li>
                                        <input type="radio" id="a25" name="addressAs" value="permanent" />
                                        <label for="a25" class="component">{{\App\CPU\translate('permanent')}}</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="a50" name="addressAs" value="home" />
                                        <label for="a50" class="component">{{\App\CPU\translate('Home')}}</label>
                                    </li>
                                    <li>
                                        <input type="radio" id="a75" name="addressAs" value="office"
                                            checked="checked" />
                                        <label for="a75" class="component">{{\App\CPU\translate('Office')}}</label>
                                    </li>
                                    @else
                                    <li>
                                        <input type="radio" id="a75" name="addressAs" value="dropship"
                                            checked="checked" />
                                        <label for="a90" class="component">{{\App\CPU\translate('Dropship')}}</label>
                                    </li>
                                    @endif


                                </ul>
                            </div>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="home" class="container tab-pane active"><br>


                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label for="name">{{\App\CPU\translate('Nama_penerima')}}</label>
                                            <input class="form-control" type="text" value="{{old('name')}}" id="name" name="name" required>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="zip">{{\App\CPU\translate('Kode_pos')}}</label>
                                            <input class="form-control" type="number" id="zip" name="zip" value="{{ old('zip') }}" required>
                                        </div>
                                    </div>
                                    <div class="form-row">

                                        <div class="form-group col-md-6">
                                            <label for="firstName">{{\App\CPU\translate('Handphone')}}</label>
                                            <input class="form-control" type="text" id="phone" name="phone">
                                        </div>
                                        @php($province = App\CPU\Helpers::province())
                                        <div class="form-group col-md-6">
                                            {{-- {{ dd($province) }} --}}
                                            <input type="hidden" name="country" value="ID">
                                            <label for="state">{{\App\CPU\translate('Provinsi')}}</label>
                                            <select class="form-control" name="state">
                                                <option value="">Pilih provinsi</option>
                                                @foreach($province as $p)
                                                <option value="{{$p['province_id'].','. $p['province']}}"
                                                    provincename="{{$p['province']}}">
                                                    {{$p['province']}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <div class="form-row prov-inter">

                                        <div class="form-group col-md-6">
                                            <label for="address-city">{{\App\CPU\translate('Kota')}}</label>
                                            <input class="form-control" type="text" id="address-city" name="city"
                                                >
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="district">{{\App\CPU\translate('Kecamatan')}}</label>
                                            <input class="form-control" type="text" id="district" name="district"
                                                >
                                        </div>
                                    </div> --}}

                                    <div class="form-row prov-indo">

                                        <div class="form-group col-md-6">
                                            <label for="address-city">{{\App\CPU\translate('Kota')}}</label>
                                            <select disabled class="form-control" name="city" id="address-city" placeholder="Select your city address"
                                        style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left'
                                        }};">
                                        <option value="">Pilih kota</option>
                                    </select>
                                        </div>
                                            <div class="form-group col-md-6">
                                            <label for="district">{{\App\CPU\translate('Kecamatan')}}</label>
                                            <select disabled class="form-control" name="district" id="address-district" placeholder="Select your District address"
                                                style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left'
                                                }};">
                                                    <option value="">Pilih Kecamatan</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="address">Nama jalan & Nomor rumah</label>
                                            <textarea class="form-control" value="{{old('address')}}" id="address" name="address"
                                                required></textarea>
                                        </div>
                                        {{-- <div class="form-group col-md-6">
                                            <label for="firstName">{{\App\CPU\translate('Phone')}}</label>
                                            <input class="form-control" type="number" id="phone" name="phone" value="{{ old('phone') }}">
                                        </div> --}}
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    {{-- <button type="button" class="btn btn-secondary"
                                        data-dismiss="modal">{{\App\CPU\translate('close')}}</button> --}}
                                    <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Continue')}}
                                        {{\App\CPU\translate('Checkout')}} </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function(){
        //ini ketika provinsi tujuan di klik maka akan eksekusi perintah yg kita mau
        //name select nama nya "provinve_id" kalian bisa sesuaikan dengan form select kalian
        getProv();
        function getProv(){
            let country = 'ID';
            if(country == 'ID'){
                $('.prov-inter').addClass('d-none');
                $('.prov-indo').removeClass('d-none');
                // $('select=[name="state"]').removeAttr('disabled')
                $('#country_hidden').val(country);
                $('select[name="country"]').attr('disabled', true)
            }else {
                $('.prov-indo').remove();
                $('#country_hidden').val(country);
                $('select[name="country"]').attr('disabled', true)
                console.log('no indo')
            }
        }

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
@endpush
