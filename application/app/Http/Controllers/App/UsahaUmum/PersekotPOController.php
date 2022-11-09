<?php

namespace App\Http\Controllers\App\UsahaUmum;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\PersekotPO;
use App\Http\Models\PersekotPOLog;
use App\Http\Models\Bank;
use App\Http\Models\Spv;
use App\Http\Models\SettingUsaha;
use App\Http\Models\User;
use App\Http\Models\Lokasi;
use App\Http\Models\Client;
use App\Http\Models\WorkOrder;
use DB, Form, Auth;

class PersekotPOController extends AppController
{

  public function report(Request $request)
  {

      if($request->ajax())
      {

        $status = $request->get('status');
        $start = $request->get('start');
        $end = $request->get('end');

        $where = "";
        if($status == ''){
            // $where = " WHERE p.status = $status";
        }else{
            $where = " WHERE p.status = $status";
        }

        // if($start != null){
        //   $where .= " and p.tgl_pengajuan >= '$start'";
        //   $where .= " and p.tgl_pengajuan <= '$end'";
        // }

        $query = DB::select("select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekot_po p
                        inner join spvs s on s.spv_id = p.spv_id
                        left join banks b on b.bank_id = p.bank_id
                        inner join users u on u.id = p.created_by
                        $where order by p.created_at desc");

        $data['data']=$query;
        return view('usaha.persekot-po.report-detail',$data);
      }
      $data['bank_id'] = Bank::pluck('nama_bank', 'bank_id');
      $data['spv_id'] = Spv::select(DB::raw('CONCAT(jabatan_spv, " - ", nama_spv) AS nama_spv'),'spv_id')->pluck('nama_spv','spv_id');

      return view('usaha.persekot-po.report',$data);
  }

  public function listVerifikasi(Request $request)
  {
    $query = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekot_po p
                    inner join spvs s on s.spv_id = p.spv_id
                    left join banks b on b.bank_id = p.bank_id
                    inner join users u on u.id = p.created_by
                    where p.status = 1 order by p.created_at desc");
    $data['data']=$query;
    return view('usaha.persekot-po.listVerifikasi',$data);
  }

  public function showVerifikasi($id)
  {
      $data['data_edit'] = PersekotPO::findOrFail($id);
      $data['bank_id'] = Bank::findOrFail($data['data_edit']->bank_id);
      $data['spv_id'] = Spv::findOrFail($data['data_edit']->spv_id);
      $data['petugas_id'] = User::findOrFail($data['data_edit']->petugas_id);
      return view('usaha.persekot-po.showVerifikasi', $data);
  }

  public function prosesVerifikasi(Request $request)
  {
    $this->validate($request, [
      'status' => 'required',
    ]);

    DB::beginTransaction();
    try
    {
        $data = PersekotPO::findOrFail($request->id);
        $data->status = $request->status;
        $data->save();

        if($request->status == 2){
          $status = "Diterima";
        }else{
          $status = "Ditolak";
        }

        $log = new PersekotPOLog();
        $log->persekot_id = $request->id;
        $log->catatan = $request->status_catatan;
        $log->log_text = "Verifikasi Persekot dengan Status ".$status;
        $log->created_by = \Auth::user()->id;
        $log->save();

        DB::commit();
        notify()->flash('Success!', 'success', [
            'text' => 'Persekot berhasil diverifikasi',
        ]);
    }
    catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
    {
        DB::rollback();
        notify()->flash('Error!', 'error', [
            'text' => $e->getMessage(),
        ]);
    }
    return redirect('usaha/persekotpo/'.$request->id);
  }

  public function showProses($id)
  {
      $data['data_edit'] = PersekotPO::findOrFail($id);
      $data['bank_id'] = Bank::findOrFail($data['data_edit']->bank_id);
      $data['spv_id'] = Spv::findOrFail($data['data_edit']->spv_id);
      $data['petugas_id'] = User::findOrFail($data['data_edit']->petugas_id);
      $data['setting'] = SettingUsaha::findOrFail(1);

      if($data['data_edit']->status == 2){
          $data['lokasi_id'] = Lokasi::where('deleted_at','=',null)->pluck('nama_lokasi', 'lokasi_id');
          $data['client_id'] = Client::where('deleted_at','=',null)->pluck('nama_client', 'client_id');
          return view('usaha.persekot-po.showRealisasi', $data);
      }else{
        return view('usaha.persekot-po.showPembayaran', $data);
      }

  }

  public function prosesNextStep(Request $request)
  {

    DB::beginTransaction();
    try
    {
        $data = PersekotPO::findOrFail($request->id);

        if($data->status == 2){
          //Proses Realisasi
          $jumlahRealisasi = replaceRp($request->jumlah);
          $infoLog = "Perubahan Persekot dengan Status Realisasi dengan Nilai Realisasi ".$request->jumlah." dari nilai sebelumnya ".toRp($data->jumlah);
          
          $data->status = 4;
          $data->jumlah = $jumlahRealisasi;
          $data->save();

          $log = new PersekotPOLog();
          $log->persekot_id = $request->id;
          $log->catatan = $request->status_catatan;
          $log->log_text = $infoLog;

          if($request->file('dokumen') != null){
              $image = $request->file('dokumen');
              $nameImage = time().'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('/persekotpo');
              $image->move($destinationPath, $nameImage);
              $log->file = $nameImage;
          }
          $log->created_by = \Auth::user()->id;
          $log->save();

          DB::commit();
          notify()->flash('Success!', 'success', [
              'text' => 'Perubahan Persekot Status Realisasi',
          ]);
        }else{
          //Proses Pembayaran Persekot dari PJB

          $data->status = 5;
          $data->metode_pembayaran = $request->metode_pembayaran;
          $data->save();

          $log = new PersekotPOLog();
          $log->persekot_id = $request->id;
          $log->catatan = $request->status_catatan;
          $log->log_text = "Perubahan Persekot dengan Status Lunas dari PJB";
          if($request->file('dokumen') != null){
              $image = $request->file('dokumen');
              $nameImage = time().'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('/persekotpo');
              $image->move($destinationPath, $nameImage);
              $log->file = $nameImage;
          }
          $log->created_by = \Auth::user()->id;
          $log->save();

          DB::commit();
          notify()->flash('Success!', 'success', [
              'text' => 'Perubahan Pembayaran Status Lunas',
          ]);
        }

    }
    catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
    {
        DB::rollback();
        notify()->flash('Error!', 'error', [
            'text' => $e->getMessage(),
        ]);
    }
    return redirect('usaha/persekotpo/'.$request->id);
  }

  public function pembayaran(Request $request)
  {
      if($request->ajax())
      {
          $query = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekot_po p
                          inner join spvs s on s.spv_id = p.spv_id
                          inner join banks b on b.bank_id = p.bank_id
                          inner join users u on u.id = p.created_by
                          where p.status= 4");

          $datatables = Datatables::of($query)
              ->addColumn('action', function ($value) {

                  $html =
                  '<a href="'.url('usaha/persekotpo/proses/'.$value->persekot_id.'').'" class="btn btn-xs blue " >Pembayaran</a>'
                      .'&nbsp;';

                  return $html;
              })
              ->editColumn('jumlah', function($value){
                return toRp($value->jumlah);
              })
              ->editColumn('margin_val', function($value){
                return toRp($value->margin_val);
              })
              ->rawColumns(['action']);
          return $datatables->make(true);
      }
      return view('usaha.persekot-po.pembayaran');
  }

    public function listPnpo(Request $request)
    {
        if($request->ajax())
        {
            $query = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekot_po p
                            inner join spvs s on s.spv_id = p.spv_id
                            inner join banks b on b.bank_id = p.bank_id
                            inner join users u on u.id = p.created_by
                            where p.status = 3");

            $datatables = Datatables::of($query)
                ->addColumn('action', function ($value) {
                      $btn = "";
                      if($value->status == 2){
                        $btn = "Realisasi";
                      }elseif ($value->status == 3) {
                        $btn = "PNPO";
                      }
                    $html =
                    '<a href="'.url('usaha/persekotpo/'.$value->persekot_id.'').'" class="btn btn-xs blue " >Detail</a>'.
                        '<a href="'.url('usaha/persekotpo/proses/'.$value->persekot_id.'').'" class="btn btn-xs purple-sharp " >Proses '.$btn.'</a>'.
                        '&nbsp;';

                    return $html;
                })
                ->editColumn('status', function($value){
                  if($value->status == 2){
                    return "Realisasi";
                  }elseif ($value->status == 3) {
                    return "PNPO";
                  }else {
                    return "";
                  }

                })
                ->editColumn('jumlah', function($value){
                  return toRp($value->jumlah);
                })
                ->rawColumns(['action']);
            return $datatables->make(true);
        }
        return view('usaha.persekot-po.listPnpo');
    }


    public function index(Request $request)
    {
        if($request->ajax())
        {
            $query = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekot_po p
                            inner join spvs s on s.spv_id = p.spv_id
                            inner join banks b on b.bank_id = p.bank_id
                            inner join users u on u.id = p.created_by
                            where p.status = 2 ");

            $datatables = Datatables::of($query)
                ->addColumn('action', function ($value) {
                      $btn = "";
                      if($value->status == 2){
                        $btn = "Realisasi";
                      }elseif ($value->status == 3) {
                        $btn = "PNPO";
                      }
                    $html =
                    '<a href="'.url('usaha/persekotpo/'.$value->persekot_id.'').'" class="btn btn-xs blue " >Detail</a>'.
                        '<a href="'.url('usaha/persekotpo/proses/'.$value->persekot_id.'').'" class="btn btn-xs purple-sharp " >Proses '.$btn.'</a>'.
                        '&nbsp;';

                    return $html;
                })
                ->editColumn('status', function($value){
                  if($value->status == 2){
                    return "Realisasi";
                  }elseif ($value->status == 3) {
                    return "PNPO";
                  }else {
                    return "";
                  }

                })
                ->editColumn('jumlah', function($value){
                  return toRp($value->jumlah);
                })
                ->rawColumns(['action']);
            return $datatables->make(true);
        }
        return view('usaha.persekot-po.index');
    }

    public function create(Request $request)
    {
        $kd = $request->kd;
        $data['data_edit'] = WorkOrder::findOrFail($kd);
        $lokasi = Lokasi::findOrFail($data['data_edit']->lokasi_id);
        $client = Client::findOrFail($data['data_edit']->client_id);

        $data['lokasi'] = $lokasi;
        $data['client'] = $client;

        $data['user_id'] = User::where('is_active','<>',0)->where('id','<>',\Auth::user()->id)->pluck('name', 'id');
        $data['bank_id'] = Bank::where('bank_id','<>',0)->pluck('nama_bank', 'bank_id');
        $data['spv_id'] = Spv::where('deleted_at','<>',null)->select(DB::raw('CONCAT(jabatan_spv, " - ", nama_spv) AS nama_spv'),'spv_id')->pluck('nama_spv','spv_id');
        $data['setting'] = SettingUsaha::findOrFail(1);
        return view('usaha.persekot-po.create',$data);
    }

    public function add(Request $request)
    {
        $id = $request->get('kd');
        $data['data_edit'] = PersekotPO::findOrFail($id);
        $data['bank_id'] = Bank::where('bank_id','<>',0)->pluck('nama_bank', 'bank_id');
        $data['spv_id'] = Spv::where('bank_id','<>',0)->select(DB::raw('CONCAT(jabatan_spv, " - ", nama_spv) AS nama_spv'),'spv_id')->pluck('nama_spv','spv_id');

        return view('usaha.persekot-po.add',$data);
    }

    public function store(Request $request)
    {
        if($request->jenis == 1){
          $this->validate($request, [
            'jumlah' => 'required',
            'jatuh_tempo' => 'required|integer',
            'no_rekening' => 'required',
            'keterangan' => 'required',
          ]);
        }else{
          $this->validate($request, [
            'jumlah' => 'required',
            'jatuh_tempo' => 'required|integer',
            'keterangan' => 'required',
          ]);
        }

        DB::beginTransaction();
        try
        {
            $cekDataKode = PersekotPO::count();
            if($cekDataKode == 0){
              $newCode = "0001";
            }else{
              $maxCode = PersekotPO::max(DB::raw('LEFT(no_persekot,4)'));
              $maxCode = (int)$maxCode;
              $maxCode++;
              $newCode = sprintf("%04s", $maxCode);
            }
            $nowDate = date('Y-m-d');
            $kodePersekot = $newCode.'/'."PSKT-PO/".getInfoBulanSingkat($nowDate)."/".date('Y');

            $data = new PersekotPO();
            $data->wo_id = $request->wo_id;
            $data->spv_id = $request->spv_id;
            $data->metode_penerimaan = $request->jenis;
            $data->petugas_id = $request->petugas_id;
            $data->jenis_pekerjaan = $request->jenis_pekerjaan;
            $data->no_persekot = $kodePersekot;
            $data->jumlah = replaceRp($request->jumlah);
            $data->jumlah_asli = replaceRp($request->jumlah);
            $data->jatuh_tempo = $request->jatuh_tempo;
            $data->tujuan_transfer = $request->tujuan_transfer;
            $data->bank_id = $request->bank_id;
            $data->no_rekening = $request->no_rekening;
            $data->keterangan = $request->keterangan;
            $data->status = 1;
            $data->tgl_pengajuan = date('Y-m-d');
            $data->tgl_jatuhtempo = date('Y-m-d',strtotime($data->tgl_pengajuan . "+".$request->jatuh_tempo." days"));
            $data->created_by = \Auth::user()->id;
            $data->save();

            $log = new PersekotPOLog();
            $log->persekot_id = $data->persekot_id;
            $log->log_text = "Pembuatan Persekot";
            $log->created_by = \Auth::user()->id;
            $log->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Persekot berhasil ditambah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/persekotpo/verifikasi');
    }

    public function edit($id)
    {
        $data['data_edit'] = PersekotPO::findOrFail($id);
        $data['user_id'] = User::where('is_active','<>',0)->where('id','<>',\Auth::user()->id)->pluck('name', 'id');
        $data['bank_id'] = Bank::where('bank_id','<>',0)->pluck('nama_bank', 'bank_id');
        $data['spv_id'] = Spv::where('deleted_at','<>',null)->select(DB::raw('CONCAT(jabatan_spv, " - ", nama_spv) AS nama_spv'),'spv_id')->pluck('nama_spv','spv_id');
        return view('usaha.persekot-po.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'jumlah' => 'required',

        ]);

        DB::beginTransaction();
        try
        {
            $data = PersekotPO::findOrFail($id);
            $data->petugas_id = $request->petugas_id;
            $data->jenis_pekerjaan = $request->jenis_pekerjaan;
            $data->spv_id = $request->spv_id;
            $data->jumlah = replaceRp($request->jumlah);
            $data->tujuan_transfer = $request->tujuan_transfer;
            $data->bank_id = $request->bank_id;
            $data->no_rekening = $request->no_rekening;
            $data->keterangan = $request->keterangan;
            $data->save();

            $log = new PersekotPOLog();
            $log->persekot_id = $id;
            $log->log_text = "Perubahan Persekot";
            $log->created_by = \Auth::user()->id;
            $log->save();


            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Persekot berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/persekotpo/verifikasi');
    }

    public function show($id)
    {
      $data['data_edit'] = PersekotPO::findOrFail($id);
      $data['bank_id'] = Bank::findOrFail($data['data_edit']->bank_id);
      $data['spv_id'] = Spv::findOrFail($data['data_edit']->spv_id);
      $data['petugas_id'] = User::findOrFail($data['data_edit']->petugas_id);

      $queryLog = DB::select("SELECT	u.`name` as operator, p.* from persekot_po_logs p
                      inner join users u on u.id = p.created_by
                      where p.persekot_id = $id order by p.created_at desc");
      $data['log'] = $queryLog;
      return view('usaha.persekot-po.show', $data);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = PersekotPO::findOrFail($id);
            $data->deleted_at = date('Y-m-d H:m:s');
            $data->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Persekot berhasil dihapus',
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
        return redirect('usaha/persekotpo');
    }



}
