@extends('layouts.master')

@section('title', 'Work Order')

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
            <h1>{{ $data_edit->nama_pekerjaan }}
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
                                    <span class="caption-subject bold uppercase">Work Order {{ $data_edit->kode_wo }}</span>
                                </div>
                                <div class="actions">
                                    @if($data_edit->status_pengiriman == 0)
                                        {!! Form::button('Pengiriman', ['class' => 'btn btn-warning pengiriman', 'type' => 'submit', 'data-swa-text' => 'Apakah anda yakin nilai WO sesuai dengan nilai kontrak ?']) !!}
                                    @endif
                                    @if($data_edit->status_pengiriman == 0)
                                        @if($data_edit->jenis_wo == 1)
                                        <a class="btn green-jungle" href="{{ url('usaha/po/create?kd='.$data_edit->wo_id) }}">
                                            <i class="fa fa-plus"></i>
                                            Tambah Purchase Order (PO)
                                        </a>
                                        @else
                                        <a class="btn green-jungle" href="{{ url('usaha/persekotpo/create?kd='.$data_edit->wo_id) }}">
                                            <i class="fa fa-plus"></i>
                                            Tambah Persekot PO
                                        </a>
                                        @endif
                                    @endif

                                    @if($data_edit->status_pengiriman == 1)
                                    <a class="btn blue" href="{{ url('usaha/wo/pembayaran?kd='.$data_edit->wo_id) }}">
                                        <i class="fa fa-money"></i>
                                        Pembayaran Work Order
                                    </a>
                                    @endif
                                    <a class="btn green" target="_blank" href="{{ url('usaha/pdf/wo?kd='.$data_edit->wo_id) }}">
                                        <i class="fa fa-print"></i>
                                        Cetak PDF
                                    </a>
                                </div>
                            </div>
				            <div class="portlet-body form">

                            {!! Form::open(['class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                            <div class="form-body">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4 bold">Jenis Pekerjaan</label>
                                            <div class="col-md-7">
                                                <p class="form-control-static"> :

                                                {{ jenisPekerjaan($data_edit->jenis_pekerjaan) }}
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
                                        <label class="control-label col-md-4 bold">No PO PJB</label>
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
                                        <label class="control-label col-md-4 bold">Jenis Transaksi</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ jenisWO($data_edit->jenis_wo) }} </p>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                        <label class="control-label col-md-4 bold">Nama PO PJB</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ $data_edit->keterangan }} </p>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label col-md-4 bold">Dokumen Pendukung</label>
                                            <div class="col-md-7">
                                                <p class="form-control-static">
                                                    @if($data_edit->dokumen != null)
                                                    <a target="_blank" href="{{ url('/').'/application/public/dokumen_wo/'.$data_edit->dokumen }}" class="mb-2 mr-2 btn btn-success" >Download</a>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <!--/span-->

                                    <!--/span-->
                                </div>
                                <hr>
                                <h4>Simulasi Pembayaran</h4>
                                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Keterangan</th>
                                            <th>Tanggal</th>
                                            <th>Nominal</th>
                                            <th>Status Pembayaran</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($dataSimulasiPembayaran as $value)
                                    <tr>
                                        <td>{{ $value->keterangan }}</td>
                                        <td align="left">{{ tglIndo($value->tanggal) }}</td>
                                        <td align="right">{{ toRp($value->nominal) }}</td>
                                        <td>
                                        {{ statusPembayaran($value->status) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <hr>
                                <h4>Pembayaran</h4>
                                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Tanggal Waktu</th>
                                            <th>Catatan</th>
                                            <th>File Pendukung</th>
                                            <!-- <th>Operator</th> -->
                                            <th>Metode Pembayaran</th>
                                            <th>Jumlah Dibayar</th>
                                            <th>PPN Keluaran</th>
                                            <th>PPH 22</th>
                                            <th>PPH 23</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($dataPembayaran as $value)
                                        <tr>
                                        <td>{{ tglWaktuIndo($value->created_at) }}</td>
                                        <td>{{ $value->catatan }}</td>
                                        <td>
                                            @if($value->file != null)
                                            <a class="btn green-jungle col-md-12" target="_blank" href="{{ url('getDownload?type=pembayaranwo&name='.$data_edit->nama_pekerjaan.'&file='.$value->file) }}">
                                                Download
                                            </a>
                                            @endif
                                        </td>
                                        <!-- <td>{{ $value->operator }}</td> -->
                                        <td>{{ statusMetodePembayaran($value->metode_pembayaran) }}</td>
                                        <td align="right">{{ toRp($value->jumlah_dibayar) }}</td>
                                        <td align="right">{{ toRp($value->ppn_keluaran) }}</td>
                                        <td align="right">{{ toRp($value->pph22) }}</td>
                                        <td align="right">{{ toRp($value->pph23) }}</td>
                                        <td align="right">{{ toRp($value->nominal) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td class="bold" align="right" colspan="8">Total</td>
                                        <td align="right" class="bold">{{ toRp($totalPembayaran) }}</td>
                                    </tr>
                                    </tbody>
                                </table>
                                <?php $totalPO = 0; ?>
                                @if($data_edit->jenis_wo == 1)

                                <hr>
                                <h4>Purchase Order Supplier</h4>
                                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                    <thead>
                                        <tr>
                                        <th>No PO</th>
                                        <th>Supplier</th>
                                        <th>No Kwitansi</th>
                                            <th>Tanggal PO</th>
                                            <th>Tanggal Levering PO</th>
                                            <!-- <th>BBM Konsumsi</th> -->
                                            <th>Nominal</th>
                                            <th>Operator</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($dataPO as $value)
                                        <?php $totalPO = $totalPO+$value->total; ?>
                                        <tr>
                                        <td>{{ $value->kode_po }}</td>
                                        <td>{{ $value->supplier }}</td>
                                        <td>{{ $value->no_kwitansi }}</td>
                                        <td>{{ tglWaktuIndo($value->tanggal_po) }}</td>
                                        <td>{{ tglWaktuIndo($value->tanggal_livering_po) }}</td>
                                        <!-- <td align="right">{{ toRp($value->bbm_konsumsi) }}</td> -->
                                        <td align="right">{{ toRp($value->total) }}</td>
                                        <td>{{ $value->operator }}</td>
                                        <td align="center">
                                            <a href="{{ url('usaha/po/'.$value->po_id.'') }}" class="btn btn-xs purple-sharp tooltips" title="Detail PO">Detail</a>
                                        </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                                @else

                                <hr>
                                <h4>Persekot PO</h4>
                                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No Persekot</th>
                                            <th>SPV</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Jatuh Tempo</th>
                                            <th>Nominal</th>
                                            <th>Keterangan</th>
                                            <th>Status</th>
                                            <th class="text-center" width="150">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                        @foreach($dataPersekotPO as $value)
                                        <?php $totalPO = $totalPO+$value->jumlah; ?>
                                        <tr>
                                            <td>{{ $value->no_persekot }}</td>
                                            <td>
                                            <b>{{ $value->nama_spv }}</b><br>
                                            <!-- {{ $value->nip_spv }}<br> -->
                                            {{ $value->jabatan_spv }}
                                            </td>
                                            <td>{{ tglIndo($value->tgl_pengajuan) }}</td>
                                            <td>{{ tglIndo($value->tgl_jatuhtempo) }}</td>
                                            <td align="right">{{ toRp($value->jumlah) }}</td>
                                            <td>{{ $value->keterangan }}</td>
                                            <td>{{ statusPersekot($value->status) }}</td>
                                            <td align="center">
                                            <a href="{{ url('usaha/persekotpo/'.$value->persekot_id.'') }}" class="btn btn-xs blue tooltips" title="Detail Persekot">Detail</a>
                                            </td>
                                            
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif

                            </div>
                            <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-4 bold">Nilai Pekerjaan</label>
                                            <div class="col-md-7">
                                                <p class="form-control-static"> : {{ toRp($data_edit->nilai_pekerjaan) }} </p>
                                            </div>
                                        </div>
                                    </div>
                                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label col-md-4 bold">Total PO</label>
                                            <div class="col-md-7">
                                                <p class="form-control-static"> : {{ toRp($totalPO) }} </p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                        <label class="control-label col-md-4 bold">Keuntungan</label>
                                        <div class="col-md-7">
                                            <?php $keuntungan = $data_edit->nilai_pekerjaan-$totalPO; ?>
                                            <p class="form-control-static"> : {{ toRp($keuntungan) }} </p>
                                        </div>
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
$(".pengiriman").on("click", function(e){
    e.preventDefault();
    swal({
        title: "Apakah anda yakin?",
        text: $(this).data("swa-text"),
        type: "warning",
        showCancelButton: true
    }).then(function() {
        location.href = "{{ url('usaha/wo/pengiriman/'.$data_edit->wo_id) }}";
    }).catch(swal.noop);
});
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
