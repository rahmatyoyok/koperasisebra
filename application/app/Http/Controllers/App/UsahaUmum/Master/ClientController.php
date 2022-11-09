<?php

namespace App\Http\Controllers\App\UsahaUmum\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\Client;
use DB, Form, Auth;

class ClientController extends AppController
{

    public function index(Request $request)
    {
        if($request->ajax())
        {
            $getData = Client::select('*')->where('deleted_at','=',null);

            $datatables = Datatables::of($getData)
                ->addColumn('action', function ($value) {

                    $html =
                        '<a href="'.url('usaha/master/client/'.$value->client_id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                        '&nbsp;'
                        .\Form::open([ 'method'  => 'delete', 'route' => [ 'client.destroy', $value->client_id ], 'style' => 'display: inline-block;' ]).
                        '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus Client '.$value->nama_client.'?" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>'
                        .\Form::close();

                    return $html;
                })
                ->rawColumns(['action']);
            return $datatables->make(true);
        }
        return view('usaha.master.client.index');
    }

    public function create()
    {
        return view('usaha.master.client.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'nama_client' => 'required',
        ]);
        DB::beginTransaction();
        try
        {
            $data = new Client();
            $data->nama_client = $request->nama_client;
            $data->nama_penghubung_cust = $request->nama_penghubung_cust;
            $data->no_tlp = $request->no_tlp;
            $data->no_fax = $request->no_fax;
            $data->alamat = $request->alamat;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Client berhasil ditambah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/client');
    }

    public function edit($id)
    {
        $data['data_edit'] = Client::findOrFail($id);
        return view('usaha.master.client.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'nama_client' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $data = Client::findOrFail($id);
            $data->nama_client = $request->nama_client;
            $data->nama_penghubung_cust = $request->nama_penghubung_cust;
            $data->no_tlp = $request->no_tlp;
            $data->no_fax = $request->no_fax;
            $data->alamat = $request->alamat;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Client berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/client');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = Client::findOrFail($id);
            $data->deleted_at = date('Y-m-d H:m:s');
            $data->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Client berhasil dihapus',
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
        return redirect('usaha/master/client');
    }



}
