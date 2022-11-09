<h3>Total Data : {{ $total }}</h3>
<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
    <thead>
        <tr>
          <th>No</th>
          <th>NO PO</th>
          <th>Tanggal PO</th>
          <!-- <th>Tanggal Levering PO</th> -->
          <!-- <th>No Kuitansi</th> -->
          <th>Supplier</th>
          <th>Barang</th>
          <th>Harga Supplier</th>
            
            <th>Jumlah</th>
            <th class="text-center" width="150">Aksi</th>
        </tr>
    </thead>
    <tbody>
      <?php $no = 1;?>
        @foreach($data as $value)
        <tr>
          <td align="center">{{ $no }}</td>
          <td>{{ $value->kode_po }}</td>
          <td>{{ tglIndo($value->tanggal_po) }}</td>
          <!-- <td>{{ tglIndo($value->tanggal_livering_po) }}</td> -->
          <!-- <td>{{ $value->no_kwitansi }}</td> -->

          <td>{{ $value->nama_supplier }}</td>
          <td>
            @if($value->jenis_pekerjaan == 1)
              {{ $value->stockcode }}<br>
              <b>{{ $value->nama_stockcode }}</b>
            @else
              {{ $value->pr }}<br>
              <b>{{ $value->nama_pr }}</b>
            @endif
          </td>
          <td align="right">{{ toRp($value->harga) }}</td>
          
          <td align="center">{{ $value->jumlah }}</td>
          <td class="text-center" width="150">
            <a href="{{ url('usaha/po/'.$value->po_id) }}" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>
          </td>
        </tr>
        <?php $no++;?>
      @endforeach

    </tbody>

</table>
