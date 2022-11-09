@extends('layouts.master')

@section('title', $title)

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
            <h1>Pembayaran WO
            </h1>
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
                  <div class="portlet-body">
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

                        <h3 class="form-section">Informasi WO - PT PJB Unit Pembangkitan Brantas</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Jenis Pekerjaan:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> Jasa </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Nama Pekerjaan:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> Pembuatan Aplikasi E-Koperasi </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Lokasi Pekerjaan:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> GUDANG SENGGURUH </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Nilai Pekerjaan:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> Rp. 100,000,000,00 </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Keterangan:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">  </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3">Dokumen Pendukung:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">  </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <h3>Pembayaran</h3>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                    {!! Form::label('perihal', 'Nominal', ['class' => 'control-label col-md-2'] ) !!}
                                    <div class="col-md-10">
                                        {!! Form::text('perihal', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                    {!! Form::label('perihal', 'Kekurangan', ['class' => 'control-label col-md-3'] ) !!}
                                    <div class="col-md-9">
                                        <p class="form-control">Rp 100.000,00</p>
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                  {!! Form::label('perihal', 'Catatan', ['class' => 'control-label col-md-2'] ) !!}
                                  <div class="col-md-10">
                                      {!! Form::text('perihal', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
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
<!-- END PAGE CONTENT BODY -->


@endsection
