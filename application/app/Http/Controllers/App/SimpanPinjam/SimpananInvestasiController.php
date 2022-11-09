<?php

namespace App\Http\Controllers\App\SimpanPinjam;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;
use App\Http\Controllers\App\SimpanPinjam\Route;
use Illuminate\Support\Facades\Session;


/*plugin phpexcel*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Http\Models\SimpanPinjam\Anggota;
use App\Http\Models\SimpanPinjam\KonfigurasiSimpanan;
use App\Http\Models\SimpanPinjam\InvestmentSavings;
use App\Http\Models\SimpanPinjam\InvestmentSavingApprovals;
use App\Http\Models\SimpanPinjam\InvestmentSavingInterests;
use App\Http\Models\Akuntansi\CompanyBankAccount;
use App\Http\Models\Akuntansi\TriggerSimpanPinjam;
use App\Http\Models\Bank;
use App\Http\Models\SimpanPinjam\RandomSeq;
use App\Http\Models\Pengaturan\Menu;

use DB, Form, Response, Auth;

class SimpananInvestasiController extends AppController
{
    
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

            // mengecek apakah permission user yang login sesuai
            // jika tidak, redirect ke dashboard dan memunculkan pesan error
            if( \Route::getCurrentRoute()->getActionName() == 'App\Http\Controllers\App\SimpanPinjam\SimpananInvestasiController@uploadTransfer')
            return $next($request);

            if( \Route::getCurrentRoute()->getActionName() == 'App\Http\Controllers\App\SimpanPinjam\SimpananInvestasiController@show')
                return $next($request);

            if((\Route::getCurrentRoute()->getActionName() == 'App\Http\Controllers\App\SimpanPinjam\SimpananInvestasiController@receiving_process') && (Menu::isValid('simpanpinjam/investasi/apprterima', auth()->user()->level_id)))
                return $next($request);

            if(!Menu::isValid($request->path(), $this->user->level_id))
            {
                notify()->flash('Error!', 'error', [
                    'text' => 'Anda tidak memiliki hak akses ke link tersebut',
                ]);
                return redirect(url()->previous());
            }

            //lanjutkan ke request selanjutnya
            return $next($request);
        });
    }

    public function index()
    {
        
        $title    = 'Daftar Investasi Anggota';
        $parssing = array('title' =>  ucwords($title));
        return view('SimpanPinjam.simpanan.investasi.simpananinvestasi')->with($parssing);
    }
    
    public function list_json(Request $request)
    {
        if($request->ajax())
        {
            $datas = InvestmentSavings::getListSimpananInvestasi();
            $datatables = Datatables::of($datas)
            ->addColumn('niak', function($val){
                return $val->anggota->niak;
            })
            ->addColumn('first_name', function($val){
                return $val->anggota->first_name .' '.$val->anggota->last_name;
            })
            ->addColumn('company_name', function($val){
                return $val->anggota->customer->company_name;
            })
            ->addColumn('person_id', function ($val) {
                return Crypt::encrypt($val->person_id);
            })
            ->addColumn('born_date', function ($val) {
                $html = $val->anggota->born_date;
                if(!empty($val->anggota->born_date))
                {
                    $html = tglIndo($val->anggota->born_date);
                }

                return $html;
            })
            ->addColumn('status_anggota', function($val){
                $rtn = '';
                switch($val->anggota->status){
                    case 1: $rtn = 'Aktif'; break;
                    case 2: $rtn = 'Mengundurkan Diri'; break;
                }
                return $rtn;
            })
            ->addColumn('total', function($val){
                return formatNoRpComma($val->total);
            })
            // ->addColumn('need_approval', function($val){
            //     return ((int)$val->need_approval == 1) ? '<span class="label label-sm label-warning"> Butuh Approval </span>l' : ""; 
            // })
            ->addColumn('action', function ($value) {
                $person_id = Crypt::encrypt($value->person_id);

                $html = '<a href="'.url('simpanpinjam/investasi/'.$person_id).'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.
                    // '<a href="'.url('simpanpinjam/investasi/'.$person_id.'/edit').'" class="btn btn-xs blue-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                    '&nbsp;';
                return $html;
            })
            ->rawColumns(['action','status']);
            return $datatables->make(true);
        }
    }

    public function create()
    {

        TriggerSimpanPinjam::simpananInvestasi(1, 1);
        $user_id = Auth::user()->id;
        $title    = 'Form Investasi Anggota';
        $parssing = array('title' =>  ucwords($title));
        $parssing['ref'] = '02'.date('Ym').sprintf( '%06d', RandomSeq::getSeq('seq_ref_simpananinvestasi'));

        $parssing['def_date'] = date('d-m-Y');
        $parssing['person_id'] = Anggota::pluck('first_name', 'person_id');
        return view('SimpanPinjam.simpanan.investasi.formspinvestasi')->with($parssing);
    }

    public function show($id)
    {
        $parssing['title'] = ucwords('Detail Data Investasi Anggota');
        $decryptedId = Crypt::decrypt($id);
        $parssing['decryptedId'] = $decryptedId;
        $parssing['data'] = Anggota::with('customer')->findOrFail($decryptedId);
        $parssing['data_invest'] = InvestmentSavings::getdataInvesasiperCustomer($decryptedId);
        $parssing['saldoInvestasi'] = InvestmentSavings::getSaldoByPerson($decryptedId)->saldo;


        InvestmentSavingApprovals::setReaded(InvestmentSavings::getNotifHasntRead($decryptedId));

        return view('SimpanPinjam.simpanan.investasi.show', $parssing);
    }

    public function store(Request $req)
    {

        $user_id = Auth::user()->id;
        if($req->isMethod('post'))
        {
            $this->validate($req, [
                'person_id' => 'required',
                'tr_date' => 'required',
                'total' => 'required',
              ]);

            DB::beginTransaction();
            try{
                $spinv = new InvestmentSavings();

                $spinv->transaction_type  = 1;
                $spinv->person_id         = $req->person_id;
                $spinv->ref_code          = $req->ref_code;
                $spinv->tr_date           = date('Y-m-d',strtotime($req->tr_date));
                // $spinv->payment_ref       = $req->payment_ref;
                $spinv->total             = replaceRp($req->total);
                $spinv->payment_method    = $req->payment_method;
                $spinv->status            = 0;

                // if((int)$spinv->payment_method == 1){
                //     $spinv->payment_date      = date('Y-m-d H:m:i');
                //     $spinv->status            = 1;
                // }

                $spinv->created_at        = date('Y-m-d H:m:i');
                $spinv->updated_at        = date('Y-m-d H:m:i');
                $spinv->created_by        = $user_id;
                $spinv->updated_by        = $user_id;
                $spinv->save();

                $encryptedId = Crypt::encrypt($spinv->person_id);
                

                    notify()->flash('Success!', 'success', [
                        'text' => 'Investasi Berhasil Tambahkan',
                    ]);
                    
                    DB::commit();
                    return redirect('simpanpinjam/investasi/'.$encryptedId);
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Investasi Gagal Tambahkan',
                ]);
                
                die();
            }catch(\Exception $e){
                DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

            DB::commit();


        }
    }

    public function form_entry(Request $req)
    {
        $user_id = Auth::user()->id;
        $title    = 'Form Investasi Anggota';
        $parssing = array('title' =>  ucwords($title));
        $parssing['ref'] = '02'.date('Ym').sprintf( '%06d', RandomSeq::getSeq('seq_ref_simpananinvestasi'));

        $parssing['def_date'] = date('d-m-Y');

        $defSimpok = KonfigurasiSimpanan::where('status',1)->firstOrFail();
        $parssing['def_total'] = number_format($defSimpok->simpanan_pokok,0,",",".");
        

        if($req->isMethod('post'))
        {
            DB::beginTransaction();
            try{
                $spinv = new InvestmentSavings();

                $spinv->person_id         = $req->get('f_niak');
                $spinv->ref_code          = $req->get('f_niak');
                $spinv->tr_date           = date('Y-m-d',strtotime($req->get('f_tanggal')));
                $spinv->payment_ref       = $req->get('f_payment_ref');
                $spinv->total             = floatval(str_replace(".","",$req->get('f_total')));
                $spinv->payment_method    = $req->get('f_payment_method');
                $spinv->status            = 1;

                if((int)$spinv->payment_method == 1){
                    $spinv->payment_date      = date('Y-m-d H:m:i');
                    $spinv->status            = 0;
                }

                $spinv->created_at        = date('Y-m-d H:m:i');
                $spinv->updated_at        = date('Y-m-d H:m:i');
                $spinv->created_by        = $user_id;
                $spinv->updated_by        = $user_id;
                $spinv->save();

                    notify()->flash('Success!', 'success', [
                        'text' => 'Investasi Berhasil Tambahkan',
                    ]);
                    
                    DB::commit();
                    return redirect('simpanpinjam/daftarspinvestasi');
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Investasi Gagal Tambahkan',
                ]);
                
                die();
            }catch(\Exception $e){
                DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

            DB::commit();


        }

        return view('SimpanPinjam.simpanan.investasi.formspinvestasi')->with($parssing);
    }

    /**
     * Pencairan investasi
     */
    public function release(Request $req)
    {
        $user_id = Auth::user()->id;
        $title    = 'Form Pengajuan Penarikan Investasi Anggota';
        $parssing = array('title' =>  ucwords($title));
        $parssing['ref'] = '02PN'.date('Ym').sprintf( '%06d', RandomSeq::getSeq('seq_ref_simpananinvestasi'));

        $parssing['def_date'] = date('d-m-Y');
        $parssing['person_id'] = Anggota::pluck('first_name', 'person_id');

        if($req->isMethod('post'))
        {
            $this->validate($req, [
                'person_id' => 'required',
                'tr_date' => 'required',
                'total' => 'required',
              ]);

            DB::beginTransaction();
            try{
                $spinv = new InvestmentSavings();

                $spinv->transaction_type  = 2;
                $spinv->person_id         = $req->person_id;
                $spinv->ref_code          = $req->ref_code;
                $spinv->tr_date           = date('Y-m-d',strtotime($req->tr_date));
                // $spinv->payment_ref       = $req->payment_ref;
                $spinv->total             = replaceRp($req->total);
                $spinv->payment_method    = $req->payment_method;
                $spinv->status            = 0;

                // if((int)$spinv->payment_method == 1){
                //     $spinv->payment_date      = date('Y-m-d H:m:i');
                //     $spinv->status            = 1;
                // }

                $spinv->created_at        = date('Y-m-d H:m:i');
                $spinv->updated_at        = date('Y-m-d H:m:i');
                $spinv->created_by        = $user_id;
                $spinv->updated_by        = $user_id;
                $spinv->save();

                $encryptedId = Crypt::encrypt($spinv->person_id);
                
                    notify()->flash('Success!', 'success', [
                        'text' => 'Pengajuan Penarikan Investasi Berhasil Ditambahkan',
                    ]);
                    
                    DB::commit();
                    return redirect('simpanpinjam/investasi/'.$encryptedId);
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Pengajuan Penarikan Investasi Gagal Tambahkan',
                ]);
                
                die();
            }catch(\Exception $e){
                DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

            DB::commit();


        }

        return view('SimpanPinjam.simpanan.investasi.formpenarikaninvestasi')->with($parssing);
    }
    
    public function ApproveDetailTransaksi(Request $req){
        $user_id = Auth::user()->id;
        $data['return'] = false;
        $data['desc']   = '';

        if($req->ajax()){
            $modelmster = InvestmentSavings::find($req->ParamId);
            if($modelmster->investment_saving_id <> 0){

                DB::beginTransaction();
                try{

                    $mdetail = new InvestmentSavingApprovals;

                    $level_id = auth()->user()->level_id;
                    if($level_id == 1){
                        $lastApproval = InvestmentSavingApprovals::getlastAppoval(1);
                        $level_id = get_next_approval('pengajuan_investasi', ((!$lastApproval) ? null : $lastApproval->level_id));
                    }
                    elseif($level_id == 9){
                        if((int)$req->approveType == 0)
                            $mdetail->status_desc = '{"read_by_bendahara":1}';
                    }

                    
                    $mdetail->investment_saving_id = $modelmster->investment_saving_id;
                    $mdetail->approval_by = $user_id;
                    $mdetail->level_id = $level_id;
                    $mdetail->status = $req->approveType;
                    $mdetail->desc = $req->desc;
                    $mdetail->approval_date = date('Y-m-d H:m:i');

                    $mdetail->created_at        = date('Y-m-d H:m:i');
                    $mdetail->updated_at        = date('Y-m-d H:m:i');
                    $mdetail->created_by        = $user_id;
                    $mdetail->updated_by        = $user_id;
                    $mdetail->save();

                    $data['return']             = true;
                    notify()->flash('Success!', 'success', [
                        'text' => 'Pengajuan Investasi Berhasil Disetujui',
                    ]);
                }catch(ValidationException $e){
                    DB::rollback();
                    print("ERROR VALIDATION");
                    notify()->flash('Gagal!', 'warning', [
                        'text' => 'Pengajuan Investasi Tidak Disetujui',
                    ]);
                    
                    die();
                }catch(\Exception $e){
                    DB::rollback();
                    throw $e;
                    print("ERROR EXCEPTION");
                    die();
                }
        
                DB::commit();
        
            }
        }

        return response()->json($data);
    }

    public function receiving_process($id){       
        $decryptedId = Crypt::decrypt($id);
        $parssing['title'] = ucwords('Proses Penerimaan Investasi Anggota');
        $parssing['data'] = InvestmentSavings::getByIdInvestasi($decryptedId);
        $parssing['idEncrypt'] = Crypt::encrypt($parssing['data']->investment_saving_id);
        $parssing['def_date'] = date('d-m-Y');
        $parssing['list_bank'] = Bank::pluck('nama_bank', 'bank_id');
        
        $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
        $parssing['nama_bank']    = $bank->bank->nama_bank;
        $parssing['nomor_rekening']    = $bank->rekening_no;
        

        return view("SimpanPinjam.simpanan.investasi.prosespenerimaan",$parssing);
    }

    public function receiving_appr($id, Request $req){
        $user_id = Auth::user()->id;
        $decryptedId    = Crypt::decrypt($id);
        $model          = InvestmentSavings::find($decryptedId);
        if($model->investment_saving_id <> 0 && $model->status == 0){

            DB::beginTransaction();
            try{

                $model->payment_date    = date('Y-m-d',strtotime($req->payment_date));
                $model->status          = 1;

                // bank
                if($model->payment_method == 1){
                    $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
                    $parssing['nama_bank']    = $bank->bank->nama_bank;
                    $parssing['nomor_rekening']    = $bank->rekening_no;
                    $model->receive_bank_account_id = $bank->bank_account_id;
                }
                    

                if($req->file('dokumen') != null){
                    $image = $req->file('dokumen');
                    $nameImage = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/simpanpinjam/investasi/setor');
                    $image->move($destinationPath, $nameImage);
                    $model->attachment = $nameImage;
                }

                $model->updated_by = $user_id;
                $model->updated_by = $user_id;
                if($model->save()){
                    TriggerSimpanPinjam::simpananInvestasi($model->investment_saving_id, $user_id);
                }

                notify()->flash('Success!', 'success', [
                    'text' => 'Penerimaan Investasi Berhasil',
                ]);
                
                DB::commit();
                $person_id = Crypt::encrypt($model->person_id);
                return redirect('simpanpinjam/investasi/'.$person_id);
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Penerimaan Investasi Gagal',
                ]);
                
                die();
            }catch(\Exception $e){
                DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

            DB::commit();


        }
    }

    public function transfer_process($id){       
        $decryptedId = Crypt::decrypt($id);
        $parssing['title'] = ucwords('Proses Penyerahan Investasi Anggota');
        $parssing['data'] = InvestmentSavings::getByIdInvestasi($decryptedId);
        $parssing['idEncrypt'] = Crypt::encrypt($parssing['data']->investment_saving_id);
        $parssing['def_date'] = date('d-m-Y');
        $parssing['list_bank'] = Bank::pluck('nama_bank', 'bank_id');
        
        $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
        $parssing['nama_bank']    = $bank->bank->nama_bank;
        $parssing['nomor_rekening']    = $bank->rekening_no;
        

        return view("SimpanPinjam.simpanan.investasi.prosesserah",$parssing);
    }

    public function transfer_appr($id, Request $req){
        $user_id = Auth::user()->id;
        $decryptedId    = Crypt::decrypt($id);
        $model          = InvestmentSavings::find($decryptedId);
        if($model->investment_saving_id <> 0 && $model->status == 0){

            DB::beginTransaction();
            try{

                $model->payment_date    = date('Y-m-d',strtotime($req->payment_date));
                $model->status          = 1;

                // bank
                if($model->payment_method == 1){
                    $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
                    $parssing['nama_bank']    = $bank->bank->nama_bank;
                    $parssing['nomor_rekening']    = $bank->rekening_no;
                    $model->transfer_bank_account_id = $bank->bank_account_id;
                }
                    

                if($req->file('dokumen') != null){
                    $image = $req->file('dokumen');
                    $nameImage = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/simpanpinjam/investasi/setor');
                    $image->move($destinationPath, $nameImage);
                    $model->attachment = $nameImage;
                }

                $model->updated_by = $user_id;
                if($model->save()){
                    TriggerSimpanPinjam::simpananInvestasi($model->investment_saving_id, $user_id, 2);
                }

                notify()->flash('Success!', 'success', [
                    'text' => 'Penyerahan Investasi Berhasil',
                ]);
                
                DB::commit();
                $person_id = Crypt::encrypt($model->person_id);
                return redirect('simpanpinjam/investasi/'.$person_id);
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Penerimaan Investasi Gagal',
                ]);
                
                die();
            }catch(\Exception $e){
                DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

            DB::commit();


        }
    }


    public function HistoryBungaInvestasi(Request $req){
        TriggerSimpanPinjam::bungaInvestasi('032020', 'AA0000008', 54);

        $parssing['title'] = ucwords('Data Bunga Investasi Anggota');
        $parssing['currentMonth']  = getMonths()[(int)date('m')].' '.date('Y');


        $periode    = sprintf( '%06d',date('mY'));
        
        $parssing['listKalkulasi'] = [];
        if(isset($req->periode) && !empty($req->periode)){
            $periode = $req->periode;
            $parssing['currentMonth']  = getMonths()[(int)substr($req->periode, 0, 2)].' '.(int)substr($req->periode, -4);    
        }
        
        $parssing['listKalkulasi'] = InvestmentSavings::getlistHistoryBunga($periode);

        return view('SimpanPinjam.simpanan.investasi.historybunga', $parssing);
    }

    /**
     * String 
     * 
     * String 
    */
    public function kalkulasiBungaInvestasi(Request $req){
        $user_id = Auth::user()->id;
        if($req->ajax())
        {
            $retrn = [];
            $prm = $req->get('params');
            $mnth = explode(' ', $prm);
            foreach(getMonths() as $key => $val){
                if($val == $mnth[0])
                    $prm = sprintf('%02d', $key).$mnth[1];
            }

            if(InvestmentSavings::prosessKalkulasiBunga($user_id, $prm)){

                notify()->flash('Success!', 'success', [
                    'text' => 'Kalkulasi Berhasil',
                ]);

                $retrn['status'] = "Success"; 
                $retrn['periode']= $prm;
                return response()->json($retrn);
            }else{

                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Kalkulasi Gagal',
                ]);
                $retrn['status'] = "Gagal";
                $retrn['periode']= $prm;
                return response()->json($retrn);
            }
        }
    }


    public static function PostingSelectedPerPeriode(Request $req){
        $user_id = Auth::user()->id;
        if($req->ajax())
        {
            $retrn = [];
            $retrn['periode'] = $req->params;

            $mnth = explode(' ', $retrn['periode']);
            foreach(getMonths() as $key => $val){
                if($val == $mnth[0])
                $retrn['periode'] = sprintf('%02d', $key).$mnth[1];
            }

            if(isset($req->datachecked )){
                $rperson_id = ''; $ln = 0;
                foreach($req->datachecked as $rw){
                    $rperson_id .= ($ln > 0) ? ','.$rw : $rw;
                    $ln++;
                }
                Session::put('listPersonExportExcelKalkulasiBngInvest', $rperson_id);
            }

            if(InvestmentSavings::postingKalkulasiPerPeriode($user_id, $retrn['periode'])){


                $retrn['status'] = "Success"; 
                $retrn['periode']= $req->periode;
                return response()->json($retrn);
            }else{

                $retrn['status'] = "Gagal";
                $retrn['periode']= $req->periode;
                return response()->json($retrn);
            }
        }
    }

    public function exportExcelPosting(Request $req)
    {


        if (Session::get('listPersonExportExcelKalkulasiBngInvest') != null){

            $inputPeriode = $req->get('fperiode');
            $fullPeriode = $req->get('fulperiode');
            $rows = 1;

            $array['periode'] = $inputPeriode;
            // $array['periode'] = $inputPeriode;
            $datas = InvestmentSavings::getlistHistoryBunga($inputPeriode, 'exportposting', Session::get('listPersonExportExcelKalkulasiBngInvest'));

            $spreadsheet = new Spreadsheet();
            $spreadsheet->getActiveSheet(0)->setTitle($fullPeriode);
            $rows++;

            $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A'.$rows, 'NIAK')
                        ->setCellValue('B'.$rows, 'NAMA')
                        ->setCellValue('C'.$rows, 'UNIT KERJA')
                        ->setCellValue('D'.$rows, 'NOMOR INDUK')
                        ->setCellValue('E'.$rows, 'JABATAN')
                        ->setCellValue('F'.$rows, 'JENIS ANGGOTA')
                        ->setCellValue('G'.$rows, 'STATUS ANGGOTA')
                        ->setCellValue('H'.$rows, 'PERIODE')
                        ->setCellValue('I'.$rows, 'SALDO INVESTASI')
                        ->setCellValue('J'.$rows, 'BUNGA INVESTASI')
                        ->setCellValue('K'.$rows, 'BIAYA ADMINISTRASI')
                        ->setCellValue('L'.$rows, 'JUMLAH TANSFER')
                        ->setCellValue('M'.$rows, 'BANK')
                        ->setCellValue('N'.$rows, 'NO. REKENING');
            
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(34);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(34);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(20);

            $spreadsheet->getActiveSheet()->getStyle('A'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('B'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('C'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('D'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('E'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('F'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('G'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('H'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('I'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('J'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('K'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('L'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('M'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
            $spreadsheet->getActiveSheet()->getStyle('N'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));

            foreach($datas as $cell){
                $rows++;
                
                $spreadsheet->getActiveSheet()->getStyle('A'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('B'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('C'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('D'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('E'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('F'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('G'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('H'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('I'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('J'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('K'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('L'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('M'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
                $spreadsheet->getActiveSheet()->getStyle('N'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));

                $spreadsheet->getActiveSheet()->getStyle('A'.$rows.':E'.$rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
                $spreadsheet->getActiveSheet()->getStyle('A'.$rows.':L'.$rows)->getNumberFormat()->setFormatCode('#,##0');
                $spreadsheet->getActiveSheet()->getStyle('M'.$rows.':N'.$rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);




                $spreadsheet->setActiveSheetIndex(0)
                        ->setCellValue('A'.$rows, $cell->niak)
                        ->setCellValue('B'.$rows, $cell->first_name.' '.$cell->last_name)
                        ->setCellValue('C'.$rows, $cell->company_name)
                        ->setCellValue('D'.$rows, $cell->nomor_induk)
                        ->setCellValue('E'.$rows, $cell->jabatan)
                        ->setCellValue('F'.$rows, $cell->member_type)
                        ->setCellValue('G'.$rows, $cell->member_status)
                        ->setCellValue('H'.$rows, $inputPeriode)
                        ->setCellValue('I'.$rows, $cell->saldo)
                        ->setCellValue('J'.$rows, $cell->bunga_investasi)
                        ->setCellValue('K'.$rows, $cell->biaya_administrasi)
                        ->setCellValue('L'.$rows, $cell->jumlah_transfer)
                        ->setCellValue('M'.$rows, $cell->bank)
                        ->setCellValue('N'.$rows, $cell->account_number);
            }


            // Redirect output to a client’s web browser (Xls)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="Data Bunga Investasi '.$fullPeriode.'.xls"');
            header('Cache-Control: max-age=0');
            // If you're serving to IE 9, then the following may be needed
            header('Cache-Control: max-age=1');

            // If you're serving to IE over SSL, then the following may be needed
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
            header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
            header('Pragma: public'); // HTTP/1.0

            $writer = IOFactory::createWriter($spreadsheet, 'Xls');
            $writer->save('php://output');
            exit;
        }
    }

    public function uploadTransfer(Request $req){
        $user_id = Auth::user()->id;
        $data['return'] = false;
        $data['desc']   = '';
        $periode = $req->uploadTFPeriode;
        // if($req->ajax()){
            // $modelmster = Loans::find($req->ParamId);
            // if($modelmster->loan_id <> 0){

                DB::beginTransaction();
                try{
                    if($req->file('dokumen') != null){
                        $image = $req->file('dokumen');
                        $data['return'] = false;
                        if(strtoupper($image->getClientOriginalExtension()) == 'XLS'){
                            
                            $nameImageExcel = date('mY').'_'.time().'.'.$image->getClientOriginalExtension();
                            $destinationPath = public_path('/simpanpinjam/uploads/kalkulasi');
                            $image->move($destinationPath, $nameImageExcel);

                            // getting excel file data into array
                            $inputFileType = IOFactory::identify($destinationPath."/".$nameImageExcel);
                            $reader = IOFactory::createReader($inputFileType);
                            $reader->setReadDataOnly(TRUE);
                            $spreadsheet = $reader->load($destinationPath."/".$nameImageExcel);
                            $sData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                            $nameImageDua = '';
                            if($req->file('dokumenLampiran') != null){
                                $imageDua = $req->file('dokumenLampiran');
                                $nameImageDua = (time()+1).'.'.$imageDua->getClientOriginalExtension();
                                $destinationPathDua = public_path('/simpanpinjam/investasi/transfer');
                                $imageDua->move($destinationPathDua, $nameImageDua);
                            }

                            $listNiak =''; $ln = 0;;
                            foreach($sData as $val){
                                $niak = $val['A']; 
                                if((strlen(trim($niak))> 0) && (trim($niak) !== 'NIAK')){
                                    $listNiak .= ($ln > 0) ? ','.$niak : $niak;
                                    $ln++;
                                } 
                            }

                            if(InvestmentSavings::transferKalkulasiPerPeriodeuser($user_id, $periode, $listNiak, $nameImageDua)){
                                    TriggerSimpanPinjam::bungaInvestasi($periode, $listNiak, $user_id);
                                }
                                // echo "dsdsd";die;

                            File::delete($destinationPath."/".$nameImageExcel);

                        }
                    }

                
                $data['return']             = true;
                    // notify()->flash('Success!', 'success', [
                    //     'text' => 'Proses Penerimaan berhasil',
                    // ]);
                    
                    return redirect('simpanpinjam/investasi/bungainvestasi?periode='.$periode);


                }catch(ValidationException $e){
                    DB::rollback();
                    print("ERROR VALIDATION");
                    notify()->flash('Gagal!', 'warning', [
                        'text' => 'Proses Penerimaan gagal',
                    ]);
                    
                    die();
                }catch(\Exception $e){
                    DB::rollback();
                    throw $e;
                    print("ERROR EXCEPTION");
                    die();
                }
        
                DB::commit();
        
            // }
        // }

        return response()->json($data);
    }
}