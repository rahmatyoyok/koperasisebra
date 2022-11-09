@extends('layouts.master-ak')

@section('title', $title)

@push('styles')
<link href="{{ assets('global/plugins/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/morris/morris.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/fullcalendar/fullcalendar.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ assets('global/plugins/jqvmap/jqvmap/jqvmap.css') }}" rel="stylesheet" type="text/css" />


@endpush

@push('plugins')
<script src="{{ assets('global/plugins/morris/morris.min.js') }}" type="text/javascript"></script>
<script src="{{ assets('global/plugins/morris/raphael-min.js') }}" type="text/javascript"></script>
@endpush


@push('scripts')
<script src="{{ assets('pages/scripts/dashboard.js') }}" type="text/javascript"></script>
@endpush


@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Beranda Akuntansi
            </h1>
        </div>
    </div>
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE CONTENT BODY -->

<!-- END PAGE CONTENT BODY -->


@endsection
