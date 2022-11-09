@extends('layouts.master')

@section('title', 'Realisasi Persekot')

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
            <h1>Realisasi Persekot - No : {{ $data_edit->no_persekot }}
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

                      {!! Form::open(['url' => url('usaha/persekot/proses-persekot'),'method'=>'POST', 'class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}

                      <div class="form-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Nomor:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ $data_edit->no_persekot }} </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Dari:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ $spv_id->jabatan_spv }} - {{ $spv_id->nip_spv }} - {{ $spv_id->nama_spv }}  </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Jenis Pekerjaan:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ jenisPekerjaan($data_edit->jenis_pekerjaan) }} </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Petugas PIC:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ $petugas_id->name }}  </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Tanggal Pengajuan:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ tglIndo($data_edit->tgl_pengajuan) }} </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Jatuh Tempo:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ $data_edit->jatuh_tempo }} Hari, {{ tglIndo($data_edit->tgl_jatuhtempo) }} </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Proses:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> Realisasi </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Penerima:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ $data_edit->tujuan_transfer }} 
                                        @if($data_edit->metode_penerimaan == 1)
                                        - {{ $bank_id->nama_bank }} - {{ $data_edit->no_rekening }}
                                        @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Jumlah:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ toRp($data_edit->jumlah) }} </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Keterangan:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ $data_edit->keterangan }} </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jumlah') ? 'has-error' : ''}}">
                                    {!! Form::label('jumlah', 'Jumlah Realisasi Persekot', ['class' => 'control-label col-md-3'] ) !!}
                                    <div class="col-md-9">
                                        {!! Form::text('jumlah', null, ['class' => 'form-control rupiah', 'id' => 'jumlah', 'autofocus'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">{{ $errors->has('jumlah') ? $errors->first('jumlah') : 'Masukkan Jumlah Realisasi Persekot' }}</span>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 ">Catatan:</label>
                                    <div class="col-md-9">
                                        <textarea class="form-control" name="status_catatan"></textarea>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 ">Dokumen Pendukung:</label>
                                    <div class="col-md-9">
                                        <input type="file" name="dokumen">
                                        <input type="hidden" name="id" value="{{ $data_edit->persekot_id }}">
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            
                            <!--/span-->
                        </div>

                      </div>

                      <div class="form-actions">

                          <div class="row">
                              <div class="col-md-12">
                                  <div class="col-md-3"></div>
                                  {!! Form::button('Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Realisasi Persekot']) !!}
                                  <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze">Batal</a>
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
