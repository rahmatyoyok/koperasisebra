@extends('layouts.master')

@section('title', 'Ubah Work Order')

@push('styles')
<link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />

@endpush

@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Ubah Work Order</h1>
        </div>
    </div>
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container">
      <div class="row">
          <div class="col-lg-12 col-xs-12 col-sm-12">
              <div class="portlet light bordered">
                  <div class="portlet-title">
                      <div class="caption">
                          <i class="icon-bubble font-dark hide"></i>
                          <span class="caption-subject font-hide bold uppercase">Ubah Work Order {{ $data_edit->kode_wo }}</span>
                      </div>

                  </div>
                  <div class="portlet-body">
                    {!! Form::model($data_edit, [
                        'method' => 'PATCH',
                        'url' => ['usaha/wo', $data_edit->wo_id],
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
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jenis_pekerjaan') ? 'has-error' : ''}}">
                                    {!! Form::label('jenis_pekerjaan', 'Jenis Pekerjaan', ['class' => 'control-label col-md-2'] ) !!}
                                    <div class="col-md-10">
                                        {{ Form::select('jenis_pekerjaan', ['1'=>'Material','2'=>'Jasa'], null, ['class' => 'form-control select2']) }}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('nama_pekerjaan') ? 'has-error' : ''}}">
                                {!! Form::label('nama_pekerjaan', 'Nama Pekerjaan', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {!! Form::text('nama_pekerjaan', null, ['class' => 'form-control',   'autofocus'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('lokasi_id') ? 'has-error' : ''}}">
                                  {!! Form::label('lokasi_id', 'Lokasi Pekerjaan', ['class' => 'control-label col-md-2'] ) !!}
                                  <div class="col-md-10">
                                      {{ Form::select('lokasi_id', $lokasi_id, null, ['class' => 'form-control select2']) }}
                                      <div class="form-control-focus"> </div>
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('nilai_pekerjaan') ? 'has-error' : ''}}">
                                {!! Form::label('nilai_pekerjaan', 'Nilai Pekerjaan', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {!! Form::text('nilai_pekerjaan', null, ['class' => 'form-control rupiah'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <!-- <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('client_id') ? 'has-error' : ''}}">
                                {!! Form::label('client_id', 'Client', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {{ Form::select('client_id', $client_id, null, ['class' => 'form-control select2']) }}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div> -->
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('keterangan') ? 'has-error' : ''}}">
                                {!! Form::label('keterangan', 'Nama PO PJB', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {!! Form::text('keterangan', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('no_refrensi') ? 'has-error' : ''}}">
                                    {!! Form::label('no_refrensi', 'No PO PJB', ['class' => 'control-label col-md-2'] ) !!}
                                    <div class="col-md-10">
                                        {!! Form::text('no_refrensi', null, ['class' => 'form-control', 'id' => 'Nomor'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('dokumen') ? 'has-error' : ''}}">
                                {!! Form::label('dokumen', 'Dokumen Pendukung', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    <input type="file" name="dokumen">
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                        </div>

                    </div>

                    <div class="form-actions">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3"></div>
                                {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Merubah Work Order']) !!}
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
