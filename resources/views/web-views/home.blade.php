{{-- home.blade --}}

@extends('layouts.front-end.app')

@section('title','Welcome To '. $web_config['name']->value.' Home')

@push('css_or_js')
<meta property="og:image" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="og:title" content="Welcome To {{$web_config['name']->value}} Home" />
<meta property="og:url" content="{{env('APP_URL')}}">
<meta property="og:description" content="{!! substr($web_config['about']->value,0,100) !!}">

<meta property="twitter:card" content="{{asset('storage/company')}}/{{$web_config['web_logo']->value}}" />
<meta property="twitter:title" content="Welcome To {{$web_config['name']->value}} Home" />
<meta property="twitter:url" content="{{env('APP_URL')}}">
<meta property="twitter:description" content="{!! substr($web_config['about']->value,0,100) !!}">

<link rel="stylesheet" href="{{asset('public/assets/front-end')}}/css/home.css" />

<style>
.bg-transparent {
    margin-top: 15px;
}

.div-flash {
  position: relative;
  width: 100%; /* The size you want */
}
.div-flash:after {
  content: "";
  display: block;
  padding-bottom: 100%; /* The padding depends on the width, not on the height, so with a padding-bottom of 100% you will get a square */
}

.div-flash img {
  position: absolute; /* Take your picture out of the flow */
  top: 0;
  bottom: 0;
  left: 0;
  right: 0; /* Make the picture taking the size of it's parent */
  width: 100%; /* This if for the object-fit */
  height: 100%; /* This if for the object-fit */
  object-fit: cover; /* Equivalent of the background-size: cover; of a background-image */
  object-position: center;
}

a .footer_banner_img {
    height: 200px !important;
}

.product-item {
        margin-right: 15px;
    }

.section-header .feature_header span {
    background-color: transparent !important;
    }
    .view-all .btn-outline-accent {
        border: none;
        /* margin-top: 0; */
        color: {{ $web_config['primary_color'] }} !important;
    }

    .view-all .btn-outline-accent:hover{
        background-color: transparent !important;
        color: #000 !important;
    }

    .view-all .btn-outline-accent .czi-arrow-right{
        display: none;
    }

  .media {
    background: white;
  }

  .brand_div img {
    max-height: 98px;
    max-width: 98px;
  }

  .section-header {
    display: flex;
    justify-content: space-between;
  }
.timer {
    margin-top: 8px;
    /* margin-left: 10px; */
}
  .cz-countdown-days {
            color: white !important;
            background-color: {{$web_config['primary_color']}};
            padding: 0px 6px;
            border-radius: 3px;
            margin-right: 3px !important;
        }

        .cz-countdown-hours {
            color: white !important;
            background-color: {{$web_config['primary_color']}};
            padding: 0px 6px;
            border-radius: 3px;
            margin-right: 3px !important;
        }

        .cz-countdown-minutes {
            color: white !important;
            background-color: {{$web_config['primary_color']}};
            padding: 0px 6px;
            border-radius: 3px;
            margin-right: 3px !important;
        }

        .cz-countdown-seconds {
            color: {{$web_config['primary_color']}};
            border: 1px solid{{$web_config['primary_color']}};
            padding: 0px 6px;
            border-radius: 3px !important;
        }
  .flash_deal_product_details .flash-product-price {
    font-weight: 700;
    font-size: 18px;

    color: {
        {
        $web_config['primary_color']
      }
    }

    ;
  }

  .featured_deal_left {
    height: 130px;
    background: {{$web_config['primary_color']}} 0% 0% no-repeat padding-box;
    padding: 10px 100px;
    text-align: center;
  }

  .featured_deal {
    min-height: 130px;

  }

  .brand_div {
    background-color: transparent !important;
    height: 98px;
  }

  .category_div {
        max-height: 132px !important;
        height: 132px;
        width: 100%;
        background-color: transparent !important;
        border: none !important;
    }

  .category_div:hover {
    color: {{$web_config['secondary_color']}};
  }

  .cat-link img {
    vertical-align: middle;
    padding: 16%;
    height: 98px;
  }

  .cat-link p {
    margin-top: 0px;
    font-size: 15px;
    display: inline-block;
    text-transform: capitalize;
    /* white-space: nowrap; */
    width: 100%;
  }

    .deal_of_the_day {
        opacity: .8;
        background: {{$web_config['secondary_color']}};
        border-radius: 3px;
    }

  .deal-title {
    font-size: 12px;

  }

  .for-flash-deal-img img {
    max-width: none;
  }


  @media (max-width: 360px) {

.featured_for_mobile {
  max-width: 100%;
  margin-top: 10px;
  margin-bottom: 10px;
}

.featured_deal {
  opacity: 1 !important;
}
}

  @media (max-width: 375px) {
    .cz-countdown {
      display: flex !important;

    }

    .cz-countdown .cz-countdown-seconds {

      margin-top: -5px !important;
    }

    .for-feature-title {
      font-size: 20px !important;
    }

    .category_div .cat-link img {
        padding: 8px !important;
    }

    .featured_for_mobile {
        max-width: 100%;
        margin-top: 10px;
        margin-bottom: 10px;
    }

    .featured_deal {
        opacity: 1 !important;
    }

    .row.banner-wrapper .banner-item {
        max-width: 75vw;
    }

  }

  @media (max-width: 600px) {
    .cat-owl .owl-stage-outer .owl-item {
        height: 85px;
    }

    .category_div .cat-link img {
        padding: 5px;
        margin-bottom: -10px;
    }

    .flash_deal_title {
        font-size: 20px
    }
    .section-header.fd{
        border-bottom: none !important;
    }

    /* category */
    .cat-owl .owl-stage-outer {
        overflow: auto !important;
        height: 100px;
    }

    .banner-wrapper .banner-item {
        max-width: 65vw;
    }

    .owl-stage-outer::-webkit-scrollbar {
        display: none !important;
    }
    .category_div {
        max-height: 95px !important;
        height: 95px;
        width: 100%
    }
    .cat-header span{
        font-size: 20px !important
    }
    .cat-link img {
        height: auto;
        padding: 16% !important;
    }
    .cat-link p {
        font-size: 12px;
        text-transform: capitalize;
        margin-bottom: 0;
    }

    /* brand */
  .brand-slider {
    margin-top: 0 !important;
  }
  .brand_div{
      height: 70px;
  }

  .brand_div img{
      max-height: 70px;
      max-width: 70px;
  }

  body > div.bod > section.banner > div > div.row.mt-1justify-content-center.banner-wrapper > div:nth-child(1){
        margin-left: -1vw;
    }
    .banner-wrapper {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        margin-left: -14px;
    }

    .banner-wrapper::-webkit-scrollbar {
        display: none !important;
    }
    .banner-item {
        flex: 0 0 auto;
        max-width: 75vw;
    }

    .product-wrapper {
        display: flex;
        flex-wrap: nowrap;
        height: 268px;
        overflow-x: auto;
    }
    .product-wrapper::-webkit-scrollbar {
        display: none !important;
    }
    .product-item {
        flex: 0 0 auto;
        max-width: 140px;
        min-width: 140px;
        margin-right: 0;
        max-height: 242px;
    }

    .section-header .feature_header span {
        font-size: 14px !important;
        background-color: transparent !important;
        line-height: 2;
    }

    .flash_deal_title {
        font-size: 14px !important;
    }

    .footer_banner_img {
        min-height: 130px;
        max-height: 130px;

    }
    .flash_deal_title {
      font-weight: 600;
      font-size: 18px;
      text-transform: uppercase;
    }

    .view-btn-div-f {
        margin-top: 0 !important;
    }

    .counter {
        padding-left: 0 !important;
        margin-left: -10px !important;
    }

    .counter .cz-countdown .cz-countdown-value {
      font-family: "Roboto", sans-serif;
      font-size: 12px !important;
      font-weight: 700 !important;
    }

    .view-all .btn-outline-accent {
        border: none;
        margin-top: 0;
        color: {{ $web_config['primary_color'] }} !important;
    }

    .featured_deal {
      opacity: 1 !important;
    }

    .cz-countdown {
      display: inline-block;
      flex-wrap: wrap;
      font-weight: normal;
      margin-top: 4px;
      font-size: smaller;
    }

    .view-btn-div-f {

      margin-top: 6px;
      float: right;
    }

    .view-btn-div {
      float: right;
    }

    .viw-btn-a {
      font-size: 10px;
      font-weight: 600;
    }


    .for-mobile {
      display: none;
    }

    .featured_for_mobile {
      max-width: 100%;
      margin-top: 20px;
      margin-bottom: 20px;
    }
  }

  @media(max-width: 500px) {
    .owl-carousel .owl-dots .owl-dot span {
        width: 5px !important;
        height: 5px !important;
    }
    .owl-carousel .owl-dots {
        margin-top: -10px !important;
    }
  }

  @media (min-width: 768px) {
    .displayTab {
      display: block !important;
    }
  }

  @media (max-width: 800px) {
    .for-tab-view-img {
      width: 40%;
    }

    .for-tab-view-img {
      width: 105px;
    }

    .widget-title {
      font-size: 19px !important;
    }
  }

  .featured_deal_carosel .carousel-inner {
    width: 100% !important;
  }

  .badge-style2 {
    color: black !important;
    background: transparent !important;
    font-size: 11px;
  }
  @media (max-width: 992px) {
    .navbar-collapse {
      position: fixed;
      top: 69px;
      left: 0;
      padding-left: 15px;
      padding-right: 15px;
      padding-bottom: 15px;
      width: 89%;
      height: 100%;
      z-index: 99;
    }

    .navbar-collapse.collapsing {
      left: -75%;
      transition: height 0s ease;
    }

    .navbar-collapse.show {
      left: 0;
      background-color: #fff;
      transition: left 300ms ease-in-out;
    }

    .navbar-toggler.collapsed~.navbar-collapse {
      transition: left 500ms ease-in-out;
    }

    @media (max-width:768px) {
      .nav-item.dropdown.ml-auto {
        margin-left: 0px !important;
    }

      .timer {
        margin: 0 auto;
        margin-top: 8px
      }

      .timer .view_all .px-2 .cz-countdown {
        margin-left: 0 !important;
      }
    }
  }


</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
  integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
  integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw=="
  crossorigin="anonymous" referrerpolicy="no-referrer" />

@endpush

@section('content')
<!-- Hero (Banners + Slider)-->
<section class="bg-transparent mb-1">
  <div class="container">
    <div class="row ">
      <div class="col-12">
        @include('web-views.partials._home-top-slider')
      </div>
    </div>
  </div>
</section>

  {{--categries--}}
    <section class="container rtl">
        <!-- Heading-->
        {{-- <div class="section-header">
            <div class="feature_header cat-header">
                <span>{{ \App\CPU\translate('categories')}}</span>
            </div>
            <div class="view-all">
                <a class="btn btn-outline-accent btn-sm viw-btn-a"
                   href="{{route('categories')}}">{{ \App\CPU\translate('view_all')}}
                    <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'}}"></i>
                </a>
            </div>
        </div> --}}

        <div class="mt-2 brand-slider">
            {{-- @include('web-views.partials._category') --}}
            <div class="owl-carousel cat_wrapper owl-theme cat-owl" id="category-slider">
                @foreach($categories as $category)
                <div class="category_div" style="">
                    <a href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}" class="cat-link">
                        <img style=""
                             onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                             src="{{asset("storage/category/$category->icon")}}"
                             alt="{{$category->name}}">
                             <p class="text-center">{{Str::limit($category->name, 17)}}</p>
                    </a>
                </div>
            @endforeach
            </div>
        </div>
    </section>

{{--flash deal--}}
@php($flash_deals=\App\Model\FlashDeal::with(['products.product.reviews'])->where(['status'=>1])->where(['deal_type'=>'flash_deal'])->whereDate('start_date','
<=',date('Y-m-d'))->whereDate('end_date','>=',date('Y-m-d'))->first())

  @if (isset($flash_deals))
  <div class="container mb-1">
    <div class="row">
      <div class="col-md-12">
        <div class="section-header mb-2 fd rtl row justify-content-between">
          <div class="col-md-2 col-3 mt-2" style="padding-{{Session::get('direction') === " rtl" ? 'right' : 'left' }}: 0">
            <div class="d-inline-flex displayTab">
              <span class="flash_deal_title ">
                {{$flash_deals['title']}}
              </span>
            </div>
          </div>
          <div class="col-lg-10 col-md-8 col-sm-10 col-9 timer" style="padding-{{Session::get('direction') === " rtl"
            ? 'left' : 'right' }}: 0">
            <div class="view_all view-btn-div-f w-100" style="justify-content: space-between !important">
              <div class="px-2 counter">
                <span class="cz-countdown" style="margin-left: -6vw;"
                  data-countdown="{{isset($flash_deals)?date('m/d/Y',strtotime($flash_deals['end_date'])):''}} 11:59:00 PM">
                  <span class="cz-countdown-days">
                    <span class="cz-countdown-value"></span>
                  </span>
                  <span class="cz-countdown-value">:</span>
                  <span class="cz-countdown-hours">
                    <span class="cz-countdown-value"></span>
                  </span>
                  <span class="cz-countdown-value">:</span>
                  <span class="cz-countdown-minutes">
                    <span class="cz-countdown-value"></span>
                  </span>
                  <span class="cz-countdown-value">:</span>
                  <span class="cz-countdown-seconds">
                    <span class="cz-countdown-value"></span>
                  </span>
                </span>
              </div>
              <div class="view-all">
                <a class="btn btn-outline-accent btn-sm viw-btn-a"
                  href="{{route('flash-deals',[isset($flash_deals)?$flash_deals['id']:0])}}">{{
                  \App\CPU\translate('view_all')}}
                  <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1'
                    }}"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
        @include('web-views.partials._flash-deal')
      </div>
    </div>
  </div>
  @endif

  <!-- Latest Product -->
    <section class="container rtl">
        <!-- Heading-->
        <div class="section-header">
            <div class="feature_header">
                <span class="for-feature-title">{{ App\CPU\Translate('Latest_Product') }}</span>
            </div>
            {{-- <div class="view-all">
                <a class="btn btn-outline-accent btn-sm viw-btn-a"
                href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                {{ \App\CPU\translate('view_all')}}
                <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1' }}"></i>
                </a>
            </div> --}}
            </div>

        <div class="row product-wrapper  mt-2">
            @foreach($latest_products as $key=>$product)
                @if($key<12)
                    <div class="product-item px-0  col-xl-2 col-sm-3 col-4 h-100" style="margin-bottom: 10px">
                        @if (empty($country))
                        @include('web-views.partials._single-product',['product'=>$product])
                        @else
                        @if($product['country'] == $country)
                        {{-- {{ dd($product['country']) }} --}}
                        @include('web-views.partials._single-product',['product'=>$product])
                        @else
                        <div id="empty" class="empty"></div>
                        @endif
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    </section>

    <!-- Banner Bardi -->
    @php($unggulan=\App\Model\FlashDeal::with(['products.product.reviews'])->where(['status'=>1])->where(['deal_type'=>'unggulan'])->first())
    @if ($unggulan)
    <div class="container mb-1">
        @include('web-views.partials._banner_bardy')
    </div>
    @endif

    {{-- small banner --}}
    {{-- <section class="banner mt-2">
        <div class="container mb-1">
        <div class="section-header">
            <div class="feature_header cat-header">
                <span>{{ \App\CPU\translate('Penawaran Kami')}}</span>
            </div>
            <div class="view-all">
            </div>
        </div>
         <div class="row mt-1 banner-wrapper">
             @foreach(\App\Model\Banner::where('banner_type','Footer Banner')->where('published',1)->orderBy('id','desc')->take(3)->get() as $banner)
                 <div class="col-md-4 col-12 h-100 w-100 banner-item">
                     <a href="{{$banner->url}}"
                        style="cursor: pointer;" class="w-100 h-100">
                         <img class="d-block footer_banner_img w-100 h-100"
                              onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                              src="{{asset('storage/banner')}}/{{$banner['photo']}}">
                     </a>
                 </div>
                 <div class="modal fade" id="quick_banner{{$banner->id}}" tabindex="-1"
                      role="dialog" aria-labelledby="exampleModalLongTitle"
                      aria-hidden="true">
                     <div class="modal-dialog modal-lg" role="document">
                         <div class="modal-content">
                             <div class="modal-header">
                                 <p class="modal-title"
                                    id="exampleModalLongTitle">{{ \App\CPU\translate('banner_photo')}}</p>
                                 <button type="button" class="close" data-dismiss="modal"
                                         aria-label="Close">
                                     <span aria-hidden="true">&times;</span>
                                 </button>
                             </div>
                             <div class="modal-body">
                                 <img class="d-block mx-auto"
                                      onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                      src="{{asset('storage/banner')}}/{{$banner['photo']}}">
                                 @if ($banner->url!="")
                                     <div class="text-center mt-2">
                                         <a href="{{$banner->url}}"
                                            class="btn btn-outline-accent">{{\App\CPU\translate('Explore')}} {{\App\CPU\translate('Now')}}</a>
                                     </div>
                                 @endif
                             </div>
                         </div>
                     </div>
                 </div>
             @endforeach
         </div>
        </div>
     </section> --}}

  {{--featured deal--}}
  @php($featured_deals=\App\Model\FlashDeal::with(['products.product.reviews'])->where(['status'=>1])->where(['deal_type'=>'feature_deal'])->first())

  @if(isset($featured_deals))
  <section class="container">
    <div class="row">
      <div class="col-xl-12">
        <div class="featured_deal">
          <div class="row">
            <div class="col-xl-3 col-md-4 rtl">
              <div class="d-flex align-items-center justify-content-center featured_deal_left">
                <h1 class="featured_deal_title">{{ \App\CPU\translate('featured_deal')}}</h1>
              </div>
            </div>
            <div class="col-xl-9 col-md-8">
              <div class="owl-carousel owl-theme" id="web-feature-deal-slider">
                @foreach($featured_deals->products as $key=>$product)
                @php($product=$product->product)
                <div class="d-flex  align-items-center justify-content-center"
                  style="height: 129px;border: 1px solid #c5bfbf54;border-radius: 5px; background: white">
                  <div class="featured_deal_product d-flex align-items-center justify-content-between">
                    <div class="row">
                      <div class="col-4">
                        <div class="featured_product_pic mt-3" style=" text-align: center;">
                          <a href="{{route('product',$product->slug)}}" class="image_center">
                            <img
                              src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$product['thumbnail']}}"
                              onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                              class="d-block w-100" alt="...">
                          </a>
                        </div>
                      </div>
                      <div class="col-8">
                        <div class="featured_product_details">
                          <h3 class="featured_product-title mt-2">
                            <a class="ptr" href="{{route('product',$product->slug)}}">
                              {{$product['name']}}
                            </a>
                          </h3>
                          <div class="featured_product-price">
                            <span class="text-accent ptp">
                              {{\App\CPU\Helpers::currency_converter(
                              $product->unit_price-(\App\CPU\Helpers::get_product_discount($product,$product->unit_price))
                              )}}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  @endif

  {{-- Categorized product --}}
  @foreach($home_categories as $category)
  @if(App\CPU\CategoryManager::products($category['id'])->count()>0)
  <section class="container rtl">
    <!-- Heading-->
    <div class="section-header">
      <div class="feature_header">
        <span class="for-feature-title">{{$category['name']}}</span>
      </div>
      <div class="view-all">
        <a class="btn btn-outline-accent btn-sm viw-btn-a"
          href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
          {{ \App\CPU\translate('view_all')}}
          <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1' }}"></i>
        </a>
      </div>
    </div>

    <div class="row product-wrapper  mt-2">
            @foreach(\App\CPU\CategoryManager::products($category['id']) as $key=>$product)
            @if($key<12) <div class="product-item px-0  col-xl-2 col-sm-3 col-4 h-100" style="margin-bottom: 10px">
                @if (empty($country))
                @include('web-views.partials._single-product',['product'=>$product])
                @else
                @if($product['country'] == $country)
                {{-- {{ dd($product['country']) }} --}}
                @include('web-views.partials._single-product',['product'=>$product])
                @else
                <div id="empty" class="empty"></div>
                @endif
                @endif
        </div>
    @endif
    @endforeach
    </div>
  </section>
  @endif
  @endforeach
  @include('web-views.partials._floating')
  @include('layouts.front-end.partials._mobile_footer')
  @endsection

  @push('script')
  {{-- Owl Carousel --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
    integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <script>
    $('#flash-deal-slider').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 20,
            nav: false,
            //navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: false,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 2
                },
                375: {
                    items: 2
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 2
                },
                //Medium
                768: {
                    items: 2
                },
                //Large
                992: {
                    items: 4
                },
                //Extra large
                1200: {
                    items: 5
                },
                //Extra extra large
                1400: {
                    items: 5
                }
            }
        })

        $('#web-feature-deal-slider').owlCarousel({
            loop: true,
            autoplay: true,
            margin: 5,
            nav: false,
            //navText: ["<i class='czi-arrow-left'></i>", "<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 1
                },
                360: {
                    items: 1
                },
                375: {
                    items: 1
                },
                540: {
                    items: 2
                },
                //Small
                576: {
                    items: 3
                },
                //Medium
                768: {
                    items: 3
                },
                //Large
                992: {
                    items: 3
                },
                //Extra large
                1200: {
                    items: 3
                },
                //Extra extra large
                1400: {
                    items: 3
                }
            }
        })

        $( window ).on( "load",function() {
            var work = $(".empty").parent('div').remove();
            console.log( work );
        });
  </script>

  <script>
    $('#brands-slider').owlCarousel({
            loop: false,
            autoplay: true,
            margin: 5,
            nav: false,
            //navText: ["<i class='czi-arrow-left'></i>","<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: true,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 2
                },
                360: {
                    items: 6
                },
                375: {
                    items: 6
                },
                540: {
                    items: 6
                },
                //Small
                576: {
                    items: 5
                },
                //Medium
                768: {
                    items: 7
                },
                //Large
                992: {
                    items: 9
                },
                //Extra large
                1200: {
                    items: 11
                },
                //Extra extra large
                1400: {
                    items: 12
                }
            }
        })
  </script>

  <script>
    $('#category-slider, #top-seller-slider').owlCarousel({
            loop: false,
            autoplay: false,
            margin: 5,
            nav: false,
            // navText: ["<i class='czi-arrow-left'></i>","<i class='czi-arrow-right'></i>"],
            dots: true,
            autoplayHoverPause: false,
            // center: true,
            responsive: {
                //X-Small
                0: {
                    items: 2
                },
                360: {
                    items: 5
                },
                375: {
                    items: 5
                },
                540: {
                    items: 6
                },
                //Small
                576: {
                    items: 6
                },
                //Medium
                768: {
                    items: 6
                },
                //Large
                992: {
                    items: 8
                },
                //Extra large
                1200: {
                    items: 10
                },
                //Extra extra large
                1400: {
                    items: 11
                }
            }
        })
  </script>
  @endpush

