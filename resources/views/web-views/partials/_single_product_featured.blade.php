{{-- _single_product_featured.blade --}}

@php($overallRating = \App\CPU\ProductManager::get_overall_rating($product->reviews))

<div class="product-card card pb-3 {{$product['current_stock']==0?'stock-card':''}}"
    style="margin-bottom: 40px; box-shadow: none;">
    @if($product['current_stock']<=0) <label style="left: 29%!important; top: 29%!important;"
        class="badge badge-danger stock-out">Stock Out</label>
        @endif

        <div class="card-header inline_product clickable" style="cursor: pointer;max-height: 135px;min-height: 127px">
            @if($product->discount > 0)
            <div class="d-flex justify-content-end for-dicount-div discount-hed" style="right: 0;position: absolute">
                <span class="for-discoutn-value">
                    @if ($product->discount_type == 'percent')
                    {{round($product->discount,2)}}%
                    @elseif($product->discount_type =='flat')
                    {{\App\CPU\Helpers::currency_converter($product->discount)}}
                    @endif
                    OFF
                </span>
            </div>
            @else
            <div class="d-flex justify-content-end for-dicount-div-null">
                <span class="for-discoutn-value-null"></span>
            </div>
            @endif
            <div class="d-flex align-items-center justify-content-center d-block">
                <a href="{{route('product',$product->slug)}}">
                    <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                        onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                        style="width: 100%;max-height: 220px">
                </a>
            </div>
        </div>

        <div class="card-body inline_product text-center p-1 clickable"
            style="cursor: pointer; height:5.5rem; max-height: 5.5rem">
            <div class="rating-show">
                <span class="d-inline-block font-size-sm text-body">
                    @for($inc=0;$inc<5;$inc++) @if($inc<$overallRating[0]) <i class="sr-star czi-star-filled active">
                        </i>
                        @else
                        <i class="sr-star czi-star"></i>
                        @endif
                        @endfor
                        <label class="badge-style">( {{$product->reviews()->count()}} )</label>
                </span>
            </div>
            <div style="position: relative;" class="product-title1">
                <a href="{{route('product',$product->slug)}}">
                    {{ Str::limit($product['name'], 30) }}
                </a>
            </div>
            <div class="justify-content-between text-center">
                <div class="product-price text-center">
                    @if($product->discount > 0)
                    <strike style="font-size: 12px!important;color: grey!important;">
                        {{\App\CPU\Helpers::currency_converter($product->unit_price)}}
                    </strike><br>
                    @endif
                    <span class="text-accent">
                        {{\App\CPU\Helpers::currency_converter(
                        $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                        )}}
                    </span>
                </div>
            </div>

        </div>
        <div class="d-flex justify-content-start w-100" style="position: absolute;bottom: 0px;left: 5px;z-index: 1;">
            <div class="flag">
                <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                    src="{{asset('public/assets/front-end')}}/img/flags/{{ strtolower($product->country)  }}.png"
                    alt="Eng">
            </div>
            @php($c_name = App\Country::where('country', $product->country)->get())
            <span style="font-size: 13px; color: #616166; line-height: 1.6;">{{ $c_name[0]->country_name }}</span>
        </div>

        <div class="card-body card-body-hidden" style="padding-bottom: 5px!important;">
            <div class="text-center">
                @if(Request::is('product/*'))
                <a class="btn btn-primary btn-sm btn-block mb-2" href="{{route('product',$product->slug)}}"
                    style="padding: 0.425rem 0.3rem; font-size: .7125rem;">
                    <i class="czi-forward align-middle {{Session::get('direction') === " rtl" ? 'ml-1' : 'mr-1' }}"></i>
                    {{\App\CPU\translate('View')}}
                </a>
                @else
                <a class="btn btn-primary btn-sm btn-block mb-2" href="javascript:"
                    onclick="quickView('{{$product->id}}')" style="padding: 0.425rem 0.3rem; font-size: .7125rem;">
                    <i class="czi-eye align-middle {{Session::get('direction') === " rtl" ? 'ml-1' : 'mr-1' }}"></i>
                    {{\App\CPU\translate('Quick')}} {{\App\CPU\translate('View')}}
                </a>
                @endif
            </div>
        </div>
</div>
