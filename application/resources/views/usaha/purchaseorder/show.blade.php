@extends('layouts.master')

@section('title', 'Purchase Order')

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
            <h1>{{ $data_wo->nama_pekerjaan }}
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
                                    <span class="caption-subject bold uppercase">Informasi Work Order</span>
                                </div>
                                <div class="actions">

                                    <a class="btn blue" href="{{ url('usaha/wo/'.$data_wo->wo_id) }}">
                                        <i class="fa fa-info"></i>
                                        Informasi Work Order
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
                                                @if($data_wo->jenis_pekerjaan == 1)
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
                                            <p class="form-control-static"> : {{ $data_wo->nama_pekerjaan }} </p>
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
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label class="control-label col-md-4 bold">Dokumen Pendukung</label>
                                          <div class="col-md-7">
                                              <p class="form-control-static">
                                                @if($data_edit->dokumen != null)
                                                <a target="_blank" href="{{ url('/').'/application/public/dokumen_po/'.$data_edit->dokumen }}" class="mb-2 mr-2 btn btn-success" >Download</a>
                                                @endif
                                              </p>
                                          </div>
                                      </div>
                                  </div>
                                  <!--/span-->

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
                                        <label class="control-label col-md-4 bold">Keterangan</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ $data_wo->keterangan }} </p>
                                        </div>
                                      </div>
                                  </div>
                                  <!--/span-->
                              </div>
                              <div class="row">

                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">Nilai Pekerjaan</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ toRp($data_wo->nilai_pekerjaan) }} </p>
                                        </div>
                                      </div>
                                  </div>

                                  <!--/span-->

                                  <!--/span-->
                              </div>



      				              </div>
                            </form>
                          </div>
      				        </div>
        				   </div>
              </div>
              <div class="row">
      				    <div class="col-lg-12 col-xs-12 col-sm-12">
      				        <div class="portlet light bordered">
                          <div class="portlet-title">
                                <div class="caption">
                                    <span class="caption-subject bold uppercase">Informasi Purchase Order</span>
                                </div>
                                <div class="actions">
                                    @if($data_edit->status_penerimaan == 0)
                                    {!! Form::button('Penerimaan', ['class' => 'btn btn-warning penerimaan', 'type' => 'button', 'data-swa-text' => 'Melakukan Penerimaan Barang']) !!}
                                    @endif
                                    <a class="btn blue" href="{{ url('usaha/po/pembayaran?kd='.$data_edit->po_id) }}">
                                        <i class="fa fa-money"></i>
                                        Pembayaran Purchase Order
                                    </a>

                                    <div class="btn-group">
                                        <button type="button" class="btn green dropdown-toggle" data-toggle="dropdown">
                                        <i class="fa fa-print"></i> Cetak PDF</button>
                                        <ul class="dropdown-menu" role="menu">
                                            <li>
                                            <a target="_blank" href="{{ url('usaha/pdf/po?kd='.$data_edit->po_id) }}">
                                                Cetak Koperasi
                                            </a>
                                            </li>
                                            <li>
                                            <a target="_blank" href="{{ url('usaha/pdf/po?type=supplier&kd='.$data_edit->po_id) }}">
                                                Cetak Supplier
                                            </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                          </div>
      				            <div class="portlet-body form">

                            {!! Form::open(['class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                            <div class="form-body">

                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label class="control-label col-md-4 bold">No PO</label>
                                          <div class="col-md-7">
                                              <p class="form-control-static"> :
                                                {{ $data_edit->kode_po }}
                                              </p>
                                          </div>
                                      </div>
                                  </div>
                                  <!--/span-->
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">No Kwitansi</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ $data_edit->no_kwitansi }} </p>
                                        </div>
                                      </div>
                                  </div>
                                  <!--/span-->
                              </div>
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label class="control-label col-md-4 bold">Tanggal PO</label>
                                          <div class="col-md-7">
                                              <p class="form-control-static"> : {{ tglIndo($data_edit->tanggal_po) }} </p>
                                          </div>
                                      </div>
                                  </div>
                                  <!--/span-->
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">Tanggal Levering PO</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ tglIndo($data_edit->tanggal_livering_po) }} </p>
                                        </div>
                                      </div>
                                  </div>
                                  <!--/span-->
                              </div>
                              <div class="row">

                                  <!--/span-->


                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label class="control-label col-md-4 bold">Dokumen Pendukung</label>
                                          <div class="col-md-7">
                                              <p class="form-control-static">
                                              </p>
                                          </div>
                                      </div>
                                  </div>
                                  <!--/span-->
                              </div>
                              <hr>
                              <h4>Detail PO</h4>
                              <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                  <thead>
                                      <tr>
                                          <th>Item</th>

                                          <th>Harga</th>
                                          <th>Jumlah</th>
                                          <th>Sub Total</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    <?php $totalDetail = 0;?>
                                    @foreach($dataDetail as $value)
                                    <?php $subTotal = $value->harga*$value->jumlah;
                                    $totalDetail = $totalDetail + $subTotal;
                                    ?>
                                      <tr>
                                        <td>
                                          @if($value->jenis_pekerjaan == 1)
                                            {{ $value->stockcode }}-
                                            <b>{{ $value->nama_stockcode }}</b>
                                          @else
                                            {{ $value->pr }}-
                                            <b>{{ $value->nama_pr }}</b>
                                          @endif

                                        </td>

                                        <td align="right">{{ toRp($value->harga) }}</td>
                                        <td align="center">{{ $value->jumlah }} {{ $value->satuan }}</td>
                                        <td align="right">{{ toRp($subTotal) }}</td>
                                      </tr>
                                    @endforeach
                                    <tr>
                                      <td class="bold" align="right" colspan="3">Total</td>
                                      <td align="right" class="bold">{{ toRp($totalDetail) }}</td>
                                    </tr>
                                  </tbody>
                              </table>
                              <div class="row">
                                <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">BBM+Konsumsi</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ toRp($data_edit->bbm_konsumsi)}} </p>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">Detail</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ toRp($data_edit->total_detail)}} </p>
                                        </div>
                                      </div>
                                  </div>
                                  
                                  
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">PPN</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ toRp($data_edit->ppn)}} </p>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">Total</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ toRp($data_edit->total)}} </p>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">PPH 22</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ toRp($data_edit->pph22)}} </p>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">PPH 23</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ toRp($data_edit->pph23)}} </p>
                                        </div>
                                      </div>
                                  </div>

                                              </div>
                              <hr>
                              <h4>Pembayaran</h4>
                              <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                  <thead>
                                      <tr>

                                          <th>Tanggal Waktu</th>
                                          <th>Catatan</th>
                                          <th>File Pendukung</th>
                                          <th>Operator</th>
                                          <th>Metode Pembayaran</th>
                                          <th>Nominal</th>
                                      </tr>
                                  </thead>
                                  <tbody>

                                    @foreach($dataPembayaran as $value)
                                      <tr>

                                        <td>{{ tglWaktuIndo($value->created_at) }}</td>
                                        <td>{{ $value->catatan }}</td>
                                        <td>
                                          @if($value->file != null)
                                          <a class="btn green-jungle col-md-12" target="_blank" href="{{ url('getDownload?type=pembayaranpo&name='.$data_wo->nama_pekerjaan.'&file='.$value->file) }}">
                                              Download
                                          </a>
                                          @endif
                                        </td>
                                        <td>{{ $value->operator }}</td>
                                        <td>{{ statusMetodePembayaran($value->metode_pembayaran) }}</td>
                                        <td align="right">{{ toRp($value->nominal) }}</td>
                                      </tr>
                                    @endforeach
                                    <tr>
                                      <td class="bold" align="right" colspan="5">Total</td>
                                      <td align="right" class="bold">{{ toRp($totalPembayaran) }}</td>
                                    </tr>
                                  </tbody>
                              </table>

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
<script>
$(".penerimaan").on("click", function(e){
    e.preventDefault();
    swal({
        title: "Apakah anda yakin?",
        text: $(this).data("swa-text"),
        type: "warning",
        showCancelButton: true
    }).then(function() {
        location.href = "{{ url('usaha/po/penerimaan/'.$data_edit->po_id) }}";
    }).catch(swal.noop);
});
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
