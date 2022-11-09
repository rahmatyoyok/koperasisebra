@extends('layouts.master')

@section('title', 'Pengaturan Usaha Umum')

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
            <h1>Pengaturan Usaha Umum
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

				            <div class="portlet-body form">

                        {!! Form::model($data_edit, [
                            'method' => 'PATCH',
                            'url' => ['usaha/master/setting', $data_edit->setting_id],
                            'class' => 'form-horizontal'
                        ]) !!}


                        <div class="form-body">

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <button class="close" data-close="alert"></button>
                                    <p>Terdapat beberapa kesalahan. Silahkan diperbaiki.</p><br>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('levering_po') ? 'has-error' : ''}}">
                                        {!! Form::label('levering_po', 'Levering Purchase Order', ['class' => 'control-label col-md-5'] ) !!}
                                        <div class="col-md-4">
                                          <div class="input-group">
                                              {!! Form::number('levering_po', null, ['class' => 'form-control', 'id' => 'levering_po'] ) !!}
                                              <span class="input-group-addon" id="sizing-addon1">Hari</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('ppn') ? 'has-error' : ''}}">
                                        {!! Form::label('ppn', 'PPN Purchase Order', ['class' => 'control-label col-md-5'] ) !!}
                                        <div class="col-md-4">
                                          <div class="input-group">
                                              {!! Form::text('ppn', null, ['class' => 'form-control', 'id' => 'ppn'] ) !!}
                                              <span class="input-group-addon" id="sizing-addon1">%</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('pph22_npwp') ? 'has-error' : ''}}">
                                        {!! Form::label('pph22_npwp', 'PPH22 NPWP', ['class' => 'control-label col-md-5'] ) !!}
                                        <div class="col-md-4">
                                          <div class="input-group">
                                              {!! Form::number('pph22_npwp', null, ['class' => 'form-control', 'id' => 'ppn'] ) !!}
                                              <span class="input-group-addon" id="sizing-addon1">%</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('pph22_non_npwp') ? 'has-error' : ''}}">
                                        {!! Form::label('pph22_non_npwp', 'PPH22 Non NPWP', ['class' => 'control-label col-md-5'] ) !!}
                                        <div class="col-md-4">
                                          <div class="input-group">
                                              {!! Form::number('pph22_non_npwp', null, ['class' => 'form-control', 'id' => 'ppn'] ) !!}
                                              <span class="input-group-addon" id="sizing-addon1">%</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('pph23_npwp') ? 'has-error' : ''}}">
                                        {!! Form::label('pph23_npwp', 'PPH23 NPWP', ['class' => 'control-label col-md-5'] ) !!}
                                        <div class="col-md-4">
                                          <div class="input-group">
                                              {!! Form::number('pph23_npwp', null, ['class' => 'form-control', 'id' => 'ppn'] ) !!}
                                              <span class="input-group-addon" id="sizing-addon1">%</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('pph23_non_npwp') ? 'has-error' : ''}}">
                                        {!! Form::label('pph23_non_npwp', 'PPH23 Non NPWP', ['class' => 'control-label col-md-5'] ) !!}
                                        <div class="col-md-4">
                                          <div class="input-group">
                                              {!! Form::number('pph23_non_npwp', null, ['class' => 'form-control', 'id' => 'ppn'] ) !!}
                                              <span class="input-group-addon" id="sizing-addon1">%</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('due_date_persekot') ? 'has-error' : ''}}">
                                        {!! Form::label('due_date_persekot', 'Jatuh Tempo Persekot', ['class' => 'control-label col-md-5'] ) !!}
                                        <div class="col-md-4">
                                          <div class="input-group">
                                              {!! Form::number('due_date_persekot', null, ['class' => 'form-control', 'id' => 'due_date_persekot'] ) !!}
                                              <span class="input-group-addon" id="sizing-addon1">Hari</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('margin_persekot') ? 'has-error' : ''}}">
                                        {!! Form::label('margin_persekot', 'Margin Persekot', ['class' => 'control-label col-md-5'] ) !!}
                                        <div class="col-md-4">
                                          <div class="input-group">
                                              {!! Form::number('margin_persekot', null, ['class' => 'form-control', 'id' => 'margin_persekot'] ) !!}
                                              <span class="input-group-addon" id="sizing-addon1">%</span>
                                          </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="col-md-3"></div>
                                    {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Merubah Pengaturan Usaha Umum']) !!}
                                    <a href="{{ url()->previous() }}" type="button" class="col-md-3 btn red-haze"><i class="fa fa-close"></i> Batal</a>
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


@push('plugins')
<script src="{{assets('global/plugins/select2/js/select2.full.min.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/datatables/datatables.min.js')}}" type="text/javascript"></script>
<script src="{{assets('global/plugins/datatables/plugins/bootstrap/datatables.bootstrap.js')}}" type="text/javascript"></script>
@endpush

@push('scripts')

<script src="{{ assets('pages/scripts/sweetalert2-scripts.js') }}" type="text/javascript"></script>
@endpush
