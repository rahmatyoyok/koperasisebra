@extends('layouts.master-sp')

@section('title', $title)

@push('styles')

    <link href="{{ assets('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" type="text/css" />
    
    <style>
        input.uppercase{
            text-transform: uppercase!important;
        }
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
    <script src="{{ assets('pages/scripts/simpanpinjam/anggota.create.js') }}" type="text/javascript"></script>
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush


@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Ubah Data Anggota</h1>
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
                        <div class="col-md-12">
                        <!-- BEGIN VALIDATION STATES-->
                          <div class="portlet light portlet-fit portlet-form ">
                              
                              <div class="portlet-body">
                                <!-- BEGIN FORM-->
                                {!! Form::model($data, ['method' => 'PATCH', 'url' => ['simpanpinjam/anggota', $data_person_id], 'class' => 'form-horizontal', 'id'=>'formSpPokok']) !!}
                                    <div class="form-body">
                                        <div class="alert alert-danger display-hide">
                                            <button class="close" data-close="alert"></button> Pastikan kolom bertanda merah terisi. 
                                        </div>
                                          
                                        <div class="row">
                                            <div class="col-md-6">
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('first_name') ? 'has-error' : ''}}">
                                                        {!! Form::label('first_name', 'Nama Depan', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('first_name', $data->first_name, ['class' => 'form-control input-sm uppercase', 'data-required'=>1]) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>


                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('last_name') ? 'has-error' : ''}}">
                                                        {!! Form::label('last_name', 'Nama Belakang', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('last_name', $data->last_name, ['class' => 'form-control input-sm uppercase']) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('born_place') ? 'has-error' : ''}}  {{ $errors->has('born_date') ? 'has-error' : ''}}">
                                                        {!! Form::label('born_place', 'Tempat, Tanggal Lahir', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon"><i class="fa fa-map"></i></span>
                                                            {!! Form::text('born_place', $data->born_place, ['class' => 'form-control input-sm uppercase']) !!}
                                                            <div class="form-control-focus"> </div><span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="input-group date date-picker" data-date-format="dd-mm-yyyy">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                            {!! Form::text('born_date', ( (strlen($data->born_date) == 10) ? date_format(date_create($data->born_date),'d-m-Y') : ""), ['class' => 'form-control input-sm ']) !!}
                                                            <div class="form-control-focus"> </div><span class="help-block">dd-mm-yyyy</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-radios">
                                                    <label class="col-md-4 control-label" for="form_control_1">Jenis Kelamin
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-8">
                                                        <div class="md-radio-list">
                                                            <div class="md-radio">
                                                                <input type="radio" id="checkbox1_6" name="f_jeniskelamin" value="1" class="md-radiobtn" @if($data->gender == 1) checked="checked" @endif >
                                                                <label for="checkbox1_6">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Laki-Laki </label>
                                                            </div>
                                                            <div class="md-radio">
                                                                <input type="radio" id="checkbox1_7" name="f_jeniskelamin" value="0" class="md-radiobtn" @if($data->gender == 2) checked="checked" @endif >
                                                                <label for="checkbox1_7">
                                                                    <span></span>
                                                                    <span class="check"></span>
                                                                    <span class="box"></span> Perempuan </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('id_card_number') ? 'has-error' : ''}}">
                                                        {!! Form::label('id_card_number', 'No. Identitas', ['class' => 'control-label col-md-4'] ) !!}
                                                    </label>
                                                    <div class="col-md-8">
                                                        {!! Form::text('id_card_number', $data->id_card_number, ['class' => 'form-control input-sm ']) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div> 

                                                <div class="form-group form-md-line-input {{ $errors->has('address_1') ? 'has-error' : ''}}">
                                                        {!! Form::label('address_1', 'Alamat', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::textarea('address_1', $data->address_1, ['class' => 'form-control input-sm ', 'rows' => 4, 'cols' => 54]) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('city') ? 'has-error' : ''}}">
                                                    {!! Form::label('city', 'Kota', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-map"></i>
                                                            </span>
                                                            {!! Form::text('city', $data->city, ['class' => 'form-control input-sm ']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('state') ? 'has-error' : ''}}">
                                                        {!! Form::label('state', 'Provinsi', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-map"></i>
                                                            </span>
                                                            {!! Form::text('state', $data->state, ['class' => 'form-control input-sm ']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('country') ? 'has-error' : ''}}">
                                                        {!! Form::label('country', 'Negara', ['class' => 'control-label col-md-4 '] ) !!}
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-map"></i>
                                                            </span>
                                                            {!! Form::text('country', $data->country, ['class' => 'form-control input-sm ']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('npwp') ? 'has-error' : ''}}">
                                                    {!! Form::label('npwp', 'npwp', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-map"></i>
                                                            </span>
                                                            {!! Form::text('npwp', $data->customer->npwp, ['class' => 'form-control input-sm ', 'data-required'=>1]) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('email') ? 'has-error' : ''}}">
                                                        {!! Form::label('email', 'Email', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-map"></i>
                                                            </span>
                                                            {!! Form::text('email', $data->email, ['class' => 'form-control input-sm ']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('phone_number') ? 'has-error' : ''}}">
                                                        {!! Form::label('phone_number', 'No. Telp', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-mobile-phone"></i>
                                                            </span>
                                                            {!! Form::text('phone_number', $data->phone_number, ['class' => 'form-control input-sm ']) !!}
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('member_type') ? 'has-error' : ''}}">
                                                        {!! Form::label('member_type', 'Jenis Anggota', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        {{ Form::select('member_type', $list_member_type, $data->member_type, ['class' => 'form-control select2']) }}
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? 'has-error' : ''}}">
                                                        {!! Form::label('member_status', 'Status Anggota', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        {{ Form::select('member_status', $list_member_status, $data->member_status, ['class' => 'form-control select2']) }}
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? 'has-error' : ''}}  {{ $errors->has('account_number') ? 'has-error' : ''}}">
                                                    {!! Form::label('bank_id', 'Rekening', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-3">
                                                        {{ Form::select('bank_id', $list_bank, $data->customer->bank_id, ['class' => 'form-control select2']) }}
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-bank"></i>
                                                            </span>
                                                            {!! Form::text('account_number', $data->customer->account_number, ['class' => 'form-control input-sm ', 'data-required'=>1]) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label{ $errors->has('company_name') ? 'has-error' : ''}}">
                                                        {!! Form::label('company_name', 'Unit Kerja', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-map"></i>
                                                            </span>
                                                            {!! Form::text('company_name', $data->customer->company_name, ['class' => 'form-control input-sm ']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label{ $errors->has('nomor_induk') ? 'has-error' : ''}}">
                                                    {!! Form::label('nomor_induk', 'Nomor Induk', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-credit-card"></i>
                                                            </span>
                                                            {!! Form::text('nomor_induk', $data->customer->nomor_induk, ['class' => 'form-control input-sm ']) !!}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label{ $errors->has('jabatan') ? 'has-error' : ''}}">
                                                    {!! Form::label('jabatan', 'Jabatan', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-sitemap"></i>
                                                            </span>
                                                            {!! Form::text('jabatan', $data->customer->jabatan, ['class' => 'form-control input-sm ']) !!}
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group form-md-line-input form-md-floating-label{ $errors->has('credit_limit') ? 'has-error' : ''}}">
                                                        {!! Form::label('credit_limit', 'Batas Pinjaman', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-5">
                                                        <div class="input-group">
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-money"></i>
                                                            </span>
                                                            {!! Form::text('credit_limit', $data->customer->credit_limit, ['class' => 'form-control input-sm rupiah', 'data-required'=>1]) !!}
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
                                                    {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Mengubah Data Angggota', 'style' => 'margin-right:5px;']) !!}
                                                        <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze"><i class="fa fa-close"></i> Batal</a>
                                                        
                                                    <div class="col-md-3"></div>
                                            </div>
                                        </div>
                                    </div>
                                  </form>
                                  <!-- END FORM-->
                              </div>                              
                          </div>
                          <!-- END VALIDATION STATES-->
                        </div>
                      </div>
                  </div>
              </div>

          </div>
      </div>
    </div>
</div>
<!-- END PAGE CONTENT BODY -->


@endsection
