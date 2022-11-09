<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request, Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'setLayout');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'username'    => 'required',
            'password' => 'required',
            // 'captcha' => 'required|captcha'
        ]);

        $login_type = filter_var($request->input('username'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $request->merge([
            $login_type => $request->input('username'),
            'is_active' => true
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if (Auth::attempt($request->only($login_type, 'password', 'is_active'))) {
            // session(['tahun' => $request->tahun]);
            session(['is_locked' => false]);
            session(['color-option' => config('app.color')]);

            if($request->input('username') == 'simpanpinjam'){
                return redirect('simpanpinjam/home');    
            }
            

            return redirect()->intended($this->redirectPath());
        }

        $this->incrementLoginAttempts($request);

        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => trans('auth.failed'),
            ]);
    }

    public function refreshCaptcha(){
        return captcha_img();
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
}
