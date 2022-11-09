<?php

namespace App\Http\Controllers\App\Akuntansi;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;


use App\Http\Models\Akuntansi\GroupCoa;
use Illuminate\Support\Arr;

use DB, Form, Response, Auth;

class GroupCoaController extends AppController
{
    private $alphabet = array('A','B','C','D','E','F','G','H','I','J','N','O','P','Q');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title      = 'Daftar Group Chart Of Account';
        $parssing = array('title' =>  ucwords($title));

        // filter yang is_delete = 0;
        $parssing['ListCoa'] = GroupCoa::all();
        
        return view('Akuntansi.indexGroupCoa')->with($parssing);
    }

    public function create(){
        $user_id    = Auth::user()->id;
        $title      = 'Tambah Data COA';
        $parssing   = array(
                            'title' =>  ucwords($title)
                        );        
        
        return view('Akuntansi.tambahgroupcoa', $parssing);
    }

    public function store(Request $req){
        
        $user_id = Auth::user()->id;
        $this->validate($req, [
            'group_coa_type' => 'required|numeric',
            'kode' => 'required',
            'desc' => 'required'            
        ]);

        if($req->isMethod('post'))
        {
            

            DB::beginTransaction();

            try{                
                $coa = new GroupCoa();
                $coa->group_coa_type    = $req->group_coa_type;
                $coa->code              = $req->kode;
                $coa->desc              = $req->desc;
                $coa->is_deleted        = 0;
                $coa->create_at         = date('Y-m-d');
                $coa->created_by        = $user_id;
                $coa->Save();
                    
                notify()->flash('Success!', 'success', [
                    'text' => 'Data Group Coa berhasil tersimpan',
                ]);
                
                DB::commit();
                return redirect('akuntansi/groupcoa');
                    
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
        $parssing['title'] = ucwords('Detail Group Kode Rekening');        
        $parssing['data_edit'] = GroupCoa::findOrFail($Id);   
        return view('Akuntansi.updateGroupCoa', $parssing);
    }

    public function update(Request $req,$id)
    {
        
        $user_id = Auth::user()->id;        
        $this->validate($req, [
            'group_coa_type' => 'required',
            'code' => 'required',
            'desc' => 'required'                    
        ]);
        DB::beginTransaction();
        try{            
            $groupcoa = GroupCoa::findOrFail($id);
            $groupcoa->group_coa_type   = $req->group_coa_type;
            $groupcoa->code             = $req->code;
            $groupcoa->desc             = $req->desc;
            $groupcoa->updated_by       = $user_id;
            $groupcoa->Save();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Kode rekening berhasil dihapus',
            ]);
        }
        catch(ValidationException $e){
            DB::rollback();
            print("ERROR VALIDATION");
            notify()->flash('Gagal!', 'warning', [
                'text' => 'Perubahan Group COA gagal, pastikan data telah terisi dan dengan format yang benar',
            ]);
            
            die();
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
            print("ERROR EXCEPTION");
            die();
        }
        
        DB::commit();
        return redirect('akuntansi/groupcoa/');
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

    public function status_delete($id)
    {
        $user_id = Auth::user()->id;        
       
        DB::beginTransaction();
        try{            
            $groupcoa = GroupCoa::findOrFail($id);
            $groupcoa->is_deleted      = 1;            
            $groupcoa->updated_by       = $user_id;
            $groupcoa->Save();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Kode rekening berhasil dihapus',
            ]);
        }
        catch(ValidationException $e){
            DB::rollback();
            print("ERROR VALIDATION");
            notify()->flash('Gagal!', 'warning', [
                'text' => 'Perubahan Group COA, pastikan data telah terisi dan dengan format yang benar',
            ]);
            
            die();
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
            print("ERROR EXCEPTION");
            die();
        }
        
        DB::commit();
        return redirect('akuntansi/groupcoa/');
    }

}