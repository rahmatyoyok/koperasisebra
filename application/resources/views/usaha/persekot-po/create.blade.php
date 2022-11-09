@extends('layouts.master')

@section('title', 'Permintaan Persekot PO')

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
            <h1>Permintaan Persekot PO
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

                          {!! Form::open(['route' => 'persekotpo.store', 'class' => 'form-horizontal']) !!}

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
                                    <h3 class="form-section">Informasi Work Order</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4 bold">Jenis Pekerjaan</label>
                                                <div class="col-md-7">
                                                    <p class="form-control-static"> :
                                                    @if($data_edit->jenis_pekerjaan == 1)
                                                        Material
                                                    @else
                                                        Jasa
                                                    @endif

                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="control-label col-md-4 bold">Nama Pekerjaan</label>
                                            <div class="col-md-7">
                                                <p class="form-control-static"> : {{ $data_edit->nama_pekerjaan }} </p>
                                            </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4 bold">Lokasi Pekerjaan</label>
                                                <div class="col-md-7">
                                                    <p class="form-control-static"> : {{ $lokasi->nama_lokasi }} </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="control-label col-md-4 bold">Nilai Pekerjaan</label>
                                            <div class="col-md-7">
                                                <p class="form-control-static"> : {{ toRp($data_edit->nilai_pekerjaan) }} </p>
                                            </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4 bold">Client</label>
                                                <div class="col-md-7">
                                                    <p class="form-control-static"> : {{ $client->nama_client }} </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="control-label col-md-4 bold">No Refrensi</label>
                                            <div class="col-md-7">
                                                <p class="form-control-static"> : {{ $data_edit->no_refrensi }} </p>
                                            </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="control-label col-md-4 bold">Tanggal Levering</label>
                                            <div class="col-md-7">
                                                <p class="form-control-static"> : {{ tglIndo($data_edit->tgl_levering_start) }} - {{ tglIndo($data_edit->tgl_levering_end) }} </p>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                            <label class="control-label col-md-4 bold">Jenis Work Order</label>
                                            <div class="col-md-7">
                                                <p class="form-control-static"> : {{ jenisWO($data_edit->jenis_wo) }} </p>
                                            </div>
                                            </div>
                                        </div>

                                    </div>
                                    <h3 class="form-section">Tambah Persekot PO</h3>

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
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jatuh_tempo') ? 'has-error' : ''}}">
                                                {!! Form::label('jatuh_tempo', 'Jatuh Tempo', ['class' => 'control-label col-md-4'] ) !!}
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        {!! Form::number('jatuh_tempo', $setting->due_date_persekot, ['class' => 'form-control', 'id' => 'jatuh_tempo'] ) !!}
                                                        <span class="input-group-addon" id="sizing-addon1">Hari</span>
                                                    </div>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('jatuh_tempo') ? $errors->first('jatuh_tempo') : 'Masukkan Jatuh Tempo' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jenis') ? 'has-error' : ''}}">
                                                {!! Form::label('jenis', 'Metode Penerimaan', ['class' => 'control-label col-md-4'] ) !!}
                                                <div class="col-md-8">
                                                    {{ Form::select('jenis', [1=>'Transfer', 2=>'Tunai'], null, ['class' => 'form-control','id'=>'jenis']) }}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('jenis') ? $errors->first('jenis') : 'Masukkan Metode Penerimaan' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('keterangan') ? 'has-error' : ''}}">
                                                {!! Form::label('keterangan', 'Keterangan Persekot', ['class' => 'control-label col-md-4'] ) !!}
                                                <div class="col-md-8">
                                                    {!! Form::textarea('keterangan', null, ['class' => 'form-control', 'id' => 'deskripsi', 'autofocus'] ) !!}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('keterangan') ? $errors->first('keterangan') : 'Masukkan Keterangan Persekot' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="jenis_pekerjaan" value="{{ $data_edit->jenis_pekerjaan }}">
                                        <input type="hidden" name="wo_id" value="{{ $data_edit->wo_id }}">
                                        <!-- <div class="col-md-6">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jenis_pekerjaan') ? 'has-error' : ''}}">
                                                {!! Form::label('jenis_pekerjaan', 'Jenis Pekerjaan', ['class' => 'control-label col-md-4'] ) !!}
                                                <div class="col-md-8">
                                                    {{ Form::select('jenis_pekerjaan', ['1'=>'Material','2'=>'Jasa','3'=>'Material Jasa'], null, ['class' => 'form-control ']) }}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('jenis_pekerjaan') ? $errors->first('jenis_pekerjaan') : 'Masukkan Jenis Pekerjaan' }}</span>
                                                </div>
                                            </div>
                                        </div> -->
                                    </div>

                                </div>

                                <div class="form-actions">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3"></div>
                                            {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Persekot PO']) !!}
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
<script>
$( document ).ready(function() {
    jenis =  $("#jenis").val();

    if(jenis == 1){
        $(".metodeTransfer").css("display", "block");
    }else{

        $(".metodeTransfer").css("display", "none");
    }
});

$('#jenis').on('change', function() {
    jenis =  $( this ).val();

    if(jenis == 1){
        $(".metodeTransfer").css("display", "block");
    }else{

        $(".metodeTransfer").css("display", "none");
    }
});
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
