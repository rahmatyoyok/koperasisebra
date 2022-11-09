<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB, Auth;

class LoanApprovals extends Model
{
    protected $table = 'sp_loan_approvals';
    public $timestamps = false;

    protected $primaryKey = 'loan_approval_id';

    protected $fillable = [
		'loan_id','person_id', 'desc','approval_date','level_id',
        'status', 'is_deleted', 'created_at', 'created_by','updated_at', 'updated_by'
    ];

    public function loan()
    {
        return $this->belongsTo(Loans::class, 'loan_id');
    }

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'person_id');
    }

    public static function getCountNotif($transactionType = 1){
        $userLevel = Auth::user()->level_id;
        $return = collect(DB::select('select count(*)count_notif
                                        from sp_loans a
                                        left join (select @rownum_a := case when @groupnum_a = apr_loan.loan_id then @rownum_a + 1 else 1 end AS rank, IFNULL(apr_loan.level_id,0)lvl_id, apr_loan.*, @groupnum_a := apr_loan.loan_id
                                        from sp_loan_approvals apr_loan, (SELECT @rownum_a := 1, @groupnum_a:=0) r1 order by apr_loan.approval_date DESC, apr_loan.loan_approval_id desc)apr on apr.loan_id = a.loan_id and apr.rank = 1
                                        where
                                            a.is_deleted = 0 and
                                            a.transaction_type_id = ? and
                                            (
                                                (9 = ? /*bendahara*/ and apr.lvl_id is null and a.status = 0) or
                                                (7 = ? /*ketua*/ and apr.lvl_id = 9 and a.status = 0) or
                                                (10 = ? /*manajer*/ and apr.lvl_id = 7 and a.status = 0) or
                                                (11 = ? /*Staff All*/ and apr.lvl_id = 7 and a.status = 0)or
                                                (12 = ? /*Staff SimpanPinjam*/ and apr.lvl_id = 7 and a.status = 0)
                                            )', array($transactionType, $userLevel, $userLevel, $userLevel, $userLevel, $userLevel)));
        return $return->first()->count_notif;
    }
    
    public static function getlastAppoval($loan_id){
        $query = collect(DB::select('select * from 
                                        (select @rownum_a := case when @groupnum_a = apr_loan.loan_id then @rownum_a + 1 else 1 end AS rank, IFNULL(apr_loan.level_id,0)lvl_id, apr_loan.*, @groupnum_a := apr_loan.loan_id
                                        from (SELECT @rownum_a := 1, @groupnum_a:=0) r1, sp_loan_approvals apr_loan
                                            where apr_loan.loan_id = ?
                                        order by apr_loan.approval_date DESC, apr_loan.loan_approval_id desc)x where rank = 1',array($loan_id)));
                                    
        return $query->first();
    }

    public static function setReaded($data){
        foreach($data as $r){
            $zd = LoanApprovals::find($r->loan_approval_id);
            $zd->status_desc = '{'.str_replace('{','',str_replace('}','',$r->valueStatus)).'}';
            $zd->save();
        }
        return true;
    }

}