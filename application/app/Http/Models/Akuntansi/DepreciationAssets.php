<?php

namespace App\Http\Models\Akuntansi;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\AsetDetail;
use DB;

class DepreciationAssets extends Model
{
    protected $table = 'ak_depreciation_assets';
    public $timestamps = false;

    protected $primaryKey = 'penyusutan_id';

    protected $fillable = [
          'aset_detail_id','periode','tarif','sisa_masa_manfaat','harga','jumlah','akm_penyusutan','total_akm_penyusutan','sisa_nilai_buku',
          'created_at','created_by','updated_at','updated_by'
    ];

    public static function getListPenyusutan(){
        $query  = "select 
                        a.id, concat(c.code, ' - ', c.desc)coa , a.nama, b.tgl_pembelian, a.total, a.masa_manfaat, concat(right(akm.periode_akhir, 2), left(akm.periode_akhir,4)) as periode_akhir, 
                        concat( FLOOR(DATEDIFF( (b.tgl_pembelian + interval a.masa_manfaat YEAR), (b.tgl_pembelian + interval ifnull(akm.jml_bln_penyutuan,0) MONTH) )/365), ',', 
                                        mod(TIMESTAMPDIFF(MONTH, (b.tgl_pembelian + interval ifnull(akm.jml_bln_penyutuan,0) MONTH), (b.tgl_pembelian + interval a.masa_manfaat YEAR)),12 )
                                        )as sisa_masa_manfaat,
                        ifnull(akm.total_penyusutan,0) total_penyusutan, 
                        (a.total - ifnull(akm.total_penyusutan,0)) as nilai_buku 
                    from aset_details a
                    inner join asets b on b.id = a.aset_id and b.status = 1
                    inner join ak_coa c on c.coa_id = a.coa_id
                    left join ( SELECT x.aset_detail_id, max(cast(concat(right(x.periode, 4), left(x.periode,2)) as int))periode_akhir, sum(total_akm_penyusutan) total_penyusutan, count(*)jml_bln_penyutuan
                                                    FROM ak_depreciation_assets x
                                                    group by x.aset_detail_id
                    )akm on akm.aset_detail_id = a.id
                    where 
                            a.status = 1
                    order by nilai_buku desc, tgl_pembelian";

        $return = collect(DB::select($query));
        return $return;
        
    }

    /**
     * string param yyyymm 
     * 
     * reurn boolean
     */
    public static function getValidPostingAset($periode){

        $wquery = "select count(*)valid_cn from ak_depreciation_assets akm
                    inner join ak_journal_headers jr on jr.is_trigger = 1 and jr.tigger_table = 'ak_depreciation_assets' and jr.posting_no is not null and jr.tigger_table_key_id = akm.penyusutan_id
                    where cast(CONCAT(right(akm.periode,4),left(akm.periode,2)) as int) = ?";
        $response = collect(DB::select($wquery, array($periode)))->first()->valid_cn;

        return ($response == 0) ? true : false; 
    }


    public static function getlistDetailPenyusutan($asset_detail_id){
        $data = DepreciationAssets::select()
                            ->join('ak_journal_headers', function ($join) {
                                $join->on('ak_depreciation_assets.penyusutan_id', '=', 'ak_journal_headers.tigger_table_key_id')
                                    ->where('ak_journal_headers.tigger_table', 'ak_depreciation_assets');
                            })
                            ->join('aset_details', function ($join) {
                                $join->on('ak_depreciation_assets.aset_detail_id', '=', 'aset_details.id')
                                     ->where('aset_details.status', '=', 1);
                            })
                            ->join('asets', function ($join) {
                                $join->on('aset_details.aset_id', '=', 'asets.id')
                                     ->where('asets.status', '=', 1);
                            })
                            ->where('aset_detail_id', $asset_detail_id)
                            ->orderByRaw("cast(right(ak_depreciation_assets.periode,4) as int) desc, cast(left(ak_depreciation_assets.periode,2) as int) desc")
                            ->get();
        
        return $data;
    }
    
}