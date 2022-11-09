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
    <script src="{{ assets('pages/scripts/SimpanPinjam/anggota.show.js') }}" type="text/javascript"></script>
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush


@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>{{ $data_edit->first_name.' '.$data_edit->last_name }}</h1>
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
                            <span class="caption-subject bold uppercase">Informasi Profile Anggota</span>
                        </div>
                        <div class="actions">
                            <a class="btn blue-sharp" href="{{ url('simpanpinjam/anggota/'.$encryptId.'/edit') }}">
                                <i class="glyphicon glyphicon-edit"></i>
                                Ubah Data Anggota
                            </a>
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
                                                <p class="form-control-static"> : {{ $data_edit->niak }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">Nama Depan</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : {{ $data_edit->first_name }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">Nama Belakang</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : {{ $data_edit->last_name }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">Tanggal Lahir</label>
                                            <div class="col-md-5">
                                                <p class="form-control-static"> : {{ $data_edit->born_place . ( (strlen($data_edit->born_date) == 10) ? ','.date_format(date_create($data_edit->born_date),'d-m-Y') : "") }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-radios">
                                            <label class="col-md-4 control-label bold">Jenis Kelamin</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : 
                                                    @if($data_edit->gender == 1)
                                                        Laki-Laki 
                                                    @else
                                                        Perempuan
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                        
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">No. Identitas</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> :  {{ $data_edit->id_card_number }} </p>
                                            </div>
                                        </div> 
    
    
                                        <div class="form-group form-md-line-input">
                                            <label class="col-md-4 control-label bold">Alamat</label>
                                            <div class="col-md-8">
                                                    <p class="form-control-static"> : {{ $data_edit->address_1 }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">Kota</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : <i class="fa fa-map"></i> {{ $data_edit->city.', '.$data_edit->state.', '.$data_edit->country }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">NPWP</span>
                                            </label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : {{ $data_edit->customer->npwp }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">Email</span>
                                            </label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : <i class="fa fa-envelope"></i> {{ $data_edit->email }} </p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">No. Telp</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : {{ $data_edit->phone_number }} </p>
                                            </div>
                                        </div>

                                    </div>

                                    @if(isset($data_edit->customer->bank_id))
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="col-md-4 control-label bold">No. Rekening</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : 
                                                        {{ $data_edit->customer->bank->nama_bank}} - {{$data_edit->customer->account_number}}
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="control-label col-md-4 bold">Unit Kerja</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : @if(isset($data_edit->customer->company_name)) {{$data_edit->customer->company_name }} @endif</p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="control-label col-md-4 bold">Nomor Induk</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : @if(isset($data_edit->customer->nomor_induk)) {{$data_edit->customer->nomor_induk }} @endif</p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="control-label col-md-4 bold">Jabatan</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : @if(isset($data_edit->customer->jabatan)) {{$data_edit->customer->jabatan }} @endif</p>
                                            </div>
                                        </div>

                                        <div class="form-group form-md-line-input form-md-floating-label">
                                            <label class="control-label col-md-4 bold">Batas Pinjaman</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : Rp. @if(isset($data_edit->customer->company_name)) {{ number_format($data_edit->customer->credit_limit,0,",",".") }} @endif</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @endif
                                </div>
                                
                                
                            </div>
                            {!! Form::close() !!}
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-xs-12 col-sm-12">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-equalizer font-dark hide"></i>
                                        <span class="caption-subject font-dark bold uppercase">Daftar Simpanan Pokok</span>
                                        <span class="caption-helper"></span>
                                    </div>
                                    <div class="tools">
                                        <a href="" class="collapse" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: block;">
                                    <div class="row">
                                        
                                        
                                        <div class="col-md-12">
                                            <div class="table-scrollable">
                                                <table class="table table-hover table-light">
                                                    <thead>
                                                        <tr>
                                                            <th> # </th>
                                                            <th> No Reff </th>
                                                            <th> Tanggal </th>
                                                            <th> Total </th>
                                                            <th> Metode Pembayaran </th>
                                                            <th> Reff Pembayaran </th>
                                                            <th> Status </th>
                                                            <th> Aksi </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $i=1; @endphp
                                                        @foreach($data_simpok as $value)
                                                            <tr>
                                                                <td> {{ $i }} </td>
                                                                <td> {{ $value->ref_code }} </td>
                                                                <td> {{ tglIndo($value->tr_date) }} </td>
                                                                <td class="text-right"><span style="float:left">Rp.</span> {{ formatNoRpComma($value->total,0)}}  </td>
                                                                <td> @php echo sp_payment_method_name('name', $value->payment_method); @endphp </td>
                                                                <td class="text-center"> 
                                                                    @if(strlen($value->attachment) > 0)
                                                                    <a class="btn btn-xs green-jungle " target="_blank" href="{{ url('getDownload?type=simpanpinjam&loc=pokok/setor&name='.$value->ref_code.'&file='.$value->attachment) }}">
                                                                        Download
                                                                    </a>
                                                                    @endif
                                                                </td>
                                                                <td> <b> @php echo ($value->status == 1) ? "Diterima": "Pending"; @endphp</b> </td>
                                                                <td class="text-center"> 
                                                                    @if((int)$value->status == 0)
                                                                        {!! Form::button('<i class="fa fa-money"></i> Proses Penerimaan', ['class' => 'btn btn-xs blue moveLinkPokok', 'data-swa-text' => 'Memproses Penerimaan Simpanan Pokok', 'data-spwjbid'=>Crypt::encrypt($value->principal_saving_id)]) !!}
                                                                    @endif
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
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-xs-12 col-sm-12">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-equalizer font-dark hide"></i>
                                        <span class="caption-subject font-dark bold uppercase">Daftar Simpanan Wajib</span>
                                        <span class="caption-helper"></span>
                                    </div>
                                    <div class="tools">
                                        <a href="" class="collapse" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: block;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-scrollable">
                                                <table class="table table-hover table-light">
                                                    <thead>
                                                        <tr>
                                                            <th> # </th>
                                                            <th> No Reff </th>
                                                            <th> Periode </th>
                                                            <th> Tanggal </th>
                                                            <th> Total </th>
                                                            <th> Metode Pembayaran </th>
                                                            <th> Reff Pembayaran </th>
                                                            <th> Status </th>
                                                            <th> Aksi </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $i=1; @endphp
                                                        @foreach($data_wajib as $value)
                                                            <tr>
                                                                <td> {{ $i }} </td>
                                                                <td> {{ $value->ref_code }} </td>
                                                                <td> {{ $value->periode }} </td>
                                                                <td> {{ tglIndo($value->tr_date) }} </td>
                                                                <td class="text-right"><span style="float:left">Rp.</span> {{ formatNoRpComma($value->total,0)}}  </td>
                                                                <td> @php echo sp_payment_method_name('name', $value->payment_method); @endphp </td>
                                                                <td class="text-center"> 
                                                                    @if(strlen($value->attachment) > 0)
                                                                    <a class="btn btn-xs green-jungle " target="_blank" href="{{ url('getDownload?type=simpanpinjam&loc=pokok/setor&name='.$value->ref_code.'&file='.$value->attachment) }}">
                                                                        Download
                                                                    </a>
                                                                    @endif
                                                                </td>
                                                                <td> 
                                                                    @php
                                                                    switch ($value->status) {
                                                                        case 1:
                                                                            echo '<div class="btn btn-xs btn-circle blue font-weight-normal" style="cursor: auto!important;"> Posting </div>';    
                                                                            break;
                                                                        case 2:
                                                                            echo '<div class="btn btn-xs btn-circle green font-weight-normal" style="cursor: auto!important;"> Diterima </div>';
                                                                            break;
                                                                        default:
                                                                            echo '<div class="btn btn-xs btn-circle default font-weight-normal" style="cursor: auto!important;"> Kalkulasi / Entry </div>';
                                                                    }
                                                                    @endphp
                                                                </td>
                                                                <td class="text-center"> 
                                                                    @if((int)$value->status <> 2)
                                                                        {!! Form::button('<i class="fa fa-money"></i> Proses Penerimaan', ['class' => 'btn btn-xs blue moveLinkWajib', 'data-swa-text' => 'Memproses Penerimaan Simpanan Pokok', 'data-spwjbid'=>Crypt::encrypt($value->periodic_saving_id)]) !!}
                                                                    @endif
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
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-xs-12 col-sm-12">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-equalizer font-dark hide"></i>
                                        <span class="caption-subject font-dark bold uppercase">Daftar Investasi</span>
                                        <span class="caption-helper"></span>
                                    </div>
                                    <div class="tools">
                                        <a href="" class="collapse" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: block;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="table-scrollable">
                                                <table class="table table-hover table-light" id="detailSetorInvesatasi">
                                                    <thead>
                                                        <tr>
                                                            <th> # </th>
                                                            <th> Jenis Transaksi </th>
                                                            <th> No Reff </th>
                                                            <th> Tanggal </th>
                                                            <th> Total </th>
                                                            <th> Metode Pembayaran </th>
                                                            <th> lampiran Reff  </th>
                                                            <th> Status </th>
                                                            <th> Aksi </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $i=1; @endphp
                                                        @foreach($data_invest as $value)
                                                            <tr>
                                                                <td> {{ $i }} </td>
                                                                <td  class="jenisTransaksi"> {{ sp_investmenttype_list()[$value->transaction_type] }} </td>
                                                                <td> {{ $value->ref_code }} </td>
                                                                <td> Pengajuan : {{ tglIndo($value->tr_date) }} </td>
                                                                <td class="text-right subtotal"><span style="float:left">Rp.</span> {{ formatNoRpComma($value->total,0)}}  </td>
                                                                <td> @php echo ($value->payment_method == 1) ? "TUNAI": "TRANSFER BANK"; @endphp </td>
                                                                <td>  </td>
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
                                                                        }else{
                                                                            echo '<a class="btn btn-xs btn-success"> Diterima </a>';
                                                                        }
                                                                    @endphp
                                                                </td>
                                                                <td>
                                                                    @php
                                                                        if($value->status <> 1){
                                                                            switch ($value->lastlevel_app) {
                                                                                
                                                                                case 7:
                                                                                    if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/investasi/prosesterima', auth()->user()->level_id) && ($value->approval_status <> 0)){
                                                                                        echo Form::button('<i class="fa fa-money"></i> Proses Penerimaan', ['class' => 'btn btn-xs blue moveLinkInvestasi', 'data-swa-text' => 'Memproses Penerimaan Investasi', 'data-spwjbid'=>Crypt::encrypt($value->investment_saving_id)]);
                                                                                    }
                                                                                    break;
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <a class="btn btn-xs green" target="_blank" href="{{ url('simpanpinjam/pdf/investasi?kd='.$value->investment_saving_id) }}"> <i class="fa fa-pdf"></i> Cetak </a>
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
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-12 col-xs-12 col-sm-12">
                            <div class="portlet light ">
                                <div class="portlet-title">
                                    <div class="caption">
                                        <i class="icon-equalizer font-dark hide"></i>
                                        <span class="caption-subject font-dark bold uppercase">Daftar Pinjaman</span>
                                        <span class="caption-helper"></span>
                                    </div>
                                    <div class="tools">
                                        <a href="" class="collapse" data-original-title="" title=""> </a>
                                    </div>
                                </div>
                                <div class="portlet-body" style="display: block;">
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
                                                            <tr>
                                                                <td> {{ $i }} </td>
                                                                <td> {{ $value->ref_code }} </td>
                                                                <td> Pengajuan : {{ tglIndo($value->loan_date) }} </td>
                                                                <td class="text-right subtotal"><span style="float:left">Rp.</span> {{ formatNoRpComma($value->loan_total,0)}}  </td>
                                                                <td class="text-right tenor">{{ $value->tenure }} Bulan</td>
                                                                <td class="text-right sisa"><span style="float:left">Rp.</span> {{ formatNoRpComma(0,0)}}  </td>
                                                                <td>  </td>
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
                                                                    <a class="detailBtnPinjaman btn btn-xs purple-sharp" data-id="{{ Crypt::encrypt($value->loan_id) }}'"> <i class="fa fa-search-plus"></i> Detail </a>
                                                                    @php
                                                                        if($value->status <> 1){
                                                                            switch ($value->lastlevel_app) {
                                                                                
                                                                                
                                                                                case 7:
                                                                                    if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/pinjaman/prosesserah', auth()->user()->level_id) && ($value->approval_status <> 0)){
                                                                                        echo Form::button('<i class="fa fa-money"></i> Proses Penyerahan', ['class' => 'btn btn-xs blue moveLinkPinjaman', 'data-swa-text' => 'Memproses Penyerahan Pinjaman', 'data-spwjbid'=>Crypt::encrypt($value->loan_id)]);
                                                                                    }
                                                                                    break;
                                                                                
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
                            </div>
                        </div>
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
                        <h3>Riwayat Angsuran:</h3>
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th> Angsuran Ke </th>
                                    <th> Tanggal </th>
                                    <th class="hidden-xs"> Keterangan </th>
                                    <th class="hidden-xs"> Angsuran Pokok </th>
                                    <th class="hidden-xs"> Angsuran Bunga </th>
                                    <th> Total </th>
                                </tr>
                            </thead>
                            <tbody>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
    </div>
</div>

@endsection
