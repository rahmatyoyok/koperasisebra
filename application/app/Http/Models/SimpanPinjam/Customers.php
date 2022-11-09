<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use App\Http\Models\Bank;
use DB;

class Customers extends Model
{
    protected $table = 'ospos_customers';
    public $timestamps = false;

    protected $primaryKey = 'person_id';

    protected $fillable = [
		'company_name','bank_id','account_number','npwp','taxable','tax_id','discount','discount_type','deleted','employe_id','credit_limit','npwp'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'person_id');
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    
}