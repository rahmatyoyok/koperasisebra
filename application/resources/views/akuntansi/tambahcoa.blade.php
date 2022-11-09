@extends('layouts.master-ak')

@section('title', $title)

@push('styles')

    <link href="{{ assets('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" type="text/css" />
    
    <style>
        input.uppercase{
            text-transform: uppercase!important;
        }
        input.form-control{
            padding:0px 5px!important;
        }
        label.required-input:after{
            content : " * ";
            color: #f00;
        }

        .form-group.form-md-line-input .form-control[disabled], .form-group.form-md-line-input .form-control[readonly], fieldset[disabled] .form-group.form-md-line-input .form-control {
            background: 0 0;
            cursor: not-allowed;
            border-bottom: 1px solid #c2cad8 !important;
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
    <script src="{{ assets('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery.masknumber.min.js') }}" type="text/javascript"></script>
    <script src="{{assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>
@endpush


@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
    </script>
    <script src="{{ assets('pages/scripts/simpanpinjam/anggota.create.js') }}" type="text/javascript"></script>
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush


@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
        <h1>{{$title}}</h1>
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
                        <!-- BEGIN VALIDATION STATES-->
                          <div class="portlet light portlet-fit portlet-form ">
                              
                              <div class="portlet-body">
                                <!-- BEGIN FORM-->
                                {!! Form::open(['route' => 'coa.store', 'class' => 'form-horizontal', 'method' => 'POST', 'id'=>'frmCoa']) !!}
                                    <div class="form-body">                                        
                                            @if (session('status'))
                                                <div class="alert alert-success">
                                                    {{ session('status') }}
                                                </div>
                                            @endif
                                            @if (count($errors) > 0)
                                                <div class="alert alert-danger">
                                                    <ul>
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                       
                                          
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('group_coa') ? 'has-error' : ''}}">
                                                    {!! Form::label('group_coa', 'Group COA', ['class' => 'control-label col-md-4 required-input','required'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('group_coa','', ['class' => 'form-control input-sm uppercase', 'data-required'=>1]) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('header_coa') ? 'has-error' : ''}}">
                                                        {!! Form::label('header_coa', 'Header COA', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('header_coa','', ['class' => 'form-control input-sm uppercase', 'data-required'=>1]) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('group_detail') ? 'has-error' : ''}}">
                                                        {!! Form::label('group_detail', 'Group Detail', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('group_detail','', ['class' => 'form-control input-sm uppercase', 'data-required'=>1]) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('activity_code') ? 'has-error' : ''}}">
                                                        {!! Form::label('activity_code', 'Activity Code', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('activity_code','', ['class' => 'form-control input-sm uppercase', 'data-required'=>1]) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('code') ? 'has-error' : ''}}">
                                                        {!! Form::label('code', 'Code', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('code','', ['class' => 'form-control input-sm uppercase', 'data-required'=>1]) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('keterangan') ? 'has-error' : ''}}">
                                                        {!! Form::label('keterangan', 'Keterangan', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('keterangan','', ['class' => 'form-control input-sm uppercase', 'data-required'=>1]) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                                                                    
                                        </div>
                                         

                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-12">
                                                    <div class="col-md-3"></div>
                                                    {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Data COA', 'style' => 'margin-right:5px;']) !!}
                                                        <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze"><i class="fa fa-close"></i> Batal</a>
                                                        
                                                    <div class="col-md-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                  {!! Form::close() !!}
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
