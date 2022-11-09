<?php

namespace App\Http\Models\Akuntansi;

use App\Http\Models\Bank;
use Illuminate\Database\Eloquent\Model;
use DB;

class CompanyBankAccount extends Model
{
    protected $table = 'company_bank_accounts';
    public $timestamps = false;

    protected $primaryKey = 'bank_account_id';

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'at_coa_id');
    }

    public static function getCoaCodeByDivisi($Divisi){
        $query = CompanyBankAccount::select(DB::raw("(select c.code from ak_coa c where c.coa_id = company_bank_accounts.at_coa_id)coa_code "))
                            ->where("divisi", $Divisi)
                            ->first();
        
        return $query;
    }

}
