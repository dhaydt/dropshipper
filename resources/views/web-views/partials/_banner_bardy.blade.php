@push('css_or_js')
    <style>
        /* Bardy Banner Style */
    .css-1bx5ylf {
        display: block;
        position: relative;
    }
    .css-ml2lxs {
        height: 1px;
        background: transparent;
    }
    .css-1rv6yew-unf-divider {
        width: 100%;
        height: 8px;
        background-color: var(--NN50,#F0F3F7);
    }
    .css-1xnb600 {
        display: block;
        position: relative;
        overflow: hidden;
    }
    .css-1n8xidt {
        display: flex;
        justify-content: space-between;
    }
    .css-1e96gai {
        display: flex;
        flex: 1 1 auto;
        -webkit-box-align: center;
        align-items: center;
        overflow: hidden;
    }
    .css-193dyj3 {
        display: block;
        color: #000;
        font-size: 1.14286rem;
        font-weight: 800;
        font-family: "Open Sauce One", "Nunito Sans", -apple-system, sans-serif;
        line-height: 1.28571rem;
        overflow: hidden;
        overflow-wrap: break-word;
        white-space: nowrap;
        text-overflow: ellipsis;
        letter-spacing: 0.2px;
    }
    .css-5txbi7 {
        font-size: 0.857143rem;
        font-weight: 800;
        font-family: "Open Sauce One", -apple-system, sans-serif;
        line-height: 1.14286rem;
        color: {{ $web_config['primary_color'] }} !important;
        margin: auto 0px auto auto;
        -webkit-box-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        align-items: center;
        letter-spacing: 0.1px;
    }
    .css-49i75y {
        display: flex;
        flex: 0 0 auto;
        -webkit-box-align: center;
        align-items: center;
        overflow: hidden;
        padding-left: 8px;
    }

    .css-1amgor6 {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
}
.css-1jke4yk {
    position: relative;
    width: 100%;
}
.css-bqlp8e.responsive {
    display: block;
}
.css-bqlp8e {
    position: relative;
    display: inline-block;
    opacity: 1;
    border: 0px;
    margin: 0px;
    padding: 0px;
    width: initial;
    height: initial;
    background: none;
    overflow: hidden;
    box-sizing: border-box;
}
.css-10rucli {
    display: block;
    width: initial;
    height: initial;
    opacity: 1;
    border: 0px;
    margin: 0px;
    padding: 100% 0px 0px;
    background: none;
    box-sizing: border-box;
    overflow: hidden;
}
.css-10rucli.responsive {
    display: block;
}
.css-1c345mg {
    position: absolute;
    inset: 0px;
    width: 0px;
    height: 0px;
    min-width: 100%;
    max-width: 100%;
    min-height: 100%;
    max-height: 100%;
    display: block;
    padding: 0px;
    margin: auto;
    border: none;
    box-sizing: border-box;
}
.css-1jke4yk {
    position: relative;
    width: 100%;
}
.css-bqlp8e.responsive {
    display: block;
}
.css-bqlp8e {
    position: relative;
    display: inline-block;
    opacity: 1;
    border: 0px;
    margin: 0px;
    padding: 0px;
    width: initial;
    height: initial;
    background: none;
    overflow: hidden;
    box-sizing: border-box;
}
.css-10rucli.responsive {
    display: block;
}
.css-10rucli {
    display: block;
    width: initial;
    height: initial;
    opacity: 1;
    border: 0px;
    margin: 0px;
    padding: 100% 0px 0px;
    background: none;
    box-sizing: border-box;
    overflow: hidden;
}
.css-1c345mg {
    position: absolute;
    inset: 0px;
    width: 0px;
    height: 0px;
    min-width: 100%;
    max-width: 100%;
    min-height: 100%;
    max-height: 100%;
    display: block;
    padding: 0px;
    margin: auto;
    border: none;
    box-sizing: border-box;
}
    </style>
    <style>
        @media(max-width: 700px){
            .css-1xnb600 .bardy-container{
                max-height: 360px;
                min-height: 360px;
            }
            /* .css-1xnb600 .banner-col{
                height: 140px;
            } */
            .css-1xnb600 .banner-item{
                height: 180px;
            }
            .css-1xnb600 .desc-banner{
                height: 25px;
            }
            .css-1xnb600 .desc-banner .title{
                font-size: 12px;
            }
            .css-1xnb600 .banner-item img {
                height: 85%;
                width: 100%;
            }
        }
        .bardy-container{
            max-height: 700px;
            min-height: 700px;
            overflow: hidden;
            border-radius: 10px;
            margin: 0;
            box-shadow: 2px 2px 3px #00000030 !important;
        }
        .banner-bardy{
            height: 100%;
        }
        .banner-item{
            height: 350px;
            position: relative;
        }

        .desc-banner{
            /* position: absolute; */
            bottom: 0;
            left: 0;
            width: 100%;
            display:flex;
            justify-content: center;
            align-items:center;
            height: 40px;
        }
        .desc-banner .title{
            font-size: 20px;
            font-weight: 700;
            color: #fff;
        }
        .banner-item img{
            height: 89%;
            width: 100%;
        }
    </style>
@endpush
<div class="css-1bx5ylf mb-4 mt-4">
        {{-- <div class="css-1rv6yew-unf-divider css-ml2lxs" data-unify="Divider"></div> --}}
        <div class="css-1xnb600" data-testid="divLegoImageChannel">
            <div class="section-header mb-2">
                <div class="feature_header">
                  <span class="for-feature-title">{{ $unggulan['title'] }}</span>
                  {{ var_dump($unggulan['background_color']) }}
                </div>
                {{-- <div class="view-all">
                  <a class="btn btn-outline-accent btn-sm viw-btn-a"
                    href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
                    {{ \App\CPU\translate('view_all')}}
                    <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1' }}"></i>
                  </a>
                </div> --}}
              </div>
              {{-- New Bardy --}}
              <div class="row bardy-container" style="background-color: {{ $unggulan['background_color'] }}">
                <div class="col-5 p-0 banner-col">
                    <div class="banner-bardy w-100 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('storage/deal').'/'.$unggulan['banner'] }}" alt="">
                    </div>
                </div>
                <div class="col-7 p-0 h-100">
                    <div class="row h-100">
                        @foreach ($unggulan['products'] as $key=>$deal)
                        <div class="col-6 col-md-4 p-0 position-relative">
                            <div class="banner-item">
                                <a href="{{route('product',$product->slug)}}">
                                    <img src="{{\App\CPU\ProductManager::product_image_path('thumbnail')}}/{{$deal->product['thumbnail']}}" alt="" style="border-right: 1px solid grey">
                                </a>
                                <div class="desc-banner" style="background-color: {{ $unggulan['background_color'] }}">
                                    <span class="title">
                                        {{ Str::limit($deal->product->name, 19) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
              </div>

              {{-- Old Bardy --}}
            {{-- <div class="css-1amgor6" data-testid="cntrHomeLegoImage">
                <a href="https://www.tokopedia.com/discovery/adidas-brandday?source=homepage.6_image.0.238732" class="css-1jke4yk" data-testid="aHomeLegoImage">
                    <div class="css-bqlp8e responsive">
                        <span class="responsive css-10rucli"></span>
                        <img class="css-1c345mg" decoding="async" src="https://images.tokopedia.net/img/cache/400-square/phZFtb/2022/11/24/2eb2e77a-4c70-47ef-8ba5-b5e0ac10841e.jpg" alt="Lego Channel" crossorigin="anonymous">
                    </div>
                </a>
                <a href="https://www.tokopedia.com/discovery/adidas-brandday?source=homepage.6_image.0.238732" class="css-1jke4yk" data-testid="aHomeLegoImage">
                    <div class="css-bqlp8e responsive">
                        <span class="responsive css-10rucli"></span>
                        <img class="css-1c345mg" decoding="async" src="https://images.tokopedia.net/img/cache/400-square/phZFtb/2022/11/24/d519b4d4-c0aa-45cd-b641-c8d16cb889b4.jpg" alt="Lego Channel" crossorigin="anonymous">
                    </div>
                </a>
                <a href="https://www.tokopedia.com/discovery/adidas-brandday?source=homepage.6_image.0.238732" class="css-1jke4yk" data-testid="aHomeLegoImage">
                    <div class="css-bqlp8e responsive">
                        <span class="responsive css-10rucli"></span>
                        <img class="css-1c345mg" decoding="async" src="https://images.tokopedia.net/img/cache/400-square/phZFtb/2022/11/24/49d217b1-95e4-49eb-b4f6-300d8bfe8358.jpg" alt="Lego Channel" crossorigin="anonymous">
                    </div>
                </a>
                <a href="https://www.tokopedia.com/discovery/adidas-brandday?source=homepage.6_image.0.238732" class="css-1jke4yk" data-testid="aHomeLegoImage">
                    <div class="css-bqlp8e responsive">
                        <span class="responsive css-10rucli"></span>
                        <img class="css-1c345mg" decoding="async" src="https://images.tokopedia.net/img/cache/400-square/phZFtb/2022/11/24/393a0a0d-b405-4afd-9365-82b286f5e6a0.jpg" alt="Lego Channel" crossorigin="anonymous">
                    </div>
                </a>
                <a href="https://www.tokopedia.com/discovery/adidas-brandday?source=homepage.6_image.0.238732" class="css-1jke4yk" data-testid="aHomeLegoImage">
                    <div class="css-bqlp8e responsive">
                        <span class="responsive css-10rucli"></span>
                        <img class="css-1c345mg" decoding="async" src="https://images.tokopedia.net/img/cache/400-square/phZFtb/2022/11/24/dafd8db7-b07d-4fc6-ad3e-21d32ca366b0.jpg" alt="Lego Channel" crossorigin="anonymous">
                    </div>
                </a>
                <a href="https://www.tokopedia.com/discovery/adidas-brandday?source=homepage.6_image.0.238732" class="css-1jke4yk" data-testid="aHomeLegoImage">
                    <div class="css-bqlp8e responsive">
                        <span class="responsive css-10rucli"></span>
                        <img class="css-1c345mg" decoding="async" src="https://images.tokopedia.net/img/cache/400-square/phZFtb/2022/11/24/d1f44434-8eb8-4b5a-9dba-1eec3484cb90.jpg" alt="Lego Channel" crossorigin="anonymous">
                    </div>
                </a>
            </div> --}}
        </div>
    </div>
