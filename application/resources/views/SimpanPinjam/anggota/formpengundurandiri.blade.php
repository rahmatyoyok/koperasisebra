@extends('layouts.master-sp')

@section('title', $title)

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
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
    <script src="{{ assets('pages/scripts/simpanpinjam/formpengundurandiri.anggota.js') }}" type="text/javascript"></script>
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush


@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>{{ $title }}</h1>
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
                                
                                {!! Form::open([ 'url' => ['simpanpinjam/anggota/submitpengundurandirianggota'], 'class' => 'form-horizontal', 'method' => 'POST', 'id'=>'formSpPokok']) !!}
                                
                                    <div class="form-body">
                                        <div class="alert alert-danger display-hide">
                                            <button class="close" data-close="alert"></button> Pastikan kolom bertanda merah terisi. 
                                        </div>
                                          
                                        <div class="row">
                                            <div class="col-md-6">
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('first_name') ? 'has-error' : ''}}">
                                                        {!! Form::label('niak', 'NIAK', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        {{ Form::select('person_id', array(), null, ['class' => 'form-control select2']) }}
                                                        <div class="form-control-focus"> </div>
                                                        <span class="help-block"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4">Nama Anggota
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="f_name" data-required="1" class="form-control" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4">Tempat, Tangal Lahir
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="f_ttl" data-required="1" class="form-control" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4">No. Identitas
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="f_no_identitas" data-required="1" class="form-control" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4">Alamat
                                                        <span class="required"> * </span>
                                                    </label>
                                                    <div class="col-md-8"><input type="text" name="f_alamat" data-required="1" class="form-control" disabled /> </div>
                                                </div>
                                                
                                            </div>
                                        
                                            <div class="col-md-6">
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('member_type') ? 'has-error' : ''}}">
                                                    {!! Form::label('member_type', 'Jenis Anggota', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_jenis_anggota" data-required="1" class="form-control" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? 'has-error' : ''}}">
                                                    {!! Form::label('member_status', 'Status Anggota', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_status" data-required="1" class="form-control" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? 'has-error' : ''}}  {{ $errors->has('account_number') ? 'has-error' : ''}}">
                                                    {!! Form::label('bank_id', 'Rekening', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_rekening" data-required="1" class="form-control" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label{ $errors->has('company_name') ? 'has-error' : ''}}">
                                                    {!! Form::label('company_name', 'Unit Kerja', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_unit_kerja" data-required="1" class="form-control" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label{ $errors->has('nomor_induk') ? 'has-error' : ''}}">
                                                    {!! Form::label('nomor_induk', 'Nomor Induk', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_no_induk" data-required="1" class="form-control" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label{ $errors->has('jabatan') ? 'has-error' : ''}}">
                                                    {!! Form::label('jabatan', 'Jabatan', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_jabatan" data-required="1" class="form-control" disabled /> </div>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <br>
                                                <br>
                                                <h4 class="bold">SALDO SIMPANAN</h4>

                                                <div class="col-md-6">
                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('first_name') ? 'has-error' : ''}}">
                                                        {!! Form::label('Simpanan Pokok', 'Simpanan Pokok', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                        <div class="col-md-8"><input type="text" name="f_simpanan_pokok" data-required="1" class="form-control rupiah" disabled /> </div>
                                                    </div>

                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('first_name') ? 'has-error' : ''}}">
                                                        {!! Form::label('Simpanan wajib', 'Simpanan Wajib', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                        <div class="col-md-8"><input type="text" name="f_simpanan_wajib" data-required="1" class="form-control rupiah" disabled /> </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('first_name') ? 'has-error' : ''}}">
                                                        {!! Form::label('Simpanan wajib', 'Simpanan Investasi', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                        <div class="col-md-8"><input type="text" name="f_simpanan_investasi" data-required="1" class="form-control rupiah" disabled /> </div>
                                                    </div>
                                                </div>


                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-md-12">
                                                <br>
                                                <br>
                                                <h4 class="bold">SALDO PINJAMAN</h4>

                                                <div class="col-md-6">
                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('first_name') ? 'has-error' : ''}}">
                                                        {!! Form::label('Simpanan wajib', 'Pinjaman USP', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                        <div class="col-md-8"><input type="text" name="f_pinjaman_usp" data-required="1" class="form-control rupiah" disabled /> </div>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('first_name') ? 'has-error' : ''}}">
                                                        {!! Form::label('Simpanan wajib', 'Pinjaman Elektronik', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                        <div class="col-md-8"><input type="text" name="f_pinjaman_elektronik" data-required="1" class="form-control rupiah" disabled /> </div>
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
