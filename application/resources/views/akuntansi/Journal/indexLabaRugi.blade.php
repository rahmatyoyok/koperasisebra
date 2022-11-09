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
            <h1> Laporan Laba Rugi </h1>
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
                                        <label class="control-label col-md-3">Divisi</label>
                                        <div class="col-md-8">
                                            <select name="divisi_id" id="divisi_id" class="form-control select2">
                                                <option value="UM" {{ ($currentDivisi == 'UM') ? "selected" : "" }}> Usaha Umum </option>
                                                <option value="SP" {{ ($currentDivisi == 'SP') ? "selected" : "" }}> Usaha Simpan Pinjam </option>
                                                <option value="TK" {{ ($currentDivisi == 'TK') ? "selected" : "" }}> Toko </option>
                                            </select>
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
                            <div class="col-md-12">
                                <h4 style="text-align:center;">Laporan Laba Rugi Divisi <b>{{ ($currentDivisi == 'UM') ? "Usaha Umum" : (($currentDivisi == 'SP') ? "Simpan Pinjam" : (($currentDivisi == 'TK') ? "Toko" : "")) }}</b></h4>
                                <h4 style="text-align:center;">Periode <b>{{ $currentMonth }}</b></h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th colspan="2"> Keterangan </th>
                                                <th colspan="3"> Jumlah </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $totalPendapatan = 0; @endphp
                                            @foreach($listPendapatan as $rws)
                                                @php $sum = ((isset($sum)) ? $sum : 0)  + $rws->ending_balance; @endphp
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rws->code." - ".$rws->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ number_format($rws->ending_balance, 2,'.',',') }}</span></td>
                                                    <td></td><td></td>
                                                </tr>
                                                @if($rws->counted == 1)
                                                    <tr>
                                                        <td></td>
                                                        <td><b>{{ $rws->group_desc }}</b></td>
                                                        <td></td><td></td>
                                                        <td><b>Rp. <span style="float:right">{{ number_format($sum, 2,'.',',') }}</span></b></td>
                                                    </tr>
                                                    @php $totalPendapatan = $sum; $sum = 0; @endphp
                                                @endif
                                            @endforeach

                                            <tr>
                                                <td colspan="5">&nbsp;</td>
                                            </tr>

                                            @php $totalBebanLangsung = 0; @endphp
                                            @foreach($listBebanLangsung as $rws)
                                                @php $sumBl = ((isset($sumBl)) ? $sumBl : 0)  + $rws->ending_balance; @endphp
                                                {{-- <tr>
                                                    <td></td>
                                                    <td>{{ $rws->code." - ".$rws->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ number_format($rws->ending_balance, 2,'.',',') }}</span></td>
                                                    <td></td><td></td>
                                                </tr> --}}
                                                @if($rws->counted == 1)
                                                    <tr>
                                                        <td></td>
                                                        <td><b>{{ $rws->group_desc }}</b></td>
                                                        <td></td>
                                                        @if($currentDivisi == 'SP')
                                                        <td></td>
                                                        <td><b>Rp. <span style="float:right">{{ number_format($sumBl, 2,'.',',') }}</span></b></td>
                                                        @elseif($currentDivisi == 'UM' || $currentDivisi == 'TK')
                                                        <td><b>Rp. <span style="float:right">{{ number_format($sumBl, 2,'.',',') }}</span></b></td><td></td>
                                                        @endif
                                                    </tr>   
                                                    @php $totalBebanLangsung = $totalBebanLangsung + $sumBl; $sumBl = 0; @endphp
                                                @endif
                                            @endforeach

                                            @if($currentDivisi == 'UM' || $currentDivisi == 'TK')
                                            
                                                <tr>
                                                    <td></td>
                                                    <td><b>BEBAN LANGSUNG</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Rp. <span style="float:right">{{ number_format($totalBebanLangsung, 2,'.',',') }}</span></b></td>
                                                </tr>
                                            @endif

                                            <tr>
                                                <td colspan="5">&nbsp;</td>
                                            </tr>
                                            
                                            @php $totalBebanOps = 0; @endphp
                                            @foreach($listBebanOpersaional as $rws)
                                                @php $sumOps = ((isset($sumOps)) ? $sumOps : 0)  + $rws->ending_balance; @endphp
                                                {{-- <tr>
                                                    <td></td>
                                                    <td>{{ $rws->code." - ".$rws->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ number_format($rws->ending_balance, 2,'.',',') }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> --}}
                                                @if($rws->counted == 1)
                                                    <tr>
                                                        <td></td>
                                                        <td><b>{{ $rws->group_desc }}</b></td>
                                                        <td></td>
                                                        <td><b>Rp. <span style="float:right">{{ number_format($sumOps, 2,'.',',') }}</span></b></td>
                                                        <td></td>
                                                    </tr>
                                                    @php $totalBebanOps = $totalBebanOps + $sumOps; $sumOps = 0; @endphp
                                                @endif
                                            @endforeach

                                                <tr>
                                                    <td></td>
                                                    <td><b>BEBAN OPERASIONAL</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Rp. <span style="float:right">{{ number_format($totalBebanOps, 2,'.',',') }}</span></b></td>
                                                </tr>
                                            <tr>
                                                <td colspan="5">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                
                                                    <td></td>
                                                    <td><b>LABA (RUGI) SEBELUM PAJAK</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Rp. <span style="float:right">{{ number_format( ($totalPendapatan - $totalBebanLangsung - $totalBebanOps), 2,'.',',') }}</span></b></td>
                                                
                                            </tr>

                                            <tr>
                                                <td colspan="5">&nbsp;</td>
                                            </tr>
                                            @php $totalPendapatanLuarUsaha = 0; @endphp
                                            @foreach($listPendapatanLuarUsaha as $rws)
                                                @php $sumPlu = ((isset($sumPlu)) ? $sumPlu : 0)  + $rws->ending_balance; @endphp
                                                {{-- <tr>
                                                    <td></td>
                                                    <td>{{ $rws->code." - ".$rws->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ number_format($rws->ending_balance, 2,'.',',') }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> --}}
                                                @if($rws->counted == 1)
                                                    <tr>
                                                        <td></td>
                                                        <td><b>{{ $rws->group_desc }}</b></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Rp. <span style="float:right">{{ number_format($sumPlu, 2,'.',',') }}</span></b></td>
                                                    </tr>
                                                    @php $totalPendapatanLuarUsaha = $totalPendapatanLuarUsaha + $sumPlu; $sumPlu = 0; @endphp
                                                @endif
                                            @endforeach

                                            <tr>
                                                <td colspan="5">&nbsp;</td>
                                            </tr>
                                            @php $totalBebanLuarUsaha = 0; @endphp
                                            @foreach($listBebanLuarUsaha as $rws)
                                                @php $sumBlu = ((isset($sumBlu)) ? $sumBlu : 0)  + $rws->ending_balance; @endphp
                                                {{-- <tr>
                                                    <td></td>
                                                    <td>{{ $rws->code." - ".$rws->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ number_format($rws->ending_balance, 2,'.',',') }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr> --}}
                                                @if($rws->counted == 1)
                                                    <tr>
                                                        <td></td>
                                                        <td><b>{{ $rws->group_desc }}</b></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><b>Rp. <span style="float:right">{{ number_format($sumBlu, 2,'.',',') }}</span></b></td>
                                                    </tr>
                                                    @php $totalBebanLuarUsaha = $totalBebanLuarUsaha + $sumBlu; $sumBlu = 0; @endphp
                                                @endif
                                            @endforeach
                                                

                                            <tr>
                                                <td colspan="5">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                
                                                    <td></td>
                                                    <td><b>LABA (RUGI) SETELAH PAJAK</b></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td><b>Rp. <span style="float:right">{{ number_format( (($totalPendapatan - $totalBebanLangsung - $totalBebanOps) - $totalPendapatanLuarUsaha - $totalBebanLuarUsaha), 2,'.',',') }}</span></b></td>
                                                
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