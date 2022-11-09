<?php

namespace App\Http\Controllers\App\Akuntansi;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;


use App\Http\Models\Akuntansi\Coa;
use App\Http\Models\SimpanPinjam\RandomSeq;
use Illuminate\Support\Arr;

use DB, Form, Response, Auth;

class CoaController extends AppController
{
    private $alphabet = array('A','B','C','D','E','F','G','H','I','J','N','O','P','Q');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title      = 'Daftar Chart Of Account';
        $parssing = array('title' =>  ucwords($title));

        $parssing['ListCoa'] = Coa::getListCoa()->get();
        return view('Akuntansi.indexCoa')->with($parssing);
    }

    public function create(){
        $user_id    = Auth::user()->id;
        $title      = 'Tambah Data COA';
        $parssing   = array(
                            'title' =>  ucwords($title)
                        );        
        
        return view('Akuntansi.tambahcoa', $parssing);
    }

    public function store(Request $req){
        
        $user_id = Auth::user()->id;
        $this->validate($req, [
            'group_coa' => 'required|numeric',
            'header_coa' => 'required',
            'group_detail' => 'required',
            'activity_code' => 'required',
            'code' => 'required',
            'keterangan' => 'required'            
        ]);

        if($req->isMethod('post'))
        {
            

            DB::beginTransaction();

            try{                
                $coa = new Coa();
                $coa->group_coa_id  = $req->group_coa;
                $coa->header_coa_id = $req->header_coa;
                $coa->group_detail  = $req->group_detail;
                $coa->activity_code = $req->activity_code;
                $coa->code          = $req->code;
                $coa->desc          = $req->keterangan;
                $coa->updated_by    = $user_id;
                $coa->Save();
                    
                notify()->flash('Success!', 'success', [
                    'text' => 'Data Coa berhasil tersimpan',
                ]);
                
                DB::commit();
                return redirect('akuntansi/coa');
                    
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Data Coa gagal tersimpan',
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

    public function show($Id)
    {
        $parssing['title'] = ucwords('Detail Kode Rekening');        
        $parssing['data_edit'] = Coa::findOrFail($Id);   
        return view('Akuntansi.show', $parssing);                  
    }

    public function edit($Id)
    {
        $parssing['title'] = ucwords('Detail Kode Rekening');        
        $parssing['data_edit'] = Coa::findOrFail($Id);   
        return view('Akuntansi.updateCoa', $parssing);
    }

    public function update(Request $req,$id)
    {
        $user_id = Auth::user()->id;
        $this->validate($req, [
            'group_coa' => 'required',
            'header_coa' => 'required',
            'group_detail' => 'required',
            'activity_code' => 'required',
            'code' => 'required',
            'keterangan' => 'required'            
        ]);
        DB::beginTransaction();
        try{
            $coa = Coa::findOrFail($id);
            $coa->group_coa_id  = $req->group_coa;
            $coa->header_coa_id = $req->header_coa;
            $coa->group_detail  = $req->group_detail;
            $coa->activity_code = $req->activity_code;
            $coa->code          = $req->code;
            $coa->desc          = $req->keterangan;
            $coa->updated_by    = $user_id;
            $coa->Save();
        }
        catch(ValidationException $e){
            DB::rollback();
            print("ERROR VALIDATION");
            notify()->flash('Gagal!', 'warning', [
                'text' => 'Perubahan kode rekening gagal, pastikan data telah terisi dan dengan format yang benar',
            ]);
            
            die();
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
            print("ERROR EXCEPTION");
            die();
        }
        
        DB::commit();
        return redirect('akuntansi/coa/');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = Coa::findOrFail($id);            
            $data->delete();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Kode rekening berhasil dihapus',
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
        return redirect('akuntansi/coa/');
    }

}