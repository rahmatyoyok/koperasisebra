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
<h3 style="text-align:center;text-decoration: underline;">
  BERITA ACARA PEMBAYARAN INVESTASI
</h3>
<br>

<div class="custom">
Pada hari ini  {{ strtoupper(hariIndo($data->tr_date)) }}     tanggal   {{ strtoupper(terbilang(date('d',strtotime($data->tr_date)))) }}   bulan {{ strtoupper(getInfoBulan($data->tr_date)) }} tahun  {{ strtoupper(terbilang(date('Y',strtotime($data->tr_date)))) }} bertempat di Kantor Koperasi Sebra, kami :
<br>
<br>
<table class="no-border">
  <thead class="no-border">
    <tr>
      <td class="no-border" style="width:10px;">I</td>
      <td class="no-border" style="width:100px;">Nama</td>
      <td class="no-border" style="width:10px;">:</td>
      <td class="no-border"><b>KPRI SEBRA PJB BRANTAS</b></td>
    </tr>
    <tr>
      <td class="no-border"></td>
      <td class="no-border">Alamat</td>
      <td class="no-border">:</td>
      <td class="no-border">Jl. Basuki Rahmat 269 Karangkates, Sumberpucung, Malang.</td>
    </tr>

  </thead>
</table>
<br>
Telah diterimakan kepada :
<br>
<br>
<table class="no-border">
  <thead class="no-border">
    <tr>
      <td class="no-border" style="width:10px;">II</td>
      <td class="no-border" style="width:100px;">Nama</td>
      <td class="no-border" style="width:10px;">:</td>
      <td class="no-border">{{ $anggota->first_name }} {{ $anggota->last_name }}</td>
    </tr>
    <tr>
      <td class="no-border"></td>
      <td class="no-border">Alamat</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $anggota->address_1 }}</td>
    </tr>
    <tr>
      <td class="no-border"></td>
      <td class="no-border">Jumlah</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ toRp($data->total) }}</td>
    </tr>

  </thead>
</table>
<br>
Sebagai bentuk penarikan investasi dari Sdr. {{ $anggota->first_name }} {{ $anggota->last_name }}, adapun saldo investasi setelah penarikan ini  Rp 55.000.000,00 (lima puluh lima juta rupiah).
<br>
<br>
Demikian berita acara pembayaran investasi atas perhatian dan kerjasamanya diucapkan terima kasih.

</div>
<br>
<br>
<div style="width:100%;">

  <div style="width:35%;float:left;">
    <table class="no-border">
      <thead class="no-border">
        <tr>
          <td class="no-border" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td class="no-border bold-text" align="center">
            Bendahara
          </td>
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
          <td class="no-border bold-text" align="center">
            Iqbal
          </td>
        </tr>
      </thead>
    </table>
  </div>
  <div style="width:30%;float:left;">
    <table class="no-border">
      <thead class="no-border">
        <tr>
          <td class="no-border" align="center">Mengetahui</td>
        </tr>
        <tr>
          <td class="no-border" align="center">

          </td>
        </tr>
        <tr>
          <td class="no-border bold-text" align="center">
            Ketua  KPRI Sebra
          </td>
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
          <td class="no-border bold-text" align="center" >
            Dwi Wahyu P.
          </td>
        </tr>
      </thead>
    </table>
  </div>
  <div style="width:35%;float:left;">
    <table class="no-border">
      <thead class="no-border">
        <tr>
          <td class="no-border" align="center">Karangkates, {{ tglIndo(date('Y-m-d'))}}</td>
        </tr>
        <tr>
          <td class="no-border" align="center">

          </td>
        </tr>
        <tr>
          <td class="no-border bold-text" align="center">
            Pemohon
          </td>
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
          <td class="no-border bold-text" align="center" >
            {{ $anggota->first_name }} {{ $anggota->last_name }}
          </td>
        </tr>
      </thead>
    </table>
  </div>
</div>

@endsection
