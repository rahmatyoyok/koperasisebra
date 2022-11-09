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
                                <h4 style="text-align:center;">Laporan Laba Rugi</h4>
                                <h4 style="text-align:center;">Periode <b>{{ $currentMonth }}</b></h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th colspan="2"> Keterangan </th>
                                                <th colspan="1"> Jumlah </th>
                                            </tr>
                                        </thead>
                                        <tbody>                     
                                            {{-- Pendapatan--}}                                                                
                                            <tr>
                                                <td colspan="3"><b>PENDAPATAN USAHA</b></td>
                                                <td></td>
                                            </tr>
                                            @php $lastCoaPendapatan = ""; @endphp
                                            @foreach($listPendapatan as $rws)
                                            @if($lastCoaPendapatan <> $rws->divisi)
                                                <tr>
                                                    <td width="5%" ></td>
                                                    <td colspan="2"><b>{{ $rws->divisi }}</b></td>    
                                                    <td></td>                             
                                                </tr>
                                            @endif
                                                @php $sum = ((isset($sum)) ? $sum : 0)  + $rws->ending_balance; @endphp
                                                <tr>
                                                    <td></td>
                                                    <td width="5%"></td>
                                                    <td>{{ $rws->code." - ".$rws->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ number_format($rws->ending_balance, 2,'.',',') }}</span></td>
                                                    
                                                </tr>
                                                @php $lastCoaPendapatan = $rws->divisi; @endphp
                                            @endforeach

                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                            </tr>

                                            {{-- Pendapatan Diluar Usaha --}}
                                            <tr>
                                                <td colspan="3"><b>PENDAPATAN DILUAR USAHA</b></td>
                                                <td></td>
                                            </tr>
                                            @php $lastCoaPendapatanLU = ""; @endphp
                                            @foreach($listPendapatanLuarUsaha as $rws)
                                            @if($lastCoaPendapatanLU <> $rws->divisi)
                                                <tr>
                                                    <td width="5%" ></td>
                                                    <td colspan="2"><b>{{ $rws->divisi }}</b></td>    
                                                    <td></td>                             
                                                </tr>
                                            @endif
                                                @php $sum = ((isset($sum)) ? $sum : 0)  + $rws->ending_balance; @endphp
                                                <tr>
                                                    <td></td>
                                                    <td width="5%"></td>
                                                    <td>{{ $rws->code." - ".$rws->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ number_format($rws->ending_balance, 2,'.',',') }}</span></td>
                                                    
                                                </tr>
                                                @php $lastCoaPendapatanLU = $rws->divisi; @endphp
                                            @endforeach

                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                            </tr>

                                            
                                            {{-- Beban Usaha --}}
                                            <tr>
                                                <td colspan="3"><b>BEBAN USAHA</b></td>
                                                <td></td>
                                            </tr>
                                            @php $lastBebanLangsung = ""; @endphp
                                            @foreach($listBebanLangsung as $rws)
                                            @if($lastBebanLangsung <> $rws->divisi)
                                                <tr>
                                                    <td width="5%" ></td>
                                                    <td colspan="2"><b>{{ $rws->divisi }}</b></td>    
                                                    <td></td>                             
                                                </tr>
                                            @endif
                                                @php $sum = ((isset($sum)) ? $sum : 0)  + $rws->ending_balance; @endphp
                                                <tr>
                                                    <td></td>
                                                    <td width="5%"></td>
                                                    <td>{{ $rws->code." - ".$rws->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ number_format($rws->ending_balance, 2,'.',',') }}</span></td>
                                                    
                                                </tr>
                                                @php $lastBebanLangsung = $rws->divisi; @endphp
                                            @endforeach

                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                            </tr>

                                            {{-- Beban Diluar Usaha --}}
                                            <tr>
                                                <td colspan="3"><b>BEBAN DILUAR USAHA</b></td>
                                                <td></td>
                                            </tr>
                                            @php $lastBebanLuarUsaha = ""; @endphp
                                            @foreach($listBebanLuarUsaha as $rws)
                                            @if($lastBebanLuarUsaha <> $rws->divisi)
                                                <tr>
                                                    <td width="5%" ></td>
                                                    <td colspan="2"><b>{{ $rws->divisi }}</b></td>    
                                                    <td></td>                             
                                                </tr>
                                            @endif
                                                @php $sum = ((isset($sum)) ? $sum : 0)  + $rws->ending_balance; @endphp
                                                <tr>
                                                    <td></td>
                                                    <td width="5%"></td>
                                                    <td>{{ $rws->code." - ".$rws->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ number_format($rws->ending_balance, 2,'.',',') }}</span></td>
                                                    
                                                </tr>
                                                @php $lastBebanLuarUsaha = $rws->divisi; @endphp
                                            @endforeach

                                            <tr>
                                                <td colspan="4">&nbsp;</td>
                                            </tr>

                                            {{-- Beban Pajak --}}
                                            <tr>
                                                <td colspan="3"><b>BEBAN PAJAK</b></td>
                                                <td></td>
                                            </tr>
                                            @php $lastBebanPajak = ""; @endphp
                                            @foreach($listBebanPajak as $rws)
                                            @if($lastBebanPajak <> $rws->divisi)
                                                <tr>
                                                    <td width="5%" ></td>
                                                    <td colspan="2"><b>{{ $rws->divisi }}</b></td>    
                                                    <td></td>                             
                                                </tr>
                                            @endif
                                                @php $sum = ((isset($sum)) ? $sum : 0)  + $rws->ending_balance; @endphp
                                                <tr>
                                                    <td></td>
                                                    <td width="5%"></td>
                                                    <td>{{ $rws->code." - ".$rws->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ number_format($rws->ending_balance, 2,'.',',') }}</span></td>
                                                    
                                                </tr>
                                                @php $lastBebanPajak = $rws->divisi; @endphp
                                            @endforeach

                                            <tr>
                                                <td colspan="4">&nbsp;</td>
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