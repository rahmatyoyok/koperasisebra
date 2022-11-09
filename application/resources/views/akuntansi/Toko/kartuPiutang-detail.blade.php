<h3 class="form-section">Daftar Anggota</h3>
<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
    <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">No Anggota</th>
            <th width="15%">Nama Anggota</th>
            <th width="15%">Nominal</th>
            <th width="5%">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
        <tr>
            <td width="5%" align="center">{{ $loop->iteration }}</td>
            <td width="15%">{{ $item->niak }}</td>
            <td width="40%">{{ $item->first_name }}</td>
            <td width="15%" align="right">{{ toRp($item->payment_amount) }}</td>
            <td width="15%" align="center">
                <a href="{{ url('akuntansi/toko/kartu-piutang-detail?person='.$item->person_id.'&month='.$month.'&year='.$year.'&status='.$status) }}" class="btn btn-xs purple-sharp tooltips" title="" data-original-title="Detail Data">Detail</a>
            </td>
        </tr>
        @endforeach
        
       
    </tbody>
</table>