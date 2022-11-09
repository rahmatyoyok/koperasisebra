@extends('layouts.master')

@section('title', 'Data Persekot PO')

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
            <h1>Data Persekot PO - {{ $data_edit->no_persekot }}
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
                          <span class="caption-subject bold uppercase">Informasi Persekot PO</span>
                      </div>
                      <div class="actions">
                      <a class="btn blue" href="{{ url('usaha/wo/'.$data_edit->wo_id) }}">
                            <i class="fa fa-info"></i>
                            Informasi Work Order
                        </a>
                        <div class="btn-group">
                          <button type="button" class="btn green dropdown-toggle" data-toggle="dropdown">
                          <i class="fa fa-print"></i> Cetak PDF</button>
                          <ul class="dropdown-menu" role="menu">
                            <li>
                              <a target="_blank" href="{{ url('usaha/pdf/persekotPO?kd='.$data_edit->persekot_id.'&status=1') }}">
                                  Permintaan
                              </a>
                            </li>
                            <li>
                              <a target="_blank" href="{{ url('usaha/pdf/persekotPO?kd='.$data_edit->persekot_id.'&status=2') }}">
                                  Verifikasi
                              </a>
                            </li>
                            <li>
                              <a target="_blank" href="{{ url('usaha/pdf/persekotPO?kd='.$data_edit->persekot_id.'&status=3') }}">
                                  Realisasi
                              </a>
                            </li>
                          </ul>
                        </div>
                      </div>
                  </div>
				            <div class="portlet-body form">

                      {!! Form::open(['url' => url('usaha/persekot/verifikasi-persekot'),'method'=>'POST', 'class' => 'form-horizontal']) !!}

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
                                        <p class="form-control-static"> Persekot </p>
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
                                    <label class="control-label col-md-3 bold">Keterangan Persekot:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ $data_edit->keterangan }} </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Status:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                          @if($data_edit->status == 1)
                                           Belum Diverifikasi
                                          @elseif($data_edit->status == 2)
                                           Disetujui
                                          @elseif($data_edit->status == 3)
                                           Realisasi
                                          @elseif($data_edit->status == 4)
                                           Sudah Realisasi
                                           @elseif($data_edit->status == 5)
                                           Lunas
                                          @elseif($data_edit->status == 99)
                                           Ditolak
                                          @else
                                          @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Dokumen OA/RAB</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static">
                                            @if($data_edit->dokumen != null)
                                            <a target="_blank" href="{{ url('/').'/application/public/dokumen_persekot/'.$data_edit->dokumen }}" class="mb-2 mr-2 btn btn-success" >Download</a>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @if($data_edit->margin != null)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Margin:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ $data_edit->margin }}% </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">PPN:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ $data_edit->ppn }}%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">PPH 22:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ $data_edit->pph22 }}%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">PPH 23:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ $data_edit->pph23 }}%</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-3 bold">Total:</label>
                                    <div class="col-md-9">
                                        <p class="form-control-static"> {{ toRp($data_edit->margin_val) }}</p>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                        <h3>History Persekot</h3>
                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Tanggal Waktu</th>
                                    <th>Informasi</th>
                                    <th>Catatan</th>
                                    <th>File Pendukung</th>
                                    <th>Operator</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach($log as $value)
                                <tr>
                                  <td>{{ tglWaktuIndo($value->created_at) }}</td>
                                  <td>{{ $value->log_text }}</td>
                                  <td>{{ $value->catatan }}</td>
                                  <td>
                                    @if($value->file != null)
                                    <a class="btn green-jungle col-md-12" target="_blank" href="{{ url('getDownload?type=persekot&name='.$data_edit->no_persekot.'&file='.$value->file) }}">
                                        Download
                                    </a>
                                    @endif
                                  </td>
                                  <td>{{ $value->operator }}</td>
                                </tr>
                              @endforeach
                            </tbody>
                        </table>

                      </div>

                      <!-- <div class="form-actions">

                          <div class="row">
                              <div class="col-md-12">
                                  <div class="col-md-3"></div>

                                  <a href="{{ url('usaha/persekot') }}" type="button" class="col-md-3 btn green">Monitoring Persekot</a>
                                  <a href="{{ url('usaha/persekot/verifikasi') }}" type="button" class="col-md-3 btn default">Verifikasi Persekot</a>
                                  <div class="col-md-3"></div>
                              </div>
                          </div>

                      </div> -->

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
