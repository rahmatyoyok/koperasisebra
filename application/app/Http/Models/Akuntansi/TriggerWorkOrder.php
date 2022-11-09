<?php

namespace App\Http\Models\Akuntansi;

use App\Http\Models\WorkOrder;
use App\Http\Models\PembayaranWO;

use Illuminate\Database\Eloquent\Model;
use DB;

class TriggerWorkOrder extends Model
{
    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param int $woid id Work Order 
     * @param int $user_id id user yang sedang merubah atau melakukan entry
     * 
     * @return int 
     */
    public static function perubahanStatus($woId, $user_id){
        $return = false;

        // check apakah wo sudah ada dan belum di hapus
        $model  = WorkOrder::select('work_orders.*')
                            ->where('wo_id',$woId)
                            ->whereRaw('YEAR(deleted_at) = 0')
                            ->first();

        if($model){
            if($model->status_pengiriman == 1){
                    // Code Coa
                    $coa_piutang    = '1000208/UM';
                    $coa_pendapatan = ($model->jenis_pekerjaan == 1) ? 'UM-A301':'UM-A302';

                    // validasi apakah jurnal sudah ada dan terposting
                    $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('desc','Pengiriman Work Order')->where('tigger_table', 'work_orders')->where('tigger_table_key_id', $model->wo_id)->count();
                    if($validHeaderCount == 0){
                        // Hapus Jurnal yang sudah ada entry ulang
                        $delets_jr =   HeaderJurnals::whereNull('posting_no')->where('is_trigger', 1)->where('type','JRR')->where('desc','Pengiriman Work Order')->where('tigger_table', 'work_orders')->where('tigger_table_key_id', $model->wo_id)->first();
                        try{
                            if($delets_jr){
                                HeaderJurnals::destroy($delets_jr->journal_header_id);
                                DetailJurnals::where('journal_header_id', $delets_jr->journal_header_id)->delete();
                            }
    
                            $journalJrNo = ($delets_jr) ? $delets_jr->journal_no : HeaderJurnals::generateNoJurnal(1, 'JRR');
                            $mheaderjr = new HeaderJurnals();

                            $mheaderjr->division = 'UM';
                            $mheaderjr->transaction_type_id = 1;
                            $mheaderjr->type = 'JRR';
                            $mheaderjr->journal_no = $journalJrNo;
                            $mheaderjr->reff_no = $model->kode_wo;
                            $mheaderjr->tr_date = date('Y-m-d');
                            $mheaderjr->desc = 'Pengiriman Work Order';
                            $mheaderjr->total = $model->nilai_pekerjaan;
                            $mheaderjr->is_trigger = 1;
                            $mheaderjr->tigger_table = 'work_orders';
                            $mheaderjr->tigger_table_key_id = $model->wo_id;
                            $mheaderjr->created_at = date('Y-m-d H:m:i');
                            $mheaderjr->created_by = $user_id;

                            if($mheaderjr->save()){
                            
                                $arrDetail = [];
                                //Entry Journal Debit Kredit
                                $sq = 1;
                                /* Debit */
                                $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_piutang."')") , 'coa_code' => $coa_piutang, 'seq' => $sq, 'debit' => $model->nilai_pekerjaan, 'kredit' => 0, 'created_by' => $user_id);
                                
                                /* Kredit */
                                $sq++;
                                $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_pendapatan."')") , 'coa_code' => $coa_pendapatan, 'seq' => $sq, 'debit' => 0, 'kredit' => $model->nilai_pekerjaan, 'created_by' => $user_id);
                                
                                
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

    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param int $woid id Work Order 
     * @param int $wo_detail_id id Work Order Pembayaran
     * @param int $user_id id user yang sedang merubah atau melakukan entry
     * 
     * @return int 
     */
    public static function pembayaranWo($wo_detail_id, $user_id){
        $return = false;

        // Check apakah wo pemnbayarn sudah ada dan belum di hapus
        $model = PembayaranWO::select("wo_pembayarans.*")
                                ->addSelect(DB::raw("(select kode_wo from work_orders w where w.wo_id = wo_pembayarans.wo_id)kode_wo"))
                                ->where("pemb_wo_id", $wo_detail_id)
                                ->first();
        
        if($model){
            // validasi apakah jurnal sudah ada dan terposting
            $validHeaderCount = HeaderJurnals::whereNotNull('posting_no')->where('is_trigger', 1)->where('desc','Pembayaran Work Order')->where('tigger_table', 'wo_pembayarans')->where('tigger_table_key_id', $model->pemb_wo_id)->count();
            if($validHeaderCount == 0){

                $coa_piutang    = '1000208/UM';
                $coaDebit_pph22 = '1000802/UM';
                $coaDebit_pph23 = '1000803/UM';
                
                // $coaDebit_ppnMasukan = '1000807/UM';
                $coaDebit_ppnMasukan = '3000601/UM';
                $coaKredit_ppnkeluaran = '3000601/UM';
                
                $coaDebit       = '1000101/UM';
                if($model->metode_pembayaran == 1){
                    $coaBanks = CompanyBankAccount::getCoaCodeByDivisi("UM");
                    $coaDebit = $coaBanks->coa_code;
                }

                $coa_pendapatan = ($model->jenis_pekerjaan == 1) ? 'UM-A301':'UM-A302';
                
                // $jumlahPendapatan = $model->jumlah_dibayar + $model->pph22 + $model->pph23 - $model->ppn_keluaran;
                $jumlahPendapatan = $model->jumlah_dibayar + $model->pph22 + $model->pph23;
                // Hapus Jurnal JKM yang sudah ada entry ulang
                // $delets_jr = HeaderJurnals::whereNull('posting_no')->where('is_trigger', 1)->where('type','JKM')->where('desc','Pembayaran Work Order')->where('tigger_table', 'wo_pembayarans')->where('tigger_table_key_id', $model->pemb_wo_id)->first();
                
                try{
                    // if($delets_jr){
                    //     HeaderJurnals::destroy($delets_jr->journal_header_id);
                    //     DetailJurnals::where('journal_header_id', $delets_jr->journal_header_id)->delete();
                    // }

                    // $journalJrNo = ($delets_jr) ? $delets_jr->journal_no : HeaderJurnals::generateNoJurnal(1, 'JKM');
                    $journalJrNo = HeaderJurnals::generateNoJurnal(1, 'JKM');
                    $mheaderjr = new HeaderJurnals();

                    $mheaderjr->division = 'UM';
                    $mheaderjr->transaction_type_id = $model->metode_pembayaran;
                    $mheaderjr->type = 'JKM';
                    $mheaderjr->journal_no = $journalJrNo;
                    $mheaderjr->reff_no = $model->kode_wo;
                    $mheaderjr->tr_date = date('Y-m-d');
                    $mheaderjr->desc = 'Pembayaran Work Order';
                    $mheaderjr->total = $model->nominal;
                    $mheaderjr->is_trigger = 1;
                    $mheaderjr->tigger_table = 'wo_pembayarans';
                    $mheaderjr->tigger_table_key_id = $model->pemb_wo_id;
                    $mheaderjr->created_at = date('Y-m-d H:m:i');
                    $mheaderjr->created_by = $user_id;

                    if($mheaderjr->save()){
                    
                        $arrDetail = [];
                        //Entry Journal Debit Kredit
                        $sq = 1;
                        /* Debit */

                        $tool10jt = $model->jumlah_dibayar + $model->pph22 +  $model->pph23 + $model->ppn_keluaran;
                        if($tool10jt <= 10000000){
                            $model->jumlah_dibayar = $model->jumlah_dibayar + $model->ppn_keluaran;
                        }

                        $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit."')") , 'coa_code' => $coaDebit, 'seq' => $sq, 'debit' => $model->jumlah_dibayar, 'kredit' => 0, 'created_by' => $user_id);
                        
                        $sq++;
                        $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_pph22."')") , 'coa_code' => $coaDebit_pph22, 'seq' => $sq, 'debit' => $model->pph22, 'kredit' => 0, 'created_by' => $user_id);
                        
                        $sq++;
                        $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_pph23."')") , 'coa_code' => $coaDebit_pph23, 'seq' => $sq, 'debit' => $model->pph23, 'kredit' => 0, 'created_by' => $user_id);
                        
                        if($tool10jt > 9999999){
                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_ppnMasukan."')") , 'coa_code' => $coaDebit_ppnMasukan, 'seq' => $sq, 'debit' => $model->ppn_keluaran, 'kredit' => 0, 'created_by' => $user_id);
                        }

                        /* Kredit */
                        $sq++;
                        $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaKredit_ppnkeluaran."')") , 'coa_code' => $coaKredit_ppnkeluaran, 'seq' => $sq, 'debit' => 0, 'kredit' => $model->ppn_keluaran, 'created_by' => $user_id);
                        
                        $sq++;
                        $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coa_pendapatan."')") , 'coa_code' => $coa_pendapatan, 'seq' => $sq, 'debit' => 0, 'kredit' => $jumlahPendapatan, 'created_by' => $user_id);
                        
                        
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

}

