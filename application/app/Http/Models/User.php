<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

use DB;

class User extends Model
{
    protected $table = 'users';

    // protected $primaryKey = 'username';

    public static function getLevelUsers($level_id){
        $data = collect(DB::select("select * from levels where id = ?", [$level_id]))->first();

        return $data;
    }

}
