<?php

namespace App\Http\Controllers\App\Pengaturan;

use Illuminate\Http\Request;
use App\Http\Controllers\AppController;

use Yajra\Datatables\Facades\Datatables;
use App\Http\Models\Pengaturan\User;
use App\Http\Models\Pengaturan\Level;

use DB, Form, Auth;

class UserController extends AppController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        /*$users = User::with('level', 'unitorganisasi')
                        ->select('users.*');
                        dd($users->get());*/
        if($request->ajax())
        {
            $users = User::with('level')
                        ->select('users.*');
            if(Auth::user()->level_id == 2){

            }elseif (Auth::user()->level_id == 3) {
              $users = $users->where('level_id',7);
              $users = $users->where('regencies',Auth::user()->regencies);

            }elseif (Auth::user()->level_id == 7) {
              $users = $users->where('level_id',5);
              $users = $users->where('regencies',Auth::user()->regencies);
            }
            $datatables = Datatables::of($users)
                ->addColumn('action', function ($user) {
                    $html = '';
                    if($user->is_active == 1)
                    {
                        $html .=
                            '<a href="'.url('pengaturan/user/'.$user->id.'/edit').'" class="btn btn-xs purple-sharp tooltips" title="Edit"><i class="glyphicon glyphicon-edit"></i></a>'.
                            '&nbsp;'.
                            Form::open([
                                'method'=>'POST',
                                'url'   => url('pengaturan/user/reset/'.$user->id),
                                'style' => 'display:inline'
                            ]).
                            Form::button('<i class="glyphicon glyphicon-refresh" aria-hidden="true"></i>', array(
                                'title' => 'Reset Password',
                                'type' => 'submit',
                                'class' => 'btn btn-icon btn-warning btn-xs dt-btn tooltips',
                                'data-swa-text' => 'Reset password user '.$user->username.'?'
                            )).
                            Form::close().
                            '&nbsp;'.
                            Form::open([
                                'method'=>'POST',
                                'url'   => url('pengaturan/user/change-status/'.$user->id),
                                'style' => 'display:inline'
                            ]).
                            Form::button('<i class="glyphicon glyphicon-ban-circle" aria-hidden="true"></i>', array(
                                'title' => 'Non Aktifkan User',
                                'type' => 'submit',
                                'class' => 'btn btn-icon btn-danger btn-xs dt-btn tooltips',
                                'data-swa-text' => 'Non aktifkan user '.$user->username.'?'
                            )).
                            Form::close();
                    }
                    else
                    {
                        $html .=
                            '&nbsp;'.
                            Form::open([
                                'method'=>'POST',
                                'url'   => url('pengaturan/user/change-status/'.$user->username),
                                'style' => 'display:inline'
                            ]).
                            Form::button('<i class="glyphicon glyphicon-upload" aria-hidden="true"></i>', array(
                                'title' => 'Aktifkan User',
                                'type' => 'submit',
                                'class' => 'btn btn-icon btn-success btn-xs dt-btn tooltips',
                                'data-swa-text' => 'Aktifkan user '.$user->username.'?'
                            )).
                            Form::close();
                    }
                    return $html;
                })
                ->editColumn('is_active', function ($user){
                    $html = '<span class="label label-';
                    if($user->is_active == 1)
                    {
                        $html .= 'success"> Aktif';
                    }
                    else
                    {
                        $html .= 'danger"> Non Aktif';
                    }
                    return $html.' </span>';
                })

                ->rawColumns(['is_active', 'action', 'unitorganisasi']);
            return $datatables->make(true);
        }

        return view('pengaturan.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        
        $data['level'] = Level::pluck('name', 'id');
        
        return view('pengaturan.user.create', $data);
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
            'username' => 'required|unique:users|max:100',
            'email' => 'required|email|unique:users|max:150',
            'password' => 'required|confirmed',
            'name' => 'required|max:200',
        ]);
        DB::beginTransaction();
        try
        {
            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->name = $request->name;
            $user->level_id = $request->level_id;
            $user->is_active = 1;
            $user->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'User berhasil ditambah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('pengaturan/user');
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
       
        $data['user_edit'] = User::findOrFail($id);
        $data['level'] = Level::pluck('name', 'id');
        return view('pengaturan.user.edit', $data);
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
            'email' => 'required|email|unique:users,email,'.$id.',username|max:150',
            'name' => 'required|max:200',


        ]);

        DB::beginTransaction();
        try
        {
            $user = User::findOrFail($id);
            $user->email = $request->email;
            $user->name = $request->name;
            $user->level_id = $request->level_id;
            $user->save();

            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Data user berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('pengaturan/user');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /*public function destroy($id)
    {
        //
    }*/

    public function reset($id)
    {
        DB::beginTransaction();
        try
        {
            $user = User::findOrFail($id);
            $user->password = bcrypt($user->username);
            $user->save();
            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Password user berhasil direset',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('pengaturan/user');
    }

    public function change_status($id)
    {
        DB::beginTransaction();
        try
        {
            $user = User::findOrFail($id);
            $status = $user->is_active;
            if($status == 1)
            {
                $user->is_active = 0;
            }
            else
            {
                $user->is_active = 1;
            }
            $user->save();
            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Status user berhasil diganti',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('pengaturan/user');
    }

    public function change_profile(Request $request)
    {
        $id = auth()->user()->username;
        $this->validate($request, [
            'email' => 'required|email|unique:users,email,'.$id.',username|max:150',
            'name' => 'required|max:200'
        ]);

        DB::beginTransaction();
        try
        {
            $user = User::findOrFail($id);
            $user->email = $request->email;
            $user->name = $request->name;
            $user->save();
            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Data user berhasil diubah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('home/profile');
    }

    public function change_password(Request $request)
    {
        $this->validate($request, [
            'old_password' => 'required|old_password:'.auth()->user()->password,
            'password' => 'required|confirmed',
        ]);

        DB::beginTransaction();
        try
        {
            $user = User::findOrFail(auth()->user()->username);
            $user->password = bcrypt($request->password);
            $user->save();
            DB::commit();
            notify()->flash('Success!', 'success', [
                'text' => 'Password berhasil diubah',
            ]);
        }
        catch(\Yajra\Pdo\Oci8\Exceptions\Oci8Exception $e)
        {
            DB::rollback();
            notify()->flash('Error!', 'error', [
                'text' => $e->getMessage(),
            ]);
        }
        return redirect('home/profile');
    }
}
