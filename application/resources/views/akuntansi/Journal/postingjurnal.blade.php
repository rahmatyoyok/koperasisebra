@extends('layouts.master-ak')

@section('title', $title)

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
        font-size: 10px!important;
        font-weight: 600;
    }

    .table tbody tr td {
        font-size: 10px;
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
                App.blockUI({});
                var periodes = getperiode($('input[name=periodekKalkulasi]').val());
                $('input[name=pmperiode]').val(periodes);
                
                $.ajax({
                    url: BaseUrl+"/akuntansi/laporan/periodehasposting",
                    dataType:'json',
                    data:{ ParamPeriode : periodes },
                    success:function(r){
                        
                        $('button[id=submitPosting]').removeClass('hide');
                        $('button[id=submitPosting]').show();
                        if(r.response == true){
                            $('button[id=submitPosting]').hide();
                        }
                        App.unblockUI();
                    }
                }).done(function(){
                    App.unblockUI();
                });
                

                $('input[name=periodekKalkulasi]').datepicker('hide');
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
            <h1> Laporan Buku Besar </h1>
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
                                    
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-offset-3 col-md-9">
                                            
                                                {!! Form::button('<i class="glyphicon glyphicon-pushpin"></i> Posting', ['class' => 'btn green dt-btn tooltips '.(($hasPosting > 0) ? ' hide':''), 'type' => 'submit', 'id'=>'submitPosting','data-swa-text' => 'Posting Jurnal ', 'style' => 'margin-right:5px;']) !!}
                                                
                                            
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h4 id="labelCoa"> Daftar Posting</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th> # </th>
                                                <th> Tanggal </th>
                                                <th> No Posting </th>
                                                <th> Periode </th>
                                                <th> Aksi </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($listPosting)
                                            @php $i = 1; $saldo = 0; @endphp
                                                @foreach($listPosting as $rws)
                                                <tr>
                                                    <td> {{ $i }} </td>
                                                    <th style="text-align:center"> {{ ((strlen($rws->tanggal) > 0) ? date('d ', strtotime($rws->tanggal)). getMonths()[(int)date('m', strtotime($rws->tanggal))]  .date(' Y', strtotime($rws->tanggal))  :"") }} </th>
                                                    <th style="text-align:center"> {{ $rws->posting_no }} </th>
                                                    <th> {{$rws->periode}} </th>
                                                    <th style="text-align:center;width:10%"> <a class="unposting btn btn-xs red-soft" data-id="{{$rws->periode}}"> <i class="fa fa-unlock"></i> Unposting </a> </th>
                                                </tr> 
                                                @php $i++; @endphp
                                                @endforeach
                                            @endif
                                        </tbody>
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
<!-- END PAGE CONTENT BODY -->



@endsection