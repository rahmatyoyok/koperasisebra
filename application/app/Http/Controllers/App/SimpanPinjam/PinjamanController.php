<?php

namespace App\Http\Controllers\App\SimpanPinjam;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;

use App\Http\Models\SimpanPinjam\Anggota;
use App\Http\Models\SimpanPinjam\RandomSeq;
use App\Http\Models\SimpanPinjam\KonfigurasiPinjaman;
use App\Http\Models\SimpanPinjam\LoanTypes;
use App\Http\Models\SimpanPinjam\Loans;
use App\Http\Models\SimpanPinjam\LoanApprovals;
use App\Http\Models\SimpanPinjam\LoanInstallments;
use App\Http\Models\Pengaturan\Menu;
use App\Http\Models\Akuntansi\CompanyBankAccount;
use App\Http\Models\Akuntansi\TriggerSimpanPinjam;
use App\Http\Models\Bank;

use Illuminate\Support\Arr;
use DB, Form, Response, Auth;


class PinjamanController extends AppController
{
    private $alphabet = array('A','B','C','D','F','G','H','I','J');

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
            if( \Route::getCurrentRoute()->getActionName() == 'App\Http\Controllers\App\SimpanPinjam\PinjamanController@transfer_appr')
                return $next($request);

            if( \Route::getCurrentRoute()->getActionName() == 'App\Http\Controllers\App\SimpanPinjam\PinjamanController@show')
                return $next($request);

            if((\Route::getCurrentRoute()->getActionName() == 'App\Http\Controllers\App\SimpanPinjam\PinjamanController@receiving_process') && (Menu::isValid('simpanpinjam/pinjaman/apprterima', auth()->user()->level_id)))
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

    public function index(){

        $title    = 'Daftar Pinjaman';
        $parssing = array('title' =>  ucwords($title));
        return view('SimpanPinjam.pinjaman.index')->with($parssing);
    }

    public function list_pinjaman_json(Request $request)
    {
        if($request->ajax())
        {
            $datas = Loans::getListPinjaman(1);
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
            ->addColumn('total_pinjaman', function($val){
                return number_format($val->total_pinjaman, 0,",",".");
            })
            ->addColumn('sisa_pinjaman', function($val){
                return number_format(0, 0,",",".");
            })
            ->addColumn('action', function ($value) {
                $person_id = Crypt::encrypt($value->person_id);

                $html = '<a href="'.url('simpanpinjam/pinjaman/'.$person_id).'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.
                    // '<a href="'.url('simpanpinjam/investasi/'.$person_id.'/edit').'" class="btn btn-xs blue-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                    '&nbsp;';
                return $html;
            })
            ->rawColumns(['action','status']);
            return $datatables->make(true);
        }
    }

    public function loanSimulation(){
        $title    = 'Daftar Anggota';
        $parssing = array('title' =>  ucwords($title));
        return view('SimpanPinjam.pinjaman.')->with($parssing);
    }

    // Pengajuan
    public function create(){
        $user_id = Auth::user()->id;

        $title    = 'Pengajuan Pinjaman';
        $parssing = array('title' =>  ucwords($title));
        $parssing['ref'] = '05'.date('Ym').sprintf( '%06d', RandomSeq::getSeq('seq_ref_pinjaman'));

        $defPinjaman = KonfigurasiPinjaman::where('status',1)->firstOrFail();
        $parssing['by_adm_pr'] = (float)$defPinjaman->biaya_administrasi_persentase*100;
        $parssing['by_adm_rp'] = $defPinjaman->biaya_administrasi_rupiah;
        $parssing['by_provisi_pr'] = (float)$defPinjaman->biaya_provisi_persentase*100;
        $parssing['by_provisi_rp'] = $defPinjaman->biaya_provisi_rupiah;
        $parssing['by_daperma'] = $defPinjaman->resiko_daperma;
        $parssing['by_materai'] = $defPinjaman->biaya_materai;
        $parssing['by_lain'] = $defPinjaman->biaya_lain;
        $parssing['denda'] = $defPinjaman->denda_cicilan;

        $parssing['SelectedLoanTypes']  = 5;
        $DataLIstLoanTypes              = LoanTypes::getListLoanType()->get()->toArray();
        $parssing['listLoanTypes'] = Arr::pluck($DataLIstLoanTypes, 'name_label', 'loan_type_id');

        $parssing['interest_type'] = '';
        $parssing['interest_rates'] = '';
        foreach($DataLIstLoanTypes as $rws){
            if((int)$rws['loan_type_id'] == $parssing['SelectedLoanTypes']){
                $parssing['interest_type'] = $rws['name'];
                $parssing['interest_rates'] = $rws['interest_rates'] * 100;
            }
        }
        

        $parssing['duetype'] = LoanTypes::dueType();

        $parssing['def_date'] = date('d-m-Y');

        return view('SimpanPinjam.pinjaman.pengajuanpinjaman')->with($parssing);
    }

    public function store(Request $req){
        $user_id = Auth::user()->id;
        if($req->isMethod('post'))
        {
            $this->validate($req, [
                'person_id' => 'required',
                'loan_date' => 'required',
                'loan_amount' => 'required',
                'tenure' => 'required',
                'biaya_administrasi_rupiah' => 'required',
                'biaya_provisi_rupiah' => 'required',
                'interest_rates' => 'required',
                'resiko_daperma' => 'required',
                'biaya_materai' => 'required',
                'biaya_lain' => 'required',
                'principal_loan_total_label' => 'required',
                'rates_loan_total_label' => 'required',
                'principal_amount' => 'required',
                'rates_amount' => 'required',
              ]);

            DB::beginTransaction();
            try{
                $spinv = new Loans();
                
                $spinv->person_id           = $req->person_id;
                $spinv->loan_type_id        = $req->loan_type_id;
                $spinv->ref_code            = $req->ref_code;
                $spinv->loan_date           = date('Y-m-d',strtotime($req->loan_date));
                $spinv->loan_amount         = replaceRp($req->loan_amount);
                $spinv->due_type            = $req->due_type;
                $spinv->tenure              = $req->tenure;

                $spinv->interest_type         = $req->interest_type;
                $spinv->interest_rates              = ($req->interest_rates/100);
                
                $spinv->biaya_administrasi_rupiah       = replaceRp($req->biaya_administrasi_rupiah);
                $spinv->biaya_provisi_rupiah            = replaceRp($req->biaya_provisi_rupiah);
                $spinv->resiko_daperma                  = replaceRp($req->resiko_daperma);
                $spinv->biaya_materai                   = replaceRp($req->biaya_materai);
                $spinv->biaya_lain                   = replaceRp($req->biaya_lain);

                $spinv->loan_total                   = replaceRp($req->principal_loan_total_label);
                $spinv->rates_total                   = replaceRp($req->rates_loan_total_label);

                $spinv->late_tolerance                   = replaceRp($req->late_tolerance);
                $spinv->daily_fines                   = replaceRp($req->daily_fines);
                $spinv->principal_amount                   = replaceRp($req->principal_amount);
                $spinv->rates_amount                   = replaceRp($req->rates_amount);

                $spinv->status            = 0;
                // echo $req->file('lampiran_pengajuan');die;
                if($req->file('lampiran_pengajuan') != null){
                    $image = $req->file('lampiran_pengajuan');
                    $nameImage = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/simpanpinjam/pinjaman/pengajuan');
                    $image->move($destinationPath, $nameImage);
                    $spinv->lampiran_pengajuan = $nameImage;
                    // echo $spinv->lampiran_pengajuan;die;
                }
                // echo $req->file('lampiran_pengajuan');die;

                $spinv->created_at        = date('Y-m-d H:m:i');
                $spinv->updated_at        = date('Y-m-d H:m:i');
                $spinv->created_by        = $user_id;
                $spinv->updated_by        = $user_id;
                $spinv->save();

                $encryptedId = Crypt::encrypt($spinv->person_id);
                

                    notify()->flash('Success!', 'success', [
                        'text' => 'Pengajuan Pianjaman Berhasil Tambahkan',
                    ]);
                    
                    DB::commit();
                    return redirect('simpanpinjam/pinjaman/'.$encryptedId);
                    // return redirect('simpanpinjam/pinjaman');
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Pengajuan Pinjaman Gagal Tambahkan',
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

    public function show($id){
        $parssing['title'] = ucwords('Detail Pinjaman Anggota');
        $decryptedId = Crypt::decrypt($id);
        $parssing['decryptedId'] = $decryptedId;
        $parssing['data'] = Anggota::select()->AddSelect(DB::raw("ifnull((select sum(c.loan_amount)-sum(ifnull(c.total_bayar_pokok,0))saldo_pinjaman 
                                                                    from (
                                                                        select 
                                                                                                        a.person_id,
                                                                                case when a.transaction_type_id = 2 then a.loan_amount + a.rates_total else a.loan_amount end as loan_amount,
                                                                                (select case when a.transaction_type_id = 2 then sum(b.principal_amount) + sum(b.rates_amount)  
                                                                                        else sum(b.principal_amount) end as principal_amount
                                                                                    from sp_loan_installments b where b.loan_id = a.loan_id and b.status = 2 and b.is_deleted = 0)total_bayar_pokok 
                                                                        from sp_loans a where a.status =1 and a.transfer_date is not null and a.is_deleted = 0)c where c.person_id = ospos_people.person_id
                                                                    ),0)saldo_pinjaman"))->with("customer")->findOrFail($decryptedId);
        
        $parssing['data_pinjaman'] = Loans::getdataPinjamanperCustomer($decryptedId);

        LoanApprovals::setReaded(Loans::getNotifHasntRead($decryptedId));




        return view('SimpanPinjam.pinjaman.show')->with($parssing);
    }

    public function detail_pinjaman_json(Request $req){
        $user_id    = Auth::user()->id;
        $return     = array();
        if($req->ajax())
        {
            $decryptedId = Crypt::decrypt($req->ParamId);
            $model = Loans::getDataDetailPinjamanById($decryptedId);
            $return['header']   = $model[0];
            $return['header']->tgl_pengajuan = tglIndo($return['header']->loan_date);
            $return['header']->loan_amount = toRp($return['header']->loan_amount);

            $return['header']->biaya_administrasi_rupiah = toRp($return['header']->biaya_administrasi_rupiah);
            $return['header']->biaya_provisi_rupiah = toRp($return['header']->biaya_provisi_rupiah);
            $return['header']->resiko_daperma = toRp($return['header']->resiko_daperma);
            $return['header']->biaya_materai = toRp($return['header']->biaya_materai);
            $return['header']->biaya_lain = toRp($return['header']->biaya_lain);

            $return['header']->loan_total = toRp($return['header']->loan_total);
            $return['header']->rates_total = toRp($return['header']->rates_total);
            $return['header']->principal_amount = toRp($return['header']->principal_amount);
            $return['header']->rates_amount = toRp($return['header']->rates_amount);
            $return['header']->total_angsuran = toRp($return['header']->total_angsuran);


            $return['mdetail'] = LoanInstallments::GetDataAngsuranByid($decryptedId)->get();

        }
        return response()->json($return, 200);        
    }

    public function ApproveDetailTransaksi(Request $req){
        $user_id = Auth::user()->id;
        $data['return'] = false;
        $data['desc']   = '';

        if($req->ajax()){
            $modelmster = Loans::find($req->ParamId);
            if($modelmster->loan_id <> 0){
                // pengajuan_pinjaman
                $level_id = auth()->user()->level_id;
                if($level_id == 1){
                    $lastApproval = LoanApprovals::getlastAppoval(1);
                    $level_id = get_next_approval('pengajuan_pinjaman', ((!$lastApproval) ? null : $lastApproval->level_id));
                }

                DB::beginTransaction();
                try{
                    $mdetail = new LoanApprovals;
                    $mdetail->loan_id = $modelmster->loan_id;
                    $mdetail->level_id = $level_id;
                    $mdetail->approval_by = $user_id;
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
                        'text' => 'Pengajuan Pinjaman Berhasil Disetujui',
                    ]);
                }catch(ValidationException $e){
                    DB::rollback();
                    print("ERROR VALIDATION");
                    notify()->flash('Gagal!', 'warning', [
                        'text' => 'Pengajuan Pinjaman Tidak Disetujui',
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


    public function transfer_process($id){
        $decryptedId = Crypt::decrypt($id);

        // echo formatNoRpComma(1000.000.01, 0); die;

        $parssing['title'] = ucwords('Proses Penyerahan Pinjaman Anggota');
        $parssing['data'] = Loans::getByIdPinjaman($decryptedId);

        // dd($parssing['data']);
        $parssing['idEncrypt'] = Crypt::encrypt($parssing['data']->loan_id);
        $parssing['def_date'] = date('d-m-Y');
        $parssing['list_bank'] = Bank::pluck('nama_bank', 'bank_id');

        $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
        $parssing['nama_bank']    = $bank->bank->nama_bank;
        $parssing['nomor_rekening']    = $bank->rekening_no;

        // Tambahan
        $parssing['SelectedLoanTypes']  = 5;
        $DataLIstLoanTypes              = LoanTypes::getListLoanType()->get()->toArray();
        $parssing['listLoanTypes'] = Arr::pluck($DataLIstLoanTypes, 'name_label', 'loan_type_id');
        $parssing['duetype'] = LoanTypes::dueType();
        


        return view("SimpanPinjam.pinjaman.prosestransfer",$parssing);
    }

    public function transfer_appr($id, Request $req){
        $user_id = Auth::user()->id;
        $decryptedId    = Crypt::decrypt($id);
        $model          = Loans::with('anggota')->with('anggota.customer')->find($decryptedId);
        
        if($model->loan_id <> 0 && $model->status == 0){

            DB::beginTransaction();
            try{

                $model->biaya_administrasi_rupiah       = replaceRp($req->biaya_administrasi_rupiah);
                $model->biaya_provisi_rupiah            = replaceRp($req->biaya_provisi_rupiah);

                $model->loan_amount                      = replaceRp($req->loan_amount);
                $model->loan_total                      = replaceRp($req->loan_amount);
                $model->rates_total                     = replaceRp($req->rates_loan_total+0);

                $model->principal_amount                = replaceRp($req->principal_amount + 0);
                $model->rates_amount                    = replaceRp($req->rates_amount+0);

                // $model->transfer_total                  = $model->loan_total - (($model->biaya_administrasi_rupiah+0) + ($model->biaya_provisi_rupiah+0) + ($model->resiko_daperma+0) + ($model->biaya_materai+0) + ($model->biaya_lain +0));
                $model->transfer_total                  = (float)($model->resiko_daperma);

                $model->transfer_date    = date('Y-m-d',strtotime($req->transfer_date));
                $model->status          = 1;

                $model->transfer_method = $req->payment_method;

                if($model->transfer_method == 1){
                    $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
                    $parssing['nama_bank']    = $bank->bank->nama_bank;
                    $parssing['nomor_rekening']    = $bank->rekening_no;
                    $model->transfer_bank_account_id = $bank->bank_account_id;
                }

                $model->bank_id             = $model->anggota->customer->bank_id;
                $model->rekening_no         = $model->anggota->customer->account_number;
                
                if($req->file('dokumen') != null){
                    $image = $req->file('dokumen');
                    $nameImage = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/simpanpinjam/pinjaman/transfer');
                    $image->move($destinationPath, $nameImage);
                    $model->attachment = $nameImage;
                }

                // $model->rates_amount = replaceRp(50000.00+0);
                // echo (float)('Rp. '.$model->resiko_daperma+0.9);die;
                // dd($model); die;
                // $model->updated_by = $user_id;
                if($model->save()){
                    TriggerSimpanPinjam::pinjaman($model->loan_id, $user_id);
                }

                notify()->flash('Success!', 'success', [
                    'text' => 'Penyerahan Pinjaman Berhasil',
                ]);
                
                DB::commit();
                $person_id = Crypt::encrypt($model->person_id);
                return redirect('simpanpinjam/pinjaman/'.$person_id);
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Penyerahan Pinjaman Gagal',
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

    // BayarAngsuran
    public function penerimaan_cicilan($id, Request $req){
        $decryptedId = Crypt::decrypt($id);
        $parssing['title'] = ucwords('Proses Penerimaan Angsuran Pinjaman Anggota');
        $parssing['data'] = Loans::getByIdPinjaman($decryptedId);
        $parssing['idEncrypt'] = Crypt::encrypt($parssing['data']->loan_id);
        $parssing['def_date'] = date('d-m-Y');
        $parssing['list_bank'] = Bank::pluck('nama_bank', 'bank_id');

        $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->where('status',1)->first();
        $parssing['nama_bank']    = $bank->bank->nama_bank;
        $parssing['nomor_rekening']    = $bank->rekening_no;

        // Tambahan
        $parssing['SelectedLoanTypes']  = 5;
        $DataLIstLoanTypes              = LoanTypes::getListLoanType()->get()->toArray();
        $parssing['listLoanTypes'] = Arr::pluck($DataLIstLoanTypes, 'name_label', 'loan_type_id');
        $parssing['duetype'] = LoanTypes::dueType();
        
        return view("SimpanPinjam.pinjaman.bayarangsuran",$parssing);
    }

    public function receivingAngsuran($id, Request $req){
        $user_id        = Auth::user()->id;
        $decryptedId    = Crypt::decrypt($id);
        $data           = Loans::getByIdPinjaman($decryptedId);

        $jumlahBayarPokok       = replaceRp($req->jumlah_pokok_angsuran);
        $jumlahBayarAngsuran    = replaceRp($req->jumlah_bunga_angsuran);
        
        if(($jumlahBayarPokok <= $data->saldo_pokok_pinjaman) && ($jumlahBayarAngsuran <= $data->saldo_bunga_pinjaman)){

            DB::beginTransaction();
            try{

                $model = new LoanInstallments();
                $model->loan_id = $data->loan_id;
                $model->periode = date('mY');
                $model->seq_number = $req->seq_number;
                $model->principal_amount = $jumlahBayarPokok;
                $model->rates_amount = $jumlahBayarAngsuran;

                $model->payment_method = $req->payment_method;
                if($model->payment_method == 1){
                    $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
                    $model->company_bank_id = $bank->bank_account_id;
                }
                $model->status = 2;
                $model->payment_date = date('Y-m-d');
                $model->created_at = date('Y-m-d H:m:i');
                $model->created_by = $user_id;
                if($model->save()){

                $valRef = "(select concat('06',DATE_FORMAT(now(), '%Y%m'), LPAD((select last_number + 1 from seq_rd where name = 'seq_ref_cicilanpinjaman'), 6, '0')))";
                DB::table('sp_loan_installments')
                    ->where('loan_detail_id', $model->loan_detail_id)
                    ->update(['ref_code' =>DB::raw($valRef)]);

                    TriggerSimpanPinjam::cicilanPinjaman($model->loan_detail_id , $user_id);
                }

                notify()->flash('Success!', 'success', [
                    'text' => 'Penerimaan Angsuran Pinjaman Berhasil',
                ]);
                
                DB::commit();
                $person_id = Crypt::encrypt($data->person_id);
                return redirect('simpanpinjam/pinjaman/'.$person_id);
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Penerimaan Angsuran Pinjaman Gagal',
                ]);
                
                die();
            }catch(\Exception $e){
                DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

            DB::commit();

        }else{
            notify()->flash('Gagal!', 'warning', [
                'text' => 'Jumlah bayar Melebihi sisa pinjaman',
            ]);
            return redirect()->back();
        }

    }

    /**
     * Param model loan
     */
    static function simpanPelunasanPinjaman(LoanInstallments $mdata){
        
        DB::beginTransaction();
        try{
            $model = new LoanInstallments();
            
            $model->loan_id = $mdata->loan_id;
            $model->periode = $mdata->periode;
            
            $model->principal_amount = $mdata->principal_amount;
            $model->rates_amount = $mdata->rates_amount;

            $model->payment_method = $mdata->payment_method;
            if($model->payment_method == 1){
                $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
                $model->company_bank_id = $bank->bank_account_id;
            }

            $model->status = $mdata->status;
            $model->payment_date =  $mdata->payment_date;
            $model->created_at = date('Y-m-d H:m:i');
            $model->created_by =  $mdata->created_by;

            if($model->save()){
                $valRef = "(select concat('06',DATE_FORMAT(now(), '%Y%m'), LPAD((select last_number + 1 from seq_rd where name = 'seq_ref_cicilanpinjaman'), 6, '0')))";
                DB::table('sp_loan_installments')
                    ->where('loan_detail_id', $model->loan_detail_id)
                    ->update(['ref_code' =>DB::raw($valRef)]);

                TriggerSimpanPinjam::cicilanPinjaman($model->loan_detail_id , $mdata->created_by);
                DB::commit();
            }
            
            return true;
        }catch(\Exception $e){
            DB::rollback();

            return false;
        }
    }
}