<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB;

class KonfigurasiPinjaman extends Model
{
    protected $table = 'sp_loan_config';
    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'batas_pinjaman', 'biaya_administrasi_persentase' ,'biaya_administrasi_rupiah',  'biaya_provisi_persentase',  'biaya_provisi_rupiah',  'resiko_daperma', 'biaya_materai', 'biaya_lain', 'denda_cicilan',
        'status', 'created_at', 'created_by', 'updated_at', 'updated_by'
    ];

}