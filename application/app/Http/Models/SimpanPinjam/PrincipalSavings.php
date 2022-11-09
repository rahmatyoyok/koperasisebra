<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB;

class PrincipalSavings extends Model
{
    protected $table = 'sp_principal_savings';
    public $timestamps = false;

    protected $primaryKey = 'principal_saving_id';

    protected $fillable = [
		'person_id', 'ref_code' ,'tr_date',  'payment_ref', 'total', 'payment_method', 'payment_date','attachment',
        'status', 'is_deleted', 'created_at', 'created_by','updated_at', 'updated_by'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'person_id');
    }

    public static function getListSimpananPokok()
    {
        $return = PrincipalSavings::with('anggota')->with('anggota.customer')
                    ->select(['person_id', DB::raw('max(tr_date) as tr_date'),  'payment_ref', DB::raw('sum(total) as total')])
                    ->where('is_deleted','0')
                    ->groupBy('person_id')->get();
        return $return;
    }

    public static function getByIdSimpananPokok($id)
    {
        $return = PrincipalSavings::with('anggota')->with('anggota.customer')->find($id);
        return $return;
    }

    

}