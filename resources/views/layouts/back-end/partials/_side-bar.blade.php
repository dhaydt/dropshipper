<style>
    .navbar-vertical .nav-link {
        color: #ffffff;
        font-weight: bold;
    }

    .navbar .nav-link:hover {
        color: #C6FFC1;
    }

    .navbar .active > .nav-link, .navbar .nav-link.active, .navbar .nav-link.show, .navbar .show > .nav-link {
        color: #C6FFC1;
    }

    .navbar-vertical .active .nav-indicator-icon, .navbar-vertical .nav-link:hover .nav-indicator-icon, .navbar-vertical .show > .nav-link > .nav-indicator-icon {
        color: #C6FFC1;
    }

    .nav-subtitle {
        display: block;
        color: #fffbdf91;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .03125rem;
    }

    .side-logo {
        background-color: #F7F8FA;
    }

    .nav-sub {
        background-color: #182c2f !important;
    }

    .nav-indicator-icon {
        margin-left: {{Session::get('direction') === "rtl" ? '6px' : ''}};
    }
</style>

<div id="sidebarMain" class="d-none">
    <aside style="background: #182c2f!important; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
           class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset pb-0">
                <div class="navbar-brand-wrapper justify-content-between side-logo">
                    <!-- Logo -->
                    @php($e_commerce_logo=\App\Model\BusinessSetting::where(['type'=>'company_web_logo'])->first()->value)
                    <a class="navbar-brand" href="{{route('admin.dashboard.index')}}" aria-label="Front">
                        <img style="max-height: 38px"
                             onerror="this.src='{{asset('public/assets/back-end/img/900x400/img1.jpg')}}'"
                             class="navbar-brand-logo-mini for-web-logo"
                             src="{{asset("storage/company/$e_commerce_logo")}}" alt="Logo">
                    </a>
                    <!-- Navbar Vertical Toggle -->
                    <button type="button"
                            class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>

                <!-- Content -->
                <div class="navbar-vertical-content mt-2">
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- Dashboards -->

                        <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel')?'show':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('admin.dashboard.index')}}">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Dashboard')}}
                                </span>
                            </a>
                        </li>

                        <!-- End Dashboards -->

                        @if(\App\CPU\Helpers::module_permission_check('order_management'))
                            <li class="nav-item {{Request::is('adminpanel/orders*')?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">{{\App\CPU\translate('order_management')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <!-- Order -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/orders*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-shopping-cart-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('orders')}}
                                </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('adminpanel/order*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('adminpanel/orders/list/all')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.orders.list',['all'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('All')}}
                                            <span class="badge badge-info badge-pill ml-1">
                                                {{\App\Model\Order::count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/orders/list/pending')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['pending'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('pending')}}
                                            <span class="badge badge-soft-info badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'pending'])->count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/orders/list/confirmed')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['confirmed'])}}"
                                           title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('confirmed')}}
                                                <span class="badge badge-soft-success badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'confirmed'])->count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/orders/list/processing')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['processing'])}}"
                                           title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('Processing')}}
                                                <span class="badge badge-warning badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'processing'])->count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/orders/list/out_for_delivery')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['out_for_delivery'])}}"
                                           title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('out_for_delivery')}}
                                                <span class="badge badge-warning badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'out_for_delivery'])->count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/orders/list/delivered')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['delivered'])}}"
                                           title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('delivered')}}
                                                <span class="badge badge-success badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'delivered'])->count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/orders/list/returned')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['returned'])}}"
                                           title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('returned')}}
                                                <span class="badge badge-soft-danger badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'returned'])->count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/orders/list/failed')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['failed'])}}" title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('failed')}}
                                            <span class="badge badge-danger badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'failed'])->count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>

                                    <li class="nav-item {{Request::is('adminpanel/orders/list/canceled')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.orders.list',['canceled'])}}"
                                           title="">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                            {{\App\CPU\translate('canceled')}}
                                                <span class="badge badge-danger badge-pill ml-1">
                                                {{\App\Model\Order::where(['order_status'=>'canceled'])->count()}}
                                            </span>
                                        </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    <!--order management ends-->

                        @if(\App\CPU\Helpers::module_permission_check('product_management'))
                            <li class="nav-item {{(Request::is('adminpanel/brand*') || Request::is('adminpanel/category*') || Request::is('adminpanel/sub*') || Request::is('adminpanel/attribute*') || Request::is('adminpanel/product*'))?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">{{\App\CPU\translate('product_management')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <!-- Pages -->
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/brand*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-apple-outlined nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('brands')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('adminpanel/brand*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('adminpanel/brand/add-new')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.brand.add-new')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('add_new')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/brand/list')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.brand.list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('List')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{(Request::is('adminpanel/category*') ||Request::is('adminpanel/sub*')) ?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-filter-list nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('categories')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{(Request::is('adminpanel/category*') ||Request::is('adminpanel/sub*'))?'block':''}}">
                                    <li class="nav-item {{Request::is('adminpanel/category/view')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.category.view')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('category')}}</span>
                                        </a>

                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/sub-category/view')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.sub-category.view')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('sub_category')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/sub-sub-category/view')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.sub-sub-category.view')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('sub_sub_category')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{(Request::is('adminpanel/request*')) ?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-filter-list nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('request_product')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{(Request::is('adminpanel/request*'))?'block':''}}">
                                    <li class="nav-item {{Request::is('adminpanel/request/list/all')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.request.list', ['all'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('all_request')}}</span>
                                        </a>

                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/request/list/pending')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.request.list', ['pending'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('pending_request')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/request/list/accepted')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.request.list', ['accepted'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('accepted_request')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/request/list/denied')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.request.list', ['denied'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('denied_request')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/attribute*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.attribute.view')}}">
                                    <i class="tio-category-outlined nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Attribute')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{(Request::is('adminpanel/product/list/in_house') || Request::is('adminpanel/product/bulk-import'))?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-shop nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        <span class="text-truncate">{{\App\CPU\translate('InHouse Products')}}</span>
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{(Request::is('adminpanel/product/list/in_house') || Request::is('adminpanel/product/bulk-import'))?'block':''}}">
                                    <li class="nav-item {{Request::is('adminpanel/product/list/in_house')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.list',['in_house', ''])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('Products')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/product/bulk-import')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.bulk-import')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('bulk_import')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/product/bulk-export')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.bulk-export')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('bulk_export')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            {{-- <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/product/list/seller*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-airdrop nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('Seller')}} {{\App\CPU\translate('Products')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('adminpanel/product/list/seller*')?'block':''}}">
                                    <li class="nav-item {{Request::is('adminpanel/product/list/seller/0')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.list',['seller', 'status'=>'0'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('New')}} {{\App\CPU\translate('Products')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/product/list/seller/1')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.list',['seller', 'status'=>'1'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('Approved')}} {{\App\CPU\translate('Products')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/product/list/seller/2')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.product.list',['seller', 'status'=>'2'])}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('Denied')}} {{\App\CPU\translate('Products')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li> --}}
                        @endif
                    <!--product management ends-->

                        @if(\App\CPU\Helpers::module_permission_check('marketing_section'))
                            <li class="nav-item {{(Request::is('adminpanel/banner*') || Request::is('adminpanel/coupon*') || Request::is('adminpanel/notification*') || Request::is('adminpanel/deal*'))?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">{{\App\CPU\translate('Marketing_Section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/banner*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.banner.list')}}">
                                    <i class="tio-photo-square-outlined nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('banners')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/coupon*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.coupon.add-new')}}">
                                    <i class="tio-credit-cards nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('coupons')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/notification*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.notification.add-new')}}" title="">
                                    <i class="tio-notifications-on-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('push_notification')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/deal/flash')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.deal.flash')}}">
                                    <i class="tio-flash nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('flash_deals')}}</span>
                                </a>
                            </li>
                            {{-- <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/deal/day')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.deal.day')}}">
                                    <i class="tio-crown-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('deal_of_the_day')}}
                                    </span>
                                </a>
                            </li> --}}
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/deal/feature')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.deal.feature')}}">
                                    <i class="tio-flag-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('featured_deal')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/deal/unggulan')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.deal.unggulan')}}">
                                    <i class="tio-crown-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('produk_unggulan')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/deal/berlimpah')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.deal.berlimpah')}}">
                                    <i class="tio-event nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('Diskon_berlimpah')}}
                                    </span>
                                </a>
                            </li>
                        @endif
                    <!--marketing section ends here-->

                        @if(\App\CPU\Helpers::module_permission_check('business_section'))
                            <li class="nav-item {{(Request::is('adminpanel/report/product-in-wishlist') || Request::is('adminpanel/reviews*') || Request::is('adminpanel/sellers/withdraw_list') || Request::is('adminpanel/report/product-stock'))?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">{{\App\CPU\translate('business_section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            {{-- seller withdraw --}}
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/stock/product-stock')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.stock.product-stock')}}">
                                    <i class="tio-fullscreen-1-1 nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                 {{\App\CPU\translate('product')}} {{\App\CPU\translate('stock')}}
                                </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/reviews*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.reviews.list')}}">
                                    <i class="tio-star nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Customer')}} {{\App\CPU\translate('Reviews')}}
                                </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/stock/product-in-wishlist')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.stock.product-in-wishlist')}}">
                                    <i class="tio-heart-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                 {{\App\CPU\translate('product')}} {{\App\CPU\translate('in')}}  {{\App\CPU\translate('wish_list')}}
                                </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/transaction/list')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.transaction.list')}}">
                                    <i class="tio-money nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                 {{\App\CPU\translate('Transactions')}}
                                </span>
                                </a>
                            </li>
                        @endif
                    <!--business section ends here-->

                        @if(\App\CPU\Helpers::module_permission_check('user_section'))
                            <li class="nav-item {{(Request::is('adminpanel/customer/list') || Request::is('adminpanel/sellers/seller-list'))?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">{{\App\CPU\translate('dropshipper_section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/seller*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-users-switch nav-icon"></i>
                                    <span
                                        class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">{{\App\CPU\translate('Dropshipper')}}</span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('adminpanel/seller*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('adminpanel/sellers/seller-list')?'active':''}}">
                                        <a class="nav-link"
                                        href="{{route('admin.sellers.seller-list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{\App\CPU\translate('list')}}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/sellers/withdraw_list')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.sellers.withdraw_list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('withdraws')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>


                            <li class="nav-item {{Request::is('adminpanel/customer/list')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.customer.list')}}">
                                    <span class="tio-poi-user nav-icon"></span>
                                    <span
                                        class="text-truncate">{{\App\CPU\translate('customers')}} </span>
                                </a>
                            </li>
                        @endif
                    <!--user section ends here-->

                        @if(\App\CPU\Helpers::module_permission_check('support_section'))
                            <li class="nav-item {{(Request::is('adminpanel/support-ticket*') || Request::is('adminpanel/contact*'))?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">{{\App\CPU\translate('support_section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/contact*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.contact.list')}}">
                                    <i class="tio-messages nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('messages')}}
                                </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/support-ticket*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.support-ticket.view')}}">
                                    <i class="tio-chat nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('support_ticket')}}
                                </span>
                                </a>
                            </li>
                        @endif
                    <!--support section ends here-->

                        @if(\App\CPU\Helpers::module_permission_check('business_settings'))
                            <li class="nav-item {{(Request::is('adminpanel/currency/view') || Request::is('adminpanel/business-settings/language*') || Request::is('adminpanel/business-settings/shipping-method*') || Request::is('adminpanel/business-settings/payment-method') || Request::is('adminpanel/business-settings/seller-settings*'))?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">{{\App\CPU\translate('business_settings')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/business-settings/seller-settings*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.business-settings.seller-settings.index')}}">
                                    <i class="tio-user-big-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('seller_settings')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/business-settings/payment-method')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.business-settings.payment-method.index')}}">
                                    <i class="tio-money-vs nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('payment_method')}}
                                    </span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/business-settings/sms-module')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.business-settings.sms-module')}}"
                                   title="{{\App\CPU\translate('sms')}} {{\App\CPU\translate('module')}}">
                                    <i class="tio-sms-active-outlined nav-icon"></i>
                                    <span class="text-truncate">{{\App\CPU\translate('sms')}} {{\App\CPU\translate('module')}}</span>
                                </a>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/business-settings/shipping-method*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-car nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('shipping_method')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('adminpanel/business-settings/shipping-method*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('adminpanel/business-settings/shipping-method/by/admin')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.business-settings.shipping-method.by.admin')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                              {{\App\CPU\translate('by_admin')}}
                                            </span>
                                        </a>
                                    </li>
                                    {{-- <li class="nav-item {{Request::is('adminpanel/business-settings/shipping-method/by/seller')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.business-settings.shipping-method.by.seller')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                               {{\App\CPU\translate('by_seller')}}
                                            </span>
                                        </a>
                                    </li> --}}
                                    <li class="nav-item {{Request::is('adminpanel/business-settings/shipping-method/setting')?'active':''}}">
                                        <a class="nav-link"
                                           href="{{route('admin.business-settings.shipping-method.setting')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                               {{\App\CPU\translate('Settings')}}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/business-settings/language*')?'active':''}}">
                                <a class="nav-link " href="{{route('admin.business-settings.language.index')}}"
                                   title="{{\App\CPU\translate('languages')}}">
                                    <i class="tio-book-opened nav-icon"></i>
                                    <span class="text-truncate">{{\App\CPU\translate('languages')}}</span>
                                </a>
                            </li>

{{--                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/business-settings/language*')?'active':''}}">--}}
{{--                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"--}}
{{--                                   href="javascript:">--}}
{{--                                    <i class="tio-book-opened nav-icon"></i>--}}
{{--                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">--}}
{{--                                        {{\App\CPU\translate('languages')}}--}}
{{--                                    </span>--}}
{{--                                </a>--}}
{{--                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"--}}
{{--                                    style="display: {{Request::is('adminpanel/business-settings/language*')?'block':'none'}}">--}}
{{--                                    <li class="nav-item {{Request::is('adminpanel/business-settings/language-app')?'active':''}}">--}}
{{--                                        <a class="nav-link"--}}
{{--                                           href="{{route('admin.business-settings.language.index-app')}}">--}}
{{--                                            <span class="tio-circle nav-indicator-icon"></span>--}}
{{--                                            <span class="text-truncate">--}}
{{--                                              {{\App\CPU\translate('for_data_entry')}}--}}
{{--                                            </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                    <li class="nav-item {{Request::is('adminpanel/business-settings/language')?'active':''}}">--}}
{{--                                        <a class="nav-link"--}}
{{--                                           href="{{route('admin.business-settings.language.index')}}">--}}
{{--                                            <span class="tio-circle nav-indicator-icon"></span>--}}
{{--                                            <span class="text-truncate">--}}
{{--                                               {{\App\CPU\translate('for_website')}}--}}
{{--                                            </span>--}}
{{--                                        </a>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
{{--                            </li>--}}
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/social-login/view')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.social-login.view')}}">
                                    <i class="tio-top-security-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('social_login')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/currency/view')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.currency.view')}}">
                                    <i class="tio-dollar-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('currencies')}}
                                    </span>
                                </a>
                            </li>
                        @endif
                    <!--business settings ends here-->

                        @if(\App\CPU\Helpers::module_permission_check('web_&_app_settings'))
                            <li class="nav-item {{(Request::is('adminpanel/business-settings/social-media') || Request::is('adminpanel/business-settings/terms-condition') || Request::is('adminpanel/business-settings/privacy-policy') || Request::is('adminpanel/business-settings/about-us') || Request::is('adminpanel/helpTopic/list') || Request::is('adminpanel/business-settings/fcm-index') || Request::is('adminpanel/business-settings/mail') || Request::is('adminpanel/business-settings/web-config'))?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">{{\App\CPU\translate('web_&_app_settings')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/business-settings/web-config')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.business-settings.web-config.index')}}">
                                    <i class="tio-globe nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('web_config')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/business-settings/mail')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.business-settings.mail.index')}}">
                                    <i class="tio-email nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('mail_config')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/business-settings/fcm-index')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.business-settings.fcm-index')}}">
                                    <i class="tio-notifications-alert nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('notification')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/business-settings/terms-condition') || Request::is('adminpanel/business-settings/privacy-policy') || Request::is('adminpanel/business-settings/about-us') || Request::is('adminpanel/helpTopic/list')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-pages-outlined nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('page_setup')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('adminpanel/business-settings/terms-condition') || Request::is('adminpanel/business-settings/privacy-policy') || Request::is('adminpanel/business-settings/about-us') || Request::is('adminpanel/helpTopic/list')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('adminpanel/business-settings/terms-condition')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.business-settings.terms-condition')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                              {{\App\CPU\translate('terms_and_condition')}}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/business-settings/privacy-policy')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.business-settings.privacy-policy')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                              {{\App\CPU\translate('privacy_policy')}}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/business-settings/about-us')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.business-settings.about-us')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                              {{\App\CPU\translate('about_us')}}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/helpTopic/list')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.helpTopic.list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                              {{\App\CPU\translate('faq')}}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/business-settings/social-media')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.business-settings.social-media')}}">
                                    <i class="tio-twitter nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('social_media')}}
                                    </span>
                                </a>
                            </li>
                        @endif
                    <!--web & app settings ends here-->

                        @if(\App\CPU\Helpers::module_permission_check('report'))
                            <li class="nav-item {{(Request::is('adminpanel/report/inhoue-product-sale') || Request::is('adminpanel/report/seller-product-sale') || Request::is('adminpanel/report/order') || Request::is('adminpanel/report/earning'))?'scroll-here':''}}">
                                <small class="nav-subtitle" title="">
                                    {{\App\CPU\translate('Report')}}& {{\App\CPU\translate('Analytics')}}
                                </small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/report/earning')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.report.earning')}}">
                                    <i class="tio-chart-pie-1 nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                       {{\App\CPU\translate('Earning')}} {{\App\CPU\translate('Report')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/report/order')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.report.order')}}">
                                    <i class="tio-chart-bar-1 nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                     {{\App\CPU\translate('Order')}} {{\App\CPU\translate('Report')}}
                                    </span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/report/inhoue-product-sale') || Request::is('adminpanel/report/seller-product-sale') ?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-chart-bar-4 nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                        {{\App\CPU\translate('sale_report')}}
                                    </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('adminpanel/report/inhoue-product-sale') || Request::is('adminpanel/report/seller-product-sale') ?'block':'none'}}">
                                    <li class="nav-item {{Request::is('adminpanel/report/inhoue-product-sale')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.report.inhoue-product-sale')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">
                                                {{\App\CPU\translate('inhouse')}} {{\App\CPU\translate('sale')}}
                                            </span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/report/seller-product-sale')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.report.seller-product-sale')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate text-capitalize">
                                                {{\App\CPU\translate('seller')}} {{\App\CPU\translate('sale')}}
                                            </span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif
                    <!--reporting and analysis ends here-->

                        @if(\App\CPU\Helpers::module_permission_check('employee_section'))
                            <li class="nav-item {{(Request::is('adminpanel/employee*') || Request::is('adminpanel/custom-role*'))?'scroll-here':''}}">
                                <small class="nav-subtitle">{{\App\CPU\translate('employee_section')}}</small>
                                <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                            </li>

                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/custom-role*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                   href="{{route('admin.custom-role.create')}}">
                                    <i class="tio-incognito nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('employee_role')}}</span>
                                </a>
                            </li>
                            <li class="navbar-vertical-aside-has-menu {{Request::is('adminpanel/employee*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle"
                                   href="javascript:">
                                    <i class="tio-user nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                            {{\App\CPU\translate('employees')}}
                                        </span>
                                </a>
                                <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                    style="display: {{Request::is('adminpanel/employee*')?'block':'none'}}">
                                    <li class="nav-item {{Request::is('adminpanel/employee/add-new')?'active':''}}">
                                        <a class="nav-link " href="{{route('admin.employee.add-new')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('add_new')}}</span>
                                        </a>
                                    </li>
                                    <li class="nav-item {{Request::is('adminpanel/employee/list')?'active':''}}">
                                        <a class="nav-link" href="{{route('admin.employee.list')}}">
                                            <span class="tio-circle nav-indicator-icon"></span>
                                            <span class="text-truncate">{{\App\CPU\translate('List')}}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        @endif

                        <li class="nav-item" style="padding-top: 50px">
                            <div class="nav-divider"></div>
                        </li>
                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>



