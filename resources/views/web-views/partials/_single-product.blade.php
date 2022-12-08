<style>
    .discount-hed{
        margin-top: 0;
    }
    span.for-discoutn-value{
        padding: 5px 10px;
        font-size: 14px;
        font-weight: 600;
        text-transform: capitalize;
        border-radius: 0px 10px 0 15px;
    }
    .new-discoutn-value{
        background-color: {{ $web_config['secondary_color'] }};
        border-radius: 15px;
        font-size: 13px;
        color: #fff;
        font-weight: 600;
        padding: 2px 10px;
    }
    .discount-div {
        margin: 5px 0;
    }
    .strike-price {
        font-size: 14px !important;
        color: grey!important;
    }
    .product-item {
        margin-right: 0;
    }
    .product-card {
        margin-bottom: 40px;
        display: flex;
        max-width: 191.9px;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        box-shadow: 2px 2px 3px #00000030 !important;
    }

    .product-card.card.stock-card label {
            left: 29%;
            top: 29% !important;
        }
    .card-header {
        cursor: pointer;
        max-height: 193px;
        min-height: 193px;
        padding: 0px;
        margin-bottom: 10px;
        border-radius: 10px 10px 0 0 !important;
    }

    .product-title1{
        text-transform: capitalize;
    }

    .product-price {
        margin-top: -6px;
    }

    .card-body {
        cursor: pointer;
        max-height: 5.5rem;
        min-height: 5.5rem;
        margin-bottom: 23px;
    }
    .card-body.inline_product {
        margin-bottom: 40px;
        position: relative;
    }
    .card-body-hidden {
        padding-bottom: 5px!important;
        min-height: 23px !important;
        box-shadow: -2px -3px 14px -9px rgb(0 0 0 / 75%) inset;
            -webkit-box-shadow: -2px -3px 14px -9px rgb(0 0 0 / 75%) inset
            -moz-box-shadow: -2px -3px 14px -9px rgb(0 0 0 / 75%) inset;
    }
    .center-div a img {
        min-width:191.9px;
        width: 100%;
        min-height: 191.9px;
        max-height: 191.9px !important;
        border-radius: 10px 10px 0 0;
    }

        @media(max-width: 373px){
        .discount-hed{
            right: 7px !important;
        }
    }
    @media (max-width: 600px) {
        span.for-discoutn-value{
            padding: 2px 9px;
            font-size: 12px;
            font-weight: 500;
            border-radius: 0px 5px 0 15px;
            z-index: 1;
        }
        .strike-price {
        font-size: 12px !important;
    }
        .new-discoutn-value {
            font-size: 10px;
        }

        .discount-div {
            margin: 0;
            margin-top: -5px;
        }
        .product-item {
            margin-right: 5px;
        }
        .card-header {
            max-height: 130px;
            min-height: 130px;
            margin-bottom: 5px;
        }

        .card-body {
            margin-top: -9px;
            max-height: 2.5rem;
            min-height: 2.5rem;
        }

        .center-div {
            position: relative;
        }
        .center-div a img {
            min-width: 100%;
            max-height: 100% !important;
            min-height: 100%;
            border-radius: 5px 5px 0 0;
        }

        .product-card.card {
            margin-bottom: 0px !important;
            display: block;
            align-items: start;
            justify-content: start;
            box-shadow: 2px 17px 3px #00000030 !important;
        }

        .product-card.card {
            border-radius: 5px 5px;
        }

        .product-card.card.stock-card label {
            left: 16%;
            top: 20% !important;
        }

        .product-card.card .card-body-hidden {
            visibility: visible !important;
            opacity: 1 !important;
            margin-top: -14px;
            padding: 0 5px;
            padding-top: 0;
            z-index: 2;
        }

        .product-card.card .card-body-hidden .text-center a{
            font-size:10px;
        }

        .product-title1 a{
            font-size: 12px;
        }

        .product-price {
            margin-top: -10px;
        }

        .product-price .text-accent {
            font-size: 14px !important;
        }
    }
</style>
@php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))
<div class="product-card card {{$product['current_stock']==0?'stock-card':''}}">
    {{-- {{ dd($product) }} --}}
        @if($product['current_stock']<=0)
            <label class="badge badge-danger stock-out">{{\App\CPU\translate('stock_out')}}</label>
        @endif
{{-- {{ dd($product) }} --}}
        <div class="card-header inline_product clickable">
            @if ($product->icon != NULL)
            <div class="ketupat">
                <img src="{{\App\CPU\ProductManager::product_image_path('icon')}}/{{$product['icon']}}" alt="">
            </div>
            @endif
            @if(isset($product->label))
            <div class="d-flex justify-content-end for-dicount-div discount-hed" style="right: 0;position: absolute">
                <span class="for-discoutn-value">
                    {{ $product->label }}
                </span>
            </div>
            @else
            <div class="d-flex justify-content-end for-dicount-div-null">
                <span class="for-discoutn-value-null"></span>
            </div>
            @endif
            <div class="center-div element-center" style="cursor: pointer">
                <a href="{{route('product',$product->slug)}}">
                    <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'">
                </a>
            </div>
        </div>
        @php($user_is = session()->get('user_is'))
        <div class="card-body inline_product text-center p-1 clickable">
            <div style="position: relative;" class="product-title1">
                <a href="{{route('product',$product->slug)}}">
                    {{ Str::limit($product['name'], 25) }}
                </a>
            </div>
            <div class="justify-content-between text-center">
                <div class="product-price text-center">
                    @if($product->discount > 0)
                    <strike class="strike-price">
                        @if ($user_is == 'dropship')
                        {{\App\CPU\Helpers::currency_converter($product->dropship)}}
                        @else
                        {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                        @endif
                    </strike><br>
                    @endif
                    @if($product->discount > 0)
                    <div class="text-center discount-div" style="">
                        <span class="new-discoutn-value">
                            {{\App\CPU\translate('off')}}
                            @if ($product->discount_type == 'percent')
                            {{round($product->discount,2)}}%
                            @elseif($product->discount_type =='flat')
                            {{\App\CPU\Helpers::currency_converter($product->discount)}}
                            @endif

                        </span>
                    </div>
                    @else
                    <div class="d-flex justify-content-end for-dicount-div-null">
                        <span class="for-discoutn-value-null"></span>
                    </div>
                    @endif
                    <span class="text-accent">
                        @if ($user_is == 'dropship')
                        {{\App\CPU\Helpers::currency_converter($product->dropship-(\App\CPU\Helpers::get_product_discount($product,$product->dropship)))}}
                        @else
                        {{\App\CPU\Helpers::currency_converter($product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price)))}}
                        @endif
                    </span>
                </div>
            </div>
        </div>
        {{-- <div class="d-flex justify-content-left w-100" style="position: absolute;bottom: 11px;left: 15px;z-index: 2;">
            <div class="flag">
                <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                    src="{{asset('public/assets/front-end')}}/img/flags/{{ strtolower($product['country'])  }}.png"
                    alt="Eng">
            </div>
           @php($c_name = App\Country::where('country', $product['country'])->get())
            <span style="font-size: 13px; color: #616166; line-height: 1.6;">{{ $c_name[0]->country_name }}</span>
        </div> --}}

        <div class="card-body card-body-hidden">
            <div class="text-center">
                @if(Request::is('product/*'))
                <a class="btn btn-primary btn-sm btn-block mb-2" href="{{route('product',$product->slug)}}">
                    <i class="czi-forward align-middle {{Session::get('direction') === " rtl" ? 'ml-1' : 'mr-1' }}"></i>
                    {{\App\CPU\translate('View')}}
                </a>
                @else
                <a class="btn btn-primary btn-sm btn-block mb-2" href="javascript:"
                onclick="addCart({{ $product->id }})">
                    <i style="font-weight: 900;font-size: 9px; margin-top: -1px;" class="czi-add align-middle {{Session::get('direction') === " rtl" ? 'ml-1' : 'mr-1' }}"></i>
                    Keranjang
                </a>
                @endif
            </div>
        </div>
</div>
@push('script')
<script>
    function addCart(val) {
        if (checkAddToCartValidity()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.post({
                url: '/cart/add',
                data: {'id' : val, 'quantity': 1},
                beforeSend: function () {
                    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
                        $('#loading').addClass('loading-mobile');
                    }
                    $('#loading').show();
                },
                success: function (response) {
                    // console.log(response);
                    if (response.status == 1) {
                        updateNavCart();
                        toastr.success(response.message, {
                            CloseButton: true,
                            ProgressBar: true
                        });
                        $('.call-when-done').click();
                        return false;
                    } else if (response.status == 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Cart',
                            text: response.message
                        });
                        return false;
                    }
                },
                complete: function () {
                    $('#loading').hide();
                    if(/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)){
                        $('#loading').removeClass('loading-mobile');
                    }
                }
            });
        } else {
            Swal.fire({
                type: 'info',
                title: 'Cart',
                text: 'Please choose all the options'
            });
        }
    }

</script>
@endpush
