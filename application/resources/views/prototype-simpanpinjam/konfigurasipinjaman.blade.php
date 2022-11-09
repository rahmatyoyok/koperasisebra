@extends('layouts.master-sp')

@section('title', $title)

@push('styles')

<link href="{{ assets('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" type="text/css" />

@endpush


@push('plugins')
<script src="{{ assets('global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/ckeditor/ckeditor.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/bootstrap-markdown/lib/markdown.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/bootstrap-markdown/js/bootstrap-markdown.js') }}" type="text/javascript"></script>
@endpush


@push('scripts')
<script src="{{ assets('pages/scripts/form-validation-md.js') }}" type="text/javascript"></script>
@endpush


@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Konfigurasi Pinjaman</h1>
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
                  <!--div class="portlet-title">
                      <div class="caption">
                          <i class="icon-bubble font-dark hide"></i>
                          <span class="caption-subject font-hide bold uppercase">Daftar Anggota</span>
                      </div>

                  </div-->
                  <div class="portlet-body">
                      <div class="row">
                        <div class="col-md-12">
                        <!-- BEGIN VALIDATION STATES-->
                          <div class="portlet light portlet-fit portlet-form " >
                              
                              <div class="portlet-body">
                                  <div class="portlet light " style="margin-bottom: 0px!important">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-gears font-red"></i>
                                            <span class="caption-subject font-red sbold uppercase">  </span>
                                        </div>
                                        <div class="actions">
                                            <button class="btn yellow-casablanca btn-sm active"> <i class="fa fa-pencil"></i> Ubah</button>
                                        </div>
                                    </div>
                                  </div>


                                  <!-- BEGIN FORM-->
                                  <form action="#" class="form-horizontal" id="form_sample_1">                                      
                                    <div class="form-body">
                                          <div class="alert alert-danger display-hide">
                                              <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                          <div class="alert alert-success display-hide">
                                              <button class="close" data-close="alert"></button> Your form validation is successful! </div>

                                      <div class="row">
                                        <div class="col-md-6">

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-12"  style="text-align:left;padding-left: 40px"> <H4>Batas Pinjaman</H4> </label>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-4"> Batas Pinjaman </label>
                                              <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calculator"></i>
                                                    </span>
                                                    <input type="text" name="f_default_simpanan_pokok" class="form-control text-right" placeholder="60"> 
                                                    <span class="input-group-addon">
                                                        % (Gaji)
                                                    </span>
                                                </div> 
                                              </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-12"  style="text-align:left;padding-left: 40px"> <H4>Biaya Administrasi</H4> </label>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-4"> Biaya Administrasi </label>
                                              <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calculator"></i>
                                                    </span>
                                                    <input type="text" class="form-control text-right" name="email2" placeholder="1.0">
                                                    <span class="input-group-addon">
                                                        % (Pinjaman)
                                                    </span>
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-money"></i>
                                                    </span>
                                                    <input type="text" class="form-control text-right" name="email2" placeholder="100.000">
                                                    <span class="input-group-addon">
                                                        Jika 0%
                                                    </span>
                                                </div>
                                              </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-4"> Biaya Provisi </label>
                                              <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calculator"></i>
                                                    </span>
                                                    <input type="text" class="form-control text-right" name="email2" placeholder="1.0">
                                                    <span class="input-group-addon">
                                                        % (Pinjaman)
                                                    </span>
                                                </div>
                                              </div>
                                              <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-money"></i>
                                                    </span>
                                                    <input type="text" class="form-control text-right" name="email2" placeholder="100.000">
                                                    <span class="input-group-addon">
                                                        Jika 0%
                                                    </span>
                                                </div>
                                              </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-4"> Resiko / Daperma </label>
                                              <div class="col-md-4">
                                                <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-calculator"></i>
                                                    </span>
                                                    <input type="text" class="form-control text-right" name="email2" placeholder="2.0">
                                                    <span class="input-group-addon">
                                                        % (Pinjaman)
                                                    </span>
                                                </div>
                                              </div>
                                              </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-4"> Biaya Materai </label>
                                              <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-money"></i> Rp.
                                                    </span>
                                                    <input type="text" name="f_default_simpanan_pokok" class="form-control text-right" placeholder="100.000"> 
                                                </div> 
                                              </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-4"> Biaya Lain-lain </label>
                                              <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-money"></i> Rp.
                                                    </span>
                                                    <input type="text" name="f_default_simpanan_pokok" class="form-control text-right" placeholder="100.000"> 
                                                </div> 
                                              </div>
                                          </div>


                                        </div>
                                        <div class="col-md-6">
                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-12"  style="text-align:left;padding-left: 40px"> <H4>Angsuran Terlambat</H4> </label>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-4"> Denda Cicilan </label>
                                              <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon"><i class="fa fa-calculator"></i></span>
                                                    <input type="text" class="form-control text-right" name="email2" placeholder="1.0">
                                                    <span class="input-group-addon">% (Pinjaman) / Hari</span>
                                                </div>
                                              </div>
                                          </div>



                                        </div>
                                      </div>
                                         

                                      </div>
                                      <div class="form-actions">
                                          <div class="row">
                                              <div class="col-md-offset-3 col-md-9 hide">
                                                  <!-- <button type="submit" class="btn green">Simpan</button> -->
                                                  <!-- <button type="button" class="btn default">Batal</button> -->
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
