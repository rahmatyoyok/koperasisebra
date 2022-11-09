<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request, Auth;
use DB;
use App\Http\Models\Pengaturan\User;

class PublicController extends ApiController
{
    use AuthenticatesUsers;

    public function __construct()
    {
        // $this->middleware('guest')->except('logout', 'setLayout');
    }

    protected $redirectTo = '/home';

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $username = $request->get('username') ?: "rizal";
        $password = $request->get('password') ?: "rizal";
        $tahun = $request->get('tahun') ?: "2017";

        $login_type = filter_var($username, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $request->merge([
            'username' => $username,
            'password' => $password,
            'is_active' => true
        ]);

        // if ($this->hasTooManyLoginAttempts($request)) {
        //     $this->fireLockoutEvent($request);
        //
        //     // return $this->sendLockoutResponse($request);
        // }

        if (Auth::attempt($request->only($login_type, 'password', 'is_active'))) {

            $user = User::findOrFail($username);
            $response["success"] = 1;
            $response["user"]["name"] =$user->name;
            $response["user"]["email"] =  $user->email;
            $response["user"]["created_at"] =  $user->created_at;
            session(['tahun' => $request->tahun]);
            session(['is_locked' => false]);
            session(['color-option' => config('app.color')]);

            // return redirect()->intended($this->redirectPath());
        }else{
            $response["error"] = 1;
            $response["error_msg"] = "Incorrect Username or password!";

        }

        return response()->json($response, 200);
        // return response()->json(json_encode($response), 200);
        // $this->incrementLoginAttempts($request);

        // return redirect()->back()
        //     ->withInput($request->only($this->username(), 'remember'))
        //     ->withErrors([
        //         $this->username() => trans('auth.failed'),
        //     ]);
    }

    public function setLayout(Request $request)
    {
        /*if((session('sidebar-option') == 'fixed' && $request->key == 'sidebar-menu-option' && $request->value == 'hover') || (session('sidebar-menu-option') == 'hover' && $request->key == 'sidebar-option' && $request->value == 'fixed'))
            return response()->json('error', 500);*/

        /*if((session('sidebar-option') == 'fixed' && $request->key == 'page-header-option' && $request->value == 'default') || (session('page-header-option') == 'default' && $request->key == 'sidebar-option' && $request->value == 'fixed'))
            return response()->json('error', 500);*/

        session([$request->key => $request->value]);
        return response()->json('success', 200);
    }

    public function creditSupplier()
    {
        $results=array();

		$query = "select
                    SUM(((pj.PBN_HARGA*pj.PBN_TOTAL_BARANG)-IFNULL(pb.PBP_BIAYA,0))) belum_dibayar
                    from pembelians pj
                    left join (select sum(pb.PBP_BIAYA) PBP_BIAYA,pb.PBN_KODE,max(pb.PBP_TANGGAL) as PBP_TANGGAL
                    FROM pembayaran_pembelians pb  GROUP BY pb.PBN_KODE) pb ON pb.PBN_KODE = pj.PBN_KODE
                    where  ((pj.PBN_HARGA*pj.PBN_TOTAL_BARANG)-pb.PBP_BIAYA) <> 0";
        $data =DB::select($query);
        $data = collect($data)->first();

        $results=array('jumlah'=>formatNoRpComma($data->belum_dibayar));
        return response()->json($results);

    }

    public function creditCustomer()
    {
        $results=array();

		$query = "select
                    SUM((pj.PJN_GRAND_TOTAL_JUAL-IFNULL(pb.PB_BIAYA,0))) belum_dibayar
                    from penjualans pj
                    left join (select sum(pb.PB_BIAYA) PB_BIAYA,pb.PJN_KODE,max(pb.PB_TANGGAL) as PB_TANGGAL
                    FROM pembayarans pb  GROUP BY pb.PJN_KODE) pb ON pb.PJN_KODE = pj.PJN_KODE";
        $data =DB::select($query);
        $data = collect($data)->first();

        $results=array('jumlah'=>formatNoRpComma($data->belum_dibayar));
        return response()->json($results);

    }

    public function creditCustomerDueDate()
    {
        $results=array();

		$query = "select
                    SUM((pj.PJN_GRAND_TOTAL_JUAL-IFNULL(pb.PB_BIAYA,0))) belum_dibayar
                    from penjualans pj
                    left join (select sum(pb.PB_BIAYA) PB_BIAYA,pb.PJN_KODE,max(pb.PB_TANGGAL) as PB_TANGGAL
                    FROM pembayarans pb  GROUP BY pb.PJN_KODE) pb ON pb.PJN_KODE = pj.PJN_KODE WHERE DATEDIFF(pj.PJN_TGL_JATUH_TEMPO,CURDATE()) < 0";
        $data =DB::select($query);
        $data = collect($data)->first();

        $results=array('jumlah'=>formatNoRpComma($data->belum_dibayar));
        return response()->json($results);

    }
}
