@extends('layouts.master-sp')

@section('title', $title)

@push('styles')
    <link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ assets('global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->
@endpush


@push('plugins')
    <script src="{{ assets('global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
    var BaseUrl = '{{ url('/') }}';
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    {{--  <script src="{{ assets('pages/scripts/SimpanPinjam/simpananwajib.index.js') }}" type="text/javascript"></script>  --}}
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Proses Simpanan Wajib Per-Periode</h1>
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
                  <div class="portlet-title">
                      <div class="caption">
                          <i class="icon-bubble font-dark hide"></i>
                          <span class="caption-subject font-hide bold uppercase">Daftar</span>
                      </div>

                  </div>
                  <div class="portlet-body">

                      <div class="row">
                        <div class="col-md-6">
                          <form action="#" id="form_sample_3" class="form-horizontal">
                            <div class="form-body">
                                <div class="form-group form-md-line-input">
                                    <label class="control-label col-md-2">Periode</label>
                                    <div class="col-md-4">
                                      <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
                                          <span class="input-group-addon">
                                              <i class="fa fa-calendar"></i>
                                          </span>
                                          <input type="text" class="form-control text-right" name="tanggallahir" name="datepicker" value="Juli 2019" />
                                          <div class="form-control-focus"> </div>
                                      </div>   
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="submit" class="btn green">Proses Kalulkasi</button>
                                        <button type="button" class="btn blue">Search</button>
                                    </div>
                                </div>
                            </div>
                          </form>
                        </div>
                      </div>

                      <div class="row">
                        <div class="col-md-12">
                          <!-- BEGIN SAMPLE TABLE PORTLET-->
                          <div class="portlet light ">
                              <div class="portlet-title">
                                  <div class="caption">
                                      <i class="icon-book-open font-red"></i>
                                      <span class="caption-subject font-red sbold uppercase"></span>
                                  </div>
                                  <div class="actions">
                                      <div class="btn-group btn-group-devided" data-toggle="buttons">
                                          <label class="btn btn-transparent green-seagreen btn-outline btn-circle btn-sm active">
                                              <input type="radio" name="options" class="toggle" id="option1"> <i class="fa fa-file-excel-o"></i> Export Excel</label>
                                          <label class="btn btn-transparent blue btn-outline btn-circle btn-sm">
                                              <input type="radio" name="options" class="toggle" id="option1"> <i class="fa fa-cloud-upload"></i> Import Data Transfer</label>
                                      </div>
                                  </div>
                              </div>
                              <div class="portlet-body">
                                  <div class="table-scrollable">
                                      <table class="table table-hover table-light">
                                          <thead>
                                              <tr>
                                                  <th> # </th>
                                                  <th> NIAK </th>
                                                  <th> Nama </th>
                                                  <th> Tempat, Tanggal Lahir </th>
                                                  <th> No. Identitas </th>
                                                  <th> Unit Kerja </th>
                                                  <th> Status Anggota </th>
                                                  <th> Nilai </th>
                                                  <th> Status </th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <tr>
                                                  <td> 1 </td>
                                                  <td> KOP-7 </td>
                                                  <td> Mark </td>
                                                  <td> Malang, 01 Januari 1970 </td>
                                                  <td> 12345678901234567890 </td>
                                                  <td> Kantor Berantas </td>
                                                  <td> <span class="label label-sm label-success"> Aktif </span> </td>
                                                  <td class="text-right"> 100.000 </td>
                                                  <td class="text-center"> <button type="button" class="btn btn-warning btn-xs"> <span class="fa fa-search-plus"> </span> Gagal Bayar </button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 2 </td>
                                                  <td> SCM-7 </td>
                                                  <td> Jacob </td>
                                                  <td> Malang, 01 Januari 1970 </td>
                                                  <td> 12345678901234567890 </td>
                                                  <td> Kantor Berantas </td>
                                                  <td> <span class="label label-sm label-success"> Aktif </span> </td>
                                                  <td class="text-right"> 100.000 </td>
                                                  <td class="text-center"> <button type="button" class="btn btn-warning btn-xs"> <span class="fa fa-search-plus"> </span> Gagal Bayar </button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 3 </td>
                                                  <td> KOP-9 </td>
                                                  <td> Larry </td>
                                                  <td> Malang, 01 Januari 1970 </td>
                                                  <td> 12345678901234567890 </td>
                                                  <td> Kantor Berantas </td>
                                                  <td> <span class="label label-sm label-success"> Aktif </span> </td>
                                                  <td class="text-right"> 100.000 </td>
                                                  <td class="text-center"> <button type="button" class="btn btn-warning btn-xs"> <span class="fa fa-search-plus"> </span> Gagal Bayar </button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 4 </td>
                                                  <td> KOP-8 </td>
                                                  <td> Sandy </td>
                                                  <td> Malang, 01 Januari 1970 </td>
                                                  <td> 12345678901234567890 </td>
                                                  <td> Kantor Berantas </td>
                                                  <td> <span class="label label-sm label-danger"> Pensiun </span> </td>
                                                  <td class="text-right"> 100.000 </td>
                                                  <td class="text-center bold"> Terbayar </td>
                                              </tr>
                                              <tr>
                                                  <td> 5 </td>
                                                  <td> KOP-8 </td>
                                                  <td> Dani </td>
                                                  <td> Malang, 01 Januari 1970 </td>
                                                  <td> 12345678901234567890 </td>
                                                  <td> Kantor Berantas </td>
                                                  <td> <span class="label label-sm label-warning"> Non-Aktif </span> </td>
                                                  <td class="text-right"> 100.000 </td>
                                                  <td class="text-center bold"> Terbayar </td>
                                              </tr>

                                              <tr>
                                                  <td> 6 </td>
                                                  <td> KOP-8 </td>
                                                  <td> Rama </td>
                                                  <td> Malang, 01 Januari 1970 </td>
                                                  <td> 12345678901234567890 </td>
                                                  <td> Kantor Berantas </td>
                                                  <td><span class="label label-sm label-success"> Aktif </span></td>
                                                  <td class="text-right"> 150.000 </td>
                                                  <td class="text-center bold"> Terbayar </td>
                                              </tr>

                                          </tbody>
                                      </table>
                                  </div>
                              </div>
                          </div>
                          <!-- END SAMPLE TABLE PORTLET-->
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
