<?php

namespace App\Http\Controllers\App\UsahaUmum;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\WorkOrder;
use App\Http\Models\PembayaranWO;
use App\Http\Models\PembayaranWOSimulasi;
use App\Http\Models\Lokasi;
use App\Http\Models\Client;

use App\Http\Models\Akuntansi\TriggerWorkOrder;


use DB, Form, Auth;

class WorkOrderController extends AppController
{
    public function pengiriman($id)
    {
        DB::beginTransaction();
        try
        {
            $data = WorkOrder::findOrFail($id);
            
            $data->tanggal_pengiriman = date('Y-m-d H:m:s');
            $data->status_pengiriman = 1;

            

            $data->save();
            
            if($data->status_pengiriman == 1 && $data->jenis_wo == 1){ // Cek jika WO hanya PO
                TriggerWorkOrder::perubahanStatus($id, \Auth::user()->id); //CallJurnal
            }

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Berhasil Melakukan Pengiriman',
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
        
        

        return redirect('usaha/wo/'.$id);
    }

    public function pembayaran(Request $request)
    {
        $id = $request->get('kd');

        $data['data_edit'] = WorkOrder::findOrFail($id);

        $lokasi = Lokasi::findOrFail($data['data_edit']->lokasi_id);
        $client = Client::findOrFail($data['data_edit']->client_id);
        $totalPembayaran = PembayaranWO::where('wo_id', $id)->sum('nominal');
        $simulasi = PembayaranWOSimulasi::where('wo_id',$id)->where('status',0)->orderBY('sort','asc')->first(); 

        $kekurangan = $data['data_edit']->nilai_pekerjaan-$totalPembayaran;

        $data['lokasi'] = $lokasi;
        $data['client'] = $client;
        $data['kekurangan'] = $kekurangan;
        $data['simulasi'] = $simulasi;
        return view('usaha.workorder.pembayaran', $data);
    }

    public function pembayaranProses(Request $request)
    {
      $this->validate($request, [
        'catatan' => 'required',
        'jumlah_dibayar' => 'required',
      ]);
      DB::beginTransaction();
      try
      {
          $data = WorkOrder::findOrFail($request->id);

          $nominal = replaceRp($request->jumlah_dibayar)+replaceRp($request->ppn_keluaran)+replaceRp($request->pph22)+replaceRp($request->pph23);

          $totalPembayaran = PembayaranWO::where('wo_id', $request->id)->sum('nominal');
          $kekurangan = $data->nilai_pekerjaan-$totalPembayaran;

          $cekNominal = replaceRp($request->jumlah_dibayar)+replaceRp($request->pph22)+replaceRp($request->pph23);
          if($cekNominal>$kekurangan){
            notify()->flash('Error!', 'error', [
                'text' => 'Total Pembayaran Kelebihan !',
            ]);
            return redirect('usaha/wo/pembayaran?kd='.$request->id);
          }
        //   if($nominal>$kekurangan){
        //     $nominal = $kekurangan;
        //     PembayaranWOSimulasi::where('wo_id', $request->id)
        //   ->update(['status' => 1]);
        //   }else{
        //     PembayaranWOSimulasi::where('id', $request->simulasi)
        //     ->update(['status' => 1]);
        //   }

          $pembayaran = new PembayaranWO();
          $pembayaran->wo_id = $request->id;
          $pembayaran->nominal = $nominal;
          $pembayaran->jumlah_dibayar = replaceRp($request->jumlah_dibayar);
          $pembayaran->ppn_keluaran = replaceRp($request->ppn_keluaran);
          $pembayaran->pph22 = replaceRp($request->pph22);
          $pembayaran->pph23 = replaceRp($request->pph23);
          
          $pembayaran->metode_pembayaran = $request->metode_pembayaran;
          $pembayaran->catatan = $request->catatan;
          if($request->file('dokumen') != null){
              $image = $request->file('dokumen');
              $nameImage = time().'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('/pembayaran_wo');
              $image->move($destinationPath, $nameImage);
              $pembayaran->file = $nameImage;
          }
          $pembayaran->created_by = \Auth::user()->id;
          $pembayaran->save();
          
          TriggerWorkOrder::pembayaranWo($pembayaran->pemb_wo_id, \Auth::user()->id); //CallJurnal

          DB::commit();
          notify()->flash('Success!', 'success', [
              'text' => 'Pembayaran Work Order',
          ]);


      }
      catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
      {
          DB::rollback();
          notify()->flash('Error!', 'error', [
              'text' => $e->getMessage(),
          ]);
      }
      return redirect('usaha/wo/'.$request->id);
    }

    public function index(Request $request)
    {
        if($request->ajax())
        {

            // $query = "select custom_data.*, (nilai_pekerjaan - total_pembayaran) as kekurangan
            //               from
            //               (select (select IFNULL(sum(nominal),0) from wo_pembayarans wop where wop.wo_id = wo.wo_id) total_pembayaran,
            //               c.nama_client as client, l.nama_lokasi as lokasi, u.`name` as operator, wo.* from work_orders wo
            //               inner join clients c on c.client_id = wo.client_id
            //               inner join lokasis l on l.lokasi_id = wo.lokasi_id
            //               inner join users u on u.id = wo.created_by
            //               where wo.`status` = 1) custom_data where  (nilai_pekerjaan - total_pembayaran) = 0";

            $query = DB::select("select * from (
                select 
                (wo.nilai_pekerjaan-(select IFNULL(sum(nominal),0) from wo_pembayarans wop where wop.wo_id = wo.wo_id)) sisa,
                c.nama_client as client, l.nama_lokasi as lokasi, u.`name` as operator, wo.*
                from work_orders wo
                inner join clients c on c.client_id = wo.client_id
                inner join lokasis l on l.lokasi_id = wo.lokasi_id
                inner join users u on u.id = wo.created_by
                where wo.`status` = 1  order by wo.created_at desc) custom_tbl where sisa <> 0 ");
            $datatables = Datatables::of($query)
                ->addColumn('action', function ($value) {

                    $html =
                    '<a href="'.url('usaha/wo/'.$value->wo_id.'').'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.
                        '<a href="'.url('usaha/wo/'.$value->wo_id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                        '&nbsp;'
                        .\Form::open([ 'method'  => 'delete', 'route' => [ 'wo.destroy', $value->wo_id ], 'style' => 'display: inline-block;' ]).
                        '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus WorkOrder '.$value->nama_pekerjaan.'?" title="Delete"><i class="glyphicon glyphicon-trash"></i></button>'
                        .\Form::close();
                    return $html;
                })
                ->editColumn('jenis_pekerjaan', function($value){
                  return jenisPekerjaan($value->jenis_pekerjaan);
                })
                ->editColumn('jenis_wo', function($value){
                    return jenisWO($value->jenis_wo);
                  })
                ->editColumn('nilai_pekerjaan', function($value){
                  return toRp($value->nilai_pekerjaan);
                })
                ->rawColumns(['action']);
            return $datatables->make(true);
        }
        return view('usaha.workorder.index');
    }

    public function create()
    {
        $data['lokasi_id'] = Lokasi::where('deleted_at','=',null)->pluck('nama_lokasi', 'lokasi_id');
        $data['client_id'] = Client::where('deleted_at','=',null)->pluck('nama_client', 'client_id');
        return view('usaha.workorder.create',$data);
    }

    public function store(Request $request)
    {
        

        $validator = \Validator::make($request->all(), [
            'jenis_pekerjaan' => 'required',
            'nama_pekerjaan' => 'required',
            'lokasi_id' => 'required',
            //   'client_id' => 'required',
            'nilai_pekerjaan' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect('usaha/wo/create')
                        ->withErrors($validator)
                        ->withInput();
        }
    
        $keteranganPembayaran = $request->keterangan_pembayaran;
        if(count($keteranganPembayaran) == 0){
            notify()->flash('Error!', 'error', [
                'text' => 'Pembayaran Kosong !',
            ]);
            return redirect('usaha/wo/create')
                    ->withErrors($validator)
                    ->withInput();
            
        }

        DB::beginTransaction();
        try
        {
            
            $cekDataKode = WorkOrder::count();
            if($cekDataKode == 0){
              $newCode = "000001";
            }else{
              $maxCode = WorkOrder::max(DB::raw('RIGHT(kode_wo,6)'));
              $maxCode = (int)$maxCode;
              $maxCode++;
              $newCode = sprintf("%06s", $maxCode);
            }

            $kodeWO = "WO/".date('Y').'/0'.$request->jenis_pekerjaan.'/'.$newCode;

            $tglLevering = $request->tgl_levering;
            $getTgl = explode(" - ", $tglLevering);

            $data = new WorkOrder();
            $data->kode_wo = $kodeWO;
            $data->jenis_wo = $request->jenis_wo;
            $data->jenis_pekerjaan = $request->jenis_pekerjaan;
            $data->nama_pekerjaan = $request->nama_pekerjaan;
            $data->lokasi_id = $request->lokasi_id;
            // $data->client_id = $request->client_id;
            $data->client_id = 1;
            $data->keterangan = $request->keterangan;
            $data->no_refrensi = $request->no_refrensi;
            $data->nilai_pekerjaan = replaceRp($request->nilai_pekerjaan);
            $data->tgl_levering_start = date('Y-m-d',strtotime($getTgl[0]));
            $data->tgl_levering_end = date('Y-m-d',strtotime($getTgl[1]));

            if($request->file('dokumen') != null){
                $dokumen = $request->file('dokumen');
                $nameFile = time().'.'.$dokumen->getClientOriginalExtension();
                $destinationPath = public_path('/dokumen_wo');
                $dokumen->move($destinationPath, $nameFile);
                $data->dokumen = $nameFile;
            }

            $data->status = 1;
            $data->created_by = \Auth::user()->id;
            $data->save();

            $keteranganPembayaran = $request->keterangan_pembayaran;
            $tanggalPembayaran = $request->tanggal_pembayaran;
            $nominalPembayaran = $request->nominal_pembayaran;
            for ($i=0; $i < count($keteranganPembayaran); $i++) {
                $modelDetail = new PembayaranWOSimulasi();
                $modelDetail->wo_id = $data->wo_id;
                $modelDetail->keterangan = $keteranganPembayaran[$i];
                $modelDetail->tanggal = date('Y-m-d',strtotime($tanggalPembayaran[$i]));
                $modelDetail->nominal = replaceRp($nominalPembayaran[$i]);
                $modelDetail->sort = $i;
                $modelDetail->save();

                if(replaceRp($nominalPembayaran[$i]) == 0){
                    notify()->flash('Error!', 'error', [
                        'text' => 'Pembayaran Kosong !',
                    ]);
                    return redirect('usaha/wo/create')
                            ->withErrors($validator)
                            ->withInput();
                    
                }
            }
            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Work Order berhasil ditambah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/wo');
    }

    public function show($id)
    {
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
                  (Select sum(pd.harga) from po_details pd where pd.po_id = po.po_id) total_po,s.nama_supplier as supplier,
                  u.`name` as operator from purchase_orders po
                  inner join work_orders wo on wo.wo_id = po.wo_id
                  inner join suppliers s on s.supplier_id = po.supplier_id
                  inner join users u on u.id = po.created_by
                  where wo.status = 1  and po.wo_id = $id order by po.tanggal_po desc");
        $data['dataPO']=$queryPO;

        $query = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekot_po p
                    inner join spvs s on s.spv_id = p.spv_id
                    left join banks b on b.bank_id = p.bank_id
                    inner join users u on u.id = p.created_by
                    where (p.status = 1 or p.status = 2 or p.status = 4) and p.wo_id = $id order by p.tgl_pengajuan asc");
        $data['dataPersekotPO']=$query;

        return view('usaha.workorder.show', $data);
    }

    public function edit($id)
    {
        $data['data_edit'] = WorkOrder::findOrFail($id);

        if($data['data_edit']->status_pengiriman == 1){
            notify()->flash('Error!', 'error', [
                'text' => 'Work Order tidak bisa dirubah',
            ]);
        
            return redirect('usaha/wo');
        }
        $data['lokasi_id'] = Lokasi::where('deleted_at','=',null)->pluck('nama_lokasi', 'lokasi_id');
        $data['client_id'] = Client::where('deleted_at','=',null)->pluck('nama_client', 'client_id');
        return view('usaha.workorder.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'jenis_pekerjaan' => 'required',
          'nama_pekerjaan' => 'required',
          'lokasi_id' => 'required',
        //   'client_id' => 'required',
          'nilai_pekerjaan' => 'required',
        ]);

        DB::beginTransaction();
        try
        {
            $data = WorkOrder::findOrFail($id);
            $data->jenis_pekerjaan = $request->jenis_pekerjaan;
            $data->nama_pekerjaan = $request->nama_pekerjaan;
            $data->lokasi_id = $request->lokasi_id;
            // $data->client_id = $request->client_id;
            $data->keterangan = $request->keterangan;
            $data->no_refrensi = $request->no_refrensi;
            $data->nilai_pekerjaan = replaceRp($request->nilai_pekerjaan);

            if($request->file('dokumen') != null){
                $dokumen = $request->file('dokumen');
                $nameFile = time().'.'.$dokumen->getClientOriginalExtension();
                $destinationPath = public_path('/dokumen_wo');
                $image->move($destinationPath, $nameFile);
                $data->dokumen = $nameFile;
            }

            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Work Order berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/wo');
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = WorkOrder::findOrFail($id);
            $data->deleted_at = date('Y-m-d H:m:s');
            $data->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'WorkOrder berhasil dihapus',
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
        return redirect('usaha/wo');
    }
     public function reportPembayaran(Request $request)
     {
       $query = "	select custom_data.*, (nilai_pekerjaan - total_pembayaran) as kekurangan
                          from
                          (select (select IFNULL(sum(nominal),0) from wo_pembayarans wop where wop.wo_id = wo.wo_id) total_pembayaran,c.nama_client as client, l.nama_lokasi as lokasi, u.`name` as operator, wo.* from work_orders wo
                          inner join clients c on c.client_id = wo.client_id
                          inner join lokasis l on l.lokasi_id = wo.lokasi_id
                          inner join users u on u.id = wo.created_by
                          where wo.`status` = 1) custom_data where  (nilai_pekerjaan - total_pembayaran) <> 0 ";

       $data['data'] =DB::select($query);
       $data['total']=count($data['data']);

       return view('usaha.workorder.reportPembayaran',$data);
     }
    public function report(Request $request)
    {

        if($request->ajax())
        {

          $lokasi_id = $request->get('lokasi_id');
          $client_id = $request->get('client_id');
          $jenis_pekerjaan = $request->get('jenis_pekerjaan');
          $nama_pekerjaan = $request->get('nama_pekerjaan');

          $where = "";
          if($lokasi_id != null){
            $where .= " and wo.lokasi_id = $lokasi_id";
          }

          if($client_id != null){
            $where .= " and wo.client_id = $client_id";
          }

          if($jenis_pekerjaan != null){
            $where .= " and wo.jenis_pekerjaan = $jenis_pekerjaan";
          }

          if($nama_pekerjaan != null){
            $where .= " and wo.nama_pekerjaan like '%$nama_pekerjaan%'";
          }

          $query = "	select c.nama_client as client, l.nama_lokasi as lokasi, u.`name` as operator, wo.* from work_orders wo
          inner join clients c on c.client_id = wo.client_id
          inner join lokasis l on l.lokasi_id = wo.lokasi_id
          inner join users u on u.id = wo.created_by
          where wo.`status` = 1 $where order by wo.created_at desc";

          $data['data'] =DB::select($query);
          $data['total']=count($data['data']);

          return view('usaha.workorder.report-detail',$data);
        }

        $data['lokasi_id'] = Lokasi::pluck('nama_lokasi', 'lokasi_id');
        $data['client_id'] = Client::pluck('nama_client', 'client_id');

        return view('usaha.workorder.report',$data);
    }

}
