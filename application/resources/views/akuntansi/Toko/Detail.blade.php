<h3 class="form-section">Pembelian Tunai</h3>
    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Buku Besar</th>
                <th width="40%">Keterangan</th>
                <th width="15%">Debit</th>
                <th width="15%">Kredit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="5%" align="center">1</td>
                <td width="15%">1000601</td>
                <td width="40%">Persediaan</td>
                <td width="15%" align="right">{{ toRp($pembelianTunai) }}</td>
                <td width="15%" align="right"></td>
            </tr>
            <tr>
                <td width="5%" align="center">2</td>
                <td width="15%">1000103</td>
                <td width="40%">Kas Toko</td>
                <td width="15%" align="right"></td>
                <td width="15%" align="right">{{ toRp($pembelianTunai) }}</td>
            </tr>
            <tr>
                <td colspan="3" align="right" class="bold">Total</td>
                <td width="15%" align="right">{{ toRp($pembelianTunai) }}</td>
                <td width="15%" align="right">{{ toRp($pembelianTunai) }}</td>
            </tr>
        </tbody>
    </table>

    <h3 class="form-section">Pembelian Hutang</h3>
    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Buku Besar</th>
                <th width="40%">Keterangan</th>
                <th width="15%">Debit</th>
                <th width="15%">Kredit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="5%" align="center">1</td>
                <td width="15%">1000601</td>
                <td width="40%">Persediaan</td>
                <td width="15%" align="right">{{ toRp($pembelianHutang) }}</td>
                <td width="15%" align="right"></td>
            </tr>
            <tr>
                <td width="5%" align="center">2</td>
                <td width="15%">3000202</td>
                <td width="40%">Hutang</td>
                <td width="15%" align="right"></td>
                <td width="15%" align="right">{{ toRp($pembelianHutang) }}</td>
            </tr>
            <tr>
                <td colspan="3" align="right" class="bold">Total</td>
                <td width="15%" align="right">{{ toRp($pembelianHutang) }}</td>
                <td width="15%" align="right">{{ toRp($pembelianHutang) }}</td>
            </tr>
        </tbody>
    </table>

    <h3 class="form-section">Pembayaran Hutang Pembelian </h3>
    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Buku Besar</th>
                <th width="40%">Keterangan</th>
                <th width="15%">Debit</th>
                <th width="15%">Kredit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="5%" align="center">1</td>
                <td width="15%">3000202</td>
                <td width="40%">Hutang Pada Kas</td>
                <td width="15%" align="right">0</td>
                <td width="15%" align="right"></td>
            </tr>
            <tr>
                <td width="5%" align="center">2</td>
                <td width="15%">1000103</td>
                <td width="40%">Kas Toko</td>
                <td width="15%" align="right"></td>
                <td width="15%" align="right">0</td>
            </tr>
            <tr>
                <td colspan="3" align="right" class="bold">Total</td>
                <td align="right">0</td>
                <td align="right">0</td>   
            </tr>
        </tbody>
    </table>


    <h3 class="form-section">Penjualan Tunai</h3>
    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Buku Besar</th>
                <th width="40%">Keterangan</th>
                <th width="15%">Debit</th>
                <th width="15%">Kredit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="5%" align="center">1</td>
                <td width="15%">1000103</td>
                <td width="40%">Kas Toko</td>
                <td width="15%" align="right">0</td>
                <td width="15%" align="right"></td>
            </tr>
            <tr>
                <td width="5%" align="center">2</td>
                <td width="15%">TK-A201</td>
                <td width="40%">Penjualan Toko</td>
                <td width="15%" align="right"></td>
                <td width="15%" align="right">0</td>
            </tr>
            <tr>
                <td colspan="3" align="right" class="bold">Total</td>
                <td align="right">0</td>
                <td align="right">0</td>   
            </tr>
        </tbody>
    </table>

    <h3 class="form-section">Penjualan Hutang</h3>
    <table class="table table-striped table-bordered table-hover dt-responsive" width="100%" id="table1" cellspacing="0">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Buku Besar</th>
                <th width="40%">Keterangan</th>
                <th width="15%">Debit</th>
                <th width="15%">Kredit</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td width="5%" align="center">1</td>
                <td width="15%">1000103</td>
                <td width="40%">Piutang Pada Kas</td>
                <td width="15%" align="right">0</td>
                <td width="15%" align="right"></td>
            </tr>
            <tr>
                <td width="5%" align="center">2</td>
                <td width="15%">1000207</td>
                <td width="40%">Piutang</td>
                <td width="15%" align="right"></td>
                <td width="15%" align="right">0</td>
            </tr>
            <tr>
                <td colspan="3" align="right" class="bold">Total</td>
                <td align="right">0</td>
                <td align="right">0</td>   
            </tr>
        </tbody>
    </table>