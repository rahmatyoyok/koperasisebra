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
            <h1> Rincian Kartu Piutang </h1>
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
                        <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn btn-primary">Kembali</a>
                        <br>
                        <br>
                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="15%">No Anggota</th>
                                    <th width="15%">Nama Anggota</th>
                                    <th width="15%">Tanggal Transaksi</th>
                                    <th width="15%">Nominal</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                <tr>
                                    <td width="5%" align="center">{{ $loop->iteration }}</td>
                                    <td width="15%">{{ $item->niak }}</td>
                                    <td width="40%">{{ $item->first_name }}</td>
                                    <td width="15%" align="center">{{ tglIndo($item->sale_time) }}</td>
                                    <td width="15%" align="right">{{ toRp($item->payment_amount) }}</td>
                                    
                                </tr>
                                @endforeach
                                
                               
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT BODY -->



@endsection


@push('plugins')

@endpush
