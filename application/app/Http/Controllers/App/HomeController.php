<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use DB, Form, Response;

use App\Http\Models\Persekot;
use App\Http\Models\PersekotPO;
use App\Http\Models\PurchaseOrder;
use App\Http\Models\WorkOrder;
use App\Http\Models\Purchase;
use App\Http\Models\PurchaseDetail;

use App\Http\Models\Akuntansi\TriggerPurchaseOrder;

use App\Http\Models\Akuntansi\TriggerWorkOrder;



use App\Http\Models\SimpanPinjam\Loans;
use App\Http\Models\SimpanPinjam\LoanApprovals;
use App\Http\Models\SimpanPinjam\InvestmentSavingApprovals;

class HomeController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home.index');
    }

    /**
     * Display an user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
    	$data['title'] = 'Profil';
        return view('home.profile', $data);
    }

    public function usaha()
    {

        
        $data['totalPersekot'] = Persekot::where('deleted_at','<>',null)->count();
        $data['totalPersekotPO'] = PersekotPO::where('deleted_at','<>',null)->count();
        $data['totalWorkOrder'] = WorkOrder::where('deleted_at','<>',null)->count();
        $data['totalPurchaseOrder'] = PurchaseOrder::where('deleted_at','<>',null)->count();
        $data['totalPurchase'] = Purchase::where('deleted_at','<>',null)->count();

        $datePersekot = date('Y-m-d',strtotime(date('Y-m-d') . "-3 days"));

        $jatuhTempoPersekot = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekots p
                            inner join spvs s on s.spv_id = p.spv_id
                            inner join banks b on b.bank_id = p.bank_id
                            inner join users u on u.id = p.created_by
                            where p.status = 2 and p.tgl_jatuhtempo >= '$datePersekot' order by p.tgl_jatuhtempo asc");
        $data['jatuhTempoPersekot'] = $jatuhTempoPersekot;
        return view('usaha.home', $data);
    }

    public function getInfoNotifUsaha(Request $request)
    {
        $datas = [];

        $queryVerifikasiPersekot = DB::select("	select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekots p
        inner join spvs s on s.spv_id = p.spv_id
        left join banks b on b.bank_id = p.bank_id
        inner join users u on u.id = p.created_by
        where p.status = 1 order by p.tgl_pengajuan asc");
        $datas['totalVerifikasiPersekot'] = count($queryVerifikasiPersekot);

        $queryVerifikasiPersekotPO = DB::select("select b.nama_bank,s.nip_spv,s.nama_spv,u.`name` as operator, s.jabatan_spv,p.* from persekot_po p
        inner join spvs s on s.spv_id = p.spv_id
        left join banks b on b.bank_id = p.bank_id
        inner join users u on u.id = p.created_by
        where p.status = 1 order by p.tgl_pengajuan asc");

        $datas['totalVerifikasiPersekotPO'] = count($queryVerifikasiPersekotPO);

        $datas['totalVerifikasiInvestasiIn'] = InvestmentSavingApprovals::getCountNotif();
        $datas['totalVerifikasiInvestasiOut'] = InvestmentSavingApprovals::getCountNotif(2);
        $datas['totalVerifikasiPinjamanSP'] = LoanApprovals::getCountNotif();
        $datas['totalVerifikasiPinjamanEl'] = LoanApprovals::getCountNotif(2);

        $cnSp = $datas['totalVerifikasiInvestasiIn'] + $datas['totalVerifikasiInvestasiOut'] + $datas['totalVerifikasiPinjamanSP'] + $datas['totalVerifikasiPinjamanEl'];

        $datas['total'] = count($queryVerifikasiPersekot)+count($queryVerifikasiPersekotPO) + $cnSp;

      return Response::json($datas);
    }

    public function getDownload(Request $request){

        if($request->get('type') == 'persekot'){
          $path_to_file = public_path()."/persekot"."/".$request->get('file');
          return \Response::download($path_to_file,'Persekot '.$request->get('name'));
        }

        if($request->get('type') == 'simpanpinjam'){
            $loc = "/".$request->get('loc');
            $path_to_file = public_path()."/simpanpinjam".$loc."/".$request->get('file');
            return \Response::download($path_to_file,$request->get('name'));
        }

    }


    public function lock()
    {
        if(auth()->check()){
            session(['is_locked' => true]);
            return view('auth.lock');
        }
        return redirect('login');
    }

    public function unlock(Request $request)
    {
        if(!auth()->check())
            return redirect('login');

        if(\Hash::check($request->password, auth()->user()->password)){
            request()->session()->forget('is_locked');
            return redirect('home');
        }

        return redirect()->back()
            ->withInput()
            ->withErrors([
                'username' => 'Username atau password salah.',
            ]);
    }


    public function simpanpinjam(){
        $data = [];
        $title    = 'Simpan Pinjam';
        $data = array('title' =>  ucwords($title));

        $data['anggota'] = DB::select("select op.member_status,count(*) total FROM ospos_people  op
                            where is_deleted = 0
                            GROUP BY op.member_status");
        $data['pinjaman'] = Loans::with('anggota')->with('anggota.customer')
                    ->select(['person_id', DB::raw('max(loan_date) as loan_date'),  DB::raw('ifnull((select sum(a.loan_total) from sp_loans a where a.loan_id = sp_loans.loan_id and a.status = 1 and a.transaction_type_id = 1),0) as total_pinjaman')])
                    ->where('transaction_type_id',1)
                    ->where('is_deleted','0')
                    ->groupBy('person_id')
                    ->orderBy('created_at','desc')
                    ->take('10')
                    // ->toSql();
                    ->get();
        // dd($data['pinjaman']);            
        
        
        
        return view('SimpanPinjam.home', $data);
    }

    public function akuntansi(){
        $data = [];
        $title    = 'Simpan Pinjam';
        $data = array('title' =>  ucwords($title));
        return view('akuntansi.home', $data);
    }

    public static function tester(){
        $arr['keterangan']  = "Pengiriman Barang WO";
        $arr['response']    = TriggerWorkOrder::pembayaranWo(3, 1);
        return response()->json($arr, 200);
        

    }
}
