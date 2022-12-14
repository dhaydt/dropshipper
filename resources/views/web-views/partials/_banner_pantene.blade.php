
@push('css_or_js')
    <style>
        .pantene-card .head{
            height: 396px;
            background: #D5FFF7;
            overflow: hidden;
        }
        .pantene-desc{
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90px;
            font-size: 28px;
            text-align: center;
            font-weight: 700;
            background-color: #0A6D56;
            color: #fff;
            text-shadow: 0px 4px 4px rgba(0, 0, 0, 0.25);
        }
        @media(max-width: 600px){
            .pantene-card .head{
                height: 242px;
            }
        }
    </style>
@endpush<div class="my-4">
    <div class="section-header mb-2">
        <div class="feature_header">
          <span class="for-feature-title">{{ $berlimpah['title'] }}</span>
        </div>
        {{-- <div class="view-all">
          <a class="btn btn-outline-accent btn-sm viw-btn-a"
            href="{{route('products',['id'=> $category['id'],'data_from'=>'category','page'=>1])}}">
            {{ \App\CPU\translate('view_all')}}
            <i class="czi-arrow-{{Session::get('direction') === " rtl" ? 'left mr-1 ml-n1' : 'right ml-1 mr-n1' }}"></i>
          </a>
        </div> --}}
    </div>
    <div class="section-body row">
        @foreach ($berlimpah['products'] as $key => $b_p)
            <div class="col-lg-2 col-md-3 col-4">
                <div class="pantene-card">
                    <a href="{{route('products',['id'=> $b_p['category_id'],'data_from'=>'category','page'=>1])}}" class="head text-center d-flex justify-content-center align-items-center">
                        <div class="img">
                            <img onerror="this.src='{{asset('public/assets/front-end/img/image-place-holder.png')}}'" src="{{ asset('storage/deal').'/'.$b_p['images'] }}" alt="" class="" style="max-height: 100%">
                        </div>
                    </a>
                    {{-- <div class="pantene-desc bg-green">
                        {{ $title[$key] }}
                    </div> --}}
                </div>
            </div>
        @endforeach
    </div>
</div>
