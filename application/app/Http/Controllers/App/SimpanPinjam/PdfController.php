<?php

namespace App\Http\Controllers\App\SimpanPinjam;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\App\SimpanPinjam\Route;

use App\Http\Models\SimpanPinjam\Anggota;
use App\Http\Models\SimpanPinjam\KonfigurasiSimpanan;
use App\Http\Models\SimpanPinjam\InvestmentSavings;
use App\Http\Models\SimpanPinjam\InvestmentSavingApprovals;
use App\Http\Models\Bank;
use App\Http\Models\SimpanPinjam\RandomSeq;
use App\Http\Models\Pengaturan\Menu;

use DB, Form, Response, Auth;

class PdfController extends AppController
{
  public function investasi(Request $request)
  {
      $id = $request->get('kd');

      $data['data'] = InvestmentSavings::findOrFail($id);
      $data['anggota'] = Anggota::with('customer')->findOrFail($data['data']->person_id);

      $pdf = \PDF::loadView("SimpanPinjam.pdf.investasi-penarikan",$data);
      // $pdf = \PDF::loadView("SimpanPinjam.pdf.investasi-pembayaran",$data);
      return $pdf->stream("Investasi.pdf");

  }


}
