@extends('layouts.back-end.app')

@section('title', \App\CPU\translate('Request Product Detail'))

@push('css_or_js')
    <style>
        .checkbox-color label {
            width: 2.25rem;
            height: 2.25rem;
            float: left;
            padding: 0.375rem;
            margin-right: 0.375rem;
            display: block;
            font-size: 0.875rem;
            text-align: center;
            opacity: 0.7;
            border: 2px solid #d3d3d3;
            border-radius: 50%;
            -webkit-transition: all 0.3s ease;
            -moz-transition: all 0.3s ease;
            -o-transition: all 0.3s ease;
            -ms-transition: all 0.3s ease;
            transition: all 0.3s ease;
            transform: scale(0.95);
        }
    </style>
@endpush

@section('content')
    <div class="content container-fluid"
         style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <!-- Page Header -->
        <div class="page-header">
            <div class="flex-between row mx-1">
                <div>
                    <h1 class="page-header-title">{{$product['name']}}</h1>
                </div>
                <div class="row">
                    <div class="col-12 flex-start">
                        <div class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">
                            <a href="{{url()->previous()}}" class="btn btn-primary float-right">
                                <i class="tio-back-ui"></i> {{\App\CPU\translate('Back')}}
                            </a>
                        </div>
                        {{-- <div>
                            <a href="javascript:" class="btn btn-primary " target="_blank"><i
                                    class="tio-globe"></i> {{ \App\CPU\translate('View') }} {{ \App\CPU\translate('from') }} {{ \App\CPU\translate('Website') }}
                            </a>
                        </div> --}}
                    </div>
                </div>
            </div>

            @if($product['status'] == 'pending')
                <div class="row">
                    <div class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">
                        @if($product['status'] == 'pending')
                            <a href="{{route('admin.request.approve-status', ['id'=>$product['id']])}}"
                               class="btn btn-secondary float-right">
                                {{\App\CPU\translate('Terima')}}
                            </a>
                        @endif
                    </div>
                    <div class="{{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                        <button class="btn btn-warning float-right" data-toggle="modal" data-target="#publishNoteModal">
                            {{\App\CPU\translate('Tolak')}}
                        </button>
                        <!-- Modal -->
                        <div class="modal fade" id="publishNoteModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                             aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title"
                                            id="exampleModalLabel">{{ \App\CPU\translate('denied_note') }}</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form class="form-group"
                                          action="{{ route('admin.product.deny', ['id'=>$product['id']]) }}"
                                          method="post">
                                        <div class="modal-body">
                                            <textarea class="form-control" name="denied_note" rows="3"></textarea>
                                            <input type="hidden" name="_token" id="csrf-token"
                                                   value="{{ csrf_token() }}"/>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">{{\App\CPU\translate('Close')}}
                                            </button>
                                            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('Save changes')}}</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        @elseif($product['request_status'] == 2)
            <!-- Card -->
                <div class="card mb-3 mb-lg-5 mt-2 mt-lg-3 bg-warning">
                    <!-- Body -->
                    <div class="card-body text-center">
                        <span class="text-dark">{{ $product['denied_note'] }}</span>
                    </div>
                </div>
        @endif
        <!-- Nav -->
            <ul class="nav nav-tabs page-header-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="javascript:">
                        {{\App\CPU\translate('Product details')}}
                    </a>
                </li>
            </ul>
            <!-- End Nav -->
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3 mb-lg-5">
            <!-- Body -->
            <div class="card-body">
                <div class="row align-items-md-center gx-md-5">
                    <div class="col-md-auto mb-3 mb-md-0">
                        <div class="d-flex align-items-center">
                            <img
                                class="avatar avatar-xxl avatar-4by3 {{Session::get('direction') === "rtl" ? 'ml-4' : 'mr-4'}}"
                                onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                src="{{asset("storage/product/request/$product->image")}}"
                                alt="Image Description">

                            {{-- <div class="d-block">
                                <h4 class="display-2 text-dark mb-0">{{count($product->rating)>0?number_format($product->rating[0]->average, 2, '.', ' '):0}}</h4>
                                <p> of {{$product->reviews->count()}} reviews
                                    <span
                                        class="badge badge-soft-dark badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}"></span>
                                </p>
                            </div> --}}
                        </div>
                    </div>

                    {{-- <div class="col-md">
                        <ul class="list-unstyled list-unstyled-py-2 mb-0">

                        @php($total=$product->reviews->count())
                        <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($five=\App\CPU\Helpers::rating_count($product['id'],5))
                                <span
                                    class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('5 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($five/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($five/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$five}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($four=\App\CPU\Helpers::rating_count($product['id'],4))
                                <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('4 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($four/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($four/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$four}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($three=\App\CPU\Helpers::rating_count($product['id'],3))
                                <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('3 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($three/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($three/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span
                                    class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$three}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($two=\App\CPU\Helpers::rating_count($product['id'],2))
                                <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('2 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($two/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($two/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$two}}</span>
                            </li>
                            <!-- End Review Ratings -->

                            <!-- Review Ratings -->
                            <li class="d-flex align-items-center font-size-sm">
                                @php($one=\App\CPU\Helpers::rating_count($product['id'],1))
                                <span class="{{Session::get('direction') === "rtl" ? 'ml-3' : 'mr-3'}}">{{\App\CPU\translate('1 star')}}</span>
                                <div class="progress flex-grow-1">
                                    <div class="progress-bar" role="progressbar"
                                         style="width: {{$total==0?0:($one/$total)*100}}%;"
                                         aria-valuenow="{{$total==0?0:($one/$total)*100}}"
                                         aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span class="{{Session::get('direction') === "rtl" ? 'mr-3' : 'ml-3'}}">{{$one}}</span>
                            </li>
                            <!-- End Review Ratings -->
                        </ul>
                    </div> --}}

                    <div class="col-12">
                        <hr>
                    </div>
                    <div class="col-4 pt-2">
                        <div class="flex-start">
                            <h4 class="border-bottom">{{$product['name']}}</h4>
                        </div>
                        <div class="flex-start">
                            <span>{{\App\CPU\translate('Jumlah')}} : </span>
                            <span
                                class="mx-1">{{$product['qty']}}</span>
                        </div>
                        <div class="flex-start">
                            <span>{{\App\CPU\translate('Nomor WhatsApp')}} : </span>
                            <span class="mx-1">{{($product['phone'])}} </span>
                        </div>
                        <div class="flex-start">
                            <span>{{\App\CPU\translate('Link')}} : </span>
                            <a href="{{ $product['link'] }}" target="_blank"
                                class="mx-1">Link Referensi</a>
                        </div>
                        <div class="flex-start">
                            <span>{{\App\CPU\translate('Deskripsi')}} : </span>
                            <span class="mx-1">{{ $product['description'] }}</span>
                        </div>
                        <div class="flex-start">
                            <span>{{\App\CPU\translate('Status')}} : </span>
                            @if ($product['status'] == 'accepted')
                            <span class="mx-1 mb-0 text-capitalize badge badge-success">{{ $product['status'] }}</span>
                            @elseif($product['status'] == 'pending')
                            <span class="mx-1 mb-0 text-capitalize badge badge-warning">{{ $product['status'] }}</span>
                            @else
                            <span class="mx-1 mb-0 text-capitalize badge badge-danger">{{ $product['status'] }}</span>
                            @endif
                        </div>
                    </div>

                    <div class="col-8 pt-2 border-left">
                        <span>
                        {{\App\CPU\translate('Request Product Image')}}
                     <div class="row">
                             <div class="col-md-3">
                                 <div class="card">
                                     <div class="card-body">
                                         <img style="width: 100%"
                                              onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                              src="{{asset("storage/product/request/$product->image")}}" alt="Product image">

                                     </div>
                                 </div>
                             </div>
                     </div>
                    </span>
                    </div>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->

        <!-- Card -->
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script src="{{asset('public/assets/back-end')}}/js/tags-input.min.js"></script>
    <script src="{{ asset('public/assets/select2/js/select2.min.js')}}"></script>
    <script>
        $('input[name="colors_active"]').on('change', function () {
            if (!$('input[name="colors_active"]').is(':checked')) {
                $('#colors-selector').prop('disabled', true);
            } else {
                $('#colors-selector').prop('disabled', false);
            }
        });
        $(document).ready(function () {
            $('.color-var-select').select2({
                templateResult: colorCodeSelect,
                templateSelection: colorCodeSelect,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            function colorCodeSelect(state) {
                var colorCode = $(state.element).val();
                if (!colorCode) return state.text;
                return "<span class='color-preview' style='background-color:" + colorCode + ";'></span>" + state.text;
            }
        });
    </script>
@endpush
