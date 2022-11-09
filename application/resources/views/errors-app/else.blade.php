@extends('layouts.master')

@section('title', $title)

@push('styles')
<link href="{{ assets('pages/css/error.css') }}" rel="stylesheet" type="text/css" />
@endpush

@section('content')
<div class="page-bar margin-bottom-20">
    <ul class="page-breadcrumb">
        <li>
            <a href="{{url('home')}}">Home</a>
            <i class="fa fa-circle"></i>
        </li>
        <li>
            <span>503</span>
        </li>
    </ul>
</div>

<h1 class="page-title"> ERROR 
</h1>

<div class="row">
    <div class="col-md-12 page-404">
        <div class="number font-red"> 503 </div>
        <div class="details">
            <h3>Permintaan gagal.</h3>
            <p> Maaf kami tidak dapat memenuhi permintaan Anda.
                <br/>
                <a href="{{ url()->previous() }}" class="btn red btn-outline"> Kembali </a> 
            </p>
        </div>
    </div>
</div>
@endsection