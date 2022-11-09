@extends('layouts.master')

@section('title', 'Tambah Work Order')

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
            <h1>Tambah Work Order</h1>
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
                  <div class="portlet-title">
                      <div class="caption">
                          <i class="icon-bubble font-dark hide"></i>
                          <span class="caption-subject font-hide bold uppercase">Tambah Work Order</span>
                      </div>

                  </div>
                  <div class="portlet-body">
                    {!! Form::open(['route' => 'wo.store', 'class' => 'form-horizontal']) !!}

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
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('nama_pekerjaan') ? 'has-error' : ''}}">
                                    {!! Form::label('nama_pekerjaan', 'Nama Pekerjaan', ['class' => 'control-label col-md-2'] ) !!}
                                    <div class="col-md-10">
                                        {!! Form::text('nama_pekerjaan', null, ['class' => 'form-control',   'autofocus'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tgl_levering') ? 'has-error' : ''}}">
                                    {!! Form::label('tgl_levering', 'Tanggal Levering', ['class' => 'control-label col-md-2'] ) !!}
                                    <div class="col-md-10">
                                        {!! Form::text('tgl_levering', null, ['class' => 'form-control datepicker', 'id' => 'reportrange'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jenis_pekerjaan') ? 'has-error' : ''}}">
                                    {!! Form::label('jenis_pekerjaan', 'Jenis Pekerjaan', ['class' => 'control-label col-md-2'] ) !!}
                                    <div class="col-md-10">
                                        {{ Form::select('jenis_pekerjaan', ['1'=>'Material','2'=>'Jasa','3'=>'Material & Jasa'], null, ['class' => 'form-control select2']) }}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('nilai_pekerjaan') ? 'has-error' : ''}}">
                                {!! Form::label('nilai_pekerjaan', 'Nilai Pekerjaan', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {!! Form::text('nilai_pekerjaan', null, ['class' => 'form-control rupiah','id'=>'nilai_pekerjaan'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div>
                          <!-- <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('client_id') ? 'has-error' : ''}}">
                                {!! Form::label('client_id', 'Client', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {{ Form::select('client_id', $client_id, null, ['class' => 'form-control select2']) }}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                          </div> -->

                        </div>

                        <div class="row">
                          <div class="col-md-6">
                              <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('lokasi_id') ? 'has-error' : ''}}">
                                  {!! Form::label('lokasi_id', 'Lokasi Pekerjaan', ['class' => 'control-label col-md-2'] ) !!}
                                  <div class="col-md-10">
                                      {{ Form::select('lokasi_id', $lokasi_id, null, ['class' => 'form-control select2']) }}
                                      <div class="form-control-focus"> </div>
                                      <span class="help-block"></span>
                                  </div>
                              </div>
                          </div>
                          <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('jenis_wo') ? 'has-error' : ''}}">
                                    {!! Form::label('jenis_wo', 'Jenis Transaksi', ['class' => 'control-label col-md-2'] ) !!}
                                    <div class="col-md-10">
                                        {{ Form::select('jenis_wo', ['1'=>'Purchase Order','2'=>'Persekot PO'], null, ['class' => 'form-control select2']) }}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            
                            <div class="col-md-6">
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('no_refrensi') ? 'has-error' : ''}}">
                                    {!! Form::label('no_refrensi', 'No PO PJB', ['class' => 'control-label col-md-2'] ) !!}
                                    <div class="col-md-10">
                                        {!! Form::text('no_refrensi', null, ['class' => 'form-control'] ) !!}
                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('keterangan') ? 'has-error' : ''}}">
                                {!! Form::label('keterangan', 'Nama PO PJB', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    {!! Form::text('keterangan', null, ['class' => 'form-control'] ) !!}
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                          </div>
                          <div class="col-md-6">
                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('dokumen') ? 'has-error' : ''}}">
                                {!! Form::label('dokumen', 'Dokumen Pendukung', ['class' => 'control-label col-md-2'] ) !!}
                                <div class="col-md-10">
                                    <input type="file" name="dokumen">
                                    <div class="form-control-focus"> </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        </div>

                        <h3 class="form-section">Pembayaran
                            <a class="btn green-jungle" id="addData">
                                <i class="fa fa-plus"></i>
                            </a>
                            <a class="btn blue" id="bagiNominal">
                                Bagi Nominal Pembayaran
                            </a>
                        </h3>
                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Keterangan</th>
                                    <th>Tanggal Jatuh Tempo</th>
                                    <th>Nominal</th>
                                    <th class="text-center" width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="listTable">

                            </tbody>
                        </table>


                    </div>

                    <div class="form-actions">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3"></div>
                                {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Work Order']) !!}
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
<script src="{{assets('global/plugins/bootstrap-daterangepicker/daterangepicker.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/bootstrap-daterangepicker/daterangepicker.min.js')}}" type="text/javascript"></script>
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


$(function() {

var start = moment().subtract(29, 'days');
var end = moment();

function cb(start, end) {
    $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
}

$('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Today': [moment(), moment()],
           'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
           'Last 7 Days': [moment().subtract(6, 'days'), moment()],
           'Last 30 Days': [moment().subtract(29, 'days'), moment()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, function(start, end, label) {
        if ($('#reportrange').attr('data-display-range') != '0') {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

            var startDataDate = start.format('YYYY-MM-DD');
            var endDataDate = end.format('YYYY-MM-DD');

            start = startDataDate;
            end = endDataDate;
            // console.log(startDate + endDate);
        }
    }
);

cb(start, end);

});



var numberTBL = 1;
$("#addData").on('click', function(event){
    var varAppend= "<tr id='row"+numberTBL+"'>";
    varAppend += "<td>";
    varAppend += "<input type='text' class='form-control' name='keterangan_pembayaran[]'>";
    varAppend += "</td>";

    varAppend += "<td>";
    varAppend += "<input type='text' class='form-control datepicker' name='tanggal_pembayaran[]'>";
    varAppend += "</td>";

    varAppend += "<td>";
    varAppend += "<input type='text' class='form-control rupiah hitungDetail'  id='detail_nominal_row"+numberTBL+"' data-number='"+numberTBL+"' name='nominal_pembayaran[]'>";
    varAppend += "</td>";


    varAppend += "</td>";
    varAppend += '<td><a href="javascript:;" class="btn btn-block btn-xs red delete" data-number="'+numberTBL+'"><i class="fa fa-trash"></i> Hapus</a>';
    varAppend += "</td>";
    varAppend += "</tr>";
    $("#listTable").append(varAppend);
    numberTBL++;

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

    $(".delete").on('click', function(event){
      var id = $(this).attr('data-number');
      $("#row"+id).remove();
    });

    $(".datepicker").datepicker( {
        // "setDate", new Date(),
        "setDate": new Date(),
        format: "dd-mm-yyyy",
    });
});


$("#bagiNominal").on('click', function(event){
    var nilai_pekerjaan = $("#nilai_pekerjaan").val();
    nilai_pekerjaan = convertToAngka(nilai_pekerjaan);
    var totalData= $("input[name='nominal_pembayaran[]']").length;

    var avg = nilai_pekerjaan/totalData;

    $("input[name='nominal_pembayaran[]']").val(avg);
});

</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
