<?php

namespace App\Http\Controllers\App\SimpanPinjam;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use App\Http\Models\SimpanPinjam\KonfigurasiSimpanan;

use Illuminate\Support\Facades\Hash;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;

use DB, Form, Response, Auth;

class KonfigurasiSimpananController extends AppController
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    
    public function index(Request $request)
    {
        $title    = 'Konfigurasi Simpanan';
        $parssing = array(
            'title' =>  ucwords($title),
            'nama'  => 'rawon'
        );
        //return view('SimpanPinjam.konfigurasi.konfigurasiSimpanan')->with($parssing);
        return view('SimpanPinjam.konfigurasi.konfigurasiSimpanan')->with($parssing);
    }

    public function getKonfigurasiSimpanan(){
        $title      = 'Konfigurasi Simpanan';
        $config     = KonfigurasiSimpanan::where('status', 1)->first();
                        
        $parssing = array(
            'title'         => ucwords($title),
            'data'          => $config
        );
        
        return view('SimpanPinjam.konfigurasi.konfigurasiSimpanan',$parssing);
    }

    public function store(Request $request)
    {
        /** validasi form */
        $messages = [
            'required'  => ': Mohon Lengkapi data bertanda bintang',
            'min'       => ': attribute harus diisi minimal :min karakter ya cuy!!!',
            'max'       => ': attribute harus diisi maksimal :max karakter ya cuy!!!',
        ];
        // $this->validate($request,[
        //     'f_default_simpanan_pokok' =>'numeric',
        //     'f_default_simpanan_wajib' =>'numeric',
        // ],$messages);
        /** end of validasi form */
        
        
        $konfig = new KonfigurasiSimpanan;

        /** Update Data Eksisting */
        KonfigurasiSimpanan::where('id',$request->simpananId)->update(['status'=>0]);

        /** Simpan Baru */
        $konfig->simpanan_pokok     = replaceRp($request->f_default_simpanan_pokok);
        $konfig->simpanan_wajib     = replaceRp($request->f_default_simpanan_wajib);
        $konfig->bunga_investasi    = $request->f_default_bunga_investasi /100 ;
        $konfig->status             = 1;

        $konfig->save();
        
        return redirect('simpanpinjam/getKonfigurasiSimpanan')->with('status', 'Data Konfigurasi Berhasil Disimpan');
    }
    
}