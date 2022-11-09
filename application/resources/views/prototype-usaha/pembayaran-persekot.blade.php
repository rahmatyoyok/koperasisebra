@extends('layouts.master')

@section('title', 'Pembayaran Persekot')

@push('styles')
<link href="{{ assets('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet')}}" type="text/css" />

@endpush

@section('content')

<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Pembayaran Persekot - No : 80
            </h1>
        </div>
    </div>
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container">
        <!-- BEGIN PAGE CONTENT INNER -->
        <div class="page-content-inner">
            <div class="mt-content-body">
				<div class="row">
				    <div class="col-lg-12 col-xs-12 col-sm-12">
				        <div class="portlet light bordered">
				            <div class="portlet-body form">

                                {!! Form::open(['class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}

                                <div class="form-body">

                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label col-md-3 bold">Nomor:</label>
                                              <div class="col-md-9">
                                                  <p class="form-control-static"> 80 </p>
                                              </div>
                                          </div>
                                      </div>
                                      <!--/span-->
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label col-md-3 bold">Dari:</label>
                                              <div class="col-md-9">
                                                  <p class="form-control-static"> SPV Senior Sipil & LK3 - Sigit Yuwono </p>
                                              </div>
                                          </div>
                                      </div>
                                      <!--/span-->
                                  </div>

                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label col-md-3 bold">Proses:</label>
                                              <div class="col-md-9">
                                                  <p class="form-control-static"> Persekot </p>
                                              </div>
                                          </div>
                                      </div>
                                      <!--/span-->
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label col-md-3 bold">Bank Transfer:</label>
                                              <div class="col-md-9">
                                                  <p class="form-control-static"> Budi - BCA - 02019201920</p>
                                              </div>
                                          </div>
                                      </div>
                                      <!--/span-->
                                  </div>
                                  <div class="row">
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label col-md-3 bold">Jumlah:</label>
                                              <div class="col-md-9">
                                                  <p class="form-control-static"> Rp 300.000.000,00 </p>
                                              </div>
                                          </div>
                                      </div>
                                      <!--/span-->
                                      <div class="col-md-6">
                                          <div class="form-group">
                                              <label class="control-label col-md-3 bold">Uraian:</label>
                                              <div class="col-md-9">
                                                  <p class="form-control-static"> persekot biaya selam support GI PLTA Sutami </p>
                                              </div>
                                          </div>
                                      </div>
                                      <!--/span-->
                                  </div>

                                  <h3>Pembayaran</h3>
                                  <div class="row">
                                      <div class="col-md-4">
                                          <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                              {!! Form::label('perihal', 'Nominal', ['class' => 'control-label col-md-3'] ) !!}
                                              <div class="col-md-9">
                                                  {!! Form::text('perihal', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                                  <div class="form-control-focus"> </div>
                                                  <span class="help-block"></span>
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                        <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('perihal') ? 'has-error' : ''}}">
                                            {!! Form::label('perihal', 'Catatan', ['class' => 'control-label col-md-3'] ) !!}
                                            <div class="col-md-9">
                                                {!! Form::text('perihal', null, ['class' => 'form-control', 'id' => 'Nomor', 'autofocus'] ) !!}
                                                <div class="form-control-focus"> </div>
                                                <span class="help-block"></span>
                                            </div>
                                        </div>
                                      </div>
                                </div>

                                <div class="form-actions">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3"></div>
                                            {!! Form::button('<i class="fa fa-check"></i> Verifikasi', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan Arsip']) !!}
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


@push('plugins')
<script src="{{assets('global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
@endpush

@push('scripts')
<script>
function PreviewImage() {
                pdffile=document.getElementById("uploadPDF").files[0];
                pdffile_url=URL.createObjectURL(pdffile);
                $('#viewer').attr('src',pdffile_url);
            }
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
