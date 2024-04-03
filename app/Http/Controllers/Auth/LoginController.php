<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Exception;
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
    
    protected function authenticated(Request $request, $user)
    {
        $this->auditlogin($user);
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
