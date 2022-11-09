@extends('layouts.master-sp')

@section('title', $title)

@push('styles')
<link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />


@endpush

@push('plugins')
<script src="{{ assets('global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/morris/raphael-min.js') }}" type="text/javascript"></script>
@endpush


@push('scripts')
<script src="{{ assets('pages/scripts/dashboard.js') }}" type="text/javascript"></script>
@endpush


@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Beranda Simpan Pinjam
            </h1>
        </div>
    </div>
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-6">
            <div class="portlet light bordered">
              <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-bubble font-dark hide"></i>
                        <span class="caption-subject font-hide bold uppercase">Total Anggota</span>
                    </div>

              </div>
              <div class="portlet-body">
                  <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                      <thead>
                          <tr>
                              <th>Jenis Anggota</th>
                              <th>Total</th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach($anggota as $value)
                          <tr>
                            <td>{{ sp_array_mdrray_search(sp_member_status(), 'id','name', $value->member_status) }}</td>
                            <td>{{ $value->total }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
              </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="portlet light bordered">
              <div class="portlet-title">
                    <div class="caption">
                        <i class="icon-bubble font-dark hide"></i>
                        <span class="caption-subject font-hide bold uppercase">Daftar Pinjaman</span>
                    </div>

              </div>
              <div class="portlet-body">
                  <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                      <thead>
                          <tr>
                              <th>Nama </th>
                              <th>Total Pinjaman</th>
                          </tr>
                      </thead>
                      <tbody>
                        @foreach($pinjaman as $val)
                          <tr>
                            <td>{{ $val->anggota->first_name .' '.$val->anggota->last_name }}</td>
                            <td>{{ toRp($val->total_pinjaman) }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                  </table>
              </div>
            </div>
        </div>
      </div>
    </div>
</div>
<!-- END PAGE CONTENT BODY -->


@endsection
