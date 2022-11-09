<?php

namespace App\Http\Controllers\App\Akuntansi;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;


use App\Http\Models\Akuntansi\CompanyBankAccount;
use App\Http\Models\Akuntansi\Coa;
use App\Http\Models\SimpanPinjam\RandomSeq;
use Illuminate\Support\Arr;

use DB, Form, Response, Auth;

class RekeningController extends AppController
{
    private $alphabet = array('A','B','C','D','E','F','G','H','I','J','N','O','P','Q');

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $title      = 'Daftar Rekening Bank';
        $parssing = array('title' =>  ucwords($title));

        $parssing['ListCoa'] = Coa::getListCoa()->get();
        return view('Akuntansi.Rekening.index')->with($parssing);
    }
}
