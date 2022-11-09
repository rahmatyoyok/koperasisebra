@extends('layouts.master')

@section('title', 'Laporan Persekot PO')

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
            <h1>Laporan Persekot PO
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
                        <div class="portlet-title tabbable-line">
                            <div class="caption">
                                <i class="icon-bubbles font-dark hide"></i>
                                <span class="caption-subject font-dark bold uppercase">Filter</span>
                            </div>
                        </div>
                        <div class="portlet-body form">
                          <div class="row">
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label class="control-label">Status</label>
                                      <select class="form-control" id="status">
                                        <option value="">Semua</option>
                                        <option value="1">Belum Diverifikasi</option>
                                        <option value="2">Diterima</option>
                                        <option value="4">Realisasi</option>
                                        <option value="99">Ditolak</option>

                                      </select>

                                  </div>
                              </div>
                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label class="control-label">Tanggal Pengajuan</label>
                                      <div class="form-check ">
                                        <div  id="tgl_pengajuan" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; ">
                                            <i class="fa fa-calendar"></i>&nbsp;
                                            <span></span> <i class="fa fa-caret-down"></i>
                                        </div>
                                      </div>

                                  </div>
                              </div>

                              <div class="col-md-4">
                                  <div class="form-group">
                                      <label class="control-label">Jatuh Tempo</label>
                                      <div class="form-check ">
                                        <div class="reportrange" id="tgl_jatuhtempo" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; ">
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
                          <div id="listData"></div>
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
      var startDataDate;
      var endDataDate;
      var filterStartDate;
      var filterEndDate;
      var filterStatus = null;
      var labelReportRange;

      function cb(start, end) {
        $('#tgl_pengajuan span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        $('#tgl_jatuhtempo span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
      }

      $('#tgl_pengajuan').daterangepicker({
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
              if ($('#tgl_pengajuan').attr('data-display-range') != '0') {
                  $('#tgl_pengajuan span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                  labelReportRange = start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY');
                  startDataDate = start.format('YYYY-MM-DD');
                  endDataDate = end.format('YYYY-MM-DD');

              }
          }
      );

      $('#tgl_jatuhtempo').daterangepicker({
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
              if ($('#tgl_jatuhtempo').attr('data-display-range') != '0') {
                  $('#tgl_jatuhtempo span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
                  labelReportRange = start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY');
                  startDataDate = start.format('YYYY-MM-DD');
                  endDataDate = end.format('YYYY-MM-DD');

              }
          }
      );

      cb(start, end);
      startDataDate = start.format('YYYY-MM-DD');
      endDataDate = end.format('YYYY-MM-DD');
      $('.labelStartEndDate').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));


      $("#preview").on("click", function(){
          App.blockUI();

          var status = $("#status").val();
          
          $.ajax({
          type: "GET",
          url: "{{ url('usaha/persekotpo/report') }}?status="+status+"&start="+startDataDate+"&end="+endDataDate,
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
