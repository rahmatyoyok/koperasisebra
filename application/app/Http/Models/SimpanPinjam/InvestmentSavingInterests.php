<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB;

class InvestmentSavingInterests extends Model
{
    protected $table = 'sp_investment_interests';
    public $timestamps = false;

    protected $primaryKey = 'investment_interest_id';

    protected $fillable = [
        'person_id', 'saving_config_id','ref_code' ,'tr_date',  'person_bank_id', 'rekening_no','saldo',
        'pr_bunga_investasi','pr_biaya_administrasi', 'bunga_investasi', 'biaya_administrasi', 'jumlah_transfer', 
        'payment_date', 'attacment',
        'status', 'is_deleted', 'created_at', 'created_by','updated_at', 'updated_by'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'person_id');
    }

}