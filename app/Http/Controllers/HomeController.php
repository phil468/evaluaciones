<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dash.index');
    }

    //inicio

    public function inicio()
    {
        $evaluaciones_pendientes = auth()->user()->personal()->evaluacionesPendientes();

        return view('inicio.index');
    }

    public function pendientes2()
    {
        return view('inicio.pendientes2');
    }

    public function pendientes()
    {
        return view('inicio.pendientes');
    }

    // public function redirectToAzure()
    // {
    //     return Socialite::driver('azure')->redirect();
    // }

}
