@extends('layouts.master')

@section('title', 'Ubah Persekot')

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
            <h1>Ubah Persekot {{ $data_edit->no_persekot }}
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
        				            <div class="portlet-body form">

                              {!! Form::model($data_edit, [
                                  'method' => 'PATCH',
                                  'url' => ['usaha/persekot', $data_edit->persekot_id],
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
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('petugas_id') ? 'has-error' : ''}}">
                                                    {!! Form::label('petugas_id', 'Petugas PIC', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        {{ Form::select('petugas_id', $user_id, null, ['class' => 'form-control']) }}
                                                        <div class="form-control-focus"> </div>
                                                        <span class="help-block">{{ $errors->has('petugas_id') ? $errors->first('petugas_id') : 'Masukkan Petugas PIC' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jenis_pekerjaan') ? 'has-error' : ''}}">
                                                    {!! Form::label('jenis_pekerjaan', 'Jenis Pekerjaan', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        {{ Form::select('jenis_pekerjaan', ['1'=>'Material','2'=>'Jasa','3'=>'Material Jasa'], null, ['class' => 'form-control ']) }}
                                                        <div class="form-control-focus"> </div>
                                                        <span class="help-block">{{ $errors->has('jenis_pekerjaan') ? $errors->first('jenis_pekerjaan') : 'Masukkan Jenis Pekerjaan' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('spv_id') ? 'has-error' : ''}}">
                                                    {!! Form::label('spv_id', 'SPV', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        {{ Form::select('spv_id', $spv_id, null, ['class' => 'form-control select2']) }}
                                                        <div class="form-control-focus"> </div>
                                                        <span class="help-block">{{ $errors->has('spv_id') ? $errors->first('spv_id') : 'Masukkan SPV' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jumlah') ? 'has-error' : ''}}">
                                                    {!! Form::label('jumlah', 'Jumlah', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-4">
                                                        {!! Form::text('jumlah', null, ['class' => 'form-control rupiah', 'id' => 'jumlah', 'autofocus'] ) !!}
                                                        <div class="form-control-focus"> </div>
                                                        <span class="help-block">{{ $errors->has('jumlah') ? $errors->first('jumlah') : 'Masukkan Jumlah' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        @if($data_edit->no_rekening != null)
                                        <div class="row metodeTransfer">

                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('bank_id') ? 'has-error' : ''}}">
                                                    {!! Form::label('bank_id', 'Bank', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        {{ Form::select('bank_id', $bank_id, null, ['class' => 'form-control select2']) }}
                                                        <div class="form-control-focus"> </div>
                                                        <span class="help-block">{{ $errors->has('bank_id') ? $errors->first('bank_id') : 'Masukkan Bank' }}</span>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('no_rekening') ? 'has-error' : ''}}">
                                                    {!! Form::label('no_rekening', 'No Rekening', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('no_rekening', null, ['class' => 'form-control', 'id' => 'no_rekening', 'autofocus'] ) !!}
                                                        <div class="form-control-focus"> </div>
                                                        <span class="help-block">{{ $errors->has('no_rekening') ? $errors->first('no_rekening') : 'Masukkan No Rekening' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tujuan_transfer') ? 'has-error' : ''}}">
                                                    {!! Form::label('tujuan_transfer', 'Nama Penerima', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('tujuan_transfer', null, ['class' => 'form-control', 'id' => 'tujuan_transfer', 'autofocus'] ) !!}
                                                        <div class="form-control-focus"> </div>
                                                        <span class="help-block">{{ $errors->has('tujuan_transfer') ? $errors->first('tujuan_transfer') : 'Masukkan Nama Penerima' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('keterangan') ? 'has-error' : ''}}">
                                                    {!! Form::label('keterangan', 'Keterangan Persekot', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::textarea('keterangan', null, ['class' => 'form-control', 'id' => 'deskripsi', 'autofocus'] ) !!}
                                                        <div class="form-control-focus"> </div>
                                                        <span class="help-block">{{ $errors->has('keterangan') ? $errors->first('keterangan') : 'Masukkan Keterangan Persekot' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>

                                    <div class="form-actions">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-3"></div>
                                                {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Ubah Persekot']) !!}
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
<script src="{{assets('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
<script src="{{assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>
@endpush

@push('scripts')

<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
