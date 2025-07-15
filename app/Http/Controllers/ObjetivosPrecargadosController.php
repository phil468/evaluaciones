<?php

namespace App\Http\Controllers;

use App\Models\Evaluacione;
use App\Models\ObjetivosPrecargado;
use App\Models\ObjetivoPrecargadoHasEvidencia;
use App\Models\TiposDeObjetivo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ObjetivosPrecargadosController extends Controller
{
    public function index()
    {
        $tipos_objetivo = TiposDeObjetivo::all();
        $evaluaciones = Evaluacione::evaluacionPorObjetivos()->activa()->get();
        
        return view('objetivos-precargados.index', compact('tipos_objetivo', 'evaluaciones'));
    }
    
    public function getData()
    {
        $objetivos = ObjetivosPrecargado::with(['tipo_objetivo', 'evaluacion', 'evidencias'])->get();
        
        return response()->json($objetivos);
    }
    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'grupal' => 'required',
            'meta' => 'required_if:grupal,1|max:500',
            'tipo_objetivo_id' => 'required_if:grupal,1',
            'resultado_anterior_o_esperado' => 'required_if:grupal,1',
            'porcentaje_de_participacion' => 'required|numeric|between:0,100',
            'evaluacion_id' => 'required',
            'tipo_de_jerarquia_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $objetivo = ObjetivosPrecargado::create($request->all());
        
        return response()->json(['success' => true, 'message' => 'Objetivo precargado creado correctamente', 'data' => $objetivo]);
    }
    
    public function show($id)
    {
        $objetivo = ObjetivosPrecargado::with(['tipo_objetivo', 'evaluacion', 'evidencias'])->findOrFail($id);
        
        return response()->json($objetivo);
    }
    
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'grupal' => 'required',
            'meta' => 'required_if:grupal,1|max:500',
            'tipo_objetivo_id' => 'required_if:grupal,1',
            'resultado_anterior_o_esperado' => 'required_if:grupal,1',
            'porcentaje_de_participacion' => 'required|numeric|between:0,100',
            'evaluacion_id' => 'required',
            'tipo_de_jerarquia_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $objetivo = ObjetivosPrecargado::findOrFail($id);
        $objetivo->update($request->all());
        
        return response()->json(['success' => true, 'message' => 'Objetivo precargado actualizado correctamente', 'data' => $objetivo]);
    }
    
    public function destroy($id)
    {
        $objetivo = ObjetivosPrecargado::findOrFail($id);
        $objetivo->delete();
        
        return response()->json(['success' => true, 'message' => 'Objetivo precargado eliminado correctamente']);
    }
    
    // Nuevas funciones para actualizar valor e ingresar evidencias
    
    public function actualizarValor(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'valor' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $objetivo = ObjetivosPrecargado::findOrFail($id);
        
        // Calcular porcentaje de logro STI
        $porcentaje_de_logro_STI = 0;
        if ($objetivo->tipo_objetivo_id == TiposDeObjetivo::CONDICIONAL) {
            $porcentaje_de_logro_STI = $request->valor == $objetivo->resultado_anterior_o_esperado ? 100 : 0;
        } else if ($request->valor > $objetivo->maximo) {
            $porcentaje_de_logro_STI = $objetivo->evaluacion->maximo;
        } else if ($request->valor >= $objetivo->minimo) {
            $porcentaje_de_logro_STI = $objetivo->resultado_anterior_o_esperado != 0 ? 
                (($request->valor / $objetivo->resultado_anterior_o_esperado) * 100) : 0;
        }
        
        // Calcular peso ponderado
        $peso_ponderado = ($objetivo->porcentaje_de_participacion * $porcentaje_de_logro_STI) / 100;
        
        $objetivo->update([
            'valor' => $request->valor,
            'porcentaje_de_logro_STI' => $porcentaje_de_logro_STI,
            'peso_ponderado' => $peso_ponderado,
            'estado_id' => 2
        ]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Valor actualizado correctamente',
            'data' => $objetivo
        ]);
    }
    
    public function subirEvidencia(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'evidencia' => 'required|file|max:5120', // 1MB Max
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        
        $objetivo = ObjetivosPrecargado::findOrFail($id);
        
        $file = $request->file('evidencia');
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->store('evidencias', 'public');
        
        $evidencia = ObjetivoPrecargadoHasEvidencia::create([
            'objetivo_precargado_id' => $id,
            'ruta' => $path,
            'name' => $name,
            'estado' => true
        ]);
        
        return response()->json([
            'success' => true, 
            'message' => 'Evidencia subida correctamente',
            'data' => $evidencia
        ]);
    }
    
    public function eliminarEvidencia($id)
    {
        $evidencia = ObjetivoPrecargadoHasEvidencia::findOrFail($id);
        
        // Eliminar archivo
        if (Storage::disk('public')->exists($evidencia->ruta)) {
            Storage::disk('public')->delete($evidencia->ruta);
        }
        
        $evidencia->delete();
        
        return response()->json([
            'success' => true, 
            'message' => 'Evidencia eliminada correctamente'
        ]);
    }
    
    public function getEvidencias($id)
    {
        $objetivo = ObjetivosPrecargado::findOrFail($id);
        $evidencias = $objetivo->evidencias;
        
        return response()->json($evidencias);
    }
    
}