@extends('layouts.master')

@section('title', 'Permintaan Persekot')

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
            <h1>Permintaan Persekot
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

                                {!! Form::open(['class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}

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
                                        <div class="col-md-4">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                                {!! Form::label('perihal', 'Nomor', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    {!! Form::text('perihal', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">*Otomatis</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tag') ? 'has-error' : ''}}">
                                                {!! Form::label('tag', 'Dari', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                  <select class="form-control">
                                                    <option>SPV Senior Sipil & LK3 - Sigit Yuwono</option>
                                                    <option>SPV Senior Umum & CSR - Erwan Prasetya</option>
                                                    <option>SPV Rendal Har - Dwi Wahyu Pujiarto</option>
                                                  </select>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('tag') ? $errors->first('tag') : 'Masukkan Tag' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tag') ? 'has-error' : ''}}">
                                                {!! Form::label('tag', 'Proses', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    <select class="form-control">
                                                      <option>Persekot</option>
                                                      <option>PNPO</option>
                                                      <option>Tukar Kwitansi</option>
                                                      <option>PBLS</option>
                                                    </select>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('tag') ? $errors->first('tag') : 'Masukkan Tag' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('rak') ? 'has-error' : ''}}">
                                                {!! Form::label('rak', 'Tujuan Transfer', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    {!! Form::text('rak', null, ['class' => 'form-control', 'id' => 'rak', 'autofocus'] ) !!}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('rak') ? $errors->first('rak') : 'Masukkan Tujuan Transfer' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('box') ? 'has-error' : ''}}">
                                                {!! Form::label('box', 'Bank', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    <select class="form-control">
                                                      <option>BRI</option>
                                                      <option>BCA</option>
                                                    </select>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('box') ? $errors->first('box') : 'Masukkan Bank' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jumlah') ? 'has-error' : ''}}">
                                                {!! Form::label('jumlah', 'No Rekening', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    {!! Form::text('jumlah', null, ['class' => 'form-control', 'id' => 'jumlah', 'autofocus'] ) !!}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('jumlah') ? $errors->first('jumlah') : 'Masukkan No Rekening' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                      <div class="col-md-4">
                                          <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tahun') ? 'has-error' : ''}}">
                                              {!! Form::label('tahun', 'Jatuh Tempo', ['class' => 'control-label col-md-2'] ) !!}
                                              <div class="col-md-10">
                                                  {!! Form::text('tahun', null, ['class' => 'form-control', 'id' => 'tahun', 'autofocus'] ) !!}
                                                  <div class="form-control-focus"> </div>
                                                  <span class="help-block">{{ $errors->has('tahun') ? $errors->first('tahun') : 'Masukkan Jatuh Tempo' }}</span>
                                              </div>
                                          </div>
                                      </div>

                                        <div class="col-md-4">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('kode') ? 'has-error' : ''}}">
                                                {!! Form::label('kode', 'Jumlah', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    {!! Form::text('kode', null, ['class' => 'form-control', 'id' => 'kode', 'autofocus'] ) !!}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('kode') ? $errors->first('kode') : 'Masukkan Jumlah' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tahun') ? 'has-error' : ''}}">
                                                {!! Form::label('tahun', 'Uraian', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    {!! Form::textarea('deskripsi', null, ['class' => 'form-control', 'id' => 'deskripsi', 'autofocus'] ) !!}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('tahun') ? $errors->first('tahun') : 'Masukkan Uraian' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-actions">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3"></div>
                                            {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Arsip']) !!}
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
<script>
function PreviewImage() {
                pdffile=document.getElementById("uploadPDF").files[0];
                pdffile_url=URL.createObjectURL(pdffile);
                $('#viewer').attr('src',pdffile_url);
            }
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
