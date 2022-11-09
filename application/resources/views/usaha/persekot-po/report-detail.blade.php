<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
    <thead>
        <tr>
          <th>No</th>
            <th>No Persekot</th>
            <th>SPV</th>
            <th>Tanggal Pengajuan</th>
            <th>Jatuh Tempo</th>
            <th>Nominal</th>
            <th>Keterangan</th>
            <th>Status</th>
            <th class="text-center" width="150">Aksi</th>
        </tr>
    </thead>
    <tbody>
      <?php $no = 1; ?>
      @foreach($data as $value)
        <tr>
          <td>{{ $no }}</td>
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
          <td>
            
            @if($value->status == 1)
             Belum Diverifikasi
            @elseif($value->status == 2)
             Disetujui
            @elseif($value->status == 4)
             Realisasi
            @elseif($value->status == 99)
             Ditolak
            @else
            @endif
          </td>
          <td align="center">
            <a href="{{ url('usaha/persekotpo/'.$value->persekot_id.'') }}" class="btn btn-xs blue tooltips" title="Detail Persekot">Detail</a>
          </td>
        </tr>
        <?php $no++; ?>
      @endforeach
    </tbody>
</table>
