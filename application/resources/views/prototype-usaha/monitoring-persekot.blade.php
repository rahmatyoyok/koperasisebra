@extends('layouts.master')

@section('title', 'Monitoring Persekot')

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
            <h1>Monitoring Persekot
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
                                                        <th>Nomor</th>
                                                        <th class="none">Dari</th>
                                                        <th>Proses</th>
                                                        <th class="none">Tgl Pengajuan</th>
                                                        <th class="none">Jatuh Tempo</th>
                                                        <th>Jumlah</th>
                                                        <th>Status</th>
                                                        <th class="text-center" width="150">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                      <td>01</td>
                                                      <td>SPV Senior Sipil & LK3 - Sigit Yuwono</td>
                                                      <td>Persekot</td>
                                                      <td>10 Agt 2019</td>
                                                      <td>20 Agt 2019</td>
                                                      <td>Rp 200.000,00</td>
                                                      <td>Belum Terbayar</td>
                                                      <td>
                                                        <a  class="btn btn-xs purple-sharp tooltips" title="Edit">Edit</a>
                                                        <a  class="btn btn-xs purple-sharp tooltips" title="Edit">Verifikasi</a>
                                                      </td>
                                                    </tr>
                                                    <tr>
                                                      <td>02</td>
                                                      <td>SPV Senior Umum & CSR - Erwan Prasetya</td>
                                                      <td>Persekot</td>
                                                      <td>20 Agt 2019</td>
                                                      <td>25 Agt 2019</td>
                                                      <td>Rp 500.000,00</td>
                                                      <td>Terbayar</td>
                                                      <td>
                                                        <a  class="btn btn-xs purple-sharp tooltips" title="Edit">Edit</a>
                                                        <a  class="btn btn-xs purple-sharp tooltips" title="Edit">Verifikasi</a>
                                                      </td>
                                                    </tr>
                                                    <tr>
                                                      <td>04</td>
                                                      <td>SPV Senior Sipil & LK3 - Sigit Yuwono</td>
                                                      <td>Persekot</td>
                                                      <td>12 Agt 2019</td>
                                                      <td>22 Agt 2019</td>
                                                      <td>Rp 200.000,00</td>
                                                      <td>Belum Terbayar</td>
                                                      <td>
                                                        <a  class="btn btn-xs purple-sharp tooltips" title="Edit">Edit</a>
                                                        <a  class="btn btn-xs purple-sharp tooltips" title="Edit">Verifikasi</a>
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
