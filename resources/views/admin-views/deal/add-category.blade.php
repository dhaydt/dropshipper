@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Diskon Berlimpah Kategori'))
@push('css_or_js')
    <link href="{{ asset('public/assets/select2/css/select2.min.css')}}" rel="stylesheet">
    <link href="{{ asset('public/assets/back-end/css/custom.css')}}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{ \App\CPU\translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{ \App\CPU\translate('diskon_berlimpah')}}</li>
            <li class="breadcrumb-item">{{ \App\CPU\translate('Add new category')}}</li>
        </ol>
    </nav>

    <!-- Content Row -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h1 class="h3 mb-0 text-black-50">{{$deal['title']}}</h1>
                </div>
                <div class="card-body">
                    <form action="{{route('admin.deal.add-category',[$deal['id']])}}" method="post">
                        @csrf
                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="name">{{ \App\CPU\translate('Add new category')}}</label>
                                    <select
                                        class="js-example-basic-multiple js-states js-example-responsive form-control"
                                        name="product_id">
                                        @foreach (\App\Model\Category::orderBy('name', 'asc')->get() as $key => $product)
                                            <option value="{{ $product->id }}">
                                                {{$product['name']}}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <button type="submit"
                                    class="btn btn-primary float-right">{{ \App\CPU\translate('add')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row" style="margin-top: 20px">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ \App\CPU\translate('Category')}} {{ \App\CPU\translate('Table')}}</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th scope="col">{{ \App\CPU\translate('sl')}}</th>
                                <th scope="col">{{ \App\CPU\translate('name')}}</th>
                                @if ($deal['deal_type'] == 'berlimpah')
                                <th scope="col">{{ \App\CPU\translate('Banner')}}</th>
                                @endif
                                {{--<th scope="col">{{ \App\CPU\translate('discount')}}</th>
                                <th scope="col">{{ \App\CPU\translate('discount_type')}}</th>--}}
                                <th scope="col" style="width: 50px">{{ \App\CPU\translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $k=>$de_p)

                                <tr>
                                    <th scope="row">{{$products->firstitem()+$k}}</th>
                                    <td>{{$de_p['name']}}</td>
                                    @if ($deal['deal_type'] == 'berlimpah')
                                    <td>
                                        <form action="{{ route('admin.deal.banner-berlimpah') }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="cat_id" value="{{ $de_p['id'] }}">
                                        <input type="hidden" name="deal_id" value="{{ $deal['id'] }}">
                                        @php($productDeal = \App\CPU\Helpers::getProductDealsImg($de_p['id'], $deal['id']))
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <center>
                                                    <img style="width: auto;border: 1px solid; border-radius: 10px; width: 150px; height:200px;" id="viewer{{ $de_p['id'] }}" onerror="this.src='{{asset('public/assets/back-end/img/1920x400/img2.jpg')}}'"
                                                        src="{{ asset('storage/deal'.'/'.$productDeal['images']) }}" alt="banner image"/>
                                                </center>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for="name">{{\App\CPU\translate('Upload')}} {{\App\CPU\translate('Banner')}} {{ $de_p['name'] }}</label>
                                                <div class="form-group" style="text-align: left">
                                                    <input type="file" name="banner" class="form-control">
                                                    {{-- <input type="file" name="image" id="customFileUpload{{ $de_p['id'] }}" class="custom-file-input"
                                                        accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*">
                                                    <label class="custom-file-label" for="customFileUpload">{{\App\CPU\translate('choose')}} {{\App\CPU\translate('file')}}</label> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row w-100">
                                            <button class="btn btn-primary ml-auto" type="submit">Upload</button>
                                        </div>
                                        </form>
                                    </td>
                                    @endif
                                    {{-- <td>{{$de_p->discount}}</td> --}}
                                    {{-- <td>{{$de_p->discount_type}}</td> --}}
                                    <td>
                                        <a href="{{route('admin.deal.delete-product',[$de_p['id']])}}"
                                           class="btn btn-danger btn-sm">
                                           {{ trans ('Delete')}}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <table>
                            <tfoot>
                                {!! $products->links() !!}
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script src="{{asset('public/assets/back-end')}}/js/select2.min.js"></script>
    <script>
        function selectImage(id){
            $("#customFileUpload" + id).val(function () {
                readURL(this, id);
            });
        }
        function readURL(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer'+id).attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }


        $(".js-example-theme-single").select2({
            theme: "classic"
        });

        $(".js-example-responsive").select2({
            width: 'resolve'
        });

        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });

        $(document).on('change', '.status', function () {
            var id = $(this).attr("id");
            if ($(this).prop("checked") == true) {
                var status = 1;
            } else if ($(this).prop("checked") == false) {
                var status = 0;
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            $.ajax({
                url: "{{route('admin.deal.status-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function () {
                    toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                }
            });
        });
    </script>
@endpush
