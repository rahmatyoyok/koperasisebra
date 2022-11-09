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
        var Initialized = [@if($listCoa) { id:{{$listCoa->coa_id }}, text:'{{ $listCoa->code.' - '.$listCoa->desc }}' } @endif]
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
                                    <div class="form-group form-md-line-input">
                                        <label class="control-label col-md-3">COA</label>
                                        <div class="col-md-8">
                                            <select name="coa_id" id="coa_id" class="form-control"></select>
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
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                @if($listCoa)
                                <h4 id="labelCoa">{{ $listCoa->code.' - '.$listCoa->desc }}</h4>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th> # </th>
                                                <th> Tanggal </th>
                                                <th> No Posting </th>
                                                <th> Keterangan </th>
                                                <th> No Ref </th>
                                                <th> Debit </th>
                                                <th> Kredit </th>
                                                <th> Saldo </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if($listBukuBesar)
                                            @php $i = 1; $saldo = 0; @endphp
                                            @foreach($listBukuBesar as $rws)
                                            @php 
                                                if($listCoa->khaidah_type == 1)
                                                    $saldo = ($saldo + $rws->saldo) + $rws->debit - $rws->kredit;
                                                elseif($listCoa->khaidah_type == 2)
                                                    $saldo = ($saldo + $rws->saldo) + $rws->kredit - $rws->debit;
                                            @endphp
                                            <tr>
                                                <td> {{ $i }} </td>
                                                <th style="text-align:center"> {{ ((strlen($rws->tr_date) > 0) ? date('d ', strtotime($rws->tr_date)). getMonths()[(int)date('m', strtotime($rws->tr_date))]  .date(' Y', strtotime($rws->tr_date))  :"") }} </th>
                                                <th style="text-align:center"> {{ $rws->posting_no }} </th>
                                                <th> {{$rws->desc}} </th>
                                                <th> {{$rws->reff_no}} </th>
                                                <th style="text-align:right"> {{ formatRpComma($rws->debit) }} </th>
                                                <th style="text-align:right"> {{ formatRpComma($rws->kredit) }} </th>
                                                <th style="text-align:right"> {{ formatRpComma($saldo) }} </th>
                                            </tr> 
                                            @php $i++; @endphp
                                            @endforeach

                                            <tr>
                                                <td>  </td>
                                                <th style="text-align:center" colspan="6"> Saldo Akhir </th>
                                                <th style="text-align:right"> {{ formatRpComma($saldo) }} </th>
                                            </tr> 

                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                @endif
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