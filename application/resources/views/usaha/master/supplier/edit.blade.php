@extends('layouts.master')

@section('title', 'Ubah Supplier')

@push('styles')
<link href="{{ assets('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet')}}" type="text/css" />

@endpush

@section('content')

<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Ubah Supplier
            </h1>
        </div>
    </div>
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container">
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="mt-content-body">
				<div class="row">
				    <div class="col-lg-12 col-xs-12 col-sm-12">
				        <div class="portlet light bordered">
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase">Ubah Supplier</span>
                        </div>
                    </div>
				            <div class="portlet-body form">

                        {!! Form::model($data_edit, [
                            'method' => 'PATCH',
                            'url' => ['usaha/master/supplier', $data_edit->supplier_id],
                            'class' => 'form-horizontal'
                        ]) !!}


                        <div class="form-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <p>Terdapat beberapa kesalahan. Silahkan diperbaiki.</p><br>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('nama_supplier') ? 'has-error' : ''}}">
                                        {!! Form::label('nama_supplier', 'Nama Supplier', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('nama_supplier', null, ['class' => 'form-control', 'id' => 'nama_supplier', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('nama_supplier') ? $errors->first('nama_supplier') : 'Masukkan Nama Supplier' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('nama_penghubung_sup') ? 'has-error' : ''}}">
                                        {!! Form::label('nama_penghubung_sup', 'Nama Penghubung', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('nama_penghubung_sup', null, ['class' => 'form-control', 'id' => 'nama_penghubung_sup', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('nama_penghubung_sup') ? $errors->first('nama_penghubung_sup') : 'Masukkan Nama Penghubung' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('no_tlp_sup') ? 'has-error' : ''}}">
                                        {!! Form::label('no_tlp_sup', 'Telepon', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('no_tlp_sup', null, ['class' => 'form-control', 'id' => 'no_tlp_sup', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('no_tlp_sup') ? $errors->first('no_tlp_sup') : 'Masukkan Telepon' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('no_fax') ? 'has-error' : ''}}">
                                        {!! Form::label('no_fax', 'No Fax', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('no_fax', null, ['class' => 'form-control', 'id' => 'no_fax', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('no_fax') ? $errors->first('no_fax') : 'Masukkan No Fax' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('alamat_sup') ? 'has-error' : ''}}">
                                        {!! Form::label('alamat_sup', 'Alamat', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('alamat_sup', null, ['class' => 'form-control', 'id' => 'alamat_sup', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('alamat_sup') ? $errors->first('alamat_sup') : 'Masukkan Alamat' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('no_npwp_sup') ? 'has-error' : ''}}">
                                        {!! Form::label('no_npwp_sup', 'NPWP', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('no_npwp_sup', null, ['class' => 'form-control', 'id' => 'no_npwp_sup', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('no_npwp_sup') ? $errors->first('no_npwp_sup') : 'Masukkan NPWP' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3"></div>
                                    {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Merubah Supplier']) !!}
                                    <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze"><i class="fa fa-close"></i> Batal</a>
                                    <div class="col-md-3"></div>
                                </div>
                            </div>
                        </div>

                        {!! Form::close() !!}

				            </div>
				        </div>
				    </div>

				</div>

            </div>
        </div>
        <!-- END PAGE CONTENT INNER -->
    </div>
</div>
<!-- END PAGE CONTENT BODY -->


@endsection


@push('plugins')
<script src="{{assets('global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
@endpush

@push('scripts')

<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
