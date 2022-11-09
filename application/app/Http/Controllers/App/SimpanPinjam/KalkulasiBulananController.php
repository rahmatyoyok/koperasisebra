<?php

namespace App\Http\Controllers\App\SimpanPinjam;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

/*plugin phpexcel*/
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Http\Models\SimpanPinjam\Kalkulasi;
use App\Http\Models\Pengaturan\Menu;
use App\Http\Models\Akuntansi\TriggerSimpanPinjam;
use App\Http\Models\Bank;

use DB, Form, Response, Auth;

class KalkulasiBulananController extends AppController
{
    private $alphabet = array('A','B','C','D','F','G','H','I','J');

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            
            // jika berhasil login buat variabel user
            $this->user = auth()->user();

            // generate menu
            $this->menus = Menu::getMenus($this->user->level_id);

            // ambil judul dari path yang dituju
            if(!$request->ajax())
                $this->title = Menu::getTitle($request->path());

            view()->share([
                'user' => $this->user,
                'menus' => $this->menus,
                'title' => $this->title
            ]);

            // // mengecek apakah permission user yang login sesuai
            // if(!Menu::isValid($request->path(), $this->user->level_id))
            // {
            //     notify()->flash('Error!', 'error', [
            //         'text' => 'Anda tidak memiliki hak akses ke link tersebut',
            //     ]);
            //     return redirect(url()->previous());
            // }

            //lanjutkan ke request selanjutnya
            return $next($request);
        });
    }

    public function index(Request $req){
        $kalkulasi = new Kalkulasi();

        $title      = 'Kalkulasi Bulanan';
        $parssing   = array('title' =>  ucwords($title));
        $periode    = sprintf( '%06d',date('mY'));
        $parssing['currentMonth']  = getMonths()[(int)date('m')].' '.date('Y');

        $parssing['listKalkulasi'] = [];
        if(isset($req->periode) && !empty($req->periode)){
            $periode = $req->periode;
            $parssing['currentMonth']  = getMonths()[(int)substr($req->periode, 0, 2)].' '.(int)substr($req->periode, -4);    
        }
        
        $parssing['listKalkulasi'] = Kalkulasi::getKalkulasi($periode);

        return view('SimpanPinjam.kalkulasi.index')->with($parssing);
    }

    public function KalkulasiPerPeriode(Request $req){
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

            if(Kalkulasi::processKalkulasiPerPeriode($user_id, $prm)){

                notify()->flash('Success!', 'success', [
                    'text' => 'Kalkulasi Berhasil',
                ]);

                $retrn['status'] = "Success"; 
                $retrn['periode']= $prm;
                return response()->json($retrn);
            }else{

                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Kalkulasi Gagal',
                ]);
                $retrn['status'] = "Gagal";
                $retrn['periode']= $prm;
                return response()->json($retrn);
            }
        }
    }

    public static function PostingSelectedPerPeriode(Request $req){
        $user_id = Auth::user()->id;
        if($req->ajax())
        {
            $retrn = [];
            $retrn['periode'] = $req->periode;

            $rperson_id = ''; $ln = 0;
            foreach($_POST['datachecked'] as $rw){
                $rperson_id .= ($ln > 0) ? ','.$rw : $rw;
                $ln++;
            }

            Session::put('listPersonExportExcelKalkulasi', $rperson_id);

            // $retrn[] = "SELECT PostingKalkulasiPerPeriode('".$retrn['periode']."','".$rperson_id."')";

            if(Kalkulasi::postingKalkulasiPerPeriode($user_id, $retrn['periode'], $rperson_id)){

                // notify()->flash('Success!', 'success', [
                //     'text' => 'Posting Berhasil',
                // ]);

                $retrn['status'] = "Success"; 
                $retrn['periode']= $req->periode;
                return response()->json($retrn);
            }else{

                // notify()->flash('Gagal!', 'warning', [
                //     'text' => 'Posting Gagal',
                // ]);
                $retrn['status'] = "Gagal";
                $retrn['periode']= $req->periode;
                return response()->json($retrn);
            }
        }
    }

    public function exportExcelPosting(Request $req){
        if (Session::get('listPersonExportExcelKalkulasi') != null){

        $inputPeriode = $req->get('fperiode');
        $fullPeriode = $req->get('fulperiode');
        $rows = 1;

        $array['periode'] = $inputPeriode;
        // $array['periode'] = $inputPeriode;
        $datas = Kalkulasi::getKalkulasi($inputPeriode, 'exportposting', Session::get('listPersonExportExcelKalkulasi'));

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet(0)->setTitle($fullPeriode);
        $rows++;

        

        $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A'.$rows, 'NIAK')
                    ->setCellValue('B'.$rows, 'NAMA')
                    ->setCellValue('C'.$rows, 'PERUSAHAAN')
                    ->setCellValue('D'.$rows, 'STATUS ANGGOTA')
                    ->setCellValue('E'.$rows, 'JENIS ANGGOTA')
                    ->setCellValue('F'.$rows, 'SIMPANAN POKOK')
                    ->setCellValue('G'.$rows, 'SIMPANN WAJIB')
                    ->setCellValue('H'.$rows, 'PINJAMAN USP')
                    ->setCellValue('I'.$rows, 'PINJAMAN ELEKTRONIK')
                    ->setCellValue('J'.$rows, 'HUTANG TOKO')
                    ->setCellValue('K'.$rows, 'TOTAL')
                    ->setCellValue('L'.$rows, 'BANK')
                    ->setCellValue('M'.$rows, 'NO. REKENING');
        
        $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(34);
        $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(34);
        $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
        $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);

        $spreadsheet->getActiveSheet()->getStyle('A'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('B'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('C'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('D'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('E'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('F'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('G'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('H'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('I'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('J'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('K'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('L'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));
        $spreadsheet->getActiveSheet()->getStyle('M'.$rows)->applyFromArray(StyleSpreadsheet('boxheader'));

        foreach($datas as $cell){
            $rows++;
            
            $spreadsheet->getActiveSheet()->getStyle('A'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('B'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('C'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('D'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('E'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('F'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('G'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('H'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('I'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('J'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('K'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('L'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));
            $spreadsheet->getActiveSheet()->getStyle('M'.$rows)->applyFromArray(StyleSpreadsheet('boxBody'));

            $spreadsheet->getActiveSheet()->getStyle('A'.$rows.':E'.$rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
            $spreadsheet->getActiveSheet()->getStyle('F'.$rows.':K'.$rows)->getNumberFormat()->setFormatCode('#,##0');
            $spreadsheet->getActiveSheet()->getStyle('L'.$rows.':M'.$rows)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_TEXT);
            $spreadsheet->setActiveSheetIndex(0)
                    ->setCellValue('A'.$rows, $cell->niak)
                    ->setCellValue('B'.$rows, $cell->first_name.' '.$cell->last_name)
                    ->setCellValue('C'.$rows, $cell->company_name)
                    ->setCellValue('D'.$rows, $cell->member_status)
                    ->setCellValue('E'.$rows, $cell->member_type)
                    ->setCellValue('F'.$rows, $cell->simpanan_pokok)
                    ->setCellValue('G'.$rows, $cell->simpanan_wajib)
                    ->setCellValue('H'.$rows, $cell->pinjaman_pokok + $cell->bunga_pinjaman)
                    ->setCellValue('I'.$rows, $cell->pokok_elektronik + $cell->bunga_elektronik)
                    ->setCellValue('J'.$rows, $cell->hutang_toko)
                    ->setCellValue('K'.$rows, $cell->simpanan_pokok + $cell->simpanan_wajib + $cell->pinjaman_pokok + $cell->bunga_pinjaman + $cell->hutang_toko)
                    ->setCellValue('L'.$rows, $cell->bank)
                    ->setCellValue('M'.$rows, $cell->account_number);
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
    }

    public static function uploadPenerimaan(Request $req){
        ini_set('max_execution_time', '0');
        $user_id = Auth::user()->id;
        $data['return'] = false;
        $data['desc']   = '';
        $listNiakUser = '';
        $periode = $req->uploadPenerimaanPeriode;
        // if($req->ajax()){
            // $modelmster = Loans::find($req->ParamId);
            // if($modelmster->loan_id <> 0){
                DB::beginTransaction();
                try{
                    
                    if($req->file('dokumen') != null){

                        
                        $image = $req->file('dokumen');
                        $data['return'] = false;
                        if(strtoupper($image->getClientOriginalExtension()) == 'XLS'){
                            
                            $nameImageExcel = date('mY').'_'.time().'.'.$image->getClientOriginalExtension();
                            $destinationPath = public_path('/simpanpinjam/uploads/kalkulasi');
                            $image->move($destinationPath, $nameImageExcel);

                            // getting excel file data into array
                            $inputFileType = IOFactory::identify($destinationPath."/".$nameImageExcel);
                            $reader = IOFactory::createReader($inputFileType);
                            $reader->setReadDataOnly(TRUE);
                            $spreadsheet = $reader->load($destinationPath."/".$nameImageExcel);
                            $sData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

                            $nameImageDua = '';
                            if($req->file('dokumenLampiran') != null){
                                $imageDua = $req->file('dokumenLampiran');
                                $nameImageDua = (time()+1).'.'.$imageDua->getClientOriginalExtension();
                                $destinationPathDua = public_path('/simpanpinjam/uploads/kalkulasi');
                                $imageDua->move($destinationPathDua, $nameImageDua);

                                    File::copy($destinationPathDua."/".$nameImageDua,public_path('/simpanpinjam/pokok/setor')."/".$nameImageDua);
                                    File::copy($destinationPathDua."/".$nameImageDua,public_path('/simpanpinjam/wajib/setor')."/".$nameImageDua);
                                    File::copy($destinationPathDua."/".$nameImageDua,public_path('/simpanpinjam/pinjaman/setor')."/".$nameImageDua);
                                    File::copy($destinationPathDua."/".$nameImageDua,public_path('/simpanpinjam/elektronik/setor')."/".$nameImageDua);
                                    File::delete($destinationPathDua."/".$nameImageDua);
                            }
                        
                
                            $n = 0;
                            foreach($sData as $val){
                                $niak = $val['A']; 
                                $sppokok = $val['F']; $spwajib = $val['G']; 
                                $usp = $val['H']; $elektronik = $val['I']; 
                                $hutangtoko = $val['J'];
                                if((strlen(trim($niak))> 0) && (trim($niak) !== 'NIAK')){
                                    
                                    Kalkulasi::peneriamaanKalkulasiPerPeriodeuser($user_id, $periode, $niak, 7, $nameImageDua, $sppokok, $spwajib, $usp, $elektronik, $hutangtoko);
                                    
                                    $listNiakUser = ($n > 0) ? ','.$niak: $niak;
                                    $n++;
                                }
                            }

                            if($n > 0){
                                TriggerSimpanPinjam::simpanPinjamKolektif($periode, $listNiakUser, $user_id);
                            }

                            File::delete($destinationPath."/".$nameImageExcel);

                        }
                    }

                    $data['return']             = true;
                    notify()->flash('Success!', 'success', [
                        'text' => 'Proses Penerimaan berhasil',
                    ]);
                    
                    return redirect('simpanpinjam/kalkulasi?periode='.$periode);


                }catch(ValidationException $e){
                    DB::rollback();
                    print("ERROR VALIDATION");
                    notify()->flash('Gagal!', 'warning', [
                        'text' => 'Proses Penerimaan gagal',
                    ]);
                    return redirect()->back();
                    die();
                }catch(\Exception $e){
                    DB::rollback();
                    throw $e;
                    print("ERROR EXCEPTION");
                    die();
                }
        
                DB::commit();
        
            // }
        // }

        return response()->json($data);

    }

}