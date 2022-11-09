<?php

namespace App\Http\Models\Akuntansi;

use App\Http\Models\PurchaseOrder;
use App\Http\Models\PembayaranPO;

use Illuminate\Database\Eloquent\Model;
use DB;

class TriggerPurchaseOrder extends Model
{
    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param int $po_id id Purchase Order
     * @param int $user_id id user yang sedang merubah atau melakukan entry
     * 
     * @return int 
     */
    public static function jurnalTransaksi($po_id, $po_pemb_id, $user_id){
        $return = false;

        // check apakah wo sudah ada dan belum di hapus
        $model  = PurchaseOrder::select('purchase_orders.*')
                            ->addSelect(DB::raw("ifnull((select sum(byr.nominal) from po_pembayarans byr where byr.po_id = purchase_orders.po_id), 0)valid_bayar"))
                            ->addSelect(DB::raw("(select nama_pekerjaan from work_orders wo where wo.wo_id = purchase_orders.wo_id)nama_pekerjaan"))
                            ->where('po_id',$po_id)
                            ->whereRaw('YEAR(deleted_at) = 0')
                            ->first();
                            // dd($model);  
        if($model){

            // Jika barang belum dikirim tapi sudah dibayar
            if(($model->status_penerimaan == 0) && ($model->valid_bayar > 0)){

                try{
                    $mPemb = PembayaranPO::find($po_pemb_id);

                    // uang muka pada kas
                    $coaDebit   = '1000703';
                    $coakredit  = '1000101/UM';
                    if($mPemb->metode_pembayaran == 1){
                        $coaBanks = CompanyBankAccount::getCoaCodeByDivisi("UM");
                        $coakredit = $coaBanks->coa_code;
                    }

                    $journalJrNo    = HeaderJurnals::generateNoJurnal($mPemb->metode_pembayaran, 'JKK');
                    $mheaderjr      = new HeaderJurnals();

                    $mheaderjr->division = 'UM';
                    $mheaderjr->transaction_type_id = $mPemb->metode_pembayaran;
                    $mheaderjr->type = 'JKK';
                    $mheaderjr->journal_no = $journalJrNo;
                    $mheaderjr->reff_no = $model->kode_po;
                    $mheaderjr->tr_date = date('Y-m-d');
                    $mheaderjr->desc = 'Transaksi Pembelian Memalui Purchase Order';
                    $mheaderjr->total = $mPemb->nominal;
                    $mheaderjr->is_trigger = 1;
                    $mheaderjr->tigger_table = 'po_pembayarans';
                    $mheaderjr->tigger_table_key_id = $mPemb->pemb_po_id;
                    $mheaderjr->created_at = date('Y-m-d H:m:i');
                    $mheaderjr->created_by = $user_id;

                    if($mheaderjr->save()){
                        $arrDetail = [];
                        //Entry Journal Debit Kredit
                        $sq = 1;
                        /* Debit */
                        $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit."')") , 'coa_code' => $coaDebit, 'seq' => $sq, 'debit' => $mPemb->nominal, 'kredit' => 0, 'created_by' => $user_id);
                        
                        /* Kredit */
                        $sq++;
                        $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coakredit."')") , 'coa_code' => $coakredit, 'seq' => $sq, 'debit' => 0, 'kredit' => $mPemb->nominal, 'created_by' => $user_id);
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

            // Jika barang diterima tapi belum bayar
            if(($model->status_penerimaan == 1) && ($model->valid_bayar <= 0)){
                try{
                    // uang muka pada kas
                    $coaDebit_1   = 'UM-E399'; 
                        if(strpos(strtolower($model->nama_pekerjaan), 'atk') !== false) {
                            $coaDebit_1 = 'UM-E305';
                        }
                        elseif(strpos(strtolower($model->nama_pekerjaan), 'ekstravoeding') !== false) {
                            $coaDebit_1 = 'UM-E306';
                        }
                        elseif(strpos(strtolower($model->nama_pekerjaan), 'consumable') !== false) {
                            $coaDebit_1 = 'UM-E304';
                        }
                       
                        $coaDebit_2 = 'UM-F402'; // bbm dan konsumsi
                        $coaDebit_3 = '1000807/UM'; // ppn masukan
                        $coaDebit_4 = '3000603/UM'; // pph 22
                        $coaDebit_5 = '3000604/UM'; // pph 23  
                    
                        
                    $coakredit  = '3000204/UM';

                    $journalJrNo    = HeaderJurnals::generateNoJurnal(3, 'JRR');
                    $mheaderjr      = new HeaderJurnals();

                    $mheaderjr->division = 'UM';
                    $mheaderjr->transaction_type_id = 3;
                    $mheaderjr->type = 'JRR';
                    $mheaderjr->journal_no = $journalJrNo;
                    $mheaderjr->reff_no = $model->kode_po;
                    $mheaderjr->tr_date = date('Y-m-d');
                    $mheaderjr->desc = 'Penerimaan Transaksi Pembelian Memalui Purchase Order';
                    $mheaderjr->total = $model->total;
                    $mheaderjr->is_trigger = 1;
                    $mheaderjr->tigger_table = 'purchase_orders';
                    $mheaderjr->tigger_table_key_id = $model->po_id;
                    $mheaderjr->created_at = date('Y-m-d H:m:i');
                    $mheaderjr->created_by = $user_id;

                    if($mheaderjr->save()){
                        $arrDetail = [];
                        //Entry Journal Debit Kredit
                        $sq = 1;
                        $total = $model->total_detail;
                        /* Debit */
                        $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_1."')") , 'coa_code' => $coaDebit_1, 'seq' => $sq, 'debit' => $model->total_detail, 'kredit' => 0, 'created_by' => $user_id);
                        
                        // if($model->bbm_konsumsi > 0){
                        //     $sq++;
                        //     $total = $total + $model->bbm_konsumsi;
                        //     $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_2."')") , 'coa_code' => $coaDebit_2, 'seq' => $sq, 'debit' => $model->bbm_konsumsi, 'kredit' => 0, 'created_by' => $user_id);
                        // }

                        if($model->ppn > 0){
                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_3."')") , 'coa_code' => $coaDebit_3, 'seq' => $sq, 'debit' => $model->ppn, 'kredit' => 0, 'created_by' => $user_id);
                        }

                        

                        /* Kredit */
                        if($model->pph22 > 0){
                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_4."')") , 'coa_code' => $coaDebit_4, 'seq' => $sq, 'debit' => 0, 'kredit' => $model->pph22, 'created_by' => $user_id);
                        }

                        if($model->pph23 > 0){
                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_5."')") , 'coa_code' => $coaDebit_5, 'seq' => $sq, 'debit' => 0, 'kredit' => $model->pph23, 'created_by' => $user_id);
                        }

                        
                        $sq++;
                        $total = $model->total_detail+$model->ppn-$model->pph22-$model->pph23;
                        $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coakredit."')") , 'coa_code' => $coakredit, 'seq' => $sq, 'debit' => 0, 'kredit' => $total, 'created_by' => $user_id);
                        DetailJurnals::insert($arrDetail);
                    }


                    if($model->bbm_konsumsi > 0){
                        
                        
                        // $mPemb = PembayaranPO::find($po_pemb_id);

                        // // uang muka pada kas
                        // $coaDebit   = '1000703';
                        $coakredit  = '1000101/UM';
                        // if($mPemb->metode_pembayaran == 1){
                        //     $coaBanks = CompanyBankAccount::getCoaCodeByDivisi("UM");
                        //     $coakredit = $coaBanks->coa_code;
                        // }

                        $journalJrNo    = HeaderJurnals::generateNoJurnal(1, 'JKK');
                        $mheaderjr      = new HeaderJurnals();

                        $mheaderjr->division = 'UM';
                        $mheaderjr->transaction_type_id = 3;
                        $mheaderjr->type = 'JKK';
                        $mheaderjr->journal_no = $journalJrNo;
                        $mheaderjr->reff_no = $model->kode_po;
                        $mheaderjr->tr_date = date('Y-m-d');
                        $mheaderjr->desc = 'Penerimaan Transaksi Pembelian Memalui Purchase Order';
                        $mheaderjr->total = $model->total;
                        $mheaderjr->is_trigger = 1;
                        $mheaderjr->tigger_table = 'purchase_orders';
                        $mheaderjr->tigger_table_key_id = $model->po_id;
                        $mheaderjr->created_at = date('Y-m-d H:m:i');
                        $mheaderjr->created_by = $user_id;

                        if($mheaderjr->save()){
                            $arrDetail = [];
                            if($model->ppn > 0){
                                $sq = 1;
                                $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_2."')") , 'coa_code' => $coaDebit_2, 'seq' => $sq, 'debit' => $model->bbm_konsumsi, 'kredit' => 0, 'created_by' => $user_id);
                            }

                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coakredit."')") , 'coa_code' => $coakredit, 'seq' => $sq, 'debit' => 0, 'kredit' => $model->bbm_konsumsi, 'created_by' => $user_id);
                            DetailJurnals::insert($arrDetail);
                        }
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

            // Penerimaan barang setalah uang muka pembayaran dan belum lunas
            if(($model->status_penerimaan == 1) && ($model->valid_bayar > 0)){
                
                if(is_null($po_pemb_id)){
                    $coaDebit_material = 'UM-E399';
                        if(strpos(strtolower($model->nama_pekerjaan), 'atk') !== false) { $coaDebit_material = 'UM-E305'; }
                        elseif(strpos(strtolower($model->nama_pekerjaan), 'ekstravoeding') !== false) { $coaDebit_material = 'UM-E306'; }
                        elseif(strpos(strtolower($model->nama_pekerjaan), 'consumable') !== false) { $coaDebit_material = 'UM-E304'; }

                    $coaDebit_ppn = '1000807/UM';
                    $coaDebit_bbm = 'UM-F402';
                    $coaDebit_pph22 = '3000603/UM';
                    $coaDebit_pph23 = '3000604/UM';
                        $coaKredit_umPembelian = '1000703/UM';
                        $coaKredit_utangDagang = '3000204/UM';

                    

                    try{
                        $journalJrNo    = HeaderJurnals::generateNoJurnal(3, 'JRR');
                        $mheaderjr      = new HeaderJurnals();

                        $mheaderjr->division = 'UM';
                        $mheaderjr->transaction_type_id = 3;
                        $mheaderjr->type = 'JRR';
                        $mheaderjr->journal_no = $journalJrNo;
                        $mheaderjr->reff_no = $model->kode_po;
                        $mheaderjr->tr_date = date('Y-m-d');
                        $mheaderjr->desc = 'Penerimaan Barang Purchase Order';
                        $mheaderjr->total = $model->total;
                        $mheaderjr->is_trigger = 1;
                        $mheaderjr->tigger_table = 'purchase_orders';
                        $mheaderjr->tigger_table_key_id = $model->po_id;
                        $mheaderjr->created_at = date('Y-m-d H:m:i');
                        $mheaderjr->created_by = $user_id;

                        if($mheaderjr->save()){
                            $arrDetail = [];
                            //Entry Journal Debit Kredit
                            $sq = 1;
                            $total = $model->total_detail;
                            /* Debit */
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_material."')") , 'coa_code' => $coaDebit_material, 'seq' => $sq, 'debit' => $model->total_detail, 'kredit' => 0, 'created_by' => $user_id);
                            
                            if($model->bbm_konsumsi > 0){
                                $sq++;
                                $total = $total + $model->bbm_konsumsi;
                                $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_bbm."')") , 'coa_code' => $coaDebit_bbm, 'seq' => $sq, 'debit' => $model->bbm_konsumsi, 'kredit' => 0, 'created_by' => $user_id);
                            }

                            if($model->nominal_ppn > 0){
                                $sq++;
                                $total = $total + $model->nominal_ppn;
                                $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_ppn."')") , 'coa_code' => $coaDebit_ppn, 'seq' => $sq, 'debit' =>  $model->nominal_ppn, 'kredit' => 0, 'created_by' => $user_id);
                            }

                            if($model->pph22 > 0){
                                $sq++;
                                $pph22 = ($model->total_detail_bbm * $model->pph22)/100;
                                $total = $total + $pph22;
                                $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_pph22."')") , 'coa_code' => $coaDebit_pph22, 'seq' => $sq, 'debit' => $pph22, 'kredit' => 0, 'created_by' => $user_id);
                            }

                            if($model->pph23 > 0){
                                $sq++;
                                $pph23 = ($model->total_detail_bbm * $model->pph23)/100;
                                $total = $total + $pph23;
                                $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit_pph23."')") , 'coa_code' => $coaDebit_pph23, 'seq' => $sq, 'debit' => $pph23, 'kredit' => 0, 'created_by' => $user_id);
                            }

                            /* Kredit */
                            $sq++;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaKredit_umPembelian."')") , 'coa_code' => $coaKredit_umPembelian, 'seq' => $sq, 'debit' => 0, 'kredit' => $model->valid_bayar, 'created_by' => $user_id);

                            
                            if(($total - $model->valid_bayar) > 0 ){
                                $sq++;
                                $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaKredit_utangDagang."')") , 'coa_code' => $coaKredit_utangDagang, 'seq' => $sq, 'debit' => 0, 'kredit' => ($total - $model->valid_bayar), 'created_by' => $user_id);
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
                
                }
                else{
                    $mPemb = PembayaranPO::find($po_pemb_id);

                    if($mPemb){
                        try{
                            // uang muka pada kas
                            $coaDebit   = '3000204/UM'; // Hutang dagang
                            $coakredit  = '1000101/UM';
                            if($mPemb->metode_pembayaran == 1){
                                $coaBanks = CompanyBankAccount::getCoaCodeByDivisi("UM");
                                $coakredit = $coaBanks->coa_code;
                            }

                            $journalJrNo    = HeaderJurnals::generateNoJurnal($mPemb->metode_pembayaran, 'JKK');
                            $mheaderjr      = new HeaderJurnals();

                            $mheaderjr->division = 'UM';
                            $mheaderjr->transaction_type_id = $mPemb->metode_pembayaran;
                            $mheaderjr->type = 'JKK';
                            $mheaderjr->journal_no = $journalJrNo;
                            $mheaderjr->reff_no = $model->kode_po;
                            $mheaderjr->tr_date = date('Y-m-d');
                            $mheaderjr->desc = 'Pembayaran Purchase Order';
                            $mheaderjr->total = $mPemb->nominal;
                            $mheaderjr->is_trigger = 1;
                            $mheaderjr->tigger_table = 'po_pembayarans';
                            $mheaderjr->tigger_table_key_id = $mPemb->pemb_po_id;
                            $mheaderjr->created_at = date('Y-m-d H:m:i');
                            $mheaderjr->created_by = $user_id;
                            if($mheaderjr->save()){
                                $arrDetail = [];
                                //Entry Journal Debit Kredit
                                $sq = 1;
                                /* Debit */
                                $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit."')") , 'coa_code' => $coaDebit, 'seq' => $sq, 'debit' => $mPemb->nominal, 'kredit' => 0, 'created_by' => $user_id);
                                
                                /* Kredit */
                                $sq++;
                                $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coakredit."')") , 'coa_code' => $coakredit, 'seq' => $sq, 'debit' => 0, 'kredit' => $mPemb->nominal, 'created_by' => $user_id);
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


        }

        return $return;
    }

    
   

}

