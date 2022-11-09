@extends('layouts.master')

@section('title', $title)

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
            <h1>Pengguna
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
                                    <span class="caption-subject bold uppercase">Data Pengguna</span>
                                </div>
                                <div class="actions">
                                    <a class="btn green-jungle col-md-12" href="user/create">
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
                                                            <th>Username</th>
                                                            <th>Email</th>
                                                            <th>Nama</th>
                                                            <th>Status</th>
                                                            <th class="text-center" width="150">Aksi</th>
                                                            <th class="none">Dibuat Tanggal</th>
                                                            <th class="none">Level</th>
                                                        </tr>
                                                    </thead>
                                                    <tfoot>
                                                        <tr>
                                                            <th class="form-group form-md-line-input">Username</th>
                                                            <th class="form-group form-md-line-input">Email</th>
                                                            <th class="form-group form-md-line-input">Nama</th>
                                                            <th class="form-group form-md-line-input">Status</th>
                                                            <th class="text-center" width="150">Aksi</th>
                                                            <th class="none">Dibuat Tanggal</th>
                                                            <th class="none">Level</th>
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
            ajax: '{!! url('pengaturan/user') !!}',
            columns: [
                {data: 'username', name: 'users.username'},
                {data: 'email', name: 'users.email'},
                {data: 'name', name: 'users.name'},
                {data: 'is_active', name: 'users.is_active', sClass: 'text-center', searchable: false},
                {data: 'action', name: 'action', sClass: 'text-center', orderable: false, searchable: false},
                {data: 'created_at', name: 'users.created_at'},
                {data: 'level.name', name: 'level.name'}
            ],
            initComplete: function () {
                this.api().columns().every(function (index) {
                    var column = this;
                    if(index !== 4){
                        var input = document.createElement("input");
                        $(input).addClass('form-control');
                        // if(index == 4)
                        // {
                        //     $(input).attr('type', 'date');
                        // }
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
