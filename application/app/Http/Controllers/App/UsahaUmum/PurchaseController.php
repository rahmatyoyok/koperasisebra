<?php

namespace App\Http\Controllers\App\UsahaUmum;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\Purchase;
use App\Http\Models\PurchaseDetail;
use App\Http\Models\Bank;
use App\Http\Models\Spv;
use App\Http\Models\SettingUsaha;
use App\Http\Models\User;
use DB, Form, Auth;

class PurchaseController extends AppController
{



    public function index(Request $request)
    {
        if($request->ajax())
        {
            $query =  Purchase::where('deleted_at','=',null);

            $datatables = Datatables::of($query)
                ->addColumn('action', function ($value) {

                    $html =
                    '<a href="'.url('usaha/purchase/'.$value->purchase_id.'').'" class="btn btn-xs blue " >Detail</a>'.
                    '<a href="'.url('usaha/purchase/'.$value->purchase_id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                        '&nbsp;'
                        .\Form::open([ 'method'  => 'delete', 'route' => [ 'purchase.destroy', $value->purchase_id ], 'style' => 'display: inline-block;' ]).
                        '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus Pembelian Langsung '.$value->kode.'?" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>'
                        .\Form::close();;

                    return $html;
                })
                ->editColumn('total', function($value){
                  return toRp($value->total);
                })
                ->rawColumns(['action']);
            return $datatables->make(true);
        }
        return view('usaha.purchase.index');
    }

    public function create()
    {
        $data['user_id'] = User::where('is_active','<>',0)->where('id','<>',\Auth::user()->id)->pluck('name', 'id');
        $data['bank_id'] = Bank::where('bank_id','<>',0)->pluck('nama_bank', 'bank_id');
        $data['spv_id'] = Spv::where('deleted_at','<>',null)->select(DB::raw('CONCAT(jabatan_spv, " - ", nama_spv) AS nama_spv'),'spv_id')->pluck('nama_spv','spv_id');
        $data['setting'] = SettingUsaha::findOrFail(1);
        return view('usaha.purchase.create',$data);
    }

    public function add(Request $request)
    {
        $id = $request->get('kd');
        $data['data_edit'] = Purchase::findOrFail($id);
        $data['bank_id'] = Bank::where('bank_id','<>',0)->pluck('nama_bank', 'bank_id');
        $data['spv_id'] = Spv::where('bank_id','<>',0)->select(DB::raw('CONCAT(jabatan_spv, " - ", nama_spv) AS nama_spv'),'spv_id')->pluck('nama_spv','spv_id');

        return view('usaha.purchase.add',$data);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
          'diterima' => 'required',
          'tanggal_pembelian' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $cekDataKode = Purchase::count();
            if($cekDataKode == 0){
              $newCode = "0001";
            }else{
              $maxCode = Purchase::max(DB::raw('LEFT(kode,4)'));
              $maxCode = (int)$maxCode;
              $maxCode++;
              $newCode = sprintf("%04s", $maxCode);
            }

            $nowDate = date('Y-m-d');
            $kode = $newCode."/"."PBLS/".getInfoBulanSingkat($nowDate)."/".date('Y');

            $data = new Purchase();
            $data->kode = $kode;
            $data->total = $request->labelTotal;
            $data->diterima = $request->diterima;
            $data->jenis_pembayaran = $request->jenis_pembayaran;
            $data->keterangan = $request->keterangan;
            $data->status = 1;
            $data->tanggal_pembelian = date('Y-m-d',strtotime($request->tanggal_pembelian));
            $data->created_by = \Auth::user()->id;
            $data->save();

            $listData = $request->keterangan_detail;
            $coa = $request->coa_id;
            $jumlah = $request->jumlah;
            for ($i=0; $i < count($listData); $i++) {
              $modelDetail = new PurchaseDetail();
              $modelDetail->purchase_id = $data->purchase_id;
              $modelDetail->keterangan = $listData[$i];
              $modelDetail->coa_id = $coa[$i];
              $modelDetail->jumlah = replaceRp($jumlah[$i]);
              $modelDetail->save();
            }

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Pembelian Langsung berhasil ditambah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/purchase');
    }

    public function edit($id)
    {
        $data['data_edit'] = Purchase::findOrFail($id);
        $data['tanggal_pembelian'] = date('d-m-Y',strtotime($data['data_edit']->tanggal_pembelian));
        $queryDetail = DB::select("select pd.*,ac.code,ac.desc from purchase_details pd
                    inner join  ak_coa ac on ac.coa_id = pd.coa_id
                    where purchase_id = $id");
        $data['detail'] = $queryDetail;
        return view('usaha.purchase.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'diterima' => 'required',
          'tanggal_pembelian' => 'required',

        ]);

        DB::beginTransaction();
        try
        {
            $data = Purchase::findOrFail($id);
            // $data->total = $request->labelTotal;
            $data->diterima = $request->diterima;
            $data->jenis_pembayaran = $request->jenis_pembayaran;
            $data->keterangan = $request->keterangan;
            $data->tanggal_pembelian = date('Y-m-d',strtotime($request->tanggal_pembelian));
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Pembelian Langsung berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/purchase');
    }

    public function show($id)
    {
      $data['data_edit'] = Purchase::findOrFail($id);

      $queryDetail = DB::select("select pd.*,ac.code,ac.desc from purchase_details pd
                  inner join  ak_coa ac on ac.coa_id = pd.coa_id
                  where purchase_id = $id");
      $data['detail'] = $queryDetail;
      return view('usaha.purchase.show', $data);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = Purchase::findOrFail($id);
            $data->deleted_at = date('Y-m-d H:m:s');
            $data->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Pembelian Langsung berhasil dihapus',
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
        return redirect('usaha/purchase');
    }



}
