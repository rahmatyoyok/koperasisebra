<?php

namespace App\Http\Models\Akuntansi;

use App\Http\Models\SimpanPinjam\PrincipalSavings;
use App\Http\Models\SimpanPinjam\PeriodicSavings;
use App\Http\Models\SimpanPinjam\InvestmentSavings;
use App\Http\Models\SimpanPinjam\InvestmentSavingInterests;
use App\Http\Models\SimpanPinjam\Loans;
use App\Http\Models\SimpanPinjam\LoanInstallments;

use App\Http\Models\Akuntansi\CompanyBankAccount;

use Illuminate\Database\Eloquent\Model;
use DB;

class TriggerSimpanPinjam extends Model
{
    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param int $Simpanan Pokok Id
     * @param int $user_id id user yang sedang merubah atau melakukan entry
     * 
     * @return int 
     */
    public static function simpananPokok($sp_id, $user_id){
        $return = false;
        $divisi = "SP";

        // check apakah wo sudah ada dan belum di hapus
        $model  = PrincipalSavings::select('*')
                            ->where('principal_saving_id',$sp_id)
                            ->where('is_deleted','0')
                            ->where('status',2)
                            ->first();

        if($model){

                $statussp = "Penerimaan Simpanan Pokok";
                $coa_debit  = "1000102/USP"; // Tunai
                
                // Jika Melalui Bank
                if($model->payment_method == 1){
                    $coaBanks = CompanyBankAccount::getCoaCodeByDivisi($divisi);
                    $coa_debit = $coaBanks->coa_code;
                }
                $coa_kredit = "2000104/USP"; // Simpanan Pokok

                // Validasi apakah jurnal sudah ada dan sudah terposting
                $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('desc',$statussp)->where('tigger_table', 'sp_principal_savings')->where('tigger_table_key_id', $model->principal_saving_id)->count();
                if($validHeaderCount == 0){
                    
                    // Hapus Jurnal yng sudah ada entry ulang
                    $delets = HeaderJurnals::where('is_trigger', 1)->where('desc',$statussp)->where('tigger_table', 'sp_principal_savings')->where('tigger_table_key_id', $model->principal_saving_id)->first();
                    try{
                        if($delets){
                            HeaderJurnals::destroy($delets->journal_header_id);
                            DetailJurnals::where('journal_header_id', $delets->journal_header_id)->delete();
                        }

                        $journalNo = ($delets) ? $delets->journal_no : HeaderJurnals::generateNoJurnal($model->payment_method, 'JKM');
                        $mheader = new HeaderJurnals();
                        
                        $mheader->division = $divisi;
                        $mheader->transaction_type_id = $model->payment_method;
                        $mheader->type = 'JKM';
                        $mheader->journal_no = $journalNo;
                        $mheader->reff_no = $model->ref_code;
                        $mheader->tr_date = date('Y-m-d');
                        $mheader->desc = $statussp;
                        $mheader->total = $model->total;
                        $mheader->is_trigger = 1;
                        $mheader->tigger_table = 'sp_principal_savings';
                        $mheader->tigger_table_key_id = $model->principal_saving_id;
                        $mheader->created_at = date('Y-m-d H:m:i');
                        $mheader->created_by = $user_id;
                        if($mheader->save()){
                            
                            $arrDetail = [];
                            //Entry Journal Debit Kredit
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_debit."')") , 'coa_code' => $coa_debit, 'seq' => 1, 'debit' => $model->total,'kredit' => 0, 'created_by' => $user_id);
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_kredit."')") , 'coa_code' => $coa_kredit, 'seq' => 2, 'debit' => 0,'kredit' => $model->total, 'created_by' => $user_id );
                            DetailJurnals::insert($arrDetail);
                        }

                        DB::commit();
                        $return = true;
                    
                    }
                    catch(ValidationException $e){
                        DB::rollback();
                        print("ERROR VALIDATION");
                        notify()->flash('Gagal!', 'warning', [
                            'text' => 'Jurnal Gagal Tambahkan',
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

        return $return;
    }

    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param int $Simpanan periodic Id
     * @param int $user_id id user yang sedang merubah atau melakukan entry
     * 
     * @return int 
     */
    public static function simpananWajib($sp_id, $user_id){
        $return = false;
        $divisi = "SP";

        // check apakah wo sudah ada dan belum di hapus
        $model  = PeriodicSavings::select('*')
                            ->where('periodic_saving_id',$sp_id)
                            ->where('status',2)
                            ->whereRaw('is_deleted = 0')
                            ->first();

        if($model){
            
            $statussp = "Penerimaan Simpanan Wajib";
            $coa_debit  = "1000102/USP"; // Kas 
            if($model->payment_method == 1){
                $coaBanks = CompanyBankAccount::getCoaCodeByDivisi($divisi);
                $coa_debit = $coaBanks->coa_code;
            }
            $coa_kredit = "2000105/USP"; // Simpanan Pokok


            // Validasi apakah jurnal sudah ada dan sudah terposting
            $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('desc',$statussp)->where('tigger_table', 'sp_periodic_savings')->where('tigger_table_key_id', $model->periodic_saving_id)->count();
            if($validHeaderCount == 0){
                
                // Hapus Jurnal yng sudah ada entry ulang
                $delets = HeaderJurnals::where('is_trigger', 1)->where('desc',$statussp)->where('tigger_table', 'sp_periodic_savings')->where('tigger_table_key_id', $model->periodic_saving_id)->first();
                try{
                    if($delets){
                        HeaderJurnals::destroy($delets->journal_header_id);
                        DetailJurnals::where('journal_header_id', $delets->journal_header_id)->delete();
                    }

                    $journalNo = ($delets) ? $delets->journal_no : HeaderJurnals::generateNoJurnal($model->payment_method, 'JKM');
                    $mheader = new HeaderJurnals();
                    
                    $mheader->division = $divisi;
                    $mheader->transaction_type_id = $model->payment_method;
                    $mheader->type = 'JKM';
                    $mheader->journal_no = $journalNo;
                    $mheader->reff_no = $model->ref_code;
                    $mheader->tr_date = date('Y-m-d');
                    $mheader->desc = $statussp;
                    $mheader->total = $model->total;
                    $mheader->is_trigger = 1;
                    $mheader->tigger_table = 'sp_periodic_savings';
                    $mheader->tigger_table_key_id = $model->periodic_saving_id;
                    $mheader->created_at = date('Y-m-d H:m:i');
                    $mheader->created_by = $user_id;
                    if($mheader->save()){
                        
                        $arrDetail = [];
                        //Entry Journal Debit Kredit
                        $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_debit."')") , 'coa_code' => $coa_debit, 'seq' => 1, 'debit' => $model->total,'kredit' => 0, 'created_by' => $user_id);
                        $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_kredit."')") , 'coa_code' => $coa_kredit, 'seq' => 2, 'debit' => 0,'kredit' => $model->total, 'created_by' => $user_id );
                        DetailJurnals::insert($arrDetail);
                    }

                    DB::commit();
                    $return = true;
                
                }
                catch(ValidationException $e){
                    DB::rollback();
                    print("ERROR VALIDATION");
                    notify()->flash('Gagal!', 'warning', [
                        'text' => 'Jurnal Gagal Tambahkan',
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

        return $return;
    }

    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param int $Simpanan Investasi Id
     * @param int $user_id id user yang sedang merubah atau melakukan entry
     * @param string param 1 receiving / 2 transfer
     * 
     * @return int 
     */
    public static function simpananInvestasi($sp_id, $user_id, $type = 1){
        $return = false;
        $divisi = "SP";

        // check apakah wo sudah ada dan belum di hapus
        $model  = InvestmentSavings::with('anggota')->select()
                                    ->addSelect(DB::raw('(select ca.code from company_bank_accounts cba, ak_coa ca where cba.at_coa_id = ca.coa_id and cba.bank_account_id = sp_investment_savings.receive_bank_account_id) coa_kas_receiving'))
                                    ->addSelect(DB::raw('(select ca.code from company_bank_accounts cba, ak_coa ca where cba.at_coa_id = ca.coa_id and cba.bank_account_id = sp_investment_savings.transfer_bank_account_id) coa_kas_transfer'))
                                    ->where('investment_saving_id',$sp_id)
                                    ->where('transaction_type', $type)
                                    ->where('status',1)
                                    ->whereRaw('is_deleted = 0')
                                    ->first();
        if($model){

            $statussp = ($type == 1) ? "Penerimaan Investasi" : "Penyerahan Investasi";
            $JRType = ($type == 1) ? "JKM" : "JKK";
            $coa_kas  = "1000102/USP"; // Kas 
            if($model->payment_method == 1){
                $coa_kas = ($type == 1) ?  $model->coa_kas_receiving : $model->coa_kas_transfer;
            }
            $coa_ht_tabungan = "3000401/USP"; // Simpanan Investasi

            // Validasi apakah jurnal sudah ada dan sudah terposting
            $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('desc',$statussp)->where('tigger_table', 'sp_investment_savings')->where('tigger_table_key_id', $model->investment_saving_id)->count();
            if($validHeaderCount == 0){
                // Hapus Jurnal yng sudah ada entry ulang
                $delets = HeaderJurnals::where('is_trigger', 1)->where('desc',$statussp)->where('tigger_table', 'sp_investment_savings')->where('tigger_table_key_id', $model->investment_saving_id)->first();

                try{
                    if($delets){
                        HeaderJurnals::destroy($delets->journal_header_id);
                        DetailJurnals::where('journal_header_id', $delets->journal_header_id)->delete();
                    }

                    $journalNo = ($delets) ? $delets->journal_no : HeaderJurnals::generateNoJurnal($model->payment_method, $JRType);
                    $mheader = new HeaderJurnals();
                    
            
                    $mheader->division = $divisi;
                    $mheader->transaction_type_id = $model->payment_method;
                    $mheader->type = $JRType;
                    $mheader->journal_no = $journalNo;
                    $mheader->reff_no = $model->ref_code;
                    $mheader->tr_date = date('Y-m-d');
                    $mheader->desc = $statussp;
                    $mheader->total = $model->total;
                    $mheader->is_trigger = 1;
                    $mheader->tigger_table = 'sp_investment_savings';
                    $mheader->tigger_table_key_id = $model->investment_saving_id;
                    $mheader->created_at = date('Y-m-d H:m:i');
                    $mheader->created_by = $user_id;
                    if($mheader->save()){
                        $arrDetail = [];
                        $coa_debit =  ($type == 1) ? $coa_kas : $coa_ht_tabungan;
                        $coa_kredit =  ($type == 1) ? $coa_ht_tabungan : $coa_kas;
                        
                        //Entry Journal Debit Kredit
                        $descsss = $statussp.' Anggota - '.$model->anggota->first_name.' '.$model->anggota->last_name.' ('.$model->anggota->niak.')';
                        $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'description' => $descsss, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_debit."')") , 'coa_code' => $coa_debit, 'seq' => 1, 'debit' => $model->total,'kredit' => 0, 'created_by' => $user_id);
                        $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'description' => $descsss, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_kredit."')") , 'coa_code' => $coa_kredit, 'seq' => 2, 'debit' => 0,'kredit' => $model->total, 'created_by' => $user_id );
                        DetailJurnals::insert($arrDetail);
                    }

                    DB::commit();
                    $return = true;
                
                }
                catch(ValidationException $e){
                    DB::rollback();
                    print("ERROR VALIDATION");
                    notify()->flash('Gagal!', 'warning', [
                        'text' => 'Jurnal Gagal Tambahkan',
                    ]);
                    
                    die();
                }catch(\Exception $e){
                    DB::rollback();
                    throw $e;
                    print("ERROR EXCEPTION");
                    die();
                }

            }
                DB::commit();
        }
        return $return;
    }

    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param string bulan mmyyyy
     * @param string list niak user
     * @param int user_id
     * 
     * @return int 
     */
    public static function bungaInvestasi($periode, $listNiakUser, $user_id){
        $return = false;
        $divisi = "SP";

        $model = InvestmentSavingInterests::with('anggota')->where('periode',$periode)
                        ->where('is_deleted',0)
                        ->where('status',2)
                        ->whereRaw("sp_investment_interests.person_id in (select p.person_id from ospos_people p where FIND_IN_SET(p.niak,'".$listNiakUser."'))")
                        ->orderBy('status')
                        ->get();
        // dd($model);die;
                    

        if($model){
            // dd($model);
            foreach($model as $mdl){
                $statuA = "Pembayaran Bunga Investasi";
                $coaKas = CompanyBankAccount::getCoaCodeByDivisi($divisi)->coa_code;
                $coaDebit = 'SP-C201'; // BEBAN LANGSUNG JASA TABUNGAN INVESTASI
                $coaKredit = 'SP-A102'; // PENDAPATAN BUNGA TABUNGAN INVESTASI 
                $coaPajak = '3000105/USP'; // JASA ANGGOTA / PAJAK

                // $descrip = "Pembayaran bunga investasi periode ".$periode." ".$mdl->anggota->first_name." ".$mdl->anggota->last_name." (".$mdl->anggota->niak.")";
                
                // Validasi apakah jurnal sudah ada dan sudah terposting
                $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('tigger_table', 'sp_investment_interests')->where('tigger_table_key_id', $mdl->investment_interest_id)->where('desc',$statuA)->count();
                if($validHeaderCount == 0){
                    
                    // Proses Penerimaan
                    // Hapus Jurnal yng sudah ada entry ulang
                    $deletsA = HeaderJurnals::where('is_trigger', 1)->where('tigger_table', 'sp_investment_interests')->where('tigger_table_key_id', $mdl->investment_interest_id)->where('desc',$statuA)->first();
                    
                    try{
                        if($deletsA){
                            HeaderJurnals::destroy($deletsA->journal_header_id);
                            DetailJurnals::where('journal_header_id', $deletsA->journal_header_id)->delete();
                        }
    
                        $journalNo = ($deletsA) ? $deletsA->journal_no : HeaderJurnals::generateNoJurnal(1, 'JKK');
                        $mheader = new HeaderJurnals();
                        
                        $mheader->division = $divisi;
                        $mheader->transaction_type_id = 1;
                        $mheader->type = 'JKK';
                        $mheader->journal_no = $journalNo;
                        $mheader->reff_no = $mdl->ref_code;
                        $mheader->tr_date = date('Y-m-d');
                        $mheader->desc = $statuA;
                        $mheader->total = $mdl->bunga_investasi;
                        $mheader->is_trigger = 1;
                        $mheader->tigger_table = 'sp_investment_interests';
                        $mheader->tigger_table_key_id = $mdl->investment_interest_id;
                        $mheader->created_at = date('Y-m-d H:m:i');
                        $mheader->created_by = $user_id;
                        if($mheader->save()){
                            
                            $arrDetail = [];
                            //Entry Journal Debit Kredit
                            $descrip = "Pembayaran bunga investasi periode ".$periode." ".$mdl->anggota->first_name." ".$mdl->anggota->last_name." (".$mdl->anggota->niak.")";
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'description'=>$descrip, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit."')") , 'coa_code' => $coaDebit, 'seq' => 1, 'debit' => $mdl->bunga_investasi,'kredit' => 0, 'created_by' => $user_id);
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'description'=>$descrip, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaKredit."')") , 'coa_code' => $coaKredit, 'seq' => 2, 'debit' => 0,'kredit' => $mdl->biaya_administrasi, 'created_by' => $user_id );
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'description'=>$descrip, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPajak."')") , 'coa_code' => $coaPajak, 'seq' => 3, 'debit' => 0,'kredit' => $mdl->biaya_pajak, 'created_by' => $user_id );
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'description'=>$descrip, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaKas."')") , 'coa_code' => $coaKas, 'seq' => 4, 'debit' => 0,'kredit' => $mdl->jumlah_transfer, 'created_by' => $user_id );
                            DetailJurnals::insert($arrDetail);

                        }
                        

                        DB::commit();
                        $return = true;
                    }
                    catch(ValidationException $e){
                        DB::rollback();
                        die();
                    }catch(\Exception $e){
                        DB::rollback();
                        die();
                    }
                }
                
            }
        }

        return $return;
    }

    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param int Id Pinjaman
     * @param int user_id
     * 
     * @return int 
     */
    public static function pinjaman($loan_id, $user_id){
        $return = false;
        $divisi = "SP";

        // check apakah pinjaman sudah ada dan belum di hapus
        $model  = Loans::with('anggota')->select()
                        ->addSelect(DB::raw('(select ca.code from company_bank_accounts cba, ak_coa ca where cba.at_coa_id = ca.coa_id and cba.bank_account_id = sp_loans.transfer_bank_account_id) coa_kas_transfer'))
                        ->where('status',1)->whereRaw('is_deleted = 0')
                        ->where('loan_id', $loan_id)
                        ->first();
        if($model){
            $statussp = ($model->transaction_type_id == 1) ? "Penyerahan Pinjaman USP" : "Penyerahan Pinjaman Elektronik - ".$model->unit_desc;
            $JRType = "JKK";

            $coa_debit_pijaman = "1000401/USP"; // Pinjaman Anggota
            $coa_kredit_adm = "SP-A102"; // Biaya Adm Pinjaman 
            $coa_kas  = "1000102/USP"; // Kas 
            if($model->transfer_method == 1){
                $coa_kas = $model->coa_kas_transfer;
            }
            

            // Validasi apakah jurnal sudah ada dan sudah terposting
            $tableSp = 'sp_loans'; $tablekeySp = $model->loan_id;
            $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('desc',$statussp)->where('tigger_table', $tableSp)->where('tigger_table_key_id', $tablekeySp)->count();
            if($validHeaderCount == 0){
                // Hapus Jurnal yng sudah ada entry ulang
                $delets = HeaderJurnals::where('is_trigger', 1)->where('desc',$statussp)->where('tigger_table', $tableSp)->where('tigger_table_key_id', $tablekeySp)->first();

                try{
                    if($delets){
                        HeaderJurnals::destroy($delets->journal_header_id);
                        DetailJurnals::where('journal_header_id', $delets->journal_header_id)->delete();
                    }

                    $journalNo = ($delets) ? $delets->journal_no : HeaderJurnals::generateNoJurnal($model->transfer_method, $JRType);
                    $mheader = new HeaderJurnals();

                    $mheader->division = $divisi;
                    $mheader->transaction_type_id = $model->transfer_method;
                    $mheader->type = $JRType;
                    $mheader->journal_no = $journalNo;
                    $mheader->reff_no = $model->ref_code;
                    $mheader->tr_date = date('Y-m-d');
                    $mheader->desc = $statussp;
                    $mheader->total = $model->loan_amount;
                    $mheader->is_trigger = 1;
                    $mheader->tigger_table = $tableSp;
                    $mheader->tigger_table_key_id = $tablekeySp;
                    $mheader->created_at = date('Y-m-d H:m:i');
                    $mheader->created_by = $user_id;
                    if($mheader->save()){
                        $arrDetail = [];
                        //Entry Journal Debit Kredit
                        $descrip = $statussp." ".$model->anggota->first_name." ".$model->anggota->last_name." (".$model->anggota->niak.")";

                        $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'description'=>$descrip,'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_debit_pijaman."')") , 'coa_code' => $coa_debit_pijaman, 'seq' => 1, 'debit' => $model->loan_amount,'kredit' => 0, 'created_by' => $user_id);
                        $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id,'description'=>$descrip, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_kas."')") , 'coa_code' => $coa_kas, 'seq' => 2, 'debit' => 0,'kredit' => $model->loan_amount, 'created_by' => $user_id );

                        $biayaadm = $model->biaya_administrasi_rupiah + $model->biaya_provisi_rupiah + $model->biaya_materai + $model->biaya_lain + $model->resiko_daperma;
                        if($biayaadm > 0)
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_kredit_adm."')") , 'coa_code' => $coa_kredit_adm, 'seq' => 2, 'debit' => 0,'kredit' => $biayaadm, 'created_by' => $user_id );
 
                        DetailJurnals::insert($arrDetail);
                    }

                    DB::commit();
                    $return = true;
                }
                catch(ValidationException $e){
                    DB::rollback();
                    print("ERROR VALIDATION");
                    notify()->flash('Gagal!', 'warning', [
                        'text' => 'Jurnal Gagal Tambahkan',
                    ]);
                    
                    die();
                }catch(\Exception $e){
                    DB::rollback();
                    throw $e;
                    print("ERROR EXCEPTION");
                    die();
                }

            }
            DB::commit();
        }
        return $return;
    }

    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param int Id Pinjaman
     * @param int user_id
     * 
     * @return int 
     */
    public static function pinjamanElektronik($loan_id, $user_id){
        $return = false;
        $divisi = "SP";
        
        // check apakah pinjaman sudah ada dan belum di hapus
        $model  = Loans::select()
                    ->addSelect(DB::raw('(select ca.code from company_bank_accounts cba, ak_coa ca where cba.at_coa_id = ca.coa_id and cba.bank_account_id = sp_loans.transfer_bank_account_id) coa_kas_transfer'))
                    ->where('status',1)->whereRaw('is_deleted = 0')
                    ->where('loan_id', $loan_id)
                    ->first();
                    
        return $return;
    }

    public static function cicilanPinjaman($loan_detail_id, $user_id){
        $return = false;
        $divisi = "SP";

        // check apakah wo sudah ada dan belum di hapus
        $model  = LoanInstallments::where('status',2)
                        ->where('is_deleted',0)
                        ->where('loan_detail_id', $loan_detail_id)
                        ->first();
        
        if($model){
            $statussp = "Penerimaan Cicilan Pinjaman";
            $JRType = "JKM";
            $coa_debit  = "1000102/USP"; // Kas 
            if($model->payment_method == 1){
                $coaBanks = CompanyBankAccount::getCoaCodeByDivisi($divisi);
                $coa_debit = $coaBanks->coa_code;
            }
            $coa_kredit_pijaman = "1000401/USP"; // Pinjaman Anggota
            $coa_kredit_bunga = "SP-A101"; // Pendapatan Bunga Pinjaman Anggota

            $tableSp = 'sp_loan_installments'; $tablekeySp = $model->loan_detail_id;
            $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('desc',$statussp)->where('tigger_table', $tableSp)->where('tigger_table_key_id', $tablekeySp)->count();
            if($validHeaderCount == 0){
                // Hapus Jurnal yng sudah ada entry ulang
                $delets = HeaderJurnals::where('is_trigger', 1)->where('desc',$statussp)->where('tigger_table', $tableSp)->where('tigger_table_key_id', $tablekeySp)->first();

                try{
                    if($delets){
                        HeaderJurnals::destroy($delets->journal_header_id);
                        DetailJurnals::where('journal_header_id', $delets->journal_header_id)->delete();
                    }

                    $journalNo = ($delets) ? $delets->journal_no : HeaderJurnals::generateNoJurnal($model->payment_method, $JRType);
                    $mheader = new HeaderJurnals();

                    $mheader->division = $divisi;
                    $mheader->transaction_type_id = $model->payment_method;
                    $mheader->type = $JRType;
                    $mheader->journal_no = $journalNo;
                    $mheader->reff_no = $model->ref_code;
                    $mheader->tr_date = date('Y-m-d');
                    $mheader->desc = $statussp;
                    $mheader->total = $model->principal_amount + $model->rates_amount;
                    $mheader->is_trigger = 1;
                    $mheader->tigger_table = $tableSp;
                    $mheader->tigger_table_key_id = $tablekeySp;
                    $mheader->created_at = date('Y-m-d H:m:i');
                    $mheader->created_by = $user_id;

                    if($mheader->save()){
                        $arrDetail = [];
                        //Entry Journal Debit Kredit
                        $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_debit."')") , 'coa_code' => $coa_debit, 'seq' => 1, 'debit' => $mheader->total ,'kredit' => 0, 'created_by' => $user_id);
                        $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_kredit_pijaman."')") , 'coa_code' => $coa_kredit_pijaman, 'seq' => 2, 'debit' => 0,'kredit' => $model->principal_amount, 'created_by' => $user_id );

                        // $biayaadm = $model->biaya_administrasi_rupiah + $model->biaya_provisi_rupiah + $model->biaya_materai + $model->biaya_lain + $model->resiko_daperma;
                        if($model->rates_amount > 0)
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_kredit_bunga."')") , 'coa_code' => $coa_kredit_bunga, 'seq' => 2, 'debit' => 0,'kredit' => $model->rates_amount, 'created_by' => $user_id );
 
                        DetailJurnals::insert($arrDetail);
                    }

                    DB::commit();
                    $return = true;
                }
                catch(ValidationException $e){
                    DB::rollback();
                    print("ERROR VALIDATION");
                    notify()->flash('Gagal!', 'warning', [
                        'text' => 'Jurnal Gagal Tambahkan',
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

        return $return;
    }

    public static function simpanPinjamKolektif($periode, $listNIakUser, $user_id){
        $return = false;
        try{
            
            // check apakah simpanan pokok sudah ada dan belum di hapus
            $modelPokok  = PrincipalSavings::where('is_deleted','0')
                                ->where('status',2)
                                ->whereRaw("sp_principal_savings.principal_saving_id not in (select aj.tigger_table_key_id from ak_journal_headers aj where aj.is_deleted = 0 and aj.is_trigger = 1 and aj.tigger_table = 'sp_principal_savings' and aj.posting_no is not null)")
                                ->whereRaw("sp_principal_savings.person_id in (select ppl.person_id from ospos_people ppl where FIND_IN_SET(ppl.niak, '".$listNIakUser."'))")
                                ->get();

            if($modelPokok){
                foreach($modelPokok as $mpokok){
                    self::simpananPokok($mpokok->principal_saving_id, $user_id);
                }
            }

            // check apakah simpanan pokok sudah ada dan belum di hapus
            $modelWajib  = PeriodicSavings::where('status',2)
                                    ->whereRaw('is_deleted = 0')
                                    ->where('periode',$periode)
                                    ->whereRaw("sp_periodic_savings.periodic_saving_id not in (select aj.tigger_table_key_id from ak_journal_headers aj where aj.is_deleted = 0 and aj.is_trigger = 1 and aj.tigger_table = 'sp_periodic_savings' and aj.posting_no is not null)")
                                    ->whereRaw("sp_periodic_savings.person_id in (select ppl.person_id from ospos_people ppl where FIND_IN_SET(ppl.niak, '".$listNIakUser."'))")
                                    ->get();

            if($modelWajib){
                foreach($modelWajib as $mwajib){
                    self::simpananPokok($mwajib->periodic_saving_id, $user_id);
                }
            }

            $modelPinjaman = LoanInstallments::where('status',2)
                                    ->where('is_deleted',0)
                                    ->where('periode',$periode)
                                    ->whereRaw("sp_loan_installments.loan_detail_id not in (select aj.tigger_table_key_id from ak_journal_headers aj where aj.is_deleted = 0 and aj.is_trigger = 1 and aj.tigger_table = 'sp_loan_installments' and aj.posting_no is not null)")
                                    ->whereRaw("sp_loan_installments.loan_id in (select l.loan_id from ospos_people ppl, sp_loans l where l.person_id = ppl.person_id and FIND_IN_SET(ppl.niak, '".$listNIakUser."'))")
                                    ->get();

            if($modelPinjaman){
                foreach($modelPinjaman as $mPinjaman){
                    self::simpananPokok($mPinjaman->loan_detail_id, $user_id);
                }
            }

        $return = true;
        DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
            print("ERROR EXCEPTION");
            die();
        }


        return $return;
    }
}