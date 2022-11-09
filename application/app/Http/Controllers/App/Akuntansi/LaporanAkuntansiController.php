<?php

namespace App\Http\Controllers\App\Akuntansi;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;


use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;


use App\Http\Models\Akuntansi\Coa;
use App\Http\Models\Akuntansi\GeneralLedger;

use App\Http\Models\Pengaturan\User;
use App\Http\Models\Pengaturan\Menu;

use App\Http\Models\SimpanPinjam\RandomSeq;
use Illuminate\Support\Arr;

use DB, Form, Response, Auth;

class LaporanAkuntansiController extends AppController
{
    private $bulanIndo   = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
    private $arrDivisi  = array('SP' => 'Simpan Pinjam', 'UM'=>'Usaha Umum', 'TK' => 'Toko', '' => 'All');
    
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            // jika berhasil login buat variabel user
            $this->user = auth()->user();

            // generate menu
            $this->menus = Menu::getMenus($this->user->level_id);

            // ambil judul dari path yang dituju
            if(!$request->ajax())
                $this->title = Menu::getTitle($request->path());

            view()->share([
                'user' => $this->user,
                'menus' => $this->menus,
                'title' => $this->title
            ]);

            // // mengecek apakah permission user yang login sesuai
            // if(!Menu::isValid($request->path(), $this->user->level_id))
            // {
            //     notify()->flash('Error!', 'error', [
            //         'text' => 'Anda tidak memiliki hak akses ke link tersebut',
            //     ]);
            //     return redirect(url()->previous());
            // }

            //lanjutkan ke request selanjutnya
            return $next($request);
        });
    }

    public function checkhasposting(Request $req){
        $return['response'] = false;

        if($req->ajax()){
            $dates = ($req->ParamPeriode) ? substr($req->ParamPeriode,-4).'-'.substr($req->ParamPeriode,0,2).'-01' : date('Y-m-d');
            $d=strtotime($dates);

            
            $hasPosting = GeneralLedger::getPostingByPeriode(date('mY',$d));
            

            $return['response'] = ($hasPosting > 0) ? true : false;
        }
        return response()->json($return);
    }

    public function postingJurnalUmum(Request $req){
        $title    = 'Posting Jurnal';
        $parssing = array('title' =>  ucwords($title));
        $dates = ($req->pmperiode) ? substr($req->pmperiode,-4).'-'.substr($req->pmperiode,0,2).'-01' : date('Y-m-d');
        $d=strtotime($dates);

        $parssing['currentMonth']  = getMonths()[(int)date('m', $d)].' '.date('Y', $d);
        $parssing['currentMonthIndo'] = date('mY', $d);
        $parssing['hasPosting'] = GeneralLedger::getPostingByPeriode(date('mY',$d));
        $parssing['listPosting'] = GeneralLedger::getLIstPosting();
        
        if($req->pmperiode){
            $valid = GeneralLedger::where('periode', $parssing['currentMonthIndo'])->count();
            if($valid <= 0){
                $postingNo = GeneralLedger::generateNoPosting($parssing['currentMonthIndo']);
                $response = GeneralLedger::eksekusiPostingBukuBesar($parssing['currentMonthIndo'], $postingNo, $this->user->id);

            }
        }

        
        return view('Akuntansi.Journal.postingjurnal')->with($parssing);
    }

    public function showBukuBesar(Request $req){
        $title    = 'Laporan Buku Besar';
        $parssing = array('title' =>  ucwords($title));
        $dates = ($req->pmperiode) ? substr($req->pmperiode,-4).'-'.substr($req->pmperiode,0,2).'-01' : date('Y-m-d');
        $coa = ($req->coa_id) ? $req->coa_id : 0;
        $d=strtotime($dates);

        $parssing['currentMonth']  = getMonths()[(int)date('m', $d)].' '.date('Y', $d);
        $parssing['currentMonthIndo'] = date('mY', $d);
        $parssing['listCoa']  = Coa::find($coa);
        $parssing['listBukuBesar'] = GeneralLedger::getBukuBesar($coa, date('mY',$d));
        
        return view('Akuntansi.Journal.indexBukuBesar')->with($parssing);
    }

    public function showLabaRugi(Request $req){
        $title    = 'Laporan Laba Rugi';
        $parssing = array('title' =>  ucwords($title));

        $parssing['showOrHide']  = ($req->pmperiode) ? true : false;
        $dates = ($req->pmperiode) ? substr($req->pmperiode,-4).'-'.substr($req->pmperiode,0,2).'-01' : date('Y-m-d');
        // $divisi = ($req->divisi_id) ? $req->divisi_id : 'UM';
        $d=strtotime($dates);

        $parssing['currentMonth']  = getMonths()[(int)date('m', $d)].' '.date('Y', $d);
        $parssing['currentMonthIndo'] = date('mY', $d);
        // $parssing['currentDivisi'] = $divisi;

        $parssing['listPendapatan']         = GeneralLedger::getlistLabaRugiNew($parssing['currentMonthIndo'], "PENDAPATAN");
    
        $parssing['listBebanLangsung']      = GeneralLedger::getlistLabaRugiNew($parssing['currentMonthIndo'], "BEBANLANGSUNG");
        
        // $parssing['listBebanOpersaional']   = GeneralLedger::getlistLabaRugi($parssing['currentMonthIndo'], $divisi, "BEBANOPERASIONAL");
        $parssing['listPendapatanLuarUsaha']   = GeneralLedger::getlistLabaRugiNew($parssing['currentMonthIndo'], "PENDAPATANLUARUSAHA");
        $parssing['listBebanLuarUsaha']   = GeneralLedger::getlistLabaRugiNew($parssing['currentMonthIndo'], "BEBANLUARUSAHA");
        $parssing['listBebanPajak']   = GeneralLedger::getlistLabaRugiNew($parssing['currentMonthIndo'], "BEBANPAJAK");
        

        // $number = 10;

        // $myFunction = array_map(function($value) use ($number) {
        //     return $value * $number;
        // }, [1,2,3,4]);
        // echo var_dump($myFunction);


        return view('Akuntansi.laporan.laporanlabarugi')->with($parssing);
    }

    public function showNeraca(Request $req){
        $title    = 'Laporan Neraca';
        $parssing = array('title' =>  ucwords($title));

        $parssing['showOrHide']  = ($req->pmperiode) ? true : false;
        $dates = ($req->pmperiode) ? substr($req->pmperiode,-4).'-'.substr($req->pmperiode,0,2).'-01' : date('Y-m-d');
        
        $d=strtotime($dates);
        $parssing['currentMonth']  = getMonths()[(int)date('m', $d)].' '.date('Y', $d);
        $parssing['currentMonthIndo'] = date('mY', $d);

        // AKTIVA
        $parssing['listSetaraKas']         = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "KASSETARAKAS");
        $parssing['listPiutangUsaha']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "PIUTANGUSAHA");
        $parssing['listPiutangDeviden']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "PIUTANGDEVIDEN");
        $parssing['listPersediaanBarang']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "PERSEDIAANBARANG");
        $parssing['listBiayaDibayaDimuka']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "BIAYADIBAYARDIMUKA");
        
        
        $parssing['listNilaiPerolehanAset']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "NILAIPEROLEHANASET");
        $parssing['listAkmPenyusutanAset']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "AKMPENYUSUTAN");


        $parssing['listPiutangLain']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "PIUTANGLAIN");
        $parssing['listInvJangkaPanjang']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "INVJANGKAPANJANG");

        // PASSIVA
        $parssing['listDanaDana']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "DANADANA");
        $parssing['listKewajibanLancar']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "KEWAJIBANLANCAR");
        $parssing['listTabunganAnggota']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "TABUNGANANGGOTA");
        $parssing['listHutangPajak']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "HUTANGPAJAK");
        $parssing['listEkuitas']      = GeneralLedger::getlistNeracaNew($parssing['currentMonthIndo'], "EKUITAS");

        
        // $parssing['listAsetTetap']          = GeneralLedger::getlistNeraca($parssing['currentMonthIndo'], "ASETTETAP");
        // $parssing['listAsetLain']          = GeneralLedger::getlistNeraca($parssing['currentMonthIndo'], "ASETLAIN");

        // $parssing['listKwajibanlancar']          = GeneralLedger::getlistNeraca($parssing['currentMonthIndo'], "LIABILITASLANCAR");
        // $parssing['listKwajibanJangkaPanjang']          = GeneralLedger::getlistNeraca($parssing['currentMonthIndo'], "LIABILITASJANGKAPANJANG");
        // $parssing['listEkuitas']          = GeneralLedger::getlistNeraca($parssing['currentMonthIndo'], "EKUITAS");
        
        return view('Akuntansi.laporan.laporanNeraca')->with($parssing);
    }

    public function showPerubahanEkuitas(Request $req){
        $title    = 'Laporan Perubahan Ekuitas';
        $parssing = array('title' =>  ucwords($title));

        $parssing['showOrHide']  = ($req->pmperiode) ? true : false;
        $dates = ($req->pmperiode) ? substr($req->pmperiode,-4).'-'.substr($req->pmperiode,0,2).'-01' : date('Y-m-d');
        
        $d=strtotime($dates);
        $parssing['currentMonth']  = getMonths()[(int)date('m', $d)].' '.date('Y', $d);
        $parssing['currentMonthIndo'] = date('mY', $d);

        $parssing['listAsetLancar']         = GeneralLedger::getlistNeraca($parssing['currentMonthIndo'], "ASETLANCAR");
        $parssing['listAsetTetap']          = GeneralLedger::getlistNeraca($parssing['currentMonthIndo'], "ASETTETAP");
        $parssing['listAsetLain']          = GeneralLedger::getlistNeraca($parssing['currentMonthIndo'], "ASETLAIN");

        $parssing['listKwajibanlancar']          = GeneralLedger::getlistNeraca($parssing['currentMonthIndo'], "LIABILITASLANCAR");
        $parssing['listKwajibanJangkaPanjang']          = GeneralLedger::getlistNeraca($parssing['currentMonthIndo'], "LIABILITASJANGKAPANJANG");
        $parssing['listEkuitas']          = GeneralLedger::getlistNeraca($parssing['currentMonthIndo'], "EKUITAS");
        
        return view('Akuntansi.Journal.indexPerubahanEkuitas')->with($parssing);
    }

    public function unpostingJurnalUmum(Request $req){
        $title    = 'Posting Jurnal';
        $parssing = array('title' =>  ucwords($title));
        // $dates = ($req->pmperiode) ? substr($req->pmperiode,-4).'-'.substr($req->pmperiode,0,2).'-01' : date('Y-m-d');
        // $d=strtotime($dates);

        // $parssing['currentMonth']  = getMonths()[(int)date('m', $d)].' '.date('Y', $d);
        // $parssing['currentMonthIndo'] = date('mY', $d);
        // $parssing['hasPosting'] = GeneralLedger::getPostingByPeriode(date('mY',$d));
        // $parssing['listPosting'] = GeneralLedger::getLIstPosting();
        
        if($req->pmperiode){
            // $valid = GeneralLedger::where('periode', $parssing['currentMonthIndo'])->count();
            // if($valid <= 0){
            //     $postingNo = GeneralLedger::generateNoPosting($parssing['currentMonthIndo']);
            //     $response = GeneralLedger::eksekusiPostingBukuBesar($parssing['currentMonthIndo'], $postingNo, $this->user->id);

            // }
        }

        
        // return view('Akuntansi.Journal.postingjurnal')->with($parssing);
    }
}