
@extends('layouts.back-end.app-seller')
@section('title', \App\CPU\translate('Shop Edit'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
     <!-- Custom styles for this page -->
     <link href="{{asset('public/assets/back-end/css/croppie.css')}}" rel="stylesheet">
     <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush
@section('content')
    <!-- Content Row -->
    <div class="content container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="h3 mb-0 ">{{\App\CPU\translate('Edit Shop Info')}}</h1>
                </div>
                <div class="card-body">
                    <form action="{{route('seller.shop.update',[$shop->id])}}" method="post"
                          style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{\App\CPU\translate('Shop Name')}} <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="{{$shop->name}}" class="form-control" id="name"
                                            required>
                                </div>
                                <div class="form-group">
                                    <label for="name">{{\App\CPU\translate('Contact')}} <small class="text-danger">( * {{\App\CPU\translate('country_code_is_must')}} {{\App\CPU\translate('like_for_BD_880')}} )</small></label>
                                    <input type="number" name="contact" value="{{$shop->contact}}" class="form-control" id="name"
                                            required>
                                </div>

                                @if ($shop->seller->country == 'ID')
                                <input type="hidden" name="country" value="{{ $shop->seller->country }}">
                                @php($province = App\CPU\Helpers::province())
                                <div class="form-group">
                                    {{-- {{ dd($province) }} --}}
                                    <label for="state">{{\App\CPU\translate('State_/_Province')}}</label>
                                    <select class="form-control" name="state">
                                                                                @if ($shop->province)
                                        <option value="{{ $shop->province }}">{{ $shop->province }}</option>
                                        @else
                                        <option value="">Select your Province Address</option>
                                        @endif                                        @foreach($province as $p)
                                        <option value="{{$p['province_id'].','. $p['province']}}"
                                            provincename="{{$p['province']}}">
                                            {{$p['province']}}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="address-city">{{\App\CPU\translate('City')}}</label>
                                    <input type="hidden" value="{{ $shop->city_id }},{{ $shop->city }}" name="city">
                                    <select disabled class="form-control" name="city" id="address-city"
                                        placeholder="Select your city address"
                                        style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left'
                                        }};">
                                                                                @if ($shop->city)
                                        @php($city = $shop->city_id.','.$shop->city )
                                        <option value="{{ $city }}">{{ $shop->city }}</option>
                                        @endif
                                        <option value="">Select your city address</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="address-district">{{\App\CPU\translate('District')}}</label>
                                    <input type="hidden" name="district" value="{{ $shop->district_id }},{{ $shop->district }}">
                                    <select disabled class="form-control" name="district" id="address-district"
                                        placeholder="Select your city address"
                                        style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left'
                                        }};">
                                        @if ($shop->district)
                                        <option value="{{ $shop->district_id }},{{ $shop->district }}">{{ $shop->district }}</option>
                                        @endif
                                        <option value="">Select your District address</option>
                                    </select>
                                </div>
                                @else
                                <div class="form-group">
                                    {{-- {{ dd($province) }} --}}
                                    <label for="state">{{\App\CPU\translate('State_/_Province')}}</label>
                                    <input type="text" name="state" id="state" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="address-city">{{\App\CPU\translate('City')}}</label>
                                    <input type="text" class="form-control" name="city" id="address-city">
                                </div>
                                @endif

                                <div class="form-group">
                                    <label for="address">{{\App\CPU\translate('Address')}} <span class="text-danger">*</span></label>
                                    <textarea type="text" rows="4" name="address" value="" class="form-control" id="address"
                                            required>{{$shop->address}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('image')}}</label>
                                    <div class="custom-file text-left">
                                        <input type="file" name="image" id="customFileUpload" class="custom-file-input"
                                            accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="width: auto;border: 1px solid; border-radius: 10px; max-height:200px;" id="viewer"
                                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                    src="{{asset('storage/shop/'.$shop->image)}}" alt="Product thumbnail"/>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4 mt-2">
                                <div class="form-group">
                                    <div class="flex-start">
                                        <div for="name">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('Banner')}} </div>
                                        <div class="mx-1" for="ratio"><small style="color: red">{{\App\CPU\translate('Ratio')}} : ( 6:1 )</small></div>
                                    </div>
                                    <div class="custom-file text-left">
                                        <input type="file" name="banner" id="BannerUpload" class="custom-file-input"
                                               accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                        <label class="custom-file-label" for="BannerUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <img style="width: auto; height:auto; border: 1px solid; border-radius: 10px; max-height:200px" id="viewerBanner"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('storage/shop/banner/'.$shop->banner)}}" alt="Product thumbnail"/>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary" id="btn_update">{{\App\CPU\translate('Update')}}</button>
                        <a class="btn btn-danger" href="{{route('seller.shop.view')}}">{{\App\CPU\translate('Cancel')}}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@push('script')

   <script>
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

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readBannerURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewerBanner').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileUpload").change(function () {
            readURL(this);
        });

        $("#BannerUpload").change(function () {
            readBannerURL(this);
        });
   </script>

@endpush
