<?php

namespace App\Http\Models\Akuntansi;

use Illuminate\Database\Eloquent\Model;
use DB;

class AkTransactionTypes extends Model
{
    protected $table = 'ak_transaction_types';
    public $timestamps = false;

    protected $primaryKey = 'transaction_type_id';

    protected $fillable = [
		  'code','desc','status'
    ];

    
}