<?php

namespace App\Http\Controllers;

use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        // $evaluaciones_pendientes = 
        // EvaluadorHasEvaluado::where('evaluador_id', Auth::user()->personal_id)
        //     ->get();
        //     // ->count();
//         $evaluacionesPendientes = EvaluadorHasEvaluado::where('evaluador_id', Auth::user()->personal_id)
//         ->with(['evaluado', 'evaluacion'])
//         ->get()
//         ->filter(function($evaluacion) {
//             return $evaluacion->estado_pendiente;
//         })
//         ->count();

//         // dd($evaluacionesPendientes);
// hasPendingEvaluations

        $user = Auth::user();
        // Cargar las relaciones necesarias para evitar consultas N+1
        if ($user->personal_id) {
            $user->load([
                'personal.evaluadorHasEvaluados.evaluacion', 
                // 'personal.evaluadoHasEvaluadors.evaluacion'
            ]);
        }

        $evaluacionesPendientes = $user->hasPendingEvaluations();

        return view('inicio.index', [
            'evaluacionesPendientes' => $evaluacionesPendientes,
        ]);
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
