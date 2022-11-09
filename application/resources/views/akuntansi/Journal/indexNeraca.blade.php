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
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ assets('pages/scripts/Akuntansi/laporan.bukubesar.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1> Laporan Neraca </h1>
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
                                            <button type="submit" id="StartSubmit" class="btn btn-info">Search</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                        @if($showOrHide)
                        <hr>
                        <div class="row">
                            <h4 style="text-align:center;">Laporan Neraca</h4>
                                <h4 style="text-align:center;">Periode <b>{{ $currentMonth }}</b></h4>

                            {{--  ASET   --}}
                            <div class="col-md-6">
                                
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        {{--  <thead>
                                            <tr>
                                                <th colspan="3"> Keterangan </th>
                                                <th colspan="3"> Jumlah </th>
                                            </tr>
                                        </thead>  --}}
                                        <tbody>
                                            <tr>
                                                <td colspan="6"><b>ASET</b></td>
                                            </tr>
                                            {{--  ASET LANCAR  --}}
                                            <tr>
                                                <td></td>
                                                <td colspan="5"><b>ASET LANCAR</b></td>
                                            </tr>
                                            @php $totalAsetLancar = 0; @endphp
                                            @foreach($listAsetLancar as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->header_desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->saldo >= 0) ? number_format($rows->saldo, 2,'.',','):"(".number_format($rows->saldo*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @php $totalAsetLancar += $rows->saldo; @endphp
                                            @endforeach
                                            
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><b>JUMLAH ASET LANCAR</b></td>
                                                <td></td>
                                                <td><b>Rp. <span style="float:right">{{ ($totalAsetLancar >= 0) ? number_format($totalAsetLancar, 2,'.',','):"(".number_format($totalAsetLancar*-1, 2,'.',',').")"  }}</span></b></td>
                                                <td></td>
                                            </tr>

                                            {{--  ASET TIDAK LANCAR  --}}
                                            <tr>
                                                <td colspan="6">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td colspan="5"><b>ASET TIDAK LANCAR</b></td>
                                            </tr>
                                            @php $totalAsetTetap = 0; @endphp
                                            @foreach($listAsetTetap as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->header_desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->saldo >= 0) ? number_format($rows->saldo, 2,'.',','):"(".number_format($rows->saldo*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td><td></td>
                                                </tr>
                                            @php $totalAsetTetap += $rows->saldo; @endphp
                                            @endforeach
                                            
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><b>JUMLAH ASET TIDAK LANCAR</b></td>
                                                <td></td>
                                                <td><b>Rp. <span style="float:right">{{ ($totalAsetTetap >= 0) ? number_format($totalAsetTetap, 2,'.',','):"(".number_format($totalAsetTetap*-1, 2,'.',',').")"  }}</span></b></td>
                                                <td></td>
                                            </tr>

                                            {{--  ASET LAIN  --}}
                                            <tr>
                                                <td colspan="6">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td colspan="5"><b>ASET LAIN</b></td>
                                            </tr>
                                            @php $totalAsetLain = 0; @endphp
                                            @foreach($listAsetLain as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->header_desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->saldo >= 0) ? number_format($rows->saldo, 2,'.',','):"(".number_format($rows->saldo*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @php $totalAsetLain += $rows->saldo; @endphp
                                            @endforeach
                                            
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><b>JUMLAH ASET LAIN</b></td>
                                                <td></td>
                                                <td><b>Rp. <span style="float:right">{{ ($totalAsetTetap >= 0) ? number_format($totalAsetTetap, 2,'.',','):"(".number_format($totalAsetTetap*-1, 2,'.',',').")"  }}</span></b></td>
                                                <td></td>
                                            </tr>

                                            @php $totalAset = $totalAsetLancar + $totalAsetTetap + $totalAsetLain; @endphp
                                            <tr>
                                                <td colspan="5"><b>JUMLAH ASET</b></td>
                                                <td><b>Rp. <span style="float:right">{{ ($totalAset >= 0) ? number_format($totalAset, 2,'.',','):"(".number_format($totalAset*-1, 2,'.',',').")"  }}</span></b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--  KEWAJIBAN   --}}
                            <div class="col-md-6">
                                
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        {{--  <thead>
                                            <tr>
                                                <th colspan="3"> Keterangan </th>
                                                <th colspan="3"> Jumlah </th>
                                            </tr>
                                        </thead>  --}}
                                        <tbody>
                                            <tr>
                                                <td colspan="7"><b>KEWAJIBAN DAN EKUITAS</b></td>
                                            </tr>
                                            {{--  KEWAJIBAN LANCAR  --}}
                                            <tr>
                                                <td></td>
                                                <td colspan="6"><b>KEWAJIBAN LANCAR</b></td>
                                            </tr>
                                            @php $totalLeabilitasLancar = 0; @endphp
                                            @foreach($listKwajibanlancar as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->header_desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->saldo >= 0) ? number_format($rows->saldo, 2,'.',','):"(".number_format($rows->saldo*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td><td></td>
                                                </tr>
                                            @php $totalLeabilitasLancar += $rows->saldo; @endphp
                                            @endforeach
                                            
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><b>JUMLAH KEWAJIBAN LANCAR</b></td>
                                                <td></td>
                                                <td><b>Rp. <span style="float:right">{{ ($totalLeabilitasLancar >= 0) ? number_format($totalLeabilitasLancar, 2,'.',','):"(".number_format($totalLeabilitasLancar*-1, 2,'.',',').")"  }}</span></b></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                            {{--  KEWAJIBAN JANGKA PANJANG  --}}
                                            <tr>
                                                <td colspan="7">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td colspan="6"><b>KEWAJIBAN JANGKA PANJANG</b></td>
                                            </tr>
                                            @php $totalLeabilitasJp = 0; @endphp
                                            @foreach($listKwajibanJangkaPanjang as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->header_desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->saldo >= 0) ? number_format($rows->saldo, 2,'.',','):"(".number_format($rows->saldo*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td><td></td><td></td>
                                                </tr>
                                            @php $totalLeabilitasJp += $rows->saldo; @endphp
                                            @endforeach
                                            
                                            @php $totalLeabilitas = $totalLeabilitasLancar + $totalLeabilitasJp; @endphp
                                            

                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><b>KEWAJIBAN JANGKA PANJANG</b></td>
                                                <td></td>
                                                <td><b>Rp. <span style="float:right">{{ ($totalLeabilitasJp >= 0) ? number_format($totalLeabilitasJp, 2,'.',','):"(".number_format($totalLeabilitasJp*-1, 2,'.',',').")"  }}</span></b></td>
                                                <td></td>
                                                <td></td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td colspan="4"><b>JUMLAH KEWAJIBAN</b></td>
                                                <td><b>Rp. <span style="float:right">{{ ($totalLeabilitas >= 0) ? number_format($totalLeabilitas, 2,'.',','):"(".number_format($totalLeabilitas*-1, 2,'.',',').")"  }}</span></b></td>
                                                <td></td>
                                            </tr>

                                            {{--  EKUITAS  --}}
                                            <tr>
                                                <td colspan="7">&nbsp;</td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td colspan="6"><b>EKUITAS</b></td>
                                            </tr>
                                            @php $totalekuitas = 0; @endphp
                                            @foreach($listEkuitas as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->header_desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->saldo >= 0) ? number_format($rows->saldo, 2,'.',','):"(".number_format($rows->saldo*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @php $totalekuitas += $rows->saldo; @endphp
                                            @endforeach
                                            
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><b>JUMLAH EKUITAS</b></td>
                                                <td></td>
                                                <td></td>
                                                <td><b>Rp. <span style="float:right">{{ ($totalekuitas >= 0) ? number_format($totalekuitas, 2,'.',','):"(".number_format($totalekuitas*-1, 2,'.',',').")"  }}</span></b></td>
                                                <td></td>
                                            </tr>

                                            @php $totalLbEk = $totalLeabilitas + $totalekuitas; @endphp
                                            <tr>
                                                <td colspan="6"><b>JUMLAH KEWAJIBAN DAN EKUITAS</b></td>
                                                <td><b>Rp. <span style="float:right">{{ ($totalLbEk >= 0) ? number_format($totalLbEk, 2,'.',','):"(".number_format($totalLbEk*-1, 2,'.',',').")"  }}</span></b></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT BODY -->



@endsection