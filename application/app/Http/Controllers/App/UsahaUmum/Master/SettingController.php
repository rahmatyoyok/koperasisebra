<?php

namespace App\Http\Controllers\App\UsahaUmum\Master;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Yajra\Datatables\Facades\Datatables;

use App\Http\Models\SettingUsaha;
use DB, Form, Auth;

class SettingController extends AppController
{

    public function index(Request $request)
    {
        $data['data_edit'] = SettingUsaha::findOrFail(1);
        return view('usaha.master.setting.edit', $data);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [

        ]);

        DB::beginTransaction();
        try
        {
            $data = SettingUsaha::findOrFail(1);
            $data->ppn = $request->ppn;
            $data->pph22_npwp = $request->pph22_npwp;
            $data->pph22_non_npwp = $request->pph22_non_npwp;
            $data->pph23_npwp = $request->pph23_npwp;
            $data->pph23_non_npwp = $request->pph23_non_npwp;
            $data->levering_po = $request->levering_po;
            $data->due_date_persekot = $request->due_date_persekot;
            $data->margin_persekot = $request->margin_persekot;
            $data->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Pengaturan Usaha Umum berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('usaha/master/setting');
    }



}
