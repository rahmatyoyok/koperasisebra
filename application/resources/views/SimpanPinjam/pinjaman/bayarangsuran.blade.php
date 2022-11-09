
@extends('layouts.master-sp')
@section('title', $title)

@push('styles')

    <link href="{{ assets('global/plugins/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/select2/css/select2-bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ assets('global/plugins/bootstrap-markdown/css/bootstrap-markdown.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{assets('global/plugins/datatables/datatables.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.css" rel="stylesheet')}}" type="text/css" />
    <style>
        .form-horizontal .form-group.form-md-line-input {
            padding-top: 10px;
            margin: 0 -10px 5px!important;
        }

        .form-group.form-md-line-input .form-control[disabled], .form-group.form-md-line-input .form-control[readonly], fieldset[disabled] .form-group.form-md-line-input .form-control {
            background: 0 0;
            cursor: not-allowed;
            border-bottom: 1px solid #c2cad8 !important;
        }
    </style>
@endpush



@push('plugins')
    <script src="{{assets('global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
    <script src="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>

    <script src="{{ assets('global/plugins/select2/js/select2.full.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery-validation/js/jquery.validate.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery-validation/js/additional-methods.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-markdown/lib/markdown.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/bootstrap-markdown/js/bootstrap-markdown.js') }}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js')}}" type="text/javascript"></script>
    <script src="{{ assets('global/plugins/jquery.masknumber.min.js') }}" type="text/javascript"></script>
    <script src="{{assets('pages/scripts/inputmask-global.js')}}" type="text/javascript"></script>
@endpush


@push('scripts')
    <script type="text/javascript">
        var BaseUrl = '{{ url('/') }}';
    </script>
    <script type="text/javascript">
        $(document).ready(function(){

            if($('select[name=payment_method] option:selected').text() == 'Tunai'){
                $('.showBank').addClass('hide');
            }

            $('select[name=payment_method]').on('select2:select', function (e) {
            // Do something
                var data = e.params.data;
                $('.showBank').removeClass('hide');
                if(data.text == 'Tunai'){
                    $('.showBank').addClass('hide');
                }
            });

            $('input[name=jumlah_pokok_angsuran], input[name=jumlah_bunga_angsuran]').keyup(function(){
                var jmlPokok = parseFloat($('input[name=jumlah_pokok_angsuran]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
                var jmlBunga = parseFloat($('input[name=jumlah_bunga_angsuran]').val().replace(/\./g,"").replace("Rp ","").replace("",0));
                
                var total = Math.ceil(jmlPokok) + Math.ceil(jmlBunga);
                $('input[name=total_bayar]').val(total);
            });

        });
    </script>
    <script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush


@section('content')

    <!-- BEGIN PAGE HEAD-->
    <div class="page-head">
        <div class="container">
            <!-- BEGIN PAGE TITLE -->
            <div class="page-title">
                <h1> Penyerahan Investasi - Ref. Code : {{ $data->ref_code }}</h1>
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
                                    {!! Form::open(['url' => url('simpanpinjam/pinjaman/apprangsuran/'.$idEncrypt),'method'=>'POST', 'class' => 'form-horizontal','enctype' => 'multipart/form-data']) !!}
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4 bold">NIAK</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->niak }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Nama</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->first_name }}  {{ $data->anggota->last_name }} </p>
                                                    </div>
                                                </div>
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Nomor Induk</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->customer->nomor_induk }}
                                                    </div>
                                                </div>
        
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Unit Kerja</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->customer->company_name }}
                                                    </div>
                                                </div>
        
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Jabatan</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->customer->jabatan }}
                                                    </div>
                                                </div>

                    
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Tanggal Lahir</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->born_place .', '.date('d F Y', strtotime($data->anggota->born_date)) }} </p>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">No. Identitas</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> :  {{ $data->anggota->id_card_number }} </p>
                                                    </div>
                                                </div> 


                                                <div class="form-group form-md-line-input">
                                                    <label class="col-md-4 control-label bold">Alamat</label>
                                                    <div class="col-md-8">
                                                            <p class="form-control-static"> : {{ $data->anggota->address_1 }} </p>
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Jenis Anggota</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ sp_array_mdrray_search(sp_member_status(), 'id', 'name',$data->anggota->member_type) }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Perusahaan</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : {{ $data->anggota->customer->company_name }} </p>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-md-6">

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    {!! Form::label('total', 'Data Pinjaman', ['class' => 'control-label col-md-4 bold', 'style' => "text-align:left;"] ) !!}
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4 bold">Pinjaman</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : Rp. {{ formatNoRpComma($data->loan_amount) }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4 bold">Sisa Pokok Pinjaman</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : Rp. {{ formatNoRpComma($data->saldo_pokok_pinjaman) }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4 bold">Sisa Bunga Pinjaman</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : Rp. {{ formatNoRpComma($data->saldo_bunga_pinjaman) }} </p>
                                                    </div>
                                                </div>

                                                
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    {!! Form::label('total', 'Data Angsuran', ['class' => 'control-label col-md-4 bold', 'style' => "text-align:left;"] ) !!}
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4 bold">Angsuran Pokok</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : Rp. {{ formatNoRpComma($data->principal_amount) }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4 bold">Angsuran Bunga</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : Rp. {{ formatNoRpComma($data->rates_amount) }} </p>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input">
                                                    <label class="control-label col-md-4 bold">Total Angsuran</label>
                                                    <div class="col-md-8">
                                                        <p class="form-control-static"> : Rp. {{ formatNoRpComma($data->principal_amount+$data->rates_amount) }} </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <hr>
                                            <div class="form-group form-md-line-input form-md-floating-label">
                                                {!! Form::label('total', 'Pembayaran Angsuran', ['class' => 'control-label col-md-4 bold', 'style' => "text-align:left;"] ) !!}
                                            </div>

                                            <div class="col-md-7">
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Angsuran Ke</label>
                                                    <div class="col-md-2">
                                                        {!! Form::text('seq_number', (int)$data->seq_number+1 , ['class' => 'form-control numeric'] ) !!}
                                                    </div>
                                                </div>
                                                
                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Jumlah Pokok Angsuran</label>
                                                    <div class="col-md-5">
                                                        {!! Form::text('jumlah_pokok_angsuran', $data->principal_amount, ['class' => 'form-control rupiah', 'autofocus'] ) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Jumlah Bunga Angsuran</label>
                                                    <div class="col-md-5">
                                                        {!! Form::text('jumlah_bunga_angsuran', $data->rates_amount, ['class' => 'form-control rupiah', 'autofocus'] ) !!}
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Jumlah Angsuran</label>
                                                    <div class="col-md-5">
                                                        {!! Form::text('total_bayar', $data->principal_amount+$data->rates_amount , ['class' => 'form-control rupiah', 'autofocus', 'disabled'] ) !!}
                                                    </div>
                                                </div>


                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="col-md-4 control-label bold">Cara Penerimaan</label>
                                                    <div class="col-md-8">
                                                        {{ Form::select('payment_method', sp_payment_method_list(), 1, ['class' => 'form-control select2']) }}
                                                    </div>
                                                </div>

                                                <div class="showBank form-group form-md-line-input form-md-floating-label {{ $errors->has('member_status') ? 'has-error' : ''}}  {{ $errors->has('account_number') ? 'has-error' : ''}}">
                                                    {!! Form::label('bank_id', 'Rekening Koperasi', ['class' => 'control-label col-md-4 required-input bold'] ) !!}
                                                    <div class="col-md-8"> @php echo $nama_bank; @endphp - {{ $nomor_rekening }}
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    {!! Form::label('transfer_date', 'Tanggal Penyerahan', ['class' => 'control-label col-md-4 required-input  bold'] ) !!}
                                                    <div class="col-md-5">
                                                        <div class="input-group date date-picker" data-date-format="dd-mm-yyyy" >
                                                            <p class="form-control-static"> {{ date('d-m-Y') }}</p>
                                                            {{--  {!! Form::text('transfer_date', date('d-m-Y'), ['class' => 'form-control'] ) !!}  --}}
                                                            <div class="form-control-focus"> </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group form-md-line-input form-md-floating-label">
                                                    <label class="control-label col-md-4  bold">Dokumen Pendukung</label>
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
                                                {!! Form::button('Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Penerimaan Angsuran', 'style' => 'margin-right:5px;']) !!}
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

