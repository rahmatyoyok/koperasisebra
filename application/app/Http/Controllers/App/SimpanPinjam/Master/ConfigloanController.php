<?php

namespace App\Http\Controllers\App\SimpanPinjam\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;

use App\Http\Models\SimpanPinjam\LoanTypes;

use DB, Form, Response, Auth;

class ConfigloanController extends AppController
{
    
    public function indexloantype(Request $req){
        $title    = 'Daftar Anggota';
        $parssing = array('title' =>  ucwords($title));

        return view('SimpanPinjam.Master.indexloantype', $parssing);
    }


    public function list_loantype_json(Request $request){
        if($request->ajax())
        {
            $datas = LoanTypes::getListLoanType();
            $datatables = Datatables::of($datas)
            ->addColumn('loan_type_id', function ($val) {
                return Crypt::encrypt($val->loan_type_id);
            })
            ->addColumn('interest_rates', function ($val) {
                return (double)$val->interest_rates;
            })
            ->addColumn('interest_type', function ($val) {
                return sp_interest_type()[$val->interest_type - 1]['name'];
            })
            ->addColumn('action', function ($value) {
                $loan_type_id = Crypt::encrypt($value->loan_type_id);

                $html = '<a href="'.url('simpanpinjam/master/konfig/ubahjenispinjaman/'.$loan_type_id).'" class="btn btn-xs blue-sharp tooltips" title="Ubah Data"> <i class="glyphicon glyphicon-edit"></i> Ubah</a>';
                return $html;
            })
            ->rawColumns(['action','status']);
            return $datatables->make(true);
        }
    }

}