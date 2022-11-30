@extends('layouts.front-end.app')

@section('title',\App\CPU\translate('Shop Page'))

@push('css_or_js')
    @if($shop['id'] != 0)
        <meta property="og:image" content="{{asset('storage/shop')}}/{{$shop->image}}"/>
        <meta property="og:title" content="{{ $shop->name}} "/>
        <meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
    @else
        <meta property="og:image" content="{{asset('storage/company')}}/{{$web_config['fav_icon']->value}}"/>
        <meta property="og:title" content="{{ $shop['name']}} "/>
        <meta property="og:url" content="{{route('shopView',[$shop['id']])}}">
    @endif
    <meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

    @if($shop['id'] != 0)
        <meta property="twitter:card" content="{{asset('storage/shop')}}/{{$shop->image}}"/>
        <meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}"/>
        <meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
    @else
        <meta property="twitter:card"
              content="{{asset('storage/company')}}/{{$web_config['fav_icon']->value}}"/>
        <meta property="twitter:title" content="{{route('shopView',[$shop['id']])}}"/>
        <meta property="twitter:url" content="{{route('shopView',[$shop['id']])}}">
    @endif

    <meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">


    <link href="{{asset('public/assets/front-end')}}/css/home.css" rel="stylesheet">
    <style>
        .headerTitle {
            font-size: 34px;
            font-weight: bolder;
            margin-top: 3rem;
        }

        .page-item.active .page-link {
            background-color: {{$web_config['primary_color']}}                       !important;
        }

        .page-item.active > .page-link {
            box-shadow: 0 0 black !important;
        }

        /***********************************/
        .sidepanel {
            width: 0;
            position: fixed;
            z-index: 6;
            height: 500px;
            top: 0;
            left: 0;
            background-color: #ffffff;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 40px;
        }

        .sidepanel a {
            padding: 8px 8px 8px 32px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidepanel a:hover {
            color: #f1f1f1;
        }

        .sidepanel .closebtn {
            position: absolute;
            top: 0;
            right: 0px;
            font-size: 36px;
        }

        .openbtn {
            font-size: 18px;
            cursor: pointer;
            background-color: #ffffff;
            color: #373f50;
            width: 40%;
            border: none;
        }

        .openbtn:hover {
            background-color: #444;
        }

        .for-display {
            display: block !important;
        }

        @media (max-width: 360px) {
            .openbtn {
                width: 59%;
            }

            .for-shoting-mobile {
                margin-right: 0% !important;
            }

            .for-mobile {

                margin-left: 10% !important;
            }

        }

        @media screen and (min-width: 375px) {

            .for-shoting-mobile {
                margin-right: 7% !important;
            }

            .custom-select {
                width: 86px;
            }


        }

        @media (max-width: 500px) {
            .for-mobile {

                margin-left: 27%;
            }

            .openbtn:hover {
                background-color: #fff;
            }

            .for-display {
                display: flex !important;
            }

            .for-shoting-mobile {
                margin-right: 11%;
            }

            .for-tab-display {
                display: none !important;
            }

            .openbtn-tab {
                margin-top: 0 !important;
            }

        }

        @media screen and (min-width: 500px) {
            .openbtn {
                display: none !important;
            }


        }

        @media screen and (min-width: 800px) {


            .for-tab-display {
                display: none !important;
            }

        }

        @media (max-width: 768px) {
            .headerTitle {
                font-size: 23px;

            }

            .openbtn-tab {
                margin-top: 3rem;
                display: inline-block !important;
            }

            .for-tab-display {
                display: inline;
            }

        }


    </style>
@endpush

@section('content')
    <!-- Page Content-->
    <div class="container pb-5 mb-2 mb-md-4">
        <div class="row rtl">
            <!-- banner  -->
            <div class="col-lg-12 mt-2">
                <div style="background: white">
                    @if($shop['id'] != 0)
                        <img style="width:100%; height: auto; max-height: 13.75rem; border-radius: 3px;"
                             src="{{asset('storage/shop/banner')}}/{{$shop->banner}}"
                             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                             alt="">
                    @else
                        @php($banner=\App\CPU\Helpers::get_business_settings('shop_banner'))
                        <img style="width:100%; height: auto; max-height: 13.75rem; border-radius: 3px;"
                             src="{{asset("storage/shop")}}/{{$banner??""}}"
                             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                             alt="">
                    @endif
                </div>
            </div>
            {{-- sidebar opener --}}
            <div class="col-md-3 mt-2 rtl" style=" width: 100%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                <a class="openbtn-tab" style="" onclick="openNav()">
                    <div style="font-size: 20px; font-weight: 600; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}" class="for-tab-display"> ☰ {{\App\CPU\translate('categories')}}</div>
                </a>
            </div>
            {{-- seller info+contact --}}
            <div class="col-lg-12 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                <div class="mt-4 mb-2">
                    <div class="row" style="margin-left: 2px">
                        {{-- logo --}}
                        <div class="row col-lg-8 col-md-6 col-12" style="margin-bottom: 10px">
                            <div class="element-center">
                                @if($shop['id'] != 0)
                                    <img style="height: 65px; border-radius: 2px;"
                                         src="{{asset('storage/shop')}}/{{$shop->image}}"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         alt="">
                                @else
                                    <img style="height: 65px; border-radius: 2px;"
                                         src="{{asset('storage/company')}}/{{$web_config['fav_icon']->value}}"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         alt="">
                                @endif
                            </div>
                            <div class="row col-8 mx-1" style="display:inline-block;">
                                <h6 class="ml-4 font-weight-bold mt-3">
                                    @if($shop['id'] != 0)
                                        {{ $shop->name}}
                                    @else
                                        {{ $web_config['name']->value }}
                                    @endif
                                </h6>
                                <div class="row ml-4 flex-start">
                                    <div class="mr-3">
                                        <span class="mr-1">{{round($avg_rating,2)}}</span>
                                        @for($count=0; $count<5; $count++)
                                            @if($avg_rating >= $count+1)
                                                <i class="sr-star czi-star-filled active"></i>
                                            @else
                                                <i class="sr-star czi-star active"></i>
                                            @endif
                                        @endfor
                                    </div>
                                    <div>{{ $total_review}} {{\App\CPU\translate('reviews')}}
                                        / {{ $total_order}} {{\App\CPU\translate('orders')}}  /
                                    @if($shop['id'] != 0)
                                    <img class="ml-1 {{Session::get('direction') === " rtl" ? 'ml-1' : 'mr-1' }}"
                                        width="20"
                                        src="{{asset('public/assets/front-end')}}/img/flags/{{ strtolower($shop->country)  }}.png"
                                        alt="Eng" style="width: 20px">
                                    @else
                                    <img class="ml-1 {{Session::get('direction') === " rtl" ? 'ml-1' : 'mr-1' }}"
                                        width="20" src="{{asset('public/assets/front-end')}}/img/flags/id.png" alt="Eng"
                                        style="width: 20px">
                                    @endif

                                    @if($shop['id'] != 0)
                                    {{ $shop->country}}
                                    @else
                                    ID
                                    @endif</div>
                                </div>
                            </div>
                        </div>

                        {{-- contact --}}
                        <div class="col-lg-2 col-md-3 col-12">
                            @if($seller_id!=0)
                                @if (auth('customer')->check())
                                    <div class="d-flex">
                                        <button class="btn btn-primary btn-block" data-toggle="modal"
                                                data-target="#exampleModal">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                            {{\App\CPU\translate('Contact')}} {{\App\CPU\translate('Seller')}}
                                        </button>
                                    </div>
                                @else
                                    <div class="d-flex">
                                        <a href="{{route('customer.auth.login')}}" class="btn btn-primary btn-block">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                            {{\App\CPU\translate('Contact')}} {{\App\CPU\translate('Seller')}}
                                        </a>
                                    </div>
                                @endif
                            @endif
                        </div>

                        {{-- search --}}
                        <div class="col-lg-2 col-md-3 col-sm-12 col-12 pt-2" style="direction: ltr;">
                            <form class="{{--form-inline--}} md-form form-sm mt-0" method="get"
                                  action="{{route('shopView',['id'=>$seller_id])}}">
                                <div class="input-group input-group-sm mb-3">
                                    <input type="text" class="form-control" name="product_name" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                           placeholder="{{\App\CPU\translate('Search Product')}}" aria-label="Recipient's username"
                                           aria-describedby="basic-addon2">
                                    <div class="input-group-append">
                                        <button type="submit" class="input-group-text" id="basic-addon2">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Motal --}}
                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                     aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="card-header">
                                {{\App\CPU\translate('write_something')}}
                            </div>
                            <div class="modal-body">
                                <form action="{{route('messages_store')}}" method="post" id="chat-form">
                                    @csrf
                                    @if($shop['id'] != 0)
                                        <input value="{{$shop->id}}" name="shop_id" hidden>
                                        <input value="{{$shop->seller_id}}}" name="seller_id" hidden>
                                    @endif

                                    <textarea name="message" class="form-control" required></textarea>
                                    <br>
                                    @if($shop['id'] != 0)
                                        <button class="btn btn-primary" style="color: white;">{{\App\CPU\translate('send')}}</button>
                                    @else
                                        <button class="btn btn-primary" style="color: white;" disabled>{{\App\CPU\translate('send')}}</button>
                                    @endif
                                </form>
                            </div>
                            <div class="card-footer">
                                <a href="{{route('chat-with-seller')}}" class="btn btn-primary mx-1">
                                    {{\App\CPU\translate('go_to')}} {{\App\CPU\translate('chatbox')}}
                                </a>
                                <button type="button" class="btn btn-secondary pull-right" data-dismiss="modal">{{\App\CPU\translate('close')}}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <hr>
        {{-- sidebar opener --}}
        <button class="openbtn" onclick="openNav()" style="width: 100%">
            <div style="margin-bottom: -30%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}}">
                {{Session::get('direction') !== "rtl" ? '☰' : ''}}
                {{\App\CPU\translate('categories')}}
                {{Session::get('direction') === "rtl" ? '☰' : ''}}
            </div>
        </button>

        <div class="row mt-2 mr-0 rtl">
            {{-- sidebar (Category) - before toggle --}}
            <div class="col-lg-3 mt-3 pr-0 mr-0">
                <aside class=" hidden-xs SearchParameters" id="SearchParameters">
                    <!-- Categories Sidebar-->
                    <div class="cz-sidebar rounded-lg box-shadow-lg" id="shop-sidebar">
                        <div class="cz-sidebar-body">
                            <!-- Categories-->
                            <div class="widget widget-categories mb-4 pb-4 border-bottom">
                                <div>
                                    <div style="display: inline">
                                        <h3 class="widget-title"
                                            style="font-weight: 700;display: inline">{{\App\CPU\translate('categories')}}</h3>
                                    </div>
                                </div>
                                <div class="divider-role"
                                     style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: 5px;"></div>
                                <div class="accordion mt-n1" id="shop-categories">
                                    @foreach($categories as $category)
                                        <div class="card">
                                            <div class="card-header p-1 flex-between">
                                                <label class="for-hover-lable" style="cursor: pointer"
                                                       onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$category['id']])}}'" {{--onclick="productSearch({{$seller_id}}, {{$category['id']}})"--}}>
                                                    {{$category['name']}}
                                                </label>
                                                <strong class="pull-right for-brand-hover" style="cursor: pointer"
                                                        onclick="$('#collapse-{{$category['id']}}').toggle(400)">
                                                    {{$category->childes->count()>0?'+':''}}
                                                </strong>
                                            </div>
                                            <div class="card-body {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}" id="collapse-{{$category['id']}}"
                                                 style="display: none">
                                                @foreach($category->childes as $child)
                                                    <div class=" for-hover-lable card-header p-1 flex-between">
                                                        <label style="cursor: pointer"
                                                               onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$child['id']])}}'">
                                                            {{$child['name']}}
                                                        </label>
                                                        <strong class="pull-right" style="cursor: pointer"
                                                                onclick="$('#collapse-{{$child['id']}}').toggle(400)">
                                                            {{$child->childes->count()>0?'+':''}}
                                                        </strong>
                                                    </div>
                                                    <div class="card-body {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}" id="collapse-{{$child['id']}}"
                                                         style="display: none">
                                                        @foreach($child->childes as $ch)
                                                            <div class="card-header p-1 flex-between">
                                                                <label class="for-hover-lable" style="cursor: pointer"
                                                                       onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$ch['id']])}}'">
                                                                    {{$ch['name']}}
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
            {{-- sidebar (Category mobile) - after toggle --}}
            <div id="mySidepanel" class="sidepanel" style="text-align: {{Session::get('direction') === "rtl" ? 'right:0; left:auto' : 'right:auto; left:0'}};">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                <div class="cz-sidebar-body">
                    <div class="widget widget-categories mb-4 pb-4 border-bottom">
                        <div>
                            <div style="display: inline">
                                <h3 class="widget-title"
                                    style="font-weight: 700;display: inline">{{\App\CPU\translate('categories')}}</h3>
                            </div>
                        </div>
                        <div class="divider-role"
                             style="border: 1px solid whitesmoke; margin-bottom: 14px;  margin-top: 5px;"></div>
                        <div class="accordion mt-n1" id="shop-categories" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                            @foreach($categories as $category)
                                <div class="card">
                                    <div class="card-header p-1 flex-between">
                                        <label class="for-hover-lable" style="cursor: pointer"
                                               onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$category['id']])}}'" {{--onclick="productSearch({{$seller_id}}, {{$category['id']}})"--}}>
                                            {{$category['name']}}
                                        </label>
                                        <strong class="pull-right for-brand-hover" style="cursor: pointer"
                                                onclick="$('#collapse-m-{{$category['id']}}').toggle(400)">
                                            {{$category->childes->count()>0?'+':''}}
                                        </strong>
                                    </div>
                                    <div class="card-body {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}" id="collapse-m-{{$category['id']}}"
                                         style="display: none">
                                        @foreach($category->childes as $child)
                                            <div class=" for-hover-lable card-header p-1 flex-between">
                                                <label style="cursor: pointer"
                                                       onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$child['id']])}}'">
                                                    {{$child['name']}}
                                                </label>
                                                <strong class="pull-right" style="cursor: pointer"
                                                        onclick="$('#collapse-m-{{$child['id']}}').toggle(400)">
                                                    {{$child->childes->count()>0?'+':''}}
                                                </strong>
                                            </div>
                                            <div class="card-body {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}" id="collapse-m-{{$child['id']}}"
                                                 style="display: none">
                                                @foreach($child->childes as $ch)
                                                    <div class="card-header p-1 flex-between">
                                                        <label class="for-hover-lable" style="cursor: pointer"
                                                               onclick="location.href='{{route('shopView',['id'=> $seller_id,'category_id'=>$ch['id']])}}'">
                                                            {{$ch['name']}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            {{-- main body (Products) --}}
            <div class="col-lg-9 product-div">
                <!-- Products grid-->
                <div class="row mt-3" id="ajax-products">
                    @include('web-views.products._ajax-products',['products'=>$products])
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        function productSearch(seller_id, category_id) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: "post",
                url: '{{url('/')}}/shopView/' + seller_id + '?category_id=' + category_id,

                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (response) {
                    $('#ajax-products').html(response.view);
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        }
    </script>

    <script>
        function openNav() {

            document.getElementById("mySidepanel").style.width = "50%";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }
    </script>

    <script>
        $('#chat-form').on('submit', function (e) {
            e.preventDefault();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });

            $.ajax({
                type: "post",
                url: '{{route('messages_store')}}',
                data: $('#chat-form').serialize(),
                success: function (respons) {

                    toastr.success('{{\App\CPU\translate('send successfully')}}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
                    $('#chat-form').trigger('reset');
                }
            });

        });
    </script>
@endpush
