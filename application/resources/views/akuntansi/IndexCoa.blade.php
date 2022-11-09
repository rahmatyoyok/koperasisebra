@extends('layouts.master-ak')

@section('title', $title)

@push('styles')
    
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <link href="{{ assets('global/plugins/datatables/datatables.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/jquery-treetable-master/css/jquery.treetable.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/jquery-treetable-master/css/jquery.treetable.theme.default.css') }}" rel="stylesheet" type="text/css" />
    
    <!-- END PAGE LEVEL PLUGINS -->

    <style>
    .ui-datepicker-calendar {
        display: none;
    }

    .table thead tr th {
        {{-- font-size: 10px!important; --}}
        font-weight: 600;
    }

    .table tbody tr td {
        {{-- font-size: 10px; --}}
    }

    </style>
@endpush
    
@push('plugins')
    
@endpush

@push('scripts')
    <script type="text/javascript">
    var BaseUrl = '{{ url('/') }}';
    </script>
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script src="{{ assets('pages/scripts/SimpanPinjam/index.kalkulasi.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery-treetable-master/jquery.treetable.js') }}" type="text/javascript"></script>
    <script>
        
        $("#example-advanced").treetable({ expandable: true });
  
        // Highlight selected row
        $("#example-advanced tbody").on("mousedown", "tr", function() {
          $(".selected").not(this).removeClass("selected");
          $(this).toggleClass("selected");
        });
        
      </script>
    <!-- END PAGE LEVEL PLUGINS -->
@endpush

@section('content')
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>COA</h1>
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
                        <div class="col-md-12">
                            <table id="example-advanced" class="table table-bordered table-striped table-condensed flip-content">
                                {{-- <caption> --}}
                                  {{-- <a href="#" onclick="jQuery('#example-advanced').treetable('expandAll'); return false;">Expand all</a>
                                  <a href="#" onclick="jQuery('#example-advanced').treetable('collapseAll'); return false;">Collapse all</a> --}}
                                {{-- </caption> --}}
                                <thead class="flip-content">
                                  <tr>
                                    <th>Kode Rekening</th>
                                    <th>Keterangan</th>
                                    <th>Group</th>
                                    <th>Aksi</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @php $i=1; $lastCoaId = null; @endphp
                                    @foreach($ListCoa as $rw)
                                        @if((int)$rw->group_detail == 1)
                                            <tr data-tt-id='{{ $rw->coa_id}}'><td><span class='folder'>{{ $rw->code }}</span></td><td>{{ $rw->desc }}</td><td>{{ $rw->desc_group_coa }}</td><td></td></tr>
                                        @else
                                            <tr data-tt-id='{{ $rw->header_coa_id }}-{{ $rw->coa_id }}' data-tt-parent-id='{{ $rw->header_coa_id }}'><td><span class='file'>{{ $rw->code }}</span></td><td>{{ $rw->desc }}</td><td>{{ $rw->desc_group_coa }}</td><td>--</td></tr>    
                                        @endif
                                        @php $lastCoaId = $rw->coa_id; @endphp
                                    @endforeach
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
<!-- END PAGE CONTENT BODY -->


@endsection
