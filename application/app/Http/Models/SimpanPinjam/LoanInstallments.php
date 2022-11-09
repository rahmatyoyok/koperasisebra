<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB;

class LoanInstallments extends Model
{
    protected $table = 'sp_loan_installments';
    public $timestamps = false;

    protected $primaryKey = 'loan_detail_id';

    protected $fillable = [
        'loan_id', 'periode', 'seq_number', 'ref_code','principal_amount', 'rates_amount', 'attachment',  'company_bank_id',  'payment_ref','payment_method','payment_date',
        'status', 'is_deleted', 'created_at', 'created_by','updated_at', 'updated_by'
    ];

    public function pinjaman()
    {
        return $this->belongsTo(Loans::class, 'person_id');
    }

    public static function GetDataAngsuranByid($loanId){
     
        $query = LoanInstallments::select()->addSelect(DB::raw('sp_loan_installments.status status_bayar'))->join('ak_transaction_types', 'sp_loan_installments.payment_method',"=", "ak_transaction_types.transaction_type_id")
                    ->where("loan_id",$loanId)->orderBy(DB::raw('cast(periode as int)'));
        return $query;
    }

}