<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        // Verificar si el correo electrónico termina en @dominio.pe
        // if (str_ends_with($request->input($this->username()), '@vanguardfresh.pe')) {
        //     return redirect()->back()->withErrors(['email_corporativo' => 'Por favor, utilice el botón de "Iniciar sesión" e ingrese con sus credenciales de correo corporativo.']);
        // }

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            if ($request->hasSession()) {
                $request->session()->put('auth.password_confirmed_at', time());
            }

            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
    
    protected function authenticated(Request $request, $user)
    {
        $this->auditlogin($user);
        redirect('/home');
    }

    function auditlogin($user){
        $rs=0;
        try{
        //    dd(getHostByName(getHostName()));
            DB::transaction(function () use($user) {   
                $id=DB::table('auditlogin')->insertGetId([
                    "user_id" => $user->id??null,
                    "user" => $user->email??null,
                    "host" => gethostbyaddr($_SERVER['REMOTE_ADDR']),
                    // "ip" => getHostByName(getHostName()),
                    "ip" => $this->getUserIpAddr(),
                    "date" => date("Y-m-d H:i:s"),
                    "navigate" => $_SERVER['HTTP_USER_AGENT'],
                    'created_at'=>date("Y-m-d H:i:s"),
                    // "updated_at" => $updated_at
    
                ]);
            });
            $rs=1;
        }catch(Exception $ex ){
            $rs=0;
            dd($ex);
        }
    
        return $rs;  
    
    }
    
    function getUserIpAddr(){
        if(!empty($_SERVER['HTTP_CLIENT_IP'])){
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
}
