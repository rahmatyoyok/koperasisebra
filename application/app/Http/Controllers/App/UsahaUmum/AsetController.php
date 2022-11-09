<?php

namespace App\Http\Controllers\App\UsahaUmum;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use App\Http\Models\Akuntansi\Coa;
use App\Http\Models\Akuntansi\TriggerAssets;
use App\Http\Models\Akuntansi\HeaderJurnals;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\Aset;
use App\Http\Models\AsetDetail;
use App\Http\Models\SettingUsaha;
use App\Http\Models\Supplier;



use DB, Form, Auth, Exception;;

class AsetController extends AppController
{
  public function list(Request $request)
  {
    if($request->ajax())
    {

        $query = AsetDetail::where('status',1);
        $datatables = Datatables::of($query)
            ->addColumn('action', function ($value) {

                $html =
                '<a href="'.url('usaha/aset/'.$value->aset_id.'').'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.
                    // '<a href="'.url('usaha/aset/'.$value->id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                    '&nbsp;';
                return $html;
            })
            ->editColumn('total', function($value){
              return toRp($value->total);
            })
            ->rawColumns(['action']);
        return $datatables->make(true);
    }
    return view('usaha.aset.list');
  }

  public function index(Request $request)
  {
    if($request->ajax())
    {

        $query = Aset::where('status',1);
        $datatables = Datatables::of($query)
            ->addColumn('action', function ($value) {

                $html =
                '<a href="'.url('usaha/aset/'.$value->id.'').'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.
                    // '<a href="'.url('usaha/aset/'.$value->id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                    '&nbsp;'
                    .\Form::open([ 'method'  => 'delete', 'route' => [ 'aset.destroy', $value->id ], 'style' => 'display: inline-block;' ]).
                    '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus Aset '.$value->kode_aset.'?" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>'
                    .\Form::close();
                return $html;
            })
            ->editColumn('total', function($value){
              return toRp($value->total);
            })
            ->rawColumns(['action']);
        return $datatables->make(true);
    }
    return view('usaha.aset.index');
  }

  public function show($id)
  {
    $data['data_edit'] = Aset::findOrFail($id);

    $supplier = Supplier::findOrFail($data['data_edit']->supplier_id);

    $data['supplier'] = $supplier;

    $queryDetail = DB::select("Select ad.*, ac.code, ac.desc,
                                  bc.code code_beban_penyusutan,
                                  cc.code code_akm_penyusutan,
                                  bc.desc desc_beban_penyusutan,
                                  cc.desc desc_akm_penyusutan
                                  
                              FROM aset_details ad
                              inner join ak_coa ac on ac.coa_id = ad.coa_id
                              left join ak_coa bc on bc.coa_id = ad.beban_penyusutan_id
                              left join ak_coa cc on cc.coa_id = ad.akm_penyusutan_id
                      WHERE ad.aset_id = $id
                      ");
                      
    $data['dataDetail']=$queryDetail;

    return view('usaha.aset.show', $data);

  }


  public function create(Request $request)
  {
    
      $data['setting'] = SettingUsaha::findOrFail(1);
      $data['supplier'] = Supplier::pluck('nama_supplier', 'supplier_id');


      return view('usaha.aset.create',$data);
  }
  
  public function store(Request $request)
  {

      $validator = \Validator::make($request->all(), [
        'supplier_id' => 'required',
        'no_kwitansi' => 'required',
        'tgl_pembelian' => 'required',
      ]);

      if ($validator->fails()) {
          return redirect('usaha/aset/create')
                      ->withErrors($validator)
                      ->withInput();
      }
      if(count($request->coa) == 0){
        return redirect('usaha/aset/create')
                    ->withErrors(['message1'=>'Daftar Pembelian Aset belum terisi'])
                    ->withInput();
      }

      DB::beginTransaction();
      try
      {
        
          $mcoa = new Coa();
          // dd($coa->coaAsetBangunan);

          $cekDataKode = Aset::whereYear('tgl_pembelian',date('Y'))->count();
          if($cekDataKode == 0){
            $newCode = "001";
          }else{
            $maxCode = Aset::whereYear('tgl_pembelian',date('Y'))->max(DB::raw('LEFT(kode_aset,3)'));
            $maxCode = (int)$maxCode;
            $maxCode++;
            $newCode = sprintf("%03s", $maxCode);
          }
          $kodeAset = $newCode."/ASET/KPRISEBRA/".getInfoBulanRomawi($request->tgl_pembelian)."/".date('Y');

          $data = new Aset();
          $data->kode_aset = $kodeAset;
          $data->supplier_id = $request->supplier_id;
          $data->no_kwitansi = $request->no_kwitansi;
          $data->tgl_pembelian = date('Y-m-d',strtotime($request->tgl_pembelian));
          $data->keterangan = $request->keterangan;
          // $data->ppn = $request->ppn;
          // $data->pph22 = $request->pph22;
          // $data->pph23 = $request->pph23;
          // $data->nominal_ppn = replaceRp($request->labelPPNNominal);
          // $data->nominal_pph = replaceRp($request->labelPPHNominal);
          // $data->total_detail = replaceRp($request->labelTotalDetail);
          $data->total = replaceRp($request->labelTotal);
          $data->status = 1;
          $data->created_by = \Auth::user()->id;
          $data->save();

          $coa = $request->coa;
          $hargaSupplier = $request->harga_supplier;
          $jumlah = $request->jumlah;
          $nama = $request->nama;
          $masa_manfaat = $request->masa_manfaat;
          $tarif = $request->tarif;

          $coaBeban = $request->coaBeban;
          $coaAkm = $request->coaAkm;
          $data_list =  array();

          $totalHarga = 0;
          
          for ($i=0; $i < count($coa); $i++) {

            if (in_array($coa[$i], $data_list))
            {

            }else{

              $totalHarga = $jumlah[$i]*replaceRp($hargaSupplier[$i]);

              $ca = Coa::find($coa[$i]);
              $modelDetail = new AsetDetail();
              $modelDetail->aset_id = $data->id;
              $modelDetail->coa = (isset($ca->code)) ? $ca->code: "";
              $modelDetail->nama = $nama[$i];
              $modelDetail->masa_manfaat = $masa_manfaat[$i];
              $modelDetail->tarif = $tarif[$i];
              $modelDetail->jumlah = $jumlah[$i];
              $modelDetail->harga = replaceRp($hargaSupplier[$i]);
              $modelDetail->total = $totalHarga;
              
              $modelDetail->coa_id = $coa[$i];

              if(($modelDetail->coa == $mcoa->coaAsetTanah) || ($modelDetail->coa == $mcoa->coaAsetBangunan)){
                  
                $coaBeban[$i] = null;
                $coaAkm[$i] = null;
              }
              else{
               
                if(strlen($coaBeban[$i]) == 0){
                  DB::rollback();
                  notify()->flash('Error!', 'error', [
                      'text' =>  'Coa Beban Penyusutan wajib diisi',
                  ]);
                  return redirect()->back();
                }
                if(strlen($coaAkm[$i]) == 0){

                  DB::rollback();
                  notify()->flash('Error!', 'error', [
                      'text' => 'Coa Akumulasi Penyusutan wajib diisi',
                  ]);
                  return redirect()->back();
                }
              }
              
              
              $modelDetail->beban_penyusutan_id = $coaBeban[$i];
              $modelDetail->akm_penyusutan_id = $coaAkm[$i];
              $modelDetail->save();

              
              array_push($data_list,$coa[$i]);
            }


          }

          TriggerAssets::jurnalTransaksi($data->id, $data->created_by);

          DB::commit();
          notify()->flash('Success!', 'success', [
              'text' => 'Aset berhasil ditambah',
          ]);
      }
      catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
      {
          DB::rollback();
          notify()->flash('Error!', 'error', [
              'text' => $e->getMessage(),
          ]);
      }
      return redirect('usaha/aset');
  }
  
  public function destroy($id)
    {
        DB::beginTransaction();
        try
        {

          $valid = HeaderJurnals::where('tigger_table','asets')->where('tigger_table_key_id',$id)->whereNotNull('posting_no')->count();
          if($valid === 1){
            $deleted = DB::delete("delete asets, aset_details, ak_journal_headers, ak_journal_details
                                          from asets
                                          inner join aset_details on asets.id = aset_details.aset_id
                                          inner join ak_journal_headers on ak_journal_headers.tigger_table = 'asets' and ak_journal_headers.tigger_table_key_id = asets.id
                                          inner join ak_journal_details on ak_journal_details.journal_header_id = ak_journal_headers.journal_header_id
                                          where asets.id = ?", array($id));



            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Lokasi berhasil dihapus',
            ]);

          }
          else{
            notify()->flash('Gagal!', 'error', [
                'text' => 'Jurnal Sudah Terposting.',
            ]);
          }
          
        }
        catch(\Illuminate\Database\QueryException $e)
        {
            DB::rollback();
            $pesan = config('app.debug') ? ' Pesan kesalahan: '.$e->getMessage() : '';
            notify()->flash('Gagal!', 'error', [
                'text' => 'Terjadi kesalahan pada database.'.$pesan,
            ]);
        }
        return redirect('usaha/aset');
    }
}
