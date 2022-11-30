@extends('layouts.back-end.app')
@section('title', \App\CPU\translate('Employee List'))
@push('css_or_js')
    <!-- Custom styles for this page -->
    <link href="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="content container-fluid">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">{{\App\CPU\translate('Dashboard')}}</a></li>
            <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('Employee')}}</li>
            <li class="breadcrumb-item" aria-current="page">{{\App\CPU\translate('List')}}</li>
        </ol>
    </nav>

    <div class="row" style="margin-top: 20px">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{\App\CPU\translate('employee_table')}}</h5>
                    <a href="{{route('admin.employee.add-new')}}" class="btn btn-primary  float-right">
                        <i class="tio-add-circle"></i>
                        <span class="text">{{\App\CPU\translate('Add')}} {{\App\CPU\translate('New')}}</span>
                    </a>
                </div>
                <div class="card-body" style="padding: 0">
                    <div class="table-responsive">
                        <table id="datatable" style="text-align: {{Session::get('direction') === "rtl" ? 'right' : 'left'}};"
                               class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table"
                               style="width: 100%">
                            <thead class="thead-light">
                            <tr>
                                <th>{{\App\CPU\translate('SL')}}#</th>
                                <th>{{\App\CPU\translate('Name')}}</th>
                                <th>{{\App\CPU\translate('Email')}}</th>
                                <th>{{\App\CPU\translate('Phone')}}</th>
                                <th>{{\App\CPU\translate('Role')}}</th>
                                <th style="width: 50px">{{\App\CPU\translate('action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($em as $k=>$e)
                            @if($e->role)
                                <tr>
                                    <th scope="row">{{$k+1}}</th>
                                    <td class="text-capitalize">{{$e['name']}}</td>
                                    <td >
                                      {{$e['email']}}
                                    </td>
                                    <td>{{$e['phone']}}</td>
                                    <td>{{$e->role['name']}}</td>
                                    <td>
                                        <a href="{{route('admin.employee.update',[$e['id']])}}"
                                           class="btn btn-primary btn-sm">
                                           {{\App\CPU\translate('Edit')}}
                                        </a>
                                    </td>
                                </tr>
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{$em->links()}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <!-- Page level plugins -->
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="{{asset('public/assets/back-end')}}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <!-- Page level custom scripts -->
    <script>
        // Call the dataTables jQuery plugin
        $(document).ready(function () {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
