@extends('layouts.master')

@section('title', 'Tambah Spv')

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
            <h1>Tambah Spv
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
                            <span class="caption-subject bold uppercase">Tambah Spv</span>
                        </div>
                    </div>
				            <div class="portlet-body form">

                        {!! Form::open(['route' => 'spv.store', 'class' => 'form-horizontal']) !!}

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
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('nip_spv') ? 'has-error' : ''}}">
                                        {!! Form::label('nip_spv', 'NIP Spv', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('nip_spv', null, ['class' => 'form-control', 'id' => 'nip_spv', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('nip_spv') ? $errors->first('nip_spv') : 'Masukkan NIP Spv' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('nama_spv') ? 'has-error' : ''}}">
                                        {!! Form::label('nama_spv', 'Nama Spv', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('nama_spv', null, ['class' => 'form-control', 'id' => 'nama_spv', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('nama_spv') ? $errors->first('nama_spv') : 'Masukkan Nama Spv' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jabatan_spv') ? 'has-error' : ''}}">
                                        {!! Form::label('jabatan_spv', 'Jabatan Spv', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('jabatan_spv', null, ['class' => 'form-control', 'id' => 'jabatan_spv', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('jabatan_spv') ? $errors->first('jabatan_spv') : 'Masukkan Jabatan Spv' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('bagian_spv') ? 'has-error' : ''}}">
                                        {!! Form::label('bagian_spv', 'Bagian Spv', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('bagian_spv', null, ['class' => 'form-control', 'id' => 'bagian_spv', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('bagian_spv') ? $errors->first('bagian_spv') : 'Masukkan Bagian Spv' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>

                        <div class="form-actions">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3"></div>
                                    {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Spv']) !!}
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
