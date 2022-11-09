@extends('layouts.master')

@section('title', 'PNPO Persekot')

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
            <h1>PNPO Persekot - {{ $data_edit->no_persekot }}
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
                                                    <p class="form-control-static"> PNPO </p>
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
                                            <div class="form-group {{ $errors->has('margin') ? 'has-error' : ''}}">
                                                {!! Form::label('margin', 'Margin', ['class' => 'control-label col-md-3'] ) !!}
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        {!! Form::number('margin', $setting->margin_persekot, ['class' => 'form-control hitung', 'id' => 'margin'] ) !!}
                                                        <span class="input-group-addon" id="sizing-addon1">%</span>
                                                    </div>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('margin') ? $errors->first('margin') : 'Masukkan Margin' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('ppn') ? 'has-error' : ''}}">
                                                {!! Form::label('ppn', 'PPN', ['class' => 'control-label col-md-3'] ) !!}
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        {!! Form::number('ppn', null, ['class' => 'form-control hitung', 'id' => 'ppn'] ) !!}
                                                        <span class="input-group-addon" id="sizing-addon1">%</span>
                                                    </div>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('ppn') ? $errors->first('ppn') : 'Masukkan PPN' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="pph22" value="0" id="pph22">
                                        <input type="hidden" name="pph23" value="0" id="pph23">
                                        <!-- <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('pph22') ? 'has-error' : ''}}">
                                                {!! Form::label('pph22', 'PPH 22', ['class' => 'control-label col-md-3'] ) !!}
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        {!! Form::number('pph22', null, ['class' => 'form-control hitung', 'id' => 'pph22'] ) !!}
                                                        <span class="input-group-addon" id="sizing-addon1">%</span>
                                                    </div>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('pph22') ? $errors->first('pph22') : 'Masukkan PPH 22' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group {{ $errors->has('pph23') ? 'has-error' : ''}}">
                                                {!! Form::label('pph23', 'PPH 23', ['class' => 'control-label col-md-3'] ) !!}
                                                <div class="col-md-4">
                                                    <div class="input-group">
                                                        {!! Form::number('pph23',null, ['class' => 'form-control hitung', 'id' => 'pph23'] ) !!}
                                                        <span class="input-group-addon" id="sizing-addon1">%</span>
                                                    </div>
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('pph23') ? $errors->first('pph23') : 'Masukkan PPH 23' }}</span>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 ">Faktur Barang:</label>
                                                <div class="col-md-9">
                                                    <input type="file" name="dokumen">
                                                    <input type="hidden" name="id" value="{{ $data_edit->persekot_id }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-3 ">Nama Tagihan:</label>
                                                <div class="col-md-9">
                                                    <textarea class="form-control" name="status_catatan"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <h3 class="form-section">Total Persekot</h3>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-md-6">Margin:</label>
                                                <div class="col-md-6">
                                                    <p class="form-control-static labelTotalMargin"> Rp. 0,00 </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-md-6">PPN:</label>
                                                <div class="col-md-6">
                                                    <p class="form-control-static labelTotalPPN"> Rp. 0,00 </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-md-6">PPH:</label>
                                                <div class="col-md-6">
                                                    <p class="form-control-static labelTotalPPH"> Rp. 0,00 </p>
                                                </div>
                                            </div>
                                        </div> -->
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label col-md-6">Total:</label>
                                                <div class="col-md-6">
                                                    <p class="form-control-static labelTotal"> Rp. 0,00 </p>
                                                    <input type="hidden" name="margin_val" id="margin_val">
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="form-actions">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3"></div>
                                            {!! Form::button('Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'PNPO Persekot']) !!}
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
@endpush

@push('scripts')


<script>
function convertToRupiah(angka)
{
	var rupiah = '';
	var angkarev = angka.toString().split('').reverse().join('');
	for(var i = 0; i < angkarev.length; i++) if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';
	return 'Rp. '+rupiah.split('',rupiah.length-1).reverse().join('');
}

$(".hitung").keyup(function(){
    var jumlah = "{{ $data_edit->jumlah }}";
    jumlah = parseFloat(jumlah);

    var margin = $("#margin").val();
    var ppn = $("#ppn").val();
    var pph22 = $("#pph22").val();
    var pph23 = $("#pph23").val();

    var totalMargin = (margin/100)*jumlah;
    totalMargin = parseFloat(totalMargin)+jumlah;

    var totalPPN = (ppn/100)*totalMargin;
    var totalPPH22 = (pph22/100)*totalMargin;
    var totalPPH23 = (pph23/100)*totalMargin;
    var totalPPH = totalPPH22+totalPPH23;
    var allTotal = totalMargin+totalPPN+totalPPH;
    $(".labelTotalMargin").html(convertToRupiah(totalMargin));
    $(".labelTotalPPN").html(convertToRupiah(totalPPN));
    $(".labelTotalPPH").html(convertToRupiah(totalPPH));
    $(".labelTotal").html(convertToRupiah(allTotal));

    $("#margin_val").val(allTotal);

});
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
