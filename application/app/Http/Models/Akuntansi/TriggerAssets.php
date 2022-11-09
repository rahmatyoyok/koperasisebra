<?php

namespace App\Http\Models\Akuntansi;

use App\Http\Models\Akuntansi\DepreciationAssets;
use App\Http\Models\AsetDetail;
use App\Http\Models\Aset;
use App\Http\Models\Akuntansi\HeaderJurnals;

use Illuminate\Database\Eloquent\Model;
use DB;

class TriggerAssets extends Model
{
    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param int aset_detail_id 
     * @param int $user_id id user yang sedang merubah atau melakukan entry
     * 
     * @return array 
     */
    public static function jurnalTransaksi($asd_id, $user_id){
        $return = false;

        $model = Aset::where('id', $asd_id)
                        ->where('status', 1)
                        ->first();
            
            if($model){
                try{
                    $coakredit  = '1000101/UM';

                    $metodpembyaran = 2;
                    $jurnalType = "JKK";
                    $journalJrNo    = HeaderJurnals::generateNoJurnal($metodpembyaran, $jurnalType);
                    $mheaderjr      = new HeaderJurnals();

                    $mheaderjr->division = 'UM';
                    $mheaderjr->transaction_type_id = $metodpembyaran;
                    $mheaderjr->type = $jurnalType;
                    $mheaderjr->journal_no = $journalJrNo;
                    $mheaderjr->reff_no = $model->kode_aset;
                    $mheaderjr->tr_date =  $model->tgl_pembelian;
                    $mheaderjr->desc = 'Transaksi Pembelian Aset';
                    $mheaderjr->total = $model->total;
                    $mheaderjr->is_trigger = 1;
                    $mheaderjr->tigger_table = 'asets';
                    $mheaderjr->tigger_table_key_id = $model->id;
                    $mheaderjr->created_at = date('Y-m-d H:m:i');
                    $mheaderjr->created_by = $user_id;
                
                    if($mheaderjr->save()){
                        $arrDetail = [];
                        $modeld = AsetDetail::select(DB::raw('aset_details.*, ak_coa.code, ak_coa.desc'))
                                            ->join('ak_coa', function ($join) {
                                                $join->on('ak_coa.coa_id', '=', 'aset_details.coa_id');
                                            })
                                            ->where('aset_id', $model->id)
                                            ->get();
                        
                        $sq = 1;
                        //Entry Journal Debit Kredit
                        foreach($modeld as $rws){
                            /* Debit */
                            $coaDebit = $rws->code;
                            $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coaDebit."')") , 'coa_code' => $coaDebit, 'seq' => $sq, 'debit' => $rws->total, 'kredit' => 0, 'created_by' => $user_id);
                            
                            $sq++;
                        }

                        /* Kredit */
                        $arrDetail[] = array('journal_header_id' => $mheaderjr->journal_header_id, 'journal_header_type' => $mheaderjr->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$coakredit."')") , 'coa_code' => $coakredit, 'seq' => $sq, 'debit' => 0, 'kredit' => $model->total, 'created_by' => $user_id);
                        DetailJurnals::insert($arrDetail);

                    }

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
            
        return $return;
    }
}