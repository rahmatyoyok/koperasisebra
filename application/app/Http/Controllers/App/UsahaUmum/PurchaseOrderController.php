<?php

namespace App\Http\Controllers\App\UsahaUmum;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\WorkOrder;
use App\Http\Models\PurchaseOrder;
use App\Http\Models\PurchaseOrderDetail;
use App\Http\Models\PembayaranPO;
use App\Http\Models\Lokasi;
use App\Http\Models\Client;
use App\Http\Models\Supplier;
use App\Http\Models\Stockcode;
use App\Http\Models\Pr;
use App\Http\Models\SettingUsaha;
use DB, Form, Auth;

use App\Http\Models\Akuntansi\TriggerPurchaseOrder;

class PurchaseOrderController extends AppController
{
  public function penerimaan($id)
  {
      DB::beginTransaction();
      try
      {
          $data = PurchaseOrder::findOrFail($id);
          if($data->status_penerimaan == 0){
            $data->tanggal_penerimaan = date('Y-m-d H:m:s');
            $data->status_penerimaan = 1;

            if($data->save()){             
                TriggerPurchaseOrder::jurnalTransaksi($id,null, \Auth::user()->id); //CallJurnal
            }
          }
         
          DB::commit();
          notify()->flash('Success!', 'success', [
            'text' => 'Berhasil Melakukan Penerimaan',
          ]);
      }
      catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
      {
          DB::rollback();
          $pesan = config('app.debug') ? ' Pesan kesalahan: '.$e->getMessage() : '';
          notify()->flash('Gagal!', 'error', [
              'text' => 'Terjadi kesalahan pada database.'.$pesan,
          ]);
      }

      return redirect('usaha/po/'.$id);
  }
  
  public function pembayaran(Request $request)
  {
      $id = $request->get('kd');

      $data['data_edit'] = PurchaseOrder::findOrFail($id);
      $data['labelJenisPekerjaan'] = labelJenisPekerjaan($data['data_edit']->jenis_pekerjaan);
      $data['supplier'] = Supplier::findOrFail($data['data_edit']->supplier_id);

      $totalPembayaran = PembayaranPO::where('po_id', $id)->sum('nominal');

      $kekurangan = $data['data_edit']->total-$totalPembayaran;
      $data['kekurangan'] = $kekurangan;

      return view('usaha.purchaseorder.pembayaran', $data);
  }

  public function pembayaranProses(Request $request)
  {
    $this->validate($request, [
      'catatan' => 'required',
      'nominal' => 'required',
    ]);
    DB::beginTransaction();
    try
    {
        $data = PurchaseOrder::findOrFail($request->po_id);

        $totalPembayaran = PembayaranPO::where('po_id', $request->po_id)->sum('nominal');
        $kekurangan = $data->total-$totalPembayaran;

        $nominal = replaceRp($request->nominal);
        
        if($nominal>$kekurangan){
          $nominal = $kekurangan;
        }


        $pembayaran = new PembayaranPO();
        $pembayaran->po_id = $request->po_id;
        $pembayaran->nominal = $nominal;
        $pembayaran->metode_pembayaran = $request->metode_pembayaran;
        $pembayaran->catatan = $request->catatan;
        if($request->file('dokumen') != null){
            $image = $request->file('dokumen');
            $nameImage = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/pembayaran_po');
            $image->move($destinationPath, $nameImage);
            $pembayaran->file = $nameImage;
        }
        $pembayaran->created_by = \Auth::user()->id;
        $pembayaran->save();

        TriggerPurchaseOrder::jurnalTransaksi($request->po_id,$pembayaran->pemb_po_id, \Auth::user()->id); //CallJurnal

        DB::commit();
        notify()->flash('Success!', 'success', [
            'text' => 'Pembayaran Purchase Order',
        ]);


    }
    catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
    {
        DB::rollback();
        notify()->flash('Error!', 'error', [
            'text' => $e->getMessage(),
        ]);
    }
    return redirect('usaha/po/'.$request->po_id);
  }

  public function persekotPO(Request $request)
  {
      $id = $request->get('kd');

      $data['data_edit'] = WorkOrder::findOrFail($id);
      $data['setting'] = SettingUsaha::findOrFail(1);

      $lokasi = Lokasi::findOrFail($data['data_edit']->lokasi_id);
      $client = Client::findOrFail($data['data_edit']->client_id);

      $data['lokasi'] = $lokasi;
      $data['client'] = $client;

      $data['supplier'] = Supplier::pluck('nama_supplier', 'supplier_id');

      $data['labelJenisPekerjaan'] = labelJenisPekerjaan($data['data_edit']->jenis_pekerjaan);

      return view('usaha.purchaseorder.createPersekotPO',$data);
  }
  public function index(Request $request)
  {
    if($request->ajax())
    {

        $query = DB::select("	Select po.*,wo.nama_pekerjaan,wo.jenis_pekerjaan,wo.nilai_pekerjaan,
                                u.`name` as operator,s.nama_supplier from purchase_orders po
                                inner join work_orders wo on wo.wo_id = po.wo_id
                                inner join users u on u.id = po.created_by
                                inner join suppliers s on s.supplier_id = po.supplier_id
                                where wo.status = 1");
        $datatables = Datatables::of($query)
            ->addColumn('action', function ($value) {

                $html =
                '<a href="'.url('usaha/po/'.$value->po_id.'').'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.
                    // '<a href="'.url('usaha/po/'.$value->po_id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                    '&nbsp;'
                    .\Form::open([ 'method'  => 'delete', 'route' => [ 'lokasi.destroy', $value->po_id ], 'style' => 'display: inline-block;' ]).
                    '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus WorkOrder '.$value->nama_pekerjaan.'?" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>'
                    .\Form::close();
                return $html;
            })
            ->editColumn('jenis_pekerjaan', function($value){
              if($value->jenis_pekerjaan == 1){
                return "Material";
              }else{
                return "Jasa";
              }
            })
            ->editColumn('nilai_pekerjaan', function($value){
              return toRp($value->nilai_pekerjaan);
            })
            ->rawColumns(['action']);
        return $datatables->make(true);
    }
    return view('usaha.purchaseorder.index');
  }

  public function show($id)
  {
    $data['data_edit'] = PurchaseOrder::findOrFail($id);
    $data['data_wo'] = WorkOrder::findOrFail($data['data_edit']->wo_id);
    $data['labelJenisPekerjaan'] = labelJenisPekerjaan($data['data_wo']->jenis_pekerjaan);

    $lokasi = Lokasi::findOrFail($data['data_wo']->lokasi_id);
    $client = Client::findOrFail($data['data_wo']->client_id);
    $supplier = Supplier::findOrFail($data['data_edit']->supplier_id);

    $data['lokasi'] = $lokasi;
    $data['client'] = $client;
    $data['supplier'] = $supplier;

    $queryDetail = DB::select("select pod.*, sc.nama_stockcode, sc.stockcode, pr.nama_pr, pr.pr
                      from po_details pod
                      
                      LEFT JOIN stockcodes sc on pod.jenis_pekerjaan = 1 and pod.item_id = sc.stockcode_id
                      LEFT JOIN prs pr on pod.jenis_pekerjaan = 2 and pod.item_id = pr.pr_id
                      WHERE pod.po_id = $id
                      ");
    $data['dataDetail']=$queryDetail;

    $queryPembayaran = DB::select("select p.*, u.name as operator from po_pembayarans p
                        inner join users u on u.id = p.created_by
                        inner join purchase_orders po on po.po_id = p.po_id
                        where p.po_id = $id order by created_at desc");

    $data['dataPembayaran']=$queryPembayaran;
    $data['totalPembayaran'] = PembayaranPO::where('po_id', $id)->sum('nominal');
    return view('usaha.purchaseorder.show', $data);

  }

  public function create(Request $request)
  {
      $id = $request->get('kd');

      $data['data_edit'] = WorkOrder::findOrFail($id);
      $data['setting'] = SettingUsaha::findOrFail(1);
      
      $lokasi = Lokasi::findOrFail($data['data_edit']->lokasi_id);
      $client = Client::findOrFail($data['data_edit']->client_id);

      $data['lokasi'] = $lokasi;
      $data['client'] = $client;

      $data['supplier'] = Supplier::pluck('nama_supplier', 'supplier_id');

      $data['labelJenisPekerjaan'] = labelJenisPekerjaan($data['data_edit']->jenis_pekerjaan);

      return view('usaha.purchaseorder.create',$data);
  }

  public function store(Request $request)
  {

      $validator = \Validator::make($request->all(), [
        'supplier_id' => 'required',
        'no_kwitansi' => 'required',
        'tanggal_po' => 'required',
      ]);

      if ($validator->fails()) {
          return redirect('usaha/po/create?kd='.$request->wo_id)
                      ->withErrors($validator)
                      ->withInput();
      }
      if(count($request->listData) == 0){
        return redirect('usaha/po/create?kd='.$request->wo_id)
                    ->withErrors(['message1'=>'Detail PO belum terisi'])
                    ->withInput();
      }

      DB::beginTransaction();
      try
      {
          $cekDataKode = PurchaseOrder::whereYear('tanggal_po',date('Y'))->count();
          if($cekDataKode == 0){
            $newCode = "001";
          }else{
            $maxCode = PurchaseOrder::whereYear('tanggal_po',date('Y'))->max(DB::raw('LEFT(kode_po,3)'));
            $maxCode = (int)$maxCode;
            $maxCode++;
            $newCode = sprintf("%03s", $maxCode);
          }
          $kodePO = $newCode."/PO/KPRISEBRA/".getInfoBulanRomawi($request->tanggal_po)."/".date('Y');

          $modelWO = WorkOrder::findOrFail($request->wo_id);
          
          $data = new PurchaseOrder();
          $data->kode_po = $kodePO;
          $data->wo_id = $request->wo_id;
          $data->supplier_id = $request->supplier_id;
          $data->no_kwitansi = $request->no_kwitansi;
          $data->tanggal_po = date('Y-m-d',strtotime($request->tanggal_po));
          $data->tanggal_livering_po = date('Y-m-d',strtotime($data->tanggal_po . "+".$request->levering_po." days"));
          $data->keterangan = $request->keterangan;
          $data->ppn = replaceRp($request->ppn);
          $data->pph22 = replaceRp($request->pph22);
          $data->pph23 = replaceRp($request->pph23);
          $data->bbm_konsumsi = replaceRp($request->bbm_konsumsi);
          $data->total_detail_bbm = replaceRp($request->labelTotalDetailBBM);
          $data->total_detail = replaceRp($request->labelTotalDetail);
          $data->total = replaceRp($request->labelTotal);
          
          if(replaceRp($request->labelTotal) == 0){
              notify()->flash('Error!', 'error', [
                  'text' => 'Total tidak boleh Kosong !',
              ]);
              return redirect('usaha/po/create?kd='.$request->wo_id)
                      ->withErrors($validator)
                      ->withInput();
              
          }

          if($request->file('dokumen') != null){
              $dokumen = $request->file('dokumen');
              $nameFile = time().'.'.$dokumen->getClientOriginalExtension();
              $destinationPath = public_path('/dokumen_po');
              $dokumen->move($destinationPath, $nameFile);
              $data->dokumen = $nameFile;
          }

          $data->status = 1;
          $data->created_by = \Auth::user()->id;
          $data->save();

          $listData = $request->listData;
          $hargaSupplier = $request->harga_supplier;
          $jumlah = $request->jumlah;
          $jenis_item = $request->jenis_item;
          $data_list =  array();

          $totalHarga = 0;
          
          for ($i=0; $i < count($listData); $i++) {

            if (in_array($listData[$i].'+'.$jenis_item[$i], $data_list))
            {

            }else{
              $modelDetail = new PurchaseOrderDetail();
              $modelDetail->po_id = $data->po_id;
              $modelDetail->jenis_pekerjaan = $jenis_item[$i];
              $modelDetail->item_id = $listData[$i];
              $modelDetail->jumlah = $jumlah[$i];
              $modelDetail->harga = replaceRp($hargaSupplier[$i]);
              $modelDetail->created_by = \Auth::user()->id;
              $modelDetail->save();

              $totalHarga = $totalHarga+replaceRp($hargaSupplier[$i]);
              array_push($data_list,$listData[$i].'+'.$jenis_item[$i]);
            }


          }

          DB::commit();
          notify()->flash('Success!', 'success', [
              'text' => 'Purchase Order berhasil ditambah',
          ]);
      }
      catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
      {
          DB::rollback();
          notify()->flash('Error!', 'error', [
              'text' => $e->getMessage(),
          ]);
      }
      return redirect('usaha/po');
  }

  public function edit($id)
  {
    $data['data_edit'] = PurchaseOrder::findOrFail($id);
    $data['setting'] = SettingUsaha::findOrFail(1);
    $data['tanggal_po'] = date('d-m-Y',strtotime($data['data_edit']->tanggal_po));
    $data['tanggal_livering_po'] = date('d-m-Y',strtotime($data['data_edit']->tanggal_livering_po));

    $data['data_wo'] = WorkOrder::findOrFail($data['data_edit']->wo_id);
    $data['labelJenisPekerjaan'] = labelJenisPekerjaan($data['data_wo']->jenis_pekerjaan);

    $lokasi = Lokasi::findOrFail($data['data_wo']->lokasi_id);
    $client = Client::findOrFail($data['data_wo']->client_id);
    $supplier = Supplier::findOrFail($data['data_edit']->supplier_id);

    $data['lokasi'] = $lokasi;
    $data['client'] = $client;
    $data['supplier'] = $supplier;

    $queryDetail = DB::select("select pod.*, sc.nama_stockcode, sc.stockcode, pr.nama_pr, pr.pr,pod.item_id
                      from po_details pod
                      LEFT JOIN stockcodes sc on pod.jenis_pekerjaan = 1 and pod.item_id = sc.stockcode_id
                      LEFT JOIN prs pr on pod.jenis_pekerjaan = 2 and pod.item_id = pr.pr_id
                      WHERE pod.po_id = $id
                      ");
    $data['dataDetail']=$queryDetail;
    $data['supplier'] = Supplier::pluck('nama_supplier', 'supplier_id');

    return view('usaha.purchaseorder.edit', $data);

  }

  public function update(Request $request, $id)
  {
      $validator = \Validator::make($request->all(), [
        'no_kwitansi' => 'required',
        'tanggal_po' => 'required',
        'tanggal_livering_po' => 'required',
      ]);

      if ($validator->fails()) {
          return redirect("usaha/po/$id/edit")
                      ->withErrors($validator)
                      ->withInput();
      }
      if(count($request->listData) == 0){
        return redirect("usaha/po/$id/edit")
                    ->withErrors(['message1'=>'Detail PO belum terisi'])
                    ->withInput();
      }

      DB::beginTransaction();
      try
      {
          $data = PurchaseOrder::findOrFail($id);
          $data->supplier_id = $request->supplier_id;
          $data->no_kwitansi = $request->no_kwitansi;
          $data->tanggal_po = date('Y-m-d',strtotime($request->tanggal_po));
          $data->tanggal_livering_po = date('Y-m-d',strtotime($request->tanggal_livering_po));
          $data->bbm_konsumsi = replaceRp($request->bbm_konsumsi);

          if($request->file('dokumen') != null){
              $dokumen = $request->file('dokumen');
              $nameFile = time().'.'.$dokumen->getClientOriginalExtension();
              $destinationPath = public_path('/dokumen_po');
              $image->move($destinationPath, $nameFile);
              $data->dokumen = $nameFile;
          }

          $data->save();

          $modelWO = WorkOrder::findOrFail($data->wo_id);

          PurchaseOrderDetail::where('po_id',$data->po_id)->delete();
          $listData = $request->listData;
          $hargaSupplier = $request->harga_supplier;
          $jumlah = $request->jumlah;
          $data_list =  array();

          for ($i=0; $i < count($listData); $i++) {

            if (in_array($listData[$i], $data_list))
            {

            }else{
              $modelDetail = new PurchaseOrderDetail();
              $modelDetail->po_id = $data->po_id;
              $modelDetail->jenis_pekerjaan = $modelWO->jenis_pekerjaan;
              $modelDetail->item_id = $listData[$i];
              $modelDetail->harga = replaceRp($hargaSupplier[$i]);
              $modelDetail->jumlah = $jumlah[$i];
              $modelDetail->created_by = \Auth::user()->id;
              $modelDetail->save();
              array_push($data_list,$listData[$i]);
            }


          }

          DB::commit();
          notify()->flash('Success!', 'success', [
              'text' => 'Purchase Order berhasil diganti',
          ]);
      }
      catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
      {
          DB::rollback();
          notify()->flash('Error!', 'error', [
              'text' => $e->getMessage(),
          ]);
      }
      return redirect('usaha/po');
  }

  public function destroy($id)
  {
      DB::beginTransaction();
      try
      {
          $data = PurchaseOrder::findOrFail($id);
          $data->deleted_at = date('Y-m-d H:m:s');
          $data->save();
          DB::commit();
          notify()->flash('Sukses!', 'success', [
              'text' => 'Purchase Order berhasil dihapus',
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
      return redirect('usaha/po');
  }

  public function reportPembayaran(Request $request)
  {
    $query = "	select custom_data.*, (total - total_pembayaran) as kekurangan
                 from
                 (select (select IFNULL(sum(nominal),0) from po_pembayarans pop where pop.po_id = po.po_id) total_pembayaran,s.nama_supplier as supplier, u.`name` as operator,
                 po.* 
                 from purchase_orders po
                 inner join suppliers s on s.supplier_id = po.supplier_id
                 inner join users u on u.id = po.created_by
                 where po.`status` = 1) custom_data where  (total - total_pembayaran) <> 0 ";

    $data['data'] =DB::select($query);
    $data['total']=count($data['data']);

    return view('usaha.purchaseorder.reportPembayaran',$data);
  }

  public function report(Request $request)
  {
      if($request->ajax())
      {

        $supplier_id = $request->get('supplier_id');
        $tglStart_1 = $request->get('tglStart_1');
        $tglEnd_1 = $request->get('tglEnd_1');

        $tglStart_2 = $request->get('tglStart_2');
        $tglEnd_2 = $request->get('tglEnd_2');

        $where = "";
        if($supplier_id != null){
          $where .= " and po.supplier_id = $supplier_id";
        }

        if($tglStart_1 != null){
          $where .= " and po.tanggal_po >= '$tglStart_1'";
          $where .= " and po.tanggal_po <= '$tglEnd_1'";
        }

        if($tglStart_2 != null){
          $where .= " and po.tanggal_livering_po >= '$tglStart_2'";
          $where .= " and po.tanggal_livering_po <= '$tglEnd_2'";
        }

        $query = "select po.no_kwitansi, po.tanggal_po, po.tanggal_livering_po, po.kode_po, pod.*,s.nama_supplier,
                    sc.nama_stockcode, sc.stockcode, pr.nama_pr, pr.pr, pod.jumlah 
                    from po_details pod
                    INNER JOIN purchase_orders po on po.po_id = pod.po_id
                    INNER JOIN suppliers s on s.supplier_id = po.supplier_id
                    LEFT JOIN stockcodes sc on pod.jenis_pekerjaan = 1 and pod.item_id = sc.stockcode_id
                    LEFT JOIN prs pr on pod.jenis_pekerjaan = 2 and pod.item_id = pr.pr_id
                    WHERE po.status = 1  $where order by po.tanggal_po desc";

        $data['data'] =DB::select($query);
        $data['total']=count($data['data']);

        return view('usaha.purchaseorder.report-detail',$data);
      }

      $data['supplier_id'] = Supplier::pluck('nama_supplier', 'supplier_id');
      return view('usaha.purchaseorder.report',$data);
  }


}
