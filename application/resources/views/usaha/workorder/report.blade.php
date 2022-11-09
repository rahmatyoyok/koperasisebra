@extends('layouts.master')

@section('title', 'Laporan Work Order')

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
            <h1>Laporan Work Order
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
                        <div class="portlet-body form">
                          <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Client</label>
                                    {{ Form::select('client_id', $client_id, null, ['class' => 'form-control select2','id'=>'client_id','placeholder' => 'TAMPILKAN SEMUA...']) }}
                                </div>
                            </div>
                              <div class="col-md-3">
                                  <div class="form-group">
                                      <label class="control-label">Lokasi Pekerjaan</label>
                                      {{ Form::select('lokasi_id', $lokasi_id, null, ['class' => 'form-control select2','id'=>'lokasi_id','placeholder' => 'TAMPILKAN SEMUA...']) }}
                                  </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Jenis Pekerjaan</label>
                                    {{ Form::select('jenis_pekerjaan', ['1'=>'Material','2'=>'Jasa'], null, ['class' => 'form-control','id'=>'jenis_pekerjaan','placeholder' => 'TAMPILKAN SEMUA...']) }}


                                </div>
                              </div>
                              <div class="col-md-3">
                                <div class="form-group">
                                    <label class="control-label">Nama Pekerjaan</label>
                                    <input type="text" class="form-control " id="nama_pekerjaan" >


                                </div>
                              </div>



                          </div>

                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-12 text-center">

                                        <a id="preview" type="button" class="btn blue" ><i class="fa fa-search"></i> Search</a>
                                        <!-- <a id="excel" type="button" class="btn green" ><i class="fa fa-file-excel-o"></i> Excel</a> -->
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div id="listData" ></div>

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
$(document).ready(function(){



    $("#preview").on("click", function(){
        App.blockUI();

        var lokasi_id = $("#lokasi_id").val();
        var client_id = $("#client_id").val();
        var jenis_pekerjaan = $("#jenis_pekerjaan").val();
        var nama_pekerjaan = $("#nama_pekerjaan").val();

        $.ajax({
    		type: "GET",
    		url: "{{ url('usaha/wo/report') }}?jenis_pekerjaan="+jenis_pekerjaan+"&lokasi_id="+lokasi_id+"&client_id="+client_id+"&nama_pekerjaan="+nama_pekerjaan,
    		dataType: "html",
    		success:function(data){
                $("#listData").html("");
    			         $("#listData").html(data);
                App.unblockUI();
    		},
    		error: function(xhr){
                App.unblockUI();
                $("#listData").html("");

                toastr['error'](JSON.parse(xhr.responseText), 'Gagal!');
    		}
    	});
    });


    $("#excel").on("click", function(){

      var kelompok = $("#kelompok").val();
      var lokasi_id = $("#lokasi_id").val();
      var perihal = $("#perihal").val();
      var no_berkas = $("#no_berkas").val();
      var kolom = $("#kolom").val();
      var tahun = $("#tahun").val();
      var rak = $("#rak").val();
      var box = $("#box").val();
      var client_id = $("#client_id").val();

        window.location.href = "{{ url('usaha/wo/excel') }}?kelompok="+kelompok+"&lokasi_id="+lokasi_id+"&perihal="+perihal+"&no_berkas="+no_berkas+"&kolom="+kolom+"&tahun="+tahun+"&rak="+rak+"&box="+box+"&client_id="+client_id;

    });

});
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
