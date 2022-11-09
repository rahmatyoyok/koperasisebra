@extends('layouts.master-sp')

@section('title', $title)

@push('styles')
    <link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ assets('global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->

    <style>
    .ui-datepicker-calendar {
        display: none;
    }
    </style>
@endpush

@push('plugins')
    <script src="{{ assets('global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js') }}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
    var BaseUrl = '{{ url('/') }}';
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ assets('pages/scripts/SimpanPinjam/simpananwajib.proses_kalkulasi.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Proses Simpanan Wajib Per-Periode</h1>
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
                  <div class="portlet-title">
                      <div class="caption">
                          <i class="icon-bubble font-dark hide"></i>
                          <span class="caption-subject font-hide bold uppercase">Daftar</span>
                      </div>

                  </div>
                  <div class="portlet-body">

                      <div class="row">
                        <div class="col-md-6">
                          <form action="#" id="form_sample_3" class="form-horizontal">
                            <div class="form-body">
                                <div class="form-group form-md-line-input">
                                    <label class="control-label col-md-2">Periode</label>
                                    <div class="col-md-4">
                                      <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
                                          <span class="input-group-addon">
                                              <i class="fa fa-calendar"></i>
                                          </span>
                                          <input type="text" class="form-control text-right" name="periodekKalkulasi" value="{{ $currentMonth }}" />
                                          <div class="form-control-focus"> </div>
                                      </div>   
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="button" id="StartKalkulasi" class="btn green">Proses Kalulkasi</button>
                                        <button type="button" id="PostingKalkulasi" class="btn red-haze">Posting Kalulkasi</button>
                                    </div>
                                </div>
                            </div>
                          </form>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <!-- BEGIN SAMPLE TABLE PORTLET-->
                          <div class="portlet light ">
                              <div class="portlet-title">
                                  <div class="caption">
                                      <i class="icon-book-open font-red"></i>
                                      <span class="caption-subject font-red sbold uppercase"></span>
                                  </div>
                                  <div class="actions">
                                      <div class="btn-group btn-group-devided" data-toggle="buttons">
                                            <label class="btn btn-transparent green-seagreen btn-outline btn-sm active" id="exportExcel">
                                              <i class="fa fa-file-excel-o"></i> Export Excel
                                            </label>
                                            <label class="btn btn-transparent blue btn-outline btn-sm">
                                                <i class="fa fa-cloud-upload"></i> Import Data Transfer
                                            </label>
                                      </div>
                                  </div>
                              </div>
                              <div class="portlet-body">
                                  <div class="table-scrollable">
                                    <table class="table table-striped table-bordered table-hover dt-responsive dtr-inline" width="100%" id="table_simpananwajib">
                                          <thead>
                                            <tr>
                                                <th class="all"> NIAK </th>
                                                <th class="all"> Nama </th>
                                                <th class="all"> Tempat, Tanggal Lahir </th>
                                                <th class=""> No. Identitas </th>
                                                <th class="all"> Unit Kerja </th>
                                                <th class=""> Status Anggota </th>
                                                <th class=""> Nilai </th>
                                                <th class=""> Status </th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                          <tfoot>
                                          <tr>
                                              <th class="form-group form-md-line-input">NIAK</th>
                                              <th class="form-group form-md-line-input">Nama</th>
                                              <th class="form-group form-md-line-input">Tempat, Tanggal Lahir</th>
                                              <th class="form-group form-md-line-input">No. Identitas</th>
                                              <th class="form-group form-md-line-input">Unit Kerja</th>
                                              <th class="form-group form-md-line-input"> Status Anggota </th>
                                              <th class="form-group form-md-line-input">Nilai</th>
                                              <th class="text-center" width="150">Status</th>
                                          </tr>
                                      </table>
                                  </div>
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
