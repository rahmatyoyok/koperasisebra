@extends('layouts.master')

@section('title', $title)

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
            <h1>Pengguna
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
                                    <span class="caption-subject bold uppercase">Tambah Pengguna</span>
                                </div>
                            </div>
				            <div class="portlet-body form">

                                {!! Form::open(['route' => 'user.store', 'class' => 'form-horizontal']) !!}

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
                                        <div class="col-md-12">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('username') ? 'has-error' : ''}}">
                                                {!! Form::label('username', 'Username', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    {!! Form::text('username', null, ['class' => 'form-control', 'id' => 'username', 'autofocus'] ) !!}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('username') ? $errors->first('username') : 'Masukkan Username' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('email') ? 'has-error' : ''}}">
                                                {!! Form::label('email', 'Email', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    {!! Form::email('email', null, ['class' => 'form-control', 'id' => 'email'] ) !!}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('email') ? $errors->first('email') : 'Masukkan Email' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('password') ? 'has-error' : ''}}">
                                                {!! Form::label('password', 'Password', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    {!! Form::password('password', ['class' => 'form-control', 'id' => 'password'] ) !!}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('password') ? $errors->first('password') : 'Masukkan Password' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('password_confirmation') ? 'has-error' : ''}}">
                                                {!! Form::label('password_confirmation', 'Ulangi Password', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    {!! Form::password('password_confirmation', ['class' => 'form-control', 'id' => 'password_confirmation'] ) !!}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('password_confirmation') ? $errors->first('password_confirmation') : 'Ulangi Password' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('name') ? 'has-error' : ''}}">
                                                {!! Form::label('name', 'Nama User', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    {!! Form::text('name', null, ['class' => 'form-control', 'id' => 'name'] ) !!}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('name') ? $errors->first('name') : 'Masukkan Nama User' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group form-md-line-input form-md-floating-label {{ $errors->has('level_id') ? 'has-error' : ''}}">
                                                {!! Form::label('level_id', 'Level', ['class' => 'control-label col-md-2'] ) !!}
                                                <div class="col-md-10">
                                                    {{ Form::select('level_id', $level, null, ['class' => 'form-control']) }}
                                                    <div class="form-control-focus"> </div>
                                                    <span class="help-block">{{ $errors->has('level_id') ? $errors->first('level_id') : 'Masukkan Level' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="form-actions">

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="col-md-3"></div>
                                            {!! Form::button('<i class="fa fa-check"></i> Simpan', ['class' => 'btn blue-sharp col-md-3 simpan', 'type' => 'submit', 'data-swa-text' => 'Menambahkan user']) !!}
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
<script>
$( document ).ready(function() {

  <?php if($user->level_id == 2){?>
  $("select[id=skpd_id]").select2({
      width : '100%',
      minimumInputLength: 0,
      placeholder:"Pilih SKPD",
      ajax: {
          url: "{{ url("/api/select2/skpd")}}",
          dataType: 'json',
          cache: false,
          data: function (params) {
              return { q: $.trim(params.term),regencies:$("#regencies").val() };
          },
          results: function (data, page) {
              return { results: data.results };
          }
      }
  });
  <?php } ?>
});
</script>
@endpush
