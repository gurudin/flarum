<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;

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
    protected $redirectTo = '/admin.cms';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * 处理身份认证尝试.
     *
     * @return Response
     */
    public function login(Request $request)
    {
        if (Auth::attempt([
            'email' => $request->post('email'),
            'password' => $request->post('password'),
            'status' => User::ADMINISTRATOR
        ])) {
            return redirect()->intended('dashboard');
        } else {
            return redirect('/admin.cms/login')->withErrors('Not enough permissions!', 'login');
        }
    }
}
