<?php

namespace App\Http\Controllers\App\UsahaUmum;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\Persekot;
use App\Http\Models\PersekotLog;
use App\Http\Models\PersekotPO;
use App\Http\Models\PersekotPOLog;
use App\Http\Models\Bank;
use App\Http\Models\Spv;
use App\Http\Models\WorkOrder;
use App\Http\Models\PurchaseOrder;
use App\Http\Models\PurchaseOrderDetail;
use App\Http\Models\PembayaranPO;
use App\Http\Models\PembayaranWO;
use App\Http\Models\PembayaranWOSimulasi;
use App\Http\Models\Lokasi;
use App\Http\Models\Client;
use App\Http\Models\Supplier;
use App\Http\Models\Stockcode;
use App\Http\Models\Pr;
use App\Http\Models\Purchase;
use App\Http\Models\PurchaseDetail;

use App\Http\Models\SettingUsaha;
use App\Http\Models\User;
use DB, Form, Auth;

class PdfController extends AppController
{
  public function persekot(Request $request)
  {
      $id = $request->get('kd');
      $status = $request->get('status');

      $persekot = Persekot::findOrFail($id);

      $data['data_edit'] = Persekot::findOrFail($id);
      $data['bank_id'] = Bank::findOrFail($data['data_edit']->bank_id);
      $data['spv_id'] = Spv::findOrFail($data['data_edit']->spv_id);
      $data['petugas_id'] = User::findOrFail($data['data_edit']->petugas_id);

      $data['status'] = $status;

      $pdf = \PDF::loadView("usaha.pdf.persekot",$data);
      return $pdf->stream("Persekot.pdf");

  }

  public function persekotPO(Request $request)
  {
    $id = $request->get('kd');
    $status = $request->get('status');

    $persekot = PersekotPO::findOrFail($id);
    $data['data_edit'] = PersekotPO::findOrFail($id);
    $data['bank_id'] = Bank::findOrFail($data['data_edit']->bank_id);
    $data['spv_id'] = Spv::findOrFail($data['data_edit']->spv_id);
    $data['petugas_id'] = User::findOrFail($data['data_edit']->petugas_id);

    $data['status'] = $status;

    $pdf = \PDF::loadView("usaha.pdf.persekotPO",$data);
    return $pdf->stream("Persekot PO.pdf");
  }

  public function wo(Request $request)
  {
    $id = $request->get('kd');

    $data['data_edit'] = WorkOrder::findOrFail($id);

    $lokasi = Lokasi::findOrFail($data['data_edit']->lokasi_id);
    $client = Client::findOrFail($data['data_edit']->client_id);

    $data['lokasi'] = $lokasi;
    $data['client'] = $client;

    $queryPembayaran = DB::select("select * from wo_pembayaran_simulasis
                        where wo_id = $id order by sort asc");

    $data['dataSimulasiPembayaran']=$queryPembayaran;

    $queryPembayaran = DB::select("select p.*, u.name as operator from wo_pembayarans p
                        inner join users u on u.id = p.created_by
                        where wo_id = $id order by created_at desc");

    $data['dataPembayaran']=$queryPembayaran;
    $data['totalPembayaran'] = PembayaranWO::where('wo_id', $id)->sum('nominal');


    $queryPO = DB::select("select po.*,wo.nama_pekerjaan, wo.jenis_pekerjaan,wo.nilai_pekerjaan,
              (Select sum(pd.harga) from po_details pd where pd.po_id = po.po_id) total_po,
              u.`name` as operator from purchase_orders po
              inner join work_orders wo on wo.wo_id = po.wo_id
              inner join users u on u.id = po.created_by
              where wo.status = 1  and po.wo_id = $id order by po.tanggal_po desc");
    $data['dataPO']=$queryPO;

    $query = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekot_po p
                inner join spvs s on s.spv_id = p.spv_id
                left join banks b on b.bank_id = p.bank_id
                inner join users u on u.id = p.created_by
                where p.status = 2 and p.wo_id = $id order by p.tgl_pengajuan asc");
    $data['dataPersekotPO']=$query;

    $pdf = \PDF::loadView("usaha.pdf.wo",$data);
    return $pdf->stream("Work Order.pdf");
  }

  public function po(Request $request)
  {
    $id = $request->get('kd');
    $type = $request->get('type');

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

    if($type == 'supplier'){
      $pdf = \PDF::loadView("usaha.pdf.po-supplier",$data);
    }else{
      $pdf = \PDF::loadView("usaha.pdf.po",$data);
    }
    
    return $pdf->stream("Purchase Order.pdf");
  }

  public function purchase(Request $request)
  {
    $id = $request->get('kd');
    $data['data_edit'] = Purchase::findOrFail($id);

    $queryDetail = DB::select("select pd.*,ac.code,ac.desc from purchase_details pd
                inner join  ak_coa ac on ac.coa_id = pd.coa_id
                where purchase_id = $id");
    $data['detail'] = $queryDetail;

    $pdf = \PDF::loadView("usaha.pdf.purchase",$data);
    return $pdf->stream("Pembelian Langsung.pdf");
  }


}
