@extends('layouts.master-sp')
@section('title', $title)

@push('styles')

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->

    <style>
        .form-horizontal .form-group.form-md-line-input {
            padding-top: 11px;
            margin: 0 -10px 5px!important;
        }

        .form-group.form-md-line-input .form-control[disabled], .form-group.form-md-line-input .form-control[readonly], fieldset[disabled] .form-group.form-md-line-input .form-control {
            background: 0 0;
            cursor: not-allowed;
            border-bottom: 1px solid #c2cad8 !important;
        }

        .form-horizontal .form-group.form-md-line-input > label {
            padding-top:7px!important;
        }
    </style>

@endpush

@push('plugins')
    <script src="{{ assets('global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-markdown/lib/markdown.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-markdown/js/bootstrap-markdown.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery.masknumber.min.js') }}" type="text/javascript"></script>
    <script src="{{assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
    var BaseUrl = '{{ url('/') }}';
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ assets('pages/scripts/SimpanPinjam/anggota.index.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')
    <!-- BEGIN PAGE HEAD-->
    <div class="page-head">
        <div class="container-fluid">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1> Pelunasan dan Penyerahan Saldo Anggota </h1>
            </div>
        </div>
    </div>
    <!-- END PAGE HEAD-->

    <!-- BEGIN PAGE CONTENT BODY -->
    <div class="page-content">
        <div class="container-fluid">
            <!-- BEGIN PAGE CONTENT INNER -->
            <div class="page-content-inner">
                <div class="mt-content-body">
                    <div class="row">
                        <div class="col-lg-12 col-xs-12 col-sm-12">
                            <div class="portlet light bordered">
                                <div class="portlet-body form">
                                    {!! Form::open(['url' => url('simpanpinjam/anggota/prosespembayaranPelunasan/'.$personId),'method'=>'POST', 'class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4 bold">NIAK</label>
                                                    <div class="col-md-8 mt10">
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
                                                    <div class="col-md-8">
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
                                                    <label class="col-md-4 control-label bold">Jenis Anggota</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ sp_array_mdrray_search(sp_member_status(), 'id', 'name',$data->member_type) }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Perusahaan</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->customer->company_name }} </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Cara Penyerahan</label>
                                                    <div class="col-md-8">
                                                        {{ Form::select('payment_method', sp_payment_method_list(), 1, ['class' => 'form-control select2']) }}
                                                    </div>
                                                </div>

                                                <div class="showBank form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Rekening Koperasi</label>
                                                    <div class="col-md-8">
                                                    <p class="form-control-static"> : {{ $nama_bank.' - '.$nomor_rekening }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                        {!! Form::label('transfer_date', 'Tanggal Penyerahan', ['class' => 'control-label col-md-4 required-input  bold'] ) !!}
                                                    <div class="col-md-5">
                                                        <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                            {!! Form::text('transfer_date', $def_date, ['class' => 'form-control', 'autofocus'] ) !!}
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="showBank form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? 'has-error' : ''}}  {{ $errors->has('account_number') ? 'has-error' : ''}}">
                                                    {!! Form::label('bank_id', 'Rekening Anggota', ['class' => 'control-label col-md-4 required-input bold'] ) !!}
                                                    <div class="col-md-8">
                                                    <p class="form-control-static"> : {{ $data->customer->bank->nama_bank }} </p>
                                                    </div>
                                                </div>

                                                <div class="showBank form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? 'has-error' : ''}}  {{ $errors->has('account_number') ? 'has-error' : ''}}">
                                                    {!! Form::label('bank_id', 'No Rekening Anggota', ['class' => 'control-label col-md-4 required-input  bold'] ) !!}
                                                    <div class="col-md-8">
                                                    <p class="form-control-static"> : {{ $data->customer->account_number }} </p>
                                                    </div>
                                                </div>


                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="control-label col-md-4  bold">Dokumen Pendukung:</label>
                                                    <div class="col-md-8">
                                                        <input type="file" name="dokumen">
                                                        <input type="hidden" name="id" value="">
                                                    </div>
                                                </div>

                                            </div>

                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="bold">SALDO SIMPANAN</h4>

                                                <div class="col-md-6">
                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                        <label class="col-md-4 control-label bold">Simpanan Pokok</label>
                                                        <div class="col-md-4">
                                                            <p class="form-control-static"> : <span class="rupiah">{{ $data->saldo['saldoPokok'] }}</span> </p>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                        <label class="col-md-4 control-label bold">Simpanan Wajib</label>
                                                        <div class="col-md-4">
                                                            <p class="form-control-static"> : <span class="rupiah">{{ $data->saldo['saldoWajib'] }}</span> </p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                        <label class="col-md-4 control-label bold">Simpanan Investasi</label>
                                                        <div class="col-md-4">
                                                            <p class="form-control-static"> : <span class="rupiah">{{ $data->saldo['saldoInvestasi'] }}</span> </p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="bold">SALDO PINJAMAN</h4>

                                                <div class="col-md-6">
                                                    <div class="col-md-12">
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <label class="col-md-4 control-label bold">Pinjaman USP</label>
                                                            <div class="col-md-4">
                                                                <p class="form-control-static"> : <span class="rupiah">{{ $data->saldo['pinjaman_usp'] }}</span> </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <div class="form-group form-md-line-input form-md-floating-label">
                                                            <label class="col-md-4 control-label bold">Bunga Pinjaman USP</label>
                                                            <div class="col-md-4">
                                                                <p class="form-control-static"> : <span class="rupiah">{{ $data->saldo['rates_pinjaman_usp'] }}</span> </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                        <label class="col-md-4 control-label bold">Pinjaman Elektronik</label>
                                                        <div class="col-md-4">
                                                            <p class="form-control-static"> : <span class="rupiah">{{ $data->saldo['pinjaman_elektronik'] }}</span> </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @php 
                                            $totalsimpanan = $data->saldo['saldoPokok'] + $data->saldo['saldoWajib'] + $data->saldo['saldoInvestasi'];
                                            $totalPinjaman = $data->saldo['pinjaman_usp'] + $data->saldo['rates_pinjaman_usp'] + $data->saldo['pinjaman_elektronik'];
                                            
                                            $showLabelTotal = ""; $totalShow = 0;
                                            if($totalsimpanan > $totalPinjaman){
                                                $showLabelTotal = "Total Penyerahan Saldo Simpanan"; $totalShow = $totalsimpanan - $totalPinjaman;
                                            }
                                            elseif($totalsimpanan < $totalPinjaman){
                                                $showLabelTotal = "Total Penerimaan Saldo Sisa Pinjaman"; $totalShow = $totalPinjaman - $totalsimpanan;
                                            }

                                        @endphp
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4 class="bold">Total Pelunasan</h4>
                                                <div class="col-md-6">
                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                        <label class="col-md-4 control-label bold">{{ $showLabelTotal }}</label>
                                                        <div class="col-md-4">
                                                            <p class="form-control-static"> : <span class="rupiah">{{ $totalShow }}</span></p>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                    
                                    </div>

                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-12">
                                                    <div class="col-md-3"></div>
                                                    {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'mengajukan Pengunduran Diri Angggota', 'style' => 'margin-right:5px;']) !!}
                                                        <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze"><i class="fa fa-close"></i> Batal</a>
                                                        
                                                    <div class="col-md-3"></div>
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
            <!-- END PAGE CONTENT INNER -->
        </div>
    </div>
    <!-- END PAGE CONTENT BODY -->
    

@endsection