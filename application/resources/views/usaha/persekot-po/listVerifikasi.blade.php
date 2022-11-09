@extends('layouts.master')

@section('title', 'Verifikasi Persekot PO')

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
            <h1>Verifikasi Persekot PO
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
                            <span class="caption-subject bold uppercase">Data</span>
                        </div>

                    </div>
				            <div class="portlet-body form">
                        <div class="form-horizontal">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No Persekot</th>
                                                    <th>SPV</th>
                                                    <th>Tanggal Pengajuan</th>
                                                    <th>Jatuh Tempo</th>
                                                    <th>Nominal</th>
                                                    <th>Keterangan</th>
                                                    <th class="text-center" width="150">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                              @foreach($data as $value)
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
                                                  <td align="center">
                                                    <a href="{{ url('usaha/persekotpo/'.$value->persekot_id.'') }}" class="btn btn-xs blue tooltips" title="Detail Persekot">Detail</a>
                                                    <a href="{{ url('usaha/persekotpo/'.$value->persekot_id.'/edit') }}" class="btn btn-xs purple-sharp tooltips" title="Ubah Persekot">Ubah</a>
                                                    <a href="{{ url('usaha/persekotpo/verifikasi-detail/'.$value->persekot_id) }}" class="btn btn-xs yellow tooltips" title="Proses Verifikasi Persekot">Verifikasi</a>
                                                  </td>
                                                </tr>
                                              @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
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

<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
