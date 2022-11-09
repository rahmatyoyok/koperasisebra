<h3 class="form-section">Rincian</h3>

<table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
    <thead>
        <tr>
            <th>No</th>
            <th>Keterangan</th>
            <th>Tanggal</th>
            <th>In</th>
            <th>Rp</th>
            <th>Jumlah</th>
            <th>Out</th>
            <th>Rp</th>
            <th>Jumlah</th>
            <th>Saldo</th>
            <th>Rp</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody>
        
        @php
        $no = 1;  
        $startJumlahPembelian = 0;
        $startJumlahPenjualan = 0;
        // $startJumlahAkhir = 22000;
        // $startRp = 2200;
        // $startStock = 10;  
        $startJumlahAkhir = $jumlah;
        $startRp = $harga;
        $startStock = $stok;  

        //Var
        $subRp = $harga;
        @endphp
        
        <tr>
            <td align="center">1</td>
            <td>Start Awal</td>
            <td align="center">-</td>
            <td align="center"></td>
            <td align="right"></td>
            <td align="right"></td>
            <td align="center"></td>
            <td align="right"></td>
            <td align="right"></td>
            
            <td align="center">{{ $startStock }}</td>
            <td align="right">{{ toRp($startRp) }}</td>
            <td align="right">{{ toRp($startJumlahAkhir) }}</td>
        </tr>
        
        @foreach ($data as $item)
        @php
        
        if ($item->type == 'Pembelian'){
            $startStock = $startStock+$item->qty;
            $harga = $item->price;
            $subTotal = $item->qty*$item->price;
            $startJumlahAkhir = $startJumlahAkhir+$subTotal;
        }else{
            //Penjualan
            $startStock = $startStock-$item->qty;
            if($startStock == 0){
            
            }else{

            }
            
            $subTotal = $item->qty*$harga;
            $startJumlahAkhir = $startJumlahAkhir-$subTotal;
        }
        
        
        $subRp = @($startJumlahAkhir/$startStock);
        if($startStock == 0){
            
        }
        $no++;
        @endphp
        <tr>
            <td align="center">{{ $no }}</td>
            <td>{{ $item->type }}</td>
            <td align="center">{{ tglIndo($item->time) }}</td>
            @if ($item->type == 'Pembelian')
                <td align="center">{{ $item->qty }}</td>
                <td align="right">{{ toRp($item->price) }}</td>
                <td align="right">{{ toRp($subTotal) }}</td>
                <td align="center"></td>
                <td align="right"></td>
                <td align="right"></td>
            @else 
                <td align="center"></td>
                <td align="right"></td>
                <td align="right"></td>
                <td align="center">{{ $item->qty }}</td>
                <td align="right">{{ toRp($harga) }}</td>
                <td align="right">{{ toRp($subTotal) }}</td>
            @endif
            
            <td align="center">{{ $startStock }}</td>
            <td align="right">{{ toRp($subRp) }}</td>
            <td align="right">{{ toRp($startJumlahAkhir) }}</td>
        </tr>
        @php
            // $startRp = $startJumlahAkhir/$startStock;
            $startRp = @($startJumlahAkhir/$startStock);
        @endphp
        @endforeach
        
        
    </tbody>
</table>
<input type="hidden" id="stok" value="{{ $startStock }}">
<input type="hidden" id="harga" value="{{ number_format(floatval($subRp),0,'','') }}">
<input type="hidden" id="jumlah" value="{{ number_format(floatval($startJumlahAkhir),0,'','') }}">
{!! Form::button('Simpan Kartu Stok', ['class' => 'btn blue tooltips', 'type' => 'button', 'id'=>'simpanData', 'style' => 'margin-right:5px;']) !!}

<script>
$(document).ready(function(){

$("#simpanData").on("click", function(){
    App.blockUI();

    var stok = $("#stok").val();
    var harga = $("#harga").val();
    var jumlah = $("#jumlah").val();
    var tahun = $("#tahun").val();
    var triwulan = $("#triwulan").val();
    var item = $("#item").val();
    
    $.ajax({
        type: "GET",
        url: "{{ url('akuntansi/toko/simpan-kartu-stok') }}?stok="+stok+"&harga="+harga+"&jumlah="+jumlah+"&tahun="+tahun+"&triwulan="+triwulan+"&item="+item,
        dataType: "html",
        success:function(data){
                
                App.unblockUI();
        },
        error: function(xhr){
                App.unblockUI();


        }
    });
});

});
</script>