<?php

namespace App\Http\Models\Akuntansi;

use Illuminate\Database\Eloquent\Model;
use DB;

class DetailJurnals extends Model
{
    protected $table = 'ak_journal_details';
    public $timestamps = false;

    protected $primaryKey = 'journal_detail_id';

    protected $fillable = [
		  'journal_header_id','journal_header_type','description','coa_id','coa_code','seq','debit','kredit','create_at','create_by'
    ];

    public function coas()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }

    public function headerjurnal()
    {
        return $this->belongsTo(HeaderJurnals::class, 'journal_header_id');
    }

    public static function getDetailJurnal($headerId){
        $query = DetailJurnals::with('coas')
                    ->where('journal_header_id', $headerId)
                    ->orderBy('seq')
                    ->get();
        
        return $query;
    }

}