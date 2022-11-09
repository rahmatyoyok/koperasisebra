<?php

namespace App\Http\Controllers\App\UsahaUmum\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\Lokasi;
use DB, Form, Auth;

class LokasiController extends AppController
{

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $getData = Lokasi::select('*')->where('deleted_at','=',null);

            $datatables = Datatables::of($getData)
                ->addColumn('action', function ($value) {

                    $html =
                        '<a href="'.url('usaha/master/lokasi/'.$value->lokasi_id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                        '&nbsp;'
                        .\Form::open([ 'method'  => 'delete', 'route' => [ 'lokasi.destroy', $value->lokasi_id ], 'style' => 'display: inline-block;' ]).
                        '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus Lokasi '.$value->nama_lokasi.'?" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>'
                        .\Form::close();

                    return $html;
                })
                ->rawColumns(['action']);
            return $datatables->make(true);
        }
        return view('usaha.master.lokasi.index');
    }

    public function create()
    {
        return view('usaha.master.lokasi.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'nama_lokasi' => 'required',
        ]);
        DB::beginTransaction();
        try
        {
            $data = new Lokasi();
            $data->nama_lokasi = $request->nama_lokasi;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Lokasi berhasil ditambah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/lokasi');
    }

    public function edit($id)
    {
        $data['data_edit'] = Lokasi::findOrFail($id);
        return view('usaha.master.lokasi.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'nama_lokasi' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $data = Lokasi::findOrFail($id);
            $data->nama_lokasi = $request->nama_lokasi;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Lokasi berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/lokasi');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = Lokasi::findOrFail($id);
            $data->deleted_at = date('Y-m-d H:m:s');
            $data->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Lokasi berhasil dihapus',
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
        return redirect('usaha/master/lokasi');
    }



}
