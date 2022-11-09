<?php

namespace App\Http\Models\Pengaturan;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
        'menu_id', 'level_id'
    ];

    public function level()
    {
    	return $this->belongsTo(Level::class, 'level_id');
    }

    public function menu()
    {
    	return $this->belongsTo(Menu::class, 'menu_id');
    }
}
