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
<script src="{{ assets('pages/scripts/form-validation.js') }}" type="text/javascript"></script>
@endpush


@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Tambah Investasi</h1>
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
                          <div class="portlet light portlet-fit portlet-form ">
                              
                              <div class="portlet-body">
                                  <!-- BEGIN FORM-->
                                  <form action="#" id="form_sample_3" class="form-horizontal">
                                      <div class="form-body">
                                          <div class="alert alert-danger display-hide">
                                              <button class="close" data-close="alert"></button> You have some form errors. Please check below. </div>
                                          <div class="alert alert-success display-hide">
                                              <button class="close" data-close="alert"></button> Your form validation is successful! </div>
                                          
                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-3">No. Reff</label>
                                              <div class="col-md-2">
                                                  <input type="text"  disabled name="noref" data-required="1" class="form-control" /> </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-3">Tanggal <span class="required"> * </span></label>
                                              <div class="col-md-4">
                                                  <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
                                                      <span class="input-group-addon">
                                                          <i class="fa fa-calendar"></i>
                                                      </span>
                                                      <input type="text" class="form-control" name="tanggallahir" name="datepicker" disbled/>
                                                      <div class="form-control-focus"> </div>
                                                  </div>
                                              </div>
                                          </div>
                                          
                                          
                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-3">NIAK
                                                  <span class="required"> * </span>
                                              </label>
                                              <div class="col-md-4">
                                                  <input type="text" name="name" data-required="1" class="form-control" /> </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-3">Nama Anggota
                                                  <span class="required"> * </span>
                                              </label>
                                              <div class="col-md-4">
                                                  <input type="text" name="name" data-required="1" class="form-control" disabled /> </div>
                                          </div>


                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-3">Unit Kerja
                                                  <span class="required"> * </span>
                                              </label>
                                              <div class="col-md-4">
                                                  <div class="input-group">
                                                      <span class="input-group-addon">
                                                          <i class="fa fa-map"></i>
                                                      </span>
                                                      <input type="text" name="f_Gaji" class="form-control" placeholder="Kantor Brantas" disabled/> 
                                                  </div>    
                                              </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-3">Nilai Investasi
                                                  <span class="required"> * </span>
                                              </label>
                                              <div class="col-md-4">
                                                  <div class="input-group">
                                                      <span class="input-group-addon">
                                                          <i class="fa fa-money"></i>
                                                      </span>
                                                      <input type="text" name="f_Gaji" class="form-control text-right" placeholder="5.000.000"> 
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-3">No. Reff Kas</label>
                                              <div class="col-md-2">
                                                  <input type="text"  disabled name="noref" data-required="1" class="form-control" /> </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-3">Cara Penerimaan</label>
                                              <div class="col-md-2">
                                                <select class="form-control select2me" name="options2">
                                                      <option value="Option 1" selected>Cash</option>
                                                      <option value="Option 2">Bank BCA</option>  
                                                      <option value="Option 3">Bank Mandiri</option>
                                                  </select>    
                                              </div>
                                          </div>                                          


                                      </div>
                                      <div class="form-actions">
                                          <div class="row">
                                              <div class="col-md-offset-3 col-md-9">
                                                  <button type="submit" class="btn green">Simpan</button>
                                                  <button type="button" class="btn default">Batal</button>
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
