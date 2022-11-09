<?php

namespace App\Http\Controllers\App\Akuntansi;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;


use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\File; 

use App\Http\Models\Akuntansi\HeaderJurnals;
use App\Http\Models\Akuntansi\DetailJurnals;
use App\Http\Models\Akuntansi\AkTransactionTypes;
use App\Http\Models\Akuntansi\DepreciationAssets;

use App\Http\Models\Akuntansi\Coa;
use App\Http\Models\Akuntansi\TriggerPenyusutan;

use App\Http\Models\Pengaturan\User;
use App\Http\Models\AsetDetail;

use App\Http\Models\SimpanPinjam\RandomSeq;
use Illuminate\Support\Arr;
use Carbon\Carbon;

use DB, Form, Response, Auth, DateTime;;

class JournalController extends AppController
{
    private $alphabet   = array('A','B','C','D','E','F','G','H','I','J','N','O','P','Q');
    private $arrDivisi  = array('SP' => 'Simpan Pinjam', 'UM'=>'Usaha Umum', 'TK' => 'Toko');
    private $jurnalType  = array('JRR' => 'Manual', 'JKK'=>'Kas Keluar', 'JKM' => 'Kas masuk');
    
    private function generateNoJurnal($tr_type, $jurnal_type = 'JRR'){
        $crtanggal  = (int)date('m')-1;
        $trData     = AkTransactionTypes::findOrFail($tr_type)->code; 

        $seqName = 'seq_ref_jurnal_jrr';
        switch($jurnal_type){
            case 'JKM' : $seqName = 'seq_ref_jurnal_jkm' ; break;
            case 'JKK' : $seqName = 'seq_ref_jurnal_jkk' ; break;
        }

        // sequance
        $randomseq  = sprintf( '%06d', RandomSeq::getSeq($seqName,999999));

        return $this->alphabet[$crtanggal].date('y').$trData.$randomseq;
    }

    private function getUserLevelForDivision(){
        $user_id    = Auth::user()->id;
        $level      = User::with('level')->findOrFail($user_id);
        $lvel       = $level->level->name;

        $return = null;
        switch($lvel){
            case 'staffsimpanpinjam': $return = 'SP'; break;
            case 'staffusahaumum': $return = 'UM'; break;
            case 'stafftoko': $return = 'TK'; break;
        }

        return $return;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexJkm(Request $request){

        $title          = 'Daftar Jurnal JKM';
        $parssing       = array('title' =>  ucwords($title));
        $parssing['codeTransaksi']    = 'JKM';
        $parssing['nameTransaksi']    = 'Kas Masuk';
        $parssing['descTransaksi']    = 'Kas Masuk ('.$parssing['codeTransaksi'].')';
        $parssing['actionAdd']        = url('akuntansi/jurnal/entryjkm');
        
        return view('Akuntansi.Journal.IndexJurnal')->with($parssing);
    }

    public function indexJkk(Request $request)
    {
        // $user_id = Auth::user()->id;
        // echo TriggerPersekots::perubahanStatus(12, $user_id);

        $title          = 'Daftar Jurnal JKK';
        $parssing       = array('title' =>  ucwords($title));
        $parssing['codeTransaksi']    = 'JKK';
        $parssing['nameTransaksi']    = 'Kas Keluar';
        $parssing['descTransaksi']    = 'Jurnal Kas Keluar ('.$parssing['codeTransaksi'].')';
        $parssing['actionAdd']        = url('akuntansi/jurnal/entryjkk');

        
        return view('Akuntansi.Journal.IndexJurnal')->with($parssing);
    }

    public function indexJrr(Request $request)
    {
        $title          = 'Daftar Jurnal JRR';
        $parssing       = array('title' =>  ucwords($title));
        $parssing['codeTransaksi']    = 'JRR';
        $parssing['nameTransaksi']    = 'Manual';
        $parssing['descTransaksi']    = 'Jurnal Manual ('.$parssing['codeTransaksi'].')';
        $parssing['actionAdd']        = url('akuntansi/jurnal/entryjrr');

        
        return view('Akuntansi.Journal.IndexJurnal')->with($parssing);
    }

    public function indexMonitoringJurnals(){
        $title          = 'Monitoring Jurnal';
        $parssing       = array('title' =>  ucwords($title));
        
        $parssing['listDivisi'] = array_merge(array(''=>''),$this->arrDivisi);
        $parssing['listJurnalType'] = array_merge(array(''=>''), $this->jurnalType);
        
        $arr2 = AkTransactionTypes::where('status',1)->pluck('desc','transaction_type_id'); $arr2->prepend('', '');
        $parssing['tr_type_id'] = $arr2;

        return view('Akuntansi.Journal.IndexMonitoringJurnal')->with($parssing);
    }

    public function showJurnal($reqId){
        $parssing['title'] = ucwords('Detail Jurnal');
        $decryptedId = Crypt::decrypt($reqId);
        $parssing['decryptedId'] = $decryptedId;

        $parssing['data'] = HeaderJurnals::with('transactiontype')->findOrFail($decryptedId);
        $parssing['data_detail'] = DetailJurnals::getDetailJurnal($decryptedId);

        $codeDesc = array();
        switch($parssing['data']->type){
            case 'JKM': $codeDesc = array('singkat'=> $parssing['data']->type, 'lengkap'=>'Kas Masuk', 'urlUbah' => 'entryjkm'); break; 
            case 'JKK': $codeDesc = array('singkat'=> $parssing['data']->type, 'lengkap'=>'Kas Keluar', 'urlUbah' => 'entryjkk'); break; 
            case 'JRR': $codeDesc = array('singkat'=> $parssing['data']->type, 'lengkap'=>'Manual', 'urlUbah' => 'entryjrr'); break; 
        }
        $parssing['codeDesc'] = $codeDesc;


        $parssing['title']  = ucwords('Detail Jurnal '.$parssing['data']->type);
        return view('Akuntansi.Journal.show')->with($parssing);
    }

    public function list_jurnal_json(Request $request){
        // if($request->ajax())
        // {
            $reqJurnalType = $request->jurnalType;

            $datas = HeaderJurnals::getListJournal($reqJurnalType);


            // Filter
            if ($divisi = $request->request->get('div')) {
                $datas->where('division', $divisi);
            }
            if ($jourType = $request->request->get('jourType')) {
                $datas->where('type', $jourType);
            }
            if ($transtype = $request->request->get('transtype')) {
                $datas->where('transaction_type_id', $transtype);
            }
            if ($trdate = $request->request->get('trdate')) {

                if(validateDate($trdate, "d-m-Y"))
                {
                    $date = DateTime::createFromFormat('d-m-Y', $trdate);
                    $datas->where('tr_date', $date->format('Y-m-d'));
                }
            }

            
            // End Filter

            $datatables = Datatables::of($datas)
            // ->filterColumn('fullname', function($query, $keyword) {
            //     $sql = "CONCAT(users.first_name,'-',users.last_name)  like ?";
            //     $query->whereRaw($sql, ["%{$keyword}%"]);
            // })
            ->addColumn('division', function($val){
                $return = $val->division;
                switch($val->division){
                    case 'SP' : $return = "Simpan Pinjam"; break;
                    case 'UM' : $return = "Usaha Umum"; break;
                    case 'TK' : $return = "Toko"; break;
                }

                return $return;
            })

            ->addColumn('trdate', function ($val) {
                $html = $val->tr_date;
                if(!empty($val->tr_date))
                {
                    $html = tglIndo($val->tr_date);
                }

                return $html;
            })
            ->addColumn('total', function($val){
                return toRp($val->total);
            })
            ->addColumn('action', function ($value) {
                $s_id = Crypt::encrypt($value->journal_header_id);

                $html = '<a href="'.url('akuntansi/jurnal/'.strtolower($value->type).'/'.$s_id).'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.
                    '&nbsp;';
                return $html;
            })
            ->rawColumns(['action','status'])
            ;

            $rewponse =  $datatables->make(true);


            return  $rewponse;
        // }
    }

    public function list_Monitoringjurnal_json(Request $request){
        // if($request->ajax())
        // {
            $reqJurnalType = $request->jurnalType;

            $datas = HeaderJurnals::getListJournal($reqJurnalType);
            $datatables = Datatables::of($datas)
            ->addColumn('type', function($val){
                $return = $val->type;
                switch($val->type){
                    case 'JKK' : $return = "Kas Masuk"; break;
                    case 'JKM' : $return = "Kas Keluar"; break;
                    case 'JRR' : $return = "Manual"; break;
                }

                return $return;
            })
            ->addColumn('division', function($val){
                $return = $val->division;
                switch($val->division){
                    case 'SP' : $return = "Simpan Pinjam"; break;
                    case 'UM' : $return = "Usaha Umum"; break;
                    case 'TK' : $return = "Toko"; break;
                }

                return $return;
            })
            ->addColumn('tr_date', function ($val) {
                $html = $val->tr_date;
                if(!empty($val->tr_date))
                {
                    $html = tglIndo($val->tr_date);
                }

                return $html;
            })
            ->addColumn('total', function($val){
                return toRp($val->total);
            })
            ->addColumn('action', function ($value) {
                $s_id = Crypt::encrypt($value->journal_header_id);

                $html = '<a href="'.url('akuntansi/jurnal/'.strtolower($value->type).'/'.$s_id).'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.
                    '&nbsp;';
                return $html;
            })
            ->rawColumns(['action','status']);
            return $datatables->make(true);
        // }
    }

    public function entryJurnalJkm(){

        

        $title      = 'Entry Jurnal JKM';
        $parssing   = array('title' =>  ucwords($title));
        
        $parssing['actionUrl']   = "saveNewJurnal";
        $parssing['jenisjurnal']   = array('singkat' => 'JKM', 'lengkap'=> 'Jurnal Kas Masuk');
        $parssing['tr_type_id'] = AkTransactionTypes::where('status',1)->pluck('desc','transaction_type_id');

        // default input
        $parssing['def_date'] = date('d-m-Y');

        $parssing['data'] = new HeaderJurnals();
        $parssing['data_detail'] = array();

        $parssing['currentDivisi'] = $this->getUserLevelForDivision();
        $parssing['listDivisi'] = $this->arrDivisi;

        return view('Akuntansi.Journal.entryjurnal')->with($parssing);
    }

    public function entryJurnalJkk(){
        $title      = 'Entry Jurnal JKK';
        $parssing   = array('title' =>  ucwords($title));
        
        $parssing['actionUrl']   = "saveNewJurnal";
        $parssing['jenisjurnal']   = array('singkat' => 'JKK', 'lengkap'=> 'Jurnal Kas Keluar');
        $parssing['tr_type_id'] = AkTransactionTypes::where('status',1)->pluck('desc','transaction_type_id');

        // default input
        $parssing['def_date'] = date('d-m-Y');

        $parssing['data'] = new HeaderJurnals();
        $parssing['data_detail'] = array();

        
        $parssing['currentDivisi'] = $this->getUserLevelForDivision();
        $parssing['listDivisi'] = $this->arrDivisi;
        return view('Akuntansi.Journal.entryjurnal')->with($parssing);
    }

    public function entryJurnalJrr(){
        $title      = 'Entry Jurnal JRR';
        $parssing   = array('title' =>  ucwords($title));
        
        $parssing['actionUrl']   = "saveNewJurnal";
        $parssing['jenisjurnal']   = array('singkat' => 'JRR', 'lengkap'=> 'Jurnal Rupa Rupa');
        $parssing['tr_type_id'] = AkTransactionTypes::where('status',1)->pluck('desc','transaction_type_id');

        // default input
        $parssing['def_date'] = date('d-m-Y');

        $parssing['data'] = new HeaderJurnals();
        $parssing['data_detail'] = array();
        

        $parssing['currentDivisi'] = $this->getUserLevelForDivision();
        $parssing['listDivisi'] = $this->arrDivisi;
        return view('Akuntansi.Journal.entryjurnal')->with($parssing);
    }

    public function editJurnal($reqId){
        $parssing['title'] = ucwords('Ubah Jurnal');
        $decryptedId = Crypt::decrypt($reqId);
        $parssing['decryptedId'] = $decryptedId;

        $parssing['actionUrl']   = "updateNewJurnal/".$reqId;
        $parssing['data'] = HeaderJurnals::with('transactiontype')->findOrFail($decryptedId);
        

        $parssing['data_detail'] = DetailJurnals::getDetailJurnal($decryptedId);

        $parssing['jenisjurnal']   = array('singkat' => 'JKM', 'lengkap'=> 'Jurnal Kas Masuk');

        
        switch($parssing['data']->type){
            case 'JKM': $parssing['jenisjurnal']   = array('singkat' => 'JKM', 'lengkap'=> 'Jurnal Kas Masuk'); break; 
            case 'JKK': $parssing['jenisjurnal']   = array('singkat' => 'JKK', 'lengkap'=> 'Jurnal Kas Keluar'); break; 
            case 'JRR': $parssing['jenisjurnal']   = array('singkat' => 'JRR', 'lengkap'=> 'Jurnal Rupa Rupa'); break; 
        }


        $parssing['tr_type_id'] = AkTransactionTypes::where('status',1)->pluck('desc','transaction_type_id');

        $parssing['def_date'] = date('d-m-Y', strtotime($parssing['data']->tr_date));

        
        $parssing['title']  = ucwords('Ubah Jurnal '.$parssing['data']->type);
        return view('Akuntansi.Journal.entryjurnal')->with($parssing);
    }

    public function storeJurnal(Request $req){
        
        $user_id = Auth::user()->id;
        if($req->isMethod('post'))
        {
            $this->validate($req, [
                'tr_type' => 'required',
                'desc' => 'required',
                'tr_date' => 'required',
              ]);

            DB::beginTransaction();
            try{
                $crdiv = $this->getUserLevelForDivision();
                $currentDivisi = (!is_null($crdiv)) ? $crdiv : $req->division;
                $nJurnal = HeaderJurnals::generateNoJurnal((int)$req->tr_type_id, $req->tr_type);

                $m_headers = new HeaderJurnals();
                
                $m_headers->division    = $currentDivisi;
                $m_headers->transaction_type_id = $req->tr_type_id;
                $m_headers->type        = $req->tr_type;
                $m_headers->journal_no  = $nJurnal;
                $m_headers->reff_no     = $req->reff_no;
                $m_headers->tr_date     = date('Y-m-d',strtotime($req->tr_date));
                $m_headers->desc        = $req->desc;
                $m_headers->fiscal_year = date('Y',strtotime($req->tr_date));
                $m_headers->fiscal_month = date('m',strtotime($req->tr_date));
                $m_headers->total       = replaceRp($req->total_debit);
                $m_headers->created_at  = date('Y-m-d H:m:i');
                $m_headers->created_by  = $user_id;
                if($m_headers->save()){
                    $d_debit = $req->f_debit;
                    $d_kredit = $req->f_kredit;
                    $d_seq = $req->seq;
                    $d_coaCode = $req->coa_code;
                    $d_desc = $req->detail_desc;
                    $n=0;
                    foreach($req->coa_id as $row){
                        $m_detail = new DetailJurnals();

                        $m_detail->journal_header_id    = $m_headers->journal_header_id;
                        $m_detail->journal_header_type  = $m_headers->type;
                        $m_detail->coa_id               = $row;
                        $m_detail->coa_code             = $d_coaCode[$n];
                        $m_detail->description          = $d_desc[$n];
                        $m_detail->seq                  = $d_seq[$n];
                        $m_detail->debit                = replaceRp($d_debit[$n]);
                        $m_detail->kredit               = replaceRp($d_kredit[$n]);
                        $m_detail->created_at           = date('Y-m-d H:m:i');
                        $m_detail->created_by           = $user_id;
                        $m_detail->save();
                        $n++;
                    }
                }

                notify()->flash('Success!', 'success', [
                    'text' => 'Jurnal Berhasil Tambahkan',
                ]);
                
                DB::commit();
                return redirect('akuntansi/jurnal/'.strtolower($req->tr_type));
            }
            catch(ValidationException $e){
                // DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Jurnal Gagal Tambahkan',
                ]);
                
                die();
            }catch(\Exception $e){
                // DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

            DB::commit();


        }
    }

    public function storeUpdateJurnal($reqId, Request $req){
        $user_id = Auth::user()->id;
        if($req->isMethod('post'))
        {
            $this->validate($req, [
                'tr_type' => 'required',
                'desc' => 'required',
                'tr_date' => 'required',
              ]);

            DB::beginTransaction();
            try{

                $decryptedId = Crypt::decrypt($reqId);
                
                $m_headers = HeaderJurnals::find($decryptedId);

                $m_headers->transaction_type_id = $req->tr_type_id;
                $m_headers->type        = $req->tr_type;
                $m_headers->reff_no     = $req->reff_no;
                $m_headers->tr_date     = date('Y-m-d',strtotime($req->tr_date));
                $m_headers->desc        = $req->desc;
                $m_headers->fiscal_year = date('Y',strtotime($req->tr_date));
                $m_headers->fiscal_month = date('m',strtotime($req->tr_date));
                $m_headers->total       = replaceRp($req->total_debit);
                $m_headers->created_at  = date('Y-m-d H:m:i');
                $m_headers->created_by  = $user_id;
                if($m_headers->save()){

                    DetailJurnals::where('journal_header_id', $decryptedId)->delete();

                    $d_debit = $req->f_debit;
                    $d_kredit = $req->f_kredit;
                    $d_seq = $req->seq;
                    $d_coaCode = $req->coa_code;
                    $n=0;
                    foreach($req->coa_id as $row){
                        $m_detail = new DetailJurnals();

                        $m_detail->journal_header_id    = $m_headers->journal_header_id;
                        $m_detail->journal_header_type  = $m_headers->type;
                        $m_detail->coa_id               = $row;
                        $m_detail->coa_code             = $d_coaCode[$n];
                        $m_detail->seq                  = $d_seq[$n];
                        $m_detail->debit                = replaceRp($d_debit[$n]);
                        $m_detail->kredit               = replaceRp($d_kredit[$n]);
                        $m_detail->created_at           = date('Y-m-d H:m:i');
                        $m_detail->created_by           = $user_id;
                        $m_detail->save();
                        $n++;
                    }
                }

                notify()->flash('Success!', 'success', [
                    'text' => 'Jurnal Berhasil Diubah',
                ]);
                
                DB::commit();
                return redirect('akuntansi/jurnal/'.strtolower($req->tr_type).'/'.$reqId);
            }
            catch(ValidationException $e){
                // DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Jurnal Gagal Diubah',
                ]);
                
                die();
            }catch(\Exception $e){
                // DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

            DB::commit();


        }
    }

    public function indexPenyusutan(Request $req){
        $title          = 'Daftar Penyusutan Aset';
        $parssing       = array('title' =>  ucwords($title));
        
        $dates = ($req->pmperiode) ? substr($req->pmperiode,-4).'-'.substr($req->pmperiode,0,2).'-01' : date('Y-m-d');
        $d=strtotime($dates);

        $parssing['currentMonth']  = getMonths()[(int)date('m', $d)].' '.date('Y', $d);
        $parssing['currentMonthIndo'] = date('mY', $d); 

        return view('Akuntansi.Journal.IndexPenyusutan')->with($parssing);
    }

    public function list_index_penyusutan_json(Request $request){
        
        if($request->ajax())
        {
            $datas = DepreciationAssets::getListPenyusutan();
            $datatables = Datatables::of($datas)
            
            ->addColumn('tgl_penerimaan', function ($val) {
                $html = $val->tgl_pembelian;
                if(!empty($val->tgl_pembelian))
                {
                    $html = tglIndo($val->tgl_pembelian);
                }

                return $html;
            })
            ->addColumn('total', function($val){
                return toRp($val->total);
            })
            ->addColumn('masa_manfaat', function($val){
                return $val->masa_manfaat.' Tahun';
            })
            ->addColumn('periode_akhir', function($val){
                $periode = '';
                if($val->periode_akhir !== NULL){
                    $periode = getMonths()[(int)substr($val->periode_akhir,0,2)]." ".substr($val->periode_akhir,-4);
                }
                return $periode;
            })
            ->addColumn('sisa_masa_manfaat', function($val){
                $expld = explode(",", $val->sisa_masa_manfaat);
                return $expld[0].' Tahun'. (((int)$expld[1] == 0) ? "": $expld[1]." Bulan") ;
            })
            ->addColumn('total_penyusutan', function($val){
                return toRp($val->total_penyusutan);
            })
            ->addColumn('nilai_buku', function($val){
                return toRp($val->nilai_buku);
            })
            ->addColumn('action', function ($value) {
                $s_id = Crypt::encrypt($value->id);

                $html = '<a href="'.url('akuntansi/penyusutan/detailpenyusutan/'.$s_id).'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.'&nbsp;';
                // $html = '<a href="" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.'&nbsp;';
                return $html;
            })
            ->rawColumns(['action']);
            return $datatables->make(true);
        }
    }

    public function kalkulasiPenyusutan(Request $req){
        if($req->ajax()){            
            $user_id    = Auth::user()->id;
            $response['responses'] = true;
            $drperide = ($req->fperiode) ? substr($req->fperiode,-4).'-'.substr($req->fperiode,0,2).'-01' : false;
            if($drperide){
                $d=strtotime($drperide);
                $periode = date('mY', $d);

                // Validasi apakah sudah terposing
                $paramPeriod = date('Y-m-d', $d);
                $response = TriggerPenyusutan::KalkulasiPenyusutanPerpeiode($paramPeriod, $user_id);
                
            }
            return Response($response);
        }
    }

    public function detailHistoryPenyusutan($reqId){
        $parssing['title'] = ucwords('Detail Penyusutan');
        $decryptedId = Crypt::decrypt($reqId);
        $parssing['decryptedId'] = $decryptedId;

        $parssing['dataListDetail'] = DepreciationAssets::getlistDetailPenyusutan($parssing['decryptedId']);
        $parssing['data'] = AsetDetail::getDataAsetDetail($parssing['decryptedId']);



        return view('Akuntansi.Journal.showPenyusutan')->with($parssing);
    }

    public function deleteHistoryPenyusutan(Request $req){
        // if($req->ajax()){
            $response['response'] = false;
            if(isset($req->id)){

                DB::beginTransaction();
                try
                {

                //     $deleted = DB::delete("delete asets, aset_details, ak_journal_headers, ak_journal_details
                //                                 from asets
                //                                 inner join aset_details on asets.id = aset_details.aset_id
                //                                 inner join ak_journal_headers on ak_journal_headers.tigger_table = 'asets' and ak_journal_headers.tigger_table_key_id = asets.id
                //                                 inner join ak_journal_details on ak_journal_details.journal_header_id = ak_journal_headers.journal_header_id
                //                                 where asets.id = ?", array($id));



                //     DB::commit();
                //     notify()->flash('Sukses!', 'success', [
                //         'text' => 'Lokasi berhasil dihapus',
                //     ]);

                
                }
                catch(\Illuminate\Database\QueryException $e)
                {
                    DB::rollback();
                    $pesan = config('app.debug') ? ' Pesan kesalahan: '.$e->getMessage() : '';
                    notify()->flash('Gagal!', 'error', [
                        'text' => 'Terjadi kesalahan pada database.'.$pesan,
                    ]);
                }

            }

            return Response($response);
        // }
    
    
    }

    public function downloadFileMaster(Request $req){
        $retunLocFile = "";


        $FileNames = $req->get("paramName");
        switch($FileNames){
            case "formatImport":                

                $ListCoa        = Coa::getListCoa()->get();

                $styleArray = array(
                    'borders' => array(
                        'outline' => array(
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => array('argb' => '000000'),
                        ),
                    ),
                );

                $retunLocFile   = public_path()."/dokumen/KertasKerjaJurnal.xls"; 
                $objPHPExcel    = IOFactory::createReader("Xls");
                $objPHPExcel    = $objPHPExcel->load($retunLocFile);
                $objPHPExcel->setActiveSheetIndex(1);
                

                $ln = 6;
                foreach($ListCoa as $rw){
                    if((int)$rw->group_detail <> 1){
                        $objPHPExcel->getActiveSheet()->setCellValue('E'.$ln, $rw->code );
                        $objPHPExcel->getActiveSheet()->getStyle("E".$ln)->applyFromArray($styleArray);
                        $objPHPExcel->getActiveSheet()->setCellValue('F'.$ln, $rw->desc );
                        $objPHPExcel->getActiveSheet()->getStyle("F".$ln)->applyFromArray($styleArray);
                        $objPHPExcel->getActiveSheet()->setCellValue('G'.$ln, $rw->desc_group_coa );
                        $objPHPExcel->getActiveSheet()->getStyle("G".$ln)->applyFromArray($styleArray);
                    $ln++;
                    }
                }


                // Redirect output to a client’s web browser (Excel2007)
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="KertasKerjaJurnal.xls"');
                header('Cache-Control: max-age=0');

                $objWriter = IOFactory::createWriter($objPHPExcel, 'Xls');
                $objWriter->save('php://output');	

                // dd($objPHPExcel);
            break;
        }

        return Response::download($retunLocFile);
    }

    private function searchInsideArray($arrays, $headerName, $HeaderValue, $Detilkey = '', $kodeCoa = '' ){
        $valid = 0; $returnArH = 0; $lastIndexDetail = 0;
        if(count($arrays) > 0){
            foreach($arrays as $arH => $val){
                if($val[$headerName] == $HeaderValue){
                    $returnArH = $arH;
                
                    if(strlen($kodeCoa)>0){
                        foreach($val[$Detilkey] as $idxDetail => $vl){
                            $lastIndexDetail = $idxDetail;
                        }
                    }

                    $valid++;
                }
            }

            if(strlen($kodeCoa)>0){
                return array('indexHeader'=> $returnArH, 'lastIndexDetail' => $lastIndexDetail);
            }

            if($valid > 0){
                return false;
            }

        }

        return true;
       
    }

    public function uploadImportJurnal(Request $req){
        ini_set('max_execution_time', '0');
        $user_id = Auth::user()->id;
        
        $this->validate($req, [
            'fileToUpload.*'=>'required|file|mimes:xls'
          ]);

          
        $data['return'] = false;
        $data['desc']   = '';
        $req->session()->regenerateToken(); // regenerate token
		$new_csrf = csrf_token();
		$req->session()->put('_token', $new_csrf);
        $data['descrf'] = $new_csrf;

        DB::beginTransaction();
        try{
            $image = $req->file('fileToUpload');
            if(strtoupper($image->getClientOriginalExtension()) == 'XLS'){
                $nameImageExcel = "tempImportFile_".date('mY').'_'.time().'.'.$image->getClientOriginalExtension();
                // $nameImageExcel = "tempImportFile_092020_1598956794.xls";
                $destinationPath = public_path('/akuntansi');
                
                if($image->move($destinationPath, $nameImageExcel)){
                    $retunLocFile   = public_path()."/akuntansi/".$nameImageExcel; 
                    $objPHPExcel    = IOFactory::createReader("Xls");
                    $objPHPExcel->setLoadSheetsOnly(['Kertas Kerja']);
                    $objPHPExcel    = $objPHPExcel->load($retunLocFile);
                    $sData = $objPHPExcel->getActiveSheet(0)->toArray(null, true, true, true);

                    $ln = 0; $arrN = 0;
                    $arraySave['descrf'] = $data['descrf'];
                    $arraySave['importJurnal'] = [];

                    $ListCoa = Coa::where('is_deleted', 0)->get();

                    foreach($sData as $val){
                        if($ln <> 0){
                            if(strlen(trim($val['A'])) > 0){
                                if($this->searchInsideArray($arraySave['importJurnal'], 'UniqueCode', $val['A'])){
                                    // header
                                    $arraySave['importJurnal'][$arrN]['UniqueCode']         = $val['A'];
                                    $arraySave['importJurnal'][$arrN]['JeniJurnal']         = $val['B'];
                                    $arraySave['importJurnal'][$arrN]['DivisiCode']         = $val['C'];
                                    $arraySave['importJurnal'][$arrN]['JenisTransaksi']     = $val['D'];
                                    $arraySave['importJurnal'][$arrN]['NoRefrensi']         = $val['E'];
                                    $arraySave['importJurnal'][$arrN]['TglTransaksi']       = $val['F'];
                                    $arraySave['importJurnal'][$arrN]['HeaderDesc']         = $val['G'];
                                    
                                    $arraySave['importJurnal'][$arrN]['TotalDebit']         = 0;
                                    $arraySave['importJurnal'][$arrN]['TotalKredit']        = 0;
                                    $detailTransaksi = [];
                                    
                                    $detailTransaksi[0]['IdCoa'] = sp_array_mdrray_search($ListCoa, 'code', 'coa_id', $val['H']);
                                    $detailTransaksi[0]['KodeCoa'] = $val['H'];
                                    $detailTransaksi[0]['DetailDesc'] = $val['I'];
                                    $detailTransaksi[0]['Debit'] = (strlen($val['J']) > 0) ? ((is_numeric( $val['J'])) ?  $val['J']: 0) : 0;
                                    $detailTransaksi[0]['Kredit'] = (strlen($val['K']) > 0) ? ((is_numeric( $val['K'])) ?  $val['K']: 0)  : 0;


                                    $arraySave['importJurnal'][$arrN]['DetailTransksi']     = $detailTransaksi;
                                    $arraySave['importJurnal'][$arrN]['TotalDebit']         = $arraySave['importJurnal'][$arrN]['TotalDebit'] + $detailTransaksi[0]['Debit'];
                                    $arraySave['importJurnal'][$arrN]['TotalKredit']         = $arraySave['importJurnal'][$arrN]['TotalKredit'] + $detailTransaksi[0]['Kredit'];
                                    
                                    $arrN++;
                                }
                                else{
                                    $lastDetail = $this->searchInsideArray($arraySave['importJurnal'], 'UniqueCode', $val['A'], 'DetailTransksi', $val['H']);
                                    // $detailTransaksi = [];
                                    

                                    $detailTransaksi[($lastDetail['lastIndexDetail']+1)]['IdCoa'] = sp_array_mdrray_search($ListCoa, 'code', 'coa_id', $val['H']);
                                    $detailTransaksi[($lastDetail['lastIndexDetail']+1)]['KodeCoa'] = $val['H'];
                                    $detailTransaksi[($lastDetail['lastIndexDetail']+1)]['DetailDesc'] = $val['I'];
                                    $detailTransaksi[($lastDetail['lastIndexDetail']+1)]['Debit'] = (strlen($val['J']) > 0) ? ((is_numeric( $val['J'])) ?  $val['J']: 0): 0;
                                    $detailTransaksi[($lastDetail['lastIndexDetail']+1)]['Kredit'] = (strlen($val['K']) > 0) ? ((is_numeric( $val['K'])) ?  $val['K']: 0) : 0;
                                    $arraySave['importJurnal'][$lastDetail['indexHeader']]['DetailTransksi']     = $detailTransaksi;
                                    $arraySave['importJurnal'][$lastDetail['indexHeader']]['TotalDebit']        = $arraySave['importJurnal'][$lastDetail['indexHeader']]['TotalDebit'] + $detailTransaksi[($lastDetail['lastIndexDetail']+1)]['Debit'];
                                    $arraySave['importJurnal'][$lastDetail['indexHeader']]['TotalKredit']       = $arraySave['importJurnal'][$lastDetail['indexHeader']]['TotalKredit'] + $detailTransaksi[($lastDetail['lastIndexDetail']+1)]['Kredit'];
                                    
                                }
                            }
                        }
                    $ln++;
                    }
                    
                    File::delete($retunLocFile);
                }
            }

        }catch(ValidationException $e){
            DB::rollback();
            print("ERROR VALIDATION");
            notify()->flash('Gagal!', 'warning', [
                'text' => 'Proses Import gagal',
            ]);
            
            die();
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
            print("ERROR EXCEPTION");
            die();
        }

        DB::commit();
        session(['tempDataImportJurnal' => json_encode((object)$arraySave)]);
        
        return response()->json($arraySave);
        // return redirect('akuntansi/jurnal/importData');
    }

    public function showTempImportJurnal(){
        $parssing['title'] = ucwords('Import Jurnal');
        $parssing['dataShow'] = json_decode(session('tempDataImportJurnal'));

        // dd($parssing['dataShow']->importJurnal[1]);

        return view('Akuntansi.Journal.showTempImport')->with($parssing);
    }

    public function listTempImportDetail(Request $req){
        $req->session()->regenerateToken(); // regenerate token
		$new_csrf = csrf_token();
		$req->session()->put('_token', $new_csrf);
        $data['descrf'] = $new_csrf;
        $data['data'] = [];

        if(!is_null(session('tempDataImportJurnal')))
        $dt = json_decode(session('tempDataImportJurnal'));
        $data['header'] = [];

        foreach($dt->importJurnal as $rw){
            if($rw->UniqueCode == $req->unikCodeParam){
                $rw->JeniJurnal = ($rw->JeniJurnal == 'JKM') ? "Kas Masuk":(($rw->JeniJurnal == 'JKK') ? "Kas Keluar":(($rw->JeniJurnal == 'JRR') ? "Manual":""));
                $rw->DivisiCode = ($rw->DivisiCode == 'SP') ? "Simpan Pinjam":(($rw->DivisiCode == 'TK') ? "Toko":(($rw->DivisiCode == 'UM') ? "Usaha Umum":""));
                $rw->JenisTransaksi = ($rw->JenisTransaksi == 'K') ? "KAS":(($rw->JenisTransaksi == 'K') ? "Kas Kecil":(($rw->JenisTransaksi == 'J') ? "Manual":""));
                $rw->TglTransaksi = tglIndo($rw->TglTransaksi);
                $data['data'] = $rw;
            }
        }

        return response()->json($data);
    }

    public function saveImportSession(Request $req){
        $user_id = Auth::user()->id;

        // regenerate token
        $req->session()->regenerateToken(); 
		$new_csrf = csrf_token();
		$req->session()->put('_token', $new_csrf);
        $data['descrf'] = $new_csrf;
        $data['responseSuccess'] = false; 

        if($req->isMethod('post')){
            DB::beginTransaction();
            try{
                $dataSession = json_decode(session('tempDataImportJurnal'));

                foreach($dataSession->importJurnal as $rsw){
                    if($rsw->TotalDebit == $rsw->TotalKredit){

                        $trTypeId = ($rsw->JenisTransaksi == 'K') ? 1:(($rsw->JenisTransaksi == 'K') ? 2:(($rsw->JenisTransaksi == 'J') ? 3:0));
                        $nJurnal = HeaderJurnals::generateNoJurnal((int)$trTypeId, $rsw->JeniJurnal);

                        $m_headers = new HeaderJurnals();                        
                        $m_headers->division    = $rsw->DivisiCode;
                        $m_headers->transaction_type_id = (int)$trTypeId;
                        $m_headers->type        = $rsw->JeniJurnal;
                        $m_headers->journal_no  = $nJurnal;
                        $m_headers->reff_no     = $rsw->NoRefrensi;
                        $m_headers->tr_date     = date('Y-m-d',strtotime($rsw->TglTransaksi));
                        $m_headers->desc        = $rsw->HeaderDesc;
                        $m_headers->fiscal_year = date('Y',strtotime($rsw->TglTransaksi));
                        $m_headers->fiscal_month = date('m',strtotime($rsw->TglTransaksi));
                        $m_headers->total       = replaceRp($req->TotalDebit);
                        $m_headers->created_at  = date('Y-m-d H:m:i');
                        $m_headers->created_by  = $user_id;
                        
                        if($m_headers->save()){
                            $nseq = 1;
                            foreach($rsw->DetailTransksi as $row){
                                $m_detail = new DetailJurnals();
                                
                                $m_detail->journal_header_id    = $m_headers->journal_header_id;
                                $m_detail->journal_header_type  = $m_headers->type;
                                $m_detail->coa_id               = $row->IdCoa;
                                $m_detail->coa_code             = $row->KodeCoa;
                                $m_detail->description          = $row->DetailDesc;
                                $m_detail->seq                  = $nseq;
                                $m_detail->debit                = replaceRp($row->Debit);
                                $m_detail->kredit               = replaceRp($row->Kredit);
                                $m_detail->created_at           = date('Y-m-d H:m:i');
                                $m_detail->created_by           = $user_id;
                                $m_detail->save();
                                $nseq++;
                            }
                        }

                    }
                }
                $req->session()->forget('tempDataImportJurnal');
                DB::commit();
                
            $data['responseSuccess'] = true; 

            }catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Proses Import gagal',
                ]);
                

                
                $data['responseMessage'] = "Proses Import gagal"; 
                // die();
            }catch(\Exception $e){
                DB::rollback();
                throw $e;
                
                $data['responseMessage'] = $e; 
                // print("ERROR EXCEPTION");
                // die();
            }

        }

        return response()->json($data);

    }

}