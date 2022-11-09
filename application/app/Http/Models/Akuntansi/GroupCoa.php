<?php

namespace App\Http\Models\Akuntansi;

use Illuminate\Database\Eloquent\Model;
use DB;

class GroupCoa extends Model
{
    protected $table = 'ak_group_coa';
    public $timestamps = false;

    protected $primaryKey = 'group_coa_id';

    protected $fillable = [
		'group_coa_type','code','desc'
    ];

    public function coa()
    {
        return $this->hasMany('Coa');
    }

    
}