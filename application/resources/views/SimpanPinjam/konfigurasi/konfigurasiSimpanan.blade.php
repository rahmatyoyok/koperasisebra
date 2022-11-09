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
  <script src="{{ assets('global/plugins/bootstrap-markdown/lib/markdown.js') }}" type="text/javascript"></script>
  <script src="{{ assets('global/plugins/bootstrap-markdown/js/bootstrap-markdown.js') }}" type="text/javascript"></script>
  <script src="{{assets('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
  <script src="{{ assets('global/plugins/jquery.masknumber.min.js') }}" type="text/javascript"></script>
  <script src="{{assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>

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
            <h1>{{$title}}</h1>
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
                              
                          <form method="POST" action="{{ url('simpanpinjam/simpanKonfigurasiSimpanan') }}" class="form-horizontal" id="form_sample_1">
                          {{ csrf_field() }}
                          <input type="hidden" id="simpananId" name="simpananId" value="{{ $data->id }}">
                                <div class="portlet-body">
                                  <div class="portlet light ">
                                    <div class="portlet-title">
                                        <div class="caption">
                                            <i class="fa fa-gears font-red"></i>
                                            <span class="caption-subject font-red sbold uppercase">  </span>
                                        </div>
                                        <div class="actions">
                                            <button type="submit" class="btn yellow-casablanca btn-sm active"> <i class="fa fa-pencil"></i> Ubah</button>
                                        </div>                                        
                                </div>
                                <div class="col-md-12">
                                  @if (session('status'))
                                      <div class="alert alert-success">
                                          {{ session('status') }}
                                      </div>
                                  @endif

                                  @if (count($errors) > 0)
                                  <div class="alert alert-danger">
                                      <ul>
                                          @foreach ($errors->all() as $error)
                                              <li>{{ $error }}</li>
                                          @endforeach
                                      </ul>
                                  </div>
                                  @endif
                                </div>
                                 

                                  <!-- BEGIN FORM-->
                                                                        
                                    <div class="form-body">                                         

                                      <div class="row">
                                        <div class="col-md-6">                                           
                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-4"> Simpanan Pokok </label>
                                              <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-money"></i>
                                                    </span>
                                                    <input type="text" required name="f_default_simpanan_pokok" class="form-control rupiah edited" value="{{ toRp($data->simpanan_pokok) }}" placeholder="{{number_format($data->simpanan_pokok, 0,',','.')}}">                                                     
                                                </div> 
                                              </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-4"> Simpanan Wajib (/Bln)</label>
                                              <div class="col-md-4">
                                                <div class="input-group">
                                                    <span class="input-group-addon">
                                                        <i class="fa fa-money"></i>
                                                    </span>
                                                    <input type="text" required name="f_default_simpanan_wajib" class="form-control text-right rupiah" value="{{ toRp($data->simpanan_wajib) }}" placeholder="{{number_format($data->simpanan_wajib, 0,',','.')}}"> 
                                                </div> 
                                              </div>
                                          </div>

                                          <div class="form-group form-md-line-input">
                                              <label class="control-label col-md-4"> Bunga Investasi (/Th) </label>
                                              <div class="col-md-2">
                                                <div class="input-group">
                                                    
                                                    <input type="text" required name="f_default_bunga_investasi" class="form-control text-right" value="{{ $data->bunga_investasi*100 }}"  placeholder="{{$data->bunga_investasi}}"> 
                                                    <span class="input-group-addon">
                                                        %
                                                    </span>
                                                </div> 
                                              </div>
                                          </div>

                                        </div>
                                        <div class="col-md-6">


                                        </div>
                                      </div>                                         

                                      </div>
                                      <div class="form-actions">
                                          <div class="row">
                                              <div class="col-md-offset-3 col-md-9 hide">
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
<script>
   if ($("#contact_us").length > 0) {    
    $("#contact_us").validate({
      
    rules: {
      name: {
        required: true,
        maxlength: 50
      }   
    },
    messages: {
        
      name: {
        required: "Please enter name",
        maxlength: "Your last name maxlength should be 50 characters long."
      },
      mobile_number: {
        required: "Please enter contact number",
        digits: "Please enter only numbers",
        minlength: "The contact number should be 10 digits",
        maxlength: "The contact number should be 12 digits",
      }
               
    }
    
  })
}
</script>

@endsection
