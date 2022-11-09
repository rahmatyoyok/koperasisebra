@extends('layouts.master')

@section('title', 'Beranda Usaha Umum')

@section('content')

<!-- END PAGE HEAD-->
<!-- BEGIN PAGE HEAD-->
<div class="page-head">
    <div class="container">
        <!-- BEGIN PAGE TITLE -->
        <div class="page-title">
            <h1>Beranda Usaha Umum
            </h1>
        </div>
    </div>
</div>
<!-- END PAGE HEAD-->
<!-- BEGIN PAGE CONTENT BODY -->
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 blue" href="{{ url('usaha/wo') }}">
                    <div class="visual">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="{{ $totalWorkOrder }}">{{ $totalWorkOrder }}</span>
                        </div>
                        <div class="desc"> Total Work Order </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 red" href="{{ url('usaha/po') }}">
                    <div class="visual">
                        <i class="fa fa-shopping-cart"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="{{ $totalPurchaseOrder }}">{{ $totalPurchaseOrder }}</span> </div>
                        <div class="desc"> Total Purchase Order </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 purple" href="{{ url('usaha/persekotpo') }}">
                    <div class="visual">
                        <i class="fa fa-money"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="{{ $totalPersekotPO }}">{{ $totalPersekotPO }}</span>
                        </div>
                        <div class="desc"> Total Persekot PO</div>
                    </div>
                </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 green" href="{{ url('usaha/persekot') }}">
                    <div class="visual">
                        <i class="fa fa-money"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="{{ $totalPersekot }}">{{ $totalPersekot }}</span>
                        </div>
                        <div class="desc"> Total Persekot </div>
                    </div>
                </a>
            </div>
            <!-- <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat dashboard-stat-v2 purple" href="{{ url('usaha/purchase') }}">
                    <div class="visual">
                        <i class="fa fa-money"></i>
                    </div>
                    <div class="details">
                        <div class="number">
                            <span data-counter="counterup" data-value="{{ $totalPurchase }}">{{ $totalPurchase }}</span>
                        </div>
                        <div class="desc"> Total Pembelian Langsung</div>
                    </div>
                </a>
            </div> -->
        </div>

        <div class="row">
          <div class="col-lg-12 col-xs-12 col-sm-12">
              <div class="portlet light bordered">
                <div class="portlet-title">
                      <div class="caption">
                          <i class="icon-bubble font-dark hide"></i>
                          <span class="caption-subject font-hide bold uppercase">Jatuh Tempo Persekot</span>
                      </div>

                </div>
                <div class="portlet-body">
                    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No Persekot</th>
                                <th>Nama SPV</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Jatuh Tempo</th>
                                <th>Nominal</th>
                                <th>Penerima</th>
                                <th class="text-center" width="150">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jatuhTempoPersekot as $value)
                            <tr>
                                <td>{{ $value->no_persekot}}</td>
                                <td>{{ $value->nama_spv}}</td>
                                <td>{{ tglIndo($value->tgl_pengajuan) }}</td>
                                <td>{{ tglIndo($value->tgl_jatuhtempo) }}</td>
                                <td align="right">{{ toRp($value->jumlah) }}</td>
                                <td>{{ $value->tujuan_transfer}}</td>
                                <td class="text-center" width="150">
                                <a href="{{ url('usaha/persekot/'.$value->persekot_id.'') }}" class="btn btn-xs blue " >Detail</a>
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
<!-- END PAGE CONTENT BODY -->


@endsection
