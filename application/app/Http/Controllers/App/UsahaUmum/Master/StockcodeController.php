<?php

namespace App\Http\Controllers\App\UsahaUmum\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\Stockcode;
use DB, Form, Auth;

class StockcodeController extends AppController
{

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $getData = Stockcode::select('*')->where('deleted_at','=',null);

            $datatables = Datatables::of($getData)
                ->addColumn('action', function ($value) {

                    $html =
                        '<a href="'.url('usaha/master/stockcode/'.$value->stockcode_id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                        '&nbsp;'
                        .\Form::open([ 'method'  => 'delete', 'route' => [ 'stockcode.destroy', $value->stockcode_id ], 'style' => 'display: inline-block;' ]).
                        '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus Stockcode '.$value->nama_stockcode.'?" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>'
                        .\Form::close();

                    return $html;
                })
                ->editColumn('status', function ($value) {
                  return statusStockcode($value->status);
                })
                ->rawColumns(['action']);
            return $datatables->make(true);
        }
        return view('usaha.master.stockcode.index');
    }

    public function create()
    {
        return view('usaha.master.stockcode.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'nama_stockcode' => 'required',
          'stockcode' => 'required',
        ]);
        DB::beginTransaction();
        try
        {
            $data = new Stockcode();
            $data->stockcode = $request->stockcode;
            $data->nama_stockcode = $request->nama_stockcode;
            $data->status = $request->status;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Stockcode berhasil ditambah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/stockcode');
    }

    public function edit($id)
    {
        $data['data_edit'] = Stockcode::findOrFail($id);
        return view('usaha.master.stockcode.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'nama_stockcode' => 'required',
          'stockcode' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $data = Stockcode::findOrFail($id);
            $data->stockcode = $request->stockcode;
            $data->nama_stockcode = $request->nama_stockcode;
            $data->status = $request->status;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Stockcode berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/stockcode');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = Stockcode::findOrFail($id);
            $data->deleted_at = date('Y-m-d H:m:s');
            $data->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Stockcode berhasil dihapus',
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
        return redirect('usaha/master/stockcode');
    }



}
