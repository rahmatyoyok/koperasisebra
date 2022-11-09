<?php

namespace App\Http\Controllers\App\SimpanPinjam;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use App\Http\Models\SimpanPinjam\Anggota;
use App\Http\Models\SimpanPinjam\KonfigurasiSimpanan;
use App\Http\Models\SimpanPinjam\PeriodicSavings;
use App\Http\Models\Akuntansi\TriggerSimpanPinjam;
use App\Http\Models\Akuntansi\CompanyBankAccount;
use App\Http\Models\Bank;

/*plugin phpexcel*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;

use App\Http\Models\SimpanPinjam\RandomSeq;

use DB, Form, Response, Auth;

class SimpananWajibController extends AppController
{
    public static function index()
    {
        $title    = 'Daftar Simpanan Wajib Anggota';
        $parssing = array('title' =>  ucwords($title));
        return view('SimpanPinjam.simpanan.wajib.index')->with($parssing);
    }

    public function show($id){
        $parssing['title'] = ucwords('Detail Data Simpanan Wajib Anggota');
        $decryptedId = Crypt::decrypt($id);
        $parssing['decryptedId'] = $decryptedId;
        $parssing['data'] = Anggota::with('customer')->findOrFail($decryptedId);
        $parssing['data_wajib'] = PeriodicSavings::where('person_id',$decryptedId)->get();

        return view('SimpanPinjam.simpanan.wajib.show', $parssing);
    }

    public static function create()
    {
        $user_id    = Auth::user()->id;
        $title      = 'Pengajuan Setoran (Tambah) Simpanan Wajib';
        $parssing   = array('title' =>  ucwords($title));
        $parssing['ref'] = '02'.date('Ym').sprintf( '%06d', RandomSeq::getSeq('seq_ref_simpananwajib'));
        $parssing['def_date'] = date('d-m-Y');

        $defSimwa = KonfigurasiSimpanan::where('status',1)->firstOrFail();
        $parssing['def_total'] = $defSimwa->simpanan_wajib;
        
        return view('SimpanPinjam.simpanan.wajib.formtambah')->with($parssing);
    }

    public function store(Request $req)
    {
        if($req->isMethod('post'))
        {
            $this->validate($req, [
                'person_id' => 'required',
                'tr_date' => 'required',
                'total' => 'required',
              ]);

            DB::beginTransaction();
            try{

                $user_id = Auth::user()->id;


                $countValid = PeriodicSavings::where('person_id', $req->person_id)->where('periode', date('mY',strtotime($req->tr_date)))->count();
                if((int)$countValid > 0){
                    PeriodicSavings::where('person_id', $req->person_id)->where('periode', date('mY',strtotime($req->tr_date)))->delete();
                }

                $model = new PeriodicSavings();
                
                $model->transaction_type = 1;
                $model->person_id = $req->person_id;
                $model->periode = date('mY',strtotime($req->tr_date));
                $model->tr_date = date('Y-m-d',strtotime($req->tr_date));
                $model->total = replaceRp($req->total);
                $model->payment_method  = (int)$req->payment_method;
                $model->status  = 0;
                $model->created_at        = date('Y-m-d H:m:i');
                $model->created_by        = $user_id;
                $model->save();

                notify()->flash('Success!', 'success', [
                    'text' => 'Simpanan Pokok Berhasil Tambahkan',
                ]);
                
                DB::commit();
                $person_id = Crypt::encrypt($model->person_id);
                return redirect('simpanpinjam/wajib/'.$person_id);

            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Simpanan Wajib Gagal Tambahkan',
                ]);
                
                die();
            }catch(\Exception $e){
                DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

        }
    }

    public function list_json(Request $request)
    {
        if($request->ajax())
        {
            $array = [];    
            $statement = '';
            if($request->has('periode')){
                $array['periode'] = $request->get('periode');
                $statement = 'status <> 1';
            }else{
                // $array['status'] = 0;
            }

            $datas = PeriodicSavings::getListSimpananWajib($array, $statement);
            $datatables = Datatables::of($datas)
            ->addColumn('niak', function($val){
                return $val->anggota->niak;
            })
            ->addColumn('first_name', function($val){
                return $val->anggota->first_name .' '.$val->anggota->last_name;
            })
            ->addColumn('company_name', function($val){
                return $val->anggota->customer->company_name;
            })
            ->addColumn('person_id', function ($val) {
                return Crypt::encrypt($val->person_id);
            })
            ->addColumn('born_date', function ($val) {
                $html = $val->anggota->born_date;
                if(!empty($val->anggota->born_date))
                {
                    $html = tglIndo($val->anggota->born_date);
                }

                return $html;
            })
            ->addColumn('status_anggota', function($val){
                $rtn = '';
                switch($val->anggota->status){
                    case 1: $rtn = 'Aktif'; break;
                    case 2: $rtn = 'Mengundurkan Diri'; break;
                }
                return $rtn;
            })
            ->addColumn('total', function($val){
                return toRp($val->total);
            })
            ->addColumn('status', function($val){
                $html = '<span class="label label-sm label-success"> Aktif </span>';
                switch($val->status){
                    case (1): $html = '<span class="label label-sm label-success"> Terbayar </span>'; break;
                    case (2): $html = '<span class="label label-sm bg-red-haze bg-font-red-haze"> Posting </span>'; break;
                    case (3): $html = '<span class="label label-sm label-warning"> Kalkulasi </span>'; break;
                }

                
                return $html;
            })
            ->addColumn('action', function ($value) {
                $person_id = Crypt::encrypt($value->person_id);

                $html = '<a href="'.url('simpanpinjam/wajib/'.$person_id).'" class="btn btn-xs purple-sharp tooltips" title="Detail Data">Detail</a>'.
                    // '<a href="'.url('simpanpinjam/investasi/'.$person_id.'/edit').'" class="btn btn-xs blue-sharp tooltips" title="Ubah Data"><i class="glyphicon glyphicon-edit"></i></a>'.
                    '&nbsp;';
                return $html;
            })
            ->rawColumns(['action','status']);
            return $datatables->make(true);
        }
    }

    public function periodicprocess(){
        $title    = 'Kalkulasi Simpanan Wajib Anggota';
        $parssing = array('title' =>  ucwords($title));
        $parssing['currentMonth']  = getMonths()[(int)date('m')].' '.date('Y');
        return view('SimpanPinjam.simpanan.wajib.kalkulasiperiodic')->with($parssing);
    }

    public function kalkulasiSimpananWajib(Request $req){
        $user_id = Auth::user()->id;
        if($req->ajax())
        {
            $retrn = [];
            $prm = $req->get('params');
            $mnth = explode(' ', $prm);
            foreach(getMonths() as $key => $val){
                if($val == $mnth[0])
                    $prm = sprintf('%02d', $key).$mnth[1];
            }
            if(PeriodicSavings::kalkulasiSpWajib($user_id, $prm)){
                $retrn['status'] = "Success"; 
                return response()->json($retrn);
            }else{
                $retrn['status'] = "Gagal";
                return response()->json($retrn);
            }
        }
    }

    public function postingKalkulasi(Request $req){
        $user_id = Auth::user()->id;
        if($req->ajax()){
            $retrn = [];
            $prm = $req->get('params');
            $mnth = explode(' ', $prm);
            foreach(getMonths() as $key => $val){
                if($val == $mnth[0])
                    $prm = sprintf('%02d', $key).$mnth[1];
            }
            if(PeriodicSavings::postingKalkulasi($user_id, $prm)){
                $retrn['status'] = "Success"; 
                return response()->json($retrn);
            }else{
                $retrn['status'] = "Gagal";
                return response()->json($retrn);
            }


        }
    }

    public function exportExcelSimpananWajib(Request $req){
        $inputPeriode = $req->get('fperiode');
        $fullPeriode = $req->get('fulperiode');
        $rows = 1;

        $array['periode'] = $inputPeriode;
        // $array['periode'] = $inputPeriode;
        $datas = PeriodicSavings::getListSimpananWajib($array);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet(0)->setTitle($fullPeriode);
        $rows++;
        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A'.$rows, 'NIAK')
                    ->setCellValue('B'.$rows, 'NAMA')
                    ->setCellValue('C'.$rows, 'Tempat, Tanggal Lahir')
                    ->setCellValue('D'.$rows, 'No. Identitas')
                    ->setCellValue('E'.$rows, 'Unit Kerja')
                    ->setCellValue('F'.$rows, 'Bank')
                    ->setCellValue('G'.$rows, 'No. Rekening')
                    ->setCellValue('H'.$rows, 'Simpanan Wajib');
        
        $spreadsheet->getActiveSheet()->getStyle('A'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('B'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('C'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('D'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('E'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('F'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('G'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('H'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));


        
        foreach($datas as $cell){
            $rows++;
            
            $spreadsheet->getActiveSheet()->getStyle('A'.$rows.':G'.$rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A'.$rows, $cell->anggota->niak)
                    ->setCellValue('B'.$rows, $cell->anggota->first_name.' '.$cell->anggota->last_name)
                    ->setCellValue('C'.$rows, $cell->anggota->born_place.', '.tglIndo($cell->anggota->born_date))
                    ->setCellValue('D'.$rows, $cell->anggota->id_card_number)
                    ->setCellValue('E'.$rows, $cell->anggota->customer->company_name)
                    ->setCellValue('F'.$rows, 'Bank')
                    ->setCellValue('G'.$rows, $cell->anggota->customer->account_number)
                    ->setCellValue('H'.$rows, $cell->total);
        }


        // Redirect output to a client’s web browser (Xls)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Data Simpanan Wajib Periode '.$fullPeriode.'.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $writer = IOFactory::createWriter($spreadsheet, 'Xls');
        $writer->save('php://output');
        exit;
    }

    public function receiving_process($id){       
        $decryptedId = Crypt::decrypt($id);
        $parssing['title'] = ucwords('Proses Penerimaan Simpanan Wajib Anggota');
        $parssing['data'] = PeriodicSavings::getByIdSimpananWajib($decryptedId);

    //  dd($parssing['data']);/

        $parssing['idEncrypt'] = Crypt::encrypt($parssing['data']->periodic_saving_id);
        $parssing['def_date'] = date('d-m-Y');
        $parssing['list_bank'] = Bank::pluck('nama_bank', 'bank_id');


        $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
        $parssing['nama_bank']    = $bank->bank->nama_bank;
        $parssing['nomor_rekening']    = $bank->rekening_no;
        
        return view("SimpanPinjam.simpanan.wajib.prosespenerimaan",$parssing);
    }

    public function receiving_appr($id, Request $req){
        $user_id = Auth::user()->id;
        $decryptedId    = Crypt::decrypt($id);
        $model          = PeriodicSavings::find($decryptedId);

        
        if($model->periodic_saving_id <> 0 && $model->status == 0){

            DB::beginTransaction();
            try{

                $model->payment_date    = date('Y-m-d',strtotime($req->payment_date));
                $model->status          = 2;

                
                // bank
                if($model->payment_method == 1){
                    $bank = CompanyBankAccount::with('bank')->where('divisi','SP')->first();
                    $parssing['nama_bank']    = $bank->bank->nama_bank;
                    $parssing['nomor_rekening']    = $bank->rekening_no;
                    $model->receive_bank_account_id = $bank->bank_account_id;
                }

                if($req->file('dokumen') != null){
                    $image = $req->file('dokumen');
                    $nameImage = time().'.'.$image->getClientOriginalExtension();
                    $destinationPath = public_path('/simpanpinjam/wajib/setor');
                    $image->move($destinationPath, $nameImage);
                    $model->attachment = $nameImage;
                }

                $model->updated_by = $user_id;
                if($model->save()){
                    TriggerSimpanPinjam::simpananWajib($model->periodic_saving_id, $user_id);
                }

                notify()->flash('Success!', 'success', [
                    'text' => 'Penerimaan Simpanan Wajib Berhasil',
                ]);
                
                DB::commit();
                $person_id = Crypt::encrypt($model->person_id);
                return redirect('simpanpinjam/wajib/'.$person_id);
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Penerimaan Simpanan Wajib Gagal',
                ]);
                
                die();
            }catch(\Exception $e){
                DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

            DB::commit();


        }
    }

    public function batalSimpok($id){
        $decryptedId = Crypt::decrypt($id);
        $response['response'] = false;
        $response['desc'] = '';


        DB::beginTransaction();
        try{
            
            $spwajib = PeriodicSavings::find($decryptedId);
            
            $spwajib->is_deleted = 1;
            $spwajib->updated_at = date('Y-m-d H:m:i');
            $spwajib->updated_by = Auth::user()->id;
            $spwajib->save();
            DB::commit();
            $response['response'] = true;
            
        }
        catch(ValidationException $e){
            DB::rollback();
            // print("ERROR VALIDATION");

            $response['desc'] = 'Simpanan Wajib Gagal Dibatalkan';

            // notify()->flash('Gagal!', 'warning', [
            //     'text' => 'Simpanan Pokok Gagal Dibatalkan',
            // ]);
            
            die();
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
            print("ERROR EXCEPTION");
            die();
        }

        return Response::json($response);
    }
}