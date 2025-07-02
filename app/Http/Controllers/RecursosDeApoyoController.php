<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecursosDeApoyoController extends Controller
{    
    public function index()
    {
        $videosTutoriales = [
            ['titulo' => 'Tutorial ED por Competencias', 'url' => url('recursos/videos/competencias')],
            ['titulo' => 'Tutorial ED por Objetivos', 'url' => url('recursos/videos/objetivos')]
        ];
        
        $guiasInformativas = [
            ['titulo' => 'Ejemplos Plan de Desarrollo de Competencias', 'url' => url('recursos/guias/ejemplos-plan')],
            ['titulo' => 'Diccionario de Competencias', 'url' => url('recursos/guias/diccionario')]
        ];
        
        $formatos = [
            ['titulo' => 'Formato Plan de Desarrollo de Competencias', 'url' => url('recursos/formatos/plan-desarrollo')]
        ];
        
        return view('recursos_de_apoyo.index', compact('videosTutoriales', 'guiasInformativas', 'formatos'));
    }
}
