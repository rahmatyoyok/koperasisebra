<?php

namespace App\Http\Models\Akuntansi;

use App\Http\Models\Persekot;
use App\Http\Models\Akuntansi\CompanyBankAccount;
use Illuminate\Database\Eloquent\Model;
use DB;

class TriggerPersekots extends Model
{

    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param int $persekotId id Persekot
     * @param int $user_id id user yang sedang merubah atau melakukan entry
     * 
     * @return int 
     */
    public static function perubahanStatus($persekotId, $user_id){
        $return = false;
        $model  = Persekot::select('persekots.*')
                            ->addSelect(DB::raw("case 
                                                    when status = 2 or status = 3 then 
                                                        (select ak.transaction_type_id from ak_transaction_types ak where ak.transaction_type_id = persekots.metode_penerimaan)
                                                    when status = 5 then 
                                                        (select ak.transaction_type_id from ak_transaction_types ak where ak.transaction_type_id = persekots.metode_pembayaran)
                                                    else '3'
                                                    end as tr_code"))
                            ->where('persekot_id',$persekotId)
                            ->first();
        
        if($model){
            $stts   = statusPersekot((int)$model->status);
            // Verivikasi
            if($stts == 'Disetujui'){
                // Code Coa
                $coaPersekotDebit = '1000702/UM';
                $coaLawanPersekotKredit = '1000101/UM'; /** Default Kas Unit umum */
                if($model->metode_peneriamaan == 1){
                    /**jika metode tf bank
                     * select company bank where bank id = header bankid
                    */
                    $coaBanks = CompanyBankAccount::getCoaCodeByDivisi("UM");
                    $coaLawanPersekotKredit = $coaBanks->coa_code;
                    //  '10001005';
                }

                // Validasi apakah jurnal sudah ada dan sudah terposting
                $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('desc','Verifikasi Persekot')->where('tigger_table', 'persekots')->where('tigger_table_key_id', $model->persekot_id)->count();
                if($validHeaderCount == 0){
                    
                    // Hapus Jurnal yng sudah ada entry ulang
                    $delets = HeaderJurnals::where('is_trigger', 1)->where('desc','Verifikasi Persekot')->where('tigger_table', 'persekots')->where('tigger_table_key_id', $model->persekot_id)->first();
                    
                    try{
                        if($delets){
                            HeaderJurnals::destroy($delets->journal_header_id);
                            DetailJurnals::where('journal_header_id', $delets->journal_header_id)->delete();
                        }

                        $journalNo = ($delets) ? $delets->journal_no : HeaderJurnals::generateNoJurnal($model->tr_code, 'JKK');
                        $mheader = new HeaderJurnals();
                        
                        $mheader->division = 'UM';
                        $mheader->transaction_type_id = $model->tr_code;
                        $mheader->type = 'JKK';
                        $mheader->journal_no = $journalNo;
                        $mheader->reff_no = $model->no_persekot;
                        $mheader->tr_date = date('Y-m-d');
                        $mheader->desc = 'Verifikasi Persekot';
                        $mheader->total = $model->jumlah;
                        $mheader->is_trigger = 1;
                        $mheader->tigger_table = 'persekots';
                        $mheader->tigger_table_key_id = $model->persekot_id;
                        $mheader->created_at = date('Y-m-d H:m:i');
                        $mheader->created_by = $user_id;
                        if($mheader->save()){
                            
                            $arrDetail = [];
                            //Entry Journal Debit Kredit
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPersekotDebit."')") , 'coa_code' => $coaPersekotDebit, 'seq' => 1, 'debit' => $model->jumlah,'kredit' => 0, 'created_by' => $user_id);
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaLawanPersekotKredit."')") , 'coa_code' => $coaLawanPersekotKredit, 'seq' => 2, 'debit' => 0,'kredit' => $model->jumlah, 'created_by' => $user_id );
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

            // Realiasi
            elseif($stts == 'Realisasi'){   
                // validasi apakah jurnal sudah ada dan terposting
                $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('desc','Realisasi Persekot')->where('tigger_table', 'persekots')->where('tigger_table_key_id', $model->persekot_id)->count();
                if($validHeaderCount == 0){
                    
                    // Hapus Jurnal JKM yang sudah ada entry ulang
                    $delets_jkm =   HeaderJurnals::whereNull('posting_no')->where('is_trigger', 1)->where('type','JKM')->where('desc','Realisasi Persekot')->where('tigger_table', 'persekots')->where('tigger_table_key_id', $model->persekot_id)->first();
                    try{
                        if($delets_jkm){
                            HeaderJurnals::destroy($delets_jkm->journal_header_id);
                            DetailJurnals::where('journal_header_id', $delets_jkm->journal_header_id)->delete();
                        }
    
                        // Ambil detail data jurnal Verifikasi
                        $persekotId = $model->persekot_id;
                        $jrVerifikasi = DetailJurnals::join('ak_journal_headers', 'ak_journal_details.journal_header_id' ,'=','ak_journal_headers.journal_header_id')
                                                        ->where('ak_journal_headers.desc','Verifikasi Persekot')
                                                        ->where('ak_journal_headers.tigger_table', 'persekots')
                                                        ->where('ak_journal_headers.tigger_table_key_id', $model->persekot_id)
                                                        ->orderByRaw('kredit DESC')->get();

                        // Start  Journal Balik verifikasi Persekot
                        $journalJkmNo = ($delets_jkm) ? $delets_jkm->journal_no : HeaderJurnals::generateNoJurnal($model->tr_code, 'JKM');
                        $mheaderjkm = new HeaderJurnals();
                        
                        $mheaderjkm->division = 'UM';
                        $mheaderjkm->transaction_type_id = $model->tr_code;
                        $mheaderjkm->type = 'JKM';
                        $mheaderjkm->journal_no = $journalJkmNo;
                        $mheaderjkm->reff_no = $model->no_persekot;
                        $mheaderjkm->tr_date = date('Y-m-d');
                        $mheaderjkm->desc = 'Realisasi Persekot';
                        $mheaderjkm->total = $model->jumlah_asli;
                        $mheaderjkm->is_trigger = 1;
                        $mheaderjkm->tigger_table = 'persekots';
                        $mheaderjkm->tigger_table_key_id = $model->persekot_id;
                        $mheaderjkm->created_at = date('Y-m-d H:m:i');
                        $mheaderjkm->created_by = $user_id;
                        if($mheaderjkm->save()){
                            
                            $arrDetail = [];
                            //Entry Journal Debit Kredit
                            $sq = 1;
                            foreach($jrVerifikasi as $rwv){
                                $arrDetail[] = array('journal_header_id' => $mheaderjkm->journal_header_id, 'journal_header_type' => $mheaderjkm->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$rwv->coa_code."')") , 'coa_code' => $rwv->coa_code, 'seq' => $sq, 'debit' => $rwv->kredit,'kredit' => $rwv->debit, 'created_by' => $user_id );
                                $sq++;
                            }
                            
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

                    // Hapus Jurnal JKK yang sudah ada entry ulang
                    $coaPersekotDebit = ($model->jenis_pekerjaan == 1) ? 'UM-E301':'UM-D201';
                    // $coaPersekotMasukan = '1000807';

                    $coaLawanPersekotKredit = '1000101/UM'; /** Default Kas Unit umum */
                    if($model->metode_peneriamaan == 1){
                        /**jika metode tf bank
                         * select company bank where bank id = header bankid
                        */
                        $coaBanks = CompanyBankAccount::getCoaCodeByDivisi("UM");
                        $coaLawanPersekotKredit = $coaBanks->coa_code;
                        // $coaLawanPersekotKredit = '10001005';
                    }
                    
                    $delets_jkk =   HeaderJurnals::whereNull('posting_no')->where('is_trigger', 1)->where('type','JKK')->where('desc','Realisasi Persekot')->where('tigger_table', 'persekots')->where('tigger_table_key_id', $model->persekot_id)->first();
                    try{
                        if($delets_jkk){
                            HeaderJurnals::destroy($delets_jkk->journal_header_id);
                            DetailJurnals::where('journal_header_id', $delets_jkk->journal_header_id)->delete();
                        }
    
                        $journalJkkNo = ($delets_jkk) ? $delets_jkk->journal_no : HeaderJurnals::generateNoJurnal($model->tr_code, 'JKK');
                        $mheaderjkk = new HeaderJurnals();
                        
                        $mheaderjkk->division = 'UM';
                        $mheaderjkk->transaction_type_id = $model->tr_code;
                        $mheaderjkk->type = 'JKK';
                        $mheaderjkk->journal_no = $journalJkkNo;
                        $mheaderjkk->reff_no = $model->no_persekot;
                        $mheaderjkk->tr_date = date('Y-m-d');
                        $mheaderjkk->desc = 'Realisasi Persekot';
                        $mheaderjkk->total = $model->jumlah;
                        $mheaderjkk->is_trigger = 1;
                        $mheaderjkk->tigger_table = 'persekots';
                        $mheaderjkk->tigger_table_key_id = $model->persekot_id;
                        $mheaderjkk->created_at = date('Y-m-d H:m:i');
                        $mheaderjkk->created_by = $user_id;
                        if($mheaderjkk->save()){
                            
                            $arrDetail = [];
                            //Entry Journal Debit Kredit
                            $sq = 1;
                            $arrDetail[] = array('journal_header_id' => $mheaderjkk->journal_header_id, 'journal_header_type' => $mheaderjkk->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPersekotDebit."')") , 'coa_code' => $coaPersekotDebit, 'seq' => $sq, 'debit' => $model->jumlah,'kredit' => 0, 'created_by' => $user_id);
                            $sq++;
                            $jmlkasout = $model->jumlah;
                            // if($model->jumlah >= 50000000){
                            //     $arrDetail[] = array('journal_header_id' => $mheaderjkk->journal_header_id, 'journal_header_type' => $mheaderjkk->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPersekotMasukan."')") , 'coa_code' => $coaPersekotMasukan, 'seq' => $sq, 'debit' => (($model->jumlah*10)/100),'kredit' => 0, 'created_by' => $user_id);
                            //     $jmlkasout = $jmlkasout + (($model->jumlah*10)/100);
                            //     $sq++;
                            // }
                            $arrDetail[] = array('journal_header_id' => $mheaderjkk->journal_header_id, 'journal_header_type' => $mheaderjkk->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaLawanPersekotKredit."')") , 'coa_code' => $coaLawanPersekotKredit, 'seq' => $sq, 'debit' => 0,'kredit' => $jmlkasout, 'created_by' => $user_id);

                            
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

            // PNPO
            elseif($stts == 'PNPO'){
                // validasi apakah jurnal sudah ada dan terposting
                $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('desc','PNPO Persekot')->where('tigger_table', 'persekots')->where('tigger_table_key_id', $model->persekot_id)->count();
                if($validHeaderCount == 0){
                    // Hapus Jurnal JRR yang sudah ada entry ulang
                    $coaPrDebit_piutang = '1000208/UM';
                    
                    //** default pendaptan material */
                    $coaPrKredit_ppnkeluaran = '3000601/UM';
                    $coaPrKredit_pendapatan = ($model->jenis_pekerjaan == 2) ? 'UM-A302' : 'UM-A301';
                    $coaPrKredit_pdmargin   = 'UM-A399';

                    // Hapus Jurnal JRR yang sudah ada entry ulang
                    $delets_jr =   HeaderJurnals::whereNull('posting_no')->where('is_trigger', 1)->where('type','JRR')->where('desc','PNPO Persekot')->where('tigger_table', 'persekots')->where('tigger_table_key_id', $model->persekot_id)->first();
                    try{
                        if($delets_jr){
                            HeaderJurnals::destroy($delets_jr->journal_header_id);
                            DetailJurnals::where('journal_header_id', $delets_jr->journal_header_id)->delete();
                        }
    
                        $journalJrNo = ($delets_jr) ? $delets_jr->journal_no : HeaderJurnals::generateNoJurnal($model->tr_code, 'JRR');
                        $mheaderjr = new HeaderJurnals();
                        
                        $mheaderjr->division = 'UM';
                        $mheaderjr->transaction_type_id = $model->tr_code;
                        $mheaderjr->type = 'JRR';
                        $mheaderjr->journal_no = $journalJrNo;
                        $mheaderjr->reff_no = $model->no_persekot;
                        $mheaderjr->tr_date = date('Y-m-d');
                        $mheaderjr->desc = 'PNPO Persekot';
                        $mheaderjr->total = $model->margin_val;
                        $mheaderjr->is_trigger = 1;
                        $mheaderjr->tigger_table = 'persekots';
                        $mheaderjr->tigger_table_key_id = $model->persekot_id;
                        $mheaderjr->created_at = date('Y-m-d H:m:i');
                        $mheaderjr->created_by = $user_id;
                        if($mheaderjr->save()){
                            
                            
                            $jmlPiutang = $model->margin_val;
                            $pendaptan  = $model->jumlah;
                            $ppn        = $model->jumlah * ($model->ppn/100);
                            $pdmargin   = $jmlPiutang-$ppn-$pendaptan;
                            
                            $arrDetail = [];
                            //Entry Journal Debit Kredit
                            $sq = 1;
                            /* Debit */
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPrDebit_piutang."')") , 'coa_code' => $coaPrDebit_piutang, 'seq' => $sq, 'debit' => $jmlPiutang, 'kredit' => 0, 'created_by' => $user_id);
                            
                            /* Kredit */
                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPrKredit_pendapatan."')") , 'coa_code' => $coaPrKredit_pendapatan, 'seq' => $sq, 'debit' => 0, 'kredit' => ($pendaptan + $ppn), 'created_by' => $user_id);
                            // $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPrKredit_pendapatan."')") , 'coa_code' => $coaPrKredit_pendapatan, 'seq' => $sq, 'debit' => 0, 'kredit' => $pendaptan, 'created_by' => $user_id);
                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPrKredit_ppnkeluaran."')") , 'coa_code' => $coaPrKredit_ppnkeluaran, 'seq' => $sq, 'debit' => 0, 'kredit' => $pdmargin, 'created_by' => $user_id);
                            $sq++;
                            // $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPrKredit_pdmargin."')") , 'coa_code' => $coaPrKredit_pdmargin, 'seq' => $sq, 'debit' => 0, 'kredit' => $ppn, 'created_by' => $user_id);
                            
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
        
            // Pembayaran
            elseif($stts == 'Lunas'){
                // validasi apakah jurnal sudah ada dan terposting
                $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('desc','Pembayaran Persekot')->where('tigger_table', 'persekots')->where('tigger_table_key_id', $model->persekot_id)->count();
                if($validHeaderCount == 0){
                    $coaDebit = '1000101/UM'; 
                    $coaPrDebit_ppnmasukan = '1000807/UM';
                    $coaPrDebit_pph22 = '1000802/UM';
                    $coaPrDebit_pph23 = '1000803/UM';
                    $coaKredit = '1000208/UM';

                    if($model->metode_pembayaran == 1){
                        $coaBanks = CompanyBankAccount::getCoaCodeByDivisi("UM");
                        $coaDebit = $coaBanks->coa_code;
                        // $coaDebit = '10001005';
                    }

                    // Hapus Jurnal JKM yang sudah ada entry ulang
                    $delets_jr =   HeaderJurnals::whereNull('posting_no')->where('is_trigger', 1)->where('type','JKM')->where('desc','Pembayaran Persekot')->where('tigger_table', 'persekots')->where('tigger_table_key_id', $model->persekot_id)->first();
                    try{
                        if($delets_jr){
                            HeaderJurnals::destroy($delets_jr->journal_header_id);
                            DetailJurnals::where('journal_header_id', $delets_jr->journal_header_id)->delete();
                        }

                        $journalJrNo = ($delets_jr) ? $delets_jr->journal_no : HeaderJurnals::generateNoJurnal($model->tr_code, 'JKM');
                        $mheaderjr = new HeaderJurnals();
                        
                        $mheaderjr->division = 'UM';
                        $mheaderjr->transaction_type_id = $model->tr_code;
                        $mheaderjr->type = 'JKM';
                        $mheaderjr->journal_no = $journalJrNo;
                        $mheaderjr->reff_no = $model->no_persekot;
                        $mheaderjr->tr_date = date('Y-m-d');
                        $mheaderjr->desc = 'Pembayaran Persekot';
                        $mheaderjr->total = $model->margin_val;
                        $mheaderjr->is_trigger = 1;
                        $mheaderjr->tigger_table = 'persekots';
                        $mheaderjr->tigger_table_key_id = $model->persekot_id;
                        $mheaderjr->created_at = date('Y-m-d H:m:i');
                        $mheaderjr->created_by = $user_id;
                        if($mheaderjr->save()){
                            
                            $arrDetail = [];
                            //Entry Journal Debit Kredit
                            $sq = 1;
                            /* Debit */
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit."')") , 'coa_code' => $coaDebit, 'seq' => $sq, 'debit' => $model->jumlah_dibayar, 'kredit' => 0, 'created_by' => $user_id);
                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPrDebit_ppnmasukan."')") , 'coa_code' => $coaPrDebit_ppnmasukan, 'seq' => $sq, 'debit' => $model->ppn_masukan, 'kredit' => 0, 'created_by' => $user_id);
                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPrDebit_pph22."')") , 'coa_code' => $coaPrDebit_pph22, 'seq' => $sq, 'debit' => $model->pph22, 'kredit' => 0, 'created_by' => $user_id);
                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaPrDebit_pph23."')") , 'coa_code' => $coaPrDebit_pph23, 'seq' => $sq, 'debit' => $model->pph23, 'kredit' => 0, 'created_by' => $user_id);

                            /* Kredit */
                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaKredit."')") , 'coa_code' => $coaKredit, 'seq' => $sq, 'debit' => 0, 'kredit' => $model->margin_val, 'created_by' => $user_id);
                            
                            
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

        }
        
        return $return;
    }

}