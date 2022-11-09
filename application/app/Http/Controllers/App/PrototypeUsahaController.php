<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use DB, Form;


class PrototypeUsahaController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $content = $request->get('menu');

        /*
        - Tambah client (Contoh PJB) (OK)
        - Tambah supplier (OK)
        - Tambah WO (OK)
        - Pembayaran WO (OK)
        - Monitoring WO
        - Tambah PO (OK)
        - Monitoring PO (OK)
        - Cetak Surat Perintah Kerja
        - Pembayaran PO (OK)
        - Tambah / Permintaan persekot (OK)
        - Verifikasi persekot (OK)
        - Monitoring persekot (OK)

        9. Master Lokasi
        10. Master SPV
        11. Pengembalian Persekot

        */
        if($content == 'cetakSPK'){
          return view('prototype-usaha.cetak-spk');
        }elseif ($content == 'tambahWO') {
          return view('prototype-usaha.tambah-wo');
        }elseif ($content == 'pembayaranWO') {
          return view('prototype-usaha.pembayaran-wo');
        }elseif ($content == 'monitoringWO') {
          return view('prototype-usaha.monitoring-wo');
        }elseif ($content == 'monitoringPersekot') {
          return view('prototype-usaha.monitoring-persekot');
        }elseif ($content == 'pembayaranPO') {
          return view('prototype-usaha.pembayaran-po');
        }elseif ($content == 'tambahClient') {
          return view('prototype-usaha.tambah-client');
        }elseif ($content == 'tambahPersekot') {
          return view('prototype-usaha.tambah-persekot');
        }elseif ($content == 'tambahPO') {
          return view('prototype-usaha.tambah-po');
        }elseif ($content == 'monitoringPO') {
          return view('prototype-usaha.monitoring-po');
        }elseif ($content == 'tambahSupplier') {
          return view('prototype-usaha.tambah-supplier');
        }elseif ($content == 'verifikasiPersekot') {
          return view('prototype-usaha.verifikasi-persekot');
        }elseif ($content == 'pembayaranPersekot') {
          return view('prototype-usaha.pembayaran-persekot');
        }elseif ($content == 'tambahSPV') {
          return view('prototype-usaha.tambah-spv');
        }elseif ($content == 'tambahLokasi') {
          return view('prototype-usaha.tambah-lokasi');
        }elseif ($content == 'pengembalianPersekot') {
          return view('prototype-usaha.pengembalian-persekot');
        }

    }

}
