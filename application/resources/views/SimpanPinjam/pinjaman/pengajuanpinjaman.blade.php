
@extends('layouts.master-sp')

@section('title', $title)

@push('styles')
    <link href="{{ assets('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" type="text/css" />

    <style>
        input.form-control{
            padding:0px 5px!important;
        }
        label.required-input:after{
            content : " * ";
            color: #f00;
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
    <script src="{{assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
    </script>
    <script src="{{ assets('pages/scripts/SimpanPinjam/loan.form.create.js') }}" type="text/javascript"></script>
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush

@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Permohonan Pengajuan Pinjaman</h1>
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

                    {!! Form::open(['route' => 'pinjaman.store', 'class' => 'form-horizontal', 'method' => 'POST', 'id'=>'formSpPokok','enctype' => 'multipart/form-data']) !!}
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-6 verticalBorder">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    {!! Form::label('s', 'DATA PINJAMAN', ['class' => 'control-label col-md-4 bold', 'style' => "text-align:left;"] ) !!}
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label  {{ $errors->has('person_id') ? 'has-error' : ''}}">
                                        {!! Form::label('ref_code', 'No Reff', ['class' => 'control-label col-md-4'] ) !!}
                                    <div class="col-md-8">
                                            {!! Form::text('ref_code_label', $ref, ['class' => 'form-control', 'disabled' => 'disabled'] ) !!}
                                            {!! Form::hidden('ref_code', $ref, ['class' => 'form-control'] ) !!}
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                        {!! Form::label('loan_date', 'Tanggal', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                    <div class="col-md-4">
                                        <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
                                            <span class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </span>
                                            {!! Form::text('loan_date', $def_date, ['class' => 'form-control'] ) !!}
                                        </div>

                                        <div class="form-control-focus"> </div>
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('person_id') ? 'has-error' : ''}}">
                                        {!! Form::label('person_id', 'NIAK', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                        <div class="col-md-8">
                                            {{ Form::select('person_id', array(), null, ['class' => 'form-control select2']) }}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>
                                        </div>
                                </div>

                                <div class="form-group form-md-line-input">
                                    <label class="control-label col-md-4">Nama Anggota</label>
                                    <div class="col-md-8">
                                        <input type="text" name="f_name" data-required="1" class="form-control" disabled /> </div>
                                </div>


                                <div class="form-group form-md-line-input">
                                    <label class="control-label col-md-4">Unit Kerja</label>
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-map"></i>
                                            </span>
                                            <input type="text" name="f_unit_kerja" class="form-control" placeholder="-" disabled/> 
                                        </div>    
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label ">
                                    {!! Form::label('credit_limit', 'Limit Pinjaman', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-money"></i>
                                            </span>
                                            <input type="text" name="f_credit_limit" class="form-control rupiah" placeholder="-" disabled/> 
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label ">
                                    {!! Form::label('saldo_pokok_pinjaman', 'Saldo Pokok Pinjaman', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                    <div class="col-md-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-money"></i>
                                            </span>
                                            <input type="text" name="f_saldo_pinjaman" class="form-control rupiah" placeholder="-" disabled/> 
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('loan_amount') ? 'has-error' : ''}}">
                                    {!! Form::label('loan_amount', 'Nilai Pinjaman', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-money"></i>
                                            </span>
                                            {!! Form::text('loan_amount', "", ['class' => 'form-control rupiah'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label">
                                {!! Form::label('lampiran_pengajuan', 'File Lampiran', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                    <div class="col-md-8">
                                        <input type="file" name="lampiran_pengajuan">
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tenure') ? 'has-error' : ''}}">
                                        {!! Form::label('tenure', 'Tenor', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                        <div class="col-md-2">
                                            {!! Form::text('tenure', "12", ['class' => 'form-control numeric'] ) !!}
                                        </div>
                                        <div class="col-md-3">
                                                {{ Form::select('due_type', $duetype, 3, ['class' => 'form-control select2']) }}
                                        </div>
                                    </div>

                                
                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('loan_type_id') ? 'has-error' : ''}}">
                                        {!! Form::label('loan_type_id', 'Jenis Bunga', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                        <div class="col-md-8">
                                            {{ Form::select('loan_type_id', $listLoanTypes , $SelectedLoanTypes, ['class' => 'form-control select2']) }}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>
                                        </div>
                                </div>


                            </div>

                            <div class="col-md-6">

                                <div class="form-group form-md-line-input form-md-floating-label">
                                    {!! Form::label('total', 'BIAYA - BIAYA', ['class' => 'control-label col-md-4 bold', 'style' => "text-align:left;"] ) !!}
                                </div>

                                

                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('biaya_administrasi_rupiah') ? 'has-error' : ''}}">
                                    {!! Form::label('biaya_administrasi_rupiah', 'Administrasi', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                    <div class="col-md-3">
                                        <div class="input-group">
                                            {!! Form::text('biaya_administrasi_persentase', $by_adm_pr, ['class' => 'form-control presentase text-right', 'data-default'] ) !!}
                                            <span class="input-group-addon">%</span>
                                            <div class="form-control-focus"> </div>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                            <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-money"></i>
                                                </span>
                                                {!! Form::text('biaya_administrasi_rupiah', $by_adm_rp, ['class' => 'form-control rupiah'] ) !!}
                                                <div class="form-control-focus"> </div>
                                            </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('biaya_provisi_rupiah') ? 'has-error' : ''}}">
                                    {!! Form::label('biaya_provisi_rupiah', 'Provisi', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                    <div class="col-md-3">
                                            <div class="input-group">
                                                {!! Form::text('biaya_provisi_persentase', $by_provisi_pr, ['class' => 'form-control presentase text-right', 'data-decimal'=>","] ) !!}
                                                <span class="input-group-addon">%</span>
                                                <div class="form-control-focus"> </div>
                                            </div>
                                        </div>
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-money"></i>
                                            </span>
                                            {!! Form::text('biaya_provisi_rupiah', $by_provisi_rp, ['class' => 'form-control rupiah'] ) !!}
                                            <div class="form-control-focus"> </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('resiko_daperma') ? 'has-error' : ''}}">
                                    {!! Form::label('resiko_daperma', 'Resiko Daperma', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-money"></i>
                                            </span>
                                            {!! Form::text('resiko_daperma', $by_daperma, ['class' => 'form-control rupiah'] ) !!}
                                            <div class="form-control-focus"> </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('biaya_materai') ? 'has-error' : ''}}">
                                    {!! Form::label('biaya_materai', 'Materai', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-money"></i>
                                            </span>
                                            {!! Form::text('biaya_materai', $by_materai, ['class' => 'form-control rupiah'] ) !!}
                                            <div class="form-control-focus"> </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('biaya_lain') ? 'has-error' : ''}}">
                                    {!! Form::label('biaya_lain', 'Lain-lain', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-money"></i>
                                            </span>
                                            {!! Form::text('biaya_lain', $by_lain, ['class' => 'form-control rupiah'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label ">
                                    {!! Form::label('totalBiaya', 'Jumlah Biaya', ['class' => 'control-label col-md-4 bold'] ) !!}
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-money"></i>
                                            </span>
                                            {!! Form::text('totalBiaya_label', "", ['class' => 'form-control rupiah', 'disabled' => 'disabled', 'id'=>'totalBiaya'] ) !!}
                                            {!! Form::hidden('totalBiaya', "", ['class' => 'form-control'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('totalDiterima') ? 'has-error' : ''}}">
                                    {!! Form::label('totalDiterima', 'Jumlah Diterima', ['class' => 'control-label col-md-4 bold'] ) !!}
                                    <div class="col-md-5">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                                <i class="fa fa-money"></i>
                                            </span>
                                            {!! Form::text('totalDiterima_label', "", ['class' => 'form-control rupiah', 'disabled' => 'disabled', 'id'=>'totalDiterima'] ) !!}
                                            {!! Form::hidden('totalDiterima', "", ['class' => 'form-control'] ) !!}
                                            <div class="form-control-focus"> </div>
                                            <span class="help-block"></span>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form-md-line-input form-md-floating-label">
                                    {!! Form::label('s', 'Angsuran', ['class' => 'control-label col-md-4 bold', 'style' => "text-align:left;"] ) !!}
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('interest_type') ? 'has-error' : ''}}">
                                                {!! Form::label('biaya_administrasi_rupiah', 'Bunga Pinjaman', ['class' => 'control-label col-md-4 '] ) !!}
                                            <div class="col-md-5">
                                                {!! Form::text('interest_type_label', $interest_type, ['class' => 'form-control', 'disabled' => 'disabled'] ) !!}
                                                {!! Form::hidden('interest_type', $interest_type, ['class' => 'form-control'] ) !!}
                                            </div>
                                            <div class="col-md-3">
                                                    <div class="input-group">
                                                            {!! Form::text('interest_rates', $interest_rates, ['class' => 'form-control presentase'] ) !!}
                                                            <span class="input-group-addon">%</span>
                                                        </div>
                                                </div>
                                        </div>
                                    </div>
                                </div>

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
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('principal_loan_total') ? 'has-error' : ''}}">
                                                {!! Form::label('loan_total', 'Jumlah Hutang Pokok', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                        {!! Form::text('principal_loan_total_label', 0, ['class' => 'form-control rupiah'] ) !!}
                                                        {!! Form::hidden('principal_loan_total', 0, ['class' => 'form-control'] ) !!}
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>    
                                        <div class="col-md-12">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('rates_loan_total') ? 'has-error' : ''}}">
                                                {!! Form::label('loan_total', 'Jumlah Hutang Bunga', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                        {!! Form::text('rates_loan_total_label', 0, ['class' => 'form-control rupiah'] ) !!}
                                                        {!! Form::hidden('rates_loan_total', 0, ['class' => 'form-control'] ) !!}
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>

                                        <div class="col-md-12">
                                            <hr>
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('rates_loan_total') ? 'has-error' : ''}}">
                                                {!! Form::label('loan_total', 'Total Hutang', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                        {!! Form::text('total_hutang_label', 0, ['class' => 'form-control rupiah'] ) !!}
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>

                                    </div> 
                               
                                    
                                    <div class="col-md-6">
                                        <div class="col-md-12">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('principal_amount') ? 'has-error' : ''}}">
                                                {!! Form::label('principal_amount', 'Angsuran Per-Tempo', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                        {!! Form::text('principal_amount_label', 0, ['class' => 'form-control rupiah'] ) !!}
                                                        {!! Form::hidden('principal_amount', 0, ['class' => 'form-control'] ) !!}
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
                                                        {!! Form::text('rates_amount_label', 0, ['class' => 'form-control rupiah'] ) !!}
                                                        {!! Form::hidden('rates_amount', 0, ['class' => 'form-control'] ) !!}
                                                    </div>
                                                </div>
                                            </div>    
                                        </div>
                                        
                                        <div class="col-md-12">
                                            <hr>
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('rates_loan_total') ? 'has-error' : ''}}">
                                                {!! Form::label('loan_total', 'Total Angsuran', ['class' => 'control-label col-md-4 required-input '] ) !!}
                                                <div class="col-md-5">
                                                    <div class="input-group">
                                                        <span class="input-group-addon"><i class="fa fa-money"></i></span>
                                                        {!! Form::text('total_angsuran_label', 0, ['class' => 'form-control rupiah'] ) !!}
                                                    </div>
                                                </div>
                                            </div>  
                                        </div>
                                        
                                        
                                    </div> 
                                </div>


                            </div>
                            
                            <div class="col-md-6"></div>
                        </div>

                    </div>
                    <div class="form-actions">
                        <div class="row">
                                <div class="col-md-3"></div>
                                {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Pinjaman', 'style' => 'margin-right:5px;']) !!}
                                <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze" style="margin-left:5px;"><i class="fa fa-close"></i> Batal</a>
                                <div class="col-md-3"></div>
                        </div>
                    </div>
                    {{ Form::close() }}
                        

                      
                  </div>
              </div>

          </div>
      </div>
    </div>
</div>
<!-- END PAGE CONTENT BODY -->


@endsection
