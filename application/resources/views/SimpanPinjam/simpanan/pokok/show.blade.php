@php
    use Illuminate\Support\Facades\Crypt;
@endphp

@extends('layouts.master-sp')
@section('title', $title)

@push('styles')
    <link href="{{assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet')}}" type="text/css" />
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
    
    <script src="{{ assets('pages/scripts/SimpanPinjam/simpanpokok.show.js') }}" type="text/javascript"></script>
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
                            <span class="caption-subject bold uppercase">Informasi Simpanan Pokok Anggota</span>
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
                                    </div>
                                </div>
                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr>
                                        <h4>Dafatar Simpanan Pokok</h4>
                                    </div>
                                </div>
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
                                                            <td> <b> @php echo ($value->status == 2) ? "Diterima": (($value->is_deleted == 0) ? "Pending" : "Batal") ; @endphp</b> </td>
                                                            <td class="text-center"> 
                                                                @if((int)$value->status <> 2 && (int)$value->is_deleted <> 1)
                                                                    {!! Form::button('<i class="fa fa-money"></i> Proses Penerimaan', ['class' => 'btn btn-xs blue moveLink', 'data-swa-text' => 'Memproses Penerimaan Simpanan Pokok', 'data-spwjbid'=>Crypt::encrypt($value->principal_saving_id)]) !!}
                                                                    {!! Form::button('<i class="fa fa-remove"></i> Batal', ['class' => 'btn btn-xs red moveLinkBatal', 'data-swa-text' => 'Pembatalan Simpanan Pokok', 'data-spwjbid'=>Crypt::encrypt($value->principal_saving_id)]) !!}
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
                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- END PAGE CONTENT BODY -->
@endsection