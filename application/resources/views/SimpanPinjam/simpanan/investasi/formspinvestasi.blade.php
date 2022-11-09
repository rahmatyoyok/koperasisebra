@extends('layouts.master-sp')

@section('title', $title)

@push('styles')
    <link href="{{ assets('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        input.form-control{
            padding:0px 5px!important;
        }
        label.required-input:after{
            content : " * ";
            color: #f00;
        }
    </style>
@endpush


@push('plugins')
    <script src="{{ assets('global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-markdown/lib/markdown.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-markdown/js/bootstrap-markdown.js') }}" type="text/javascript"></script>
    <script src="{{assets('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery.masknumber.min.js') }}" type="text/javascript"></script>
    <script src="{{assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
    </script>
    <script src="{{ assets('pages/scripts/SimpanPinjam/tambahspinvestasi.js') }}" type="text/javascript"></script>
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush

@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Tambah Investasi</h1>
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
                  <!--div class="portlet-title">
                      <div class="caption">
                          <i class="icon-bubble font-dark hide"></i>
                          <span class="caption-subject font-hide bold uppercase">Daftar Anggota</span>
                      </div>

                  </div-->
                  <div class="portlet-body">
                      <div class="row">
                        <div class="col-md-12">
                        <!-- BEGIN VALIDATION STATES-->
                          <div class="portlet light portlet-fit portlet-form ">
                              
                              <div class="portlet-body">
                                  <!-- BEGIN FORM-->
                                  {!! Form::open(['route' => 'investasi.store', 'class' => 'form-horizontal', 'method' => 'POST', 'id'=>'formSpPokok']) !!}
                                      <div class="form-body">
                                          <div class="alert alert-danger display-hide">
                                              <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                          
                                          <div class="form-group form-md-line-input">
                                                {!! Form::label('ref_code', 'No Reff', ['class' => 'control-label col-md-3'] ) !!}
                                                <div class="col-md-2">
                                                    {!! Form::text('ref_code_label', $ref, ['class' => 'form-control', 'disabled' => 'disabled'] ) !!}
                                                    {!! Form::hidden('ref_code', $ref, ['class' => 'form-control'] ) !!}
                                                </div>
                                          </div>

                                          <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tr_date') ? 'has-error' : ''}}">                                                {!! Form::label('tr_date', 'Tanggal', ['class' => 'control-label col-md-3 required-input '] ) !!}
                                              <div class="col-md-2">
                                                  <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
                                                      <span class="input-group-addon">
                                                          <i class="fa fa-calendar"></i>
                                                      </span>
                                                      {!! Form::text('tr_date', $def_date, ['class' => 'form-control', 'autofocus'] ) !!}
                                                      <div class="form-control-focus"> </div>
                                                  </div>
                                              </div>
                                          </div>
                                          
                                          
                                          <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('person_id') ? 'has-error' : ''}}">                                                {!! Form::label('person_id', 'NIAK', ['class' => 'control-label col-md-3 required-input '] ) !!}
                                                <div class="col-md-4">
                                                    {{ Form::select('person_id', array(), null, ['class' => 'form-control select2']) }}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block"></span>
                                                </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-3">Nama Anggota
                                                  <span class="required"> * </span>
                                              </label>
                                              <div class="col-md-4">
                                                  <input type="text" name="f_name" data-required="1" class="form-control" disabled /> </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-3">Unit Kerja
                                                  <span class="required"> * </span>
                                              </label>
                                              <div class="col-md-4">
                                                  <div class="input-group">
                                                      <span class="input-group-addon">
                                                          <i class="fa fa-map"></i>
                                                      </span>
                                                      <input type="text" name="f_unit_kerja" class="form-control" placeholder="-" disabled/> 
                                                  </div>    
                                              </div>
                                          </div>

                                          <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('total') ? 'has-error' : ''}}">                                                
                                              {!! Form::label('total', 'Nilai Investasi', ['class' => 'control-label col-md-3 required-input '] ) !!}
                                                <div class="col-md-3">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-money"></i>
                                                        </span>
                                                        {!! Form::text('total', '', ['class' => 'form-control rupiah'] ) !!}
                                                        <div class="form-control-focus"> </div>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                                {!! Form::label('payment_method', 'Cara Penerimaan', ['class' => 'control-label col-md-3 required-input'] ) !!}
                                                <div class="col-md-2">
                                                    {{ Form::select('payment_method', sp_payment_method_list(), 2, ['class' => 'form-control select2']) }}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block"></span>
                                                </div>
                                          </div>

                                      </div>
                                      <div class="form-actions">
                                            <div class="row">
                                                    <div class="col-md-3"></div>
                                                    {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Investasi', 'style' => 'margin-right:5px;']) !!}
                                                    <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze" style="margin-left:5px;"><i class="fa fa-close"></i> Batal</a>
                                                    <div class="col-md-3"></div>
                                              </div>
                                      </div>
                                      {{ Form::close() }}
                                  <!-- END FORM-->
                              </div>                              
                          </div>
                          <!-- END VALIDATION STATES-->
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
