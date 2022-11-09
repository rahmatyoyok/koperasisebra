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
<h3 style="text-align:center;text-decoration: underline;">PEMBELIAN LANGSUNG</h3>
<br>
<table class="no-border">
  <thead class="no-border">
    <tr>
      <td class="no-border" style="width:150px;">Nomor</td>
      <td class="no-border" style="width:10px;">:</td>
      <td class="no-border">{{ $data_edit->kode }}</td>
    </tr>
    <tr>
      <td class="no-border">Tanggal Pembelian</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ tglIndo($data_edit->tanggal_pembelian) }}</td>
    </tr>
    <tr>
      <td class="no-border">Diterima Oleh</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $data_edit->diterima }}</td>
    </tr>
    <tr>
      <td class="no-border">Jenis Pembayaran</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $data_edit->jenis_pembayaran }}</td>
    </tr>
    <tr>
      <td class="no-border">Keterangan</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $data_edit->keterangan }}</td>
    </tr>

  </thead>
</table>

<br>
<table>
  <thead>
    <tr>
      <th>No.</th>
      <th>COA</th>
      <th>Keterangan</th>
      <th>Sub Total</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=0;?>
    @foreach($detail as $value)
    <?php
    $no++;
    ?>
      <tr>
        <td align="center">{{ $no }}</td>
        <td>{{ $value->code }}-{{ $value->desc }}</td>
        <td>{{ $value->keterangan }}</td>
        <td align="right">{{ toRp($value->jumlah) }}</td>
      </tr>

    @endforeach
    <tr>
      <td colspan="3" align="right" style="font-weight:bold;">Total</td>
      <td align="right">{{ toRp($data_edit->total) }}</td>
    </tr>
  </tbody>
</table>
<br>

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
