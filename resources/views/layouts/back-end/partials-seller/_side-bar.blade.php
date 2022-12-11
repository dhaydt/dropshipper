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
    .side-logo{
        background-color: #F7F8FA;
    }
    .nav-sub{
        background-color: #182c2f!important;
    }

    .nav-indicator-icon {
        margin-left: {{Session::get('direction') === "rtl" ? '6px' : ''}};
    }
</style>
<div id="sidebarMain" class="d-none">
    <aside style="background: #182c2f!important; text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
        class="js-navbar-vertical-aside navbar navbar-vertical-aside navbar-vertical navbar-vertical-fixed navbar-expand-xl navbar-bordered  ">
        <div class="navbar-vertical-container">
            <div class="navbar-vertical-footer-offset" style="padding-bottom: 0">
                <div class="navbar-brand-wrapper justify-content-between side-logo">
                    <!-- Logo -->
                    @php($seller_logo=\App\Model\Shop::where(['seller_id'=>auth('seller')->id()])->first()->image)
                    <a class="navbar-brand" href="{{route('seller.dashboard.index')}}" aria-label="Front">
                        <img onerror="this.src='{{asset('public/assets/back-end/img/900x400/img1.jpg')}}'"
                             class="navbar-brand-logo-mini for-seller-logo"
                             src="{{asset("storage/shop/$seller_logo")}}" alt="Logo">
                    </a>
                    <!-- End Logo -->

                    <!-- Navbar Vertical Toggle -->
                    <button type="button"
                            class="js-navbar-vertical-aside-toggle-invoker navbar-vertical-aside-toggle btn btn-icon btn-xs btn-ghost-dark">
                        <i class="tio-clear tio-lg"></i>
                    </button>
                    <!-- End Navbar Vertical Toggle -->
                </div>

                <!-- Content -->
                <div class="navbar-vertical-content">
                    <ul class="navbar-nav navbar-nav-lg nav-tabs">
                        <!-- Dashboards -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller')?'show':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.dashboard.index')}}">
                                <i class="tio-home-vs-1-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Dashboard')}}
                                </span>
                            </a>
                        </li>
                        <!-- End Dashboards -->


                        <li class="nav-item">
                            <small class="nav-subtitle">{{\App\CPU\translate('order_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <!-- Pages -->
                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/orders*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <i class="tio-shopping-cart nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('orders')}}
                                </span>
                            </a>
                            @php($sellerId = auth('seller')->id())
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{Request::is('seller/order*')?'block':'none'}}">

                                <li class="nav-item {{Request::is('seller/orders/list/all')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['all'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('All')}}</span>
                                        <span class="badge badge-info badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                            {{ \App\Model\Order::where(['user_is'=>'dropship'])->where(['customer_id'=>$sellerId])->count()}}
                                        </span>
                                    </a>
                                </li>
                                {{-- <li class="nav-item {{Request::is('seller/orders/list/pending')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['pending'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('Pending')}}</span>
                                        <span class="badge badge-soft-info badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                            {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'pending'])->count()}}
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/confirmed')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['confirmed'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('confirmed')}}</span>
                                        <span class="badge badge-soft-info badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                            {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'confirmed'])->count()}}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/orders/list/processing')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['processing'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('Processing')}}</span>
                                        <span class="badge badge-warning badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                            {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'processing'])->count()}}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/orders/list/out_for_delivery')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['out_for_delivery'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('out_for_delivery')}}</span>
                                        <span class="badge badge-warning badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                            {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'out_for_delivery'])->count()}}
                                        </span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/orders/list/delivered')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['delivered'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('Delivered')}}</span>
                                        <span class="badge badge-success badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                            {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'delivered'])->count()}}
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/returned')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['returned'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('Returned')}}</span>
                                        <span class="badge badge-soft-danger badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                            {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'returned'])->count()}}
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/failed')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['failed'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('Failed')}}</span>
                                        <span class="badge badge-danger badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                            {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'failed'])->count()}}
                                        </span>
                                    </a>
                                </li>
                                <li class="nav-item {{Request::is('seller/orders/list/canceled')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.orders.list',['canceled'])}}" title="">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('canceled')}}</span>
                                        <span class="badge badge-danger badge-pill {{Session::get('direction') === "rtl" ? 'mr-1' : 'ml-1'}}">
                                            {{ \App\Model\Order::where(['seller_is'=>'seller'])->where(['seller_id'=>$sellerId])->where(['order_status'=>'canceled'])->count()}}
                                        </span>
                                    </a>
                                </li> --}}
                            </ul>
                        </li>
                        <!-- End Pages -->

                        <li class="nav-item">
                            <small class="nav-subtitle">{{\App\CPU\translate('product_management')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{(Request::is('seller/product*'))?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link nav-link-toggle" href="javascript:">
                                <i class="tio-premium-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Products')}}
                                </span>
                            </a>
                            <ul class="js-navbar-vertical-aside-submenu nav nav-sub"
                                style="display: {{(Request::is('seller/product*'))?'block':''}}">
                                <li class="nav-item {{Request::is('seller/product/list')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.product.list')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('Products')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/product/bulk-import')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.product.bulk-import')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('bulk_import')}}</span>
                                    </a>
                                </li>

                                <li class="nav-item {{Request::is('seller/product/bulk-export')?'active':''}}">
                                    <a class="nav-link " href="{{route('seller.product.bulk-export')}}">
                                        <span class="tio-circle nav-indicator-icon"></span>
                                        <span class="text-truncate">{{\App\CPU\translate('bulk_export')}}</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/reviews/list*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.reviews.list')}}">
                                <i class="tio-star nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('Product')}} {{\App\CPU\translate('Reviews')}}
                                </span>
                            </a>
                        </li>


                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/messages*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.messages.chat')}}">
                                <i class="tio-email nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('messages')}}
                                </span>
                            </a>
                        </li>
                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/profile*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.profile.view')}}">
                                <i class="tio-shop nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('my_bank_info')}}
                                </span>
                            </a>
                        </li>


                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/shop*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.shop.view')}}">
                                <i class="tio-home nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate">
                                    {{\App\CPU\translate('my_shop')}}
                                </span>
                            </a>
                        </li>


                        <!-- End Pages -->
                        <li class="nav-item {{( Request::is('seller/business-settings*'))?'scroll-here':''}}">
                            <small class="nav-subtitle" title="">{{\App\CPU\translate('business_section')}}</small>
                            <small class="tio-more-horizontal nav-subtitle-replacer"></small>
                        </li>
                        @php($shippingMethod = \App\CPU\Helpers::get_business_settings('shipping_method'))
                        @if($shippingMethod=='sellerwise_shipping')
                            <li class="navbar-vertical-aside-has-menu {{Request::is('seller/business-settings/shipping-method*')?'active':''}}">
                                <a class="js-navbar-vertical-aside-menu-link nav-link"
                                href="{{route('seller.business-settings.shipping-method.add')}}">
                                    <i class="tio-settings nav-icon"></i>
                                    <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate text-capitalize">
                                        {{\App\CPU\translate('shipping_method')}}
                                    </span>
                                </a>
                            </li>
                        @endif

                        <li class="navbar-vertical-aside-has-menu {{Request::is('seller/business-settings/withdraws*')?'active':''}}">
                            <a class="js-navbar-vertical-aside-menu-link nav-link"
                               href="{{route('seller.business-settings.withdraw.list')}}">
                                <i class="tio-wallet-outlined nav-icon"></i>
                                <span class="navbar-vertical-aside-mini-mode-hidden-elements text-truncate text-capitalize">
                                        {{\App\CPU\translate('withdraws')}}
                                    </span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!-- End Content -->
            </div>
        </div>
    </aside>
</div>

