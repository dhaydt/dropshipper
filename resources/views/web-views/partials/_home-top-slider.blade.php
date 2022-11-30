<style>
    /* .just-padding {
        padding: 15px;
        border: 1px solid #ccccccb3;
        border-bottom-left-radius: 5px;
        border-bottom-right-radius: 5px;
        height: 100%;
        background-color: white;
    } */
    .carousel-banner-row {
            height: 369px;
        }
    .carousel-inner{
        border-radius: 20px;
        height: 100%;
    }
    .carousel-item {
        width: 100%;
    }

    .carousel-item img{
        width: 100%;
        height: auto;
        /* margin-top: -90px; */
    }
    @media(max-width: 600px){
        .row.rtl {
        }
        .carousel-banner-row {
            height: 154px;
            position: relative;
        }
        .carousel.slide {
            position: absolute;
            top: 0;
            bottom: 0;
            left: -7px;
            right: -7px;
        }

        .carousel-indicators {
            bottom: -22px
        }

        .indicators {
            width: 20px !important;
        }

        .carousel-inner {
            border-radius: 5px;
            height: 100%;
        }


        .carousel-inner .carousel-item a img{
            transition: .3s;
            height: auto;
            border-radius: 5px;
            width: 100%;
            /* margin-top: -20px; */
        }
    }

    @media(max-width: 500px){
        .carousel-banner-row {
            height: 145px;
        }

        .carousel-indicators {
            bottom: -13px
        }

        .carousel-inner .carousel-item a img{
            height: 145px;
        }
    }
    @media(max-width: 380px){
        .carousel-banner-row {
            height: 125px;
        }
        .carousel-inner {
            height: 125px;
            width: auto;
        }

        .carousel-inner .carousel-item a img{
            height: 125px;
        }

    }
</style>

<div class="row rtl mb-2">
    <div class="col-xl-12 col-md-12 carousel-banner-row">
        @php($main_banner=\App\Model\Banner::where('banner_type','Main Banner')->where('published',1)->orderBy('id','desc')->get())
        <div id="carouselExampleIndicators" class="carousel slide h-100" data-ride="carousel">
            <ol class="carousel-indicators">
                @foreach($main_banner as $key=>$banner)
                    <li data-target="#carouselExampleIndicators" class="indicators" data-slide-to="{{$key}}"
                        class="{{$key==0?'active':''}}">
                    </li>
                @endforeach
            </ol>
            <div class="carousel-inner h-100">
                @foreach($main_banner as $key=>$banner)
                    <div class="carousel-item h-100 {{$key==0?'active':''}}">
                        <a href="//{{$banner['url']}}">
                            <img class="d-block h-100"
                                 onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                                 src="{{asset('storage/banner')}}/{{$banner['photo']}}"
                                 alt="">
                        </a>
                    </div>
                @endforeach
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button"
               data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">{{\App\CPU\translate('Previous')}}</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button"
               data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">{{\App\CPU\translate('Next')}}</span>
            </a>
        </div>

        {{-- <div class="row mt-2">
            @foreach(\App\Model\Banner::where('banner_type','Footer Banner')->where('published',1)->orderBy('id','desc')->take(3)->get() as $banner)
                <div class="col-4">
                    <a data-toggle="modal" data-target="#quick_banner{{$banner->id}}"
                       style="cursor: pointer;">
                        <img class="d-block footer_banner_img" style="width: 100%"
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
        </div> --}}
    </div>
    <!-- Banner group-->
</div>


<script>
    $(function () {
        $('.list-group-item').on('click', function () {
            $('.glyphicon', this)
                .toggleClass('glyphicon-chevron-right')
                .toggleClass('glyphicon-chevron-down');
        });
    });
</script>
