@extends('layouts.back-end.app')

@push('css_or_js')
<!-- Custom styles for this page -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Custom styles for this page -->
@endpush

@section('content')
<div class="content container-fluid">
  <div class="card">
    <div class="card-header">
      <h5 class="text-center"><i class="tio-settings-outlined"></i>
        {{\App\CPU\translate('JNE_Configuration')}}
      </h5>
      <div class="card-toolbar">

      </div>
    </div>
    <div class="card-body">
      {{-- <form action="{{route('admin.business-settings.shipping-method.jne.store')}}" method="post"
        enctype="multipart/form-data">
        @csrf
        <div class="">
          <label for="Branch">Branch</label>
          <input type="file" name="branch">
        </div>

        <div class="">
          <label for="Destination">Destination</label>
          <input type="file" name="dest">
        </div>
        <div class="">
          <label for="Origin">Origin</label>
          <input type="file" name="origin">
        </div>

        <button type="submit">Save</button>
      </form> --}}
      <form action="{{ route('admin.business-settings.shipping-method.jne.store.setting') }}" method="POST">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <label for="" class="form-label">Branch JNE</label>
            <select class="form-control branch" name="branch" style="width: 100%">
              <option value="">-- Pilih Branch JNE --</option>
              @foreach ($branch as $b)
                <option value="{{ $b['branch_code'] }}" {{ $o_branch == $b['branch_code'] ? 'selected' : '' }}>{{ $b['branch_name'] }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6">
            <label for="" class="form-label">Dikirim dari:</label>
            <select class="form-control origin" name="origin" style="width: 100%">
              <option value="">-- Pilih asal pengiriman --</option>
              @foreach ($origin as $o)
                <option value="{{ $o['origin_code'] }}" {{ $o_origin == $o['origin_code'] ? 'selected' : '' }}>{{ $o['origin_name'] }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-12 mt-4">
            <button type="submit" class="btn btn-primary">{{\App\CPU\translate('submit')}}</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('script_2')
  <script>
    $(document).ready(function () {
            $('.branch').select2({});
            $('.origin').select2({});
        });
  </script>
@endpush