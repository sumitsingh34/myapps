<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Redirect;
use Auth;
use Cache;
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
    protected $redirectTo = '/dashboard';

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
        return view('admin.login');
    }

    public function login(Request $request)
    {

        $rules = array(
            'email'    => 'required|email', // make sure the email is an actual email
            'password' => 'required|alphaNum|min:3' // password can only be alphanumeric and has to be greater than 3 characters
        );
        $userdata = array(
                'email'     => $request->email,
                'password'  => $request->password
            );
        $validator = Validator::make($userdata, $rules);

        if($validator->fails())
        {
            return Redirect::to('admin/login')
            ->withErrors($validator);
        }
        else
        {
         if (Auth::attempt($userdata)) 
            {
                return Redirect::to('/dashboard'); 
            }
            else
            {     
                return redirect()->back()->withInput($request->only('email', 'remember'))->withErrors([
                'approve' => 'Invalid Username or password!',
                    ]);

            }
        }
    }

    public function logout(Request $request) 
    {
        Auth::logout();
        Cache::flush();
        $request->session()->flush();
        $request->session()->regenerate();
        return Redirect::to('admin/login');
    }
}
