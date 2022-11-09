<?php

namespace App\Http\Models\SimpanPinjam;

use Illuminate\Database\Eloquent\Model;
use DB;

class ResignForms extends Model
{
    protected $table = 'sp_resign_forms';
    public $timestamps = false;

    protected $primaryKey = 'resign_id';

    protected $fillable = [
		'person_id', 'resign_date' ,'approval_resign_date',  'approval_bendahara_id', 'approval_bendahara_desc', 'approval_ketua_id', 'approval_ketua_desc',
        'is_deleted', 'created_at', 'created_by', 'updated_at','updated_by', 'status'
    ];

    public function anggota()
    {
        return $this->belongsTo(Anggota::class, 'person_id');
    }

    public static function getListResignAnggota()
    {
        $return = ResignForms::with('anggota')->with('anggota.customer')->with('anggota.customer.bank')->where('status','<>',2)->get();
        // dd($return);
        return $return;
    }
}