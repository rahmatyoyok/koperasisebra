<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\ApiController;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Crypt;


use App\Http\Models\SimpanPinjam\InvestmentSavings;
use App\Http\Models\SimpanPinjam\PeriodicSavings;
use DB , Auth;

class TableController extends ApiController
{

    public function getDataPeserta($id)
    {
        $query = PengadaanPeserta::select('*');
        $query->where('p_id',$id);

        return Datatables::of($query)
            ->editColumn('pp_harga_penawaran', function($query){
                return formatRpComma($query->pp_harga_penawaran);
            })
            ->editColumn('pp_tgl_ph', function($query){
                return formatDateView($query->pp_tgl_ph);
            })
            ->addColumn('action', function($query){
                $html = "<button type='button' class='btn btn-xs btn-danger delTrans' title='Delete'><i class='glyphicon glyphicon-trash'></i></button>";
                $html .="<input type='hidden' name='perusahaan[]' value='".$query->pp_perusahaan."'>";
                $html .="<input type='hidden' name='no_ph[]' value='".$query->pp_no_ph."'>";
                $html .="<input type='hidden' name='tgl_ph[]' value='".$query->pp_tgl_ph."'>";
                $html .="<input type='hidden' name='harga_penawaran[]' vaalue='".$query->pp_harga_penawaran."'>";
                $html .="<input type='hidden' name='waktu[]' value='".$query->pp_waktu."'>";
                $html .="<input type='hidden' name='aanwijzing[]' value='".$query->pp_aanwijzing."'>";
                $html .="<input type='hidden' name='sampul[]' value='".$query->pp_sampul."'>";
                $html .="<input type='hidden' name='adm[]' value='".$query->pp_adm."'>";
                $html .="<input type='hidden' name='teknik[]' value='".$query->pp_teknik."'>";
                $html .="<input type='hidden' name='kelulusan[]' value='".$query->pp_kelulusan."'>";
                $html .="<input type='hidden' name='ket[]' value='".$query->pp_ket."'>";
                return $html;
            })
            ->make(true);
    }

    public static function getSaldoPerKaryawan(Request $req)
    {
        $user_id = Auth::user()->id;
        $param = $req->get('q') ?:null;

        $return['saldo'] = null; 

        try {
            $decryptedid = Crypt::decrypt($param);
            $sl = InvestmentSavings::getSaldoKaryawan($decryptedid);

            $return['saldo'] = $sl->saldo;
            $return['status'] = 'success';
        } catch (Illuminate\Contracts\Encryption\DecryptException $e) {
            $return['status'] = 'gagal';
        }

        return response()->json($return);
        
    }

    public function checkButtonKalkulasi(Request $req){
        $return['postingstatus'] = false;
        if($req->ajax())
        {
            $chekposting = PeriodicSavings::checkKalkulasi($req->get('periode'));
            if($chekposting > 0)
                $return['postingstatus'] = true;
        }
        return response()->json($return, 200);
    }

}
