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
    <script src="{{ assets('pages/scripts/SimpanPinjam/index.kalkulasi.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Proses Kalkulasi Per-Periode</h1>
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
                                        {{--  <button type="button" id="PostingKalkulasi" class="btn red-haze">Posting Kalulkasi</button>  --}}
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
                                    <div class="caption font-dark">
                                        <i class="icon-calculator font-red"></i>
                                    </div>
                                    <div class="actions">
                                        <div class="btn-group btn-group-devided" data-toggle="buttons">
                                            <button id="prosesExport" class="btn green-seagreen btn-sm"> <i class="fa fa-file-excel-o"></i> Export Excel</button>
                                            <button id="prosesPenerimaan" class="btn blue btn-sm"> <i class="fa fa-exchange"></i> Prosess Penerimaan</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="portlet-body">
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
                                                <th> NIAK </th>
                                                <th> NAMA </th>
                                                <th> Perusahaan </th>
                                                <th width="10%"> Jenis Anggota </th>
                                                <th style="width: 5%;"> Status Anggota </th>
                                                <th> Simpanan Pokok </th>
                                                <th> Simpanan Wajib </th>
                                                <th> Pinjaman Koperasi </th>
                                                <th> Pinjaman Elektronik </th>
                                                <th> Hutang Toko </th>
                                                <th> Total </th>
                                                <th> Status </th>
                                            </tr>
                                        </thead>
                                       
                                        <tbody>
                                            @foreach($listKalkulasi as $row)
                                            <tr class="odd gradeX">
                                                <td>
                                                    @if($row->payment_status <> 1)
                                                    <label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                                                        <input type="checkbox" class="checkboxes" name="datachecked[]" value="{{ $row->person_id }}" />
                                                        <span></span>
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $row->niak }} </td>
                                                <td> {{ $row->first_name.' '.$row->last_name }} </td>
                                                <td> {{ $row->company_name }} </td>
                                                <td> {{ $row->member_type }} </td>
                                                <td> {{ $row->member_status }} </td>
                                                <td class="text-right"> {{ toRp($row->simpanan_pokok) }} </td>
                                                <td class="text-right"> {{ toRp($row->simpanan_wajib) }} </td>
                                                <td class="text-right"> {{ toRp($row->pinjaman_pokok + $row->bunga_pinjaman) }} </td>
                                                <td class="text-right"> {{ toRp($row->pokok_elektronik + $row->bunga_elektronik) }} </td>
                                                <td class="text-right"> {{ toRp($row->hutang_toko) }} </td>
                                                <td class="text-right"> {{ toRp($row->simpanan_pokok + $row->simpanan_wajib + $row->pinjaman_pokok + $row->bunga_pinjaman + $row->pokok_elektronik + $row->bunga_elektronik + $row->hutang_toko) }} </td>
                                                <td class="text-center">
                                                    @if($row->payment_status <> 1)
                                                        <span class="badge badge-warning"> Belum Terbayar </span>
                                                    @else
                                                        <span class="badge badge-success"> Terbayar </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                                <th> </th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    {{ Form::close() }}
                                </div>
                            </div>
                            <!-- END EXAMPLE TABLE PORTLET-->
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
                <h4 class="modal-title">Proses Menerimaan</h4>
            </div>
            <div class="modal-body"> 
                {{ Form::open(['url' => '#', 'id'=>'actionProcess','files' => 'true','enctype'=>'multipart/form-data']) }}
                    <input type="hidden" name="uploadPenerimaanPeriode" id="uploadPenerimaanPeriode" >
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
