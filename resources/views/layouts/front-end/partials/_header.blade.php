{{-- navabr / _header --}}
<style>
    .loc-div {
        min-width: 135px !important;
    }
    .span-cat::after{
        display: none;
    }
    .mobile-head .navbar.navbar-dark{
        background-color: {{ $web_config['primary_color'] }};
    }
    .cate-mobile::after {
        display: none
    }
    .just-padding {
        padding: 15px;
        border: 1px solid #ccccccb3;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        height: 100%;
        background-color: white;
    }
    .category-canva{
        position: absolute;
        left: -17vw;
        width: 83vw;
        /* display: block; */
    }
    .mega-right {
        left: 96%;
        top: -80%;
        padding-left: 50px;
        text-align: left;
        position: absolute;
    }
    #nav-global-location-slot {
        border: 2px solid transparent;
        /* padding: 10px;
        margin-right: 40px; */
        transition: .3s;
        cursor: pointer;
        border-radius: 4px;
    }
    #nav-global-location-slot:hover {
        border: 2px solid #0f0f0f;
    }
    .nav-line-1.nav-progressive-content {
        font-size: 14px;
        line-height: 14px;
        transition: .3s;
        height: 14px;
        color: #9d9d9d;
        font-weight: 700;
    }
    .nav-line-2.nav-progressive-content {
        font-size: 16px;
        font-weight: 700;
        transition: .3s;
    }
    .card-body.search-result-box {
        overflow: scroll;
        height: 400px;
        overflow-x: hidden;
    }
    .active .seller {
        font-weight: 700;
    }
    ul.navbar-nav.mega-nav .nav-item .nav-link {
        color: #000 !important;
    }
    .for-count-value {
        position: absolute;
        right: 0.6875rem;
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 50%;
        color: {{ $web_config['primary_color'] }};
        font-size: .75rem;
        font-weight: 500;
        text-align: center;
        line-height: 1.25rem;
    }
    .count-value {
        width: 1.25rem;
        height: 1.25rem;
        border-radius: 50%;
        color: {{ $web_config['primary_color'] }};
        font-size: .75rem;
        font-weight: 500;
        text-align: center;
        line-height: 1.25rem;
    }

    @media (min-width: 992px) {
        .navbar-sticky.navbar-stuck .navbar-stuck-menu.show {
            display: block;
            height: 55px !important;
        }
    }

    @media (min-width: 768px) {
        .navbar-stuck-menu {
            background-color: {{ $web_config['primary_color'] }};
            line-height: 15px;
            padding-bottom: 6px;
        }
        .tab-logo {
            width: 10rem;
        }
    }

    @media (max-width: 767px) {
        .search_button {
            background-color: transparent !important;
        }
        .search_button .input-group-text i {
            color: {{ $web_config['primary_color'] }} !important;
        }

        .navbar-expand-md .dropdown-menu>.dropdown>.dropdown-toggle {
            position: relative;
            padding-right: 1.95rem;
        }
        .mega-nav1 {
            background: white;
            color: {{ $web_config['primary_color'] }} !important;
            border-radius: 3px;
        }
        .mega-nav1 .nav-link {
            color: {{ $web_config['primary_color'] }} !important;
        }
    }

    @media (max-width: 360px) {
        .mobile-head {
            padding: 3px;
        }
    }

    @media (max-width: 471px) {
        .mega-nav1 {
            background: white;
            color: #000 !important;
            border-radius: 3px;
        }
        .mega-nav1 .nav-link {
            color: #000 !important;
        }
    }
</style>

<header class="box-shadow-sm rtl d-none d-md-block">

  <div class="navbar-sticky bg-light mobile-head">
    <div class="navbar navbar-expand-md navbar-light">
      <div class="container ">
        <!--    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button> -->

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
          aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        {{-- {{ dd($web_config) }} --}}

        <a class="navbar-brand d-none d-sm-block mr-0
          flex-shrink-0 tab-logo" href="{{route('home')}}" style="min-width: 7rem;">
          <img width="250" height="60" style="height: 60px!important;"
                         src="{{asset("storage/company")."/".$web_config['web_logo']->value}}"
                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                         alt="{{$web_config['name']->value}}"/>
        </a>
        <a class="navbar-brand d-sm-none {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"
          href="{{route('home')}}">
          <img width="100" height="60" style="height: 60px!important;" src="{{asset("storage/company")."/".$web_config['mob_logo']->value}}"
          onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
          alt="{{$web_config['name']->value}}"/>
        </a>

      <!-- new search -->
          <div id="nav-global-location-slot" data-toggle="tooltip" data-placement="top" title="Location">
                    <span id="nav-global-location-data-modal-action" class="a-declarative nav-progressive-attribute">
                        <a id="nav-global-location-popover-link"
                            class="d-flex align-items-center nav-a nav-a-2 a-popover-trigger a-declarative nav-progressive-attribute"
                            tabindex="0">
                            <img class="mt-1 mr-1" style="height: 20px; width: auto;" src="{{asset('public/assets/front-end/img/loc.png')}}" alt="">
                            <div class="mr-2 d-flex loc-div flex-column justify-content-center">
                                <span class="nav-line-1 nav-progressive-content">Area pengiriman test upload</span>
                                <span class="nav-line-2 nav-progressive-content d-flex" id="auto-loc">
                                    {{-- Indonesia --}}
                                </span>
                            </div>
                        </a>
                    </span>
                </div>
        <div class="input-group-overlay d-none d-md-block" style="text-align: {{Session::get('direction') === "
          rtl" ? 'right' : 'left' }}">
          <form action="{{route('products')}}" type="submit" class="search_form">
            <input class="form-control appended-form-control search-bar-input" type="text" autocomplete="off"
              placeholder="{{\App\CPU\translate('search')}}" name="name"
              style="border: 2px solid {{
                $web_config['primary_color']
              }}; border-radius: 50px; border-top-right-radius: 50px !important; border-bottom-right-radius: 50px !important;">
            <button class="input-group-append-overlay search_button" type="submit"
              style="border-radius: {{Session::get('direction') === " rtl" ? '50px 0px 0px 50px; right: unset; left: 0'
              : '0px 50px 50px 0px; left: unset; right: 0' }};">
              <span class="input-group-text" style="font-size: 20px;">
                <i class="czi-search text-white"></i>
              </span>
            </button>
            <input name="data_from" value="search" hidden>
            <input name="page" value="1" hidden>
            <diV class="card search-card"
              style="position: absolute;background: white;z-index: 999;width: 100%;display: none">
              <div class="card-body search-result-box" style="overflow:scroll; height:400px;overflow-x: hidden"></div>
            </diV>
          </form>
        </div>

        <!-- Toolbar-->
        <div class="navbar-toolbar d-flex flex-shrink-0 align-items-center">
          <div class="navbar-tool dropdown {{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}">
            <a class="navbar-tool-icon-box bg-secondary dropdown-toggle" href="{{route('wishlists')}}">
              <span class="navbar-tool-label">
                <span class="countWishlist">{{session()->has('wish_list')?count(session('wish_list')):0}}</span>
              </span>
              <i class="navbar-tool-icon czi-heart"></i>
            </a>
          </div>
          @if(auth('customer')->check() || auth('seller')->check())
          <div class="dropdown">
            <a class="navbar-tool ml-2 mr-2 " type="button" data-toggle="dropdown" aria-haspopup="true"
              aria-expanded="false">
              <div class="navbar-tool-icon-box bg-secondary">
                <div class="navbar-tool-icon-box bg-secondary">
                    @php
                        if(auth('customer')->check()){
                            $prof = 'storage/profile/'.auth('customer')->user()->image;
                            $name = auth('customer')->user()->f_name;
                        }else{
                            $prof = 'storage/seller/'.auth('seller')->user()->image;
                            $name = auth('seller')->user()->f_name;
                        }
                    @endphp
                  <img style="width: 40px;height: 40px"
                    src="{{asset($prof)}}"
                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                    class="img-profile rounded-circle">
                </div>
              </div>
              <div class="navbar-tool-text">
                <small>Hello, {{ $name }}</small>
                Dashboard
              </div>
            </a>
            @if (session()->get('user_is') == 'customer')
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
              <a class="dropdown-item" href="{{route('account-oder')}}"> {{ \App\CPU\translate('my_order')}} </a>
              <a class="dropdown-item" href="{{route('account-address')}}"> {{ \App\CPU\translate('my_address')}} </a>
              <a class="dropdown-item" href="{{route('account-tickets')}}"> {{ \App\CPU\translate('my_tickets')}} </a>
              <a class="dropdown-item" href="{{route('user-account')}}"> {{ \App\CPU\translate('my_profile')}}</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{route('customer.auth.logout')}}">{{ \App\CPU\translate('logout')}}</a>
            </div>
            @else
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{route('seller.dashboard.index')}}"> {{ \App\CPU\translate('Dropship_Dashboard')}} </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{route('seller.auth.logout')}}">{{ \App\CPU\translate('logout')}}</a>
            </div>
            @endif
          </div>
          @else
          <div class="dropdown">
            <a class="navbar-tool {{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}" type="button"
              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Kostumer">
              <div class="navbar-tool-icon-box bg-secondary">
                <div class="navbar-tool-icon-box bg-secondary">
                  <i class="navbar-tool-icon czi-user"></i>
                </div>
              </div>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
              style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};">
              <a class="dropdown-item" href="{{route('customer.auth.login')}}">
                <i class="fa fa-sign-in {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"></i>
                {{\App\CPU\translate('Masuk_sebagai_Kostumer')}}
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{route('customer.auth.register')}}">
                <i class="fa fa-user-circle {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2'
                  }}"></i>{{\App\CPU\translate('Daftar_sebagai_Kostumer')}}
              </a>
            </div>
          </div>

          <div class="dropdown">
            <a class="navbar-tool {{Session::get('direction') === " rtl" ? 'mr-3' : 'ml-3' }}" type="button"
              data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Dropshipper">
              <div class="navbar-tool-icon-box bg-secondary">
                <div class="navbar-tool-icon-box bg-secondary">
                  <img src="{{ asset('assets/front-end/img/drop-shipping.png') }}" class="navbar-tool-icon" style="height: 30px;"></img>
                </div>
              </div>
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
              style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};">
              <a class="dropdown-item" href="{{route('seller.auth.login')}}">
                <i class="fa fa-sign-in {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"></i>
                {{\App\CPU\translate('Masuk_sebagai_Dropshipper')}}
              </a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{route('shop.apply')}}">
                <i class="fa fa-user-circle {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2'
                  }}"></i>{{\App\CPU\translate('Daftar_sebagai_Dropshipper')}}
              </a>
            </div>
          </div>
          @endif
          <div id="cart_items">
            @include('layouts.front-end.partials.cart')
          </div>
        </div>
      </div>
    </div>

    {{-- Bottom Nav --}}
    <div class="navbar navbar-expand-md navbar-stuck-menu bg-light border-top d-none">
      <div class="container">
        <div class="collapse navbar-collapse" id="navbarCollapse" style="text-align: {{Session::get('direction') === "
          rtl" ? 'right' : 'left' }}">

        @php($categories=\App\CPU\CategoryManager::parents())
          <ul class="navbar-nav mega-nav pr-2 pl-2 {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}">
                        <!--web-->
                        <li class=" nav-item {{!request()->is('/none')?'dropdown':''}}">
            <a class="nav-link dropdown-toggle {{Session::get('direction') === " rtl" ? 'pr-0' : 'pl-0' }}" href="#"
              data-toggle="dropdown">
              <i class="czi-menu align-middle mt-n1"></i>
              <span style="margin-{{Session::get('direction') === " rtl" ? 'right' : 'left' }}: 5px
                !important;margin-{{Session::get('direction')==="rtl" ? 'left' : 'right' }}: 5px">
                {{ \App\CPU\translate('categories')}}
              </span>
            </a>
            @if(request()->is('/'))
               <ul class="dropdown-menu"
                                    style="right: 0%; margin-top: 7px; box-shadow: none;min-width: 303px !important;{{Session::get('direction') === "rtl" ? 'margin-right: 1px!important;text-align: right;' : 'margin-left: 1px!important;text-align: left;'}}padding-bottom: 0px!important;">
                                    @foreach($categories as $key=>$category)
                                        @if($key<11)
                                            <li class="dropdown">
                                                <a class="dropdown-item flex-between"
                                                   <?php if ($category->childes->count() > 0) echo "data-toggle='dropdown'"?> href="javascript:"
                                                   onclick="location.href='{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}'">
                                                    <div>
                                                        <img
                                                            src="{{asset("storage/category/$category->icon")}}"
                                                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                            style="width: 50px; height: 50px; ">
                                                        <span
                                                            class="{{Session::get('direction') === "rtl" ? 'pr-3' : 'pl-3'}}">{{$category['name']}}</span>
                                                    </div>
                                                    @if ($category->childes->count() > 0)
                                                        <div>
                                                            <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" style="line-height: 3;" ></i>
                                                        </div>
                                                    @endif
                                                </a>
                                                @if($category->childes->count()>0)
                                                    <ul class="dropdown-menu"
                                                        style="right: 100%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                                        @foreach($category['childes'] as $subCategory)
                                                            <li class="dropdown">
                                                                <a class="dropdown-item flex-between"
                                                                   <?php if ($subCategory->childes->count() > 0) echo "data-toggle='dropdown'"?> href="javascript:"
                                                                   onclick="location.href='{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}'">
                                                                    <div>
                                                                        <span
                                                                            class="{{Session::get('direction') === "rtl" ? 'pr-3' : 'pl-3'}}">{{$subCategory['name']}}</span>
                                                                    </div>
                                                                    @if ($subCategory->childes->count() > 0)
                                                                        <div>
                                                                            <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" style="line-height: 3;"></i>
                                                                        </div>
                                                                    @endif
                                                                </a>
                                                                @if($subCategory->childes->count()>0)
                                                                    <ul class="dropdown-menu"
                                                                        style="right: 100%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                                                        @foreach($subCategory['childes'] as $subSubCategory)
                                                                            <li>
                                                                                <a class="dropdown-item"
                                                                                   href="{{route('products',['id'=> $subSubCategory['id'],'data_from'=>'category','page'=>1])}}">{{$subSubCategory['name']}}</a>
                                                                            </li>
                                                                        @endforeach
                                                                    </ul>
                                                                @endif
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endif
                                    @endforeach
                                    <a class="dropdown-item" href="{{route('categories')}}"
                                       style="{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 29%">
                                        {{\App\CPU\translate('view_more')}}
                                    </a>
                                </ul>
                            @else
                                <ul class="dropdown-menu"
                                    style="right: 0; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                    @foreach($categories as $category)
                                        <li class="dropdown">
                                            <a class="dropdown-item flex-between <?php if ($category->childes->count() > 0) echo "data-toggle='dropdown"?> "
                                               <?php if ($category->childes->count() > 0) echo "data-toggle='dropdown'"?> href="javascript:"
                                               onclick="location.href='{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}'">
                                                <div>
                                                    <img src="{{asset("storage/category/$category->icon")}}"
                                                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                                         style="width: 50px; height: 50px; ">
                                                    <span
                                                        class="{{Session::get('direction') === "rtl" ? 'pr-3' : 'pl-3'}}">{{$category['name']}}</span>
                                                </div>
                                                @if ($category->childes->count() > 0)
                                                    <div>
                                                        <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" style="line-height: 3;"></i>
                                                    </div>
                                                @endif
                                            </a>
                                            @if($category->childes->count()>0)
                                                <ul class="dropdown-menu"
                                                    style="right: 100%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                                    @foreach($category['childes'] as $subCategory)
                                                        <li class="dropdown">
                                                            <a class="dropdown-item flex-between <?php if ($subCategory->childes->count() > 0) echo "data-toggle='dropdown"?> "
                                                               <?php if ($subCategory->childes->count() > 0) echo "data-toggle='dropdown'"?> href="javascript:"
                                                               onclick="location.href='{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}'">
                                                                <div>
                                                                    <span
                                                                        class="{{Session::get('direction') === "rtl" ? 'pr-3' : 'pl-3'}}">{{$subCategory['name']}}</span>
                                                                </div>
                                                                @if ($subCategory->childes->count() > 0)
                                                                    <div>
                                                                        <i class="czi-arrow-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}" style="line-height: 3;"></i>
                                                                    </div>
                                                                @endif
                                                            </a>
                                                            @if($subCategory->childes->count()>0)
                                                                <ul class="dropdown-menu"
                                                                    style="right: 100%; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                                                    @foreach($subCategory['childes'] as $subSubCategory)
                                                                        <li>
                                                                            <a class="dropdown-item"
                                                                               href="{{route('products',['id'=> $subSubCategory['id'],'data_from'=>'category','page'=>1])}}">{{$subSubCategory['name']}}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                    <a class="dropdown-item" href="{{route('categories')}}"
                                       style="{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 29%">
                                        {{\App\CPU\translate('view_more')}}
                                    </a>
                                </ul>
                            @endif
                        </li>
                    </ul>


          <ul class="navbar-nav mega-nav1 pr-2 pl-2 d-block d-xl-none">
            <!--mobile-->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle {{Session::get('direction') === " rtl" ? 'pr-0' : 'pl-0' }}" href="#"
                data-toggle="dropdown">
                <i class="czi-menu align-middle mt-n1 d-none {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"></i>
                <span style="margin-{{Session::get('direction') === " rtl" ? 'right' : 'left' }}: -8px !important;">{{
                  \App\CPU\translate('categories')}}</span>
              </a>
              <ul class="dropdown-menu" style="right: 0%; text-align: {{Session::get('direction') === " rtl" ? 'right'
                : 'left' }};">
                @foreach($categories as $category)
                <li class="dropdown">
                  <a class="dropdown-item <?php if ($category->childes->count() > 0) echo " dropdown-toggle"?> "
                    <?php if ($category->childes->count() > 0) echo "data-toggle='dropdown'"?>
                    href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                    <img src="{{asset("storage/category/$category->icon")}}"
                    onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                    style="width: 50px; height: 40px; ">
                    <span class="{{Session::get('direction') === " rtl" ? 'pr-3' : 'pl-3'
                      }}">{{$category['name']}}</span>
                  </a>
                  @if($category->childes->count()>0)
                  <ul class="dropdown-menu" style="right: 100%; text-align: {{Session::get('direction') === " rtl"
                    ? 'right' : 'left' }};">
                    @foreach($category['childes'] as $subCategory)
                    <li class="dropdown">
                      <a class="dropdown-item <?php if ($subCategory->childes->count() > 0) echo " dropdown-toggle"?> "
                        <?php if ($subCategory->childes->count() > 0) echo "data-toggle='dropdown'"?>
                        href="{{route('products',['id'=> $subCategory['id'],'data_from'=>'category','page'=>1])}}">
                        <span class="{{Session::get('direction') === " rtl" ? 'pr-3' : 'pl-3'
                          }}">{{$subCategory['name']}}</span>
                      </a>
                      @if($subCategory->childes->count()>0)
                      <ul class="dropdown-menu" style="right: 100%; text-align: {{Session::get('direction') === " rtl"
                        ? 'right' : 'left' }};">
                        @foreach($subCategory['childes'] as $subSubCategory)
                        <li>
                          <a class="dropdown-item"
                            href="{{route('products',['id'=> $subSubCategory['id'],'data_from'=>'category','page'=>1])}}">{{$subSubCategory['name']}}</a>
                        </li>
                        @endforeach
                      </ul>
                      @endif
                    </li>
                    @endforeach
                  </ul>
                  @endif
                </li>
                @endforeach
              </ul>
            </li>
          </ul>
          <!-- Primary menu-->
          <ul class="navbar-nav w-100" style="{{Session::get('direction') === " rtl" ? 'padding-right: 0px' : '' }}">
            <li class="nav-item dropdown {{request()->is('/')?'active':''}}">
              <a class="nav-link text-dark border-right py-2 mt-2" style="color: black !important"
                href="{{route('home')}}">{{ \App\CPU\translate('Home')}}</a>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-dark border-right py-2 mt-2" style="color: black !important"
                href="#" data-toggle="dropdown">{{ \App\CPU\translate('brand') }}</a>
              <ul class="dropdown-menu scroll-bar" style="text-align: {{Session::get('direction') === " rtl" ? 'right'
                : 'left' }};">
                @foreach(\App\CPU\BrandManager::get_brands() as $brand)
                <li style="border-bottom: 1px solid #e3e9ef; display:flex; justify-content:space-between; ">
                  <div>
                    <a class="dropdown-item"
                      href="{{route('products',['id'=> $brand['id'],'data_from'=>'brand','page'=>1])}}">
                      {{$brand['name']}}
                    </a>
                  </div>
                  <div class="align-baseline">
                    @if($brand['brand_products_count'] > 0 )
                    <span class="count-value px-2">( {{ $brand['brand_products_count'] }} )</span>
                    @endif
                  </div>
                </li>
                @endforeach
              </ul>
            </li>
            @php($seller_registration=\App\Model\BusinessSetting::where(['type'=>'seller_registration'])->first()->value)
            @if($seller_registration)
            <!--   <li class="nav-item">
                                <div class="dropdown">
                                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
                                            style="color: white;margin-top: 5px; padding-{{Session::get('direction') === "rtl" ? 'right' : 'left'}}: 0">
                                        <b>{{ \App\CPU\translate('Seller')}}  {{ \App\CPU\translate('zone')}} </b>
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton"
                                         style="min-width: 165px !important; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};">
                                        <a class="dropdown-item" href="{{route('shop.apply')}}">
                                            <b>{{ \App\CPU\translate('Become a')}} {{ \App\CPU\translate('Seller')}}</b>
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="{{route('seller.auth.login')}}">
                                            <b>{{ \App\CPU\translate('Seller')}}  {{ \App\CPU\translate('login')}} </b>
                                        </a>
                                    </div>
                                </div>
                            </li> -->

            <li class="nav-item dropdown">
              <a class="nav-link text-dark border-right py-2 mt-2" style="color: black !important"
                href="{{url('track-order')}}">
                {{ \App\CPU\translate('Track')}} {{ \App\CPU\translate('Order')}}
              </a>
            </li>

            <li class="nav-item dropdown">
              <a class="nav-link text-dark border-right py-2 mt-2" style="color: black !important"
                href="{{route('shop.apply')}}">
                {{ \App\CPU\translate('Become a')}} {{ \App\CPU\translate('Seller')}}
              </a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link text-dark border-right py-2 mt-2" style="color: black !important"
                href="{{route('seller.auth.login')}}">
                {{ \App\CPU\translate('Seller')}} {{ \App\CPU\translate('login')}}
              </a>
            </li>
            @endif

            @php( $short = \App\CPU\Helpers::country())
                        {{-- {{ dd($short[0]) }} --}}
                        <li class="nav-item dropdown ml-auto">
                            <a class="nav-link dropdown-toggle text-dark border-right py-2 mt-2" href="#"
                                data-toggle="dropdown" style="color: black !important">
                                <!--<img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"-->
                                <!--    style="height: 16px; width: auto"-->
                                <!--    src="{{asset('public/assets/front-end')}}/img/loc.png" alt="Eng">-->
                              @if (empty($country))
                                    <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                                        src="{{asset('public/assets/front-end')}}/img/shop.png"
                                        alt="Eng">
                                    All Country
                                    @else
                                    <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                                    src="{{asset('public/assets/front-end')}}/img/flags/{{ strtolower($country ?? 'id')  }}.png"
                                    alt="Eng">

                                {{ $country }}
                                @endif
                            </a>
                            <ul class="dropdown-menu scroll-bar">
                                <li>
                                    <a class="dropdown-item pb-1" href="{{route('home')}}">
                                        <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"
                                            width="20"
                                            src="{{asset('public/assets/front-end')}}/img/shop.png"
                                            alt="flsg" />
                                        <span style="text-transform: capitalize">All Country</span>
                                    </a>
                                @foreach($short as $data)
                                    <a class="dropdown-item pb-1" href="{{route('shortBy', $data->country)}}">
                                        <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"
                                            width="20"
                                            src="{{asset('public/assets/front-end')}}/img/flags/{{strtolower($data->country)}}.png"
                                            alt="flsg" />
                                        <span style="text-transform: capitalize">{{ $data->country_name }}</span>
                                    </a>
                                @endforeach
                                </li>
                            </ul>
                        </li>

            @php( $local = \App\CPU\Helpers::default_lang())
            <li class="nav-item dropdown ml-auto">
              <a class="nav-link dropdown-toggle text-dark border-right py-2 mt-2" href="#" data-toggle="dropdown"
                style="color: black !important">
                @foreach(json_decode($language['value'],true) as $data)
                @if($data['code']==$local)
                <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                  src="{{asset('public/assets/front-end')}}/img/flags/{{$data['code']}}.png" alt="Eng">
                {{$data['name']}}
                @endif
                @endforeach
              </a>
              <ul class="dropdown-menu scroll-bar">
                @foreach(json_decode($language['value'],true) as $key =>$data)
                @if($data['status']==1)
                <li>
                  <a class="dropdown-item pb-1" href="{{route('lang',[$data['code']])}}">
                    <img class="{{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}" width="20"
                      src="{{asset('public/assets/front-end')}}/img/flags/{{$data['code']}}.png"
                      alt="{{$data['name']}}" />
                    <span
                      style="text-transform: capitalize">{{\App\CPU\Helpers::get_language_name($data['code'])}}</span>
                  </a>
                </li>
                @endif
                @endforeach
              </ul>
            </li>

            @php($currency_model = \App\CPU\Helpers::get_business_settings('currency_model'))
            @if($currency_model=='multi_currency')
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-dark" style="color: black !important" href="#"
                data-toggle="dropdown">
                <span>{{session('currency_code')}} {{session('currency_symbol')}}</span>
              </a>
              <ul class="dropdown-menu" style="min-width: 160px!important;">
                @foreach (\App\Model\Currency::where('status', 1)->get() as $key => $currency)
                <li style="cursor: pointer" class="dropdown-item" onclick="currency_change('{{$currency['code']}}')">
                  {{ $currency->name }}
                </li>
                @endforeach
              </ul>
            </li>
            @endif



          </ul>
        </div>
      </div>
    </div>
  </div>
</header>

@push('script')
<script>
    $(document).ready(function(){
        function ObserveInputValue() {
            // console.log($('#cartCount').val());
            $('#cartNumber').text($('#cartCount').val())
        }
        setInterval(function() { ObserveInputValue() }, 2000);
    });
</script>
<script>

fetch('https://ipapi.co/json/')
  .then(function(response) {
    return response.json();
  })
  .then(function(data) {
    console.log('location',data);

            if(data.region !== "West Java"){
                $('#auto-loc').append('Diluar jangkauan').attr('style', 'font-size: 16px; width: 120px;')
            }else{
                $('#auto-loc').append(data.city)
            }
            $('#nav-global-location-slot').attr('data-original-title', data.country_name + ', ' + data.region);
  });
</script>
@endpush
