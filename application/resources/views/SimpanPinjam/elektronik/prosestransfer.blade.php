@extends('layouts.master-sp')
@section('title', $title)

@push('styles')
    <link href="{{ assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet')}}" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .form-horizontal .form-group.form-md-line-input {
            padding-top: 10px;
            margin: 0 -10px 5px!important;
        }

        .form-group.form-md-line-input .form-control[disabled], .form-group.form-md-line-input .form-control[readonly], fieldset[disabled] .form-group.form-md-line-input .form-control {
            background: 0 0;
            cursor: not-allowed;
            border-bottom: 1px solid #c2cad8 !important;
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
    <script src="{{ assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>

    <script src="{{ assets('global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';

        var vLimitPinjaman = {{ $data->anggota->customer->credit_limit }}, vSaldoPinjaman = 0, vNilaiPinjaman = {{ $data->loan_amount }},
            vBesarBunga =  {{ $data->loantype->interest_rates }}, vBDaperma = {{ $data->resiko_daperma }}, vBMaterai = {{ $data->biaya_materai }}, vBLain = {{ $data->biaya_lain }};

    </script>
    
    <!--script src="{{ assets('pages/scripts/SimpanPinjam/pinjaman.penyerahan.js') }}" type="text/javascript"></script-->
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush


@section('content')

    <!-- BEGIN PAGE HEAD-->
    <div class="page-head">
        <div class="container-fluid">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1> Penyerahan Pinjaman - Ref. Code : {{ $data->ref_code }}</h1>
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
                                    {!! Form::open(['url' => url('simpanpinjam/elektronik/apprterima/'.$idEncrypt),'method'=>'POST', 'class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4 bold">NIAK</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->niak }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Nama</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->first_name }}  {{ $data->anggota->last_name }} </p>
                                                    </div>
                                                </div>

                    
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Tanggal Lahir</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->born_place .', '.date('d F Y', strtotime($data->anggota->born_date)) }} </p>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">No. Identitas</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> :  {{ $data->anggota->id_card_number }} </p>
                                                    </div>
                                                </div> 


                                                <div class="form-group form-md-line-input">
                                                    <label class="col-md-4 control-label bold">Alamat</label>
                                                    <div class="col-md-8">
                                                            <p class="form-control-static"> : {{ $data->anggota->address_1 }} </p>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Jenis Anggota</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ sp_array_mdrray_search(sp_member_status(), 'id', 'name',$data->anggota->member_type) }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Perusahaan</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->customer->company_name }} </p>
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

                                                <div class="showBank form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? 'has-error' : ''}}  {{ $errors->has('account_number') ? 'has-error' : ''}}">
                                                    {!! Form::label('bank_id', 'Rekening Koperasi', ['class' => 'control-label col-md-4 required-input bold'] ) !!}
                                                    <div class="col-md-8">
                                                        : @php echo $nama_bank; @endphp - {{ $nomor_rekening }}
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
                                                        : {{ $data->anggota->customer->bank->nama_bank }}
                                                    </div>
                                                </div>

                                                <div class="showBank form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? 'has-error' : ''}}  {{ $errors->has('account_number') ? 'has-error' : ''}}">
                                                    {!! Form::label('bank_id', 'No Rekening Anggota', ['class' => 'control-label col-md-4 required-input  bold'] ) !!}
                                                    <div class="col-md-8">
                                                        : {{ $data->anggota->customer->account_number }}
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
                                                <hr>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    {!! Form::label('s', 'Data Pengajuan Pinjaman', ['class' => 'control-label col-md-4 bold', 'style' => "text-align:left;"] ) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="col-md-6">
                                                   

                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('loan_amount') ? 'has-error' : ''}}">
                                                        {!! Form::label('loan_amount', 'Nilai Pinjaman', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                        <div class="col-md-5">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-money"></i>
                                                                </span>
                                                                {!! Form::text('loan_amount', $data->loan_total, ['class' => 'form-control rupiah' , 'readonly' => 'readonly'] ) !!}
                                                                <div class="form-control-focus"> </div>
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>
                    
                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tenure') ? 'has-error' : ''}}">
                                                        {!! Form::label('tenure', 'Tenor', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                        <div class="col-md-2">
                                                            {!! Form::text('tenure', $data->tenure, ['class' => 'form-control numeric' , 'readonly' => 'readonly'] ) !!}
                                                        </div>
                                                        <div class="col-md-3">
                                                            <p class="form-control-static"> {{ $duetype[3] }} </p>
                                                        </div>
                                                    </div>
                    
                                                    
                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('loan_type_id') ? 'has-error' : ''}}">
                                                        {!! Form::label('interest_rates', 'Bunga', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                        <div class="col-md-8">
                                                            <p class="form-control-static"> {{ $data->interest_rates*100 }}% </p>
                                                        </div>
                                                    </div>

                                                    

                                                </div>

                                                <div class="col-md-6">

                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('loan_amount') ? 'has-error' : ''}}">
                                                        {!! Form::label('loan_amount', 'Nilai Bunga', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                        <div class="col-md-5">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-money"></i>
                                                                </span>
                                                                {!! Form::text('loan_amount', $data->rates_total, ['class' => 'form-control rupiah', 'readonly' => 'readonly'] ) !!}
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('loan_amount') ? 'has-error' : ''}}">
                                                        {!! Form::label('loan_amount', 'Nilai Total Pinjaman', ['class' => 'control-label col-md-4 ', 'readonly' => 'readonly'] ) !!}
                                                        <div class="col-md-5">
                                                            <div class="input-group">
                                                                <span class="input-group-addon">
                                                                    <i class="fa fa-money"></i>
                                                                </span>
                                                                {!! Form::text('loan_amount', ($data->loan_total + $data->rates_total), ['class' => 'form-control rupiah'] ) !!}
                                                                <span class="help-block"></span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    


                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('totalDiterima') ? 'has-error' : ''}}">
                                                        {!! Form::label('totalDiterima', 'Jumlah Diterima', ['class' => 'control-label col-md-4 bold'] ) !!}
                                                        <div class="col-md-5">
                                                            <p class="form-control-static"> : <b>{{ $data->unit_desc }}</b>
                                                            
                                                        </div>
                                                    </div>

                                                </div>

                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    {!! Form::label('s', 'Data Angsuran', ['class' => 'control-label col-md-4 bold', 'style' => "text-align:left;"] ) !!}
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                
                
                                                <div class="row hide">
                                                        <div class="col-md-6">
                                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('late_tolerance') ? 'has-error' : ''}}">
                                                                {!! Form::label('late_tolerance', 'Toleransi Telat', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                                <div class="col-md-2">
                                                                    <div class="input-group">
                                                                        {!! Form::text('late_tolerance', 0, ['class' => 'form-control text-right'] ) !!}
                                                                        <span class="input-group-addon">Hari</span>
                                                                        <div class="form-control-focus"> </div>
                                                                        <span class="help-block"></span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                
                                                        <div class="col-md-6">
                                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('daily_fines') ? 'has-error' : ''}}">
                                                                    {!! Form::label('daily_fines', 'Denda Per-hari', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                                    <div class="col-md-2">
                                                                        <div class="input-group">
                                                                            {!! Form::text('daily_fines', 0, ['class' => 'form-control presentase'] ) !!}
                                                                            <span class="input-group-addon">%</span>
                                                                            <div class="form-control-focus"> </div>
                                                                            <span class="help-block"></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                        </div>
                                                </div>
                                            
                                                
                                                
                
                                                <div class="row">
                                                   
                                                    <div class="col-md-6">
                                                        <div class="col-md-12">
                                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('principal_amount') ? 'has-error' : ''}}">
                                                                {!! Form::label('principal_amount', 'Angsuran Per-Tempo', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                                <div class="col-md-5">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                                        {!! Form::text('principal_amount_label', $data->principal_amount, ['class' => 'form-control rupiah','disabled' => 'disabled'] ) !!}
                                                                        {!! Form::hidden('principal_amount', $data->principal_amount, ['class' => 'form-control'] ) !!}
                                                                    </div>
                                                                </div>
                                                            </div>    
                                                        </div>    
                                                        <div class="col-md-12">
                                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('rates_amount') ? 'has-error' : ''}}">
                                                                {!! Form::label('rates_amount', 'Angsuran Bunga Per-Tempo', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                                <div class="col-md-5">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                                        {!! Form::text('rates_amount_label', $data->rates_amount, ['class' => 'form-control rupiah','disabled' => 'disabled'] ) !!}
                                                                        {!! Form::hidden('rates_amount', $data->rates_amount, ['class' => 'form-control'] ) !!}
                                                                    </div>
                                                                </div>
                                                            </div>    
                                                        </div>
                                                        
                                                        <div class="col-md-12">
                                                            
                                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('rates_loan_total') ? 'has-error' : ''}}">
                                                                {!! Form::label('loan_total', 'Total Angsuran', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                                <div class="col-md-5">
                                                                    <div class="input-group">
                                                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                                        {!! Form::text('total_angsuran_label', $data->rates_amount + $data->principal_amount, ['class' => 'form-control rupiah'] ) !!}
                                                                    </div>
                                                                </div>
                                                            </div>  
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
                                                {!! Form::button('Proses', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Penyerahan Pinjaman', 'style' => 'margin-right:5px;']) !!}
                                                <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze">Batal</a>
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

