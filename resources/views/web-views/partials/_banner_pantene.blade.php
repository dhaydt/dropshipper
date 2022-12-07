<div class="my-4">
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
        @foreach ($berlimpah['products'] as $b_p)
            <div class="col-lg-2 col-md-3 col-4">
                <div class="pantene-card">
                    <img src="{{ asset('storage/deal').'/'.$b_p['images'] }}" alt="" class="w-100" style="max-height: 200px">
                    <div class="pantene-desc bg-green">
                        Top Produk
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
