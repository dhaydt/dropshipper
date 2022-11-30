@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('My Address'))

@push('css_or_js')
    <link rel="stylesheet" media="screen"
          href="{{asset('public/assets/front-end')}}/vendor/nouislider/distribute/nouislider.min.css"/>

    <style>
        .headerTitle {
            font-size: 24px;
            font-weight: 600;
            margin-top: 1rem;
        }

        body {
            font-family: 'Titillium Web', sans-serif
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

    <div class="modal fade rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="row">
                        <div class="col-md-12"><h5 class="modal-title font-name ">{{\App\CPU\translate('add_new_address')}}</h5></div>
                    </div>
                </div>
                <div class="modal-body">
                    <form action="{{route('address-store')}}" method="post">
                        @csrf

                        <div class="col-md-12" style="display: flex">
                            <!-- Nav pills -->

                            <ul class="donate-now">
                                <li>
                                    <input type="radio" id="a25" name="addressAs" value="permanent"/>
                                    <label for="a25" class="component">{{\App\CPU\translate('permanent')}}</label>
                                </li>
                                <li>
                                    <input type="radio" id="a50" name="addressAs" value="home"/>
                                    <label for="a50" class="component">{{\App\CPU\translate('Home')}}</label>
                                </li>
                                <li>
                                    <input type="radio" id="a75" name="addressAs" value="office" checked="checked"/>
                                    <label for="a75" class="component">{{\App\CPU\translate('Office')}}</label>
                                </li>

                            </ul>
                        </div>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div id="home" class="container tab-pane active"><br>


                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name">{{\App\CPU\translate('contact_person_name')}}</label>
                                        <input class="form-control" type="text" id="name" name="name" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="address">{{\App\CPU\translate('address')}}</label>
                                        <input class="form-control" type="text" id="address" name="address" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="address-city">{{\App\CPU\translate('City')}}</label>
                                        <input class="form-control" type="text" id="address-city" name="city" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="zip">{{\App\CPU\translate('zip_code')}}</label>
                                        <input class="form-control" type="number" id="zip" name="zip" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="state">{{\App\CPU\translate('State')}}</label>
                                        <input type="text" class="form-control" id="state" name="state" placeholder="" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="country">{{\App\CPU\translate('Country')}}</label>
                                        <input type="text" class="form-control" id="country" name="country"
                                               placeholder="" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="firstName">{{\App\CPU\translate('Phone')}}</label>
                                        <input class="form-control" type="text" id="phone" name="phone" required>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('close')}}</button>
                                <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Add')}} {{\App\CPU\translate('Informations')}}  </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4 mt-3 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row">
            <!-- Sidebar-->
        @include('web-views.partials._profile-aside')
        <!-- Content  -->
            <section class="col-lg-9 mt-3 col-md-9">

                <!-- Addresses list-->
                <div class="row">
                    <div class="col-lg-12 col-md-12  d-flex justify-content-between overflow-hidden">
                        <div class="col-sm-4">
                            <h1 class="h3  mb-0 folot-left headerTitle">{{\App\CPU\translate('ADDRESSES')}}</h1>
                        </div>
                        <div class="mt-2 col-sm-4">
                            <button type="submit" class="btn btn-primary float-right" data-toggle="modal"
                                data-target="#exampleModal">{{\App\CPU\translate('add_new_address')}}
                            </button>
                        </div>
                    </div>
                    @foreach($shippingAddresses as $shippingAddress)
                        <section class="col-lg-6 col-md-6 mb-4 mt-5">
                            <div class="card" style="text-transform: capitalize;">
                                {{-- <div class="card cardColor"> --}}
                                    <div class="card-header" style="padding: 5px;">
                                        <i class="fa fa-thumb-tack fa-2x iconHad" aria-hidden="true"></i>
                                        <span class="namHad"> {{$shippingAddress['address_type']}} {{\App\CPU\translate('address')}} </span>
                                        {{-- <div> --}}
                                        <span class="float-right iconSp">
                                            <a class="" id="edit" data-toggle="modal" data-target="#editAddress_{{$shippingAddress->id}}">
                                                <i class="fa fa-edit fa-lg"></i>
                                            </a>

                                            <a class="" href="{{ route('address-delete',['id'=>$shippingAddress->id])}}" onclick="return confirm('{{\App\CPU\translate('Are you sure you want to Delete')}}?');" id="delete">
                                                <i class="fa fa-trash fa-lg"></i>
                                            </a>
                                        </span>
                                    </div>
                                        {{-- </div> --}}

                                    {{-- Modal Address Edit --}}
                                    <div class="modal fade" id="editAddress_{{$shippingAddress->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog  modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                <div class="row">
                                                    <div class="col-md-12"> <h5 class="modal-title font-name ">{{\App\CPU\translate('update')}} {{\App\CPU\translate('address')}}  </h5></div>
                                                </div>
                                                </div>
                                                <div class="modal-body">
                                                    <form id="updateForm">
                                                        @csrf
                                                        <div class="pb-3" style="display: flex">
                                                            <!-- Nav pills -->
                                                            <input type="hidden" id="defaultValue" class="add_type" value="{{$shippingAddress->address_type}}">
                                                            <ul class="donate-now">
                                                                <li class="address_type_li">
                                                                    <input type="radio" class="address_type" id="a25" name="addressAs" value="permanent"  {{ $shippingAddress->address_type == 'permanent' ? 'checked' : ''}} />
                                                                    <label for="a25" class="component">{{\App\CPU\translate('permanent')}}</label>
                                                                </li>
                                                                <li class="address_type_li">
                                                                    <input type="radio" class="address_type" id="a50" name="addressAs" value="home" {{ $shippingAddress->address_type == 'home' ? 'checked' : ''}} />
                                                                    <label for="a50" class="component">{{\App\CPU\translate('Home')}}</label>
                                                                </li>
                                                                <li class="address_type_li">
                                                                    <input type="radio" class="address_type" id="a75" name="addressAs" value="office" {{ $shippingAddress->address_type == 'office' ? 'checked' : ''}}/>
                                                                    <label for="a75" class="component">{{\App\CPU\translate('Office')}}</label>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <!-- Tab panes -->
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="person_name">{{\App\CPU\translate('contact_person_name')}}</label>
                                                                <input class="form-control" type="text" id="person_name"
                                                                    name="name"
                                                                    value="{{$shippingAddress->contact_person_name}}"
                                                                    required>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="own_address">{{\App\CPU\translate('address')}}</label>
                                                                <input class="form-control" type="text" id="own_address"
                                                                    name="address"
                                                                    value="{{$shippingAddress->address}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label for="city">{{\App\CPU\translate('City')}}</label>

                                                                <input class="form-control" type="text" id="city" name="city" value="{{$shippingAddress->city}}" required>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="zip_code">{{\App\CPU\translate('zip_code')}}</label>
                                                                <input class="form-control" type="number" id="zip_code" name="zip" value="{{$shippingAddress->zip}}" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                            <label for="own_state">{{\App\CPU\translate('State')}}</label>
                                                                <input type="text" class="form-control" name="state" value="{{ $shippingAddress->state }}" id="own_state"  placeholder="" required>
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                            <label for="own_country">{{\App\CPU\translate('Country')}}</label>
                                                                <input type="text" class="form-control" id="own_country" name="country" value="{{ $shippingAddress->country }}" placeholder="" required>
                                                            </div>
                                                        </div>
                                                        <div class="form-row">
                                                            <div class="form-group col-md-12">
                                                            <label for="own_phone">{{\App\CPU\translate('Phone')}}</label>
                                                                <input class="form-control" type="text" id="own_phone" name="phone" value="{{$shippingAddress->phone}}" required="required">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="closeB btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('close')}}</button>
                                                            <button type="submit" class="btn btn-primary" id="addressUpdate" data-id="{{$shippingAddress->id}}">{{\App\CPU\translate('update')}}  </button>
                                                        </div>
                                                    </form>
                                            </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card-body" style="padding: {{Session::get('direction') === "rtl" ? '0 13px 15px 15px' : '0 15px 15px 13px'}};">
                                        <div class="font-name"><span>{{$shippingAddress['contact_person_name']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('Phone')}}  :</strong>  {{$shippingAddress['phone']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('Province')}}  :</strong>  {{$shippingAddress['province']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('City')}}  :</strong>  {{$shippingAddress['city']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('District')}}  :</strong>  {{$shippingAddress['district']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong> {{\App\CPU\translate('zip_code')}} :</strong> {{$shippingAddress['zip']}}</span>
                                        </div>
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('address')}} :</strong> {{$shippingAddress['address']}}</span>
                                        </div>
                                        @php($c_name = App\Country::where('country', $shippingAddress['country'])->first())
                                        <div><span class="font-nameA"> <strong>{{\App\CPU\translate('Country')}} :</strong> {{$c_name->country_name}}</span>
                                        </div>

                    
                                    </div>
                                {{-- </div> --}}
                            </div>
                        </section>
                    @endforeach
                </div>

            </section>

        </div>
    </div>

@endsection

@push('script')
    <script>
        $(document).ready(function (){
            $('.address_type_li').on('click', function (e) {
                // e.preventDefault();
                $('.address_type_li').find('.address_type').removeAttr('checked', false);
                $('.address_type_li').find('.component').removeClass('active_address_type');
                $(this).find('.address_type').attr('checked', true);
                $(this).find('.address_type').removeClass('add_type');
                $('#defaultValue').removeClass('add_type');
                $(this).find('.address_type').addClass('add_type');

                $(this).find('.component').addClass('active_address_type');
            });
        })

        $('#addressUpdate').on('click', function(e){
            e.preventDefault();
            let addressAs, address, name, zip, city, state, country, phone;

            addressAs = $('.add_type').val();

            address = $('#own_address').val();
            name = $('#person_name').val();
            zip = $('#zip_code').val();
            city = $('#city').val();
            state = $('#own_state').val();
            country = $('#own_country').val();
            phone = $('#own_phone').val();

            let id = $(this).attr('data-id');

            if (addressAs != '' && address != '' && name != '' && zip != '' && city != '' && state != '' && country != '' && phone != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('address-update')}}",
                    method: 'POST',
                    data: {
                        id : id,
                        addressAs: addressAs,
                        address: address,
                        name: name,
                        zip: zip,
                        city: city,
                        state: state,
                        country: country,
                        phone: phone
                    },
                    success: function () {
                        toastr.success('{{\App\CPU\translate('Address Update Successfully')}}.');
                        location.reload();
                        // $('#name').val('');
                        // $('#link').val('');
                        // $('#icon').val('');
                        // $('#image-set').val('');

                    }
                });
            }else{
                toastr.error('{{\App\CPU\translate('All input field required')}}.');
            }

        });
    </script>
    <style>
        .modal-backdrop {
            z-index: 0 !important;
            display: none;
        }
    </style>
@endpush
