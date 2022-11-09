@extends('layouts.master-ak')
@section('title', $title)

@push('styles')
    <link href="{{assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet')}}" type="text/css" />
    <style>
        .form-horizontal .form-group.form-md-line-input {
            padding-top: 10px;
            margin: 0 -10px 5px!important;
        }
    </style>
@endpush


@push('scripts')
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush


@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Detil Kode Rekening</h1>
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
                            <span class="caption-subject bold uppercase">Informasi Detil Kode Rekening</span>
                        </div>
                        <div class="actions">
                            <a class="btn blue-sharp" href="">
                                <i class="glyphicon glyphicon-edit"></i>
                                Ubah Data Kode Rekening
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body form">
                            {!! Form::open(['class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group form-md-line-input">
                                            <label class="control-label col-md-4 bold">NIAK</label>
                                            <div class="col-md-8">
                                                <p class="form-control-static"> : tulis </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
