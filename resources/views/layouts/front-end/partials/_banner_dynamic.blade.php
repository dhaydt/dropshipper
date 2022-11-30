<style>
    .banner_dynamic{
        height: 72px;
        width: 100%;
        position: relative;
    }
    .banner_dynamic img{
        height: 100%;
        width: 100%;
        background-color: #fff;
    }
    .downloadApp {
        border: none;
        position: absolute;
        right: 110px;
        top: 20px;
        width: 90px;
        border-radius: 8px;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .googleApp {
        border: none;
        position: absolute;
        right: 10px;
        top: 20px;
        width: 90px;
        border-radius: 8px;
        font-weight: 700;
        letter-spacing: 1px;
    }
    .closeBan {
        position: absolute;
        left: 0;
        color: #6c727c;
        font-size: 32px;
        font-weight: 600;
        margin-top: 0;
    }
    @media(max-width: 375px){
        .downloadApp, .googleApp{
            width: 90px;
            max-width: 90px;
        }
    }


</style>

@php($main_banner=\App\Model\Banner::where('banner_type','Header Banner')->where('published',1)->orderBy('id','desc')->first())
@if (isset($main_banner))
<div class="banner_dynamic" id="bannerDynamic">
    <img src="{{asset('storage/app/public/banner/'.$main_banner['photo'])}}" alt="">
    <button type="button" class="closeBan btn" aria-label="Close" onclick="closeBanner()">
        <span aria-hidden="true">&times;</span>
    </button>
    <a class="downloadApp" target="_blank"  href="{{ $main_banner['url2'] }}" role="button"><img
        src="{{asset("public/assets/front-end/png/apple_app.png")}}"
        alt="" style="height: 30px!important;">
    </a>
    <a class="googleApp" target="_blank"  href="{{ $main_banner['url'] }}" role="button"><img
            src="{{asset("public/assets/front-end/png/google_app.png")}}"
            alt="" style="height: 30px!important;">
    </a>
</div>
@endif
