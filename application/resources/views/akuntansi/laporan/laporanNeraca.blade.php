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

                            {{--  Aktiva   --}}
                            <div class="col-md-6 col-sm-6">
                                
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        
                                        <tbody>
                                            <tr>
                                                <td colspan="6" style="text-align:center;"><b>AKTIVA</b></td>
                                            </tr>
                                            {{--  ASET LANCAR  --}}
                                            
                                            {{--  KAS SETARA KAS  --}}
                                            @php $totalAsetLancar = array_sum(array_map(function($item) { return $item->ending_balance; }, $listSetaraKas)); @endphp
                                            <tr>
                                                <td colspan="4"><b>KAS DAN SETARA KAS</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalAsetLancar >= 0) ? number_format($totalAsetLancar, 2,'.',','):"(".number_format($totalAsetLancar*-1, 2,'.',',').")"  }}</span></td>
                                            </tr>
                                            @foreach($listSetaraKas as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

                                            {{--  PIUTANG USAHA  --}}
                                            @php $totalPiutangUsaha = array_sum(array_map(function($item) { return $item->ending_balance; }, $listPiutangUsaha)); @endphp
                                            <tr>
                                                <td colspan="4"><b>PIUTANG USAHA</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalPiutangUsaha >= 0) ? number_format($totalPiutangUsaha, 2,'.',','):"(".number_format($totalPiutangUsaha*-1, 2,'.',',').")"  }}</span></td>
                                            </tr>
                                            @foreach($listPiutangUsaha as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

                                            
                                            {{--  PIUTANG Deviden  --}}
                                            @php $totalPiutangDeviden = array_sum(array_map(function($item) { return $item->ending_balance; }, $listPiutangDeviden)); @endphp
                                            <tr>
                                                <td colspan="4"><b>PIUTANG DEVIDEN</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalPiutangDeviden >= 0) ? number_format($totalPiutangDeviden, 2,'.',','):"(".number_format($totalPiutangDeviden*-1, 2,'.',',').")"  }}</span></td>
                                            </tr>
                                            @foreach($listPiutangDeviden as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                            
                                            {{--  Persediaan Barang  --}}                                            
                                            @php $totalPersediaanBarang = array_sum(array_map(function($item) { return $item->ending_balance; }, $listPersediaanBarang)); @endphp
                                            <tr>
                                                <td colspan="4"><b>PERSEDIAAN BARANG</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalPersediaanBarang >= 0) ? number_format($totalPersediaanBarang, 2,'.',','):"(".number_format($totalPersediaanBarang*-1, 2,'.',',').")"  }}</span></td>
                                            </tr>
                                            @foreach($listPersediaanBarang as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

                                            {{--  Biaya Dibayar Dimuka  --}}
                                            @php $totalBiayaDibayaDimuka = array_sum(array_map(function($item) { return $item->ending_balance; }, $listBiayaDibayaDimuka)); @endphp
                                            <tr>
                                                <td colspan="4"><b>BIAYA DIBAYAR DIMUKA</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalBiayaDibayaDimuka >= 0) ? number_format($totalBiayaDibayaDimuka, 2,'.',','):"(".number_format($totalBiayaDibayaDimuka*-1, 2,'.',',').")"  }}</span></td>
                                            </tr>
                                            @foreach($listBiayaDibayaDimuka as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

                                            {{--  Aset Tetap  --}}
                                                @php 
                                                    $totalNilaiPerolehanAset = array_sum(array_map(function($item) { return $item->ending_balance; }, $listNilaiPerolehanAset)); 
                                                    $totalAkmPenyusutanAset = array_sum(array_map(function($item) { return $item->ending_balance; }, $listAkmPenyusutanAset)); 
                                                @endphp
                                           
                                            @php 
                                                $nBangunan = $nKendaraan = $nMesin = $nKomputer = 0;
                                                $nAstBangunan = $nAstKendaraan = $nAstMesin = $nAstKomputer = 0;
                                            @endphp
                                            @foreach($listNilaiPerolehanAset as $rows)
                                                @foreach($listAkmPenyusutanAset as $rowAkm)
                                                    @php                                                    
                                                        $nAstBangunan = ($rows->code == '1100002/UM') ? (($rowAkm->code == '1100102/UM') ? $rows->ending_balance - $rowAkm->ending_balance : $nAstBangunan) : $nAstBangunan;
                                                        $nAstKendaraan = ($rows->code == '1100003/UM') ? (($rowAkm->code == '1100103/UM') ? $rows->ending_balance - $rowAkm->ending_balance : $nAstKendaraan)  : $nAstKendaraan;
                                                        $nAstMesin = ($rows->code == '1100004/UM') ? (($rowAkm->code == '1100104/UM') ? $rows->ending_balance - $rowAkm->ending_balance : $nAstMesin)  : $nAstMesin;
                                                        $nAstKomputer = ($rows->code == '1100005/UM') ? (($rowAkm->code == '1100105/UM') ? $rows->ending_balance - $rowAkm->ending_balance : $nAstKomputer)  : $nAstKomputer;
                                                    @endphp
                                                @endforeach
                                            @endforeach
                                            @php $totalNilaiBukuAsetTetap = $totalNilaiPerolehanAset - ($nAstBangunan + $nAstKendaraan + $nAstMesin + $nAstKomputer); @endphp
                                            <tr>
                                                <td colspan="4"><b>ASET TETAP</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalNilaiBukuAsetTetap >= 0) ? number_format($totalNilaiBukuAsetTetap, 2,'.',','):"(".number_format($totalNilaiBukuAsetTetap*-1, 2,'.',',').")"  }}</span></td>                                            </tr>
                                            </tr>
                                                <tr>
                                                    <td></td>
                                                    <td colspan="2"><b>NILAI PEROLEHAN</b></td>
                                                    <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalNilaiPerolehanAset >= 0) ? number_format($totalNilaiPerolehanAset, 2,'.',','):"(".number_format($totalNilaiPerolehanAset*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                </tr>
                                                @foreach($listNilaiPerolehanAset as $rows)
                                                    <tr>
                                                        <td></td>
                                                        <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                        <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach

                                                <tr>
                                                    <td></td>
                                                    <td colspan="2"><b>AKUMULASI PENYUSUTAN</b></td>
                                                    <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalAkmPenyusutanAset >= 0) ? number_format($totalAkmPenyusutanAset, 2,'.',','):"(".number_format($totalAkmPenyusutanAset*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                </tr>
                                               
                                                @foreach($listAkmPenyusutanAset as $rows)
                                                    <tr>
                                                        <td></td>
                                                        <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                        <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td></td>
                                                    <td colspan="2"><b>NILAI BUKU ASET TETAP</b></td>
                                                    <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalNilaiBukuAsetTetap >= 0) ? number_format($totalNilaiBukuAsetTetap, 2,'.',','):"(".number_format($totalNilaiBukuAsetTetap*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                </tr>
                                                @foreach($listNilaiPerolehanAset as $rows)
                                                    @php  
                                                        $total = $rows->ending_balance;                                                  
                                                        $total = ($rows->code == '1100002/UM') ? $nAstBangunan : $total;
                                                        $total = ($rows->code == '1100003/UM') ? $nAstKendaraan : $total;
                                                        $total = ($rows->code == '1100004/UM') ? $nAstMesin : $total;
                                                        $total = ($rows->code == '1100005/UM') ? $nAstKomputer : $total;
                                                    @endphp
                                                    <tr>
                                                        <td></td>
                                                        <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $rows->desc }}</td>
                                                        <td>Rp. <span style="float:right">{{ ($total >= 0) ? number_format($total, 2,'.',','):"(".number_format($total*-1, 2,'.',',').")"  }}</span></td>
                                                        <td></td>
                                                        <td></td>
                                                    </tr>
                                                @endforeach


                                            {{--  Piutang Lain-Lain  --}}
                                            
                                            @php $totalPiutangLain = array_sum(array_map(function($item) { return $item->ending_balance; }, $listPiutangLain)); @endphp
                                            <tr>
                                                <td colspan="4"><b>PIUTANG LAIN-LAIN</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalPiutangLain >= 0) ? number_format($totalPiutangLain, 2,'.',','):"(".number_format($totalPiutangLain*-1, 2,'.',',').")"  }}</span></td>
                                            </tr>
                                            @foreach($listPiutangLain as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

                                            {{--  Investasi jangka Panjang  --}}
                                            @php $totalInvJangkaPanjang = array_sum(array_map(function($item) { return $item->ending_balance; }, $listInvJangkaPanjang)); @endphp
                                            <tr>
                                                <td colspan="4"><b>INVESTASI JANGKA PANJANG</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalInvJangkaPanjang >= 0) ? number_format($totalInvJangkaPanjang, 2,'.',','):"(".number_format($totalInvJangkaPanjang*-1, 2,'.',',').")"  }}</span></td>
                                            </tr>
                                            @foreach($listInvJangkaPanjang as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach


                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{--  Pasiva   --}}
                            <div class="col-md-6 col-sm-6">
                                
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        
                                        <tbody>
                                            <tr>
                                                <td colspan="6" style="text-align:center;"><b>PASIVA</b></td>
                                            </tr>                                            
                                            {{--  Kewajiban Jangka Pendek  --}}
                                            @php 
                                                $totalDanaDana = array_sum(array_map(function($item) { return $item->ending_balance; }, $listDanaDana)); 
                                                $totalKewajibanLancar = array_sum(array_map(function($item) { return $item->ending_balance; }, $listKewajibanLancar)); 
                                                $totalTabunganAnggota = array_sum(array_map(function($item) { return $item->ending_balance; }, $listTabunganAnggota)); 
                                                $totalHutangPajak = array_sum(array_map(function($item) { return $item->ending_balance; }, $listHutangPajak)); 
                                                $totalEkuitas = array_sum(array_map(function($item) { return $item->ending_balance; }, $listEkuitas)); 

                                                $totalKewajibanJangkaPendek = $totalDanaDana + $totalKewajibanLancar + $totalTabunganAnggota + $totalHutangPajak;
                                            @endphp
                                            <tr>
                                                <td colspan="4"><b>KEWAJIBAN JANGKA PANJANG</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalKewajibanJangkaPendek >= 0) ? number_format($totalKewajibanJangkaPendek, 2,'.',','):"(".number_format($totalKewajibanJangkaPendek*-1, 2,'.',',').")"  }}</span></td>                                            </tr>
                                            </tr>
                                            <tr>
                                                <td></td>
                                                <td colspan="2"><b>DANA DANA</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalDanaDana >= 0) ? number_format($totalDanaDana, 2,'.',','):"(".number_format($totalDanaDana*-1, 2,'.',',').")"  }}</span></td>
                                                <td></td>
                                            </tr>
                                            @foreach($listDanaDana as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td></td>
                                                <td colspan="2"><b>KEWAJIBAN LANCAR</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalKewajibanLancar >= 0) ? number_format($totalKewajibanLancar, 2,'.',','):"(".number_format($totalKewajibanLancar*-1, 2,'.',',').")"  }}</span></td>
                                                <td></td>
                                            </tr>
                                            @foreach($listKewajibanLancar as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td></td>
                                                <td colspan="2"><b>TABUNGAN ANGGOTA</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalTabunganAnggota >= 0) ? number_format($totalTabunganAnggota, 2,'.',','):"(".number_format($totalTabunganAnggota*-1, 2,'.',',').")"  }}</span></td>
                                                <td></td>
                                            </tr>
                                            @foreach($listTabunganAnggota as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

                                            <tr>
                                                <td></td>
                                                <td colspan="2"><b>HUTANG PAJAK</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalHutangPajak >= 0) ? number_format($totalHutangPajak, 2,'.',','):"(".number_format($totalHutangPajak*-1, 2,'.',',').")"  }}</span></td>
                                                <td></td>
                                            </tr>
                                            @foreach($listHutangPajak as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach                                            

                                            <tr>
                                                <td colspan="4"><b>EKUITAS</b></td>
                                                <td>Rp. <span style="float:right;font-weight:bold;">{{ ($totalEkuitas >= 0) ? number_format($totalEkuitas, 2,'.',','):"(".number_format($totalEkuitas*-1, 2,'.',',').")"  }}</span></td>                                            </tr>
                                            </tr>
                                            @foreach($listEkuitas as $rows)
                                                <tr>
                                                    <td></td>
                                                    <td>{{ $rows->code." - ".$rows->desc }}</td>
                                                    <td>Rp. <span style="float:right">{{ ($rows->ending_balance >= 0) ? number_format($rows->ending_balance, 2,'.',','):"(".number_format($rows->ending_balance*-1, 2,'.',',').")"  }}</span></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            @endforeach

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