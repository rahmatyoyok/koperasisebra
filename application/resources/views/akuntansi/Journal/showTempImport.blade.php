@extends('layouts.master-ak')

@section('title', $title)

@push('styles')
    <link href="{{assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />

    <style>
        .form-horizontal .form-group.form-md-line-input {
            padding-top: 10px;
            margin: 0 -10px 5px!important;
        }

        .form-horizontal .form-group .form-control-static {
            margin-top: -1px!Important;
        }
        tr.notbalanced{

        }
    </style>

@endpush

@push('plugins')
    <script src="{{ assets('global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
    </script>
    <!-- BEGIN PAGE LEVEL -->
    <script src="{{ assets('pages/scripts/akuntansi/importJurnal.index.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL  -->
@endpush

@section('content')

<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Informasi Jurnal</h1>
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
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase">Check Import Jurnal </span>
                            
                        </div>
                        <div class="actions">
                            @php
                                $invalid = false;
                                if(isset($dataShow->importJurnal)){
                                    foreach($dataShow->importJurnal as $rinv){
                                        if(!$invalid){
                                            $invalid = ($rinv->TotalDebit <> $rinv->TotalKredit) ? true : false;
                                        }
                                    }
                                }
                            @endphp
                            @if(!$invalid)
                                <a class="btn btn-sm green dropdown-toggle" id="saveImportData" href="javascript:;" ><i class="fa fa-upload"></i> Simpan Import Jurnal</a>
                            @endif
                        </div>
                    </div>
                    <div class="portlet-body form">
                        <table class="table table-striped table-bordered dt-responsive" width="100%" id="table_import">
                            <thead>
                                <tr>
                                    <th class="all"> Kode Unik </th>
                                    <th class="all"> Jenis Jurnal </th>
                                    <th class="all"> Divisi </th>
                                    <th class="all"> Jenis Transaksi </th>
                                    <th class="all"> No. Referensi </th>
                                    <th class="all"> Tanggal </th>
                                    <th class="all"> Keterangan </th>
                                    <th class="all"> Total Debit </th>
                                    <th class="all"> Total Kredit </th>
                                    <th class=""> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($dataShow->importJurnal))
                                @php $xn = 0; @endphp
                                    @foreach($dataShow->importJurnal as $rows)
                                        <tr class="@if($rows->TotalDebit <> $rows->TotalKredit) bg-red bg-font-red @endif">
                                            <td class="all"> {{ $rows->UniqueCode }} </td>
                                            <td class="all"> {{ ($rows->JeniJurnal == 'JKM') ? "Kas Masuk":(($rows->JeniJurnal == 'JKK') ? "Kas Keluar":(($rows->JeniJurnal == 'JRR') ? "Manual":"")) }} </td>
                                            <td class="all"> {{ ($rows->DivisiCode == 'SP') ? "Simpan Pinjam":(($rows->DivisiCode == 'TK') ? "Toko":(($rows->DivisiCode == 'UM') ? "Usaha Umum":"")) }} </td>
                                            <td class="all"> {{ ($rows->JenisTransaksi == 'K') ? "KAS":(($rows->JenisTransaksi == 'K') ? "Kas Kecil":(($rows->JenisTransaksi == 'J') ? "Manual":"")) }} </td>
                                            <td class="all"> {{ $rows->NoRefrensi }} </td>
                                            <td class="all"> {{ tglIndo($rows->TglTransaksi) }} </td>
                                            <td class="all"> {{ $rows->HeaderDesc }} </td>
                                            <td class="all text-right"> {{ toRp($rows->TotalDebit) }} </td>
                                            <td class="all text-right"> {{ toRp($rows->TotalKredit) }} </td>
                                            <td class="">
                                            <a class="btn btn-xs blue openModals" data-uniqCode="{{ $rows->UniqueCode }}" data-indexarray="{{ $xn }}" ><i class="fa fa-search-plus"></i> Detail</a>
                                            </td>
                                        </tr>
                                        @php $xn++; @endphp
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

<div class="modal fade" id="full" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Detail Jurnal <span id="titlesModal" class="bold">A1</span></h4>
            </div>
            <div class="modal-body">
                <form action="#" class="form-horizontal" id="formEntryJurnal">
                <div class="form-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="division" class="control-label col-md-4 bold">Divisi :</label>
                                <div class="col-md-3">
                                    <p class="form-control-static" id="labelDivisi">
                                    </p>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="tr_type_id" class="control-label col-md-4 bold">Jenis Transaksi :</label>
                                <div class="col-md-3">
                                    <p class="form-control-static" id="labelJenisTransaksi"></p>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="reff_no" class="control-label col-md-4 bold">Nomor Refrensi :</label>
                                <div class="col-md-3">
                                    <p class="form-control-static" id="labelNoREfrensi"></p>
                                </div>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="tr_date" class="control-label col-md-4 bold">Tanggal Transaksi :</label>
                                <div class="col-md-3">
                                    <p class="form-control-static" id="labeltanggal"></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group form-md-line-input form-md-floating-label">
                                    <label for="tr_date" class="control-label col-md-4 bold">Keterangan :</label>
                                <div class="col-md-3">
                                    <p class="form-control-static" id="labelKeterangan"></p>
                                </div>
                            </div>
                        </div>

                    </div>   
                    <div class="row">
                        <div class="col-lg-12">
                            <h3 class="form-section">Detail Transaksi</h3>

                            <table class="table table-striped table-bordered table-hover dt-responsive" id="table1" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="15%">Buku Besar</th>
                                        <th width="40%">Keterangan</th>
                                        <th width="15%">Debit</th>
                                        <th width="15%">Kredit</th>
                                    </tr>
                                </thead>
                                <tbody id="listTableModal">
                                    
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">Total</td>
                                        <td class="bold text-right" id="lebelTotalDebit"></td>
                                        <td class="bold text-right" id="lebelTotalKredit"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                </div>
                </form>

            </div>
            
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- END PAGE CONTENT BODY -->
@endsection