<?php

namespace App\Http\Models\Pengaturan;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;
use App\Http\Models\Regencies;
class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    // protected $primaryKey = 'id';
    // public $incrementing = false;
    protected $dateFormat = 'Y-m-d H:i:s';

    protected $casts = [
        'is_active'	=> 'boolean',
        'is_heading' => 'boolean',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $fillable = [
        'username', 'email', 'password', 'name', 'level_id', 'is_active'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    public function level()
    {
    	return $this->belongsTo(Level::class, 'level_id');
    }

    public function regencies()
    {
    	return $this->belongsTo(Regencies::class, 'regencies');
    }

    public function unitorganisasi()
    {
        return $this->hasMany(\App\Http\Models\UserSubUnit::class, 'username');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->formatLocalized('%e %B %Y');
    }

    public function isSuperadmin()
    {
        if(auth()->user()->level_id == 1)
            return true;
        return false;
    }
}
