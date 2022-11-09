<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB, Auth;

class InvestmentSavingApprovals extends Model
{
    protected $table = 'sp_investment_saving_approvals';
    public $timestamps = false;

    protected $primaryKey = 'investment_saving_approval_id';

    protected $fillable = [
		'investment_saving_id','person_id', 'desc','approval_date', 'approval_by', 'level_id',
        'status', 'status_desc', 'is_deleted', 'created_at', 'created_by','updated_at', 'updated_by'
    ];

    public function investasi()
    {
        return $this->belongsTo(InvestmentSavings::class, 'investment_saving_id');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'person_id');
    }

    public static function getlastAppoval($investentid){
        $query = collect(DB::select('select * from 
                                    (select @rownum_a:= case when @groupnum_a = apr_inv.investment_saving_id then @rownum_a + 1 else 1 end AS rank, IFNULL(apr_inv.level_id,0)lvl_id, apr_inv.*, @groupnum_a := apr_inv.investment_saving_id
                                    from (SELECT @rownum_a := 1, @groupnum_a:=0) r1, sp_investment_saving_approvals apr_inv 
                                    where apr_inv.investment_saving_id = ?
                                    order by apr_inv.approval_date DESC, apr_inv.investment_saving_approval_id desc)x where rank = 1',array($investentid)));
                                    
        return $query->first();
    }

    public static function getCountNotif($transactionType = 1){
        $userLevel = Auth::user()->level_id;
        
        $return = collect(DB::select('select count(*)count_notif
                                        from sp_investment_savings a
                                        left join (select @rownum_a:= case when @groupnum_a = apr_inv.investment_saving_id then @rownum_a + 1 else 1 end AS rank, IFNULL(apr_inv.level_id,0)lvl_id, apr_inv.*, @groupnum_a := apr_inv.investment_saving_id
                                        from sp_investment_saving_approvals apr_inv, (SELECT @rownum_a := 1, @groupnum_a:=0) r1 order by apr_inv.approval_date DESC, apr_inv.investment_saving_approval_id desc)apr on apr.investment_saving_id = a.investment_saving_id and apr.rank = 1
                                        where
                                            a.is_deleted = 0 and
                                            a.transaction_type = ? and
                                            (
                                                (9 = ? /*bendahara*/ and apr.lvl_id is null and a.status = 0) or
                                                (8 = ? /*wakil*/ and apr.lvl_id = 9 and a.status = 0 and apr.status = 1) or
                                                (7 = ? /*ketua*/ and apr.lvl_id = 8 and a.status = 0 and apr.status = 1) or
                                                (10 = ? /*manajer*/ and apr.lvl_id = 7 and a.status = 0 and apr.status = 1) or
                                                (11 = ? /*Staff All*/ and apr.lvl_id = 7 and a.status = 0 and apr.status = 1)or
                                                (12 = ? /*Staff SimpanPinjam*/ and apr.lvl_id = 7 and a.status = 0 and apr.status = 1)
                                            )', array($transactionType, $userLevel, $userLevel, $userLevel, $userLevel, $userLevel, $userLevel)));
        return $return->first()->count_notif;
    }

    public static function setReaded($data){
        foreach($data as $r){
            $zd = InvestmentSavingApprovals::find($r->investment_saving_approval_id);
            $zd->status_desc = '{'.str_replace('{','',str_replace('}','',$r->valueStatus)).'}';
            $zd->save();
        }
        return true;
    }

    
}