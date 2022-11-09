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
        var jurnalTypes = "{{ $data->type }}";
    </script>
    <!-- BEGIN PAGE LEVEL -->
    <script src="{{ assets('pages/scripts/akuntansi/jurnal.index.js') }}" type="text/javascript"></script>
    <!-- END PAGE LEVEL  -->
@endpush

@section('content')

<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container-fluid">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Informasi Jurnal</h1>
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
                            <span class="caption-subject bold uppercase">{{ $codeDesc['lengkap'] }} ({{$codeDesc['singkat']}})</span>
                        </div>
                        <div class="actions">
                        
                            @if(is_null($data->posting_no))
                                @if($data->is_trigger == 0)
                                    <a href="{{ url('akuntansi/jurnal/'.$codeDesc['urlUbah'].'/'.Crypt::encrypt($data->journal_header_id)) }}" class="btn btn-warning"><i class="fa fa-pencil"></i> Ubah</a>
                                @endif
                            @endif
                            
                        </div>
                    </div>
                    <div class="portlet-body form">
                        {!! Form::open(['url' => '#', 'class' => 'form-horizontal', 'method' => 'POST', 'id'=>'formEntryJurnal']) !!}
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('division', 'Divisi :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-3">
                                            <p class="form-control-static">
                                                @php 
                                                    $div = "";
                                                    switch($data->division){
                                                        case 'SP' : $div = "Simpan Pinjam"; break;
                                                        case 'UM' : $div = "Usaha Umum"; break;
                                                        case 'TK' : $div = "Toko"; break;
                                                    }
                                                    echo $div;
                                                @endphp 
                                            </p>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('journal_no', 'Nomor Jurnal :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-3">
                                            <p class="form-control-static">{{ $data->journal_no }} </p>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('tr_type_id', 'Jenis Transaksi :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-3">
                                            <p class="form-control-static">{{ $data->transactiontype->desc }} </p>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('reff_no', 'Nomor Refrensi :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-3">
                                            <p class="form-control-static">{{ $data->reff_no }} </p>
                                        </div>
                                    </div>

                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('tr_date', 'Tanggal Transaksi :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-3">
                                            <p class="form-control-static">{{ tglIndo($data->tr_date) }} </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group form-md-line-input form-md-floating-label">
                                            {!! Form::label('tr_date', 'Keterangan :', ['class' => 'control-label col-md-4 bold'] ) !!}
                                        <div class="col-md-3">
                                            <p class="form-control-static">
                                                
                                                @php
                                                    $jDesc = nl2br($data->desc);
                                                    $jDesc = stripslashes($jDesc);
                                                    $jDesc = htmlspecialchars($jDesc);
                                                    $jDesc = str_replace(array("&lt;", "&gt;", "&lt;/"), array("<", ">" ,"</"), $jDesc);   
                                                    echo $jDesc;
                                                @endphp
                                            </p>
                                        </div>
                                    </div>
                                </div>

                            </div>   
                            <div class="row">
                                <div class="col-lg-12">
                                    <h3 class="form-section">Detail Transaksi</h3>

                                    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th width="5%">No</th>
                                                <th width="15%">Buku Besar</th>
                                                <th width="40%">Keterangan</th>
                                                <th width="15%">Debit</th>
                                                <th width="15%">Kredit</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listTable">
                                            @php $totalDebit = 0; $totalKredit = 0; @endphp
                                            @foreach($data_detail as $rDetail)
                                            <tr>
                                                <td>{{ $rDetail->seq }}</td>
                                                <td>{{ $rDetail->coa_code." - ".$rDetail->coas->desc }}</td>
                                                <td>{{ $rDetail->description }}</td>
                                                <td class="text-right" style="padding:10px 18px;">{{ ($rDetail->debit>0) ? toRp($rDetail->debit) :"-"  }}</td>
                                                <td class="text-right" style="padding:10px 18px;">{{ ($rDetail->kredit>0) ? toRp($rDetail->kredit) :"-" }}</td>
                                            </tr>
                                            @php $totalDebit += $rDetail->debit; $totalKredit += $rDetail->kredit; @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3">Total</td>
                                                <td class="bold text-right">{{ toRp($totalDebit) }}</td>
                                                <td class="bold text-right">{{ toRp($totalKredit) }}</td>
                                            </tr>
                                        </tfoot>
                                    </table>
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
                
<!-- END PAGE CONTENT BODY -->
@endsection