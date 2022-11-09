<?php

namespace App\Http\Models\Pengaturan;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
	use SoftDeletes;

    protected $table = 'levels';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name', 'description'
    ];

    public function user()
    {
    	return $this->hasMany(User::class, 'level_id');
    }

    public function permission()
    {
    	return $this->hasMany(Permission::class, 'level_id');
    }
}
