@extends('layouts.master-sp')

@section('title', $title)

@push('styles')
<link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />

@endpush

@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Simpanan Pokok Anggota</h1>
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
                          <!-- BEGIN SAMPLE TABLE PORTLET-->
                          <div class="portlet light ">
                              <div class="portlet-title">
                                  <div class="caption">
                                      <i class="icon-book-open font-red"></i>
                                      <span class="caption-subject font-red sbold uppercase"> DAFTAR </span>
                                  </div>
                                  <div class="actions">
                                      <div class="btn-group btn-group-devided" data-toggle="buttons">
                                          <label class="btn btn-transparent red btn-outline btn-circle btn-sm active">
                                              <input type="radio" name="options" class="toggle" id="option1">Actions</label>
                                          <label class="btn btn-transparent red btn-outline btn-circle btn-sm">
                                              <input type="radio" name="options" class="toggle" id="option2">Settings</label>
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
                                                  <th> Saldo Simpanan Pokok </th>
                                                  <th> Aksi </th>
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
                                                  <td class="text-right"> 5.000.000 </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Detail</button> </td>
                                                  
                                              </tr>
                                              <tr>
                                                  <td> 2 </td>
                                                  <td> SCM-7 </td>
                                                  <td> Jacob </td>
                                                  <td> Malang, 01 Januari 1970 </td>
                                                  <td> 12345678901234567890 </td>
                                                  <td> Kantor Berantas </td>
                                                  <td> <span class="label label-sm label-success"> Aktif </span> </td>
                                                  <td class="text-right"> 5.000.000 </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Detail</button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 3 </td>
                                                  <td> KOP-9 </td>
                                                  <td> Larry </td>
                                                  <td> Malang, 01 Januari 1970 </td>
                                                  <td> 12345678901234567890 </td>
                                                  <td> Kantor Berantas </td>
                                                  <td> <span class="label label-sm label-success"> Aktif </span> </td>
                                                  <td class="text-right"> 6.000.000 </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Detail</button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 4 </td>
                                                  <td> KOP-8 </td>
                                                  <td> Sandy </td>
                                                  <td> Malang, 01 Januari 1970 </td>
                                                  <td> 12345678901234567890 </td>
                                                  <td> Kantor Berantas </td>
                                                  <td> <span class="label label-sm label-danger"> Pensiun </span> </td>
                                                  <td class="text-right"> 4.000.000 </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Detail</button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 5 </td>
                                                  <td> KOP-8 </td>
                                                  <td> Dani </td>
                                                  <td> Malang, 01 Januari 1970 </td>
                                                  <td> 12345678901234567890 </td>
                                                  <td> Kantor Berantas </td>
                                                  <td> <span class="label label-sm label-warning"> Non-Aktif </span> </td>
                                                  <td class="text-right"> 5.000.000 </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Detail</button> </td>
                                              </tr>

                                              <tr>
                                                  <td> 6 </td>
                                                  <td> KOP-8 </td>
                                                  <td> Rama </td>
                                                  <td> Malang, 01 Januari 1970 </td>
                                                  <td> 12345678901234567890 </td>
                                                  <td> Kantor Berantas </td>
                                                  <td><span class="label label-sm label-success"> Aktif </span></td>
                                                  <td class="text-right"> 5.000.000 </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Detail</button> </td>
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
