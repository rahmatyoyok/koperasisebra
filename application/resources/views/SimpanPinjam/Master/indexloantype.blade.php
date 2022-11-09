@extends('layouts.master-sp')

@section('title', $title)

@push('styles')
    <link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ assets('global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->

@endpush

@push('plugins')
    <script src="{{ assets('global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
    var BaseUrl = '{{ url('/') }}';
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ assets('pages/scripts/SimpanPinjam/loan.type.index.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>DAFTAR JENIS PINJAMAN</h1>
        </div>
    </div>
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-lg-12 col-xs-12 col-sm-12">
              <div class="portlet light bordered">
                  <div class="portlet-body">
                      <div class="row">
                        <div class="col-md-12">
                          <!-- BEGIN SAMPLE TABLE PORTLET-->
                          <div class="portlet light ">
                              <div class="portlet-title">
                                    <div class="caption font-green">
                                        <i class="icon-users font-green"></i>
                                        <span class="caption-subject bold uppercase">Daftar</span>
                                    </div>
                                    <div class="tools"> </div>
                              </div>
                              <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover dt-responsive dtr-inline" width="100%" id="table_komfigsimpananwajib">
                                        <thead>
                                            <tr>
                                                <th> Kode </th>
                                                <th> Nama </th>
                                                <th> % Bunga </th>
                                                <th> Tenor </th>
                                                <th> Model Bunga </th>
                                                <th> Aksi </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                        <tfoot>
                                        <tr>
                                            <th class="form-group form-md-line-input">Kode</th>
                                            <th class="form-group form-md-line-input">Nama</th>
                                            <th class="form-group form-md-line-input">% Bunga</th>
                                            <th class="form-group form-md-line-input">Tenor</th>
                                            <th class="form-group form-md-line-input"> Model Bunga </th>
                                            <th class="text-center" width="150">Aksi</th>
                                        </tr>
                                        </tfoot>
                                    </table>
                              </div>
                          </div>
                          <!-- END SAMPLE TABLE PORTLET-->
                        </div>
                      </div>
                  </div>
              </div>
          </div>
      </div>
    </div>
</div>
<!-- END PAGE CONTENT BODY -->


@endsection
