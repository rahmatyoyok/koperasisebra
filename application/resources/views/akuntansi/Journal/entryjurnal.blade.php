@extends('layouts.master-ak')

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

        input.notbalance{
            background-color:#e7505a !important;
            color:white !important;
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
    <script src="{{assets('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery.masknumber.min.js') }}" type="text/javascript"></script>
    <script src="{{assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
    </script>
    <script src="{{ assets('pages/scripts/akuntansi/jurnal.formentrytambah.js') }}" type="text/javascript"></script>
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush

@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Form Jurnal {{ $jenisjurnal['singkat'] }}</h1>
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
                                  {!! Form::open(['url' => 'akuntansi/jurnal/'.$actionUrl, 'class' => 'form-horizontal', 'method' => 'POST', 'id'=>'formEntryJurnal']) !!}
                                  {!! Form::hidden('tr_type', $jenisjurnal['singkat']) !!}
                                    <div class="form-body">
                                          
                                        <div class="row">
                                            <div class="col-lg-6">
                                                
                                                @if(is_null($currentDivisi))
                                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('division') ? 'has-error' : ''}}">
                                                            {!! Form::label('division', 'Divisi', ['class' => 'control-label col-md-4'] ) !!}
                                                        <div class="col-md-3">
                                                            {!! Form::select('division', $listDivisi, $data->division, ['class' => 'form-control input-sm uppercase']) !!}
                                                            <div class="form-control-focus"> </div><span class="help-block"></span>
                                                        </div>
                                                    </div>
                                                @endif

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tr_type_id') ? 'has-error' : ''}}">
                                                        {!! Form::label('tr_type_id', 'Jenis Transaksi', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-3">
                                                        {!! Form::select('tr_type_id', $tr_type_id, $data->transaction_type_id, ['class' => 'form-control input-sm uppercase']) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>

                                                {{--  <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('journal_no') ? 'has-error' : ''}}">
                                                        {!! Form::label('journal_no', 'Nomor Jurnal', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('journal_no', '', ['class' => 'form-control input-sm uppercase','disabled' =>'disabled']) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>  --}}

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('ref_no') ? 'has-error' : ''}}">
                                                        {!! Form::label('reff_no', 'Nomor Refrensi', ['class' => 'control-label col-md-4'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('reff_no', $data->reff_no, ['class' => 'form-control input-sm uppercase']) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('tr_date') ? 'has-error' : ''}}">
                                                    {!! Form::label('tr_date', 'Tanggal Transaksi', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                <div class="col-md-3">
                                                    <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                        {!! Form::text('tr_date', $def_date, ['class' => 'form-control'] ) !!}
                                                        <div class="form-control-focus"> </div>
                                                    </div>
                                                    <div class="form-control-focus"> </div><span class="help-block"></span>
                                                </div>
                                            </div>

                                            </div>

                                            <div class="col-lg-6">
                                                {{--  <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('posting_no') ? 'has-error' : ''}}">
                                                        {!! Form::label('posting_no', 'Nomor Posting', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::text('posting_no', '', ['class' => 'form-control input-sm', 'disabled' => 'disabled'] ) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>  --}}

                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('desc') ? 'has-error' : ''}}">
                                                        {!! Form::label('desc', 'Keterangan', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        {!! Form::textarea('desc', $data->desc, ['class' => 'form-control input-sm', 'rows' => 4] ) !!}
                                                        <div class="form-control-focus"> </div><span class="help-block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-12">
                                                <h3 class="form-section">Detail Transaksi
                                                    <a class="btn green-jungle" id="addData"><i class="fa fa-plus"></i></a>
                                                </h3>

                                                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                                    <thead>
                                                        <tr>
                                                            <th width="4%">No</th>
                                                            <th width="25%">Buku Besar</th>
                                                            <th>Keterangan</th>
                                                            <th width="12%">Debit</th>
                                                            <th width="12%">Kredit</th>
                                                            <th width="4%" class="text-center">Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="listTable">
                                                        @if(count($data_detail) > 0)
                                                            @foreach ($data_detail as $item)
                                                                <tr>
                                                                    <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::text('seq[]', $item->seq, ['class' => 'form-control input-sm'] ) !!}</div></td>
                                                                    <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::select('coa_id[]', array($item->coa_id=>$item->coa_code), $item->coa_id,['class' => 'form-control input-sm inputCoa'] ) !!}</div></td>
                                                                    <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::hidden('coa_code[]',$item->coa_code) !!}{!! Form::text('detail_desc[]', $item->coas->desc, ['class' => 'form-control input-sm','disabled'=>'disabled'] ) !!}</div></td>
                                                                    <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::text('f_debit[]', $item->debit, ['class' => 'form-control input-sm rupiah'] ) !!}</div></td>
                                                                    <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::text('f_kredit[]', $item->kredit, ['class' => 'form-control input-sm rupiah'] ) !!}</div></td>
                                                                    <td class="text-center"><a href="javascript:;" class="btn btn-block red delete" data-number="1"><i class="fa fa-trash"></i> Hapus</a></td>
                                                                </tr>
                                                            @endforeach
                                                        
                                                        @elseif(count($data_detail) <= 0)
                                                            <tr>
                                                                <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::text('seq[]', '1', ['class' => 'form-control input-sm'] ) !!}</div></td>
                                                                <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::select('coa_id[]', array(),'',['class' => 'form-control input-sm inputCoa'] ) !!}</div></td>
                                                                <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::hidden('coa_code[]','') !!}{!! Form::text('detail_desc[]', '', ['class' => 'form-control input-sm'] ) !!}</div></td>
                                                                <td ><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::text('f_debit[]', '', ['class' => 'form-control input-sm rupiah'] ) !!}</div></td>
                                                                <td ><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::text('f_kredit[]', '', ['class' => 'form-control input-sm rupiah'] ) !!}</div></td>
                                                                <td class="text-center"><a href="javascript:;" class="btn btn-block red delete" data-number="1"><i class="fa fa-trash"></i> Hapus</a></td>
                                                            </tr>
                                                            <tr>
                                                                <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::text('seq[]', '2', ['class' => 'form-control input-sm'] ) !!}</div></td>
                                                                <td ><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::select('coa_id[]', array(),'',['class' => 'form-control input-sm inputCoa'] ) !!}</div></td>
                                                                <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::hidden('coa_code[]','') !!}{!! Form::text('detail_desc[]', '', ['class' => 'form-control input-sm'] ) !!}</div></td>
                                                                <td ><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::text('f_debit[]', '', ['class' => 'form-control input-sm rupiah'] ) !!}</div></td>
                                                                <td ><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::text('f_kredit[]', '', ['class' => 'form-control input-sm rupiah'] ) !!}</div></td>
                                                                <td class="text-center"><a href="javascript:;" class="btn btn-block red delete" data-number="1"><i class="fa fa-trash"></i> Hapus</a></td>
                                                            </tr>
                                                        @endif
                                                        
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3">Total</td>
                                                            <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::text('total_debit', '', ['class' => 'form-control input-sm', 'disabled' => 'disabled'] ) !!}</div></td>
                                                            <td><div class="form-group form-md-line-input" style="margin:0px 5px;padding-top:0px">{!! Form::text('total_kredit', '', ['class' => 'form-control input-sm', 'disabled' => 'disabled'] ) !!}</div></td>
                                                            <td><span id="BalanceStatus"><b></b></span></td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                          
                                          
                                        

                                    </div>
                                    <div class="form-actions">
                                            <div class="row">
                                                    <div class="col-md-3"></div>
                                                    {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Simpan '.$jenisjurnal['lengkap'], 'style' => 'margin-right:5px;']) !!}
                                                    <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze" style="margin-left:5px;"><i class="fa fa-close"></i> Batal</a>
                                                    <div class="col-md-3"></div>
                                              </div>
                                      </div>
                                      {{ Form::close() }}
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
