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
    <script src="{{ assets('pages/scripts/SimpanPinjam/investasi.show.js') }}" type="text/javascript"></script>
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
                            <span class="caption-subject bold uppercase">Informasi Investasi Anggota</span>
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
                                            <label class="control-label col-md-4 bold">Saldo Total Investasi</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : Rp. <span id="TotalInvestasii"> {{ formatNoRpComma($saldoInvestasi,0)}} </span></p>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <hr>
                                        <h4>Daftar Investasi</h4>
                                    </div>
                                </div>
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
                                                            <td  class="jenisTransaksi" data-trtype="{{ (sp_investmenttype_list()[$value->transaction_type] == 'Penyetoran') ? 1 : 2 }}"> {{ sp_investmenttype_list()[$value->transaction_type] }} </td>
                                                            <td> {{ $value->ref_code }} </td>
                                                            <td> 
                                                                @php
                                                                    if($value->status == 0)
                                                                        echo "Pengajuan : ".tglIndo($value->tr_date);
                                                                    elseif($value->status == 1)
                                                                        echo (($value->transaction_type == 1) ? "Penerimaan : " : "Penyerahan").tglIndo($value->payment_date);
                                                                @endphp
                                                            </td>
                                                            <td class="text-right subtotal"><span style="float:left">Rp.</span> {{ formatNoRpComma($value->total,0)}}  </td>
                                                           
                                                            <td> @php echo sp_payment_method_name('name', $value->payment_method); @endphp </td>
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
                                                                        if($value->transaction_type == 1)
                                                                            echo '<a class="btn btn-xs btn-success"> Diterima </a>';

                                                                        if($value->transaction_type == 2)
                                                                            echo '<a class="btn btn-xs purple-wisteria"> Diserahkan </a>';

                                                                    }
                                                                    
                                                                    echo '<br><span style="float:left">Keterangan : </span>';
                                                                @endphp
                                                            </td>
                                                            <td>
                                                                @php
                                                                    if($value->status <> 1){
                                                                        
                                                                        switch ($value->lastlevel_app) {
                                                                            case 9:
                                                                                if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/investasi/approvalpengajuandua', auth()->user()->level_id) && ($value->approval_status <> 0)){
                                                                                    echo '<a class="approveBtn btn btn-xs blue-sharp" data-id="'.$value->investment_saving_id.'" data-hreffaction="simpanpinjam/investasi/approvalpengajuandua"> <i class="fa fa-check-circle"></i> Setujui </a>';
                                                                                }
                                                                                break;
                                                                            case 8:
                                                                                if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/investasi/approvalpengajuansatu', auth()->user()->level_id) && ($value->approval_status <> 0)){
                                                                                    echo '<a class="approveBtn btn btn-xs blue-sharp" data-id="'.$value->investment_saving_id.'" data-hreffaction="simpanpinjam/investasi/approvalpengajuansatu"> <i class="fa fa-check-circle"></i> Setujui </a>';
                                                                                }
                                                                                break;
                                                                            case 7:
                                                                                if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/investasi/prosesterima', auth()->user()->level_id) && ($value->approval_status <> 0)){
                                                                                    if($value->transaction_type == 1)
                                                                                        echo Form::button('<i class="fa fa-money"></i> Proses Penerimaan', ['class' => 'btn btn-xs blue moveLink', 'data-swa-text' => 'Memproses Penerimaan Investasi', 'data-spwjbid'=>Crypt::encrypt($value->investment_saving_id)]);
                                                                                    elseif($value->transaction_type == 2)
                                                                                        echo Form::button('<i class="fa fa-money"></i> Proses Penyerahan', ['class' => 'btn btn-xs blue moveLink', 'data-swa-text' => 'Memproses Penyerahan Investasi', 'data-spwjbid'=>Crypt::encrypt($value->investment_saving_id)]);
                                                                                }
                                                                                break;
                                                                            case 1:
                                                                                if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/investasi/prosesterima', auth()->user()->level_id) && ($value->approval_status <> 0)){
                                                                                    if($value->transaction_type == 1)
                                                                                        echo Form::button('<i class="fa fa-money"></i> Proses Penerimaan', ['class' => 'btn btn-xs blue moveLink', 'data-swa-text' => 'Memproses Penerimaan Investasi', 'data-spwjbid'=>Crypt::encrypt($value->investment_saving_id)]);
                                                                                    elseif($value->transaction_type == 2)
                                                                                        echo Form::button('<i class="fa fa-money"></i> Proses Penyerahan', ['class' => 'btn btn-xs blue moveLink', 'data-swa-text' => 'Memproses Penyerahan Investasi', 'data-spwjbid'=>Crypt::encrypt($value->investment_saving_id)]);
                                                                                }
                                                                                break;
                                                                            default :
                                                                                if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/investasi/approvalpengajuantiga', auth()->user()->level_id)){
                                                                                    echo '<a class="approveBtn btn btn-xs blue-sharp" data-id="'.$value->investment_saving_id.'" data-hreffaction="simpanpinjam/investasi/approvalpengajuantiga"> <i class="fa fa-check-circle"></i> Setujui </a>';
                                                                                }
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
                            {!! Form::close() !!}
                    </div>
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
                    <p id="descInvestasi"></p>
                    <p>Ketrangan :
                        <br>
                        <textarea name="detailApprovalDesc" rows="4" cols="50" class="form-control"></textarea>
                    </p>

                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" id="approveInvestasi" class="btn green"> Setuju </button>
            <button type="button" id="rejectInvestasi" class="btn red-haze"> Tidak Setuju</button>
        </div>
    </div>


<!-- END PAGE CONTENT BODY -->
@endsection
