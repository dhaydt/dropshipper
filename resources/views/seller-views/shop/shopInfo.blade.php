@extends('layouts.back-end.app-seller')
@section('title', \App\CPU\translate('Shop view'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="h3 mb-0  ">{{\App\CPU\translate('my_shop')}} {{\App\CPU\translate('Info')}} </h3>
                    </div>
                    <div class="card-body">
                        <div class="row mt-2">
                            @if($shop->image=='def.png')
                                <div class="col-md-3 text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                                    <img height="200" width="200" class="rounded-circle border"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset('public/assets/back-end')}}/img/shop.png">
                                </div>
                            @else
                                <div class="col-md-3 text-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                                    <img src="{{asset('storage/shop/'.$shop->image)}}"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         class="rounded-circle border"
                                         height="200" width="200" alt="">
                                </div>
                            @endif


                            <div class="col-md-4 mt-4">
                                <div class="flex-start">
                                    <h4>{{\App\CPU\translate('Name')}} : </h4>
                                    <h4 class="mx-1">{{$shop->name}}</h4>
                                </div>
                                <div class="flex-start">
                                    <h6>{{\App\CPU\translate('Phone')}} : </h6>
                                    <h6 class="mx-1">{{$shop->contact}}</h6>
                                </div>
                                                                <div class="flex-start">
                                    <h6>{{\App\CPU\translate('Country')}} : </h6>
                                    @php($c=App\Country::where('country', $shop->country)->first())
                                    <h6 class="mx-1">{{$c->country_name}}</h6>
                                </div>
                                <div class="flex-start">
                                    <h6>{{\App\CPU\translate('Province')}} : </h6>
                                    <h6 class="mx-1">{{$shop->province}}</h6>
                                </div>
                                <div class="flex-start">
                                    <h6>{{\App\CPU\translate('City')}} : </h6>
                                    <h6 class="mx-1">{{$shop->city}}</h6>
                                </div>
                                <div class="flex-start">
                                    <h6>{{\App\CPU\translate('District')}} : </h6>
                                    <h6 class="mx-1">{{$shop->district}}</h6>
                                </div>
                                <div class="flex-start">
                                    <h6>{{\App\CPU\translate('address')}} : </h6>
                                    <h6 class="mx-1">{{$shop->address}}</h6>
                                </div>

                                <div class="flex-start">
                                    <a class="btn btn-primary" href="{{route('seller.shop.edit',[$shop->id])}}">{{\App\CPU\translate('edit')}}</a>
                                </div>
                            </div>
                            <div class="col-md-5"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
@endpush
