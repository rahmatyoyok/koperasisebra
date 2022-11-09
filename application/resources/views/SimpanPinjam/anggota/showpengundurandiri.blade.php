@extends('layouts.master-sp')

@section('title', $title)

@push('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    
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
                                
                                {!! Form::open([ 'url' => ['#'], 'class' => 'form-horizontal', 'method' => 'POST', 'id'=>'formSpPokok']) !!}
                                
                                    <div class="form-body">
                                          
                                        <div class="row">
                                            <div class="col-md-6">
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('first_name') ? ' ' : ''}}">
                                                        {!! Form::label('niak', 'NIAK', ['class' => 'control-label col-md-4  '] ) !!}
                                                    <div class="col-md-8">
                                                        <input type="text" name="f_name" data-required="1" class="form-control" value="{{ $data->niak }}" disabled /> 
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4">Nama Anggota
                                                         
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="f_name" data-required="1" class="form-control" value="{{ $data->first_name .' '.$data->last_name }}"  disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4">Tempat, Tangal Lahir
                                                         
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="f_ttl" data-required="1" class="form-control" value="{{ $data->born_palce .', '.$data->born_date }}" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4">No. Identitas
                                                         
                                                    </label>
                                                    <div class="col-md-8">
                                                        <input type="text" name="f_no_identitas" data-required="1" class="form-control" value="{{ $data->id_card_number }}" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4">Alamat
                                                         
                                                    </label>
                                                    <div class="col-md-8"><input type="text" name="f_alamat" data-required="1" class="form-control" value="{{ $data->address_1 }}" disabled /> </div>
                                                </div>
                                                
                                            </div>
                                        
                                            <div class="col-md-6">
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('member_type') ? ' ' : ''}}">
                                                    {!! Form::label('member_type', 'Jenis Anggota', ['class' => 'control-label col-md-4  '] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_jenis_anggota" data-required="1" class="form-control" value="{{ $data->jenis_anggota_desc }}" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? ' ' : ''}}">
                                                    {!! Form::label('member_status', 'Status Anggota', ['class' => 'control-label col-md-4  '] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_status" data-required="1" class="form-control" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? ' ' : ''}}  {{ $errors->has('account_number') ? ' ' : ''}}">
                                                    {!! Form::label('bank_id', 'Rekening', ['class' => 'control-label col-md-4  '] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_rekening" data-required="1" class="form-control"  value="{{ $data->customer->bank->nama_bank .' - '.$data->customer->account_number }}"disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label{ $errors->has('company_name') ? ' ' : ''}}">
                                                    {!! Form::label('company_name', 'Unit Kerja', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_unit_kerja" data-required="1" class="form-control" value="{{ $data->customer->company_name }}" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label{ $errors->has('nomor_induk') ? ' ' : ''}}">
                                                    {!! Form::label('nomor_induk', 'Nomor Induk', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_no_induk" data-required="1" class="form-control" value="{{ $data->customer->nomor_induk }}" disabled /> </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label{ $errors->has('jabatan') ? ' ' : ''}}">
                                                    {!! Form::label('jabatan', 'Jabatan', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8"><input type="text" name="f_jabatan" data-required="1" class="form-control" value="{{ $data->customer->jabatan }}" disabled /> </div>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <br>
                                                <br>
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
                                                <br>
                                                <br>
                                                <h4 class="bold">SALDO PINJAMAN</h4>

                                                <div class="col-md-6">
                                                    <div class="form-group form-md-line-input form-md-floating-label">
                                                        <label class="col-md-4 control-label bold">Pinjaman USP</label>
                                                        <div class="col-md-4">
                                                            <p class="form-control-static"> : <span class="rupiah">{{ $data->saldo['pinjaman_usp'] }}</span> </p>
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

                                    </div>
                                    <div class="form-actions">
                                        <div class="row">
                                            <div class="col-md-12">
                                                    <div class="col-md-3"></div>
                                                    @php
                                                        if($data->resign->status == 0){
                                                            if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/anggota/approvalresignsatu', auth()->user()->level_id) && (strlen($data->resign->approval_bendahara_id) <= 0)){
                                                                echo Form::button('<i class="fa fa-check"></i> Setujui Proses Penyerahan & Pelunasan', ['class' => 'btn blue-sharp col-md-3 linkto', 'data-href'=> URL::to("/simpanpinjam/anggota/approvalresignsatu/".$personId), 'type' => 'button', 'data-swa-text' => 'Menyetujui Pengunduran Diri Angggota', 'style' => 'margin-right:5px;']);
                                                            }
                                                            if(App\Http\Models\Pengaturan\Menu::isValid('simpanpinjam/anggota/approvalresigndua', auth()->user()->level_id) && (strlen($data->resign->approval_ketua_id) <= 0 && strlen($data->resign->approval_bendahara_id) > 0 )){
                                                                echo Form::button('<i class="fa fa-check"></i> Setujui Proses Penyerahan & Pelunasan', ['class' => 'btn blue-sharp col-md-3 linkto', 'data-href'=> URL::to("/simpanpinjam/anggota/approvalresigndua/".$personId), 'type' => 'button', 'data-swa-text' => 'Menyetujui Pengunduran Diri Angggota', 'style' => 'margin-right:5px;']);   
                                                            }
                                                        }
                                                    @endphp 
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
