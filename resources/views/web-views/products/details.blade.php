@extends('layouts.front-end.app')

@section('title',$product['name'])

@push('css_or_js')
    <meta name="description" content="{{$product->slug}}">
    <meta name="keywords" content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @if($product->added_by=='seller')
        <meta name="author" content="{{ $product->seller->shop?$product->seller->shop->name:$product->seller->f_name}}">
    @elseif($product->added_by=='admin')
        <meta name="author" content="{{$web_config['name']->value}}">
    @endif
    <!-- Viewport-->

    @if($product['meta_image']!=null)
        <meta property="og:image" content="{{asset("storage/>product/meta")}}/{{$product->meta_image}}"/>
        <meta property="twitter:card"
              content="{{asset("storage/product/meta")}}/{{$product->meta_image}}"/>
    @else
        <meta property="og:image" content="{{asset("storage/product/thumbnail")}}/{{$product->thumbnail}}"/>
        <meta property="twitter:card"
              content="{{asset("storage/product/thumbnail/")}}/{{$product->thumbnail}}"/>
    @endif

    @if($product['meta_title']!=null)
        <meta property="og:title" content="{{$product->meta_title}}"/>
        <meta property="twitter:title" content="{{$product->meta_title}}"/>
    @else
        <meta property="og:title" content="{{$product->name}}"/>
        <meta property="twitter:title" content="{{$product->name}}"/>
    @endif
    <meta property="og:url" content="{{route('product',[$product->slug])}}">

    @if($product['meta_description']!=null)
        <meta property="twitter:description" content="{!! $product['meta_description'] !!}">
        <meta property="og:description" content="{!! $product['meta_description'] !!}">
    @else
        <meta property="og:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
        <meta property="twitter:description"
              content="@foreach(explode(' ',$product['name']) as $keyword) {{$keyword.' , '}} @endforeach">
    @endif
    <meta property="twitter:url" content="{{route('product',[$product->slug])}}">

    <link rel="stylesheet" href="{{asset('public/assets/front-end/css/product-details.css')}}"/>
    <style>
        .msg-option {
            display: none;
        }

        .chatInputBox {
            width: 100%;
        }

        .go-to-chatbox {
            width: 100%;
            text-align: center;
            padding: 5px 0px;
            display: none;
        }

        .feature_header {
            display: flex;
            justify-content: center;
        }

        .btn-number:hover {
            color: {{$web_config['secondary_color']}};
        }

        .for-total-price {
            margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0%;
        }

        .feature_header span {
            padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 15px;
            font-weight: 700;
            font-size: 25px;
            background-color: #ffffff;
            text-transform: uppercase;
        }

        @media (max-width: 768px) {
            .feature_header span {
                margin-bottom: -40px;
            }

            .for-total-price {
                padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 30%;
            }

            .product-quantity {
                padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 4%;
            }

            .for-margin-bnt-mobile {
                margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 7px;
            }

            .font-for-tab {
                font-size: 11px !important;
            }

            .pro {
                font-size: 13px;
            }
        }

        @media (max-width: 375px) {
            .for-margin-bnt-mobile {
                margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: 3px;
            }

            .for-discount {
                margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 10% !important;
            }

            .for-dicount-div {
                margin-top: -5%;
                margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: -7%;
            }

            .product-quantity {
                margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 4%;
            }

        }

        @media (max-width: 500px) {
            .for-dicount-div {
                margin-top: -4%;
                margin-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: -5%;
            }

            .for-total-price {
                margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: -20%;
            }

            .view-btn-div {

                margin-top: -9%;
                float: {{Session::get('direction') === "rtl" ? 'left' : 'right'}};
            }

            .for-discount {
                margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 7%;
            }

            .viw-btn-a {
                font-size: 10px;
                font-weight: 600;
            }

            .feature_header span {
                margin-bottom: -7px;
            }

            .for-mobile-capacity {
                margin-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 7%;
            }
        }
    </style>
    <style>

        thead {
            background: {{$web_config['primary_color']}} !important;
            color: white;
        }
        th, td {
            border-bottom: 1px solid #ddd;
            padding: 5px;
        }

    </style>
@endpush

@section('content')
    <?php
    $overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews);
    $rating = \App\CPU\ProductManager::get_rating($product->reviews);
    ?>
    <!-- Page Content-->
    <div class="container mt-4 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <!-- General info tab-->
        <div class="row" style="direction: ltr">
            <!-- Product gallery-->
            <div class="col-lg-6 col-md-6">
                <div class="cz-product-gallery">
                    <div class="cz-preview">
                        @if($product->images!=null)
                            @foreach (json_decode($product->images) as $key => $photo)
                                <div
                                    class="cz-preview-item d-flex align-items-center justify-content-center {{$key==0?'active':''}}"
                                    id="image{{$key}}">
                                    <img class="cz-image-zoom img-responsive"
                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                         src="{{asset("storage/product/$photo")}}"
                                         data-zoom="{{asset("storage/product/$photo")}}"
                                         alt="Product image" width="">
                                    <div class="cz-image-zoom-pane"></div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="cz">
                        <div class="container">
                            <div class="row">
                                <div class="table-responsive" data-simplebar style="max-height: 515px; padding: 1px;">
                                    <div class="d-flex">
                                        @if($product->images!=null)
                                            @foreach (json_decode($product->images) as $key => $photo)
                                                <div class="cz-thumblist">
                                                    <a class="cz-thumblist-item  {{$key==0?'active':''}} d-flex align-items-center justify-content-center "
                                                       href="#image{{$key}}">
                                                        <img
                                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                            src="{{asset("storage/product/$photo")}}"
                                                            alt="Product thumb">
                                                    </a>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Product details-->
            <div class="col-lg-6 col-md-6 mt-md-0 mt-sm-3" style="direction: {{ Session::get('direction') }}">
                <div class="details">
                    <h1 class="h3 mb-2">{{$product->name}}</h1>
                    <div class="d-flex align-items-center mb-2 pro">
                        {{-- <span
                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-md-2 ml-sm-0 pl-2' : 'mr-md-2 mr-sm-0 pr-2'}}">{{$overallRating[0]}}</span> --}}
                        {{-- <div class="star-rating">
                            @for($inc=0;$inc<5;$inc++)
                                @if($inc<$overallRating[0])
                                    <i class="sr-star czi-star-filled active"></i>
                                @else
                                    <i class="sr-star czi-star"></i>
                                @endif
                            @endfor
                        </div> --}}
                        {{-- <span
                            class="font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-1 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-1 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}}">{{$overallRating[1]}} {{\App\CPU\translate('Reviews')}}</span> --}}

                        {{-- <span style="width: 0px;height: 10px;border: 0.5px solid #707070; margin-top: 6px">
                        </span> --}}
                        {{-- <span
                            class="font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-1 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-1 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}}">{{$countOrder}} {{\App\CPU\translate('orders')}}   </span>
                        <span style="width: 0px;height: 10px;border: 0.5px solid #707070; margin-top: 6px">    </span>
                        <span
                            class=" font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-0 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-0 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}}">  {{$countWishlist}} {{\App\CPU\translate('wish')}} </span> --}}

                    </div>
                    <div class="mb-3">
                        <span
                            class="h3 font-weight-normal text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}">
                            {{\App\CPU\Helpers::get_price_range($product) }}
                        </span>
                        @if($product->discount > 0)
                            <strike style="color: {{$web_config['secondary_color']}};">
                                @if (session()->get('user_is') == 'dropship')
                                {{\App\CPU\Helpers::currency_converter($product->dropship)}}
                                @else
                                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                                @endif
                            </strike>
                        @endif
                    </div>

                    @if($product->discount > 0)
                        <div class="mb-3">
                            <strong>{{\App\CPU\translate('discount')}} : </strong>
                            <strong id="set-discount-amount"></strong>
                        </div>
                    @endif

                    {{-- <div class="mb-3">
                        <strong>{{\App\CPU\translate('tax')}} : </strong>
                        <strong id="set-tax-amount"></strong>
                    </div> --}}
                    <form id="add-to-cart-form" class="mb-2">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <div class="position-relative {{Session::get('direction') === "rtl" ? 'ml-n4' : 'mr-n4'}} mb-3">
                            @if (count(json_decode($product->colors)) > 0)
                                <div class="flex-start">
                                    <div class="product-description-label mt-2">{{\App\CPU\translate('color')}}:
                                    </div>
                                    <div>
                                        <ul class="list-inline checkbox-color mb-1 flex-start {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"
                                            style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;">
                                            @foreach (json_decode($product->colors) as $key => $color)
                                                <div>
                                                    <li>
                                                        <input type="radio"
                                                               id="{{ $product->id }}-color-{{ $key }}"
                                                               name="color" value="{{ $color }}"
                                                               @if($key == 0) checked @endif>
                                                        <label style="background: {{ $color }};"
                                                               for="{{ $product->id }}-color-{{ $key }}"
                                                               data-toggle="tooltip"></label>
                                                    </li>
                                                </div>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endif
                            @php
                                $qty = 0;
                                if(!empty($product->variation)){
                                foreach (json_decode($product->variation) as $key => $variation) {
                                        $qty += $variation->qty;
                                    }
                                }
                            @endphp
                        </div>
                        @foreach (json_decode($product->choice_options) as $key => $choice)
                            <div class="row flex-start mx-0">
                                <div
                                    class="product-description-label mt-2 {{Session::get('direction') === "rtl" ? 'pl-2' : 'pr-2'}}">{{ $choice->title }}
                                    :
                                </div>
                                <div>
                                    <ul class="list-inline checkbox-alphanumeric checkbox-alphanumeric--style-1 mb-2 mx-1 flex-start"
                                        style="padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0;">
                                        @foreach ($choice->options as $key => $option)
                                            <div>
                                                <li class="for-mobile-capacity">
                                                    <input type="radio"
                                                           id="{{ $choice->name }}-{{ $option }}"
                                                           name="{{ $choice->name }}" value="{{ $option }}"
                                                           @if($key == 0) checked @endif >
                                                    <label style="font-size: .6em"
                                                           for="{{ $choice->name }}-{{ $option }}">{{ $option }}</label>
                                                </li>
                                            </div>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                    @endforeach

                    <!-- Quantity + Add to cart -->
                        <div class="row no-gutters">
                            <div class="col-2">
                                <div class="product-description-label mt-2">{{\App\CPU\translate('Jumlah')}}:</div>
                            </div>
                            <div class="col-10">
                                <div class="product-quantity d-flex align-items-center">
                                    <div
                                        class="input-group input-group--style-2 {{Session::get('direction') === "rtl" ? 'pl-3' : 'pr-3'}}"
                                        style="width: 160px;">
                                        <span class="input-group-btn">
                                            <button class="btn btn-number" type="button"
                                                    data-type="minus" data-field="quantity"
                                                    disabled="disabled" style="padding: 10px">
                                                -
                                            </button>
                                        </span>
                                        <input type="text" name="quantity"
                                               class="form-control input-number text-center cart-qty-field"
                                               placeholder="1" value="1" min="1" max="100">
                                        <span class="input-group-btn">
                                            <button class="btn btn-number" type="button" data-type="plus"
                                                    data-field="quantity" style="padding: 10px">
                                               +
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row flex-start no-gutters d-none mt-2" id="chosen_price_div">
                            <div class="{{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">
                                <div class="product-description-label">{{\App\CPU\translate('total_harga')}}:</div>
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="product-price for-total-price mt-0">
                                    <strong id="chosen_price"></strong>
                                </div>
                            </div>

                            <div class="col-12">
                                @if($product['current_stock']<=0)
                                    <h5 class="mt-3" style="color: red">{{\App\CPU\translate('out_of_stock')}}</h5>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-left mt-2">
                            {{-- <button
                                class="btn btn-secondary element-center btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}"
                                onclick="buy_now()"
                                type="button"
                                style="width:37%; height: 45px">
                                <span class="string-limit">{{\App\CPU\translate('buy_now')}}</span>
                            </button> --}}
                            <button
                                class="btn btn-primary element-center btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}"
                                onclick="addToCart()"
                                type="button"
                                style="width:65%; height: 45px">
                                <span class="string-limit"><i class="fa fa-plus"></i> {{\App\CPU\translate('Keranjang')}}</span>
                            </button>
                            <button type="button" onclick="addWishlist('{{$product['id']}}')"
                                    class="btn btn-dark for-hover-bg"
                                    style="">
                                <i class="fa fa-heart-o {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"
                                   aria-hidden="true"></i>
                                <span class="countWishlist-{{$product['id']}}">{{$countWishlist}}</span>
                            </button>
                            @php($type = session()->get('user_is'))
                            @if ($type == 'dropship')
                            @php($seller = auth('seller')->user())
                            <div class="text-left" style="margin-left: 2px;">
                                <a class="btn btn-success" href="{{env('ETOKO_URL').'/generated/'.$seller['id'].'/'.$seller->shop['name'].'/'.$product['slug']  }}" target="_blank">
                                    <i class="fa fa-share-alt" aria-hidden="true"></i>
                                </a>
                            </div>
                            @else

                            @endif
                        </div>
                    </form>
                    {{-- <div style="text-align:{{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                         class="sharethis-inline-share-buttons">
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

    {{--seller section--}}
    {{-- @if($product->added_by=='seller')
        <div class="container mt-4 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="row seller_details d-flex align-items-center" id="sellerOption">
                <div class="col-md-6">
                    <div class="seller_shop">
                        <div class="shop_image d-flex justify-content-center align-items-center">
                            <a href="#" class="d-flex justify-content-center">
                                <img style="height: 65px; width: 65px; border-radius: 50%"
                                     src="{{asset('storage/shop')}}/{{$product->seller->shop->image}}"
                                     onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                     alt="">
                            </a>
                        </div>
                        <div
                            class="shop-name-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} d-flex justify-content-center align-items-center">
                            <div>
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="title">{{$product->seller->shop->name}}</div>
                                </a>
                                <div class="review d-flex align-items-center">
                                    <div class="">
                                        <span
                                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">{{\App\CPU\translate('Seller')}} {{\App\CPU\translate('Info')}} </span>
                                        <span
                                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"></span>
                                    </div>
                                </div>
                                 <div class="review d-flex align-items-center">
                            <div class="w-100 d-flex">
                                <div class="flag">
                                    <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                                        src="{{asset('public/assets/front-end')}}/img/flags/{{ strtolower($product->seller->shop->country)  }}.png"
                                        alt="Eng" style="width: 20px">
                                </div>
                                @php($c_name = App\Country::where('country', $product->seller->shop->country)->get())
                                <span
                                    class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "
                                    rtl" ? 'ml-2' : 'mr-2' }}" style="line-height: 1.2;">{{$c_name[0]->country_name}}
                                </span>
                                <span
                                    class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "
                                    rtl" ? 'mr-2' : 'ml-2' }}"></span>
                            </div>
                        </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6 p-md-0 pt-sm-3">
                    <div class="seller_contact">
                        <div
                            class="d-flex align-items-center {{Session::get('direction') === "rtl" ? 'pl-4' : 'pr-4'}}">
                            <a href="{{ route('shopView',[$product->seller->id]) }}">
                                <button class="btn btn-secondary">
                                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                    {{\App\CPU\translate('Visit')}}
                                </button>
                            </a>
                        </div>

                        @if (auth('customer')->id() == '')
                            <div class="d-flex align-items-center">
                                <a href="{{route('customer.auth.login')}}">
                                    <button class="btn btn-primary">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                        {{\App\CPU\translate('Contact')}} {{\App\CPU\translate('Seller')}}
                                    </button>
                                </a>
                            </div>
                        @else
                            <div class="d-flex align-items-center" id="contact-seller">
                                <button class="btn btn-primary">
                                    <i class="fa fa-envelope" aria-hidden="true"></i>
                                    {{\App\CPU\translate('Contact')}} {{\App\CPU\translate('Seller')}}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row msg-option" id="msg-option">
                <form action="">
                    <input type="text" class="seller_id" hidden seller-id="{{$product->seller->id }}">
                    <textarea shop-id="{{$product->seller->shop->id}}" class="chatInputBox"
                            id="chatInputBox" rows="5"> </textarea>

                    <button class="btn btn-secondary" style="color: white;"
                            id="cancelBtn">{{\App\CPU\translate('cancel')}}
                    </button>
                    <button class="btn btn-primary" style="color: white;"
                            id="sendBtn">{{\App\CPU\translate('send')}}</button>
                </form>
            </div>
            <div class="go-to-chatbox" id="go_to_chatbox">
                <a href="{{route('chat-with-seller')}}" class="btn btn-primary" id="go_to_chatbox_btn">
                    {{\App\CPU\translate('go_to')}} {{\App\CPU\translate('chatbox')}} </a>
            </div>
        </div>
    @else
        <div class="container rtl mt-3" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
            <div class="row seller_details d-flex align-items-center" id="sellerOption">
                <div class="col-md-6">
                    <div class="seller_shop">
                        <div class="shop_image d-flex justify-content-center align-items-center">
                            <a href="{{ route('shopView',[0]) }}" class="d-flex justify-content-center">
                                <img style="height: 65px;width: 65px; border-radius: 50%"
                                     src="{{asset("storage/company")}}/{{$web_config['fav_icon']->value}}"
                                     onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                     alt="">
                            </a>
                        </div>
                        <div
                            class="shop-name-{{Session::get('direction') === "rtl" ? 'right' : 'left'}} d-flex justify-content-center align-items-center">
                            <div>
                                <a href="#" class="d-flex align-items-center">
                                    <div
                                        class="title">{{$web_config['name']->value}}</div>
                                </a>
                                <div class="review d-flex align-items-center">
                                    <div class="">
                                        <span
                                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}">{{ \App\CPU\translate('web_admin')}}</span>
                                        <span
                                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-2' : 'ml-2'}}"></span>
                                    </div>
                                </div>
                                <div class="review d-flex align-items-center">
                            <div class="w-100 d-flex">
                                <div class="flag">
                                    <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                                        src="{{asset('public/assets/front-end')}}/img/flags/id.png" alt="Eng"
                                        style="width: 20px">
                                </div>
                                <span
                                    class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "
                                    rtl" ? 'ml-2' : 'mr-2' }}" style="line-height: 1.2;">Indonesia </span>
                                <span
                                    class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "
                                    rtl" ? 'mr-2' : 'ml-2' }}"></span>
                            </div>
                        </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-md-6 p-md-0 pt-sm-3">
                    <div class="seller_contact">

                        <div
                            class="d-flex align-items-center {{Session::get('direction') === "rtl" ? 'pl-4' : 'pr-4'}}">
                            <a href="{{ route('shopView',[0]) }}">
                                <button class="btn btn-secondary">
                                    <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                                    {{\App\CPU\translate('Visit')}}
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif --}}

    {{--overview--}}
    <div class="container mt-2 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="row" style="background: white">
            <div class="col-12">
                <div class="product_overview mt-1">
                    <!-- Tabs-->
                    <ul class="nav nav-tabs d-flex justify-content-center" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" href="#overview" data-toggle="tab" role="tab"
                               style="color: black !important;">
                                {{\App\CPU\translate('Informasi_Produk')}}
                            </a>
                        </li>
                    </ul>
                    <div class="px-4 pt-lg-3 pb-3 mb-3">
                        <div class="tab-content px-lg-3">
                            <!-- Tech specs tab-->
                            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                <div class="row pt-2 specification">
                                    @if($product->video_url!=null)
                                        <div class="col-12 mb-4">
                                            <iframe width="420" height="315"
                                                    src="{{$product->video_url}}">
                                            </iframe>
                                        </div>
                                    @endif

                                    <?php
                                    $prod = \App\Model\Product::find($product->id);
                                    ?>
                                    <div class="col-lg-12 col-md-12">
                                        {{-- {{ dd($prod->details) }} --}}
                                        {!! $prod->details !!}
                                    </div>
                                </div>
                            </div>
                            <!-- Reviews tab-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Product carousel (You may also like)-->
    <div class="container mt-3 mb-3 rtl" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="flex-between">
            <div class="feature_header">
                <span style="background-color: transparent !important;">{{ \App\CPU\translate('Produk_Mirip')}}</span>
            </div>

            <div class="view_all ">
                <div>
                    @php($category=json_decode($product['category_ids']))
                    <a class="btn btn-outline-accent btn-sm viw-btn-a"
                       href="{{route('products',['id'=> $category[0]->id,'data_from'=>'category','page'=>1])}}">{{ \App\CPU\translate('view_all')}}
                        <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'}}"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- Grid-->
        <hr class="view_border">
        <!-- Product-->
        <div class="row mt-4">
            @if (count($relatedProducts)>0)
                @foreach($relatedProducts as $key => $relatedProduct)
                    <div class="col-xl-2 col-sm-3 col-6" style="margin-bottom: 20px">
                        @include('web-views.partials._single-product',['product'=>$relatedProduct])
                    </div>
                @endforeach
            @else
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="text-danger text-center">{{\App\CPU\translate('similar')}} {{\App\CPU\translate('product_not_available')}}</h6>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade rtl" id="show-modal-view" tabindex="-1" role="dialog" aria-labelledby="show-modal-image"
         aria-hidden="true" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body" style="display: flex;justify-content: center">
                    <button class="btn btn-default"
                            style="border-radius: 50%;margin-top: -25px;position: absolute;{{Session::get('direction') === "rtl" ? 'left' : 'right'}}: -7px;"
                            data-dismiss="modal">
                        <i class="fa fa-close"></i>
                    </button>
                    <img class="element-center" id="attachment-view" src="">
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')

    <script type="text/javascript">
        cartQuantityInitialize();
        getVariantPrice();
        $('#add-to-cart-form input').on('change', function () {
            getVariantPrice();
        });

        function showInstaImage(link) {
            $("#attachment-view").attr("src", link);
            $('#show-modal-view').modal('toggle')
        }
    </script>

    {{-- Messaging with shop seller --}}
    <script>
        $('#contact-seller').on('click', function (e) {
            // $('#seller_details').css('height', '200px');
            $('#seller_details').animate({'height': '276px'});
            $('#msg-option').css('display', 'block');
        });
        $('#sendBtn').on('click', function (e) {
            e.preventDefault();
            let msgValue = $('#msg-option').find('textarea').val();
            let data = {
                message: msgValue,
                shop_id: $('#msg-option').find('textarea').attr('shop-id'),
                seller_id: $('.msg-option').find('.seller_id').attr('seller-id'),
            }
            if (msgValue != '') {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "post",
                    url: '{{route('messages_store')}}',
                    data: data,
                    success: function (respons) {
                        console.log('send successfully');
                    }
                });
                $('#chatInputBox').val('');
                $('#msg-option').css('display', 'none');
                $('#contact-seller').find('.contact').attr('disabled', '');
                $('#seller_details').animate({'height': '125px'});
                $('#go_to_chatbox').css('display', 'block');
            } else {
                console.log('say something');
            }
        });
        $('#cancelBtn').on('click', function (e) {
            e.preventDefault();
            $('#seller_details').animate({'height': '114px'});
            $('#msg-option').css('display', 'none');
        });
    </script>

    <script type="text/javascript"
            src="https://platform-api.sharethis.com/js/sharethis.js#property=5f55f75bde227f0012147049&product=sticky-share-buttons"
            async="async"></script>
@endpush
