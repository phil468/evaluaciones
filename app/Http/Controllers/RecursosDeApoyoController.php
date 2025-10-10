<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecursosDeApoyoController extends Controller
{    
    public function index()
    {
        $file = 'manual-desempeno-competencias.pdf'; 
        abort_unless(file_exists(public_path('docs/' . $file)), 404); 
        $manualUrl = asset('docs/' . rawurlencode($file)); 
        // return view('recursos_apoyo.index', compact('manualUrl'));

        $file2 = 'manual-de-visualizacion-de-resultados.pdf'; 
        abort_unless(file_exists(public_path('docs/' . $file2)), 404); 
        $manualUrl2 = asset('docs/' . rawurlencode($file2)); 

        $videosTutoriales = [
            'habilitado' => false,
            'archivos' => [
                ['titulo' => 'Tutorial ED por Competencias', 'url' => url('recursos/videos/competencias'), 'habilitado' => false],
                ['titulo' => 'Tutorial ED por Objetivos', 'url' => url('recursos/videos/objetivos'), 'habilitado' => false]
            ]
        ];
        
        $guiasInformativas = [
            'habilitado' => true,
            'archivos' => [
                ['titulo' => 'Ejemplos Plan de Desarrollo de Competencias', 'url' => url('recursos/guias/ejemplos-plan'), 'habilitado' => false],
                ['titulo' => 'Diccionario de Competencias', 'url' => url('recursos/guias/diccionario'), 'habilitado' => false],
                [
                    'titulo' => 'Manual de Usuario - Ingreso y Evaluación de Desempeño por Competencias', 
                    'url' => $manualUrl, 
                    'habilitado' => true,
                    'icon' => 'fas fa-file-pdf'
                ],
                [
                    'titulo' => 'Manual de usuario - Visualización de resultados de Evaluación por Competencias', 
                    'url' => $manualUrl2, 
                    'habilitado' => true,
                    'icon' => 'fas fa-file-pdf'
                ]
            ]
        ];
        
        $formatos = [
            'habilitado' => false,
            'archivos' => [
                ['titulo' => 'Formato Plan de Desarrollo de Competencias', 'url' => url('recursos/formatos/plan-desarrollo'), 'habilitado' => false]
            ]
        ];
        
        return view('recursos_de_apoyo.index', compact('videosTutoriales', 'guiasInformativas', 'formatos'));
    }
}
