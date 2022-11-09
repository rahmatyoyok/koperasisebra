<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class AsetDetail extends Model
{
	protected $table = 'aset_details';
    protected $primaryKey = 'id';

	public static function getDataAsetDetail($id){
		$queryDetail = collect(DB::select("Select ad.*, ac.code, ac.desc,
                                  bc.code code_beban_penyusutan,
                                  cc.code code_akm_penyusutan,
                                  bc.desc desc_beban_penyusutan,
								  cc.desc desc_akm_penyusutan,
								  ast.tgl_pembelian,
								  ast.no_kwitansi,
								  kode_aset
							  FROM aset_details ad
							  inner join asets ast on ast.id = ad.aset_id
                              inner join ak_coa ac on ac.coa_id = ad.coa_id
                              inner join ak_coa bc on bc.coa_id = ad.beban_penyusutan_id
                              inner join ak_coa cc on cc.coa_id = ad.akm_penyusutan_id
                      WHERE ad.aset_id = $id
					  "))->first();
		return $queryDetail;
	}
}
