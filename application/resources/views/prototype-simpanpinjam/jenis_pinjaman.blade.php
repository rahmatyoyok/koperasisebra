@extends('layouts.master-sp')

@section('title', 'Konfigurasi Jenis Pinjaman')

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
                          <!-- BEGIN SAMPLE TABLE PORTLET-->
                          <div class="portlet light ">
                              <div class="portlet-title">
                                  <div class="caption">
                                      <i class="icon-book-open font-red"></i>
                                      <span class="caption-subject font-red sbold uppercase"> DAFTAR JENIS PINJAMAN</span>
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
                                                  <th> Kode Jenis Pinjaman </th>
                                                  <th> Nama </th>
                                                  <th> % Bunga </th>
                                                  <th> Tenor </th>
                                                  <th> Model Bunga </th>
                                                  <th> Aksi </th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              <tr>
                                                  <td> 1 </td>
                                                  <td> Flat-Hari </td>
                                                  <td> FLAT HARIAN </td>
                                                  <td> 0.028 </td>
                                                  <td> 30 </td>
                                                  <td> BUNGA / POKOK PINJAMAN </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Ubah</button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 2 </td>
                                                  <td> Mingguan </td>
                                                  <td> MINGGUAN </td>
                                                  <td> 0.21 </td>
                                                  <td> 24 </td>
                                                  <td> BUNGA / POKOK ANGSURAN </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Ubah</button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 3 </td>
                                                  <td> Bulanan </td>
                                                  <td> BULANAN </td>
                                                  <td> 0.84 </td>
                                                  <td> 12 </td>
                                                  <td> BUNGA / POKOK ANGSURAN </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Ubah</button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 4 </td>
                                                  <td> Flat-Minggu </td>
                                                  <td> FLAT MINGGUAN </td>
                                                  <td> 0.021 </td>
                                                  <td> 24 </td>
                                                  <td> BUNGA / POKOK PINJAMAN </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Ubah</button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 5 </td>
                                                  <td> Flat-Bulanan </td>
                                                  <td> FLAT BULANAN </td>
                                                  <td> 10.08 </td>
                                                  <td> 12 </td>
                                                  <td> BUNGA / POKOK PINJAMAN </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Ubah</button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 6 </td>
                                                  <td> Menurun </td>
                                                  <td> MENURUN </td>
                                                  <td> 0.84 </td>
                                                  <td> 12 </td>
                                                  <td> BUNGA / SISA POKOK </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Ubah</button> </td>
                                              </tr>
                                              <tr>
                                                  <td> 7 </td>
                                                  <td> Menurun - %Tahun </td>
                                                  <td> BUNGA MENURUN </td>
                                                  <td> 13.25 </td>
                                                  <td> 12 </td>
                                                  <td> BUNGA / SISA POKOK / TAHUN </td>
                                                  <td class="text-center"> <button type="button" class="btn blue btn-xs"> <span class="fa fa-search-plus"> </span> Ubah</button> </td>
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
