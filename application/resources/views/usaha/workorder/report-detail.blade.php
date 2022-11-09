<h3>Total Data : {{ $total }}</h3>
<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
    <thead>
        <tr>
          <th>No</th>
          <th>Kode</th>
          <th>Client</th>
            <th>Jenis</th>
            <th>Lokasi</th>
            <th>Pekerjaan</th>
            <th>Nominal</th>
            <th>Tanggal Dibuat</th>
            <th class="text-center" width="150">Aksi</th>
        </tr>
    </thead>
    <tbody>
      <?php $no = 1;?>
        @foreach($data as $value)
        <tr>
          <td align="center">{{ $no }}</td>
          <td>{{ $value->kode_wo }}</td>
          <td>{{ $value->client }}</td>
          <td>{{ jenisPekerjaan($value->jenis_pekerjaan) }}</td>
          <td>{{ $value->lokasi }}</td>
          <td>{{ $value->nama_pekerjaan }}</td>
          <td align="right">{{ toRp($value->nilai_pekerjaan) }}</td>
          <td>{{ tglIndo($value->created_at) }}</td>
          <td class="text-center" width="150">
            <a href="{{ url('usaha/wo/'.$value->wo_id) }}" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>
          </td>
        </tr>
        <?php $no++;?>
      @endforeach

    </tbody>

</table>
