@extends('layouts.master')

@section('title', 'Monitoring Work Order')

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
            <h1>Monitoring Work Order
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

                            <div class="form-horizontal">

                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                      <th class="none">Client</th>
                                                        <th class="none">Jenis</th>
                                                        <th class="none">Lokasi</th>
                                                        <th class="none">Pekerjaan</th>
                                                        <th>Nominal</th>
                                                        <th class="none">Status</th>
                                                        <th class="text-center" width="150">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                      <td>PT. PJB</td>
                                                      <td>Material</td>
                                                      <td>GUDANG SUTAMI</td>
                                                      <td>Pengadaan Pakaian Wear Pack</td>
                                                      <td>20.325.000</td>
                                                      <td>Belum Lunas</td>
                                                      <td>
                                                        <a  class="btn btn-xs purple-sharp tooltips" title="Edit">Detail</a>
                                                        <a  class="btn btn-xs green tooltips" title="Edit">Pembayaran</a>
                                                      </td>
                                                    </tr>
                                                    <tr>
                                                      <td>PT. PJB</td>
                                                      <td>Material</td>
                                                      <td>GUDANG SUTAMI</td>
                                                      <td>Pengadaan Pakaian Wear Pack</td>
                                                      <td>20.325.000</td>
                                                      <td>Lunas</td>
                                                      <td>
                                                        <a  class="btn btn-xs purple-sharp tooltips" title="Edit">Detail</a>
                                                      </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

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
