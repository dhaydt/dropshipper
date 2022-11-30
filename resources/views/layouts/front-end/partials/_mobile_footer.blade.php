<style>
    .profile-btn {
        max-width: 51px;
        min-width: 51px;
    }
</style>
<div class="mobile-footer d-block d-md-none" id="mobile-footer">
    <div class="unf-bottomnav css-15iqbvc">
        <a class="css-11rf802" href="{{route('home')}}">
            <div class="css-mw28ox"><img width="24" height="24"
                    src="https://assets.tokopedia.net/assets-tokopedia-lite/v2/atreus/kratos/20f068ca.svg" alt="home"
                    class="css-mw28ox"></div>Home
        </a>
        <a class="css-11rf802" href="{{route('wishlists')}}">
            <div class="css-mw28ox"><img width="24" height="24"
                    src="https://assets.tokopedia.net/assets-tokopedia-lite/v2/atreus/kratos/eb6fad37.svg"
                    alt="wishlist" class="css-mw28ox"></div>Wish List
        </a>
        <a class="css-11rf802" href="{{route('account-oder')}}" data-cy="bottomnavFeed" id="bottomnavFeed"
            data-testid="icnFooterFeed">
            <div class="css-mw28ox"><img width="24" height="24"
                    src="https://assets.tokopedia.net/assets-tokopedia-lite/v2/atreus/kratos/66eb4811.svg" alt="feed"
                    class="css-mw28ox"></div>Transaksi
        </a>
        @if(auth('customer')->check())
            <a class="css-11rf802" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-transform: capitalize">
                <div class="">
                    <div class="">
                        <img style="width: 24px;height: 24px"
                            src="{{asset('storage/app/public/profile/'.auth('customer')->user()->image)}}"
                            onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                            class="img-profile rounded-circle">
                    </div>
                </div>
                {{auth('customer')->user()->f_name}}
            </a>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{route('account-address')}}"> {{ \App\CPU\translate('my_address')}} </a>
                <a class="dropdown-item" href="{{route('account-tickets')}}"> {{ \App\CPU\translate('my_tickets')}} </a>
                <a class="dropdown-item" href="{{route('user-account')}}"> {{ \App\CPU\translate('my_profile')}}</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{route('customer.auth.logout')}}">{{ \App\CPU\translate('logout')}}</a>
            </div>
        @else
            <a class="css-11rf802 profile-btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="">
                    <div class="">
                        <i class="czi-user" style="font-size: 20px; color: #2e3137;"></i>
                    </div>
                </div>
                Akun
            </a>
            <div class="dropdown-menu bg-white" aria-labelledby="dropdownMenuButton"
                style="text-align: {{Session::get('direction') === " rtl" ? 'right' : 'left' }};">
                <a class="dropdown-item" href="{{route('customer.auth.login')}}">
                    <i class="fa fa-sign-in {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2' }}"></i>
                    {{\App\CPU\translate('sign_in')}}
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{route('customer.auth.register')}}">
                    <i class="fa fa-user-circle {{Session::get('direction') === " rtl" ? 'ml-2' : 'mr-2'
                        }}"></i>{{\App\CPU\translate('sign_up')}}
                </a>
            </div>
        @endif
    </div>
</div>
