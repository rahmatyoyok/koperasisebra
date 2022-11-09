@php
    use Illuminate\Support\Facades\Crypt;
@endphp

@extends('layouts.master-sp')
@section('title', $title)

@push('styles')
    <link href="{{assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{assets('global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{assets('global/plugins/bootstrap-modal/css/bootstrap-modal.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .form-horizontal .form-group.form-md-line-input {
            padding-top: 10px;
            margin: 0 -10px 5px!important;
        }
    </style>
@endpush


@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
    </script>
    <script src="{{ assets('pages/scripts/SimpanPinjam/pinjaman.show.js') }}" type="text/javascript"></script>
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush

@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>{{ $data->first_name.' '.$data->last_name }}</h1>
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
                            <span class="caption-subject bold uppercase">Informasi Pinjaman Anggota</span>
                        </div>
                        <div class="actions">
                            
                        </div>
                    </div>
                    <div class="portlet-body form">
                            {!! Form::open(['class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <label class="control-label col-md-4 bold">NIAK</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : {{ $data->niak }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">Nama</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : {{ $data->first_name }}  {{ $data->last_name }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">Nomor Induk</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : {{ $data->customer->nomor_induk }}
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">Unit Kerja</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : {{ $data->customer->company_name }}
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">Jabatan</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : {{ $data->customer->jabatan }}
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">Tanggal Lahir</label>
                                            <div class="col-md-5">
                                                <p class="form-control-static"> : {{ $data->born_place .', '.date('d F Y', strtotime($data->born_date)) }} </p>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">No. Identitas</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> :  {{ $data->id_card_number }} </p>
                                            </div>
                                        </div> 
    
    
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-4 control-label bold">Alamat</label>
                                            <div class="col-md-8">
                                                    <p class="form-control-static"> : {{ $data->address_1 }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">Kota</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : <i class="fa fa-map"></i> {{ $data->city.', '.$data->state.', '.$data->country }} </p>
                                            </div>
                                        </div>

                                    </div>

                                    
                                    <div class="col-md-6">
                                    @if(isset($data->customer->bank_id))
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">No. Rekening</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : 
                                                        <?php 
                                                            if(isset($data->customer->bank_id)){
                                                            switch((int)$data->customer->bank_id){
                                                                case (1): echo '<span>BNI 1946</span>'; break;
                                                                case (2): echo '<span>BRI</span>'; break;
                                                                case (3): echo '<span>BCA</span>'; break;
                                                                case (4): echo '<span>MANDIRI</span>'; break;
                                                            }

                                                            echo ' - '.$data->customer->account_number;
                                                        } ?> </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="control-label col-md-4 bold">Unit Kerja</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : @if(isset($data->customer->company_name)) {{$data->customer->company_name }} @endif</p>
                                            </div>
                                        </div>

                                    @endif

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="control-label col-md-4 bold">Saldo Total Pinjaman</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : Rp. <span id="TotalPinjaman"> {{ formatNoRpComma($data->saldo_pinjaman,0) }} </span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr>
                                        <h4>Dafatar Pinjaman</h4>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-scrollable">
                                            <table class="table table-hover table-light" id="detailSetorPinjaman">
                                                <thead>
                                                    <tr>
                                                        <th> # </th>
                                                        <th> No Reff </th>
                                                        <th> Tanggal </th>
                                                        <th> Total Pinjaman </th>
                                                        <th> Tenor </th>
                                                        <th> Sisa Pinjaman </th>
                                                        <th> lampiran Reff </th>
                                                        <th> Status </th>
                                                        <th> Aksi </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $i=1; @endphp
                                                    @foreach($data_pinjaman as $value)
                                                        <tr data-loanid="{{ Crypt::encrypt($value->loan_id) }}">
                                                            <td> {{ $i }} </td>
                                                            <td> {{ $value->ref_code }} </td>
                                                            <td> Pengajuan : {{ tglIndo($value->loan_date) }} </td>
                                                            <td class="text-right subtotal"><span style="float:left">Rp.</span> {{ formatNoRpComma($value->loan_total,0)}}  </td>
                                                            <td class="text-right tenor">{{ $value->tenure }} Bulan</td>
                                                            <td class="text-right sisa"><span style="float:left">Rp.</span> {{ formatNoRpComma($value->saldo_pokok_pinjaman,0)}}  </td>
                                                            <td>@if(strlen($value->lampiran_pengajuan) > 0) <a href="{{ URL::to("application/public/simpanpinjam/pinjaman/pengajuan/".$value->lampiran_pengajuan) }}" target="_blank">lampiran</a> @endif</td>
                                                            <td class="text-center">
                                                                @php
                                                                    if($value->status <> 1){
                                                                        if(strlen($value->approval_status) <= 0){
                                                                            echo '<span class="font-red-haze sbold"> Menunggu Persetujuan </span>';
                                                                        }
                                                                        elseif($value->approval_status == 0){
                                                                            echo '<a class="btn btn-xs btn-danger"> Tidak Disetujui oleh '.$value->user_approval.' - '.$value->level.' </a>';    
                                                                        }elseif($value->approval_status == 1){
                                                                            echo '<a class="btn btn-xs btn-success"> Disetujui Oleh '.$value->user_approval.' - '.$value->level.' </a>';
                                                                        }

                                                                        echo '<br><span style="float:left">Keterangan : '.$value->desc.'</span>';
                                                                    }else{
                                                                        echo '<a class="btn btn-xs btn-success"> Diterima </a>';
                                                                    }
                                                                @endphp
                                                            </td>
                                                            <td> 
                                                                <a class="detailBtn btn btn-xs purple-sharp" data-id="{{ Crypt::encrypt($value->loan_id) }}'"> <i class="fa fa-search-plus"></i> Detail </a>
                                                                @php
                                                                    if($value->status <> 1){
                                                                        switch ($value->lastlevel_app) {
                                                                            case 9:
                                                                                if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/pinjaman/approvalpengajuansatu', auth()->user()->level_id) && ($value->approval_status <> 0)){
                                                                                    echo '<a class="approveBtn btn btn-xs blue-sharp" data-id="'.$value->loan_id.'" data-hreffaction="simpanpinjam/pinjaman/approvalpengajuansatu"> <i class="fa fa-check-circle"></i> Setujui </a>';
                                                                                }    
                                                                                break;
                                                                            case 7:
                                                                                if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/pinjaman/prosesserah', auth()->user()->level_id) && ($value->approval_status <> 0)){
                                                                                    echo Form::button('<i class="fa fa-money"></i> Proses Penyerahan', ['class' => 'btn btn-xs blue moveLink', 'data-swa-text' => 'Memproses Penyerahan Pinjaman', 'data-spwjbid'=>Crypt::encrypt($value->loan_id)]);
                                                                                }
                                                                                break;
                                                                            default :
                                                                                if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/pinjaman/approvalpengajuandua', auth()->user()->level_id)){
                                                                                    echo '<a class="approveBtn btn btn-xs blue-sharp" data-id="'.$value->loan_id.'" data-hreffaction="simpanpinjam/pinjaman/approvalpengajuandua"> <i class="fa fa-check-circle"></i> Setujui </a>';
                                                                                }
                                                                        }
                                                                    }
                                                                @endphp
                                                            </td>
                                                        </tr>
                                                        @php $i++; @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

    
</div>

<div id="actionDetailPinjaman" class="modal fade container" style="top:10%!important">
    <div class="modal-header">
        Detail Pinjaman
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    </div>
    <div class="modal-body">
        <div class="invoice">
                <div class="row invoice-logo">
                    <div class="col-xs-6 invoice-logo-space">
                        
                    </div>
                    <div class="col-xs-6">
                        <p> <h3><b>#<span id="popup_noref"></span> / <span id="popup_tgl_pengajuan"></span></b></h3>
                            <span class="muted" id="popup_niak"></span> -
                            <span class="muted" id="popup_nama"></span><br>
                            <span class="muted" id="popup_companyname"></span>
                        </p>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-xs-4">
                        <h3>Data Pinjaman:</h3>
                        <ul class="list-unstyled">
                            <li> Nilai Pinjaman : <br> <span style="margin-left:10px;font-weight:bold;" id="popup_loan"></span> </li>
                            <li> Bunga Piutang : <br><span style="margin-left:10px;font-weight:bold;" id="popup_ttl_bunga"></span> </li>
                            <li> Tenor  : <span style="margin-left:10px;font-weight:bold;" id="popup_tenor"></span> </li>
                            <li> Jenis Bunga Pinjaman <br> <span style="margin-left:10px;font-weight:bold;" id="popup_jenis_bunga"></span> </li>
                        </ul>
                    </div>
                    <div class="col-xs-4">
                        <h3>Biaya-Biaya:</h3>
                        <ul class="list-unstyled">
                            <li> Administrasi : <span style="margin-left:10px;font-weight:bold;" id="popup_administrasi"></span> </li>
                            <li> Provisi : <span style="margin-left:10px;font-weight:bold;" id="popup_provisi"></span> </li>
                            <li> Resiko Daperma : <span style="margin-left:10px;font-weight:bold;" id="popup_daperma"></span> </li>
                            <li> Materai : <span style="margin-left:10px;font-weight:bold;" id="popup_materai"></span> </li>
                            <li> Lain-lain : <span style="margin-left:10px;font-weight:bold;" id="popup_lain"></span> </li>
                        </ul>
                    </div>
                    <div class="col-xs-4 invoice-payment">
                        <h3>Angsuran:</h3>
                        <ul class="list-unstyled">
                            <li> Angsuran Pokok : <br><span style="margin-left:10px;font-weight:bold;" id="popup_angsuran_pokok"></span> </li>
                            <li> Angsuran Bunga : <br><span style="margin-left:10px;font-weight:bold;" id="popup_angsuran_bunga"></span> </li>
                            <li> <storng>Total</strong> : <br><span style="margin-left:10px;font-weight:bold;" id="popup_ttl_angsuran"></span> </li>
                        </ul>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <h3>Riwayat Angsuran: <button id="bayarAngsuran" class="btn btn-xs btn-success">Bayar Angsuran Sekarang</button> </h3>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th> Angsuran Ke </th>
                                    <th> Tanggal </th>
                                    <th class="hidden-xs"> Angsuran Pokok </th>
                                    <th class="hidden-xs"> Angsuran Bunga </th>
                                    <th> Total </th>
                                    <th class="hidden-xs"> Keterangan </th>
                                </tr>
                            </thead>
                            <tbody id="tableDetailAngsuran">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>


<div id="actionApproveal" class="modal fade" tabindex="-1">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <h4>Apakah Anda Yakin Menyetujui</h4>
                <p id="descPinjaman"></p>
                <p>Ketrangan : 
                    <br>
                    <textarea name="detailApprovalDesc" rows="4" cols="50" class="form-control"></textarea>
                </p>

            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" id="approvePinjaman" class="btn green"> Setuju </button>
        <button type="button" id="rejectPinjaman" class="btn red-haze"> Tidak Setuju</button>
    </div>
</div>
    

<!-- END PAGE CONTENT BODY -->
@endsection