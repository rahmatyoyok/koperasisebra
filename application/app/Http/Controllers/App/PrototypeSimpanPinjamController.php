<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;
use DB, Form;


class PrototypeSimpanPinjamController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $content  = $request->get('menu');
        $title    = (strlen($content)>0) ? $content : 'dashboard';
        $parssing = array('title' =>  ucwords($title));

        /*
        1. Tambah anggota ()
        2. Tambah pengguna
        3. Tambah PO
        4. Konfigurasi System
        5. Transaksi simpan
        6. Transaksi pinjam
        7. simulasi pinjam
        8. perhitungan shu
        */
        if($content == 'masterkonfigurasi')
          return view('prototype-simpanpinjam.config')->with($parssing);

        elseif($content == 'ListCoa')
          return view('prototype-simpanpinjam.daftarCoa')->with($parssing);

        elseif($content == 'configSimpanan')
          return view('prototype-simpanpinjam.konfigurasisimpanan')->with($parssing);
        elseif($content == 'configPinjaman')
          return view('prototype-simpanpinjam.konfigurasipinjaman')->with($parssing);
        elseif ($content == 'jenisBungaPinjaman')
          return view('prototype-simpanpinjam.jenis_pinjaman')->with($parssing);



        elseif($content == 'anggota')
          return view('prototype-simpanpinjam.anggota')->with($parssing);
        elseif ($content == 'tambahanggota')
          return view('prototype-simpanpinjam.tambahanggota')->with($parssing);
        elseif ($content == 'ListInvestasi')
          return view('prototype-simpanpinjam.investasi')->with($parssing);
        elseif ($content == 'TambahInvestasi')
          return view('prototype-simpanpinjam.tambahinvestasi')->with($parssing);
        elseif ($content == 'PengajuanPencairanInvestasi')
          return view('prototype-simpanpinjam.daftarpencairaninvestasi')->with($parssing);
        elseif ($content == 'ListSimpananPokok')
          return view('prototype-simpanpinjam.listsimpananpokok')->with($parssing);
        elseif ($content == 'TambahSimpokPerPeriode')
          return view('prototype-simpanpinjam.tambahsimpananpokok')->with($parssing);
        elseif ($content == 'ListSimpananWajib')
          return view('prototype-simpanpinjam.listsimpananwajib')->with($parssing);
        elseif ($content == 'ProsesSimpananWajib')
          return view('prototype-simpanpinjam.prosessimpananwajibperiode')->with($parssing);
        elseif ($content == 'simulasiPinjaman') 
          return view('prototype-simpanpinjam.simulasiPinjaman')->with($parssing);
        elseif ($content == 'listPinjaman') 
          return view('prototype-simpanpinjam.listPinjaman')->with($parssing);


        return view('prototype-simpanpinjam.dashboard')->with($parssing);
    }

}
