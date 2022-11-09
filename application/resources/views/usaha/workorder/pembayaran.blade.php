@extends('layouts.master')

@section('title', 'Pembayaran Work Order')

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
            <h1>Pembayaran Work Order
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
                    {!! Form::open(['url' => url('usaha/wo/proses-pembayaran'),'method'=>'POST', 'class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}

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

                        <h3 class="form-section">Informasi WO - {{ $client->nama_client }}</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4 bold">Jenis Pekerjaan:</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static"> {{ jenisPekerjaan($data_edit->jenis_pekerjaan) }} </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4 bold">Nama Pekerjaan:</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static"> {{ $data_edit->nama_pekerjaan }} </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4 bold">Lokasi Pekerjaan:</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static"> {{ $lokasi->nama_lokasi }}  </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4 bold">Nilai Pekerjaan:</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static"> {{ toRp($data_edit->nilai_pekerjaan) }} </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4 bold">Keterangan:</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">  </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-4 bold">Dokumen Pendukung:</label>
                                    <div class="col-md-8">
                                        <p class="form-control-static">  </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <h3>Pembayaran</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group  ">
                                  <label class="control-label col-md-4">Informasi Pembayaran</label>
                                  <div class="col-md-8">
                                      <p class="form-control-static"> {{ $simulasi->keterangan }} | {{ tglIndo($simulasi->tanggal) }} | {{ toRp($simulasi->nominal) }} </p>
                                      <input type="hidden" name="simulasi" value="{{ $simulasi->id }}">
                                  </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group  {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                  <label class="control-label col-md-4">Kekurangan</label>
                                  <div class="col-md-8">
                                      <p class="form-control-static"> {{ toRp($kekurangan) }} </p>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group  {{ $errors->has('jumlah_dibayar') ? 'has-error' : ''}}">
                                    {!! Form::label('jumlah_dibayar', 'Jumlah Yang Dibayar', ['class' => 'control-label col-md-4'] ) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('jumlah_dibayar', null, ['class' => 'form-control rupiah', 'autofocus'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                        <input type="hidden" name="id" value="{{ $data_edit->wo_id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group  {{ $errors->has('ppn_keluaran') ? 'has-error' : ''}}">
                                    {!! Form::label('ppn_keluaran', 'PPN', ['class' => 'control-label col-md-4'] ) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('ppn_keluaran', null, ['class' => 'form-control rupiah', 'autofocus'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group  {{ $errors->has('pph22') ? 'has-error' : ''}}">
                                    {!! Form::label('pph22', 'PPH 22', ['class' => 'control-label col-md-4'] ) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('pph22', null, ['class' => 'form-control rupiah', 'autofocus'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group  {{ $errors->has('pph23') ? 'has-error' : ''}}">
                                    {!! Form::label('pph23', 'PPH 23', ['class' => 'control-label col-md-4'] ) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('pph23', null, ['class' => 'form-control rupiah', 'autofocus'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group  {{ $errors->has('metode_pembayaran') ? 'has-error' : ''}}">
                                    {!! Form::label('metode_pembayaran', 'Metode Pembayaran', ['class' => 'control-label col-md-4'] ) !!}
                                    <div class="col-md-8">
                                        {{ Form::select('metode_pembayaran', [1=>'Transfer', 2=>'Tunai'], null, ['class' => 'form-control','id'=>'metode_pembayaran']) }}
                                        <div class="form-control-focus"> </div>
                                        
                                    </div>
                                </div>
                            </div>
                          <div class="col-md-6">
                            <div class="form-group  {{ $errors->has('catatan') ? 'has-error' : ''}}">
                                {!! Form::label('catatan', 'Catatan', ['class' => 'control-label col-md-4'] ) !!}
                                <div class="col-md-8">
                                    {!! Form::text('catatan', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group  {{ $errors->has('dokumen') ? 'has-error' : ''}}">
                                {!! Form::label('dokumen', 'Dokumen Pendukung', ['class' => 'control-label col-md-4'] ) !!}
                                <div class="col-md-8">
                                    <input type="file" name="dokumen">
                                </div>
                            </div>
                          </div>

                        </div>

                    </div>

                    <div class="form-actions">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3"></div>
                                {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Pembayaran Work Order']) !!}
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
