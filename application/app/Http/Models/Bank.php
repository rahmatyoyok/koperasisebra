<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;


class Bank extends Model
{
	protected $table = 'banks';
    public $timestamps = false;
	protected $primaryKey = 'bank_id';

    protected $fillable = [
		    'nama_bank'
	];
	

}
