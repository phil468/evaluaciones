<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

//DomPDF
use PDF;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    function __construct()
    {
        $this->middleware('permission:ver-user|crear-user|editar-user|borrar-user',['only'  =>  ['index']]);
        $this->middleware('permission:crear-user',['only'  =>  ['create','store']]);
        $this->middleware('permission:editar-user',['only'  =>  ['edit','update']]);
        $this->middleware('permission:borrar-user',['only'  =>  ['destroy']]);        
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (isset($_GET['sort']))
        {
            if($_GET['desc']==0)
            {
                $users = User::orderBy($_GET['sort'])->paginate(5);
            }
            else
            {
                $users = User::orderByDesc($_GET['sort'])->paginate(5);
            }
            $users->appends([
                'sort'  =>  $_GET['sort'],
                'desc'  =>  $_GET['desc']
            ]);
        }
        else
        {
            $users = User::paginate(5);
        }
                
        return view('users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $personal = Personal::orderBy('name')->pluck('name','id')->all();
        $roles = Role::pluck('name','name')->all();
        return view('users.crear',compact('roles','personal'));
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'personal_id' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        if(empty($input['estado'])){
            $input['estado'] = 0;
        }

        if(empty($input['registrador'])){
            $input['registrador'] = 0;
        }
    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
    
        return redirect()->route('users.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $personal = Personal::orderBy('name')->pluck('name','id')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('users.show',compact('user','roles','userRole','personal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $personal = Personal::orderBy('name')->pluck('name','id')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('users.editar',compact('user','roles','userRole','personal'));
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
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required',
            'personal_id' => 'required'
        ]);
        // dd($request->all());
        $input = $request->all();
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }    
        
        if(empty($input['estado'])){
            $input['estado'] = 0;
        }

        if(empty($input['registrador'])){
            $input['registrador'] = 0;
        }    

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();
    
        $user->assignRole($request->input('roles'));
        
        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        // Cambiar el email a un valor temporal único antes de eliminar
        $user->email = 'deleted_' . time() . '_' . $user->email;
        $user->save();
    
        // Ahora eliminar (soft delete) el usuario
        $user->delete();
        return redirect()->route('users.index');
    }

    public function dompdf($user_id)
    {        
        $user = User::find($user_id);
        $pdf = PDF::loadHTML('<p>Usuario: '.$user->name.'</p>');
        return $pdf->download('prueba.pdf');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(compact('token'));
    }
    
    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                    return response()->json(['user_not_found'], 404);
            }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json(['token_expired'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                    return response()->json(['token_invalid'], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
                    return response()->json(['token_absent'], $e->getStatusCode());
            }
            return response()->json(compact('user'));
    }
}