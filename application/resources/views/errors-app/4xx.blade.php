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
            <span>{{$exception->getStatusCode()}}</span>
        </li>
    </ul>
</div>

<h1 class="page-title"> ERROR {{$exception->getStatusCode()}}
</h1>

<div class="row">
    <div class="col-md-12 page-404">
        <div class="number font-red"> {{$exception->getStatusCode()}} </div>
        <div class="details">
            <h3>Halaman tidak tersedia.</h3>
            <p> Maaf kami tidak dapat menemukan halaman yang Anda cari.
                <br/>
                <a href="{{ url()->previous() }}" class="btn red btn-outline"> Kembali </a> 
            </p>
        </div>
    </div>
</div>
@endsection