@extends('layouts.master')

@section('title', 'Laporan Purchase Order')

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
            <h1>Laporan Purchase Order
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Supplier</label>
                                    {{ Form::select('supplier_id', $supplier_id, null, ['class' => 'form-control select2','id'=>'supplier_id','placeholder' => 'TAMPILKAN SEMUA...']) }}
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Tanggal PO</label>
                                    <div class="form-check ">
                                      <div  id="tanggal_po" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; ">
                                          <i class="fa fa-calendar"></i>&nbsp;
                                          <span></span> <i class="fa fa-caret-down"></i>
                                      </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="control-label">Tanggal Levering PO</label>
                                    <div class="form-check ">
                                      <div  id="tanggal_levering_po" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; ">
                                          <i class="fa fa-calendar"></i>&nbsp;
                                          <span></span> <i class="fa fa-caret-down"></i>
                                      </div>
                                    </div>

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
<script type="text/javascript" src="{{assets('/datepicker/moment.min.js') }}"></script>
<script type="text/javascript" src="{{assets('/datepicker/daterangepicker.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{assets('/datepicker/daterangepicker.css') }}" />
@endpush

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){


      //Start Datepicker
      var start = moment().subtract(29, 'days');
      var end = moment();
      var tglStart_1;
      var tglEnd_1;
      var tglStart_2;
      var tglEnd_2;
      var filterStartDate;
      var filterEndDate;
      var filterStatus = null;
      var labelReportRange;

      function cb(start, end) {
        $('#tanggal_po span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#tanggal_levering_po span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      }

      $('#tanggal_po').daterangepicker({
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
              if ($('#tanggal_po').attr('data-display-range') != '0') {
                  $('#tanggal_po span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                  labelReportRange = start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY');
                  tglStart_1 = start.format('YYYY-MM-DD');
                  tglEnd_1 = end.format('YYYY-MM-DD');

              }
          }
      );

      $('#tanggal_levering_po').daterangepicker({
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
              if ($('#tanggal_levering_po').attr('data-display-range') != '0') {
                  $('#tanggal_levering_po span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                  labelReportRange = start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY');
                  tglStart_2 = start.format('YYYY-MM-DD');
                  tglEnd_2 = end.format('YYYY-MM-DD');

              }
          }
      );

      cb(start, end);
      startDataDate = start.format('YYYY-MM-DD');
      endDataDate = end.format('YYYY-MM-DD');
      $('.labelStartEndDate').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));


      $("#preview").on("click", function(){
          App.blockUI();

          var supplier_id = $("#supplier_id").val();

          $.ajax({
          type: "GET",
          url: "{{ url('usaha/po/report') }}?supplier_id="+supplier_id+"&tglStart_1="+tglStart_1+"&tglEnd_1="+tglEnd_1+"&tglStart_2="+tglStart_2+"&tglEnd_2="+tglEnd_2,
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

    });
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
