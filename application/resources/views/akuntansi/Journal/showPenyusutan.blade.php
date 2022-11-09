@php
    use Illuminate\Support\Facades\Crypt;
@endphp

@extends('layouts.master-ak')

@section('title', $title)

@push('styles')
    <link href="{{assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css')}}" rel="stylesheet" type="text/css" />

    <link href="{{assets('global/plugins/bootstrap-modal/css/bootstrap-modal-bs3patch.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{assets('global/plugins/bootstrap-modal/css/bootstrap-modal.css')}}" rel="stylesheet" type="text/css" />
    <style>
        .form-horizontal .form-group.form-md-line-input {
            padding-top: 10px;
            margin: 0 -10px 5px!important;
        }

        .form-horizontal .form-group .form-control-static {
            margin-top: -1px!Important;
        }
    </style>

@endpush

@push('plugins')
    <script src="{{ assets('global/scripts/datatable.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js') }}" type="text/javascript"></script>
@endpush

@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
    </script>
    <!-- BEGIN PAGE LEVEL -->
    <script src="{{ assets('pages/scripts/akuntansi/penyusutan.show.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL  -->
@endpush

@section('content')

<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Informasi Penyusutan</h1>
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
                            <span class="caption-subject bold uppercase">Detail Penyusutan</span>
                        </div>
                        <div class="actions"></div>
                    </div>
                    <div class="portlet-body form">
                        {!! Form::open(['url' => '#', 'class' => 'form-horizontal', 'method' => 'POST', 'id'=>'formEntryJurnal']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('KodeAset', 'No. Aset :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-8">
                                            <p class="form-control-static">{{ $data->kode_aset }}</p>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('Nama', 'Nama :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-8">
                                            <p class="form-control-static">{{ $data->nama }}</p>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('tgl', 'Tanggal Perolehan :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-8">
                                            <p class="form-control-static">{{ tglIndo($data->tgl_pembelian)}}</p>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('tgl', 'Masa Manfaat :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-8">
                                            <p class="form-control-static">{{ $data->masa_manfaat}} Tahun</p>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('tgl', 'Tarif Penyusutan :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-8">
                                            <p class="form-control-static">{{ floatval($data->tarif) }}% Per-tahun</p>
                                        </div>
                                    </div>

                       
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('KodeAset', 'Harga Perolehan:', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-8">
                                            <p class="form-control-static">{{ toRp($data->harga) }} Per-unit</p>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('KodeAset', 'Jumlah :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-8">
                                            <p class="form-control-static">{{ floatval($data->jumlah) }} Unit</p>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        
                        {!! Form::close() !!}

                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="tb_list_penyusutan" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Periode</th>
                                            <th>No. Posting</th>
                                            <th>Sisa Masa Manfaat</th>
                                            <th>Akumulasi Penyusutan Per-unit</th>
                                            <th>Total Akumulasi Penyusutan</th>
                                            <th>Nilai Buku Akhir</th>
                                            <th class="text-center" width="150">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dataListDetail as $item)
                                            @php $isamasa = explode(".",$item->sisa_masa_manfaat) @endphp
                                            <tr>
                                                <td>{{ getMonths()[(int)substr($item->periode,0,2)].' '.substr($item->periode,-4) }}</td>
                                                <td>{{ $item->posting_no }}</td>
                                                <td>{{ $isamasa[0]." Tahun".(int)$isamasa[1]." Bulan" }}</td>
                                                <td style="text-align:right;">{{ toRp($item->akm_penyusutan) }}</td>
                                                <td style="text-align:right;">{{ toRp($item->total_akm_penyusutan) }}</td>
                                                <td style="text-align:right;">{{ toRp($item->sisa_nilai_buku) }}</td>
                                                <td width="5%">
                                                    @if(strlen($item->posting_no) <= 0) 
                                                        <a href="#" data-id="{{ Crypt::encrypt($item->penyusutan_id) }}" data-periode="{{ getMonths()[(int)substr($item->periode,0,2)].' '.substr($item->periode,-4) }}" class="deletehistory btn btn-xs btn-danger tooltips">Hapus</a> 
                                                    @endif
                                                </td>
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
</div>

<div id="pertanyaanPenyusutan" class="modal fade" tabindex="-1" data-focus-on="input:first">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Hapus Penyusutan</h4>
    </div>
    <div class="modal-body">
        <p> Apakah anda yakin menghapus penyusutan periode <span id="periodeid"></span>?</p>
    </div>
    <div class="modal-footer">
        <button type="button" data-dismiss="modal" class="btn red">Tidak</button>
        <button type="button" class="btn green" id="hapusPenyusutan">Ya</button>
    </div>
</div>


<div id="stack3" class="modal fade" tabindex="-1" data-focus-on="input:first">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Hapus Penyusutan</h4>
    </div>
    <div class="modal-body">
        <div class="alert alert-danger"><strong>Error!</strong> Hapus penyusutan periode <span id="periodeid"></span> gagal. </div>
    </div>
</div>


<!-- END PAGE CONTENT BODY -->
@endsection