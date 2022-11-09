<?php

namespace App\Http\Controllers\App\SimpanPinjam;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use App\Http\Models\SimpanPinjam\Anggota;
use App\Http\Models\SimpanPinjam\KonfigurasiSimpanan;
use App\Http\Models\SimpanPinjam\PrincipalSavings;
use App\Http\Models\Akuntansi\TriggerSimpanPinjam;
use App\Http\Models\Akuntansi\CompanyBankAccount;
use App\Http\Models\Bank;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;

use App\Http\Models\SimpanPinjam\RandomSeq;

use App\Http\Models\Akuntansi\HeaderJurnals;

use DB, Form, Response, Auth;

class SimpananPokokController extends AppController
{
    public function index()
    {
        $title    = 'Daftar Simapan Pokok Anggota';
        $parssing = array('title' =>  ucwords($title));
        return view('SimpanPinjam.simpanan.pokok.simpananpokok')->with($parssing);
    }

    public function list_json(Request $request)
    {
        if($request->ajax())
        {
            $datas = PrincipalSavings::getListSimpananPokok();
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
            ->addColumn('action', function ($value) {
                $person_id = Crypt::encrypt($value->person_id);

                $html = '<a href="'.url('simpanpinjam/simpananpokok/'.$person_id).'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.
                    // '<a href="'.url('simpanpinjam/simpananpokok/'.$person_id.'/edit').'" class="btn btn-xs blue-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                    '&nbsp;';
                return $html;
            })
            ->rawColumns(['action','status']);
            return $datatables->make(true);
        }
    }
    
    public function show($id)
    {
        $parssing['title'] = ucwords('Detail Data Simpanan Pokok Anggota');
        $decryptedId = Crypt::decrypt($id);
        $parssing['decryptedId'] = $decryptedId;
        $parssing['data'] = Anggota::with('customer')->findOrFail($decryptedId);
        $parssing['data_simpok'] = PrincipalSavings::where('person_id',$decryptedId)->get();
        
        return view('SimpanPinjam.simpanan.pokok.show', $parssing);
    }

    public function create()
    {
        $user_id = Auth::user()->id;
        $title    = 'Tambah Simpanan Pokok';
        $parssing = array('title' =>  ucwords($title));
        $parssing['ref'] = '01'.date('Ym').sprintf( '%06d', RandomSeq::getSeq('seq_ref_simpananpokok'));
        $parssing['def_date'] = date('d-m-Y');

        $defSimpok = KonfigurasiSimpanan::where('status',1)->firstOrFail();
        $parssing['def_total'] = $defSimpok->simpanan_pokok;
        $parssing['person_id'] = Anggota::pluck('first_name', 'person_id');
        // $parssing['client_id'] = Client::pluck('nama_client', 'client_id');
        return view('SimpanPinjam.simpanan.pokok.formsppokok',$parssing);
    }

    public function store(Request $req)
    {
        if($req->isMethod('post'))
        {
            $this->validate($req, [
                'person_id' => 'required',
                'tr_date' => 'required',
                'total' => 'required',
              ]);

            DB::beginTransaction();
            try{
                $user_id = Auth::user()->id;

                

                $sppokok = new PrincipalSavings();

                $sppokok->person_id         = $req->person_id;
                $sppokok->ref_code          = $req->ref_code;
                $sppokok->tr_date           = date('Y-m-d',strtotime($req->tr_date));
                $sppokok->payment_ref       = $req->payment_ref;
                $sppokok->total             = replaceRp($req->total);
                $sppokok->payment_method    = (int)$req->payment_method;
                $sppokok->status            = 0;

                $sppokok->created_at        = date('Y-m-d H:m:i');
                $sppokok->updated_at        = date('Y-m-d H:m:i');
                $sppokok->created_by        = $user_id;
                $sppokok->updated_by        = $user_id;
                $sppokok->save();

                    notify()->flash('Success!', 'success', [
                        'text' => 'Simpanan Pokok Berhasil Tambahkan',
                    ]);
                    
                    DB::commit();
                    $person_id = Crypt::encrypt($sppokok->person_id);
                    return redirect('simpanpinjam/simpananpokok/'.$person_id);
                    
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Simpanan Pokok Gagal Tambahkan',
                ]);
                
                die();
            }catch(\Exception $e){
                DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

        }
    }

    public function form_entry(Request $req)
    {
        $user_id = Auth::user()->id;
        $title    = 'Tambah Simpanan Pokok';
        $parssing = array('title' =>  ucwords($title));
        $parssing['ref'] = '01'.date('Ym').sprintf( '%06d', RandomSeq::getSeq('seq_ref_simpananpokok'));

        $parssing['def_date'] = date('d-m-Y');

        $defSimpok = KonfigurasiSimpanan::where('status',1)->firstOrFail();
        $parssing['def_total'] = number_format($defSimpok->simpanan_pokok,0,",",".");
        

        if($req->isMethod('post'))
        {
            DB::beginTransaction();
            try{
                $sppokok = new PrincipalSavings();

                $sppokok->person_id         = $req->get('f_niak');
                $sppokok->ref_code          = $req->get('f_niak');
                $sppokok->tr_date           = date('Y-m-d',strtotime($req->get('f_tanggal')));
                $sppokok->payment_ref       = $req->get('f_payment_ref');
                $sppokok->total             = floatval(str_replace(".","",$req->get('f_total')));
                $sppokok->payment_method    = $req->get('f_payment_method');
                $sppokok->status            = 1;

                // jika transaksi transfer
                if((int)$sppokok->payment_method == 2){
                    $sppokok->payment_date      = date('Y-m-d H:m:i');
                    $sppokok->status            = 0;
                }

                $sppokok->created_at        = date('Y-m-d H:m:i');
                $sppokok->updated_at        = date('Y-m-d H:m:i');
                $sppokok->created_by        = $user_id;
                $sppokok->updated_by        = $user_id;
                $sppokok->save();

                    notify()->flash('Success!', 'success', [
                        'text' => 'Simpanan Pokok Berhasil Tambahkan',
                    ]);
                    
                    DB::commit();
                    return redirect('simpanpinjam/daftarsppokok');
                    
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Simpanan Pokok Gagal Tambahkan',
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

        return view('SimpanPinjam.simpanan.pokok.formsppokok')->with($parssing);
    }

    public function receiving_process($id){       
        $decryptedId = Crypt::decrypt($id);
        $parssing['title'] = ucwords('Proses Penerimaan Simpanan Pokok Anggota');
        $parssing['data'] = PrincipalSavings::getByIdSimpananPokok($decryptedId);
        $parssing['idEncrypt'] = Crypt::encrypt($parssing['data']->principal_saving_id);
        $parssing['def_date'] = date('d-m-Y');
        $parssing['list_bank'] = Bank::pluck('nama_bank', 'bank_id');
        
        $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
        $parssing['nama_bank']    = $bank->bank->nama_bank;
        $parssing['nomor_rekening']    = $bank->rekening_no;
        
        return view("SimpanPinjam.simpanan.pokok.prosespenerimaan",$parssing);
    }

    public function receiving_appr($id, Request $req){
        $user_id = Auth::user()->id;
        $decryptedId    = Crypt::decrypt($id);
        $model          = PrincipalSavings::find($decryptedId);
        if($model->principal_saving_id <> 0 && $model->status == 0){

            DB::beginTransaction();
            try{

                $model->payment_date    = date('Y-m-d',strtotime($req->payment_date));
                $model->status          = 2;

                // Jika bank tranasfer
                if($model->payment_method == 1){
                    $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
                    $parssing['nama_bank']    = $bank->bank->nama_bank;
                    $parssing['nomor_rekening']    = $bank->rekening_no;
                    $model->receive_bank_account_id = $bank->bank_account_id;
                }

                if($req->file('dokumen') != null){
                    $image = $req->file('dokumen');
                    $nameImage = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/simpanpinjam/pokok/setor');
                    $image->move($destinationPath, $nameImage);
                    $model->attachment = $nameImage;
                }

                $model->updated_by = $user_id;
                if($model->save()){
                    TriggerSimpanPinjam::simpananPokok($model->principal_saving_id, $user_id);


                }

                notify()->flash('Success!', 'success', [
                    'text' => 'Penerimaan Simpanan Pokok Berhasil',
                ]);
                
                DB::commit();
                $person_id = Crypt::encrypt($model->person_id);
                return redirect('simpanpinjam/simpananpokok/'.$person_id);
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Penerimaan Simpanan Pokok Gagal',
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

    public function batalSimpok($id){
        $decryptedId = Crypt::decrypt($id);
        $response['response'] = false;
        $response['desc'] = '';


        DB::beginTransaction();
        try{
            
            $sppokok = PrincipalSavings::find($decryptedId);
            
            $sppokok->is_deleted = 1;
            $sppokok->updated_at = date('Y-m-d H:m:i');
            $sppokok->updated_by = Auth::user()->id;
            $sppokok->save();
            DB::commit();
            $response['response'] = true;
            
        }
        catch(ValidationException $e){
            DB::rollback();
            // print("ERROR VALIDATION");

            $response['desc'] = 'Simpanan Pokok Gagal Dibatalkan';

            // notify()->flash('Gagal!', 'warning', [
            //     'text' => 'Simpanan Pokok Gagal Dibatalkan',
            // ]);
            
            die();
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
            print("ERROR EXCEPTION");
            die();
        }

        return Response::json($response);
    }
}