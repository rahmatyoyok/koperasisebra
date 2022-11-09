<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB;

class RandomSeq extends Model
{
    protected $table = 'seq_rd';
    public $timestamps = false;

    protected $primaryKey = 'seq_id';

    protected $fillable = [
		'name', 'last_number'
    ];

    public static function getSeq($seqName, $max = 999999)
    {
        $ls = 1;
        $lastnum = RandomSeq::where('name', $seqName)->first();
        if(!$lastnum){
            $nw = new RandomSeq();
            $nw->name = $seqName;
            $nw->last_number = $ls;
            $nw->save();
        }
        else{
            if($lastnum->last_number == $max){
                $lastnum->last_number = 0;
            }

            $ls = $lastnum->last_number + 1;
            $lastnum->last_number = $ls;
            $lastnum->save();
        }
        
        return $ls;
    }

    public static function newSeqJurnal(){
        $dt = date('Ym');
        $query = collect(DB::select("select ifnull(last_seq, 0)+1 new_seq from (
                    select max(cast(right(journal_no,6) as int)) last_seq from ak_journal_headers
                    where DATE_FORMAT(created_at, '%Y%m') = ?)x", array($dt)))->first();
        // dd($query);
        return $query->new_seq; 

    }
}