@extends('layouts.back-end.app-seller')

@section('title',\App\CPU\translate('Product List'))

@push('css_or_js')

@endpush

@section('content')
    <div class="content container-fluid">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('seller.dashboard.index')}}">{{\App\CPU\translate('Dashboard')}}</a>
                </li>
                <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Products')}}</li>

            </ol>
        </nav>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div class="flex-start">
                            <h5>{{ \App\CPU\translate('Product')}} {{ \App\CPU\translate('Table')}}</h5>
                            <h5 class="mx-1"><span style="color: red;">({{ $products->total() }})</span></h5>
                        </div>

                        <div class="row justify-content-between align-items-center flex-grow-1">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-6 mb-3 mb-lg-0">
                                <form action="{{ url()->current() }}" method="GET">
                                    <!-- Search -->
                                    <div class="input-group input-group-merge input-group-flush">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="tio-search"></i>
                                            </div>
                                        </div>
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{\App\CPU\translate('Search by Product Name')}}" aria-label="Search orders" value="{{ $search }}" required>
                                        <button type="submit" class="btn btn-primary">{{\App\CPU\translate('search')}}</button>
                                    </div>
                                    <!-- End Search -->
                                </form>
                            </div>
                            {{-- <div class="col-lg-4">
                                <a href="{{route('seller.product.add-new')}}" class="btn btn-primary float-{{Session::get('direction') === "rtl" ? 'left' : 'right'}}">
                                    <i class="tio-add-circle"></i>
                                    <span class="text">{{\App\CPU\translate('Add new product')}}</span>
                                </a>
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body" style="padding: 0">
                        <div class="table-responsive">
                            <table id="datatable"
                                   style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                                   class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                                   style="width: 100%">
                                <thead class="thead-light">
                                <tr>
                                    <th>{{\App\CPU\translate('SL#')}}</th>
                                    <th>{{\App\CPU\translate('Product Name')}}</th>
                                    <th>{{\App\CPU\translate('price')}}</th>
                                    {{-- <th>{{\App\CPU\translate('selling_price')}}</th>
                                    <th>{{\App\CPU\translate('verify_status')}}</th>
                                    <th>{{\App\CPU\translate('Active')}} {{\App\CPU\translate('status')}}</th> --}}
                                    <th style="width: 5px" class="text-center">{{\App\CPU\translate('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($products as $k=>$p)
                                    <tr>
                                        <th scope="row">{{$products->firstitem()+ $k}}</th>
                                        <td><a href="{{route('seller.product.view',[$p['id']])}}">
                                                {{$p['name']}}
                                            </a></td>
                                        <td>
                                            {{\App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['dropship']))}}
                                        </td>
                                        {{-- <td>
                                            {{ \App\CPU\BackEndHelper::set_symbol(\App\CPU\BackEndHelper::usd_to_currency($p['unit_price']))}}
                                        </td>
                                        <td>
                                            @if($p->request_status == 0)
                                                <label class="badge badge-warning">{{\App\CPU\translate('New Request')}}</label>
                                            @elseif($p->request_status == 1)
                                                <label class="badge badge-success">{{\App\CPU\translate('Approved')}}</label>
                                            @elseif($p->request_status == 2)
                                                <label class="badge badge-danger">{{\App\CPU\translate('Denied')}}</label>
                                            @endif
                                        </td>
                                        <td>
                                            <label class="switch">
                                                <input type="checkbox" class="status"
                                                       id="{{$p['id']}}" {{$p->status == 1?'checked':''}}>
                                                <span class="slider round"></span>
                                            </label>
                                        </td> --}}
                                        <td>
                                            <a class="btn btn-primary btn-sm"
                                               href="{{route('seller.product.view',[$p['id']])}}">
                                               <i class="fa-solid fa-eye"></i>
                                                {{\App\CPU\translate('View')}}
                                            </a>
                                            @php($seller = auth('seller')->user())
                                            @php($desc = urlencode(strip_tags($p['details'])))
                                            @php($str = substr($desc, 0, 50) . '...')
                                            @php($url = env('ETOKO_URL').'/'.'generated'.'/'.$seller['id'].'/'.$seller->f_name.'_'.$seller->l_name.'/'.$p['slug'])
                                            <a class="btn btn-success btn-sm"
                                               href="{{ $url }}" target="_blank">
                                                <i class="fa-solid fa-share"></i>
                                                {{\App\CPU\translate('Share')}}
                                            </a>
                                            {{-- <a class="btn btn-primary btn-sm"
                                               href="{{route('seller.product.edit',[$p['id']])}}">
                                                <i class="tio-edit"></i>{{\App\CPU\translate('Edit')}}
                                            </a> --}}
                                            {{-- <a class="btn btn-danger btn-sm" href="javascript:"
                                               onclick="form_alert('product-{{$p['id']}}','{{\App\CPU\translate("Want to delete this item")}} ?')">
                                               <i class="tio-add-to-trash"></i> {{\App\CPU\translate('Delete')}}
                                            </a>
                                            <form action="{{route('seller.product.delete',[$p['id']])}}"
                                                  method="post" id="product-{{$p['id']}}">
                                                @csrf @method('delete')
                                            </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Footer -->
                     <div class="card-footer">
                        {{$products->links()}}
                    </div>
                    @if(count($products)==0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{asset('public/assets/back-end')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{\App\CPU\translate('No data to show')}}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>
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
                url: "{{route('seller.product.status-update')}}",
                method: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function (data) {
                    if(data.success == true) {
                        toastr.success('{{\App\CPU\translate('Status updated successfully')}}');
                    }
                    else if(data.success == false) {
                        toastr.error('{{\App\CPU\translate('Status updated failed. Product must be approved')}}');
                        location.reload();
                    }
                }
            });
        });
    </script>
@endpush
