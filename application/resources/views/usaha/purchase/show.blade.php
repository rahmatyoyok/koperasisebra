@extends('layouts.master')

@section('title', 'Data Pembelian Langsung')

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
            <h1>Data Pembelian Langsung - {{ $data_edit->kode }}
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
                                    <span class="caption-subject bold uppercase">Informasi Pembelian Langsung</span>
                                </div>
                                <div class="actions">

                                    <div class="btn-group">
                                    <button type="button" class="btn green dropdown-toggle" data-toggle="dropdown">
                                    <i class="fa fa-print"></i> Cetak PDF</button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                        <a target="_blank" href="{{ url('usaha/pdf/purchase?kd='.$data_edit->purchase_id) }}">
                                            Cetak
                                        </a>
                                        </li>
                                    </ul>
                                    </div>
                                </div>
                            </div>
				            <div class="portlet-body form">

                                {!! Form::open(['url' => url('usaha/persekot/verifikasi-persekot'),'method'=>'POST', 'class' => 'form-horizontal']) !!}

                                <div class="form-body">


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4 bold">Tanggal Pembelian:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"> {{ tglIndo($data_edit->tanggal_pembelian) }} </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4 bold">Diterima:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"> {{ $data_edit->diterima }} </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4 bold">Jenis Pembayaran:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"> {{ statusMetodePembayaran($data_edit->jenis_pembayaran) }} </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4 bold">Total:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"> {{ toRp($data_edit->total) }} </p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4 bold">Keterangan:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static"> {{ $data_edit->keterangan }} </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <h3>Detail Item</h3>
                                    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>COA</th>
                                                <th>Keterangan</th>
                                                <th>Jumlah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($detail as $value)
                                            <tr>
                                            <td>{{ $value->code }}-{{ $value->desc }}</td>
                                            <td>{{ $value->keterangan }}</td>
                                            <td align="right">{{ toRp($value->jumlah) }}</td>
                                            </tr>
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

<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
