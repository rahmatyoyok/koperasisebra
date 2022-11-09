<?php

namespace App\Http\Controllers\App\SimpanPinjam;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;

use App\Http\Models\SimpanPinjam\Anggota;
use App\Http\Models\SimpanPinjam\RandomSeq;
use App\Http\Models\SimpanPinjam\KonfigurasiPinjaman;
use App\Http\Models\SimpanPinjam\LoanTypes;
use App\Http\Models\SimpanPinjam\Loans;
use App\Http\Models\SimpanPinjam\LoanApprovals;
use App\Http\Models\SimpanPinjam\LoanInstallments;
use App\Http\Models\Pengaturan\Menu;
use App\Http\Models\Akuntansi\CompanyBankAccount;
use App\Http\Models\Akuntansi\TriggerSimpanPinjam;
use App\Http\Models\Bank;

use Illuminate\Support\Arr;
use DB, Form, Response, Auth;


class PiutangTokoController extends AppController
{
    private $alphabet = array('A','B','C','D','F','G','H','I','J');

    

    public function index(Request $request){
        
        $title    = 'Daftar Piutang Toko';
        $parssing = array('title' =>  ucwords($title));

        if($request->ajax())
        {
            $query = "select 
                    cs.niak,
                    concat(cs.first_name, ' ', cs.last_name)nama,
                    cs.born_place, 
                    cs.born_date,
                    cs.id_card_number, 
                    null unit_kerja, 
                    null status_anggota, 
                    sl.customer_id, 
                    sum(pm.payment_amount) total_piutang
                from ospos_sales sl
                inner join ospos_sales_payments pm on pm.sale_id = sl.sale_id and pm.payment_id in (select max(pms.payment_id) from ospos_sales_payments pms group by sale_id)
                inner join ospos_people cs on cs.person_id = sl.customer_id
                where 
                    sl.sale_status = 0
                    and customer_id is not null
                    and payment_type = 'Kredit'
                group by sl.customer_id";

            $model = DB::connection('mysql_toko')->select($query);
            $mdata = collect($model);
            $datatables = Datatables::of($mdata)
            ->addColumn('total_piutang', function($val){
                return number_format($val->total_piutang, 0,",",".");
            })
            ->addColumn('piutang_terbayar', function($val){
                return number_format(0, 0,",",".");
            })
            ->addColumn('sisa_piutang', function($val){
                return number_format(0, 0,",",".");
            })
            ->addColumn('action', function ($value) {
                $person_id = Crypt::encrypt($value->person_id);

                $html = '<a href="'.url('simpanpinjam/elektronik/'.$person_id).'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.
                    '&nbsp;';
                return $html;
            })
            ->order(function ($querys) {
                if (request()->has('tahun')) {
                
                }
            });

            return $datatables->make(true);
        }

        // $users = DB::connection('mysql_toko')->select('SELECT * FROM ospos_people');
        // dd($users);
        return view('SimpanPinjam.piutangtoko.index')->with($parssing);
    }
}