@extends('layouts.master-ak')

@section('title', $title)

@push('styles')
    <link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />
    
    <link href="{{ assets('global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-modal/css/bootstrap-modal.css') }}" rel="stylesheet" type="text/css" />

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
    
    <script src="{{ assets('global/plugins/bootstrap-modal/js/bootstrap-modalmanager.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-modal/js/bootstrap-modal.js') }}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
        var jurnalTypes = "{{ $codeTransaksi }} ";
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ assets('pages/scripts/akuntansi/jurnal.index.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1> {{ $descTransaksi }} </h1>
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
                                        <i class="fa fa-list-alt font-green"></i>
                                        <span class="caption-subject bold uppercase">Daftar</span>
                                    </div>
                                    <div class="actions">
                                        <a href="{{ $actionAdd }}" class="btn sbold green"> <i class="fa fa-plus"></i> Tambah Transaksi {{ $nameTransaksi }} </a>
                                        <a href="#showModalUpload" data-toggle="modal" class="btn sbold green-meadow"> <i class="fa fa-file-excel-o"></i> Import Transaksi </a>
                                        
                                    </div>
                              </div>
                              <div class="portlet-body">
                                    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_jurnal">
                                            <thead>
                                                <tr>
                                                    <th class="all"> Divisi </th>
                                                    <th class="all"> Jenis Transaksi </th>
                                                    <th class="all"> No. Jurnal </th>
                                                    <th class="all"> No. Ref </th>
                                                    <th class="all"> Tanggal </th>
                                                    <th class="all"> Keterangan </th>
                                                    <th class="all"> Total </th>
                                                    <th class=""> Aksi </th>
                                                </tr>
                                            </thead>
                                            <tbody></tbody>
                                            <tfoot>
                                            <tr>
                                                <th class="form-group form-md-line-input"></th>
                                                <th class="form-group form-md-line-input"></th>
                                                <th class="form-group form-md-line-input"></th>
                                                <th class="form-group form-md-line-input"></th>
                                                <th class="form-group form-md-line-input"></th>
                                                <th class="form-group form-md-line-input"></th>
                                                <th class="form-group form-md-line-input"></th>
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


<div id="showModalUpload" class="modal fade" tabindex="-1" data-width="760">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Import Jurnal</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <a href="{{ url('akuntansi/jurnal/downloadfile?paramName=formatImport') }}" class="btn sbold green-meadow"> <i class="fa fa-download"></i> Download Format Import </a>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <label for="exampleInputFile" class="col-md-3 control-label">File Excel</label>
                    <div class="col-md-9">
                        <input type="file" id="exampleInputFile">
                        {{-- <p class="help-block"> some help text here. </p> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
     <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn btn-outline dark">Batal</button>
        <button type="button" id="uploadFile" class="btn green">Upload</button>
    </div>
</div>

@endsection
