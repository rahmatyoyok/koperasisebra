@extends('layouts.master')

@section('title', 'Ubah Purchase Order')

@push('styles')
<link href="{{ assets('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet')}}" type="text/css" />

<link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
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
                                  <span class="caption-subject bold uppercase">Ubah Purchase Order - {{ $data_edit->kode_po }}</span>
                              </div>
                          </div>
      				            <div class="portlet-body form">

                            {!! Form::model($data_edit, [
                                'method' => 'PATCH',
                                'url' => ['usaha/po', $data_edit->wo_id],
                                'class' => 'form-horizontal','enctype' => 'multipart/form-data'
                            ]) !!}

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
                              <div class="row">
                                 
                                  <div class="col-md-6">
                                      <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('supplier_id') ? 'has-error' : ''}}">
                                          {!! Form::label('supplier_id', 'Supplier', ['class' => 'control-label col-md-4'] ) !!}
                                          <div class="col-md-8">
                                              {{ Form::select('supplier_id', $supplier, null, ['class' => 'form-control select2']) }}

                                              <div class="form-control-focus"> </div>
                                              <span class="help-block"></span>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('no_kwitansi') ? 'has-error' : ''}}">
                                        {!! Form::label('no_kwitansi', 'No Kwitansi', ['class' => 'control-label col-md-4'] ) !!}
                                        <div class="col-md-8">
                                            {!! Form::text('no_kwitansi', null, ['class' => 'form-control',   'autofocus'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                  </div>
                                  <!--/span-->
                              </div>
                              <div class="row">

                                  <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tanggal_po') ? 'has-error' : ''}}">
                                        {!! Form::label('tanggal_po', 'Tanggal PO', ['class' => 'control-label col-md-4'] ) !!}
                                        <div class="col-md-8">
                                            <div class="input-group input-medium">
                                                <input type="text" class="form-control datepicker"  name="tanggal_po" autocomplete="off" value="{{ $tanggal_po}}">
                                                <div class="form-control-focus"> </div>
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('tanggal_po') ? $errors->first('tanggal_po') : 'Pilih Tanggal PO' }}</span>
                                        </div>
                                    </div>
                                  </div>
                                  <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tanggal_livering_po') ? 'has-error' : ''}}">
                                        {!! Form::label('tanggal_livering_po', 'Tanggal Levering PO', ['class' => 'control-label col-md-4'] ) !!}
                                        <div class="col-md-8">
                                            <div class="input-group input-medium">
                                                <input type="text" class="form-control datepicker"  name="tanggal_livering_po" autocomplete="off" value="{{ $tanggal_livering_po}}">
                                                <div class="form-control-focus"> </div>
                                                <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </div>
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block">{{ $errors->has('tanggal_livering_po') ? $errors->first('tanggal_livering_po') : 'Pilih Tanggal Levering PO' }}</span>
                                        </div>
                                    </div>
                                  </div>

                              </div>
                              <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('bbm_konsumsi') ? 'has-error' : ''}}">
                                        {!! Form::label('bbm_konsumsi', 'BBM + Konsumsi', ['class' => 'control-label col-md-4'] ) !!}
                                        <div class="col-md-4">
                                            {!! Form::text('bbm_konsumsi', null, ['class' => 'form-control rupiah'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('ppn') ? 'has-error' : ''}}">
                                        {!! Form::label('ppn', 'PPN Purchase Order', ['class' => 'control-label col-md-4'] ) !!}
                                        <div class="col-md-4">
                                          <div class="input-group">
                                              {!! Form::number('ppn', $setting->ppn, ['class' => 'form-control', 'id' => 'ppn'] ) !!}
                                              <span class="input-group-addon" id="sizing-addon1">%</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div cl
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('pph22') ? 'has-error' : ''}}">
                                        {!! Form::label('pph22', 'PPH22 Purchase Order', ['class' => 'control-label col-md-4'] ) !!}
                                        <div class="col-md-4">
                                          <div class="input-group">
                                              {!! Form::number('pph22', $setting->pph22, ['class' => 'form-control', 'id' => 'ppn'] ) !!}
                                              <span class="input-group-addon" id="sizing-addon1">%</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('pph23') ? 'has-error' : ''}}">
                                        {!! Form::label('pph23', 'PPH23 Purchase Order', ['class' => 'control-label col-md-4'] ) !!}
                                        <div class="col-md-4">
                                          <div class="input-group">
                                              {!! Form::number('pph23', $setting->pph23, ['class' => 'form-control', 'id' => 'ppn'] ) !!}
                                              <span class="input-group-addon" id="sizing-addon1">%</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('keterangan') ? 'has-error' : ''}}">
                                      {!! Form::label('keterangan', 'Keterangan', ['class' => 'control-label col-md-4'] ) !!}
                                      <div class="col-md-8">
                                          {!! Form::text('keterangan', null, ['class' => 'form-control'] ) !!}
                                          <div class="form-control-focus"> </div>
                                          <span class="help-block"></span>
                                      </div>
                                  </div>
                                </div>
                                <div class="col-md-6">
                                  <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('dokumen') ? 'has-error' : ''}}">
                                      {!! Form::label('dokumen', 'Dokumen Pendukung', ['class' => 'control-label col-md-4'] ) !!}
                                      <div class="col-md-8">
                                          <input type="file" name="dokumen" >
                                          <div class="form-control-focus"> </div>
                                          <span class="help-block"></span>
                                      </div>
                                  </div>
                                </div>
                              </div>
                           
                              <hr>
                              <h4><a class="btn green-jungle" id="addData">
                                  <i class="fa fa-plus"></i>

                              </a> Detail PO</h4>
                              <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="listTable" cellspacing="0">
                                  <thead>
                                      <tr>
                                          <th>{{$labelJenisPekerjaan}}</th>
                                          @if($data_wo->jenis_pekerjaan == 1)
                                            <th>Jumlah</th>
                                          @endif
                                          <th>Harga Supplier</th>
                                          <th>Aksi</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                    <?php $no = 1; ?>
                                    @foreach($dataDetail as $value)
                                      <tr id='row{{$no}}' >
                                        <td>
                                          @if($data_wo->jenis_pekerjaan)
                                            {{ $value->stockcode }}-
                                            <b>{{ $value->nama_stockcode }}</b>
                                          @else
                                            {{ $value->pr }}-
                                            <b>{{ $value->nama_pr }}</b>
                                          @endif
                                        </td>
                                        @if($data_wo->jenis_pekerjaan == 1)
                                            <td align="right">
                                              {!! Form::number('jumlah[]', $value->jumlah, ['class' => 'form-control'] ) !!}
                                              
                                            </td>
                                          @endif
                                        <td align="right">
                                          {!! Form::text('harga_supplier[]', $value->harga, ['class' => 'form-control rupiah'] ) !!}
                                          <input type="hidden" name="listData[]" value="{{ $value->item_id }}">
                                        </td>
                                        <td align="center">
                                          <a href="javascript:;" class="btn btn-block btn-xs red delete" data-number="{{ $no }}"><i class="fa fa-trash"></i> Hapus</a>
                                        </td>
                                      </tr>
                                      <?php $no++; ?>
                                    @endforeach

                                  </tbody>
                              </table>

      				              </div>

                            <div class="form-actions">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-3"></div>
                                        {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Merubah Purchase Order']) !!}
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
<script src="{{assets('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
<script src="{{assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>
@endpush

@push('scripts')
<script>

$(".datepicker").datepicker( {
    // "setDate", new Date(),
    "setDate": new Date(),
    format: "dd-mm-yyyy",
});


var numberTBL = 99;
$("#addData").on('click', function(event){
    var varAppend= "<tr id='row"+numberTBL+"'>";
    varAppend += "<td>";
    varAppend += "<select name='listData[]' class='form-control listData'></select>";
    varAppend += "</td>";
    @if($data_wo->jenis_pekerjaan == 1)
    varAppend += "<td>";
    varAppend += "<input type='number' class='form-control' name='jumlah[]'>";
    varAppend += "</td>";
    @endif
    varAppend += "<td>";
    varAppend += "<input type='text' class='form-control rupiah' name='harga_supplier[]'>";
    varAppend += "</td>";
    varAppend += '<td><a href="javascript:;" class="btn btn-block btn-xs red delete" data-number="'+numberTBL+'"><i class="fa fa-trash"></i> Hapus</a>';
    varAppend += "</td>";
    varAppend += "</tr>";
    $("#listTable").append(varAppend);
    numberTBL++;

    // $(".select2").select2();
    $(".rupiah").inputmask("decimal", {
			radixPoint: ',',
			groupSeparator: '.',
			min: $(this).data('min'),
			placeholder: '0,00',
			digits: 0,
			digitsOptional: !1,
			autoGroup: true,
			prefix: 'Rp. '
		});
    $(".listData").select2({
      width : '100%',
      minimumInputLength: 0,
      placeholder:"Pilih Data",
      ajax: {
          url: "{{ url("api/select2/listMasterPO")}}",
          dataType: 'json',
          cache: false,
          data: function (params) {
              return { q: $.trim(params.term), jenis_pekerjaan : "{{ $data_edit->jenis_pekerjaan }}" };
          },
          results: function (data, page) {
              return { results: data.results };
          }
      }
    });
    $(".delete").on('click', function(event){
      var id = $(this).attr('data-number');
      $("#row"+id).remove();
    });
});

$(".delete").on('click', function(event){

  var id = $(this).attr('data-number');
  $("#row"+id).remove();
});
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
