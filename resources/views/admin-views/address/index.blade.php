@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Shop Address'))

@push('css_or_js')

@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a>
            </li>
            <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Address')}}</li>
        </ol>
    </nav>
    <!-- End Page Header -->
    <div class="row gx-2 gx-lg-3">
        <div class="col-sm-12 col-lg-12 mb-3 mb-lg-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="page-header-title">{{\App\CPU\translate('Address')}} </h1>
                </div>
                @php($address = json_decode($address['value']))
                <div class="card-body">
                    <form action="{{route('admin.business-settings.address.update')}}" method="post"
                        style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            @php($province = App\CPU\Helpers::province())
                            <div class="form-group col-md-6">
                                {{-- {{ dd($province) }} --}}
                                <input type="hidden" name="country" value="ID">
                                <label for="state">{{\App\CPU\translate('Provinsi')}}</label>
                                <select class="form-control" name="state">
                                    <option value="">Pilih provinsi</option>
                                    @foreach($province as $p)
                                    <option value="{{$p['province_id'].','. $p['province']}}" {{
                                        $p['province_id']==$address->province_id ? 'selected' : '' }}
                                        provincename="{{$p['province']}}">
                                        {{$p['province']}}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="address-city">{{\App\CPU\translate('Kota')}}</label>
                                <select disabled class="form-control" name="city" id="address-city"
                                    placeholder="Select your city address"
                                    style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};">
                                    @if ($address->city_id !== "")
                                        <option value="{{ $address->city_id }}, {{ $address->city }}">{{ $address->city }}</option>
                                    @else
                                        <option value="">Pilih kota</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="district">{{\App\CPU\translate('Kecamatan')}}</label>
                                <select disabled class="form-control" name="district" id="address-district"
                                    placeholder="Select your District address"
                                    style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};">
                                    @if ($address->district_id !== "")
                                    <option value="{{ $address->district_id }}, {{ $address->district }}">{{ $address->district }}</option>
                                    @else
                                    <option value="">Pilih Kecamatan</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="address">Nama jalan & Nomor rumah</label>
                                <textarea class="form-control" id="address" name="address"
                                    required>
                                    {{ $address->address }}
                                </textarea>
                            </div>
                            {{-- <div class="form-group col-md-6">
                                <label for="firstName">{{\App\CPU\translate('Phone')}}</label>
                                <input class="form-control" type="number" id="phone" name="phone"
                                    value="{{ old('phone') }}">
                            </div> --}}
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Simpan Alamat')}} </button>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Table -->
    </div>
</div>

@endsection

@push('script_2')
<script>
    $(document).ready(function(){
        //ini ketika provinsi tujuan di klik maka akan eksekusi perintah yg kita mau
        //name select nama nya "provinve_id" kalian bisa sesuaikan dengan form select kalian
        var prov = $('select[name="state"]').val();
        var cit = $('select[name="city"]').val();
        if(prov !== ''){
            getCity(prov);

            if(cit !== ''){
                getDistrict(cit);
            }
        }
        console.log('prov', prov);


        $('select[name="state"]').on('change', function(){
            $('select[name="city"]').empty();
            getCity($(this).val())
        });

        $('select[name="city"]').on('change', function(){
            $('select[name="district"]').empty();
            getDistrict($(this).val())
        });
    });
    function getDistrict(val){
        $('#loading').show();
            // kita buat variable provincedid untk menampung data id select province
            console.log(val)
            let cities = val;
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
                        // $('select[name="district"]').empty();
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
    }

    function getCity(val){
        $('#loading').show();
            // kita buat variable provincedid untk menampung data id select province
            console.log(val)
            let prov = val;
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
                        // $('select[name="city"]').empty();
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
    }

</script>

<script>
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
</script>
@endpush
