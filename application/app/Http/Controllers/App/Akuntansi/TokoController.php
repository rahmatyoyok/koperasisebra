<?php

namespace App\Http\Controllers\App\Akuntansi;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;


use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;


use App\Http\Models\Item;
use App\Http\Models\KartuStok;
use App\Http\Models\ItemQuantity;
use App\Http\Models\Akuntansi\Coa;
use App\Http\Models\Akuntansi\GeneralLedger;

use App\Http\Models\Pengaturan\User;
use App\Http\Models\Pengaturan\Menu;

use App\Http\Models\SimpanPinjam\RandomSeq;
use Illuminate\Support\Arr;

use DB, Form, Response, Auth;

class TokoController extends AppController
{
    private $bulanIndo   = array('Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember');
    private $arrDivisi  = array('SP' => 'Simpan Pinjam', 'UM'=>'Usaha Umum', 'TK' => 'Toko', '' => 'All');
    
    // public function __construct()
    // {
    //     $this->middleware(function ($request, $next) {
            
    //         // jika berhasil login buat variabel user
    //         $this->user = auth()->user();

    //         // generate menu
    //         $this->menus = Menu::getMenus($this->user->level_id);

    //         // ambil judul dari path yang dituju
    //         if(!$request->ajax())
    //             $this->title = Menu::getTitle($request->path());

    //         view()->share([
    //             'user' => $this->user,
    //             'menus' => $this->menus,
    //             'title' => $this->title
    //         ]);

    //         // // mengecek apakah permission user yang login sesuai
    //         // if(!Menu::isValid($request->path(), $this->user->level_id))
    //         // {
    //         //     notify()->flash('Error!', 'error', [
    //         //         'text' => 'Anda tidak memiliki hak akses ke link tersebut',
    //         //     ]);
    //         //     return redirect(url()->previous());
    //         // }

    //         //lanjutkan ke request selanjutnya
    //         return $next($request);
    //     });
    // }


    public function index(Request $req){

        $dates = ($req->pmperiode) ? substr($req->pmperiode,-4).'-'.substr($req->pmperiode,0,2).'-01' : date('Y-m-d');
        
        $d=strtotime($dates);

        $parssing['currentMonth']  = getMonths()[(int)date('m', $d)].' '.date('Y', $d);
        $parssing['currentMonthIndo'] = date('mY', $d);
        return view('Akuntansi.Toko.Index',$parssing);
    }

    public function get(Request $request)
    {
        
        //Total Pembelian Tunai
        $query = "select sum(receiving_detail.item_unit_price*receiving_detail.quantity_purchased) as total from ospos_receivings receiving
        inner join ospos_receivings_items receiving_detail on receiving.receiving_id = receiving_detail.receiving_id
        where MONTH(receiving_time) = '04' and YEAR(receiving_time) = '2020' and receiving.payment_type = 'Tunai'";
        $query = DB::select($query);
        $data['pembelianTunai']= $query[0]->total;

        //Total Pembelian Kredit/Hutang
        $query = "select sum(receiving_detail.item_unit_price*receiving_detail.quantity_purchased) as total from ospos_receivings receiving
        inner join ospos_receivings_items receiving_detail on receiving.receiving_id = receiving_detail.receiving_id
        where MONTH(receiving_time) = '04' and YEAR(receiving_time) = '2020' and receiving.payment_type = 'Kredit'";
        $query = DB::select($query);
        $data['pembelianHutang']= $query[0]->total;

        //Total Penjualan Tunai
        $query = "select sum(payment.payment_amount-payment.cash_refund) as total from ospos_sales sale
        inner join ospos_sales_payments payment on sale.sale_id = payment.sale_id
        where MONTH(sale_time) = '04' and YEAR(sale_time) = '2020' and payment.payment_type = 'Tunai'";

        //Total Penjualan Hutang
        $query = "select sum(payment.payment_amount-payment.cash_refund) as total from ospos_sales sale
        inner join ospos_sales_payments payment on sale.sale_id = payment.sale_id
        where MONTH(sale_time) = '04' and YEAR(sale_time) = '2020' and payment.payment_type = 'Hutang'";

        
        return view('Akuntansi.Toko.Detail',$data);
    }

    public function hutangPembelian(Request $request)
    {
        
        $query = "select receiving.*,company_name,(receiving_detail.item_unit_price*receiving_detail.quantity_purchased) as total from ospos_receivings receiving
        inner join ospos_receivings_items receiving_detail on receiving.receiving_id = receiving_detail.receiving_id
        left join ospos_suppliers supplier on supplier.person_id = receiving.supplier_id
        where receiving.payment_type != 'Tunai'
        GROUP BY receiving.receiving_id ,receiving.supplier_id";
        $data['data'] = DB::select($query);

        return view('Akuntansi.Toko.HutangPembelian',$data);
    }

    public function hutangPenjualan(Request $request)
    {
        $query = "select sale.*,people.first_name,people.last_name, (payment.payment_amount-payment.cash_refund) as total from ospos_sales sale
        inner join ospos_sales_payments payment on sale.sale_id = payment.sale_id
        left join ospos_people people on sale.customer_id = people.person_id
        where payment.payment_type != 'Tunai' ";
        $data['data'] = DB::select($query);

        return view('Akuntansi.Toko.HutangPenjualan',$data);
    }

    public function kartuPiutang(Request $req)
    {
        if ($req->ajax()) {
            $status = $req->get('status'); 
            $periode = $req->get('periode'); 

            $month = mb_substr($periode, 0, 2);
            $year = mb_substr($periode, 2, 4);
            

            if($month == '01'){
                $beforeYear = (int)$year - 1;
                $beforeYear = str_pad($beforeYear, 2, '0', STR_PAD_LEFT);
                $beforeMonth = '12';
            }else{
                $beforeYear = $year;
                $beforeMonth = (int)$month - 1;
                $beforeMonth = str_pad($beforeMonth, 2, '0', STR_PAD_LEFT);
            }

            $query = "select sum(p.payment_amount) payment_amount, po.first_name,po.niak,po.person_id from ospos_sales_payments p
            inner join ospos_sales s on  s.sale_id = p.sale_id 
            inner join ospos_people po on po.person_id = s.customer_id
            where (p.payment_time BETWEEN '$beforeYear-$beforeMonth-15 00:00:00' AND '$year-$month-16 23:00:00')
            and p.payment_type = 'Kredit' and p.payment_status = $status
            GROUP BY po.first_name,po.niak,po.person_id ";

            $data['data'] = DB::select($query);
            $data['month'] = $month;
            $data['year'] = $year;
            $data['status'] = $status;
            return view('Akuntansi.Toko.kartuPiutang-detail',$data);
        }

        $dates = ($req->pmperiode) ? substr($req->pmperiode,-4).'-'.substr($req->pmperiode,0,2).'-01' : date('Y-m-d');
        
        $d=strtotime($dates);

        $parssing['currentMonth']  = getMonths()[(int)date('m', $d)].' '.date('Y', $d);
        $parssing['currentMonthIndo'] = date('mY', $d);

        return view('Akuntansi.Toko.kartuPiutang',$parssing);
    }

    public function kartuStokDetail(Request $request)
    {
        $status = $request->get('status');
        $month = $request->get('month');
        $year = $request->get('year');
        $person = $request->get('person');

        if($month == '01'){
            $beforeYear = (int)$year - 1;
            $beforeYear = str_pad($beforeYear, 2, '0', STR_PAD_LEFT);
            $beforeMonth = '12';
        }else{
            $beforeYear = $year;
            $beforeMonth = (int)$month - 1;
            $beforeMonth = str_pad($beforeMonth, 2, '0', STR_PAD_LEFT);
        }

        $query = "select p.payment_amount,s.sale_time, po.first_name,po.niak,po.person_id from ospos_sales_payments p
        inner join ospos_sales s on  s.sale_id = p.sale_id 
        inner join ospos_people po on po.person_id = s.customer_id
        where (p.payment_time BETWEEN '$beforeYear-$beforeMonth-15 00:00:00' AND '$year-$month-16 23:00:00')
        and p.payment_type = 'Kredit' and p.payment_status = $status and po.person_id = $person
        ";

        $data['data'] = DB::select($query);

        return view('Akuntansi.Toko.kartuPiutangDetail',$data);
    }

    public function kartuStok(Request $req)
    {
        if ($req->ajax()) {
            $item = $req->get('item');
            $tahun = $req->get('tahun');
            $triwulan = $req->get('triwulan');
            
            if($triwulan == '1'){
                $listTriwulan = "('01','02','03')";
            }elseif($triwulan == '2'){
                $listTriwulan = "('04','05','06')";
            }elseif($triwulan == '3'){
                $listTriwulan = "('07','08','09')";
            }else{
                $listTriwulan = "('10','11','12')";
            }
            
            if ($triwulan == '1') {
                $beforeYear = $tahun - 1;
                $beforeTriwulan = 4;
            }else{
                $beforeYear = $tahun;
                $beforeTriwulan = $triwulan - 1;
            }
            //Cek data
            $cek = KartuStok::where('year', $beforeYear)->where('triwulan', $beforeTriwulan)->count();
            if($cek != 0){
                $kartuStok = KartuStok::where('year', $beforeYear)->where('triwulan', $beforeTriwulan)->first();
                $data['stok'] = $kartuStok->stok;
                $data['harga'] = $kartuStok->harga;
                $data['jumlah'] = $kartuStok->jumlah;
            }else{
                $dataItem = Item::where('item_id', $item)->first();
                $dataStok = ItemQuantity::where('item_id', $item)->first();
                $data['stok'] = (int)$dataStok->quantity;
                $data['harga'] = $dataItem->unit_price;
                $data['jumlah'] = (int)$dataStok->quantity*$dataItem->unit_price;
            }

            $query = "(select 'Pembelian' as type,i.receiving_quantity as qty, i.item_unit_price as price,  '0' as status,r.receiving_time as time from ospos_receivings r 
            inner join ospos_receivings_items i on i.receiving_id = r.receiving_id
            where YEAR(r.receiving_time) = '$tahun' and MONTH(r.receiving_time) in $listTriwulan and i.item_id = $item ORDER BY r.receiving_time
            )
            UNION 
            (
            select 'Penjualan' as type, i.quantity_purchased as qty, i.item_unit_price as price, s.sale_status as status,s.sale_time as time from ospos_sales s
            inner join ospos_sales_items i on i.sale_id = s.sale_id
            where YEAR(s.sale_time) = '$tahun' and MONTH(s.sale_time) in $listTriwulan and i.item_id = $item ORDER BY s.sale_time
            )
            ORDER BY time";
            $data['data'] = DB::select($query);
            return view('Akuntansi.Toko.kartuStok-detail',$data);
        }

        $dates = ($req->pmperiode) ? substr($req->pmperiode,-4).'-'.substr($req->pmperiode,0,2).'-01' : date('Y-m-d');
        
        $d=strtotime($dates);

        $parssing['currentMonth']  = getMonths()[(int)date('m', $d)].' '.date('Y', $d);
        $parssing['currentMonthIndo'] = date('mY', $d);
        $parssing['item'] = Item::where('deleted',0)->pluck('name', 'item_id');
        
        return view('Akuntansi.Toko.kartuStok',$parssing);
    }

    public function simpanKartuStok(Request $request)
    {
        DB::beginTransaction();
        try
        {
            $stok = $request->get('stok');
            $harga = $request->get('harga');
            $jumlah = $request->get('jumlah');
            $item_id = $request->get('item');
            $year = $request->get('tahun');
            $triwulan = $request->get('triwulan');

            $cek = KartuStok::where('item_id',$item_id)->where('year',$year)->where('triwulan',$triwulan)->count();
            if($cek == 0){
                $data = new KartuStok();
                $data->stok = $stok;
                $data->harga = $harga;
                $data->jumlah = $jumlah;
                $data->item_id = $item_id;
                $data->year = $year;
                $data->triwulan = $triwulan;
                $data->created_by = \Auth::user()->id;
                $data->save();
            }else{
                $cek = KartuStok::where('item_id',$item_id)->where('year',$year)->where('triwulan',$triwulan)->first();
                $data = KartuStok::findOrFail($cek->id);
                $data->stok = $stok;
                $data->harga = $harga;
                $data->jumlah = $jumlah;
                $data->item_id = $item_id;
                $data->year = $year;
                $data->triwulan = $triwulan;
                $data->save();
            }
            

            DB::commit();
            
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            
        }
    }

    public function jurnalPeriodik(Request $req)
    {
        $dates = ($req->pmperiode) ? substr($req->pmperiode,-4).'-'.substr($req->pmperiode,0,2).'-01' : date('Y-m-d');
        
        $d=strtotime($dates);

        $parssing['currentMonth']  = getMonths()[(int)date('m', $d)].' '.date('Y', $d);
        $parssing['currentMonthIndo'] = date('mY', $d);

        return view('Akuntansi.Toko.jurnalPeriodik',$parssing);
    }

}
