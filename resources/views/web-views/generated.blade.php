<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <style>
        body {
            max-width: 1080px;
            background-color: gray;
            display: flex;
            justify-content: center;
            margin: auto;
        }

        .main-container,
        .card {
            background-color: rgb(215, 215, 215);
        }

        .carousel-item {
            height: 423px;
            width: 100%;
        }

        .card .price_box {
            padding: 1rem;
            background-color: #fff;
        }

        .card .price_box .price {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card .price_box .price .price-text {
            color: #21bc88;
            font-size: 1.2rem;
        }

        .card .price_box .price .price-text>span {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .card .price_box .copy_msg {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card .price_box .copy_msg .text {
            flex: 1 1;
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            word-break: break-word;
            overflow: hidden;
            max-width: 100%;
            font-size: 1.2rem;
            font-weight: 600;
            color: #30333c;
        }

        .type_box{
            background-color: #fff;
            padding: 1rem;
        }

        .card .type_box .title {
            margin-bottom: 7px;
        }

        .type_box .before-icon {
            display: inline-block;
            width: 3px;
            height: 10px;
            margin-right: 7px;
            background: #18a78c;
            border-radius: 35px;
        }

        .for-mobile-capacity {
            margin-left: 7%;
        }

        .checkbox-alphanumeric input {
            left: -9999px;
            position: absolute;
        }

        label:not(.form-check-label):not(.custom-control-label):not(.custom-file-label):not(.custom-option-label) {
            color: #373f50;
        }

        .checkbox-alphanumeric input:checked ~ label {
            transform: scale(1.1);
            border-color: red !important;
        }

        .checkbox-alphanumeric--style-1 label {
            width: auto;
            padding-left: 1rem;
            padding-right: 1rem;
            border-radius: 2px;
        }

        .checkbox-alphanumeric label {
            width: 2.25rem;
            height: 2.25rem;
            float: left;
            padding: 0.375rem 0;
            margin-right: 0.375rem;
            display: block;
            color: #818a91;
            font-size: 0.875rem;
            font-weight: 400;
            text-align: center;
            background: transparent;
            text-transform: uppercase;
            border: 1px solid #e6e6e6;
            border-radius: 2px;
            -webkit-transition: all 0.3s ease;
            -moz-transition: all 0.3s ease;
            -o-transition: all 0.3s ease;
            -ms-transition: all 0.3s ease;
            transition: all 0.3s ease;
            transform: scale(0.95);
        }

        .checkbox-alphanumeric::after {
            clear: both;
        }

        .checkbox-alphanumeric::after, .checkbox-alphanumeric::before {
            content: '';
            display: table;
        }
        @media (max-width: 768px){
            .product-quantity {
                padding-left: 4%;
            }
        }
        .btn-number:hover {
            color: #000000;
        }

        .btn.disabled, .btn:disabled {
            opacity: .65;
            box-shadow: none;
            border: none;
        }
        .btn:not(:disabled):not(.disabled) {
            cursor: pointer;
        }
        .input-group > .form-control:not(:last-child), .input-group > .custom-select:not(:last-child) {
            border-radius: 4px !important;
            font-size: 10px;
            padding: 0;
        }

        .anticon {
            display: inline-block;
            color: inherit;
            font-style: normal;
            line-height: 0;
            text-align: center;
            text-transform: none;
            vertical-align: -0.125em;
            text-rendering: optimizelegibility;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }



        .card .detail_box {
            padding: 1rem;
            background-color: #fff;
            flex: 1 1 auto;
        }

        .card .detail_box .title {
            margin-bottom: 7px;
        }

        .detail_box .before-icon {
            display: inline-block;
            width: 3px;
            height: 10px;
            margin-right: 7px;
            background: #18a78c;
            border-radius: 35px;
        }

        .detail_box p {
            margin-bottom: 0.5rem;
        }

        .card .detail_box .detail_html img {
            width: 100%;
            -o-object-fit: fill;
            object-fit: fill;
        }

        .card .footer {
            width: 100%;
            max-width: 1080px;
            background-color: #fff;
            margin-top: 0.6rem;
            display: flex;
            z-index: 2;
            align-items: center;
            padding: 0.5rem 1rem;
            position: fixed;
            bottom: 0;
        }

        .card .footer .buy_btn,
        .Details_details__CQ1ad .footer .keran_btn,
        .Details_details__CQ1ad .footer .terjual_btn {
            flex: 1 1;
            overflow: hidden;
            background: linear-gradient(265.04deg, #38ef7d -164.21%, #11998e 54.98%);
            border: 1px solid transparent;
            height: 2.5rem;
            font-size: 14px;
            font-weight: 700;
            text-align: center;
            border-radius: 5px;
        }

        .card .footer span{
            box-sizing:border-box;
            display:inline-block;
            overflow:hidden;
            width:initial;
            height:initial;
            background:none;
            opacity:1;
            border:0;
            margin:0;
            padding:0;
            position:relative;
            max-width:100%;
        }

        .buy_btn{
            padding: 5px 10px;
        }
    </style>
</head>

<body>

    <div class="main-container w-100 position-relaitve">
        <div class="card position-relative">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="false">
                <div class="carousel-indicators">
                    @if ($product->images != null)
                    @foreach (json_decode($product->images) as $key => $photo)
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="{{ $key }}"
                        class="active" aria-current="true" aria-label="Slide-{{ $key }}"></button>
                    @endforeach
                    @endif
                </div>

                <div class="carousel-inner">
                    @if ($product->images != null)
                    @foreach (json_decode($product->images) as $key => $photo)
                    <div class="carousel-item {{ $key == 0 ? 'active' : '' }}" id="slide-{{ $key }}">
                        <img src="{{asset("storage/product/$photo")}}"
                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                            class="d-block w-100 card-img-top h-100" alt="{{ $product->name }}">
                    </div>
                    @endforeach
                    @else
                    <div class="carousel-item active">
                        <img src="{{asset('public/assets/front-end/img/image-place-holder.png')}}"
                            class="d-block w-100 card-img-top" alt="{{ $product->name }}">
                    </div>
                    @endif
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                    data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <div class="price_box">

                <div class="price">
                    <div class="price-text">Rp. <span>{{ $product->dropship }}</span></div>
                </div>
                <div class="copy_msg"><span class="text">{{ $product->name }}</span></div>
            </div>

            <div class="type_box my-2">
                <div class="title">
                    <div class="before-icon"></div><b>Detail Produk</b>
                </div>
                <div class="details">
                    {{-- <h1 class="h3 mb-2">{{$product->name}}</h1>
                    <div class="d-flex align-items-center mb-2 pro">
                        <span
                            class="d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'ml-md-2 ml-sm-0 pl-2' : 'mr-md-2 mr-sm-0 pr-2'}}">{{$overallRating[0]}}</span>
                        <div class="star-rating">
                            @for($inc=0;$inc<5;$inc++)
                                @if($inc<$overallRating[0])
                                    <i class="sr-star czi-star-filled active"></i>
                                @else
                                    <i class="sr-star czi-star"></i>
                                @endif
                            @endfor
                        </div>
                        <span
                            class="font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-1 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-1 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}}">{{$overallRating[1]}} {{\App\CPU\translate('Reviews')}}</span>
                        <span style="width: 0px;height: 10px;border: 0.5px solid #707070; margin-top: 6px"></span>
                        <span
                            class="font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-1 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-1 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}}">{{$countOrder}} {{\App\CPU\translate('orders')}}   </span>
                        <span style="width: 0px;height: 10px;border: 0.5px solid #707070; margin-top: 6px">    </span>
                        <span
                            class=" font-for-tab d-inline-block font-size-sm text-body align-middle mt-1 {{Session::get('direction') === "rtl" ? 'mr-1 ml-md-2 ml-0 pr-md-2 pr-sm-1 pl-md-2 pl-sm-1' : 'ml-1 mr-md-2 mr-0 pl-md-2 pl-sm-1 pr-md-2 pr-sm-1'}}">  {{$countWishlist}} {{\App\CPU\translate('wish')}} </span>
                    </div>
                    <div class="mb-3">
                        <span
                            class="h3 font-weight-normal text-accent {{Session::get('direction') === "rtl" ? 'ml-1' : 'mr-1'}}">
                            {{\App\CPU\Helpers::get_price_range($product) }}
                        </span>
                        @if($product->discount > 0)
                            <strike style="color: {{$web_config['secondary_color']}};">
                                {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                            </strike>
                        @endif
                    </div>

                    @if($product->discount > 0)
                        <div class="mb-3">
                            <strong>{{\App\CPU\translate('discount')}} : </strong>
                            <strong id="set-discount-amount"></strong>
                        </div>
                    @endif --}}

                    <div class="mb-2 d-flex justify-content-between">
                        <strong>{{\App\CPU\translate('tax')}} : </strong>
                        <strong id="set-tax-amount">Rp. 0</strong>
                    </div>
                    <form id="add-to-cart-form" class="mb-1">
                        @csrf
                        <input type="hidden" name="id" value="{{ $product->id }}">
                            @if (count(json_decode($product->colors)) > 0)
                                <div class="row flex-column flex-start">
                                    <div class="product-description-label">{{\App\CPU\translate('color')}}:
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
                            <div class="d-flex flex-start mx-0 justify-content-between">
                                <div
                                    class="product-description-label d-flex align-items-center {{Session::get('direction') === "rtl" ? 'pl-2' : 'pr-2'}}"><strong>{{ $choice->title }}
                                        :</strong>
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
                            <div class="col-4 d-flex align-items-center">
                                <strong class="product-description-label">{{\App\CPU\translate('Quantity')}}:</strong>
                            </div>
                            <div class="col-8 d-flex justify-content-end">
                                <div class="product-quantity d-flex align-items-center">
                                    <div
                                        class="input-group input-group--style-2 {{Session::get('direction') === "rtl" ? 'pl-3' : 'pr-3'}}"
                                        style="width: 100px;">
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
                                <div class="product-description-label">{{\App\CPU\translate('total_price')}}:</div>
                            </div>
                            <div>
                                <div class="product-price for-total-price">
                                    <strong id="chosen_price"></strong>
                                </div>
                            </div>

                            <div class="col-12">
                                @if($product['current_stock']<=0)
                                    <h5 class="mt-3" style="color: red">{{\App\CPU\translate('out_of_stock')}}</h5>
                                @endif
                            </div>
                        </div>

                        {{-- <div class="d-flex justify-content-between mt-2">
                            <button
                                class="btn btn-secondary element-center btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}"
                                onclick="buy_now()"
                                type="button"
                                style="width:37%; height: 45px">
                                <span class="string-limit">{{\App\CPU\translate('buy_now')}}</span>
                            </button>
                            <button
                                class="btn btn-primary element-center btn-gap-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}"
                                onclick="addToCart()"
                                type="button"
                                style="width:37%; height: 45px">
                                <span class="string-limit">{{\App\CPU\translate('add_to_cart')}}</span>
                            </button>
                            <button type="button" onclick="addWishlist('{{$product['id']}}')"
                                    class="btn btn-dark for-hover-bg"
                                    style="">
                                <i class="fa fa-heart-o {{Session::get('direction') === "rtl" ? 'ml-2' : 'mr-2'}}"
                                   aria-hidden="true"></i>
                                <span class="countWishlist-{{$product['id']}}">{{$countWishlist}}</span>
                            </button>
                        </div> --}}
                    </form>
                </div>
            <div class="detail_box mb-5">
                <div class="title">
                    <div class="before-icon"></div><b>Detail Produk</b>
                </div>
                <div class="detail_html">
                    <div>
                        <p><span style="font-size: medium;">Nama Produk: Rak Pengeringan Botol Bayi Dapat
                                Dibongkar&nbsp;<br>Warna: Hijau, Biru, Pink<br>Ukuran (kotak/packaging): 21.3 x 21 x 4
                                cm<br>Berat: 260g<br><br>Rak pengering dengan 16 batang akar, dapat menggantung
                                perlengkapan bayi yang cukup untuk satu hari.<br><br>Tetesan air tidak akan berceceran
                                kemana-mana, karena ada baki di dasarnya yang akan menampung semua air yang menetes.
                                <br><br>Mengeringkan botol secara alami , tidak akan menjadi tempat berkembang biaknya
                                bakteri, alas yang extra tebal. Rak pengering botol bayi ini didesain agar mudah di
                                bongkar dan mudah untuk mengeluarkan air yang terkumpul di dasarnya.</span></p>
                        {{-- <p>
                            <img src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954954735624.jpg"
                                style="max-width:100%;"><img
                                src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954954792964.jpg"
                                style="max-width: 100%;"><img
                                src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954954825731.jpg"
                                style="max-width: 100%;"><img
                                src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954954866692.jpg"
                                style="max-width: 100%;"><img
                                src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954954899459.jpg"
                                style="max-width: 100%;"><img
                                src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954954932226.jpg"
                                style="max-width: 100%;"><img
                                src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954954969090.jpg"
                                style="max-width: 100%;"><img
                                src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954955001857.jpg"
                                style="max-width: 100%;"><img
                                src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954955046915.jpg"
                                style="max-width: 100%;"><img
                                src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954955091973.jpg"
                                style="max-width: 100%;"><img
                                src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954955128837.jpg"
                                style="max-width: 100%;"><img
                                src="https://grosiraja-production-public.oss-accelerate.aliyuncs.com/operation/notice/712954955165701.jpg"
                                style="max-width: 100%;"><span style="font-size: medium;"><br></span>
                        </p> --}}
                    </div>
                </div>
            </div>
            <div class="position-relative">
                <div class="footer card shadow-sm w-100">
                    <a href="javascript:" type="button" class="btn btn-primary buy_btn">
                        Beli Sekarang
                    </a>
                </div>
            </div>
            <div class="modal fade" id="variant" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h1 class="modal-title fs-5" id="exampleModalLabel">Modal title</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      ...
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>
        </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function(){
            cartQuantityInitialize()
            getVariantPrice();
        });

        $('input[name=quantity]').on('change', function () {
            getVariantPrice();
            console.log('work')
        });

        function getVariantPrice() {
        if ($('input[name=quantity]').val() > 0 && checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: '{{ route('cart.variant_price') }}',
                data: $('#add-to-cart-form').serializeArray(),
                success: function (data) {
                    console.log('pressed',data)
                    $('#add-to-cart-form #chosen_price_div').removeClass('d-none');
                    $('#add-to-cart-form #chosen_price_div #chosen_price').html(data.price);
                    $('#set-tax-amount').html(data.tax);
                    $('#set-discount-amount').html(data.discount);
                    $('#available-quantity').html(data.quantity);
                    $('.cart-qty-field').attr('max', data.quantity);
                }
            });
        }
    }

    function checkAddToCartValidity() {
        var names = {};
        $('#add-to-cart-form input:radio').each(function () { // find unique names
            names[$(this).attr('name')] = true;
        });
        var count = 0;
        $.each(names, function () { // then count them
            count++;
        });
        if ($('input:radio:checked').length == count) {
            return true;
        }
        return false;
    }


        function cartQuantityInitialize() {
            console.log('pressed')
            $('.btn-number').click(function (e) {
                e.preventDefault();

                fieldName = $(this).attr('data-field');
                type = $(this).attr('data-type');
                var input = $("input[name='" + fieldName + "']");
                var currentVal = parseInt(input.val());

                if (!isNaN(currentVal)) {
                    if (type == 'minus') {

                        if (currentVal > input.attr('min')) {
                            input.val(currentVal - 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('min')) {
                            $(this).attr('disabled', true);
                        }

                    } else if (type == 'plus') {

                        if (currentVal < input.attr('max')) {
                            input.val(currentVal + 1).change();
                        }
                        if (parseInt(input.val()) == input.attr('max')) {
                            $(this).attr('disabled', true);
                        }

                    }
                } else {
                    input.val(0);
                }
            });

            $('.input-number').focusin(function () {
                $(this).data('oldValue', $(this).val());
            });

            $('.input-number').change(function () {

                minValue = parseInt($(this).attr('min'));
                maxValue = parseInt($(this).attr('max'));
                valueCurrent = parseInt($(this).val());

                var name = $(this).attr('name');
                if (valueCurrent >= minValue) {
                    $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: '{{\App\CPU\translate('Sorry, the minimum value was reached')}}'
                    });
                    $(this).val($(this).data('oldValue'));
                }
                if (valueCurrent <= maxValue) {
                    $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Cart',
                        text: '{{\App\CPU\translate('Sorry, stock limit exceeded')}}.'
                    });
                    $(this).val($(this).data('oldValue'));
                }


            });
            $(".input-number").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                    // Allow: Ctrl+A
                    (e.keyCode == 65 && e.ctrlKey === true) ||
                    // Allow: home, end, left, right
                    (e.keyCode >= 35 && e.keyCode <= 39)) {
                    // let it happen, don't do anything
                    return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        }
    </script>
</body>

</html>
