@extends('layouts.master')

@section('title', 'Pendaftaran Aset')

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
            <h1>Pendaftaran Aset
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
                    {!! Form::open(['route' => 'aset.store','class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
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
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tgl_pembelian') ? 'has-error' : ''}}">
                                  {!! Form::label('tgl_pembelian', 'Tanggal Pembelian', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-8">
                                      <div class="input-group input-medium">
                                          <input type="text" class="form-control datepicker"  name="tgl_pembelian" autocomplete="off" >
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
<!-- 
                          <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('ppn') ? 'has-error' : ''}}">
                                  {!! Form::label('ppn', 'PPN', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-4">
                                    <div class="input-group">
                                        {!! Form::number('ppn', $setting->ppn, ['class' => 'form-control hitung', 'id' => 'ppn'] ) !!}
                                        <span class="input-group-addon" id="sizing-addon1">%</span>
                                    </div>
                                  </div>
                              </div>
                          </div>



                          <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('pph22') ? 'has-error' : ''}}">
                                  {!! Form::label('pph22', 'PPH 22', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-4" >
                                    <div class="input-group">
                                        {!! Form::text('pph22', null, ['class' => 'form-control hitung', 'id' => 'pph22'] ) !!}
                                        <span class="input-group-addon" id="sizing-addon1">%</span>
                                    </div>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('pph23') ? 'has-error' : ''}}">
                                  {!! Form::label('pph23', 'PPH 23', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-4" >
                                    <div class="input-group">
                                        {!! Form::text('pph23', null, ['class' => 'form-control hitung', 'id' => 'pph23'] ) !!}
                                        <span class="input-group-addon" id="sizing-addon1">%</span>
                                    </div>
                                  </div>
                              </div>
                          </div> -->
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

                        </div>


                        <h3 class="form-section">Daftar Aset<a class="btn green-jungle" id="addData">
                            <i class="fa fa-plus"></i>

                        </a></h3>

                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>COA Aset</th>
                                    <th>COA Beban Penyusutan</th>
                                    <th>COA Akumulasi Pneyusutan</th>
                                    <th>Nama Barang</th>
                                    <th>Masa Manfaat (Tahun)</th>
                                    <th>Tarif (% Tahun)</th>
                                    <th>Jumlah</th>
                                    <th>Harga Supplier</th>
                                    <th>Sub Total</th>
                                    <th class="text-center" width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="listTable">

                            </tbody>
                        </table>
                        <h3 class="form-section">Total Purchase Order</h3>

                        <!-- <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">PPN:</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static labelPPN"> Rp. 0,00 </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">PPH:</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static labelPPHNominal"> Rp. 0,00 </p>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                        <div class="row">
                            <!-- <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Sub Total:</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static labelTotalDetail"> Rp. 0,00 </p>
                                        
                                    </div>
                                </div>
                            </div> -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6 bold">Total:</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static labelTotal"> Rp. 0,00 </p>
                                        <input type="hidden" name="labelTotalDetail" id="labelTotalDetail">
                                        <input type="hidden" name="labelPPHNominal" id="labelPPHNominal">
                                        <input type="hidden" name="labelPPNNominal" id="labelPPNNominal">
                                        <input type="hidden" name="labelTotal" id="labelTotal">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3"></div>
                                {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Aset']) !!}
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
var BaseUrl = '{{ url('/') }}';

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

});

function perhitunganPO(){

    // var ppn = $('#ppn').val();
    // var pph22 = $("#pph22").val();
    // var pph23 = $("#pph23").val();

    var harga_supplier = document.getElementsByName('harga_supplier[]');
    var jumlah = document.getElementsByName('jumlah[]');
    var total = 0;
    for (var i = 0; i <harga_supplier.length; i++) {

        var val_harga_supplier=convertToAngka(harga_supplier[i].value);
        var val_jumlah = jumlah[i].value;
        var subTotal = val_harga_supplier * val_jumlah;
        total = total+subTotal;
    }
    var grandTotal = total;
    // var dasarPPN = grandTotal*(ppn/100);
    // var dasarPPH22 = grandTotal*(pph22/100);
    // var dasarPPH23 = grandTotal*(pph23/100);
    // var dasarPPH = dasarPPH22+dasarPPH23;
    // var allTotal = grandTotal+dasarPPN+dasarPPH;
    var allTotal = grandTotal;

    // $(".labelTotalDetail").html(convertToRupiah(total));
    // $(".labelPPN").html(convertToRupiah(dasarPPN));
    // $(".labelPPHNominal").html(convertToRupiah(dasarPPH));
    $(".labelTotal").html(convertToRupiah(allTotal));

    // $("#labelTotalDetail").val(total);
    // $("#labelPPHNominal").val(dasarPPH);
    // $("#labelPPNNominal").val(dasarPPN);
    $("#labelTotal").val(allTotal);

}
$(".hitung").keyup(function(){
    perhitunganPO();
});

var numberTBL = 1;
$("#addData").on('click', function(event){
    var varAppend= "<tr id='row"+numberTBL+"'>";

    varAppend += "<td width='200px'>";
    varAppend += "<select class='form-control input-sm inputCoa' name='coa[]' tabindex='0' aria-hidden='false'></select>";
    varAppend += "</td>";

    varAppend += "<td width='200px'><select class='form-control input-sm inputCoaBeban' name='coaBeban[]' tabindex='0' aria-hidden='false'></select></td>";
    varAppend += "<td width='200px'><select class='form-control input-sm inputCoaAkm' name='coaAkm[]' tabindex='0' aria-hidden='false'></select></td>";

    varAppend += "<td>";
    varAppend += "<input type='text' class='form-control '  name='nama[]'>";
    varAppend += "</td>";

    varAppend += "<td>";
    varAppend += "<input type='number' class='form-control '  name='masa_manfaat[]'>";
    varAppend += "</td>";

    varAppend += "<td>";
    varAppend += "<input type='number' class='form-control '  name='tarif[]'>";
    varAppend += "</td>";

    varAppend += "<td>";
    varAppend += "<input type='number' class='form-control hitungDetail' id='detail_jumlah_row"+numberTBL+"' data-number='"+numberTBL+"' name='jumlah[]'>";
    varAppend += "</td>";

    varAppend += "<td>";
    varAppend += "<input type='text' class='form-control rupiah hitungDetail'  id='detail_harga_row"+numberTBL+"' data-number='"+numberTBL+"' name='harga_supplier[]'>";
    varAppend += "</td>";

    varAppend += "<td>";
    varAppend += "<span class='total_row"+numberTBL+"'>Rp. 0,00</span>";
    varAppend += "</td>";

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
        

    $('select.inputCoa').select2({
        width : '100%',
        placeholder:"Pilih Coa",
        ajax: {
            url: BaseUrl+"/api/select2/coaaset",
            dataType: 'json',
            cache: false,
            data: function (params) {
                return { q: $.trim(params.term), asetType : 1, page : 1 };
            },
            results: function (data, page) {
                return { results: data.results };
            }
        }
    });

    $('select.inputCoaBeban').select2({
        width : '100%',
        placeholder:"Pilih Coa",
        ajax: {
            url: BaseUrl+"/api/select2/coaaset",
            dataType: 'json',
            cache: false,
            data: function (params) {
                return { q: $.trim(params.term), asetType : 2, page : 1 };
            },
            results: function (data, page) {
                return { results: data.results };
            }

        }
    });

    $('select.inputCoaAkm').select2({
        width : '100%',
        placeholder:"Pilih Coa",
        ajax: {
            url: BaseUrl+"/api/select2/coaaset",
            dataType: 'json',
            cache: false,
            data: function (params) {
                return { q: $.trim(params.term), asetType : 3, page : 1 };
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
