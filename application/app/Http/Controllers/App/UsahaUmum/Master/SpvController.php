<?php

namespace App\Http\Controllers\App\UsahaUmum\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\Spv;
use DB, Form, Auth;

class SpvController extends AppController
{

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $getData = Spv::select('*')->where('deleted_at','=',null);

            $datatables = Datatables::of($getData)
                ->addColumn('action', function ($value) {

                    $html =
                        '<a href="'.url('usaha/master/spv/'.$value->spv_id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                        '&nbsp;'
                        .\Form::open([ 'method'  => 'delete', 'route' => [ 'spv.destroy', $value->spv_id ], 'style' => 'display: inline-block;' ]).
                        '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus Spv '.$value->nama_spv.'?" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>'
                        .\Form::close();

                    return $html;
                })
                ->rawColumns(['action']);
            return $datatables->make(true);
        }
        return view('usaha.master.spv.index');
    }

    public function create()
    {
        return view('usaha.master.spv.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'nip_spv' => 'required',
          'jabatan_spv' => 'required',
          'nama_spv' => 'required',
        ]);
        DB::beginTransaction();
        try
        {
            $data = new Spv();
            $data->nama_spv = $request->nama_spv;
            $data->jabatan_spv = $request->jabatan_spv;
            $data->bagian_spv = $request->bagian_spv;
            $data->nip_spv = $request->nip_spv;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Spv berhasil ditambah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/spv');
    }

    public function edit($id)
    {
        $data['data_edit'] = Spv::findOrFail($id);
        return view('usaha.master.spv.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'nip_spv' => 'required',
          'jabatan_spv' => 'required',
          'nama_spv' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $data = Spv::findOrFail($id);
            $data->nama_spv = $request->nama_spv;
            $data->jabatan_spv = $request->jabatan_spv;
            $data->bagian_spv = $request->bagian_spv;
            $data->nip_spv = $request->nip_spv;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Spv berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/spv');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = Spv::findOrFail($id);
            $data->deleted_at = date('Y-m-d H:m:s');
            $data->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Spv berhasil dihapus',
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
        return redirect('usaha/master/spv');
    }



}
