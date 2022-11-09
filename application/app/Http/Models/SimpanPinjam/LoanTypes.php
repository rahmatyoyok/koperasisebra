<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB;

class LoanTypes extends Model
{
    protected $table = 'sp_loan_types';
    public $timestamps = false;

    protected $primaryKey = 'loan_type_id';

    protected $fillable = [
		'code','name','interest_rates','tenure','interest_type',
        'status', 'is_deleted', 'created_at', 'created_by','updated_at', 'updated_by'
    ];

    public static function dueType()
	{
		return array(
            // 1 => 'Hari',
            // 2 => 'Minggu',
            3 => 'Bulan',
            // 4 => 'Tahun'
        );
	}

    public static function getListLoanType(){
        $return = LoanTypes::select('*')->addSelect(DB::raw('concat(name, " - Bunga (",floor(interest_rates*100),"%)")as name_label'))
                        ->where('status',1)->orderBy('loan_type_id');
        return $return;
    }

    


}