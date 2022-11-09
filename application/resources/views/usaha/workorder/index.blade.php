@extends('layouts.master')

@section('title', 'Monitoring Work Order')

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
            <h1>Monitoring Work Order
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

                            <div class="form-horizontal">

                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                      <th>Kode</th>
                                                      <th>Client</th>
                                                        <th>Jenis Transaksi</th>
                                                        <th>Jenis Pekerjaan</th>
                                                        <!-- <th>Lokasi</th> -->
                                                        <th>Pekerjaan</th>
                                                        <th>Kekurangan Pembayaran</th>
                                                        <!-- <th>Nominal</th> -->
                                                        <th>Dibuat</th>
                                                        <th class="text-center" width="150">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tfoot>
                                                    <tr>
                                                      <th class="form-group form-md-line-input">Kode</th>
                                                      <th class="form-group form-md-line-input">Client</th>
                                                      <th class="form-group form-md-line-input">Jenis WO</th>
                                                      <th class="form-group form-md-line-input">Jenis Pekerjaan</th>
                                                      <!-- <th class="form-group form-md-line-input">Lokasi</th> -->
                                                      <th class="form-group form-md-line-input">Pekerjaan</th>
                                                      <th class="form-group form-md-line-input">Kekurangan Pembayaran</th>
                                                      <!-- <th class="form-group form-md-line-input">Nominal</th> -->
                                                      <th class="form-group form-md-line-input">Dibuat</th>
                                                      <th class="text-center" width="150">Aksi</th>
                                                    </tr>
                                                </tfoot>

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
<script type="text/javascript">
    $(document).ready(function(){
        $("#table1").DataTable({
            order: [[ 5, "desc" ]],
            processing: true,
            serverSide: true,
            ajax: '{!! url('usaha/wo') !!}',
            columns: [
                {data: 'kode_wo', name: 'kode_wo'},
                {data: 'client', name: 'client'},
                {data: 'jenis_wo', name: 'jenis_wo'},
                {data: 'jenis_pekerjaan', name: 'jenis_pekerjaan'},
                // {data: 'lokasi', name: 'lokasi'},
                {data: 'nama_pekerjaan', name: 'nama_pekerjaan'},
                
                // {data: 'nilai_pekerjaan', name: 'nilai_pekerjaan'},
                {data: 'sisa', name: 'sisa'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', sClass: 'text-center', orderable: false, searchable: false},
            ],
            initComplete: function () {
                this.api().columns().every(function (index) {
                    var column = this;
                    var colCount = this.columns().nodes().length - 1;
                    if(index !== colCount){ // && index !== 5
                        var input = document.createElement("input");
                        $(input).addClass('form-control');
                        // if(index == 2)
                        // {
                        //     $(input).attr('type', 'date');
                        // }
                        // if(index == 3)
                        // {
                        //     $(input).attr('type', 'date');
                        // }
                        $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    }

                    if(index == 2){
                      var column = this;
                      var select = $('<select class="form-control"><option value="">Pilih Semua</option></select>')
                          .appendTo( $(column.footer()).empty() )
                          .on( 'change', function () {
                              var val = $.fn.dataTable.util.escapeRegex(
                                  $(this).val()
                              );

                              column
                                  .search( val ? '^'+val+'$' : '', true, false )
                                  .draw();
                          } );

                          select.append( '<option value="Purchase Order(PO)">PO</option>' )
                          select.append( '<option value="Persekot PO">Persekot PO</option>' )
                    }

                    if(index == 3){
                      var column = this;
                      var select = $('<select class="form-control"><option value="">Pilih Semua</option></select>')
                          .appendTo( $(column.footer()).empty() )
                          .on( 'change', function () {
                              var val = $.fn.dataTable.util.escapeRegex(
                                  $(this).val()
                              );

                              column
                                  .search( val ? '^'+val+'$' : '', true, false )
                                  .draw();
                          } );

                          select.append( '<option value="Material">Material</option>' )
                          select.append( '<option value="Jasa">Jasa</option>' )
                          select.append( '<option value="Material Jasa">Material Jasa</option>' )
                    }

                });
                $(".dataTables_length select").select2();
            }
        });
    });
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
