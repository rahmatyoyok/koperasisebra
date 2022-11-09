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
  @if($status == 1)
   PERMINTAAN PERSEKOT
  @elseif($status == 2)
   VERIFIKASI DAN PERSETUJUAN PERSEKOT
  @elseif($status == 3)
    PERTANGGUNG JAWABAN PERSEKOT
  @elseif($status == 4)
    REALISASI PNPO PERSEKOT
  @elseif($status == 5)

  @elseif($status == 99)

  @else
  @endif
</h3>
<br>
<table class="no-border">
  <thead class="no-border">
    <tr>
      <td class="no-border" style="width:100px;">Nomor</td>
      <td class="no-border" style="width:10px;">:</td>
      <td class="no-border">{{ $data_edit->no_persekot }}</td>
    </tr>
    @if($status == 1)
    <tr>
      <td class="no-border">Dibuat Oleh</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $petugas_id->name }}</td>
    </tr>
    @elseif($status == 2)
    <tr>
      <td class="no-border">Diverifikasi Oleh</td>
      <td class="no-border">:</td>
      <td class="no-border">Iqbal</td>
    </tr>
    @elseif($status == 3)
    <tr>
      <td class="no-border">Diverifikasi Oleh</td>
      <td class="no-border">:</td>
      <td class="no-border">Iqbal</td>
    </tr>
    @elseif($status == 4)
    <tr>
      <td class="no-border">Dibuat Oleh</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $petugas_id->name }}</td>
    </tr>
    @elseif($status == 5)

    @elseif($status == 99)

    @else
    @endif

    <tr>
      <td class="no-border">Jumlah</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ toRp($data_edit->jumlah) }}</td>
    </tr>
    @if($status == 1)
    <tr>
      <td class="no-border">Proses</td>
      <td class="no-border">:</td>
      <td class="no-border">
        Permintaan Persekot
      </td>
    </tr>
    @elseif($status == 2)
    <tr>
      <td class="no-border">Proses</td>
      <td class="no-border">:</td>
      <td class="no-border">
        Persetujuan Persekot
      </td>
    </tr>
    @elseif($status == 3)
    <tr>
      <td class="no-border">Proses</td>
      <td class="no-border">:</td>
      <td class="no-border">
        Pertanggung Jawaban Persekot
      </td>
    </tr>
    @elseif($status == 4)
    <tr>
      <td class="no-border">Proses</td>
      <td class="no-border">:</td>
      <td class="no-border">
        Realisasi PNPO Persekot
      </td>
    </tr>
    @elseif($status == 5)

    @elseif($status == 99)

    @else
    @endif

  </thead>
</table>

<br>
@if($status == 1 || $status == 2)
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Sub Dit</th>
      <th>Uraian</th>
      <th>Jumlah</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td align="center">1</td>
      <td>{{ $spv_id->bagian_spv }}</td>
      <td>{{ $data_edit->keterangan }}</td>
      <td align="right">{{ toRp($data_edit->jumlah) }}</td>
    </tr>
    <!-- <tr>
      <td colspan="3" align="right" style="font-weight:bold;">Jumlah</td>
      <td align="right">{{ toRp($data_edit->jumlah) }}</td>
    </tr> -->
  </tbody>
</table>
@elseif($status == 3)
<?php
$selisih = $data_edit->jumlah_asli-$data_edit->jumlah;
$labelRealisasi = "";
if($selisih != 0){
  if($selisih >= 0){
    $labelRealisasi = "Yang bersangkutan harus mengembalikan sebesar ".toRp($selisih).".";
  }else{
    $labelRealisasi = "Yang bersangkutan dapat kembalian sebesar ".toRp(abs($selisih)).".";
  }
}
?>
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Sub Dit</th>
      <th>Uraian</th>
      <th>Realisasi</th>
      <th>Persekot</th>
      <th>Selisih</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td align="center">1</td>
      <td>{{ $spv_id->bagian_spv }}</td>
      <td>{{ $data_edit->keterangan }}</td>
      <td align="right">{{ toRp($data_edit->jumlah) }}</td>
      <td align="right">{{ toRp($data_edit->jumlah_asli) }}</td>
      <td align="right">{{ toRp(abs($selisih)) }}</td>
    </tr>
    <!-- <tr>
      <td colspan="3" align="right" style="font-weight:bold;">Jumlah</td>
      <td align="right">{{ toRp($data_edit->jumlah) }}</td>
      <td align="right">{{ toRp($data_edit->jumlah_asli) }}</td>
      <td align="right">{{ toRp($selisih) }}</td>
    </tr> -->
  </tbody>
</table>
@elseif($status == 4)
<table>
  <thead>
    <tr>
      <th>No</th>
      <th>Sub Dit</th>
      <th>Uraian</th>
      <th>Jumlah</th>
      <th>Margin</th>
      <th>PPN</th>
      <th>PPH</th>
      <th>Total</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td align="center">1</td>
      <td>{{ $spv_id->bagian_spv }}</td>
      <td>{{ $data_edit->keterangan }}</td>
      <td align="right">{{ toRp($data_edit->jumlah) }}</td>
      <td align="center">{{ $data_edit->margin }}%</td>
      <td align="center">{{ $data_edit->ppn }}%</td>
      <td align="center">{{ $data_edit->pph }}%</td>
      <td align="right">{{ toRp($data_edit->margin_val) }}</td>
    </tr>
    <!-- <tr>
      <td colspan="3" align="right" style="font-weight:bold;">Jumlah</td>
      <td align="right">{{ toRp($data_edit->jumlah) }}</td>
    </tr> -->
  </tbody>
</table>
@endif

<br>

<table class="no-border">
  <thead class="no-border">
    <tr>
      <td class="no-border" style="width:100px;">Terbilang</td>
      <td class="no-border" style="width:10px;">:</td>
      <td class="no-border"><b>{{ terbilang($data_edit->jumlah) }} Rupiah</b></td>
    </tr>
    <tr>
      <td class="no-border">Nama Penerima</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ $data_edit->tujuan_transfer }}</td>
    </tr>
    <tr>
      <td class="no-border">No.Rek</td>
      <td class="no-border">:</td>
      <td class="no-border">
        @if($data_edit->metode_penerimaan == 1)
        {{ $bank_id->nama_bank }} - {{ $data_edit->no_rekening }}
        @endif
      </td>
    </tr>
    <tr>
      <td class="no-border">Jatuh Tempo</td>
      <td class="no-border">:</td>
      <td class="no-border">{{ tglIndo($data_edit->tgl_jatuhtempo) }}</td>
    </tr>
  </thead>
</table>
<br>
<div class="custom">
@if($status == 3)
  {{ $labelRealisasi }}
@endif
Demikian atas perhatian dan kerjasamanya, diucapkan terima kasih.
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
          <td class="no-border" align="center">&nbsp;</td>
        </tr>
        <tr>
          <td class="no-border" align="center">
            @if($status == 1)

            @elseif($status == 2)
             Setuju Dibayar,
            @elseif($status == 3)

            @elseif($status == 4)

            @elseif($status == 5)

            @elseif($status == 99)

            @else
            @endif
          </td>
        </tr>
        <tr>
          <td class="no-border bold-text" align="center">
            @if($status == 1)

            @elseif($status == 2)
             Bendahara KPRI SEBRA
            @elseif($status == 3)
            Bendahara KPRI SEBRA
            @elseif($status == 4)
            Bendahara KPRI SEBRA
            @elseif($status == 5)

            @elseif($status == 99)

            @else
            @endif
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
            @if($status == 1)

            @elseif($status == 2)
             IQBAL NABIL
            @elseif($status == 3)
            IQBAL NABIL
            @elseif($status == 4)
            IQBAL NABIL
            @elseif($status == 5)

            @elseif($status == 99)

            @else
            @endif
          </td>
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
          <td class="no-border" align="center">
            @if($status == 1)
             Dibuat
            @elseif($status == 2)
             Menyetujui,
            @elseif($status == 3)

            @elseif($status == 4)

            @elseif($status == 5)

            @elseif($status == 99)

            @else
            @endif
          </td>
        </tr>
        <tr>
          <td class="no-border bold-text" align="center">

            @if($status == 1)
             {{ $spv_id->jabatan_spv }}
            @elseif($status == 2)
             Ketua Pengurus KPRI SEBRA
            @elseif($status == 3)
              Ketua Pengurus KPRI SEBRA
            @elseif($status == 4)
              Ketua Pengurus KPRI SEBRA
            @elseif($status == 5)

            @elseif($status == 99)

            @else
            @endif
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

            @if($status == 1)
             {{ $spv_id->nama_spv }}
            @elseif($status == 2)
              DWI WAHYU PUJIARTO
            @elseif($status == 3)
              DWI WAHYU PUJIARTO
            @elseif($status == 4)
              DWI WAHYU PUJIARTO
            @elseif($status == 5)

            @elseif($status == 99)

            @else
            @endif
          </td>
        </tr>
      </thead>
    </table>
  </div>
</div>

@endsection
