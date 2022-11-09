<?php

namespace App\Http\Models\Akuntansi;

use App\Http\Models\Akuntansi\DepreciationAssets;
use App\Http\Models\Akuntansi\HeaderJurnals;

use Illuminate\Database\Eloquent\Model;
use DB;

class TriggerPenyusutan extends Model
{

    public static function getDataKalkulasi($periode){
       $query = "select 
                    temp.*,
                    (harga) * (tarif/100) / 12 as akm_penyusutan,
                    ((harga) * (tarif/100) / 12) * jumlah as total_akm_penyusutan,
                    total - (total_penyusutan + (((harga) * (tarif/100) / 12) * jumlah) )sisa_nilai_buku
                from 
                    (select 
                                    a.id, c.coa_id, a.beban_penyusutan_id,
                                    ca.code coa_beban_penyusutan,
                                    a.akm_penyusutan_id, cb.code coa_akm_penyusutan,
                                    c.code, c.desc, a.nama, 
                                    b.tgl_pembelian, 
                                    a.harga, a.jumlah, a.tarif, a.total, 
                                    a.masa_manfaat, akm.periode_akhir as periode_akhir, 
                                    cast( 
                                        FLOOR(DATEDIFF( (b.tgl_pembelian + interval a.masa_manfaat YEAR), (b.tgl_pembelian + interval ifnull(akm.jml_bln_penyutuan,0)+1 MONTH) )/365)+
                                        mod(TIMESTAMPDIFF(MONTH, (b.tgl_pembelian + interval ifnull(akm.jml_bln_penyutuan,0)+1 MONTH), (b.tgl_pembelian + interval a.masa_manfaat YEAR)),12 )/100 as decimal(5,2))sisa_masa_manfaat,
                                    ifnull(akm.total_penyusutan,0) total_penyusutan, 
                                    a.nilai_residu,
                                    (a.total - ifnull(akm.total_penyusutan,0)) as nilai_buku 
                    from aset_details a
                    inner join asets b on b.id = a.aset_id and b.status = 1
                    inner join ak_coa c on c.coa_id = a.coa_id
                    inner join ak_coa ca on ca.coa_id = a.beban_penyusutan_id
                    inner join ak_coa cb on cb.coa_id = a.akm_penyusutan_id
                    left join ( SELECT x.aset_detail_id, max(cast(concat(right(x.periode, 4), left(x.periode,2)) as int))periode_akhir, sum(total_akm_penyusutan) total_penyusutan, count(*)jml_bln_penyutuan
                                                                                                                                                    FROM ak_depreciation_assets x
                                                                                                                                                    group by x.aset_detail_id
                                            )akm on akm.aset_detail_id = a.id
                    where a.status = 1 and
                                cast(DATE_FORMAT(b.tgl_pembelian, '%Y%m') as int) <= cast(concat(right(?,4), left(?,2)) as int)
                )temp
                where total_penyusutan + nilai_residu < total";

        $response = collect(DB::select($query, array($periode, $periode)));
        return $response;
    }

    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param string foramt yyyy-mm-dd date periodeDate 
     * @param int $user_id id user yang sedang merubah atau melakukan entry
     * 
     * @return array 
     */
    public static function KalkulasiPenyusutanPerpeiode($periodeDate, $user_id){
        $response['response'] = false;
        $response['message']  = "";

        $paramPeriod = date('Ym', strtotime($periodeDate));
        if(DepreciationAssets::getValidPostingAset($paramPeriod)){
            try{
                // Hapus jurnal Dan Akumulasi penyusutan
                    $q = "delete ak_journal_details, ak_journal_headers from ak_journal_details
                            inner join ak_journal_headers on ak_journal_details.journal_header_id = ak_journal_headers.journal_header_id
                            where ak_journal_headers.is_trigger = 1 and ak_journal_headers.tigger_table = 'ak_depreciation_assets' and ak_journal_headers.posting_no is null
                                        and ak_journal_headers.tigger_table_key_id in (select akm.penyusutan_id from ak_depreciation_assets akm where cast(CONCAT(right(akm.periode,4),left(akm.periode,2)) as int) = ?)";
                    $deleteJurnal = DB::delete($q, array($paramPeriod));
                    

                    DepreciationAssets::whereRaw('cast(CONCAT(right(periode,4),left(periode,2)) as int) = ?', array($paramPeriod))
                                    ->delete();
                // End Hapus jurnal Dan Akumulasi penyusutan

                
                // Kalkulasi Ulang
                $periodesss = date('mY', strtotime($periodeDate));
                $dataKalkulasi = TriggerPenyusutan::getDataKalkulasi($periodesss);
                foreach($dataKalkulasi as $rws){
                    $akmPenyusutan = new DepreciationAssets();
                    $akmPenyusutan->aset_detail_id      = $rws->id;
                    $akmPenyusutan->periode             = date('mY', strtotime($periodeDate));
                    $akmPenyusutan->tarif               = $rws->tarif;
                    $akmPenyusutan->sisa_masa_manfaat   = $rws->sisa_masa_manfaat;
                    $akmPenyusutan->harga               = $rws->harga;
                    $akmPenyusutan->jumlah              = $rws->jumlah;
                    $akmPenyusutan->akm_penyusutan      = $rws->akm_penyusutan;
                    $akmPenyusutan->total_akm_penyusutan      = $rws->total_akm_penyusutan;
                    $akmPenyusutan->sisa_nilai_buku      = $rws->sisa_nilai_buku;
                    $akmPenyusutan->created_at = date('Y-m-d H:m:i');
                    $akmPenyusutan->created_by = $user_id;
                    if($akmPenyusutan->save()){

                        $jrType = 'JRR';
                        $journalNo = HeaderJurnals::generateNoJurnal('3', $jrType);
                        $mheader = new HeaderJurnals();
                        
                        $mheader->division = 'UM';
                        $mheader->transaction_type_id = 3;
                        $mheader->type = $jrType;
                        $mheader->journal_no = $journalNo;
                        $mheader->tr_date = date('Y-m-d');
                        $mheader->desc = 'Penyusutan Aset';
                        $mheader->total = $akmPenyusutan->total_akm_penyusutan;
                        $mheader->is_trigger = 1;
                        $mheader->tigger_table = 'ak_depreciation_assets';
                        $mheader->tigger_table_key_id = $akmPenyusutan->penyusutan_id;
                        $mheader->created_at = date('Y-m-d H:m:i');
                        $mheader->created_by = $user_id;
                        if($mheader->save()){

                            $arrDetail = [];
                            //Entry Journal Debit Kredit
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$rws->coa_beban_penyusutan."')") , 'coa_code' => $rws->coa_beban_penyusutan, 'seq' => 1, 'debit' => $rws->total_akm_penyusutan,'kredit' => 0, 'created_by' => $user_id);
                            $arrDetail[] = array('journal_header_id' => $mheader->journal_header_id, 'journal_header_type' => $mheader->type, 'coa_id' => DB::raw("(select coa_id from ak_coa where code = '".$rws->coa_akm_penyusutan."')") , 'coa_code' => $rws->coa_akm_penyusutan, 'seq' => 2, 'debit' => 0,'kredit' => $rws->total_akm_penyusutan, 'created_by' => $user_id );
                            DetailJurnals::insert($arrDetail);
                        }
                    }


                }
                // End Kalkulasi Ulang
                
                DB::commit();
                    
                $response['responses'] = true;
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
        else{
            $response['message']  = "Penyusutan periode ini sudah terposting.";
        }


        return $response;
    }

}
