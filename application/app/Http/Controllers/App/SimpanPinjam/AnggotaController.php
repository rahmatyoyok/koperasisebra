<?php

namespace App\Http\Controllers\App\SimpanPinjam;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;

use App\Http\Models\User;
use App\Http\Models\SimpanPinjam\Anggota;
use App\Http\Models\SimpanPinjam\ResignForms;
use App\Http\Models\SimpanPinjam\Customers;
use App\Http\Models\SimpanPinjam\PrincipalSavings;
use App\Http\Models\SimpanPinjam\PeriodicSavings;
use App\Http\Models\SimpanPinjam\InvestmentSavings;
use App\Http\Models\SimpanPinjam\Loans;


use App\Http\Models\SimpanPinjam\LoanInstallments;

use App\Http\Models\Bank;
use App\Http\Models\Akuntansi\CompanyBankAccount;
use App\Http\Models\SimpanPinjam\RandomSeq;
use Illuminate\Support\Arr;

use App\Http\Controllers\App\SimpanPinjam\PinjamanController;

use DB, Form, Response, Auth, Session;

class AnggotaController extends AppController
{
    private $alphabet = array('A','B','C','D','E','F','G','H','I','J','N','O','P','Q');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title    = 'Daftar Anggota';
        $parssing = array('title' =>  ucwords($title));
        return view('SimpanPinjam.anggota.anggota')->with($parssing);
    }

    public function show($id)
    {        
        $parssing['title'] = ucwords('Detail Data Anggota');
        $decryptedId = Crypt::decrypt($id);
        $parssing['encryptId'] = $id;
        $parssing['decryptedId'] = $decryptedId;
        $parssing['data_edit'] = Anggota::with('customer.bank')->findOrFail($decryptedId);
                
        $parssing['data_simpok'] = PrincipalSavings::where('person_id',$decryptedId)->get();
        $parssing['data_wajib'] = PeriodicSavings::where('person_id',$decryptedId)->get();
        $parssing['data_invest'] = InvestmentSavings::getdataInvesasiperCustomer($decryptedId);

        $parssing['data_pinjaman'] = Loans::getdataPinjamanperCustomer($decryptedId);

        return view('SimpanPinjam.anggota.show', $parssing);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list_json(Request $request)
    {
        if($request->ajax())
        {
            $datas = Anggota::getListAnggota();
            $datatables = Datatables::of($datas)
            ->addColumn('person_id', function ($val) {
                return Crypt::encrypt($val->person_id);
            })
            ->addColumn('born_date', function ($val) {
                $html = $val->born_date;
                if(!empty($val->born_date))
                {
                    $html = tglIndo($val->born_date);
                }

                return $html;
            })
            ->addColumn('member_type', function ($val) {
                $html = sp_array_mdrray_search(sp_member_type(), 'id','name', $val->member_type);

                return $html;
            })
            ->addColumn('member_status', function ($val) {
                $html = sp_array_mdrray_search(sp_member_status(), 'id','name', $val->member_status);

                return $html;
            })
            ->addColumn('action', function ($value) {
                $person_id = Crypt::encrypt($value->person_id);

                $html = '<a href="'.url('simpanpinjam/anggota/'.$person_id).'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>';
                
                $html .= ($value->member_type <> 0) ? '<a href="'.url('simpanpinjam/anggota/'.$person_id.'/edit').'" class="btn btn-xs blue-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>' :"";
                
                $html .= Form::open([ 'method'  => 'delete', 'route' => [ 'anggota.destroy', $person_id ], 'style' => 'display: inline-block;' ]).
                    '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus Anggota '.$value->first_name.' '.$value->last_name.'?" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>'
                    .\Form::close();
                return $html;
            })
            ->rawColumns(['action','status']);
            return $datatables->make(true);
        }
    }

    public function create(){

        // echo substr('22-02-2020', 0, 2);
        $user_id = Auth::user()->id;
        $title    = 'Tambah Anggota Baru';
        $parssing = array('title' =>  ucwords($title));
        $parssing['list_member_type'] = Arr::pluck(sp_member_type_nonresign(), 'name', 'id');
        $parssing['list_member_status'] = Arr::pluck(sp_member_status(), 'name', 'id');
        $parssing['list_bank'] = Bank::pluck('nama_bank', 'bank_id');
        
        return view('SimpanPinjam.anggota.tambahanggota')->with($parssing);
    }

    public function edit($id)
    {
        $decrypted = Crypt::decrypt($id);
        $parssing['title'] = ucwords('Ubah Data Anggota');
        $parssing['data'] = Anggota::findOrFail($decrypted);
        $parssing['data_person_id'] = $id;
        $parssing['list_member_type'] = Arr::pluck(sp_member_type_nonresign(), 'name', 'id');
        $parssing['list_member_status'] = Arr::pluck(sp_member_status(), 'name', 'id');
        $parssing['list_bank'] = Bank::pluck('nama_bank', 'bank_id');
        return view('SimpanPinjam.anggota.update')->with($parssing);
    }

    public function store(Request $req){
        
        $user_id = Auth::user()->id;
            $this->validate($req, [
                'first_name' => 'required',
                'member_type' => 'required',
                'member_status' => 'required',
                'account_number' => 'required|unique:ospos_customers',
              ]);

        if($req->isMethod('post'))
        {
            

            DB::beginTransaction();

            try{
                $anggota = new Anggota;

                $x = (int)$req->get('member_type');
                $y = (int)$req->get('member_status');

                // check tanggal lahir
                $tglLahir = null;
                if(strlen($req->born_date) == 10){
                    $yr = substr($req->born_date, 6, 4);
                    $mn = substr($req->born_date, 3, 2);
                    $dd = substr($req->born_date, 0, 2);
                    if(checkdate((int)$mn, (int)$dd, (int)$yr)){
                        $tglLahir = date('Y-m-d',strtotime($req->born_date));
                    }
                }

                $anggota->niak              = $this->alphabet[(($x>0) ? ($x-1) : 13)].$this->alphabet[(($y>0) ? ($y-1) : 13)].sprintf( '%07d', RandomSeq::getSeq('seq_ref_anggota'));
                $anggota->first_name        = strtoupper($req->first_name);
                $anggota->last_name         = strtoupper($req->last_name);
                $anggota->gender            = $req->get('f_jeniskelamin');
                $anggota->email             = $req->email;
                $anggota->phone_number      = $req->phone_number;
                $anggota->id_card_number    = $req->id_card_number;
                $anggota->born_place        = strtoupper($req->born_place);
                $anggota->born_date         = $tglLahir;
                $anggota->address_1         = $req->address_1;
                $anggota->address_2         = "";

                $anggota->city              = $req->city;
                $anggota->state             = $req->state;
                $anggota->zip               = "";
                $anggota->country           = $req->country;
                $anggota->comments          = "";
                
                $anggota->member_type       = $x;
                $anggota->member_status     = $y;
                $anggota->status            = 1;

                $anggota->created_at        = date('Y-m-d H:m:i');
                $anggota->updated_at        = date('Y-m-d H:m:i');
                $anggota->created_by        = $user_id;
                $anggota->updated_by        = $user_id;
                if($anggota->save()){

                    $cus = new Customers;
                    $cus->person_id     = $anggota->person_id;
                    $cus->nomor_induk   = $req->nomor_induk;
                    $cus->jabatan       = $req->jabatan;
                    $cus->bank_id           = $req->get('bank_id');
                    $cus->account_number    = $req->get('account_number');
                    $cus->company_name  = $req->get('company_name');
                    $cus->credit_limit  = replaceRp($req->get('credit_limit'));
                    
                    $cus->npwp              = $req->npwp;
                    $cus->tax_id        = 1;
                    $cus->employee_id    = 1;
                    $cus->save();
                    
                        notify()->flash('Success!', 'success', [
                            'text' => 'Anggota Baru Berhasil Tambahkan',
                        ]);
                        
                        DB::commit();
                        return redirect('simpanpinjam/anggota');
                }
                    
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Anggota Baru Gagal Tambahkan',
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

    public function update(Request $req, $id)
    {
        $decrypted = Crypt::decrypt($id);
        $user_id = Auth::user()->id;
        $this->validate($req, [
                                'first_name' => 'required',
                                'member_type' => 'required',
                                'member_status' => 'required',
                                'account_number' => 'required|unique:ospos_customers,account_number,'.$decrypted.',person_id',
                                ]);
            
                DB::beginTransaction();
                try{
                    
                    $anggota = Anggota::findOrFail($decrypted);
    
                    $x = (int)$req->get('member_type');
                    $y = (int)$req->get('member_status');
    
                    // check tanggal lahir
                    $tglLahir = null;
                    if(strlen($req->born_date) == 10){
                        $yr = substr($req->born_date, 6, 4);
                        $mn = substr($req->born_date, 3, 2);
                        $dd = substr($req->born_date, 0, 2);
                        if(checkdate((int)$mn, (int)$dd, (int)$yr)){
                            $tglLahir = date('Y-m-d',strtotime($req->born_date));
                        }
                    }


                    $anggota->first_name        = strtoupper($req->first_name);
                    $anggota->last_name         = strtoupper($req->last_name);
                    $anggota->gender            = $req->get('f_jeniskelamin');
                    $anggota->email             = $req->email;
                    $anggota->phone_number      = $req->phone_number;
                    $anggota->id_card_number    = $req->id_card_number;
                    $anggota->born_place        = strtoupper($req->born_place);
                    $anggota->born_date         = $tglLahir;
                    $anggota->address_1         = $req->address_1;
    
                    $anggota->city              = $req->city;
                    $anggota->state             = $req->state;
                    // $anggota->zip               = $req->zip;
                    $anggota->country           = $req->country;
    
                    
                    $anggota->member_type       = $x;
                    $anggota->member_status     = $y;
                    $anggota->status            = 1;
    
                    $anggota->updated_by        = $user_id;
                    $anggota->save();
    
                        $cus = Customers::findOrFail($decrypted);
                        
                        $cus->nomor_induk   = $req->nomor_induk;
                        $cus->jabatan       = $req->jabatan;
                        
                    $cus->npwp              = $req->npwp;
                        $cus->bank_id           = $req->get('bank_id');
                        $cus->account_number    = $req->get('account_number');
                        $cus->company_name      = $req->get('company_name');
                        $cus->credit_limit      = replaceRp($req->get('credit_limit'));
                        $cus->save();
                        
                            notify()->flash('Success!', 'success', [
                                'text' => 'Data Anggota Berhasil Diubah',
                            ]);
                            
                            
                        
                }
                catch(ValidationException $e){
                    DB::rollback();
                    print("ERROR VALIDATION");
                    notify()->flash('Gagal!', 'warning', [
                        'text' => 'Data Anggota Gagal Diubah',
                    ]);
                    
                    die();
                }catch(\Exception $e){
                    DB::rollback();
                    throw $e;
                    print("ERROR EXCEPTION");
                    die();
                }
                
                DB::commit();
                return redirect('simpanpinjam/anggota/'.$id);
    }

    public function form_update($id, Request $req){
        $data = Crypt::decrypt($id);
        
        $anggota = Anggota::find($id);
        //echo $anggota['first_name']."s";


        $user_id = Auth::user()->id;
        $title    = 'Tambah Anggota Baru';
        $parssing = array('title' =>  ucwords($title));

        //Cookie::queue($name, $value, $minutes);

        if($req->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                $anggota = Anggota::find($id);

                $anggota->first_name        = $req->get('f_first_name');
                $anggota->last_name         = $req->get('f_last_name');
                $anggota->gender            = $req->get('f_jeniskelamin');
                $anggota->email             = $req->get('f_email');
                $anggota->phone_number      = $req->get('f_no_telp');            
                $anggota->id_card_number    = $req->get('f_identitas');
                $anggota->born_place        = $req->get('f_tempatlahir');
                $anggota->born_date         = date('Y-m-d',strtotime($req->get('f_tanggallahir')));
                $anggota->address_1         = $req->get('f_alamat');
                $anggota->address_2         = "";

                $anggota->city              = $req->get('f_city');
                $anggota->state             = $req->get('f_state');
                $anggota->zip               = $req->get('f_postcode');
                $anggota->country           = $req->get('f_country');            
                $anggota->comments          = "";

                
                $anggota->member_type       = 1;
                $anggota->status            = 1;

                $anggota->created_at        = date('Y-m-d H:m:i');
                $anggota->updated_at        = date('Y-m-d H:m:i');
                $anggota->created_by        = $user_id;
                $anggota->updated_by        = $user_id;
                $anggota->save();

                    $cus = new Customers;
                    $cus->person_id     = $anggota->person_id;
                    $anggota->bank_id           = $req->get('f_bank_id');
                    $anggota->account_number    = $req->get('f_rekening_no');
                    $cus->company_name  = $req->get('f_unitkerja');
                    $cus->credit_limit  = $req->get('f_batas_pinjaman');
                    $cus->tax_id        = 1;
                    $cus->employee_id    = 1;
                    $cus->save();
                    
                        notify()->flash('Success!', 'success', [
                            'text' => 'Anggota Baru Berhasil Tambahkan',
                        ]);
                        
                        DB::commit();
                        return redirect('simpanpinjam/anggota');
                    
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Anggota Baru Gagal Tambahkan',
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

        
        return view('SimpanPinjam.tambahanggota')->with($parssing);
    }

    public function form_pengundurandiri(Request $req){
        $title    = 'Pengajuan Pengunduran Diri Anggota';
        $parssing = array('title' =>  ucwords($title));
        $parssing['list_member_type'] = Arr::pluck(sp_member_type(), 'name', 'id');
        $parssing['list_member_status'] = Arr::pluck(sp_member_status(), 'name', 'id');
        $parssing['list_bank'] = Bank::pluck('nama_bank', 'bank_id');
        
        return view('SimpanPinjam.anggota.formpengundurandiri')->with($parssing);
    }

    public function listResignForm(Request $req){
        $title    = 'Daftar Pengajuan Pengunduran Diri';
        $parssing = array('title' =>  ucwords($title));
        return view('SimpanPinjam.anggota.resignanggota')->with($parssing);
    }

    public function showResign($id){
        $decrypted = Crypt::decrypt($id);
        $parssing['title'] = ucwords('Pengajuan Data Anggota');

        $parssing['personId'] = $id;
        $parssing['data'] = Anggota::with('customer')->with('resign')->with('customer.bank')->find($decrypted);
        $parssing['data']['jenis_anggota_desc'] = sp_member_type()[array_search($parssing['data']->member_type, array_column(sp_member_type(), 'id'))]['name'];
        $parssing['data']['status_anggota_desc'] = sp_member_status()[array_search($parssing['data']->member_status, array_column(sp_member_status(), 'id'))]['name'];
        
        $parssing['data']['saldo'] = Anggota::getSaldoByperson($decrypted);
        // dd($parssing);
        return view('SimpanPinjam.anggota.showpengundurandiri')->with($parssing);
    }

    public function approveResign($id){
        $decrypted = Crypt::decrypt($id);
        $models = ResignForms::where('person_id', $decrypted)->first();
        $model = ResignForms::find($models->resign_id);

        $levelid = auth()->user()->level_id;
        $level_name = get_levelusers_byid($levelid)->name;
        $sisasaldos = 1;
        try{
            if((strlen($models->approve_bendahara_id) <= 0) && ($level_name == 'superadmin' || $level_name == 'bendahara')){
                $model->approval_bendahara_id = auth()->user()->id;
                $model->approval_bendahara_desc = '{"approval_date" : "'.date('Y-m-d H:m:i').'"}';

                $model->updated_by = Auth::user()->id;
            }
            elseif((strlen($models->approve_ketua_id) <= 0) && ($level_name == 'superadmin' || $level_name == 'ketua')){
                $model->approval_ketua_id = auth()->user()->id;
                $model->approval_ketua_desc = '{"approval_date" : "'.date('Y-m-d H:m:i').'"}';
                $model->status = 1;
                $model->approval_resign_date = date('Y-m-d H:m:i');
                $model->updated_by = Auth::user()->id;

                $saldo = Anggota::getSaldoByperson($decrypted);
                $sisasaldos = $saldo['saldoPokok'] + $saldo['saldoWajib'] + $saldo['saldoInvestasi'] + $saldo['pinjaman_usp'] + $saldo['pinjaman_elektronik'];

                if((int)$sisasaldos == 0){
                    $model->status = 2;


                }
            }
        

            if($model->save()){

                if((int)$sisasaldos == 0){
                    $user = Anggota::find($decrypted);
                    $user->member_type = 0;
                    $user->save();
                }
                DB::commit();

                return redirect('simpanpinjam/anggota/pengundurandiri');
            }
            


        }
        catch(ValidationException $e){
            DB::rollback();
            print("ERROR VALIDATION");
            notify()->flash('Gagal!', 'warning', [
                'text' => 'Persetujuan Penguduran Diri Gagal',
            ]);
            
            die();
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
            print("ERROR EXCEPTION");
            die();
        }

    }

    public function list_resign_json(Request $request){       
        if($request->ajax()){
            $datas = ResignForms::getListResignAnggota();
            $datatables = Datatables::of($datas)
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
            ->addColumn('member_type', function ($val) {
                $html = sp_array_mdrray_search(sp_member_type(), 'id','name', $val->anggota->member_type);

                return $html;
            })
            ->addColumn('member_status', function ($val) {
                $html = sp_array_mdrray_search(sp_member_status(), 'id','name', $val->anggota->member_status);

                return $html;
            })
            ->addColumn('action', function ($value) {
                $person_id = Crypt::encrypt($value->person_id);
                $html = '';
                if($value->status == 0)
                    $html = '<a href="'.url('simpanpinjam/anggota/showpengundurandiri/'.$person_id).'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Menunggu Persetujuan</a>';
                
                if($value->status == 1)
                    $html = '<a href="'.url('simpanpinjam/anggota/pelunasansaldo/'.$person_id).'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Proses Penyerahan</a>';

                return $html;
            })
            ->rawColumns(['action','status']);
            return $datatables->make(true);
        }
    }

    public function getSaldoAnggota(Request $req){
        $user_id = Auth::user()->id;
        $response = [];
        $response['response'] = false;
        if($req->ajax()){
            $sessionToken = Session::token();
            $token = $req->header('X-CSRF-TOKEN');

            $responseValid = hash_equals($sessionToken, $token);
            
            
            
            $sessionToken = $req->session()->get('_token');
            $token = $req->input('_token') ?: $req->header('X-CSRF-TOKEN');
            // $response['sessionToken'] = Session::token();
            // $response['token'] = $token;


            $response['response'] = true;
            $model  = new Anggota();
            $person_id = Crypt::decrypt($req->input('ParamId'));
            $response['datasaldo'] = $model->getSaldoByperson($person_id);
            
        }
        $req->session()->regenerateToken(); // regenerate token
        $new_csrf = csrf_token();
        $req->session()->put('_token', $new_csrf);
        $response["csrf"] = $new_csrf;

        return response()->json($response);
    }

    public function setPelunasan($id){
        $decrypted = Crypt::decrypt($id);
        $parssing['title'] = ucwords('Pengajuan Data Anggota');
        
        $parssing['personId'] = $id;
        $parssing['data'] = Anggota::with('customer')->with('customer.bank')->with('resign')->find($decrypted);
        $parssing['def_date'] = date('d-m-Y');
        $parssing['list_bank'] = Bank::pluck('nama_bank', 'bank_id');

        
        $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
        $parssing['nama_bank']    = $bank->bank->nama_bank;
        $parssing['nomor_rekening']    = $bank->rekening_no;

        $parssing['data']['jenis_anggota_desc'] = sp_member_type()[array_search($parssing['data']->member_type, array_column(sp_member_type(), 'id'))]['name'];
        $parssing['data']['status_anggota_desc'] = sp_member_status()[array_search($parssing['data']->member_status, array_column(sp_member_status(), 'id'))]['name'];
        
        $parssing['data']['saldo'] = Anggota::getSaldoByperson($decrypted);
        
        return view('SimpanPinjam.anggota.pelunasan_saldo')->with($parssing);
    }

    public function prosessPelunasan($id, Request $req){
        $user_id = Auth::user()->id;
        if($req->isMethod('post')){
            $decrypted = Crypt::decrypt($id);

            $saldo = Anggota::getSaldoByperson($decrypted);
            // if($saldo['saldoPokok'] > 0)
                // jurnal saldo pokok

            // if($saldo['saldoWajib'] > 0)
                // junral saldo wajib

            // if($saldo['saldoInvestasi'] > 0)
                // jurnal saldo investasi

            if($saldo['pinjaman_usp'] > 0){
                $dataPinjaman = Loans::getpinjamanbelumlunas($decrypted);
                foreach($dataPinjaman as $lusp){
                    $mloanInstal = new LoanInstallments();
                    $mloanInstal->loan_id = $lusp->loan_id;

                    $mloanInstal->periode = date('mY');
                    $mloanInstal->principal_amount = $lusp->loan_amount- $lusp->total_bayar;
                    $mloanInstal->rates_amount = $lusp->rates_amount;
                    // $mloanInstal->payment_method = $req->payment_method;
                    $mloanInstal->payment_method = 1;
                    $mloanInstal->status = 2;
                    $mloanInstal->payment_date = date('Y-m-d');
                    $mloanInstal->created_at = date('Y-m-d H:m:i');
                    $mloanInstal->created_by = $decrypted;

                    PinjamanController::simpanPelunasanPinjaman($mloanInstal);
                }
            }
                
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = WorkOrder::findOrFail($id);
            $data->deleted_at = date('Y-m-d H:m:s');
            $data->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'WorkOrder berhasil dihapus',
            ]);
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            $pesan = config('app.debug') ? ' Pesan kesalahan: '.$e->getMessage() : '';
            notify()->flash('Gagal!', 'error', [
                'text' => 'Terjadi kesalahan pada database.'.$pesan,
            ]);
        }
        return redirect('simpanpinjam/anggota');
    }

    public function saveResignForm(Request $req){
        $user_id = Auth::user()->id;
            $this->validate($req, [
                'person_id' => 'required'
              ]);

        if($req->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                $ResignF = new ResignForms;
                $person_id = Crypt::decrypt($req->input('person_id'));

                // $ResignF->person_id = $req->;
                $ResignF->person_id         = $person_id;
                $ResignF->resign_date       = date('Y-m-d H:m:i');
                $ResignF->status            = 0;
                $ResignF->is_deleted            = 0;

                $ResignF->created_at        = date('Y-m-d H:m:i');
                $ResignF->updated_at        = date('Y-m-d H:m:i');
                $ResignF->created_by        = $user_id;
                $ResignF->updated_by        = $user_id;
                if($ResignF->save()){
                    DB::commit();
                    return redirect('simpanpinjam/anggota/pengundurandiri');
                }
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Pengajuan Penguduran Diri Gagal Tambahkan',
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
}