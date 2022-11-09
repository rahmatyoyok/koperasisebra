<?php

namespace App\Http\Controllers\App\SimpanPinjam;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use App\Http\Models\SimpanPinjam\KonfigurasiPinjaman;

use Illuminate\Support\Facades\Hash;

use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Crypt;

use DB, Form, Response, Auth;

class KonfigurasiPinjamanController extends AppController
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    
    public function index(Request $request)
    {
        $title    = 'Konfigurasi Pinjaman';
        $parssing = array(
            'title' =>  ucwords($title)            
        );
        return view('SimpanPinjam.konfigurasi.konfigurasipinjaman', $parssing);
    }

    public function getKonfigurasiPinjaman()
    {        
        
        $parssing = array(
            'title'         => ucwords('Konfigurasi Pinjaman'),
            'data'          => KonfigurasiPinjaman::where('status', 1)->first()
        );
        
        return view('SimpanPinjam.konfigurasi.konfigurasipinjaman', $parssing);
    }

    public function store(Request $request)
    {               
        /** validasi form */
        $messages = [
            'required'  => ': Mohon Lengkapi data bertanda bintang',
            'min'       => ': attribute harus diisi minimal :min karakter ya cuy!!!',
            'max'       => ': attribute harus diisi maksimal :max karakter ya cuy!!!',
            'numeric'   => ': isian harus angka'
        ];
        // $this->validate($request,[
        //     'batasPinjaman' =>'numeric',
        //     'biayaAdminPersen' =>'numeric',
        //     'biayaAdminRupiah' =>'numeric'
        // ],$messages);
        /** end of validasi form */
        
        DB::beginTransaction();

        try{
            $konfig = new KonfigurasiPinjaman;
            /** Simpan Baru */
            $konfig->batas_pinjaman                     = 0; 
            $konfig->biaya_administrasi_persentase      = $request->biayaAdminPersen / 100; 
            $konfig->biaya_administrasi_rupiah          = replaceRp($request->biayaAdminRupiah);
            $konfig->biaya_provisi_persentase           = $request->biayaProvisiPersentase/100;
            $konfig->biaya_provisi_rupiah               = replaceRp($request->biayaProvisiRupiah);
            $konfig->resiko_daperma                     = $request->resikoDaperna/100;
            $konfig->biaya_materai                      = $request->biayaMaterai;
            $konfig->biaya_lain                         = $request->biayaLain;
            $konfig->denda_cicilan                      = 0;
            $konfig->status                             = 1;
            $konfig->save();

            /** Update Data Eksisting */
            KonfigurasiPinjaman::where('id',$request->pinjamanId)->update(['status'=>0]);

            notify()->flash('Success!', 'success', [
                'text' => 'Konfigurasi Berhasil Disimpan',
            ]);
            
            DB::commit();
            //return redirect('simpanpinjam/getKonfigurasiPinjaman',['status'=>'Data Konfigurasi Berhasil Disimpan']);
            return redirect('simpanpinjam/getKonfigurasiPinjaman')->with('status', 'Data Konfigurasi Berhasil Disimpan');
        }
        catch(ValidationException $e){
            DB::rollback();
            print("ERROR VALIDATION");
            notify()->flash('Gagal!', 'warning', [
                'text' => 'Anggota Baru Gagal Tambahkan',
            ]);
            
            die();
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
            print("ERROR EXCEPTION");
            die();
        }

        
        DB::commit();
    }

    public function form_entry(Request $req){
        $user_id = Auth::user()->id;
        $title    = 'Tambah Anggota Baru';
        $parssing = array('title' =>  ucwords($title));

        //Cookie::queue($name, $value, $minutes);

        if($req->isMethod('post'))
        {
            DB::beginTransaction();

            try{
                $anggota = new Anggota;

                $anggota->first_name        = $req->get('f_first_name');
                $anggota->last_name         = $req->get('f_last_name');
                $anggota->gender            = $req->get('f_jeniskelamin');
                $anggota->email             = $req->get('f_email');
                $anggota->phone_number      = $req->get('f_no_telp');            
                $anggota->id_card_number    = $req->get('f_identitas');
                $anggota->born_place        = $req->get('f_tempatlahir');
                $anggota->born_date         = date('Y-m-d',strtotime($req->get('f_tanggallahir')));
                $anggota->address_1         = $req->get('f_alamat');
                $anggota->address_2         = "";

                $anggota->city              = $req->get('f_city');
                $anggota->state             = $req->get('f_state');
                $anggota->zip               = $req->get('f_postcode');
                $anggota->country           = $req->get('f_country');            
                $anggota->comments          = "";

                
                $anggota->member_type       = 1;
                $anggota->status            = 1;

                $anggota->created_at        = date('Y-m-d H:m:i');
                $anggota->updated_at        = date('Y-m-d H:m:i');
                $anggota->created_by        = $user_id;
                $anggota->updated_by        = $user_id;
                $anggota->save();

                    $cus = new Customers;
                    $cus->person_id     = $anggota->person_id;
                    $cus->bank_id           = $req->get('f_bank_id');
                    $cus->account_number    = $req->get('f_rekening_no');
                    $cus->company_name  = $req->get('f_unitkerja');
                    $cus->credit_limit  = $req->get('f_batas_pinjaman');
                    $cus->tax_id        = 1;
                    $cus->employee_id    = 1;
                    $cus->save();
                    
                        notify()->flash('Success!', 'success', [
                            'text' => 'Anggota Baru Berhasil Tambahkan',
                        ]);
                        
                        DB::commit();
                        return redirect('simpanpinjam/anggota');
                    
            }
            catch(ValidationException $e){
                DB::rollback();
                print("ERROR VALIDATION");
                notify()->flash('Gagal!', 'warning', [
                    'text' => 'Anggota Baru Gagal Tambahkan',
                ]);
                
                die();
            }catch(\Exception $e){
                DB::rollback();
                throw $e;
                print("ERROR EXCEPTION");
                die();
            }

            DB::commit();


        }

        
        return view('SimpanPinjam.anggota.tambahanggota')->with($parssing);
    }
    
}