<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Http\Models\Supplier;
use App\Http\Models\SettingUsaha;
use App\Http\Models\WorkOrder;
use DB, Form, Auth,Response;

class UsahaController extends ApiController
{
	public function getInfoSupplier(Request $request)
    {
        $id = $request->get('kd');
        $jenis = $request->get('jenis');
        $supplier = Supplier::findOrFail($id);
        $setting = SettingUsaha::findOrFail(1);
        $data['supplier'] = $supplier;
        if($supplier->no_npwp_sup == "" || $supplier->no_npwp_sup == null ){
            if($jenis == "1"){
                //Material
                $data['pph'] = $setting->pph23_non_npwp;
            }else{
                //Jasa
                $data['pph'] = $setting->pph22_non_npwp;
            }
            
        }else{
            if($jenis == "1"){
                //Material
                $data['pph'] = $setting->pph23_npwp;
            }else{
                //Jasa
                $data['pph'] = $setting->pph22_npwp;
            }
        }
        
        return Response::json($data);
    }

    public function getInfoWO(Request $request)
    {
        $id = $request->get('kd');
        
        $wo = WorkOrder::findOrFail($id);
        $data['jenis_pekerjaan'] = jenisPekerjaan($wo->jenis_pekerjaan);
        $data['nama_pekerjaan'] = $wo->nama_pekerjaan;
        $data['nilai_pekerjaan'] = toRp($wo->nilai_pekerjaan);
        $data['no_refrensi'] = $wo->no_refrensi;
        $data['tanggal_levering'] = tglIndo($wo->tgl_levering_start).' - '.tglIndo($wo->tgl_levering_end);
        $data['jenis_wo'] = jenisWO($wo->jenis_wo);

        return Response::json($data);
    }
}
