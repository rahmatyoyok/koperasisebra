@extends('layouts.master-ak')

@section('title', 'Akutansi Toko')

@push('styles')
    <link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ assets('global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->

    <style>
    .ui-datepicker-calendar {
        display: none;
    }

    .table thead tr th, .table tfoot tr th,  {
        font-size: 12px!important;
        font-weight: 600;
    }

    .table tbody tr td {
        font-size: 12px;
    }

    </style>
@endpush

@push('plugins')
    <script src="{{ assets('global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js') }}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
        var Initialized = []
        var getperiode = function(fperiode){
            var objbln = function(bln){
                var bulan = ["Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
                return bulan.indexOf(bln)+1;
            }
            // var fperiode = $('input[name=periodekKalkulasi]').val();
            var res = fperiode.split(" ");
            var nmrbln = objbln(res[0]).toString();
            var blnoke = nmrbln.length < 2 ? "0" + nmrbln : nmrbln;
            var blnokethn = blnoke+res[1];
            return blnokethn;
        }


        $(document).ready(function(){

            $('input[name=periodekKalkulasi]').datepicker( {
                format: "MM yyyy",
                startView: "months", 
                minViewMode: "months",
                language: 'id'
            }).on('changeDate', function (ev) {
                var periodes = getperiode($('input[name=periodekKalkulasi]').val());
                $('input[name=pmperiode]').val(periodes);

            });

        });

    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1> Kartu Piutang </h1>
        </div>
    </div>
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-xs-12 col-sm-12">
                <div class="portlet light bordered">
                    <div class="portlet-body">

                        <div class="row">
                            <div class="col-md-6">
                            <form action="" class="form-horizontal" method="GET">
                                <div class="form-body">
                                    <div class="form-group form-md-line-input">
                                        <label class="control-label col-md-3">Periode</label>
                                        <div class="col-md-4">
                                        <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                <input type="text" class="form-control text-right" name="periodekKalkulasi" value="{{ $currentMonth }}" readonly/>
                                                <input type="hidden" class="form-control text-right" name="pmperiode" value="{{ $currentMonthIndo }}" />
                                            <div class="form-control-focus"> </div>
                                        </div>   
                                        </div>
                                    </div>
                                    <div class="form-group form-md-line-input">
                                        <label class="control-label col-md-3">Status</label>
                                        <div class="col-md-4">
                                            <select class="form-control" id="status">
                                                <option value="0">Belum Lunas</option>
                                                <option value="1">Lunas</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                             
                                                {!! Form::button('Ambil Data', ['class' => 'btn green dt-btn tooltips', 'type' => 'button', 'id'=>'ambilData','data-swa-text' => 'Ambil Data ', 'style' => 'margin-right:5px;']) !!}
                                            
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <hr>
                        <div id="listData"></div>
                        
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
<script type="text/javascript" src="{{assets('/datepicker/moment.min.js') }}"></script>
<script type="text/javascript" src="{{assets('/datepicker/daterangepicker.min.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{assets('/datepicker/daterangepicker.css') }}" />
@endpush

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){


      $("#ambilData").on("click", function(){
          App.blockUI();

          var status = $("#status").val();
          var periodes = getperiode($('input[name=periodekKalkulasi]').val());

          $.ajax({
          type: "GET",
          url: "{{ url('akuntansi/toko/kartu-piutang') }}?status="+status+"&periode="+periodes,
          dataType: "html",
          success:function(data){
                $("#listData").html("");
                $("#listData").html(data);
                App.unblockUI();
          },
          error: function(xhr){
                  App.unblockUI();
                  $("#listData").html("");

          }
        });
      });

    });
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
