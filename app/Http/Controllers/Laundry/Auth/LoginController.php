<?php

namespace App\Http\Controllers\Laundry\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $loginCredentials = [
            'username' => $request->username,
            'password' => $request->password
        ];

        if (auth()->attempt($loginCredentials)) {
            if (auth()->user()->status === 'active') {
                return $this->authenticated($request, auth()->user());
            } else {
                auth()->logout();
                Alert::toast('<span class="toast-information">Akun Anda telah dinonaktifkan</span>')->hideCloseButton()->padding('25px')->toHtml();
                return redirect()->back();
            }
        }

        Alert::toast('<span class="toast-information">Nama Pengguna atau Kata Sandi Salah</span>')->hideCloseButton()->padding('25px')->toHtml();
        return redirect()->back();
    }
}
