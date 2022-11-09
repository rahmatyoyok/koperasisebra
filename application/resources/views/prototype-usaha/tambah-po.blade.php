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
            <h1>Tambah PO
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
                        <h3 class="form-section">Informasi WO</h3>
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
                        <h3 class="form-section">Tambah PO</h3>
                        <div class="row">

                            <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                  {!! Form::label('perihal', 'Supplier', ['class' => 'control-label col-md-2'] ) !!}
                                  <div class="col-md-10">
                                      <select class="form-control">
                                        <option>CV. BERKAT ANUGRAH</option>
                                        <option>SUMBER REJEKI</option>
                                        <option>Artha radja</option>
                                      </select>
                                      <div class="form-control-focus"> </div>
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                            </div>

                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                {!! Form::label('perihal', 'No Kuitansi', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {!! Form::text('perihal', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                {!! Form::label('perihal', 'Tanggal PO', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {!! Form::text('perihal', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                {!! Form::label('perihal', 'Tanggal Levering PO', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {!! Form::text('perihal', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                {!! Form::label('perihal', 'Harga dari Supplier', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {!! Form::text('perihal', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                {!! Form::label('perihal', 'PPN+PPH', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    <p class="form-control">Rp. 0,00</p>
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                {!! Form::label('perihal', 'BBM + Konsumsi', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {!! Form::text('perihal', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                {!! Form::label('perihal', 'Perolehan Laba', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    <p class="form-control">Rp. 0,00</p>
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                {!! Form::label('perihal', 'Keterangan', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {!! Form::text('perihal', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                {!! Form::label('perihal', 'Dokumen Pendukung', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    <input type="file">
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
