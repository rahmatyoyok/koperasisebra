@extends('layouts.master-ak')

@section('title', $title)

@push('styles')
    <link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ assets('global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

    
    <link href="{{ assets('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->

@endpush

@push('plugins')
    <script src="{{ assets('global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    
    <script src="{{ assets('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{assets('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery.masknumber.min.js') }}" type="text/javascript"></script>
    <script src="{{assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ assets('pages/scripts/akuntansi/MonitorJurnal.index.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1> {{ $title }} </h1>
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
                                    </div>
                              </div>
                              <div class="portlet-body">
                                    <form class="form-horizontal">
                                        <div class="form-body">
                                            
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-input form-md-floating-label">
                                                            {!! Form::label('division', 'Divisi :', ['class' => 'control-label col-md-4'] ) !!}
                                                        <div class="col-md-8">
                                                        {!! Form::select('division', $listDivisi,'', ['class' => 'form-control input-sm uppercase']) !!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-md-input form-md-floating-label">
                                                            {!! Form::label('journal_type', 'Jenis Jurnal :', ['class' => 'control-label col-md-4'] ) !!}
                                                        <div class="col-md-8">
                                                        {!! Form::select('journal_type', $listJurnalType, "", ['class' => 'form-control input-sm uppercase']) !!}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group form-md-input form-md-floating-label ">
                                                            {!! Form::label('transaction_type_id', 'Jenis Transaksi :', ['class' => 'control-label col-md-4'] ) !!}
                                                        <div class="col-md-8">
                                                        {!! Form::select('transaction_type_id', $tr_type_id, "", ['class' => 'form-control input-sm uppercase']) !!}
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-md-input form-md-floating-label ">
                                                            {!! Form::label('transaction_type_id', 'Tanggal :', ['class' => 'control-label col-md-4'] ) !!}
                                                        <div class="col-md-8">
                                                            <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </span>
                                                                {!! Form::text('tr_date', '', ['class' => 'form-control datepicker'] ) !!}
                                                                <div class="form-control-focus"> </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                    <div class="col-md-4">
                                                    <div class="col-md-4">
                                                        <button id="searchJurnal" class="btn red" style="float:right;margin-bottom: 10px;">Cari</button>
                                                    </div>
                                                    </div>
                                            </div>
                                        </div>

                                    </form>

                                
                                
                                    <hr>

                                    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table_jurnal">
                                            <thead>
                                                <tr>
                                                    <th class="all"> Jenis Jurnal </th>
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
                                                <th class="form-group form-md-line-input"></th>
                                                <th class="text-center" width="150"></th>
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
