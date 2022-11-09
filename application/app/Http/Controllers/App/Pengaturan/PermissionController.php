<?php

namespace App\Http\Controllers\App\Pengaturan;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;

use App\Http\Models\Pengaturan\Level;
use App\Http\Models\Pengaturan\Permission;
use App\Http\Models\Pengaturan\Menu;

use DB;

class PermissionController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['all_menus'] = Menu::getAllMenus();
        $data['level_id'] = Level::select('id', 'name')->get();
        $data['first_level_id'] = Level::select('id')->first();
        if(isset($data['first_level_id']))
        {
            $data['first_level_id'] = $data['first_level_id']->id;
        }
        else
        {
            $data['first_level_id'] = 0;
        }
        return view('pengaturan.hak-akses.index', $data);    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /*public function create()
    {
        return view('errors.404');
    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($request->ajax())
        {
            DB::beginTransaction();
            try 
            {
                $level_id = $request->level_id;
                Permission::where('level_id', $level_id)->delete();
                foreach ($request->menu_id as $item)
                {
                    $permission = new Permission();
                    $permission->level_id = $level_id;
                    $permission->menu_id = $item;
                    $permission->save();
                }
                DB::commit();
                notify()->flash('Success!', 'success', [
                    'text' => 'Hak akses berhasil diubah',
                ]);
            }
            catch (\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
            {
                DB::rollback();
                notify()->flash('Error!', 'error', [
                    'text' => $e->getMessage(),
                ]);
            }
            return url('pengaturan/hak-akses');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Permission::where('level_id', $id)->pluck('menu_id');
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function edit($id)
    {
        return view('errors.404');
    }*/

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function update(Request $request, $id)
    {
        return view('errors.404');
    }*/

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($id)
    {
        return view('errors.404');
    }*/
}
