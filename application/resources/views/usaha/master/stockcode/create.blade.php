@extends('layouts.master')

@section('title', 'Tambah Stockcode')

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
            <h1>Tambah Stockcode
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
                            <span class="caption-subject bold uppercase">Tambah Stockcode</span>
                        </div>
                    </div>
				            <div class="portlet-body form">

                        {!! Form::open(['route' => 'stockcode.store', 'class' => 'form-horizontal']) !!}

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
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('stockcode') ? 'has-error' : ''}}">
                                        {!! Form::label('stockcode', 'Stockcode', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('stockcode', null, ['class' => 'form-control', 'id' => 'stockcode', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('stockcode') ? $errors->first('stockcode') : 'Masukkan Stockcode' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('nama_stockcode') ? 'has-error' : ''}}">
                                        {!! Form::label('nama_stockcode', 'Nama Stockcode', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {!! Form::text('nama_stockcode', null, ['class' => 'form-control', 'id' => 'nama_stockcode', 'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('nama_stockcode') ? $errors->first('nama_stockcode') : 'Masukkan Nama Stockcode' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('status') ? 'has-error' : ''}}">
                                        {!! Form::label('status', 'Status', ['class' => 'control-label col-md-2'] ) !!}
                                        <div class="col-md-10">
                                            {{ Form::select('status', getStockcode(), null, ['class' => 'form-control ']) }}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('status') ? $errors->first('status') : 'Masukkan Status' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="form-actions">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3"></div>
                                    {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Stockcode']) !!}
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
