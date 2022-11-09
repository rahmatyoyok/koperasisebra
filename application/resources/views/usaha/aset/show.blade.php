@extends('layouts.master')

@section('title', 'Aset')

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
            <h1>{{ $data_edit->kode_aset }}
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
                                        <span class="caption-subject bold uppercase">Informasi Aset</span>
                                    </div>
                                    <div class="actions">
                                </div>
                            </div>
      				        <div class="portlet-body form">

                            {!! Form::open(['class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                            <div class="form-body">

                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label class="control-label col-md-4 bold">No Aset</label>
                                          <div class="col-md-7">
                                              <p class="form-control-static"> :
                                                {{ $data_edit->kode_aset }}
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
                                          <label class="control-label col-md-4 bold">Tanggal Pembelian</label>
                                          <div class="col-md-7">
                                              <p class="form-control-static"> : {{ tglIndo($data_edit->tgl_pembelian) }} </p>
                                          </div>
                                      </div>
                                  </div>
                                  <!--/span-->
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">Keterangan</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ $data_edit->keterangan }} </p>
                                        </div>
                                      </div>
                                  </div>
                                  <!--/span-->
                              </div>
                              <div class="row">
                                  <!-- <div class="col-md-6">
                                      <div class="form-group">
                                          <label class="control-label col-md-4 bold">PPN</label>
                                          <div class="col-md-7">
                                              <p class="form-control-static"> :
                                                {{ toRp($data_edit->nominal_ppn) }}
                                              </p>
                                          </div>
                                      </div>
                                  </div>
                                  
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">PPH</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ toRp($data_edit->nominal_pph) }} </p>
                                        </div>
                                      </div>
                                  </div> -->
                                  <div class="col-md-6">
                                      <div class="form-group">
                                        <label class="control-label col-md-4 bold">Total</label>
                                        <div class="col-md-7">
                                            <p class="form-control-static"> : {{ toRp($data_edit->total) }} </p>
                                        </div>
                                      </div>
                                  </div>
                              </div>
                              <hr>
                              <h4>Detail Pembelian Aset</h4>
                              <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                  <thead>
                                      <tr>
                                          <th>COA</th>
                                          <th>Nama</th>
                                          <th>Masa Manfaat</th>
                                          <th>Tarif</th>
                                          <th>Harga</th>
                                          <th>Jumlah</th>
                                          <th>Sub Total</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    
                                    @foreach($dataDetail as $value)
                                    
                                      <tr>
                                        <td>
                                          {{ $value->code }}-{{ $value->desc }}
                                          <br><span style="font-size:12px"><b>Beban Penyusutan</b> : {{ $value->code_beban_penyusutan }}-{{ $value->desc_beban_penyusutan }}</span>
                                          <br><span style="font-size:12px"><b>Akumulasi Penyusutan</b> : {{ $value->code_akm_penyusutan }}-{{ $value->desc_akm_penyusutan }}</span>
                                        </td>
                                        <td >{{ $value->nama }}</td>
                                        <td >{{ $value->masa_manfaat }} Tahun</td>
                                        <td >{{ $value->tarif }} %</td>
                                        <td align="right">{{ toRp($value->harga) }}</td>
                                        <td align="center">{{ $value->jumlah }}</td>
                                        <td align="right">{{ toRp($value->total) }}</td>
                                      </tr>
                                    @endforeach
                                    <tr>
                                      <td class="bold" align="right" colspan="6">Total</td>
                                      <td align="right" class="bold">{{ toRp($data_edit->total) }}</td>
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

<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
