<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB;

class KonfigurasiSimpanan extends Model
{
    protected $table = 'sp_saving_config';
    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
		'simpanan_pokok', 'simpanan_wajib' ,'bunga_investasi',  'status'
    ];

}