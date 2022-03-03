<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Requests;
use Auth;
use Session;


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
        $this->middleware('guest')->except('logout');
    }


    public function showLoginForm()
    {
        return view('auth.login');
    }


    public function attemptLogin(Request $request)
    { 
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:5'
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'suspend' => 0], $request->get('remember') )) {

            //return redirect('/home');
            return redirect()->intended($this->redirectPath());
        }else{
            return redirect()->back()->with('error','Email or password not correct');
        }
        //return back()->withInput($request->only('email', 'remember'));
    }
    
    public function logout(Request $request)
    {
        $this->guard()->logout();
        //$request->session()->invalidate();
        Auth::logout();
        //Cookie::queue(\Cookie::forget('laravel_session'));
        Session::flush();

        return redirect()->route('guest');
    }



}
