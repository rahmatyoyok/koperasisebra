@extends('layouts.master')

@section('title', 'Tambah Pembelian Langsung')

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
            <h1>Tambah Pembelian Langsung
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
                    {!! Form::open(['route' => 'purchase.store','class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}

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
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tanggal_pembelian') ? 'has-error' : ''}}">
                                  {!! Form::label('tanggal_pembelian', 'Tanggal Pembelian', ['class' => 'control-label col-md-4'] ) !!}
                                  <div class="col-md-8">
                                      <div class="input-group input-medium">
                                          <input type="text" class="form-control datepicker"  name="tanggal_pembelian" autocomplete="off" >
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
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jenis_pembayaran') ? 'has-error' : ''}}">
                                {!! Form::label('jenis_pembayaran', 'Jenis Pembayaran', ['class' => 'control-label col-md-4'] ) !!}
                                <div class="col-md-8">
                                    {{ Form::select('jenis_pembayaran', [1=>'Transfer', 2=>'Tunai'], null, ['class' => 'form-control','id'=>'jenis']) }}
                                </div>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('diterima') ? 'has-error' : ''}}">
                                    {!! Form::label('diterima', 'Diterima Oleh', ['class' => 'control-label col-md-4'] ) !!}
                                    <div class="col-md-8">
                                        {!! Form::text('diterima', null, ['class' => 'form-control', 'id' => 'diterima', 'autofocus'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">{{ $errors->has('diterima') ? $errors->first('diterima') : 'Masukkan Nama Penerima' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('keterangan') ? 'has-error' : ''}}">
                                    {!! Form::label('keterangan', 'Keterangan', ['class' => 'control-label col-md-4'] ) !!}
                                    <div class="col-md-8">
                                        {!! Form::textarea('keterangan', null, ['class' => 'form-control', 'id' => 'deskripsi', 'autofocus'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block">{{ $errors->has('keterangan') ? $errors->first('keterangan') : 'Masukkan Keterangan' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>



                        <h3 class="form-section">Detail Item<a class="btn green-jungle" id="addData">
                            <i class="fa fa-plus"></i>

                        </a></h3>
                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="100">COA</th>
                                    <!-- <th>Keterangan COA</th> -->
                                    <th>Keterangan</th>
                                    <th>Total</th>
                                    <th class="text-center" width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="listTable">

                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label col-md-6">Total Pembayaran:</label>
                                    <div class="col-md-6">
                                        <p class="form-control-static labelTotal"> Rp. 0,00 </p>
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
                                {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Pembelian Langsung']) !!}
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

function perhitunganPO(){

    var jumlah = document.getElementsByName('jumlah[]');
    var total = 0;
    for (var i = 0; i <jumlah.length; i++) {

        var val_jumlah=convertToAngka(jumlah[i].value);
        total = total+val_jumlah;
    }

    $(".labelTotal").html(convertToRupiah(total));

    $("#labelTotal").val(total);

}
$(".hitung").keyup(function(){
    perhitunganPO();
});

var numberTBL = 1;
$("#addData").on('click', function(event){
    var varAppend= "<tr id='row"+numberTBL+"'>";

    varAppend += "<td width='100'>";
    varAppend += "<select class='form-control input-sm inputCoa' name='coa_id[]' tabindex='0' aria-hidden='false'></select>";
    varAppend += "</td>";

    // varAppend += "<td>";
    // varAppend += "<input class='form-control input-sm' disabled='disabled' name='desc[]' type='text' >";
    // varAppend += "</td>";

    varAppend += "<td>";
    varAppend += "<input type='text' class='form-control'  name='keterangan_detail[]'>";
    varAppend += "</td>";

    varAppend += "<td>";
    varAppend += "<input type='text' class='form-control rupiah hitungDetail'  id='detail_harga_row"+numberTBL+"' data-number='"+numberTBL+"' name='jumlah[]'>";
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
        minimumInputLength: 3,
        placeholder:"Pilih Coa",
        ajax: {
            url: BaseUrl+"/api/select2/coaaktif",
            dataType: 'json',
            cache: false,
            data: function (params) {
                return { q: $.trim(params.term), page : 1 };
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
