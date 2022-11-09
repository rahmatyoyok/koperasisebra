@extends('layouts.master-sp')

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
            <h1>Home
            </h1>
        </div>
    </div>
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container-fluid">
      <div class="row">
          <div class="col-lg-12 col-xs-12 col-sm-12">

            <div class="portlet light ">
                <div class="portlet-title">
                    <div class="caption caption-md">
                        <i class="icon-bar-chart font-dark hide"></i>
                        <span class="caption-subject font-green-steel uppercase bold">Sales Summary</span>
                        <span class="caption-helper hide">weekly stats...</span>
                    </div>
                    <div class="actions">
                        <div class="btn-group btn-group-devided" data-toggle="buttons">
                            <label class="btn btn-transparent btn-no-border blue-oleo btn-outline btn-circle btn-sm active">
                                <input type="radio" name="options" class="toggle" id="option1">Today</label>
                            <label class="btn btn-transparent btn-no-border blue-oleo btn-outline btn-circle btn-sm">
                                <input type="radio" name="options" class="toggle" id="option2">Week</label>
                            <label class="btn btn-transparent btn-no-border blue-oleo btn-outline btn-circle btn-sm">
                                <input type="radio" name="options" class="toggle" id="option2">Month</label>
                        </div>
                    </div>
                </div>
                <div class="portlet-body">
                    <div class="row list-separated">
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="font-grey-mint font-sm"> Total Sales </div>
                            <div class="uppercase font-hg font-red-flamingo"> 13,760
                                <span class="font-lg font-grey-mint">$</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="font-grey-mint font-sm"> Revenue </div>
                            <div class="uppercase font-hg theme-font"> 4,760
                                <span class="font-lg font-grey-mint">$</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="font-grey-mint font-sm"> Expenses </div>
                            <div class="uppercase font-hg font-purple"> 11,760
                                <span class="font-lg font-grey-mint">$</span>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-3 col-xs-6">
                            <div class="font-grey-mint font-sm"> Growth </div>
                            <div class="uppercase font-hg font-blue-sharp"> 9,760
                                <span class="font-lg font-grey-mint">$</span>
                            </div>
                        </div>
                    </div>
                    <ul class="list-separated list-inline-xs hide">
                        <li>
                            <div class="font-grey-mint font-sm"> Total Sales </div>
                            <div class="uppercase font-hg font-red-flamingo"> 13,760
                                <span class="font-lg font-grey-mint">$</span>
                            </div>
                        </li>
                        <li> </li>
                        <li class="border">
                            <div class="font-grey-mint font-sm"> Revenue </div>
                            <div class="uppercase font-hg theme-font"> 4,760
                                <span class="font-lg font-grey-mint">$</span>
                            </div>
                        </li>
                        <li class="divider"> </li>
                        <li>
                            <div class="font-grey-mint font-sm"> Expenses </div>
                            <div class="uppercase font-hg font-purple"> 11,760
                                <span class="font-lg font-grey-mint">$</span>
                            </div>
                        </li>
                        <li class="divider"> </li>
                        <li>
                            <div class="font-grey-mint font-sm"> Growth </div>
                            <div class="uppercase font-hg font-blue-sharp"> 9,760
                                <span class="font-lg font-grey-mint">$</span>
                            </div>
                        </li>
                    </ul>
                    <div id="sales_statistics" class="portlet-body-morris-fit morris-chart" style="height: 267px"> </div>
                </div>
            </div>
                   

          </div>
      </div>
    </div>
</div>
<!-- END PAGE CONTENT BODY -->


@endsection
