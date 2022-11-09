<?php

namespace App\Http\Controllers\App\UsahaUmum\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\Supplier;
use DB, Form, Auth;

class SupplierController extends AppController
{

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $getData = Supplier::select('*')->where('deleted_at','=',null);

            $datatables = Datatables::of($getData)
                ->addColumn('action', function ($value) {

                    $html =
                        '<a href="'.url('usaha/master/supplier/'.$value->supplier_id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                        '&nbsp;'
                        .\Form::open([ 'method'  => 'delete', 'route' => [ 'supplier.destroy', $value->supplier_id ], 'style' => 'display: inline-block;' ]).
                        '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus Supplier '.$value->nama_supplier.'?" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>'
                        .\Form::close();

                    return $html;
                })
                ->rawColumns(['action']);
            return $datatables->make(true);
        }
        return view('usaha.master.supplier.index');
    }

    public function create()
    {
        return view('usaha.master.supplier.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'nama_supplier' => 'required',
        ]);
        DB::beginTransaction();
        try
        {
            $data = new Supplier();
            $data->nama_supplier = $request->nama_supplier;
            $data->nama_penghubung_sup = $request->nama_penghubung_sup;
            $data->no_tlp_sup = $request->no_tlp_sup;
            $data->no_fax = $request->no_fax;
            $data->alamat_sup = $request->alamat_sup;
            $data->no_npwp_sup = $request->no_npwp_sup;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Supplier berhasil ditambah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/supplier');
    }

    public function edit($id)
    {
        $data['data_edit'] = Supplier::findOrFail($id);
        return view('usaha.master.supplier.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'nama_supplier' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $data = Supplier::findOrFail($id);
            $data->nama_supplier = $request->nama_supplier;
            $data->nama_penghubung_sup = $request->nama_penghubung_sup;
            $data->no_tlp_sup = $request->no_tlp_sup;
            $data->no_fax = $request->no_fax;
            $data->alamat_sup = $request->alamat_sup;
            $data->no_npwp_sup = $request->no_npwp_sup;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Supplier berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/supplier');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = Supplier::findOrFail($id);
            $data->deleted_at = date('Y-m-d H:m:s');
            $data->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Supplier berhasil dihapus',
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
        return redirect('usaha/master/supplier');
    }



}
