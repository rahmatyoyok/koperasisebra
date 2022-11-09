<?php

namespace App\Http\Controllers\App\Pengaturan;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;

use App\Http\Models\Pengaturan\Menu;
use DB;

class MenuController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['all_menus'] = Menu::getAllMenus();
        return view('pengaturan.menu.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data['parent_id'] = Menu::pluck('name', 'id')->prepend('Header', 0);
        return view('pengaturan.menu.create', $data);
    }

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
                $this->recursiveUpdate($request->menus, 0);
                DB::commit();
                notify()->flash('Sukses!', 'success', [
                    'text' => 'Struktur menu berhasil diubah',
                ]);
            }
            catch (\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
            {
                DB::rollback();
                notify()->flash('Error!', 'error', [
                    'text' => $e->getMessage(),
                ]);
            }
            return url('pengaturan/menu');
        }
        else
        {
            $rules = [
                'name' => 'required|max:50'
            ];

            $no_urut = DB::table('menus')->select(DB::raw('MAX(no_urut) AS res'))->where('parent_id', 0)->first()->res ?: 0;
            if(!$request->is_heading){
                $rules['link'] = 'required|no_spaces|max:255';
                $rules['parent_id'] = 'required';
                $rules['icon'] = 'max:50';
                $no_urut = DB::table('menus')->select(DB::raw('MAX(no_urut) AS res'))->where('parent_id', $request->parent_id)->first()->res ?: 0;
            }
            else{
                $request->merge([
                    'is_heading' => 1
                ]);
            }
            
            $request->merge([
                'no_urut' => $no_urut+1
            ]);

            $this->validate($request, $rules);

            DB::beginTransaction();
            try
            {
                Menu::create($request->all());
                DB::commit();
                notify()->flash('Sukses!', 'success', [
                    'text' => 'Menu berhasil ditambah',
                ]);
            }
            catch (\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
            {
                DB::rollback();
                notify()->flash('Error!', 'error', [
                    'text' => $e->getMessage(),
                ]);
            }
            return redirect('pengaturan/menu');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        //
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['menu'] = Menu::findOrFail($id);
        $data['parent_id'] = Menu::pluck('name', 'id')->prepend('Header', 0);
        return view('pengaturan.menu.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:50'
        ];

        if(!$request->is_heading){
            $rules['link'] = 'required|no_spaces|max:255';
            $rules['parent_id'] = 'required';
            $rules['icon'] = 'max:50';
        }
        else{
            $request->merge([
                'is_heading' => 1
            ]);
        }
        $this->validate($request, $rules);
        DB::beginTransaction();
        try
        {
            $menu = Menu::find($id)->update($request->all());
            /*$menu->name = $request->name;
            $menu->link = $request->link;
            $menu->icon = $request->icon;
            $menu->save();*/
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Menu berhasil diubah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('pengaturan/menu');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try
        {
            Menu::destroy($id);
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Menu berhasil dihapus',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('pengaturan/menu');
    }

    /**
     * Recursive update on menu
     */
    public function recursiveUpdate($menus = null, $parentId = 0)
    {
        foreach ($menus as $key => $item)
        {
            $menu = Menu::findOrFail($item['id']);
            $menu->parent_id = $parentId;
            $menu->no_urut = $key+1;
            $menu->save();
            if(isset($item['children']))
            {
                $this->recursiveUpdate($item['children'], $item['id']);
            }
        }
    }
}
