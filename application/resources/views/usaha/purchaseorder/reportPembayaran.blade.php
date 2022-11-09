@extends('layouts.master')

@section('title', 'Laporan Pembayaran Purchase Order')

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
            <h1>Laporan Pembayaran Purchase Order
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
                        <h3>Total Data : {{ $total }}</h3>
                        <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                            <thead>
                                <tr>
                                  <th>No</th>
                                  <th>Supplier</th>
                                    <!-- <th>Jenis</th>
                                    <th>Lokasi</th>
                                    <th>Pekerjaan</th> -->
                                    <th>Total Biaya PO</th>
                                    <th>Pembayaran</th>
                                    <th>Kekurangan</th>
                                    <!-- <th>Tanggal Dibuat</th> -->
                                    <th class="text-center" width="150">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                              <?php $no = 1;?>
                                @foreach($data as $value)
                                <tr>
                                  <td align="center">{{ $no }}</td>
                                  <td>{{ $value->supplier }}</td>
                                  
                                  <td align="right">{{ formatNoRpComma($value->total) }}</td>
                                  <td align="right">{{ formatNoRpComma($value->total_pembayaran) }}</td>
                                  <td align="right">{{ formatNoRpComma($value->kekurangan) }}</td>
                                  <!-- <td>{{ tglIndo($value->created_at) }}</td> -->
                                  <td class="text-center" width="150">
                                    <a href="{{ url('usaha/po/'.$value->po_id) }}" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>
                                  </td>
                                </tr>
                                <?php $no++;?>
                              @endforeach

                            </tbody>

                        </table>
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

</script>
<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
