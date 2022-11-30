@php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))
<div class="flash_deal_product rtl" style="cursor: pointer;"
     onclick="location.href='{{route('product',$product->slug)}}'">
    @if($product->discount > 0)
        <div class="discount-top-f">
            <span class="for-discoutn-value pl-1 pr-1">
                @if ($product->discount_type == 'percent')
                    {{round($product->discount)}}%
                @elseif($product->discount_type =='flat')
                    {{\App\CPU\Helpers::currency_converter($product->discount)}}
                @endif {{\App\CPU\translate('off')}}
            </span>
        </div>
    @endif
    <div class=" d-flex">
        <div class="d-flex align-items-center justify-content-center"
             style="min-width: 110px">
            <img style="height: 130px!important;"
                 src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                 onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"/>
        </div>
        <div class="flash_deal_product_details pl-2 pr-1 d-flex align-items-center">
            <div>
                <h6 class="flash-product-title">
                    {{$product['name']}}
                </h6>
                <div class="flash-product-price">
                    {{\App\CPU\Helpers::currency_converter($product->unit_price-\App\CPU\Helpers::get_product_discount($product,$product->unit_price))}}
                    @if($product->discount > 0)
                        <strike
                            style="font-size: 12px!important;color: grey!important;">
                            {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                        </strike>
                    @endif
                </div>
                <h6 class="flash-product-review">
                    @for($inc=0;$inc<5;$inc++)
                        @if($inc<$overallRating[0])
                            <i class="sr-star czi-star-filled active"></i>
                        @else
                            <i class="sr-star czi-star"></i>
                        @endif
                    @endfor
                    <label class="badge-style2">
                        ( {{$product->reviews->count()}} )
                    </label>
                </h6>
            </div>
        </div>
    </div>
</div>
