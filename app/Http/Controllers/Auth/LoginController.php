<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Auth\Events\Registered;
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
    protected $MenuCategories;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Controller $MenuCategories)
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
        $this->MenuCategories = $MenuCategories;


    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Simpan session_id user yang login
            $user = Auth::user();
            $user->session_id = Session::getId();
            $user->save();

              // kirim email verifikasi
            event(new Registered($user));

            return redirect()->route('login')->with('success', 'Pendaftaran berhasil! Silakan cek email untuk verifikasi akun.');
            // return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    public function destroy(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $user->session_id = null;
            $user->save();
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
