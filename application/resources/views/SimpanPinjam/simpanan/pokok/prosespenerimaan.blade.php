
@extends('layouts.master-sp')
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



@push('plugins')
    <script src="{{assets('global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
    <script src="{{assets('global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
@endpush


@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
    </script>
    
    {{-- <script src="{{ assets('pages/scripts/SimpanPinjam/simpanpokok.show.js') }}" type="text/javascript"></script> --}}
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush


@section('content')

    <!-- BEGIN PAGE HEAD-->
    <div class="page-head">
        <div class="container">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1> Penerimaan Simpanan Pokok - Ref. Code : {{ $data->ref_code }}</h1>
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
                                <div class="portlet-body form">
                                    {!! Form::open(['url' => url('simpanpinjam/simpananpokok/apprterima/'.$idEncrypt),'method'=>'POST', 'class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4">NIAK</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->niak }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label">Nama</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->first_name }}  {{ $data->anggota->last_name }} </p>
                                                    </div>
                                                </div>

                    
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label">Tanggal Lahir</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->born_place .', '.date('d F Y', strtotime($data->anggota->born_date)) }} </p>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label">No. Identitas</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> :  {{ $data->anggota->id_card_number }} </p>
                                                    </div>
                                                </div> 


                                                <div class="form-group form-md-line-input">
                                                    <label class="col-md-4 control-label">Alamat</label>
                                                    <div class="col-md-8">
                                                            <p class="form-control-static"> : {{ $data->anggota->address_1 }} </p>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label">Jenis Anggota</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ sp_array_mdrray_search(sp_member_status(), 'id', 'name',$data->anggota->member_type) }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label">Perusahaan</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->customer->company_name }} </p>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Total</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : Rp. {{ formatNoRpComma($data->total)  }}</p>
                                                    </div>
                                                </div>


                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label">Cara Penerimaan</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ sp_payment_method_name('name', $data->payment_method) }}</p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                        {!! Form::label('tr_date', 'Tanggal Penerimaan', ['class' => 'control-label col-md-4 required-input bold '] ) !!}
                                                    <div class="col-md-5">
                                                        <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
                                                            <span class="input-group-addon">
                                                                <i class="fa fa-calendar"></i>
                                                            </span>
                                                            {!! Form::text('payment_date', $def_date, ['class' => 'form-control', 'autofocus'] ) !!}
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                @if(sp_payment_method_name('name',$data->payment_method) == 'Transfer Bank')
                                                <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? 'has-error' : ''}}  {{ $errors->has('account_number') ? 'has-error' : ''}}">
                                                    {!! Form::label('bank_id', 'Rekening Koperasi', ['class' => 'control-label col-md-4 required-input'] ) !!}
                                                    <div class="col-md-8">
                                                        : @php echo $nama_bank; @endphp
                                                        {{-- {{ Form::select('bank_id', $list_bank, 7, ['class' => 'form-control select2']) }} --}}
                                                        -
                                                        
                                                            {{ $nomor_rekening }}
                                                            
                                                    </div>
                                                </div>
                                                @endif

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="control-label col-md-4 ">Dokumen Pendukung:</label>
                                                    <div class="col-md-8">
                                                        <input type="file" name="dokumen">
                                                        <input type="hidden" name="id" value="">
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>

                                    <div class="form-actions">

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="col-md-3"></div>
                                                {!! Form::button('Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menerima Pembayaran Simpanan Pokok', 'style' => 'margin-right:5px;']) !!}
                                                <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze">Batal</a>
                                                <div class="col-md-3"></div>
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
            <!-- END PAGE CONTENT INNER -->
        </div>
    </div>
    <!-- END PAGE CONTENT BODY -->


@endsection

