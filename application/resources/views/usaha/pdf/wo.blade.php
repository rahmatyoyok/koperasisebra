@extends('usaha.pdf.master')


@section('content')
<style>
.page-break {
    page-break-after: always;
}
</style>
<table class="no-border">
  <thead class="no-border">
        <tr class="no-border">
          <th width="100px" class="no-border">
            <img src="{{assets('koperasi.png')}}" width="80px" style="padding-top:20px;">

            <span style="font-size: 13px;padding-top:-30px;">KPRI SEBRA</span>
          </th>
          <th class="no-border">
            <span style="font-size: 13px;">KOPERASI  PEGAWAI  REPUBLIK  INDONESIA</span>
            <br />
            <span style="font-size: 15px;">“ S E B R A “</span>
            <br />
            <span style="font-size: 12px;">PT PJB UNIT BRANTAS</span>
            <br />
            <span style="font-size: 12px;">Jl. Jendral Basuki Rahmat 269 Karangkates – Sumberpucung – Malang 65165</span>
            <br />
            <span style="font-size: 12px;">Nomor  Badan Hukum : 7025/BH/II/ ’91 Tanggal 24 Juni 1991</span>
            <br />
            <span style="font-size: 12px;">Telp. (0341) 385545  Ext. 145, Fax. (0341) 383442, Bank : BNI  1946  Kpj</span>
          </th>
        </tr>
  </thead>
</table>
<hr>
<h3 style="text-align:center;text-decoration: underline;">WORK ORDER</h3>
<br>
<table class="no-border">
  <thead class="no-border">
    <tr>
      <td class="no-border" style="width:100px;">Nomor WO</td>
      <td class="no-border" style="width:10px;">:</td>
      <td class="no-border">{{ $data_edit->kode_wo }}</td>
    </tr>
    <tr>
      <td class="no-border">Client</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $client->nama_client }}</td>
    </tr>
    <tr>
      <td class="no-border">Nama Pekerjaan</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $data_edit->nama_pekerjaan }}</td>
    </tr>

    <tr>
      <td class="no-border">Jenis Pekerjaan</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ jenisPekerjaan($data_edit->jenis_pekerjaan) }}</td>
    </tr>
    <tr>
      <td class="no-border">Lokasi Pekerjaan</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $lokasi->nama_lokasi }}</td>
    </tr>
    <tr>
      <td class="no-border">Nilai Pekerjaan</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ toRp($data_edit->nilai_pekerjaan) }}</td>
    </tr>
    <tr>
      <td class="no-border">No. Referensi</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $data_edit->no_refrensi }}</td>
    </tr>
    <tr>
      <td class="no-border">Jenis Transaksi</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ jenisWO($data_edit->jenis_wo) }}</td>
    </tr>
    <tr>
      <td class="no-border">Keterangan</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $data_edit->keterangan }}</td>
    </tr>
  </thead>
</table>

<br>
@if($data_edit->jenis_wo == 1)

<hr>
<h4>Purchase Order</h4>
<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
    <thead>
        <tr>
          <th>No PO</th>
          <th>No Kwitansi</th>
            <th>Tanggal PO</th>
            <th>Tanggal Levering PO</th>
            <!-- <th>BBM Konsumsi</th> -->
            <th>Total Biaya</th>

        </tr>
    </thead>
    <tbody>

      @foreach($dataPO as $value)
        <tr>
          <td>{{ $value->kode_po }}</td>
          <td>{{ $value->no_kwitansi }}</td>
          <td>{{ tglWaktuIndo($value->tanggal_po) }}</td>
          <td>{{ tglWaktuIndo($value->tanggal_livering_po) }}</td>
          <!-- <td align="right">{{ toRp($value->bbm_konsumsi) }}</td> -->
          <td align="right">{{ toRp($value->total) }}</td>


        </tr>
      @endforeach

    </tbody>
</table>
@else

<hr>
<h4>Persekot PO</h4>
<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
    <thead>
        <tr>
            <th>No Persekot</th>
            <th>SPV</th>
            <th>Tanggal Pengajuan</th>
            <th>Jatuh Tempo</th>
            <th>Nominal</th>
            <th>Keterangan</th>
            <th>Status</th>
            
        </tr>
    </thead>
    <tbody>
        @foreach($dataPersekotPO as $value)
        <tr>
            <td>{{ $value->no_persekot }}</td>
            <td>
            <b>{{ $value->nama_spv }}</b><br>
            <!-- {{ $value->nip_spv }}<br> -->
            {{ $value->jabatan_spv }}
            </td>
            <td>{{ tglIndo($value->tgl_pengajuan) }}</td>
            <td>{{ tglIndo($value->tgl_jatuhtempo) }}</td>
            <td align="right">{{ toRp($value->jumlah) }}</td>
            <td>{{ $value->keterangan }}</td>
            <td>{{ statusPersekot($value->status) }}</td>

        </tr>
        @endforeach
    </tbody>
</table>
@endif

<br>
<!--
<table class="no-border">
  <thead class="no-border">
    <tr>
      <td class="no-border" style="width:100px;">BBM+Konsumsi</td>
      <td class="no-border" style="width:10px;">:</td>
      <td class="no-border">Val</td>
    </tr>
    <tr>
      <td class="no-border">PPN</td>
      <td class="no-border">:</td>
      <td class="no-border">Val</td>
    </tr>
    <tr>
      <td class="no-border">PPH</td>
      <td class="no-border">:</td>
      <td class="no-border">Val</td>
    </tr>
    <tr>
      <td class="no-border">Detail+BBM+Konsumsi</td>
      <td class="no-border">:</td>
      <td class="no-border">Val</td>
    </tr>
    <tr>
      <td class="no-border">Total</td>
      <td class="no-border">:</td>
      <td class="no-border">Val</td>
    </tr>
  </thead>
</table> -->
<br>
<div class="custom">
Demikian atas perhatian dan kerjasamanya, diucapkan terima kasih.
<!--
<br>
<br>
<table class="no-border">
  <thead class="no-border">
    <tr>
      <td class="no-border bold" style="width:65px;font-weight:bold;" valign="top">Perhatian :</td>
      <td class="no-border bold" style="font-weight:bold;">Penandatanganan ini menjamin bahwa transaksi yang terdapat pada form ini telah sesuai dengan peraturan yang berlaku.</td>
    </tr>
  </thead>
</table>
</div>
<br>
<br>
<div style="width:100%;">
  <div style="width:35%;float:left;">
    <table class="no-border">
      <thead class="no-border">
        <tr>
          <td class="no-border" align="center">Karangkates, {{ tglIndo(date('Y-m-d'))}}</td>
        </tr>
        <tr>
          <td class="no-border" align="center">Dibuat</td>
        </tr>
        <tr>
          <td class="no-border" align="center">SPV</td>
        </tr>
        <tr>
          <td class="no-border" align="center">
            <br>
            <br>
            <br>
            <br>
            <br>
          </td>
        </tr>
        <tr>
          <td class="no-border" align="center">Rizal</td>
        </tr>
      </thead>
    </table>
  </div>
  <div style="width:30%;float:left;">

  </div>
  <div style="width:35%;float:left;">
    <table class="no-border">
      <thead class="no-border">
        <tr>
          <td class="no-border" align="center">Karangkates, {{ tglIndo(date('Y-m-d'))}}</td>
        </tr>
        <tr>
          <td class="no-border" align="center">Dibuat</td>
        </tr>
        <tr>
          <td class="no-border" align="center">SPV</td>
        </tr>
        <tr>
          <td class="no-border" align="center">
            <br>
            <br>
            <br>
            <br>
            <br>
          </td>
        </tr>
        <tr>
          <td class="no-border" align="center">Rizal</td>
        </tr>
      </thead>
    </table>
  </div> -->
</div>

@endsection
