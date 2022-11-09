<?php

namespace App\Http\Controllers\App\Pengaturan;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;

use Yajra\Datatables\Facades\Datatables;
use App\Http\Models\Pengaturan\Level;
use DB;

class LevelController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $levels = Level::select('id', 'name', 'description', 'created_at');
            $datatables = Datatables::of($levels)
                ->addColumn('action', function ($level) {
                    return
                        '<a href="'.url('pengaturan/level/'.$level->id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>'
                        .\Form::open([ 'method'  => 'delete', 'route' => [ 'level.destroy', $level->id ], 'style' => 'display: inline-block;' ]).
                        '<button class="btn btn-xs red-haze dt-btn tooltips" data-swa-text="Hapus level '.$level->name.'?" title="Hapus"><i class="glyphicon glyphicon-trash"></i></button>'
                        .\Form::close();
                });
            return $datatables->make(true);
        }
        return view('pengaturan.level.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pengaturan.level.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);
        DB::beginTransaction();
        try
        {
            $level = new Level();
            $level->name = $request->name;
            $level->description = $request->description;
            $level->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Level berhasil ditambah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Gagal!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('pengaturan/level');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function show($id)
    {
        return view('errors.404');
    }*/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data['level'] = Level::findOrFail($id);
        return view('pengaturan.level.edit', $data);
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
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'max:255',
        ]);
        DB::beginTransaction();
        try
        {
            $level = Level::findOrFail($id);
            $level->name = $request->name;
            $level->description = $request->description;
            $level->save();
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Level berhasil diubah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Gagal!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('pengaturan/level');
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
            Level::destroy($id);
            DB::commit();
            notify()->flash('Sukses!', 'success', [
                'text' => 'Level berhasil dihapus',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Gagal!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('pengaturan/level');
    }
}
