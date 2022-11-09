<?php

namespace App\Http\Models\Akuntansi;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\SimpanPinjam\RandomSeq;
use DB;

class HeaderJurnals extends Model
{
    protected $table = 'ak_journal_headers';
    public $timestamps = false;

    protected $primaryKey = 'journal_header_id';

    protected $fillable = [
		  'transaction_type_id','division','type','journal_no','reff_no','tr_date','desc','fiscal_year','fiscal_month','payment_code','customer','posting_no','is_trigger','total','create_at','create_by'
    ];

    public function transactiontype()
    {
        return $this->belongsTo(AkTransactionTypes::class, 'transaction_type_id');
    }

    public function detailjurnal(){
        return $this->hasMany(DetailJurnals::class);
    }

    public static function generateNoJurnal($tr_type, $jurnal_type = 'JRR'){
        $alphabet   = array('A','B','C','D','E','F','G','H','I','J','N','O','P','Q');
        $crtanggal  = (int)date('m')-1;
        $trData     = AkTransactionTypes::findOrFail($tr_type)->code; 

        $seqName = 'R';
        switch($jurnal_type){
            case 'JKM' : $seqName = 'M' ; break;
            case 'JKK' : $seqName = 'K' ; break;
        }

        // sequance
        // $randomseq  = sprintf( '%06d', RandomSeq::getSeq($seqName,999999));
        $randomseq  = sprintf( '%06d', RandomSeq::newSeqJurnal());

        return $seqName.$alphabet[$crtanggal].date('y').$trData.$randomseq;
    }

    public static function getListJournal($tr_type = '')
    {

        $return = HeaderJurnals::with('transactiontype')
                    ->select(['division','journal_header_id', 'journal_no', 'type','reff_no','tr_date','desc','is_trigger', DB::raw('(select mtr.desc from ak_transaction_types mtr where mtr.transaction_type_id = ak_journal_headers.transaction_type_id )as tr_desc'),  DB::raw('(select sum(jr.debit) from ak_journal_details jr where jr.journal_header_id = ak_journal_headers.journal_header_id) as total ')]);
        
        if(strlen($tr_type)>0){ $return->where('type',$tr_type); }
        
        /*Filter*/
            if(strlen($tr_type)>0){ $return->where('type',$tr_type); }
        /*END Filter*/


        $return->where('is_deleted','0');
        // $return->orderBy(DB::raw('tr_date'),'desc');
                    
        // dd($return);
        return $return;
    }
    
}