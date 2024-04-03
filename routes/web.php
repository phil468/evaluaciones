<?php

use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneraReporte;
use App\Models\Asignacione;
use Livewire\Livewire;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     // return redirect('entregas');
//     view('dash.index');
// });

//Ruta HOME:
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('dash.index');
Route::get('/personal/importar/{numero}', [App\Http\Controllers\PersonalController::class,'actualizarPersonalNisira'])->name('personal.actualizar');
Route::get('/personal/actualizarEstadoParaTodos', [App\Http\Controllers\PersonalController::class,'actualizarEstadoParaTodos'])->name('personal.actualizarEstadoParaTodos');
Route::get('/planilla/importar/{empresa}/{val}', [App\Http\Controllers\PlanillaController::class,'upsert'])->name('planilla.upsert');
Route::get('/tipodepersonal/importar/{empresa}/{val}', [App\Http\Controllers\TipoDePersonalController::class,'upsert'])->name('tipodepersonal.upsert');
Route::get('/tipodetrabajador/importar/{empresa}/{val}', [App\Http\Controllers\TipoDeTrabajadorController::class,'upsert'])->name('tipodetrabajador.upsert');
//Rutas de autenticación
Auth::routes();

Route::get('/evaluacion/login', function () {
    return view('app-evaluacion.auth.login-evaluacion-view');
})->name('evaluacion.login');



Route::post('/login', 'Auth\LoginController@login')->name('evaluacion.login');

Route::group(['middleware'  =>  ['auth']],function(){
    Route::resource('roles',RolController::class);

    //Prueba domPDF
    // Route::get('/users/{user_id}/dompdf',[UserController::class,'dompdf'])->name('users.dompdf');

    Route::view('/personal','livewire.personals.index')->name('personal')->middleware(['can:ver-personal']);
    Route::view('/areas','livewire.areas.index')->name('areas')->middleware(['can:ver-area']);
    Route::view('/capacitaciones','livewire.capacitaciones.index')->name('capacitaciones')->middleware(['can:ver-capacitacion']);
    Route::get('/capacitaciones/{capacitacion_id}', function ($capacitacion_id) {
        return view('livewire.capacitacion-has-personals.index')->with('capacitacion_id', $capacitacion_id);
    })->name('capacitaciones.personal')->middleware(['can:ver-capacitacion']);
    Route::get('/capacitaciones/{capacitacion_id}/asistencia', function ($capacitacion_id) {
        return view('livewire.asistenciums.index')->with('capacitacion_id', $capacitacion_id);
    })->name('capacitaciones.asistencia')->middleware(['can:ver-capacitacion']);

    
    // Route::get('/evaluacion/{id}', function ($evaluacion_id) {
    //     return view('livewire.evaluacion.index')->with('evaluacion_id', $evaluacion_id);
    // })->name('evaluacionnumero')->middleware(['can:ver-capacitacion']);

    // )->name('asistencia')->middleware(['can:ver-asistencia']);
    // Route::view('/accesorios','livewire.accesorios.index')->name('accesorio')->middleware(['can:ver-accesorio']);
    Route::view('/cargos','livewire.cargos.index')->name('cargos')->middleware(['can:ver-cargo']);
    Route::view('/tipos_de_capacitaciones','livewire.tipo-de-capacitaciones.index')->name('tipo-de-capacitacion')->middleware(['can:ver-tipo-de-capacitacion']);//tipo_de_activos
    Route::view('/empresas','livewire.empresas.index')->name('empresas')->middleware(['can:ver-empresa']);
    
    Route::view('/evaluaciones','livewire.evaluaciones.index')->name('evaluaciones')->middleware(['can:ver-empresa']);
    Route::view('/preguntas','livewire.preguntas.index')->name('preguntas')->middleware(['can:ver-empresa']);
    Route::view('/opciones','livewire.opciones.index')->name('opciones')->middleware(['can:ver-empresa']);
    Route::view('/evaluaciones_de_desempeno','livewire.evaluador-has-evaluados.index')->name('evaluacion_de_desempeno')->middleware(['can:ver-empresa']);
    Route::view('/evaluadores','livewire.evaluadores.index')->name('evaluadores')->middleware(['can:ver-empresa']);
    Route::view('/secciones','livewire.secciones.index')->name('secciones')->middleware(['can:ver-empresa']);
    
    Route::get('/evaluacion/{id}', function ($evaluacion_id) {
        return view('livewire.evaluacion.index')->with('evaluacion_id', $evaluacion_id);
    })->name('evaluacion.show')->middleware(['can:ver-capacitacion']);
    Route::view('/respuestas','livewire.respuestas.index')->name('respuestas')->middleware(['can:ver-empresa']);
    Route::view('/seguimiento_evaluadores','livewire.seguimiento-evaluadores.index')->name('seguimiento_evaluadores')->middleware(['can:ver-empresa']);
    Route::view('/seguimiento_evaluados','livewire.seguimiento-evaluados.index')->name('seguimiento_evaluados')->middleware(['can:ver-empresa']);

    Route::view('/sedes','livewire.sedes.index')->name('sedes')->middleware(['can:ver-sede']);
    Route::view('/gerencias','livewire.gerencias.index')->name('gerencias')->middleware(['can:ver-gerencia']);
    Route::view('/temas','livewire.temas.index')->name('temas')->middleware(['can:ver-tema']); //marca
    Route::view('/modalidades','livewire.modalidades.index')->name('modalidades')->middleware(['can:ver-modalidad']);//modelo
    Route::view('/estados','livewire.statuses.index')->name('estados')->middleware(['can:ver-estado']);
    Route::view('/planillas','livewire.planillas.index')->name('planillas')->middleware(['can:ver-planilla']);
    // Route::view('/vigencia','livewire.vigenciums.index')->name('vigencia')->middleware(['can:ver-vigencia']);
    // Route::view('/motivo_baja','livewire.baja-motivos.index')->name('motivo-bajas')->middleware(['can:ver-motivo-baja']);
    // Route::view('/tipo_asignacion','livewire.asignacion-tipos.index')->name('tipo_asignaciones')->middleware(['can:ver-tipo-asignacion']);
    Route::view('/asistencias','livewire.asistenciums.reporte')->name('asistencias')->middleware(['can:ver-asistencia']);
    // Route::view('/devoluciones','livewire.devoluciones.index')->name('devoluciones')->middleware(['can:ver-devolucion']);
    Route::view('/emails/templates/send-invoice','emails.templates.send-invoice')->name('mail-template');

    Route::view('/reporte-de-activos','livewire.reporte-de-activos.index')->name('reporte-de-activos')->middleware(['can:ver-reporte-de-activos']);
    Route::view('/reporte-por-tipo-de-activos','livewire.reporte-por-tipo-de-activos.index')->name('reporte-por-tipo-de-activos')->middleware(['can:ver-reporte-por-tipo-de-activos']);
    Route::view('/reporte-por-estado-de-activos','livewire.reporte-por-estado-de-activos.index')->name('reporte-por-estado-de-activos')->middleware(['can:ver-reporte-por-estado-de-activos']);
    
    Route::get('/pdfprueba', function () {
        $asignacion_guardada = Asignacione::where('id',16)
        ->with(
            'activos_asignados',
            'activos_asignados.activo',
            'activos_asignados.performance',
            'activos_asignados.vigencia',
            'activos_asignados.accesorios',
            'personal',
            'area',
            'empresa',
            'sede',
            'responsable',
            'responsable_area',
            'cargo',
            'responsable_cargo'
            )
        ->first()->toArray();
    
        return view('livewire.asignaciones.pdf', ['asignacion_guardada' => $asignacion_guardada]);
    });
    // Route::view('/clientes','livewire.clientes.index')->name('clientes')->middleware(['can:ver-clientes']);
    // Route::view('/jaba_tipos','livewire.jaba-tipos.index')->name('jaba-tipos')->middleware(['can:ver-jaba-tipos']);
    // Route::view('/motivo_tipos','livewire.motivo-tipos.index')->name('motivo-tipos')->middleware(['can:ver-motivo-tipos']);
    // Route::view('/parihuela_tipos','livewire.parihuela-tipos.index')->name('parihuela-tipos')->middleware(['can:ver-parihuela-tipos']);
    // Route::view('/fruto_colores','livewire.fruto-colores.index')->name('colores')->middleware(['can:ver-fruto-colores']);
    // Route::view('/fruto_variedades','livewire.fruto-variedades.index')->name('variedades')->middleware(['can:ver-fruto-variedades']);
    // Route::view('/productores','livewire.productores.index')->name('productores')->middleware(['can:ver-productores']);
    
    // Route::view('/salida-pallets','livewire.pallets-salidas.index')->name('salida-pallets')->middleware(['can:ver-pallets-salida']);
    
    // Route::view('/reporte_ingreso_pallets','livewire.reporte-ingreso-pallets.index')->name('reporte-ingreso-pallets')->middleware(['can:ver-reporte-ingreso']);
    // Route::view('/reporte_salida_pallets','livewire.reporte-salida-pallets.index')->name('reporte-salida-pallets')->middleware(['can:ver-reporte-salida']);
    // Route::view('/resumen_ingreso_pallets','livewire.resumen-ingreso-pallets.index')->name('resumen-ingreso-pallets')->middleware(['can:ver-resumen-ingreso-pallets']);
    // Route::view('/grafica_pallets','livewire.grafica-pallets.index')->name('grafica-pallets')->middleware(['can:ver-grafica-pallets']);
    // Route::view('/resumen_pallets','livewire.resumen-pallets.index')->name('resumen-pallets')->middleware(['can:ver-resumen-pallets']);
    // Route::view('/kardex','livewire.kardex.index')->name('kardex')->middleware(['can:ver-kardex']);

    // Route::view('/distribucion_variedad','livewire.distribucion-variedad.index')->name('distribucion-variedad')->middleware(['can:ver-distribucion-variedad']);
    // Route::view('/distribucion_salida','livewire.distribucion-salida.index')->name('distribucion-salida')->middleware(['can:ver-distribucion-salida']);
    // Route::view('/distribucion-saldos','livewire.distribucion-saldos.index')->name('distribucion-saldos')->middleware(['can:ver-distribucion-saldos']);

    // Route::view('/resumen-ventas','livewire.resumen-ventas.index')->name('resumen-ventas')->middleware(['can:ver-resumen-ventas']);
    // Route::view('/grafica-ventas','livewire.grafica-ventas.index')->name('grafica-ventas')->middleware(['can:ver-grafica-ventas']);
    // Route::view('/grafica-ml','livewire.grafica-ml.index')->name('grafica-ml')->middleware(['can:ver-grafica-ml']);
    // Route::view('/grafica-ml-resumen-porcentual','livewire.grafica-ml-resumen-porcentual.index')->name('grafica-ml-resumen-porcentual')->middleware(['can:ver-grafica-ml-resumen-porcentual']);

    // Route::view('/inventario','livewire.inventario.index')->name('inventario')->middleware(['can:ver-inventario']);
    // Route::view('/grafica-inventario','livewire.grafica-inventario.index')->name('grafica-inventario')->middleware(['can:ver-grafica-inventario']);

    // Route::view('/impresora','livewire.impresoras.index')->name('impresora')->middleware(['can:ver-impresora']);

    Route::resource('users',UserController::class);
    Route::resource('roles',RolController::class);

    // Route::post('/obtener-token', 'ApiController@login');
    
    // Route::post('/reporte1/pdf', [GeneraReporte::class, 'createPDF'])->name('reporte1.pdf');
    // Route::get('/reporte1', [GeneraReporte::class, 'index'])->name('reporte1.index');
    // Route::post('/imprimezebra', [GeneraReporte::class, 'ImprimeZebra'])->name('imprimezebra');
    
});
// Auth::routes();
Route::get('/web/capacitaciones/{tipo_user}/{user_id}', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'getCapacitaciones'])->name('capacitaciones.getCapacitaciones'); //FALTA MODIFICAR

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/validar-codigo', [App\Http\Controllers\VerificationController::class, 'verifyCode'])->name('verification.verify');

Route::get('/enviar-correo', [App\Http\Controllers\VerificationController::class, 'enviarCorreo']);

