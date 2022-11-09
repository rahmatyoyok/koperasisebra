@extends('layouts.master')

@section('title', 'Tambah Purchase Order')

@push('styles')
<link href="{{ assets('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet')}}" type="text/css" />
<link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endpush

@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Tambah Purchase Order
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
                    {!! Form::open(['route' => 'po.store','class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}

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
                        <h3 class="form-section">Informasi WO</h3>
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
                                  <label class="control-label col-md-4 bold">Keterangan</label>
                                  <div class="col-md-7">
                                      <p class="form-control-static"> : {{ $data_edit->keterangan }} </p>
                                  </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        <div class="row">
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
                        <h3 class="form-section">Tambah Purchase Order</h3>
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('supplier_id') ? 'has-error' : ''}}">
                                  {!! Form::label('supplier_id', 'Supplier', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-8">
                                      {{ Form::select('supplier_id', $supplier, null, ['class' => 'form-control select2','id'=>'supplier','placeholder'=>'PILIH SUPPLIER . . .']) }}

                                      <div class="form-control-focus"> </div>
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('no_kwitansi') ? 'has-error' : ''}}">
                                {!! Form::label('no_kwitansi', 'No Kwitansi', ['class' => 'control-label col-md-4'] ) !!}
                                <div class="col-md-8">
                                    {!! Form::text('no_kwitansi', null, ['class' => 'form-control'] ) !!}
                                    <input type="hidden" name="wo_id" value="{{ $data_edit->wo_id }}">
                                    <input type="hidden" name="jenis_pekerjaan" value="{{ $data_edit->jenis_pekerjaan }}">
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tanggal_po') ? 'has-error' : ''}}">
                                  {!! Form::label('tanggal_po', 'Tanggal PO', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-8">
                                      <div class="input-group input-medium">
                                          <input type="text" class="form-control datepicker"  name="tanggal_po" autocomplete="off" >
                                          <div class="form-control-focus"> </div>
                                          <span class="input-group-addon">
                                              <i class="fa fa-calendar"></i>
                                          </span>
                                      </div>
                                      <div class="form-control-focus"> </div>
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('levering_po') ? 'has-error' : ''}}">
                                {!! Form::label('levering_po', 'Durasi Levering PO', ['class' => 'control-label col-md-4'] ) !!}
                                <div class="col-md-8">
                                    <div class="input-group">
                                        {!! Form::number('levering_po', $setting->levering_po, ['class' => 'form-control', 'id' => 'levering_po'] ) !!}
                                        <span class="input-group-addon" id="sizing-addon1">Hari</span>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
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
                                  {!! Form::label('dokumen', 'Dokumen', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-8">
                                      <input type="file" name="dokumen">
                                      <div class="form-control-focus"> </div>
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('bbm_konsumsi') ? 'has-error' : ''}}">
                                  {!! Form::label('bbm_konsumsi', 'BBM + Konsumsi', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-4">
                                      {!! Form::text('bbm_konsumsi', 0, ['class' => 'form-control rupiah hitung','id'=>'bbm_konsumsi'] ) !!}
                                      <div class="form-control-focus"> </div>
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('ppn') ? 'has-error' : ''}}">
                                  {!! Form::label('ppn', 'PPN', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-4">
                                    <div class="input-group">
                                        {!! Form::text('ppn', 0, ['class' => 'form-control rupiah hitung', 'id' => 'ppn'] ) !!}
                                    </div>
                                  </div>
                              </div>
                          </div>
                          
                          
                        </div>
                        <div class="row">
                          
                           
                          <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('pph22') ? 'has-error' : ''}}">
                                  {!! Form::label('pph22', 'PPH 22', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-4" >
                                    <div class="input-group">
                                        {!! Form::text('pph22', 0, ['class' => 'form-control rupiah hitung', 'id' => 'pph22'] ) !!}
                                        
                                    </div>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('pph23') ? 'has-error' : ''}}">
                                  {!! Form::label('pph23', 'PPH 23', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-4" >
                                    <div class="input-group">
                                        {!! Form::text('pph23', 0, ['class' => 'form-control  rupiah hitung', 'id' => 'pph23'] ) !!}
                                        
                                    </div>
                                  </div>
                              </div>
                          </div>
                          
                        </div>
                        
                        
                        <h3 class="form-section">Detail Purchase Order<a class="btn green-jungle" id="addData">
                            <i class="fa fa-plus"></i>

                        </a></h3>
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group ">
                                  {!! Form::label('jenis_item_label', 'Jenis Item', ['class' => 'control-label col-md-2'] ) !!}
                                  <div class="col-md-8">
                                        @if($data_edit->jenis_pekerjaan == 1)
                                            {{ Form::select('jenis_item_label', ['1'=>'Material'], null, ['class' => 'form-control ','id'=>'jenis_item']) }}
                                        @elseif($data_edit->jenis_pekerjaan == 2)
                                            {{ Form::select('jenis_item_label', ['2'=>'Jasa'], null, ['class' => 'form-control ','id'=>'jenis_item']) }}
                                        @else
                                            {{ Form::select('jenis_item_label', ['1'=>'Material','2'=>'Jasa'], null, ['class' => 'form-control ','id'=>'jenis_item']) }}
                                        @endif
                                  </div>
                              </div>
                          </div>
                        </div>
                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Jumlah</th>
                                    <th>Satuan</th>
                                    <th>Harga Supplier</th>                                 
                                    <th>Sub Total</th>
                                    <th class="text-center" width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="listTable">

                            </tbody>
                        </table>
                        <h3 class="form-section">Total Purchase Order</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Biaya Detail PO:</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static labelTotalDetail"> Rp. 0,00 </p>
                                        <input type="hidden" name="labelTotalDetail" id="labelTotalDetail">
                                        <input type="hidden" name="labelTotalDetailBBM" id="labelTotalDetailBBM">
                                       
                                        <input type="hidden" name="labelTotal" id="labelTotal">
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Biaya Detail + BBM + Konsumsi:</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static labelTotalDetailBBM"> Rp. 0,00 </p>
                                    </div>
                                </div>
                            </div>
                            <!--/span-->
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6 bold">Total:</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static labelTotal"> Rp. 0,00 </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3"></div>
                                {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Purchase Order']) !!}
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
<script src="{{assets('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
<script src="{{assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>
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

function convertToAngka(param_rupiah)
{
	return parseInt(param_rupiah.replace(/,.*|[^0-9]/g, ''), 10);
}


$(".datepicker").datepicker( {
    // "setDate", new Date(),
    "setDate": new Date(),
    format: "dd-mm-yyyy",
});

$('#supplier').on('change', function() {
    kd =  $( this ).val();
    jenis = "{{$data_edit->jenis_pekerjaan}}";
    $.ajax({
        method: "GET",
        url: "{{ url('api/usaha/getInfoSupplier') }}?kd="+kd+"&jenis="+jenis,
        dataType: "json",
        success : function(edata){
            $(".labelPPH").html(edata.pph+"%");
            $("#pph").val(edata.pph);
            perhitunganPO();
        }
    });
});

function perhitunganPO(){
    var bbm = convertToAngka($('#bbm_konsumsi').val());
    var ppn = convertToAngka($('#ppn').val());
    var pph22 = convertToAngka($("#pph22").val());
    var pph23 = convertToAngka($("#pph23").val());

    var harga_supplier = document.getElementsByName('harga_supplier[]');
    var jumlah = document.getElementsByName('jumlah[]');
    var total = 0;
    for (var i = 0; i <harga_supplier.length; i++) {
        
        var val_harga_supplier=convertToAngka(harga_supplier[i].value);
        var val_jumlah = jumlah[i].value;
        var subTotal = val_harga_supplier * val_jumlah;
        total = total+subTotal;
    }
    var grandTotal = total + bbm;
    
    var allTotal = grandTotal+ppn-pph22-pph23;
    
    if (isNaN(total)) total = 0; 
    if (isNaN(grandTotal)) grandTotal = 0; 
    if (isNaN(allTotal)) allTotal = 0; 

    $(".labelTotalDetail").html(convertToRupiah(total));
    $(".labelTotalDetailBBM").html(convertToRupiah(grandTotal));
    $(".labelTotal").html(convertToRupiah(allTotal));

    $("#labelTotalDetail").val(total);
    $("#labelTotalDetailBBM").val(grandTotal);
    $("#labelTotal").val(allTotal);
    
}
$(".hitung").keyup(function(){
    perhitunganPO();
});

var numberTBL = 1;
$("#addData").on('click', function(event){
    var varAppend= "<tr id='row"+numberTBL+"'>";
    varAppend += "<td>";
    varAppend += "<select name='listData[]' class='form-control listData' data-number='"+numberTBL+"'></select>";
    varAppend += "<input type='hidden' class='form-control'  id='detail_jenis_row"+numberTBL+"' data-number='"+numberTBL+"' name='jenis_item[]'>";
    varAppend += "</td>";
    
    varAppend += "<td width='80'>";
    varAppend += "<input type='number' class='form-control hitungDetail' id='detail_jumlah_row"+numberTBL+"' data-number='"+numberTBL+"' name='jumlah[]'>";
    varAppend += "</td>";

    varAppend += "<td width='100'>";
    varAppend += "<input type='text' class='form-control ' name='satuan[]'>";
    varAppend += "</td>";
    
    varAppend += "<td width='200'>";
    varAppend += "<input type='text' class='form-control rupiah hitungDetail'  id='detail_harga_row"+numberTBL+"' data-number='"+numberTBL+"' name='harga_supplier[]'>";
    varAppend += "</td>";
    
    varAppend += "<td width='250' align='right'>";
    varAppend += "<span class='total_row"+numberTBL+"'>Rp. 0,00</span>";
    varAppend += "</td>";
    
    varAppend += "</td width='100'>";
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
              var jenis = $("#jenis_item").val();
              return { q: $.trim(params.term), jenis_pekerjaan : jenis };
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

    $(".hitungDetail").keyup(function(){
        var id = $(this).attr('data-number');
        var detailJumlah = $("#detail_jumlah_row"+id).val();
        var detailHarga = $("#detail_harga_row"+id).val();
        var detailTotal = detailJumlah*convertToAngka(detailHarga);
        $(".total_row"+id).html(convertToRupiah(detailTotal));
        perhitunganPO();
    });
    $('.listData').on("change", function(e) { 
        var id = $(this).attr('data-number');
        var jenis = $("#jenis_item").val();

        $("#detail_jenis_row"+id).val(jenis);
        
    });
    
});




</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
