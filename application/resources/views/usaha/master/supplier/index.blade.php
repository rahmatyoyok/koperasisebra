@extends('layouts.master')

@section('title', 'Master Supplier')

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
            <h1>Master Supplier
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
                    <div class="portlet-title">
                        <div class="caption">
                            <span class="caption-subject bold uppercase">Data</span>
                        </div>
                        <div class="actions">
                            <a class="btn green-jungle col-md-12" href="{{ url('usaha/master/supplier/create') }}">
                                <i class="fa fa-plus"></i>
                                Tambah
                            </a>
                        </div>
                    </div>
				            <div class="portlet-body form">
                        <div class="form-horizontal">
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                            <thead>
                                                <tr>
                                                  <th>Nama</th>
                                                  <th>Penghubung</th>
                                                  <th>Telepon</th>
                                                  <th>Fax</th>
                                                  <th>Alamat</th>
                                                    <th>NPWP</th>
                                                    <th>Dibuat Tanggal</th>
                                                    <th class="text-center" width="150">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tfoot>
                                                <tr>
                                                  <th class="form-group form-md-line-input">Nama</th>
                                                  <th class="form-group form-md-line-input">Penghubung</th>
                                                  <th class="form-group form-md-line-input">Telepon</th>
                                                  <th class="form-group form-md-line-input">Fax</th>
                                                  <th class="form-group form-md-line-input">Alamat</th>
                                                  <th class="form-group form-md-line-input">NPWP</th>
                                                  <th class="form-group form-md-line-input">Dibuat Tanggal</th>
                                                  <th class="text-center" width="150">Aksi</th>
                                                </tr>
                                            </tfoot>
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
<script type="text/javascript">
    $(document).ready(function(){
        $("#table1").DataTable({
            processing: true,
            serverSide: true,
            ajax: '{!! url('usaha/master/supplier') !!}',
            columns: [
              {data: 'nama_supplier', name: 'nama_supplier'},
              {data: 'nama_penghubung_sup', name: 'nama_penghubung_sup'},
              {data: 'no_tlp_sup', name: 'no_tlp_sup'},
              {data: 'no_fax', name: 'no_fax'},
              {data: 'alamat_sup', name: 'alamat_sup'},
                {data: 'no_npwp_sup', name: 'no_npwp_sup'},
                {data: 'created_at', name: 'created_at'},
                {data: 'action', name: 'action', sClass: 'text-center', orderable: false, searchable: false},
            ],
            initComplete: function () {
                this.api().columns().every(function (index) {
                    var column = this;
                    if(index !== 6 && index !== 7){
                        var input = document.createElement("input");
                        $(input).addClass('form-control');
                        $(input).appendTo($(column.footer()).empty())
                        .on('change', function () {
                            column.search($(this).val(), false, false, true).draw();
                        });
                    }
                });
                $(".dataTables_length select").select2();
            }
        });
    });
</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
