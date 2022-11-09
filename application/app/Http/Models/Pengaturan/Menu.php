<?php

namespace App\Http\Models\Pengaturan;

use Illuminate\Database\Eloquent\Model;

use DB;

class Menu extends Model
{
    protected $table = 'menus';
    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $fillable = [
		'name', 'link', 'parent_id', 'icon', 'is_heading', 'no_urut'
    ];

    public function children()
    {
        $permissions = Permission::where('level_id', \Auth::user()->level_id)->pluck('menu_id');
    	return $this->hasMany($this, 'parent_id')
            ->whereIn('id', $permissions)
            ->orderBy('no_urut', 'asc');
    }

    public function childrens()
    {
        return $this->children()->with('childrens');
    }

    public function allchildren()
    {
        return $this->hasMany($this, 'parent_id')->orderBy('no_urut', 'asc');
    }

    public function allchildrens()
    {
        return $this->allchildren()->with('allchildrens');
    }

    public static function getAllMenus()
    {
        $menus = Menu::with('allchildrens')
                ->where('parent_id', 0)
                ->orderBy('no_urut', 'asc')
                ->get();
        return $menus;
    }

    public static function getMenus($level)
    {
    	$permissions = Permission::where('level_id', $level)->pluck('menu_id');
        $menus = Menu::with('childrens')
        		->where('parent_id', 0)
        		->whereIn('id', $permissions)
                ->orWhere('is_heading', 1)
        		->orderBy('no_urut', 'asc')
                ->get();
        return $menus;
    }

    public static function getTitle($requestPath)
    {
        DB::enableQueryLog();
        $menu = DB::table('menus')->select('name')
                ->join(DB::raw("(SELECT max(link) maxlink FROM menus WHERE link <= ?) m1"), function($join)
                    {
                        $join->on('link', '=', 'm1.maxlink');
                    })
                ->setBindings([$requestPath])
                ->first();

                
//$query = DB::getQueryLog();
//print_r($query);
        return isset($menu->name) ? $menu->name : null;
    }

    public static function isValid($requestPath = null, $level = -1)
    {
        $status = false;
        // $id = Menu::select('id')->where('link', 'like', '%' . $requestPath . '%')->first();
        $id = DB::table('menus')->select('id')
            ->join(DB::raw("(SELECT max(link) maxlink FROM menus WHERE link <= ?) m1"), function($join)
                {
                    $join->on('link', '=', 'm1.maxlink');
                })
            ->setBindings([$requestPath])
            ->first();

        if($id)
        {
            $id = $id->id;
            $permissions = Permission::select('level_id')->where('menu_id', $id)->where('level_id', $level)->first();
            if($permissions)
                $status = true;
        }
        
        return $status;
    }
}
