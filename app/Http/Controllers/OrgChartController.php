<?php

namespace App\Http\Controllers;

use App\Models\Personal;
use Illuminate\Http\Request;

class OrgChartController extends Controller
{
    public function index()
    {
        // Obtener todo el personal activo que tenga un superior asignado
        $personal = Personal::with(['superior', 'cargo', 'area'])
            ->where('estado', 1)
            ->whereNotNull('reporta_a')
            ->get();

        // Obtener también los superiores que podrían no estar en la consulta anterior
        $superiores = Personal::with(['cargo', 'area'])
            ->whereIn('id', $personal->pluck('reporta_a')->unique())
            ->where('estado', 1)
            ->get();

        // Combinar ambos conjuntos y eliminar duplicados
        $allPersonal = $personal->merge($superiores)->unique('id');

        // Estructurar datos para d3-org-chart
        $orgChartData = [];
        foreach ($allPersonal as $person) {
            $orgChartData[] = [
                'id' => $person->id,
                'parentId' => $person->reporta_a,
                'name' => $person->name,
                'position' => $person->cargo ? $person->cargo->name : 'Sin cargo',
                'image' => $person->foto ? asset('storage/' . $person->foto) : asset('img/evaluacion/icono_de_notificacion.png'),
                'area' => $person->area ? $person->area->name : 'Sin área',
                'email' => $person->correo_empresa ?: 'Sin correo'
            ];
        }

        return view('orgchart.index', compact('orgChartData'));
    }
}

        // // Obtener todo el personal activo que tenga un superior asignado
        // $personal = Personal::with(['superior', 'cargo', 'area'])
        //     ->where('estado', 1)
        //     ->whereNotNull('reporta_a')
        //     ->get();

        //     $personal_alt = CampaniaHasEvaluado::with(['personal', 'puesto', 'area'])
        //     ->where('estado', 1)
        //     ->whereNotNull('superior_personal_id')
        //     ->get();

        // // Obtener también los superiores que podrían no estar en la consulta anterior
        // $superiores = Personal::with(['cargo', 'area'])
        //     ->whereIn('id', $personal->pluck('reporta_a')->unique())
        //     ->where('estado', 1)
        //     ->get();
        
        //     $superiores_alt = CampaniaHasEvaluado::with(['personal', 'puesto', 'area'])
        //     ->whereIn('personal_id', $personal_alt->pluck('superior_personal_id')->unique())
        //     ->where('estado', 1)
        //     ->get();

        // // Combinar ambos conjuntos y eliminar duplicados
        // $allPersonal = $personal_alt->merge($superiores_alt)->unique('id');

        // // dd($allPersonal->toArray());

        // // Estructurar datos para d3-org-chart
        // $orgChartData = [];
        // foreach ($allPersonal as $person) {
        //     $orgChartData[] = [
        //         'id' => $person->personal_id ?? $person->id,
        //         'parentId' => $person->reporta_a ?? $person->superior_personal_id,
        //         'name' => $person->personal->name,
        //         'position' => $person->puesto ? $person->puesto->name : 'Sin cargo',
        //         'image' => $person->foto ? asset('storage/' . $person->foto) : asset('img/evaluacion/icono_de_notificacion.png'),
        //         'area' => $person->area ? $person->area->name : 'Sin área',
        //         'email' => $person->correo_empresa ?: 'Sin correo'
        //     ];
        // }
