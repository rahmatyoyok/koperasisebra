<?php

namespace App\Http\Models\Akuntansi;

use App\Http\Models\WorkOrder;

use Illuminate\Database\Eloquent\Model;
use DB;

class TriggerInvesatasi extends Model
{
    /**
     * Trigger pembuatan jurnal akuntansi 
     * 
     * @param int $Investid
     * @param int $user_id id user yang sedang merubah atau melakukan entry
     * 
     * @return int 
     */
    public static function perubahanStatus($woId, $user_id){

    }
}