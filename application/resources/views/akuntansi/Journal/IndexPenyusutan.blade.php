@extends('layouts.master-ak')

@section('title', $title)

@push('styles')
    <link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ assets('global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->

    <style>
        .ui-datepicker-calendar {
            display: none;
        }
    
        .table thead tr th, .table tfoot tr th,  {
            font-size: 10px!important;
            font-weight: 600;
        }
    
        .table tbody tr td {
            font-size: 10px;
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

    <script src="{{ assets('global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script>

@endpush

@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
        
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ assets('pages/scripts/akuntansi/penyusutan.index.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1> Penyusutan Asset Tetap </h1>
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
                                    <div class="actions">
                                        <button type="button" data-target="#stack1" data-toggle="modal" id="showKalkulasi" class="btn btn-success">
                                            <i class="fa fa-calculator"></i> Kakulasi Penyusutan
                                        </button>
                                    </div>
                              </div>
                                <div class="portlet-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tb_list_penyusutan" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>COA</th>
                                                        <th>Nama</th>
                                                        <th>Tanggal Perolehan</th>
                                                        <th>Total Nilai Perolehan</th>
                                                        <th>Masa Manfaat</th>
                                                        <th>Periode Akhir</th>
                                                        <th>Sisa Masa Manfaat</th>
                                                        <th>Total Penyusutan</th>
                                                        <th>Nilai Buku Akhir</th>
                                                        <th class="text-center" width="150">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
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


<!-- stackable -->
<div id="stack1" class="modal fade" tabindex="-1" data-focus-on="input:first">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Kalkulasi Penyusutan</h4>
    </div>
    <div class="modal-body">
        <p> Periode : </p>
        <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                <input type="text" class="form-control text-right" name="periodekKalkulasi" value="{{ $currentMonth }}" readonly/>
                <input type="hidden" class="form-control text-right" name="pmperiode" value="{{ $currentMonthIndo }}" />
            <div class="form-control-focus"> </div>
        </div>   
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark">Batal</button>
        <button class="btn blue" data-toggle="modal" href="#stack2">Kalkulasi</button>
    </div>
</div>
<div id="stack2" class="modal fade" tabindex="-1" data-focus-on="input:first">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Kalkulasi Penyusutan</h4>
    </div>
    <div class="modal-body">
        <p> Apakah anda yakin melakukan kalkulasi penyusutan?</p>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn red">Tidak</button>
        <button type="button" class="btn green" id="actionkalkulasi">Ya</button>
    </div>
</div>

<div id="stack3" class="modal fade" tabindex="-1" data-focus-on="input:first">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Kalkulasi Penyusutan</h4>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger"><strong>Error!</strong> Semua aset sudah terkalkulasi diperiode ini. </div>
    </div>
</div>



@endsection
