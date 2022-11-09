<?php

namespace App\Http\Controllers\App\UsahaUmum\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\Pr;
use DB, Form, Auth;

class PrController extends AppController
{

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $getData = Pr::select('*')->where('deleted_at','=',null);

            $datatables = Datatables::of($getData)
                ->addColumn('action', function ($value) {

                    $html =
                        '<a href="'.url('usaha/master/pr/'.$value->pr_id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                        '&nbsp;'
                        .\Form::open([ 'method'  => 'delete', 'route' => [ 'pr.destroy', $value->pr_id ], 'style' => 'display: inline-block;' ]).
                        '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus Pr '.$value->nama_pr.'?" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>'
                        .\Form::close();

                    return $html;
                })
                ->editColumn('status', function ($value) {
                  return statusPR($value->status);
                })
                ->rawColumns(['action']);
            return $datatables->make(true);
        }
        return view('usaha.master.pr.index');
    }

    public function create()
    {
        return view('usaha.master.pr.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'nama_pr' => 'required',
          'pr' => 'required',
        ]);
        DB::beginTransaction();
        try
        {
            $data = new Pr();
            $data->pr = $request->pr;
            $data->nama_pr = $request->nama_pr;
            $data->status = $request->status;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'PR berhasil ditambah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/pr');
    }

    public function edit($id)
    {
        $data['data_edit'] = Pr::findOrFail($id);
        return view('usaha.master.pr.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'nama_pr' => 'required',
          'pr' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $data = Pr::findOrFail($id);
            $data->pr = $request->pr;
            $data->nama_pr = $request->nama_pr;
            $data->status = $request->status;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'PR berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/pr');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = Pr::findOrFail($id);
            $data->deleted_at = date('Y-m-d H:m:s');
            $data->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'PR berhasil dihapus',
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
        return redirect('usaha/master/pr');
    }



}
