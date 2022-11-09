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
<h3 style="text-align:center;text-decoration: underline;">PURCHASE ORDER</h3>
<br>
<table class="no-border">
  <thead class="no-border">
    <tr>
      <td class="no-border" style="width:100px;">Nomor PO</td>
      <td class="no-border" style="width:10px;">:</td>
      <td class="no-border">{{ $data_edit->kode_po }}</td>
    </tr>
    <tr>
      <td class="no-border">No Kwitansi</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $data_edit->no_kwitansi }}</td>
    </tr>
    <tr>
      <td class="no-border">Tanggal PO</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ tglIndo($data_edit->tanggal_po) }}</td>
    </tr>
    <tr>
      <td class="no-border">Tanggal Levering</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ tglIndo($data_edit->tanggal_livering_po) }}</td>
    </tr>
  </thead>
</table>

<br>
<table>
  <thead>
    <tr>
      <th>No.</th>
      <th>Item</th>
      <th>Nominal</th>
      <th>Jumlah</th>
      <th>Sub Total</th>
    </tr>
  </thead>
  <tbody>
    <?php $totalDetail = 0;$no=0;?>
    @foreach($dataDetail as $value)
    <?php $subTotal = $value->harga*$value->jumlah;
    $totalDetail = $totalDetail + $subTotal;
    $no++;
    ?>
      <tr>
        <td align="center">{{ $no }}</td>
        <td>
          @if($value->jenis_pekerjaan == 1)
            {{ $value->stockcode }}-
            <b>{{ $value->nama_stockcode }}</b>
          @else
            {{ $value->pr }}-
            <b>{{ $value->nama_pr }}</b>
          @endif

        </td>

        <td align="right">{{ toRp($value->harga) }}</td>
        <td align="center">{{ $value->jumlah }} {{ $value->satuan }}</td>
        <td align="right">{{ toRp($subTotal) }}</td>
      </tr>

    @endforeach
    <tr>
      <td colspan="4" align="right" style="font-weight:bold;">Total</td>
      <td align="right">{{ toRp($totalDetail) }}</td>
    </tr>
  </tbody>
</table>
<br>

<table class="no-border">
  <thead class="no-border">
    <tr>
      <td class="no-border" style="width:100px;">BBM+Konsumsi</td>
      <td class="no-border" style="width:10px;">:</td>
      <td class="no-border">{{ toRp($data_edit->bbm_konsumsi)}}</td>
    </tr>
    <tr>
      <td class="no-border">PPN</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ toRp($data_edit->nominal_ppn)}}</td>
    </tr>
    <tr>
      <td class="no-border">PPH</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ toRp($data_edit->nominal_pph)}}</td>
    </tr>
    <!-- <tr>
      <td class="no-border">Detail+BBM+Konsumsi</td>
      <td class="no-border">:</td>
      <td class="no-border">Val</td>
    </tr> -->
    <tr>
      <td class="no-border">Total</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ toRp($data_edit->total)}}</td>
    </tr>
  </thead>
</table>
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
