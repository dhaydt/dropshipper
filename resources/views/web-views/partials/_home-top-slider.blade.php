
@push('css_or_js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
@endpush
<style>
    .section-borders span.black-border {
    background: #333;
    width: 30px;
    margin: 0 6px;

}

.client-testimonial-carousel .owl-dots button {
    height: 5px;
    background: #cbcbcb !important;
    width: 5px;
    display: inline-block;
    margin: 5px;
    transition: .2s;
    border-radius: 2px;
}

.client-testimonial-carousel button.owl-dot.active {
    background: #545454 !important;
    width: 5px;
}

.client-testimonial-carousel .owl-dots {
    text-align: left;
    margin-top: 10px
}

.single-testimonial-item {
    position: relative;
    box-shadow: 0 0 2px #dadfd3;
    font-style: italic;
}

.single-testimonial-item h3 {
    font-size: 20px;
    font-style: normal;
    margin-bottom: 0;
}

.single-testimonial-item{
    height: 325px;
}

.single-testimonial-item h3 span {
    display: block;
    font-size: 12px;
    font-weight: normal;
    margin-top: 5px;
}
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
        .client-testimonial-carousel .owl-dots {
            text-align: center;
            margin-top: unset;
        }
        .carousel-banner-row {
            height: 215px;
            position: relative;
        }
        .single-testimonial-item{
            height: 210px;
        }
        .carousel.slide {
            position: absolute;
            top: 0;
            bottom: 0;
            left: -10px;
            right: -10px;
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
            height: 215px;
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
            height: 215px;
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

<div class="row rtl">
    <div class="col-xl-12 col-md-12 carousel-banner-row px-0">
        @php($main_banner=\App\Model\Banner::where('banner_type','Main Banner')->where('published',1)->orderBy('id','desc')->get())
        <div class="owl-carousel owl-carousel-banner client-testimonial-carousel">
            @foreach($main_banner as $key=>$banner)
            <div class="single-testimonial-item {{$key==0?'active':''}}">
                <a href="//{{$banner['url']}}" class="h-100">
                    <img class="d-block h-100"
                         onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'"
                         src="{{asset('storage/banner')}}/{{$banner['photo']}}"
                         alt="">
                </a>
            </div>
            @endforeach
        </div>
        <!--<div id="carouselExampleIndicators" class="carousel slide h-100" data-ride="carousel">
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
            <a class="carousel-control-prev d-none d-md-flex" href="#carouselExampleIndicators" role="button"
               data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">{{\App\CPU\translate('Previous')}}</span>
            </a>
            <a class="carousel-control-next d-none d-md-flex" href="#carouselExampleIndicators" role="button"
               data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">{{\App\CPU\translate('Next')}}</span>
            </a>
        </div> -->

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<script>
    $(document).ready(function(){
    $(".owl-carousel-banner").owlCarousel({
        autoplay: true,
        margin:10,
        loop:true,
        dots:true,
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
                    items: 1
                },
                //Small
                576: {
                    items: 1
                },
                //Medium
                768: {
                    items: 1
                },
                //Large
                992: {
                    items: 2
                },
                //Extra large
                1200: {
                    items: 2
                },
                //Extra extra large
                1400: {
                    items: 2
                }
            }
    //      nav:true,
    //      navText:["<i class='fas fa-long-arrow-alt-left'></i>","<i class='fas fa-long-arrow-alt-right'></i>" ]
    });
    });
    $(function () {
        $('.list-group-item').on('click', function () {
            $('.glyphicon', this)
                .toggleClass('glyphicon-chevron-right')
                .toggleClass('glyphicon-chevron-down');
        });
    });
</script>
