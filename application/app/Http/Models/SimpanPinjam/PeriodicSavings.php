<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB;

class PeriodicSavings extends Model
{
    protected $table = 'sp_periodic_savings';
    public $timestamps = false;

    protected $primaryKey = 'periodic_saving_id';

    protected $fillable = [
		'transaction_type','person_id', 'periode', 'ref_code' ,'tr_date',  'payment_ref', 'total', 'payment_method', 'payment_date',
        'status', 'is_deleted', 'created_at', 'created_by','updated_at', 'updated_by'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'person_id');
    }

    public static function getListSimpananWajib($arrayParam = array(), $statement = '')
    {
        $return = PeriodicSavings::with('anggota')->with('anggota.customer')
                    ->select(['person_id', DB::raw('max(tr_date) as tr_date'),  DB::raw(' (select sum(a.total) from sp_periodic_savings a where a.person_id = sp_periodic_savings.person_id and a.status = 2) as total')])
                    ->where('is_deleted','0')
                    ->groupBy('person_id');

        foreach($arrayParam as $key=>$val){
            $return->where($key, $val);
        }

        if(strlen($statement) > 0)
            $return->whereRaw($statement);
        
        return $return->get();
    }

    public static function checkKalkulasi($periode){
        return PeriodicSavings::where('periode',$periode)->where('status', 'in', '(1, 2)')->count();
    }

    public static function kalkulasiSpWajib($user_id, $periode){
        DB::beginTransaction();
        try{
            $dataDelete = PeriodicSavings::where('periode',$periode)->where('status', 3)->delete();
            $querySelect = DB::table("ospos_people AS p")
                            ->select(DB::raw("1 tr_type, p.person_id, '".$periode."' as periode, DATE_FORMAT(NOW(), '%Y-%m-%d')tnggl,
                            (select simpanan_wajib from sp_saving_config sp where sp.status = 1)total,
                            2 pymnt, 3 status, now() created_at, ".$user_id))
                            ->whereRaw("p.member_type = 1 and p.status = 1 and p.is_deleted= 0");
            
            insertFromSelectStatement(PeriodicSavings::class, ['transaction_type','person_id', 'periode', 'tr_date', 'total', 'payment_method', 'status', 'created_at', 'created_by'], $querySelect );
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollback();
            return false;
            die();
        }
    }

    public static function postingKalkulasi($user_id, $periode){
        DB::beginTransaction();
        try{
            $dataDelete = PeriodicSavings::where('periode',$periode)->where('status', 3)->update(['status' => 2, 'updated_by' => $user_id]);
            DB::commit();
            return true;
        }catch(\Exception $e){
            DB::rollback();
            return false;
            die();
        }
    }

    public static function getByIdSimpananWajib($id)
    {
        $return = PeriodicSavings::with('anggota')->with('anggota.customer')->find($id);
        return $return;
    }

}