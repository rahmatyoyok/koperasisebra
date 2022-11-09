@extends('layouts.master-sp')

@section('title', $title)

@push('styles')
    <link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />

    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ assets('global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css') }}" rel="stylesheet" type="text/css" />
    <!-- END PAGE LEVEL PLUGINS -->

    <style>
    .ui-datepicker-calendar {
        display: none;
    }
    
    .table thead tr th, .table tfoot tr th,  {
        font-size: 10px!important;
        font-weight: 600;
    }

    .table tbody tr td {
        font-size: 10px;
    }
    </style>
@endpush

@push('plugins')
    <script src="{{ assets('global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-datepicker/locales/bootstrap-datepicker.id.min.js') }}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
    var BaseUrl = '{{ url('/') }}';
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ assets('pages/scripts/SimpanPinjam/investasi.showbunga.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Bunga Investasi Anggota</h1>
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
                                          <input type="text" class="form-control text-right" name="periodekKalkulasi" value="{{ $currentMonth }}" />
                                          <div class="form-control-focus"> </div>
                                      </div>   
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <div class="row">
                                    <div class="col-md-offset-3 col-md-9">
                                        <button type="button" id="StartKalkulasi" class="btn green">Proses Kalulkasi</button>
                                        <button type="button" id="PostingKalkulasi" class="btn red-haze">Posting Kalulkasi</button>
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
                                            <label class="btn btn-transparent green-seagreen btn-outline btn-sm active" id="exportExcel">
                                              <i class="fa fa-file-excel-o"></i> Export Excel
                                            </label>
                                            <button id="prosesPenerimaan" class="btn blue btn-sm"> <i class="fa fa-exchange"></i> Prosess Penerimaan</button>
                                      </div>
                                  </div>
                              </div>
                              <div class="portlet-body">
                                  <div class="table-scrollable">
                                    {{ Form::open(['url' => '#', 'id'=>'actionProcess']) }}
                                    <table class="table table-striped table-bordered table-hover table-checkable order-column" id="sample_1_2" style="width:105%!important">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                        <input type="checkbox" class="group-checkable" data-set="#sample_1_2 .checkboxes" />
                                                        <span></span>
                                                    </label>
                                                </th>
                                                <th class="all"> NIAK </th>
                                                <th class="all"> Nama </th>
                                                <th class="all"> Unit Kerja </th>
                                                <th class="all"> Nomor Induk </th>
                                                <th class="all"> Jabatan </th>
                                                <th class=""> Jenis Anggota </th>
                                                <th class=""> Status Anggota </th>
                                                <th class=""> Saldo Investasi </th>
                                                <th class=""> Besaran Bunga Investasi </th>
                                                <th class=""> Bunga Investasi </th>
                                                <th class=""> Biaya Administrasi </th>
                                                <th class=""> Biaya Jasa Anggota </th>
                                                <th class=""> Jumlah Transfer </th>
                                                <th class=""> Status </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($listKalkulasi as $row)
                                            <tr class="odd gradeX">
                                                <td>
                                                    @if($row->status <> 2)
                                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                        <input type="checkbox" class="checkboxes" name="datachecked[]" value="{{ $row->person_id }}" />
                                                        <span></span>
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $row->niak }} </td>
                                                <td> {{ $row->first_name.' '.$row->last_name }} </td>
                                                <td> {{ $row->company_name }} </td>
                                                <td> {{ $row->nomor_induk }} </td>
                                                <td> {{ $row->jabatan }} </td>
                                                <td> {{ $row->member_type }} </td>
                                                <td> {{ $row->member_status }} </td>
                                                <td class="text-right"> {{ toRp($row->saldo) }} </td>
                                                <td class="text-right"> {{ toRp($row->bunga_investasi) }} </td>
                                                <td class="text-right"> {{ $row->pr_bunga_investasi }}% </td>
                                                <td class="text-right"> {{ toRp($row->biaya_administrasi) }} </td>
                                                <td class="text-right"> {{ toRp($row->biaya_pajak) }} </td>
                                                <td class="text-right"> {{ toRp($row->jumlah_transfer) }} </td>
                                                <td class="text-center">
                                                    @if($row->status <> 2)
                                                        <span class="badge {{ (($row->status == 0) ? 'badge-info' : 'badge-warning') }} "> {{ (($row->status == 0) ? 'Ter-Kalkulasi' : 'Belum Ditransfer') }} </span>
                                                    @else
                                                        <span class="badge badge-success"> Sudah Ditransfer </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                      </table>

                                    {{ Form::close() }}
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

<div class="modal fade bs-modal-sm" id="modalUploadPoenerimaan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Proses Transfer</h4>
            </div>
            <div class="modal-body"> 
                {{ Form::open(['url' => '#', 'id'=>'actionProcess','files' => 'true','enctype'=>'multipart/form-data']) }}
                    <input type="hidden" name="uploadTFPeriode" id="uploadTFPeriode" >
                    <div class="row">
                        <div class="col-md-12 verticalBorder">
                            
                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label class="control-label col-md-3">Dokumen List Excel:</label>
                                <div class="col-md-8">
                                    <input type="file" name="dokumen">
                                    <input type="hidden" name="id" value="">
                                </div>
                            </div>

                            <div class="form-group form-md-line-input form-md-floating-label">
                                <label class="control-label col-md-3">Dokumen Lampiran:</label>
                                <div class="col-md-8">
                                    <input type="file" name="dokumenLampiran">
                                    <input type="hidden" name="idDokumenLampiran" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                {{ Form::close() }}


            </div>
            <div class="modal-footer">
                <button type="button" class="btn red" data-dismiss="modal">Batal</button>
                <button type="button" id="btnUploadExcel" class="btn green">Proses Simpan</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endsection
