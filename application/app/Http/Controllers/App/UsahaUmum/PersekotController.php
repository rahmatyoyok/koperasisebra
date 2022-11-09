<?php

namespace App\Http\Controllers\App\UsahaUmum;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\Persekot;
use App\Http\Models\PersekotLog;
use App\Http\Models\Bank;
use App\Http\Models\Spv;
use App\Http\Models\SettingUsaha;
use App\Http\Models\User;
use App\Http\Models\WorkOrder;

use App\Http\Models\PersekotPO;
use App\Http\Models\PersekotPOLog;

use App\Http\Models\Akuntansi\TriggerPersekots;

use DB, Form, Auth;

class PersekotController extends AppController
{

  public function pindahPersekotPo($id)
  {
    $data['data_edit'] = Persekot::findOrFail($id);
    $data['bank_id'] = Bank::findOrFail($data['data_edit']->bank_id);
    $data['spv_id'] = Spv::findOrFail($data['data_edit']->spv_id);
    $data['petugas_id'] = User::findOrFail($data['data_edit']->petugas_id);
    $data['wo_id'] = WorkOrder::where('jenis_wo',2)->select(DB::raw('concat(kode_wo, " - ",nama_pekerjaan) as kode_wo, wo_id'))->pluck('kode_wo', 'wo_id');
    return view('usaha.persekot.pindahPersekotPO', $data);
  }

  public function updatePersekotPO(Request $request)
  {
    $this->validate($request, [
      'workorder' => 'required',
    ]);

    DB::beginTransaction();
    try
    {
        $persekot = Persekot::findOrFail($request->persekot_id); 
        $persekot->deleted_at = date('Y-m-d H:m:s');
        $persekot->save();

        $data = new PersekotPO();
        $data->wo_id = $request->workorder;
        $data->spv_id = $persekot->spv_id;
        $data->metode_penerimaan = $persekot->jenis;
        $data->petugas_id = $persekot->petugas_id;
        $data->jenis_pekerjaan = $persekot->jenis_pekerjaan;
        $data->no_persekot = $persekot->no_persekot;
        $data->jumlah = replaceRp($persekot->jumlah);
        $data->jumlah_asli = replaceRp($persekot->jumlah);
        $data->jatuh_tempo = $persekot->jatuh_tempo;
        $data->tujuan_transfer = $persekot->tujuan_transfer;
        $data->bank_id = $persekot->bank_id;
        $data->no_rekening = $persekot->no_rekening;
        $data->keterangan = $persekot->keterangan;
        $data->dokumen = $persekot->dokumen;
        $data->status = 4;
        $data->tgl_pengajuan = $persekot->tgl_pengajuan;
        $data->tgl_jatuhtempo = $persekot->tgl_jatuhtempo;
        $data->created_by = \Auth::user()->id;
        $data->save();

        $log = new PersekotPOLog();
        $log->persekot_id = $data->persekot_id;
        $log->log_text = "Pembuatan Persekot";
        $log->created_by = \Auth::user()->id;
        $log->save();

        DB::commit();
        notify()->flash('Success!', 'success', [
            'text' => 'Persekot PO berhasil ditambah',
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

  public function report(Request $request)
  {

      if($request->ajax())
      {

        $status = $request->get('status');
        $start = $request->get('start');
        $end = $request->get('end');

        if($status == 0){
            $where = " WHERE p.status = $status";
        }else{
            $where = " WHERE p.status = $status";
        }

        // if($start != null){
        //   $where .= " and p.tgl_pengajuan >= '$start'";
        //   $where .= " and p.tgl_pengajuan <= '$end'";
        // }

        $query = DB::select("select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekots p
                        inner join spvs s on s.spv_id = p.spv_id
                        left join banks b on b.bank_id = p.bank_id
                        inner join users u on u.id = p.created_by
                        $where order by p.created_at desc");

        $data['data']=$query;
        return view('usaha.persekot.report-detail',$data);
      }
      $data['bank_id'] = Bank::pluck('nama_bank', 'bank_id');
      $data['spv_id'] = Spv::select(DB::raw('CONCAT(jabatan_spv, " - ", nama_spv) AS nama_spv'),'spv_id')->pluck('nama_spv','spv_id');

      return view('usaha.persekot.report',$data);
  }

  public function listVerifikasi(Request $request)
  {
    $query = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekots p
                    inner join spvs s on s.spv_id = p.spv_id
                    left join banks b on b.bank_id = p.bank_id
                    inner join users u on u.id = p.created_by
                    where p.status = 1 and p.deleted_at is null order by p.created_at desc");
    $data['data']=$query;
    return view('usaha.persekot.listVerifikasi',$data);
  }

  public function showVerifikasi($id)
  {
      $data['data_edit'] = Persekot::findOrFail($id);
      $data['bank_id'] = Bank::findOrFail($data['data_edit']->bank_id);
      $data['spv_id'] = Spv::findOrFail($data['data_edit']->spv_id);
      $data['petugas_id'] = User::findOrFail($data['data_edit']->petugas_id);
      return view('usaha.persekot.showVerifikasi', $data);
  }

  public function prosesVerifikasi(Request $request)
  {
    $this->validate($request, [
      'status' => 'required',
    ]);

    DB::beginTransaction();
    try
    {
        $data = Persekot::findOrFail($request->id);
        $data->status = $request->status;
        $data->save();

        if($request->status == 2){
          $status = "Diterima";
        }else{
          $status = "Ditolak";
        }

        $log = new PersekotLog();
        $log->persekot_id = $request->id;
        $log->catatan = $request->status_catatan;
        $log->log_text = "Verifikasi Persekot dengan Status ".$status;
        $log->created_by = \Auth::user()->id;
        $log->save();

        TriggerPersekots::perubahanStatus($request->id, \Auth::user()->id); //CallJurnal

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
    return redirect('usaha/persekot/'.$request->id);
  }

  public function showProses($id)
  {
      $data['data_edit'] = Persekot::findOrFail($id);
      $data['bank_id'] = Bank::findOrFail($data['data_edit']->bank_id);
      $data['spv_id'] = Spv::findOrFail($data['data_edit']->spv_id);
      $data['petugas_id'] = User::findOrFail($data['data_edit']->petugas_id);
      $data['setting'] = SettingUsaha::findOrFail(1);

      if($data['data_edit']->status == 2){
          return view('usaha.persekot.showRealisasi', $data);
      }elseif($data['data_edit']->status == 3) {
          return view('usaha.persekot.showPNPO', $data);
      }else{
        return view('usaha.persekot.showPembayaran', $data);
      }

  }

  public function prosesNextStep(Request $request)
  {

    DB::beginTransaction();
    try
    {
        $data = Persekot::findOrFail($request->id);

        if($data->status == 2){
          //Proses Realisasi
          $jumlahRealisasi = replaceRp($request->jumlah);
          $infoLog = "Perubahan Persekot dengan Status Realisasi dengan Nilai Realisasi ".$request->jumlah." dari nilai sebelumnya ".toRp($data->jumlah);

          $data->status = 3;
          $data->jumlah = $jumlahRealisasi;
          $data->save();

          $log = new PersekotLog();
          $log->persekot_id = $request->id;
          $log->catatan = $request->status_catatan;
          $log->log_text = $infoLog;

          if($request->file('dokumen') != null){
              $image = $request->file('dokumen');
              $nameImage = time().'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('/persekot');
              $image->move($destinationPath, $nameImage);
              $log->file = $nameImage;
          }
          $log->created_by = \Auth::user()->id;
          $log->save();

          TriggerPersekots::perubahanStatus($request->id, \Auth::user()->id); //CallJurnal

          DB::commit();
          notify()->flash('Success!', 'success', [
              'text' => 'Perubahan Persekot Status Realisasi',
          ]);
        }else if($data->status == 3){
          //Proses PNPO
          $data->status = 4;
          $data->margin = $request->margin;
          $data->ppn = $request->ppn;
          $data->pph22 = $request->pph22;
          $data->pph23 = $request->pph23;
          $data->margin_val = $request->margin_val;
          $data->save();

          $log = new PersekotLog();
          $log->persekot_id = $request->id;
          $log->catatan = $request->status_catatan;
          $log->log_text = "Perubahan Persekot dengan Status PNPO";
          if($request->file('dokumen') != null){
              $image = $request->file('dokumen');
              $nameImage = time().'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('/persekot');
              $image->move($destinationPath, $nameImage);
              $log->file = $nameImage;
          }
          $log->created_by = \Auth::user()->id;
          $log->save();

          TriggerPersekots::perubahanStatus($request->id, \Auth::user()->id); //CallJurnal

          DB::commit();
          notify()->flash('Success!', 'success', [
              'text' => 'Perubahan Persekot Status PNPO',
          ]);
        }else{
          //Proses Pembayaran Persekot dari PJB
          $totalPembayaran = replaceRp($request->jumlah_dibayar)+replaceRp($request->ppn_masukan)+replaceRp($request->pph22)+replaceRp($request->pph23);
          
          if((int)$totalPembayaran != (int) $data->margin_val){
            
            notify()->flash('Error!', 'error', [
                'text' => 'Total Pembayaran Tidak Sama !',
            ]);
            return redirect('usaha/persekot/proses/'.$request->id);
          }
          
          
          $data->status = 5;
          $data->metode_pembayaran = $request->metode_pembayaran;

          $data->jumlah_dibayar = replaceRp($request->jumlah_dibayar);
          $data->ppn_masukan = replaceRp($request->ppn_masukan);
          $data->pph22 = replaceRp($request->pph22);
          $data->pph23 = replaceRp($request->pph23);

          $data->save();

          $log = new PersekotLog();
          $log->persekot_id = $request->id;
          $log->catatan = $request->status_catatan;
          $log->log_text = "Perubahan Persekot dengan Status Lunas dari PJB";
          if($request->file('dokumen') != null){
              $image = $request->file('dokumen');
              $nameImage = time().'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('/persekot');
              $image->move($destinationPath, $nameImage);
              $log->file = $nameImage;
          }
          $log->created_by = \Auth::user()->id;
          $log->save();

          TriggerPersekots::perubahanStatus($request->id, \Auth::user()->id); //CallJurnal

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
    return redirect('usaha/persekot/'.$request->id);
  }

  public function pembayaran(Request $request)
  {
      if($request->ajax())
      {
          $query = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekots p
                          inner join spvs s on s.spv_id = p.spv_id
                          inner join banks b on b.bank_id = p.bank_id
                          inner join users u on u.id = p.created_by
                          where p.status= 4");

          $datatables = Datatables::of($query)
              ->addColumn('action', function ($value) {

                  $html =
                  '<a href="'.url('usaha/persekot/'.$value->persekot_id.'').'" class="btn btn-xs blue " >Detail</a>'.
                  '<a href="'.url('usaha/persekot/proses/'.$value->persekot_id.'').'" class="btn btn-xs purple " >Pembayaran</a>'
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
      return view('usaha.persekot.pembayaran');
  }

    public function listPnpo(Request $request)
    {
        if($request->ajax())
        {
            $query = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekots p
                            inner join spvs s on s.spv_id = p.spv_id
                            inner join banks b on b.bank_id = p.bank_id
                            inner join users u on u.id = p.created_by
                            where p.status = 3 and p.deleted_at is null");

            $datatables = Datatables::of($query)
                ->addColumn('action', function ($value) {
                      $btn = "";
                      if($value->status == 2){
                        $btn = "Realisasi";
                      }elseif ($value->status == 3) {
                        $btn = "PNPO";
                      }
                    $html =
                    '<a href="'.url('usaha/persekot/'.$value->persekot_id.'').'" class="btn btn-xs blue " >Detail</a>'.
                        '<a href="'.url('usaha/persekot/proses/'.$value->persekot_id.'').'" class="btn btn-xs purple-sharp " >Proses '.$btn.'</a>'.
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
        return view('usaha.persekot.listPnpo');
    }


    public function index(Request $request)
    {
        if($request->ajax())
        {
            $query = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekots p
                            inner join spvs s on s.spv_id = p.spv_id
                            inner join banks b on b.bank_id = p.bank_id
                            inner join users u on u.id = p.created_by
                            where p.status = 2 and p.deleted_at = '00:00:00'  ");

            $datatables = Datatables::of($query)
                ->addColumn('action', function ($value) {
                      $btn = "";
                      if($value->status == 2){
                        $btn = "Realisasi";
                      }elseif ($value->status == 3) {
                        $btn = "PNPO";
                      }
                    $html =
                    '<a href="'.url('usaha/persekot/'.$value->persekot_id.'').'" class="btn btn-xs blue " >Detail</a>'.
                        '<a href="'.url('usaha/persekot/proses/'.$value->persekot_id.'').'" class="btn btn-xs purple-sharp " >Proses '.$btn.'</a>'.
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
        return view('usaha.persekot.index');
    }

    public function create()
    {
        // $data['user_id'] = User::where('is_active','<>',0)->where('id','<>',\Auth::user()->id)->pluck('name', 'id');
        $data['user_id'] = User::where('is_active','<>',0)->pluck('name', 'id');
        $data['bank_id'] = Bank::where('bank_id','<>',0)->pluck('nama_bank', 'bank_id');
        $data['spv_id'] = Spv::where('deleted_at','<>',null)->select(DB::raw('CONCAT(jabatan_spv, " - ", nama_spv) AS nama_spv'),'spv_id')->pluck('nama_spv','spv_id');
        $data['setting'] = SettingUsaha::findOrFail(1);
        return view('usaha.persekot.create',$data);
    }

    public function add(Request $request)
    {
        $id = $request->get('kd');
        $data['data_edit'] = Persekot::findOrFail($id);
        $data['bank_id'] = Bank::where('bank_id','<>',0)->pluck('nama_bank', 'bank_id');
        $data['spv_id'] = Spv::where('bank_id','<>',0)->select(DB::raw('CONCAT(jabatan_spv, " - ", nama_spv) AS nama_spv'),'spv_id')->pluck('nama_spv','spv_id');

        return view('usaha.persekot.add',$data);
    }

    public function store(Request $request)
    {
        if($request->jenis == 1){
          $this->validate($request, [
            'jumlah' => 'required',
            'jatuh_tempo' => 'required|integer',
            'no_rekening' => 'required',
            'keterangan' => 'required',
            // 'dokumen' => 'required',
          ]);
        }else{
          $this->validate($request, [
            'jumlah' => 'required',
            'jatuh_tempo' => 'required|integer',
            'keterangan' => 'required',
            // 'dokumen' => 'required',
          ]);
        }

        DB::beginTransaction();
        try
        {
            $cekDataKode = Persekot::count();
            if($cekDataKode == 0){
              $newCode = "0001";
            }else{
              $maxCode = Persekot::max(DB::raw('LEFT(no_persekot,4)'));
              $maxCode = (int)$maxCode;
              $maxCode++;
              $newCode = sprintf("%04s", $maxCode);
            }
            $nowDate = date('Y-m-d');
            $kodePersekot = $newCode."/"."PSKT/".getInfoBulanSingkat($nowDate)."/".date('Y');

            $data = new Persekot();
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
            
            if($request->file('dokumen') != null){
                $dokumen = $request->file('dokumen');
                $nameFile = time().'.'.$dokumen->getClientOriginalExtension();
                $destinationPath = public_path('/dokumen_persekot');
                $dokumen->move($destinationPath, $nameFile);
                $data->dokumen = $nameFile;
            }
            
            $data->save();

            $log = new PersekotLog();
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
        return redirect('usaha/persekot/verifikasi');
    }

    public function edit($id)
    {
        $data['data_edit'] = Persekot::findOrFail($id);
        // $data['user_id'] = User::where('is_active','<>',0)->where('id','<>',\Auth::user()->id)->pluck('name', 'id');
        $data['user_id'] = User::where('is_active','<>',0)->pluck('name', 'id');
        $data['bank_id'] = Bank::where('bank_id','<>',0)->pluck('nama_bank', 'bank_id');
        $data['spv_id'] = Spv::where('deleted_at','<>',null)->select(DB::raw('CONCAT(jabatan_spv, " - ", nama_spv) AS nama_spv'),'spv_id')->pluck('nama_spv','spv_id');
        return view('usaha.persekot.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
          'jumlah' => 'required',

        ]);

        DB::beginTransaction();
        try
        {
            $data = Persekot::findOrFail($id);
            $data->petugas_id = $request->petugas_id;
            $data->jenis_pekerjaan = $request->jenis_pekerjaan;
            $data->spv_id = $request->spv_id;
            $data->jumlah = replaceRp($request->jumlah);
            $data->tujuan_transfer = $request->tujuan_transfer;
            $data->bank_id = $request->bank_id;
            $data->no_rekening = $request->no_rekening;
            $data->keterangan = $request->keterangan;
            $data->save();

            $log = new PersekotLog();
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
        return redirect('usaha/persekot/verifikasi');
    }

    public function show($id)
    {
      $data['data_edit'] = Persekot::findOrFail($id);
      $data['bank_id'] = Bank::findOrFail($data['data_edit']->bank_id);
      $data['spv_id'] = Spv::findOrFail($data['data_edit']->spv_id);
      $data['petugas_id'] = User::findOrFail($data['data_edit']->petugas_id);

      $queryLog = DB::select("SELECT	u.`name` as operator, p.* from persekot_logs p
                      inner join users u on u.id = p.created_by
                      where p.persekot_id = $id order by p.created_at desc");
      $data['log'] = $queryLog;
      return view('usaha.persekot.show', $data);
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            $data = Persekot::findOrFail($id);
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
        return redirect('usaha/persekot');
    }



}
