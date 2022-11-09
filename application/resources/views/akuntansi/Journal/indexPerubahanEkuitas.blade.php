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
            <h1> {{$title}} </h1>
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
                            <h4 style="text-align:center;">{{$title}}</h4>
                                <h4 style="text-align:center;">Periode <b>{{ $currentMonth }}</b>
                            </h4>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="portlet">
                                        <div class="portlet-body flip-scroll">
                                            <table class="table table-bordered table-striped table-condensed flip-content">
                                                <thead class="flip-content">
                                                    <tr>
                                                        <th width="20%"> Keterangan </th>
                                                        <th class="numeric"> Modal Donasi </th>
                                                        <th class="numeric"> Simpanan Pokok </th>
                                                        <th class="numeric"> Simpanan Wajib </th>
                                                        <th class="numeric"> Modal Tetap Tambahan USP </th>
                                                        <th class="numeric"> Modal Toko </th>
                                                        <th class="numeric"> Dana Cadangan </th>
                                                        <th class="numeric"> Jumlah </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td> Saldo 01 Desember 2019 </td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td> SHU Bersih </td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td> Rp. <span style="float:right">-</span></td>
                                                        <td> Rp. <span style="float:right">-</span></td>
                                                    </tr>
                                                    <tr style="font-weight:bold;">
                                                        <td> Saldo 31 Desember 2019 </td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="8">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td> Saldo 01 Desember 2019 </td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td> Mutasi </td>
                                                        <td>&nbsp;</td>
                                                        <td> Rp. <span style="float:right">-</span></td>
                                                        <td> Rp. <span style="float:right">-</span></td>
                                                        <td>&nbsp;</td>
                                                        <td> Rp. <span style="float:right">-</span></td>
                                                        <td>&nbsp;</td>
                                                        <td> Rp. <span style="float:right">-</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td> SHU Bersih </td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td> Rp. <span style="float:right">-</span></td>
                                                        <td> Rp. <span style="float:right">-</span></td>
                                                    </tr>
                                                    <tr>
                                                        <td> Pembagian SHU </td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td>&nbsp;</td>
                                                        <td> Rp. <span style="float:right">-</span></td>
                                                        <td> Rp. <span style="float:right">-</span></td>
                                                    </tr>
                                                    <tr style="font-weight:bold;">
                                                        <td> Saldo 31 Desember 2019 </td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                        <td> Rp. <span style="float:right">20.000.000</span></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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