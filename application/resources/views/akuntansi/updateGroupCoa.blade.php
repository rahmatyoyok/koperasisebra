@extends('layouts.master-ak')
@section('title', $title)

@push('styles')
    <link href="{{assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet')}}" type="text/css" />
    <style>
        .form-horizontal .form-group.form-md-line-input {
            padding-top: 10px;
            margin: 0 -10px 5px!important;
        }
    </style>
@endpush


@push('scripts')
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush


@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Detil Group Kode Rekening</h1>
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
                            <span class="caption-subject bold uppercase">Informasi Detil Group Kode Rekening</span>
                        </div>
                        
                    </div>
                    <div class="portlet-body form">                            
                            {!! Form::model($data_edit, ['method' => 'PATCH', 'url' => ['akuntansi/groupcoa', $data_edit->group_coa_id], 'class' => 'form-horizontal', 'id'=>'formcoa']) !!}                       
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">

                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('first_name') ? 'has-error' : ''}}">
                                            {!! Form::label('group_coa', 'Group COA Type', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('group_coa_type', $data_edit->group_coa_type, ['class' => 'form-control input-sm uppercase', 'data-required'=>1]) !!}
                                            <div class="form-control-focus"> </div><span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('first_name') ? 'has-error' : ''}}">
                                            {!! Form::label('header_coa', 'Kode', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('code', $data_edit->code, ['class' => 'form-control input-sm uppercase', 'data-required'=>1]) !!}
                                            <div class="form-control-focus"> </div><span class="help-block"></span>
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('first_name') ? 'has-error' : ''}}">
                                            {!! Form::label('group_detail', 'Keterangan', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('desc', $data_edit->desc, ['class' => 'form-control input-sm uppercase', 'data-required'=>1]) !!}
                                            <div class="form-control-focus"> </div><span class="help-block"></span>
                                        </div>
                                    </div>                                   
                                    
                                </div>
                            </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-3"></div>
                                    {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Merubah data kode rekening', 'style' => 'margin-right:5px;']) !!}
                                    <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze" style="margin-left:5px;"><i class="fa fa-close"></i> Batal</a>
                                    <div class="col-md-3"></div>
                                </div>
                            </div>


                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
