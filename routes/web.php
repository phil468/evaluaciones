<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CampaniaController;
use App\Http\Controllers\CampaniaHasCompetenciaController;
use App\Http\Controllers\CampaniaHasEvaluadoController;
use App\Http\Controllers\CompetenciaController;
use App\Http\Controllers\DominioController;
use App\Http\Controllers\DominioHasPreguntaController;
use App\Http\Controllers\EscalaMedicionController;
use App\Http\Controllers\EvaluacionController;
use App\Http\Controllers\EvaluacionesController;
use App\Http\Controllers\EvaluadorHasEvaluadoController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GeneraReporte;
use App\Http\Controllers\GradoController;
use App\Http\Controllers\NivelJerarquicoController;
use App\Http\Controllers\ObjetivosListaController;
use App\Http\Controllers\ObjetivosPrecargadosController;
use App\Http\Controllers\PersonalController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\RespuestasController;
use App\Http\Controllers\SeguimientoEvaluadoresController;
use App\Http\Controllers\ResumenEvaluacionController;
use App\Http\Controllers\TipoCompetenciaController;
use App\Http\Controllers\TipoDePuestoController;
use App\Http\Controllers\TipoDePuestoHasNivelJerarquicoController;
use App\Http\Controllers\TipoMedicionController;
use App\Http\Controllers\TipoRelacionJerarquicoController;
// use App\Http\Livewire\ImportarPreguntas;
use App\Models\Asignacione;
use App\Models\Campania;
use App\Models\CampaniaHasEvaluado;
use App\Models\EvaluadorHasEvaluado;
use App\Models\Personal;
use App\Models\User;
use Clockwork\Request\Request;
use Laravel\Socialite\Facades\Socialite;
use Livewire\Livewire;
use GuzzleHttp\Client;


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
// });

//Ruta HOME:
// Route::get('/connect', [App\Http\Controllers\HomeController::class,'redirectToAzure']);

Route::get('/auth/redirect', function () {
    return Socialite::driver('azure')
    // ->scopes([
    //     'api://e5a37484-1e31-499f-94af-fd254c7422d4/Contacts.Read',
    //     'api://e5a37484-1e31-499f-94af-fd254c7422d4/User.ReadBasic.All'
    //     ]) // Solicita el ámbito específico
    ->redirect('/inicio');
});
 
Route::get('/auth/callback', function () {
    $user = Socialite::driver('azure')->user();

    $localUser = User::where('email', $user->email)->first();

    //he agregado el atributo employeeNumber

    if (!$localUser) {
        // El usuario no existe, crea un nuevo usuario
        $localUser = User::create([
            'name' => $user->name,
            'email' => $user->email,
            // puedes agregar más campos aquí si los necesitas
        ]);
    }

    // Inicia sesión con el usuario
    Auth::login($localUser, true);
    $loginController = new LoginController();
    $loginController->auditlogin($localUser);

    // $response = Http::withToken($user->token)->get('https://graph.microsoft.com/v1.0/me/contacts');
    // $response = Http::withToken($user->token)->get('https://graph.microsoft.com/v1.0/users');
    // $response = Http::withToken($user->token)->get('https://graph.microsoft.com/v1.0/users');


    // dd($response->json());

    // Redirige al usuario a la página de inicio o a donde quieras
    return redirect('/inicio');

});

Route::get('/logout', function () {
    Auth::guard()->logout();
    return redirect('/login');
        
    // $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login')); // reemplaza con tu URL de redirección
    // return redirect()->away($azureLogoutUrl);
    // $request->session()->flush();
    // $azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login'));
    // return redirect($azureLogoutUrl);
})->name('logout');

Route::get('/', [App\Http\Controllers\HomeController::class, 'inicio'])->name('dash.inicio');
Route::get('/inico', [App\Http\Controllers\HomeController::class, 'inicio'])->name('dash.inicio');
// Route::get('/inicio', [App\Http\Controllers\HomeController::class, 'inicio'])->name('dash.inicio');
Route::get('/personal/importar/{numero}', [App\Http\Controllers\PersonalController::class,'actualizarPersonalNisira'])->name('personal.actualizar');
Route::get('/personal/actualizarEstadoParaTodos', [App\Http\Controllers\PersonalController::class,'actualizarEstadoParaTodos'])->name('personal.actualizarEstadoParaTodos');
Route::get('/planilla/importar/{empresa}/{val}', [App\Http\Controllers\PlanillaController::class,'upsert'])->name('planilla.upsert');
Route::get('/tipodepersonal/importar/{empresa}/{val}', [App\Http\Controllers\TipoDePersonalController::class,'upsert'])->name('tipodepersonal.upsert');
Route::get('/tipodetrabajador/importar/{empresa}/{val}', [App\Http\Controllers\TipoDeTrabajadorController::class,'upsert'])->name('tipodetrabajador.upsert');
//Rutas de autenticación
Auth::routes();

Route::group(['middleware'  =>  ['auth']],function(){


    // Route::get('/test-actualizar-resumen', function() {
    //     $comp = new \App\Http\Livewire\Evaluacion(1); // id de evaluación
    //     $comp->actualizarResumenRespuestas(2, 2094); // campania_id, evaluado_id
    //     return 'Resumen actualizado';
    // });

    // Route::get('/actualiza_desde_campania', function() {
        
    //     $evaluados = CampaniaHasEvaluado::where('campania_id', 2)->get();

    //     foreach ($evaluados as $e) {
    //         $personal = Personal::find($e->personal_id);
    //         if ($personal) {
    //             $personal->area_id = $e->area_id;
    //             $personal->cargo_id = $e->puesto_id; // puesto_id en CampaniaHasEvaluado es cargo_id en Personal
    //             $personal->reporta_a = $e->superior_personal_id;
    //             $personal->save();
    //         }
    //     }

    //     return 'Datos Actualizados';
    // });

    // Route::get('cambios_de_respuestas', function(){
    //     // $evaluaciones = Evaluacion::where('campania_id', 2)->get(); // Cambia 1 por el ID de la campaña que deseas procesar

    //     $msg = '';

    //     //MOSTRAR LAS RESPUESTAS DE RESPUESTAS DE LOS EVALUADOS 706 Y 709, CUANDO EL PESO ES 0.5
    //     $respuestas = \App\Models\Respuesta::
    //     //whereIn('evaluado_id', [706, 709])
    //         where('campania_id', 2)
    //         ->where('peso', 0.5)
    //         ->get();

    //     $respuestas = $respuestas->whereIn('evaluado_id', [(int)706, (int)709]);

    //     foreach ($respuestas as $r) {
    //         $msg .= "Respuesta ID: {$r->id}, Evaluado ID: {$r->evaluado_id}, Pregunta ID: {$r->pregunta_id}, Respuesta: {$r->valor_numerico}, Peso: {$r->peso}<br>";
    //     }

    //     // ahora lo que necesito intercambiar , de estos resultados, el evaluador_ID 706, con el evaluado_ID 709 Y VICEVERSA

    //     $msg .= "<br>--- Después del intercambio ---<br>";
    //     foreach ($respuestas as $r) {
    //         if ($r->evaluado_id == (int)706) {
    //             $r->evaluado_id = 709;
    //         } elseif ($r->evaluado_id == (int)709) {
    //             $r->evaluado_id = 706;
    //         }
    //         $r->save();
    //         $msg .= "Respuesta ID: {$r->id}, Evaluado ID: {$r->evaluado_id}, Pregunta ID: {$r->pregunta_id}, Respuesta: {$r->valor_numerico}, Peso: {$r->peso}<br>";
    //     }

    //     return $msg;
    // });

    Route::get('/inicio', [App\Http\Controllers\HomeController::class,'inicio'])->name('inicio');
    Route::get('/pendientes2', 
    [App\Http\Controllers\HomeController::class,'pendientes2'])
    ->name('pendientes2');
    Route::get('/inicio/pendientes', 
    [App\Http\Controllers\HomeController::class, 'pendientes'])
    ->name('pendientes');

    Route::get('/evaluaciones/pendientes/data', 'App\Http\Controllers\EvaluacionesController@pendientesData')->name('evaluaciones.pendientes.data');

    Route::get('/evaluacion_de_competencias', 
    [App\Http\Controllers\EvaluacionDeCompetenciasController::class,'index'])   
    ->name('evaluacion_de_competencias'); // EvaluacionDeCompetenciasController

    //estoy pasando el id de campania como parámetro para mostrar los resultados de la evaluación de competencias
    Route::post('/evaluacion_de_competencias/resultados',
    [App\Http\Controllers\EvaluacionDeCompetenciasController::class,'mostrarResultados'])
    ->name('evaluacion_de_competencias.resultados');

    Route::get('/plan-de-mejora', [App\Http\Controllers\PlanDeMejoraController::class, 'index'])
        ->name('plan.mejora');

    Route::get('/plan-de-mejora/detalle', [App\Http\Controllers\PlanDeMejoraController::class, 'detalle'])
        ->name('plan.mejora.detalle');
    
    // Resultados de equipo
    Route::get('/resultados-de-equipo', [App\Http\Controllers\ResultadosDeEquipoController::class, 'index'])
        ->name('resultados-de-equipo.index');
    Route::get('/resultados-de-equipo/{id}', [App\Http\Controllers\ResultadosDeEquipoController::class, 'detalle'])
        ->name('resultados-de-equipo.detalle');

    // Rutas para ver detalles de evaluaciones y dar feedback
    Route::get('/evaluacion-competencias/detalle', [App\Http\Controllers\EvaluacionDeCompetenciasController::class, 'detalle'])
        ->name('evaluacion-competencias.detalle');
    Route::get('/evaluacion-objetivos/detalle', [App\Http\Controllers\EvaluacionPorObjetivosController::class, 'detalle'])
        ->name('evaluacion-objetivos.detalle');
    Route::get('/pdi/detalle', [App\Http\Controllers\PdiController::class, 'detalle'])
        ->name('pdi.detalle');
    Route::get('/feedback/crear', [App\Http\Controllers\FeedbackController::class, 'crear'])
        ->name('feedback.crear');
    Route::get('/informe/descargar', [App\Http\Controllers\InformeController::class, 'descargar'])
        ->name('informe.descargar');

    Route::get('/recursos-de-apoyo', [App\Http\Controllers\RecursosDeApoyoController::class, 'index'])
        ->name('recursos.apoyo');

    Route::get('/download_evidencia_objetivo_precargado/{id}', 
    [App\Http\Controllers\EvidenciaController::class,'download_evidencia_objetivo_precargado'])
    ->name('download_evidencia_objetivo_precargado');
    Route::get('/download/{id}', [App\Http\Controllers\EvidenciaController::class,'download'])->name('download');
    Route::get('/download_evidencia_plan/{id}', [App\Http\Controllers\EvidenciaController::class,'download_evidencia_plan'])->name('download_evidencia_plan');

    Route::resource('roles',RolController::class);

    //Prueba domPDF
    // Route::get('/users/{user_id}/dompdf',[UserController::class,'dompdf'])->name('users.dompdf');
    Route::view('/dashboard','livewire.dashboard.index')->name('dashboard')->middleware(['can:ver-dashboard']);
    Route::view('/personal-table','livewire.personals.index')->name('personal')->middleware(['can:ver-personal']);
    Route::get('/personal-tabulator', [PersonalController::class, 'indexTabulator'])->name('personal.tabulator')->middleware(['can:ver-personal']);
    Route::view('/areas','livewire.areas.index')->name('areas')->middleware(['can:ver-area']);
    Route::view('/capacitaciones','livewire.capacitaciones.index')->name('capacitaciones')->middleware(['can:ver-capacitacion']);
    Route::get('/capacitaciones/{capacitacion_id}', function ($capacitacion_id) {
        return view('livewire.capacitacion-has-personals.index')->with('capacitacion_id', $capacitacion_id);
    })->name('capacitaciones.personal')->middleware(['can:ver-capacitacion']);
    Route::get('/capacitaciones/{capacitacion_id}/asistencia', function ($capacitacion_id) {
        return view('livewire.asistenciums.index')->with('capacitacion_id', $capacitacion_id);
    })->name('capacitaciones.asistencia')->middleware(['can:ver-capacitacion']);

    Route::view('/cargos','livewire.cargos.index')->name('cargos')->middleware(['can:ver-cargo']);
    Route::view('/tipos_de_capacitaciones','livewire.tipo-de-capacitaciones.index')->name('tipo-de-capacitacion')->middleware(['can:ver-tipo-de-capacitacion']);//tipo_de_activos
    Route::view('/empresas','livewire.empresas.index')->name('empresas')->middleware(['can:ver-empresa']);

    // SEGUIMIENTO DE EVALUACIONES -- INICIO
    Route::view('/respuestas','livewire.respuestas.index')->name('respuestas')->middleware(['can:ver-seguimiento-respuestas']);
    Route::view('/respuesta-evaluacion-competencias','livewire.respuestas.table')->name('respuestas')->middleware(['can:ver-seguimiento-respuestas']);
    Route::get('/respuestas/data', [RespuestasController::class, 'getData'])->name('respuestas.data')->middleware(['can:ver-seguimiento-respuestas']);
    Route::get('/respuestas/export-all', [RespuestasController::class, 'exportAll'])->name('respuestas.export-all')->middleware(['can:ver-seguimiento-respuestas']);

    Route::get('/resumen-evaluacion', [ResumenEvaluacionController::class, 'index'])->name('resumen.evaluacion')->middleware(['can:ver-calibracion']);
    Route::get('/resumen-evaluacion/data', [ResumenEvaluacionController::class, 'data'])->name('resumen.evaluacion.data')->middleware(['can:ver-calibracion']);
    Route::get('/detalle-evaluacion', [ResumenEvaluacionController::class, 'detalle'])->name('detalle.competencia')->middleware(['can:ver-calibracion']);

    Route::get('/calibracion-evaluacion', 
        [ResumenEvaluacionController::class, 'calibracion'])->name('calibracion.competencia')->middleware(['can:ver-calibracion']);

    Route::post('/calibracion-evaluacion/comite', 
        [ResumenEvaluacionController::class, 'guardarComite'])->name('calibracion.comite.guardar')->middleware(['can:editar-calibracion']);
    // Route::get('/calibracion-evaluacion/editar', 
    //     [ResumenEvaluacionController::class, 'editarCalibracion'])->name('calibracion.competencia.editar');
    // Route::post('/calibracion-evaluacion/guardar', 
    //     [ResumenEvaluacionController::class, 'guardarCalibracion'])->name('calibracion.competencia.guardar');
    Route::get('/calibracion/obtener-datos/{personal_id}/{competencia_id}/{campania_id}', 
        [ResumenEvaluacionController::class, 'obtenerDatosCalibracion'])->name('calibracion.obtener-datos')->middleware(['can:editar-calibracion']);    
    Route::get('/personal/search-comite', [PersonalController::class, 'searchComite'])
        ->name('personal.search-comite');
    Route::get('/personal/search-evaluado', [PersonalController::class, 'searchComite'])
        ->name('personal.search-evaluado');
    Route::get('/personal/details', [PersonalController::class, 'getPersonalDetails'])
        ->name('personal.details');
    Route::post('/personal/verificar-correo', [PersonalController::class, 'verificarCorreo'])
    ->name('personal.verificar-correo');

    Route::view('/objetivos','livewire.objetivos-lista.index')->name('objetivos')->middleware(['can:ver-seguimiento-objetivos']);
    Route::view('/respuesta-evaluacion-resultados','livewire.objetivos-lista.table')->name('objetivos')->middleware(['can:ver-seguimiento-objetivos']);
    Route::get('/objetivos-lista/data', [ObjetivosListaController::class, 'getData'])->name('objetivos-lista.data')->middleware(['can:ver-seguimiento-objetivos']);
    Route::get('/objetivos-lista/historial/{id}', [ObjetivosListaController::class, 'getHistorial'])
    ->name('objetivos-lista.historial')->middleware(['can:ver-seguimiento-objetivos']);

    Route::view('/planes-de-accion','livewire.planes-de-accion.index')->name('planes-de-accion')->middleware(['can:ver-planes-de-accion']);
    Route::view('/seguimiento_evaluadores','livewire.seguimiento-evaluadores.index')->name('seguimiento_evaluadores')->middleware(['can:ver-seguimiento-evaluadores']);
    Route::view('/seguimiento_evaluados','livewire.seguimiento-evaluados.index')->name('seguimiento_evaluados')->middleware(['can:ver-seguimiento-evaluados']);
    
    Route::get('/seguimiento-evaluadores', [App\Http\Controllers\SeguimientoEvaluadoresController::class, 'index'])->name('seguimiento-evaluadores.index')->middleware(['can:ver-seguimiento-evaluadores']);
    Route::get('/seguimiento-evaluadores/data',[App\Http\Controllers\SeguimientoEvaluadoresController::class, 'getData'])->name('seguimiento-evaluadores.data')->middleware(['can:ver-seguimiento-evaluadores']);
    Route::get('/seguimiento-evaluadores/resumen',[App\Http\Controllers\SeguimientoEvaluadoresController::class, 'getResumen'])->name('seguimiento-evaluadores.resumen')->middleware(['can:ver-seguimiento-evaluadores']);
    Route::get('/seguimiento-evaluadores/planes-de-accion-no-resueltos',[App\Http\Controllers\SeguimientoEvaluadoresController::class, 'getPlanesNoResueltos'])->name('seguimiento-evaluadores.planes-de-accion-no-resueltos')->middleware(['can:ver-seguimiento-evaluadores']);
    Route::get('/seguimiento-evaluadores/resumen-objetivos', [SeguimientoEvaluadoresController::class, 'getResumenObjetivos'])
    ->name('seguimiento-evaluadores.resumen-objetivos')->middleware(['can:ver-seguimiento-evaluadores']);
    Route::post('/seguimiento-evaluadores/enviar-correos', [SeguimientoEvaluadoresController::class, 'enviarCorreos'])
    ->name('seguimiento-evaluadores.enviar-correos')->middleware(['can:ver-seguimiento-evaluadores']);
    // SEGUIMIENTO DE EVALUACIONES -- FIN

    // AJUSTE DE EVALUACIONES -- INICIO
    Route::resource('evaluaciones', EvaluacionesController::class);
    Route::view('/evaluaciones','livewire.evaluaciones.index')->name('evaluaciones')->middleware(['can:ver-configuracion-evaluaciones']);

    Route::view('/evaluadores','livewire.evaluadores.index')->name('evaluadores')->middleware(['can:ver-configuracion-evaluadores']);
    
    Route::view('/competencias','livewire.secciones.index')->name('secciones')->middleware(['can:ver-configuracion-secciones']);
    Route::get('/competencias/data', [CompetenciaController::class, 'getData'])->name('competencias.data')->middleware(['can:ver-competencias']);
    Route::resource('competencias', CompetenciaController::class);

    // Route::view('/preguntas','livewire.preguntas.index')->name('preguntas')->middleware(['can:ver-configuracion-preguntas']);
    Route::view('/estados-de-plan-de-accion','livewire.estados-de-plan-de-accion.index')->name('estados-de-plan-de-accion')->middleware(['can:ver-estados-de-plan-de-accion']);
    Route::view('/objetivos-precargados-old','livewire.objetivos-precargados.index')->name('objetivos-precargados')->middleware(['can:ver-objetivos-precargados']);
    
    Route::get('/personal/data', [PersonalController::class, 'getData'])->name('personal.data')->middleware(['can:ver-personal']);
    Route::post('personal/marcar-seleccionados', [PersonalController::class, 'marcarSeleccionados'])->name('personal.marcar-seleccionados');

    
    Route::get('/escala_mediciones/data', [EscalaMedicionController::class, 'getData'])->name('escala_mediciones.data')->middleware(['can:ver-escala-medicion']);
    Route::resource('escala_mediciones', EscalaMedicionController::class);

    Route::get('/tipo_mediciones/data', [TipoMedicionController::class, 'getData'])->name('tipo_mediciones.data')->middleware(['can:ver-tipo-medicion']);
    Route::resource('tipo_mediciones', TipoMedicionController::class);

    Route::get('/tipo_competencias/data', [TipoCompetenciaController::class, 'getData'])->name('tipo_competencias.data');
    Route::resource('tipo_competencias', TipoCompetenciaController::class);
    
    Route::get('/campania_has_competencias/data', [CampaniaHasCompetenciaController::class, 'getData'])->name('campania_has_competencias.data')->middleware(['can:ver-campania-has-competencia']);
    Route::resource('campania_has_competencias', CampaniaHasCompetenciaController::class);

    // Rutas para evaluados por campaña
    Route::get('/campania_has_evaluados/selects', [CampaniaHasEvaluadoController::class, 'getSelects'])->name('campania_has_evaluados.selects');
    Route::post('/campania_has_evaluados/{id}/reset-respuestas', [CampaniaHasEvaluadoController::class, 'resetRespuestas'])->name('campania_has_evaluados.resetRespuestas');
    Route::post('/campania_has_evaluados/{id}/toggle-competencias', [CampaniaHasEvaluadoController::class, 'toggleCompetencias'])->name('campania_has_evaluados.toggle-competencias');
    Route::post('/campania_has_evaluados/{id}/toggle-objetivos', [CampaniaHasEvaluadoController::class, 'toggleObjetivos'])->name('campania_has_evaluados.toggle-objetivos');
    Route::post('/campania_has_evaluados/importar', [CampaniaHasEvaluadoController::class, 'importar'])->name('campania_has_evaluados.importar');
    Route::post('/campania_has_evaluados/validar-importar', [CampaniaHasEvaluadoController::class, 'validarImportar'])->name('campania_has_evaluados.validar-importar');
    Route::post('/campanias/{id}/importar-evaluados', [CampaniaHasEvaluadoController::class, 'importar'])->name('campanias.importar_evaluados');
    Route::post('/campanias/{id}/validar-evaluados', [CampaniaHasEvaluadoController::class, 'validarImportar'])->name('campanias.validar_evaluados');
    Route::get('/campanias/{id}/evaluados/template', [CampaniaHasEvaluadoController::class, 'templateEvaluados'])->name('campanias.evaluados.template');
    Route::resource('campania_has_evaluados', CampaniaHasEvaluadoController::class);

    Route::get('/nivel_jerarquicos/data', [NivelJerarquicoController::class, 'getData'])->name('nivel_jerarquicos.data');
    Route::resource('nivel_jerarquicos', NivelJerarquicoController::class);

    Route::get('/grados/data', [GradoController::class, 'getData'])->name('grados.data');
    Route::resource('grados', GradoController::class);

    Route::get('/dominios/data', [DominioController::class, 'getData'])->name('dominios.data');
    Route::resource('dominios', DominioController::class);

    Route::get('/tipo_de_puestos/data', [TipoDePuestoController::class, 'getData'])->name('tipo_de_puestos.data');
    Route::get('/tipo_puesto/lista', [TipoDePuestoController::class, 'getLista'])->name('api.tipo_puesto.lista');
    Route::resource('tipo_de_puestos', TipoDePuestoController::class);

    Route::get('/tipo_puesto_niveles/data', [TipoDePuestoHasNivelJerarquicoController::class, 'getData'])->name('tipo_puesto_niveles.data');
    Route::resource('tipo_puesto_niveles', TipoDePuestoHasNivelJerarquicoController::class);

    Route::get('dominio-has-preguntas/data', [DominioHasPreguntaController::class, 'getData'])->name('dominio-has-preguntas.data');
    Route::resource('dominio-has-preguntas', DominioHasPreguntaController::class);

    Route::get('/preguntas/data', [PreguntaController::class, 'getData'])->name('preguntas.data')->middleware(['can:ver-preguntas']);
    Route::resource('preguntas', PreguntaController::class);

    // Rutas para Cargos
    Route::get('/cargos/data', [App\Http\Controllers\CargoController::class, 'getData'])->name('cargos.data')->middleware(['can:ver-cargo']);
    Route::resource('cargos', App\Http\Controllers\CargoController::class);
    Route::post('/cargos/actualizar-tipos', [App\Http\Controllers\CargoController::class, 'actualizarTiposDePuesto'])
    ->name('cargos.actualizar-tipos');

    // Rutas para Pesos por campaña
    Route::get('/campanias/{id}/pesos', [App\Http\Controllers\PesoController::class, 'getByCampania'])->name('campanias.pesos');
    Route::get('/pesos/selects', [App\Http\Controllers\PesoController::class, 'getSelects'])->name('pesos.selects');
    Route::resource('pesos', App\Http\Controllers\PesoController::class);
    // Route::view('/importar-preguntas', 'livewire.importar-preguntas.index')->name('importar-preguntas')->middleware(['auth']);
    // AJUSTE DE EVALUACIONES -- FIN

    // routes/web.php (agregar estas rutas junto a las demás rutas de recursos)
    Route::get('/tipo_relacion_jerarquicas/data', [App\Http\Controllers\TipoRelacionJerarquicoController::class, 'getData'])
        ->name('tipo_relacion_jerarquicas.data')
        ->middleware(['can:ver-tipo-relacion-jerarquica']);
    Route::resource('tipo_relacion_jerarquicas', App\Http\Controllers\TipoRelacionJerarquicoController::class);

    // routes/api.php (opcional, para endpoints API)
    Route::get('/tipo_relacion_jerarquica/lista', [TipoRelacionJerarquicoController::class, 'lista'])
        ->name('api.tipo_relacion_jerarquica.lista');

    Route::get('/evaluaciones-de-desempeno/{id}', function ($tipo_de_evaluacion_id) {
        return view('livewire.evaluador-has-evaluados.index')->with('tipo_de_evaluacion_id', $tipo_de_evaluacion_id);
    })->name('evaluacion_de_desempeno')
    ->middleware(['can:ver-evaluaciones-de-desempeno']);

    // Route::view('/planes-de-accion','livewire.planes-de-accion.index')->name('planes-de-accion')->middleware(['can:ver-planes-de-accion']);
    
    // Route::get('/evaluacion/{tipo_de_evaluacion_id}/{id}', function ($tipo_de_evaluacion_id,$evaluacion_id) {
    //     $this->evaluadorHasEvaluado = EvaluadorHasEvaluado::where('evaluador_has_evaluados.id',$evaluacion_id)
    //         ->where('evaluador_has_evaluados.evaluador_id', auth()->user()->personal_id)
    //         ->when($tipo_de_evaluacion_id == 1, function ($query) {
    //             return $query->where('evaluador_has_evaluados.realizado', null);
    //         })
    //         ->leftJoin('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
    //         ->where('evaluaciones.tipo_de_evaluacion_id',$tipo_de_evaluacion_id)
    //         ->first();
    //     if ($this->evaluadorHasEvaluado) {            
    //         if ($tipo_de_evaluacion_id == 1) {
    //             return view('livewire.evaluacion.index')->with('evaluacion_id', $evaluacion_id);
    //         } elseif ($tipo_de_evaluacion_id == 2) {
    //             return view('livewire.objetivos.index')->with('evaluacion_id', $evaluacion_id);
    //         }
    //     } else {
    //         $this->evaluadorHasEvaluado = EvaluadorHasEvaluado::where('evaluador_has_evaluados.id',$evaluacion_id)
    //         ->leftJoin('evaluaciones','evaluador_has_evaluados.evaluacion_id','=','evaluaciones.id')
    //         ->where('evaluaciones.tipo_de_evaluacion_id',$tipo_de_evaluacion_id)
    //         ->first();            
    //         if ($this->evaluadorHasEvaluado) {
    //             if ($this->evaluadorHasEvaluado->realizado == 1 && $this->evaluadorHasEvaluado->tipo_de_evaluacion_id == 1) {
    //                 return redirect()->route('evaluacion_de_desempeno', $tipo_de_evaluacion_id)->with('error', 'Ya evaluó a este empleado');
    //             } else if ($this->evaluadorHasEvaluado->evaluador_id != auth()->user()->personal_id) {
    //                 return redirect()->route('evaluacion_de_desempeno', $tipo_de_evaluacion_id)->with('error', 'No tiene permisos para evaluar este personal');
    //             }
    //         } else {
    //         return redirect()->route('evaluacion_de_desempeno', $tipo_de_evaluacion_id)->with('error', 'No se encuentra registrada esta evaluación');
    //         }
    //     }
    // })->name('evaluacion.show')->middleware(['can:ver-evaluaciones-de-desempeno']);

    Route::get('/evaluacion/{tipo_de_evaluacion_id}/{id}', [EvaluacionController::class, 'show'])
    ->name('evaluacion.show')
    ->middleware(['can:ver-evaluaciones-de-desempeno']);

    Route::get('/planes-de-mejora/{ingreso}', function ($ingreso) {
        return view('livewire.planes-de-mejora.index')->with('ingreso', $ingreso);
    })->name('planes-de-mejora.ingreso')->middleware(['can:ver-evaluaciones-de-desempeno']);

    Route::get('/planes-de-mejora/{dashboard}/{empleado_id}', function ($dashboard, $empleado_id) {
        return view('livewire.planes-de-mejora.index')->with('dashboard', $dashboard)->with('empleado_id', $empleado_id);
    })->name('planes-de-mejora')->middleware(['can:ver-evaluaciones-de-desempeno']);

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

    Route::resource('users',UserController::class);
    Route::resource('roles',RolController::class);

    Route::get('/campanias/getData', [CampaniaController::class, 'getData'])->name('campanias.getData');
    Route::get('/campanias/{id}/getAllCompetenciasByCampaniaId', [CampaniaController::class, 'getAllCompetenciasByCampaniaId'])->name('campanias.getAllCompetenciasByCampaniaId');
    Route::get('/campanias/getSelect', [CampaniaController::class, 'getSelect'])->name('campanias.getAll');
    Route::get('/campanias/{id}/getAllCompetenciasCampaniaAnterior', [CampaniaController::class, 'getAllCompetenciasCampaniaAnterior'])->name('campanias.getAllCompetenciasCampaniaAnterior')->middleware(['can:ver-campania']);

    Route::get('/campanias/{id}/config', [CampaniaController::class, 'config'])->name('campanias.config');   
    Route::get('/campanias/{id}/preguntas', [CampaniaController::class, 'getPreguntas'])->name('campanias.preguntas');
    Route::get('/campanias/{id}/preguntas-estado', [CampaniaController::class, 'getPreguntasEstado'])->name('campanias.preguntas-estado');
    Route::get('/campanias/{id}/competencias', [CampaniaController::class, 'getCompetencias'])->name('campanias.competencias');
    Route::get('/campanias/{id}/dominios', [CampaniaController::class, 'getDominios'])->name('campanias.dominios');
    Route::post('/campanias/{id}/validar-preguntas', [CampaniaController::class, 'validatePreguntas'])->name('campanias.validar-preguntas');
    Route::post('/campanias/{id}/importar-preguntas', [CampaniaController::class, 'importarPreguntas'])->name('campanias.importar-preguntas');
        
    Route::resource('campanias', CampaniaController::class);
    Route::post('/campanias/{id}/restore', [CampaniaController::class, 'restore'])->name('campanias.restore');
    Route::delete('/campanias/{id}/force-delete', [CampaniaController::class, 'forceDelete'])->name('campanias.forceDelete');

    // Rutas para evaluaciones
    Route::get('/campanias/{id}/evaluaciones', [EvaluacionesController::class, 'getByCampania'])->name('campanias.evaluaciones');
    Route::get('/tipos-evaluacion', [EvaluacionesController::class, 'getTiposEvaluacion'])->name('tipos_evaluacion.data');

    // Rutas para evaluados por campaña
    // Route::get('/campanias/{id}/evaluados', [CampaniaHasEvaluadoController::class, 'getByCampania'])->name('campanias.evaluados');
    Route::get('/campanias/{id}/evaluados', [CampaniaHasEvaluadoController::class, 'getByCampania'])->name('campanias.evaluados');
    Route::get('/campanias/{id}/evaluados/index', [CampaniaHasEvaluadoController::class, 'index'])->name('campania_has_evaluados.index');
    Route::get('/evaluados/selects', [CampaniaHasEvaluadoController::class, 'getSelects'])->name('campania_has_evaluados.selects');
    Route::post('/evaluados', [CampaniaHasEvaluadoController::class, 'store'])->name('campania_has_evaluados.store');
    Route::get('/evaluados/{id}', [CampaniaHasEvaluadoController::class, 'show'])->name('campania_has_evaluados.show');
    Route::put('/evaluados/{id}', [CampaniaHasEvaluadoController::class, 'update'])->name('campania_has_evaluados.update');
    Route::delete('/evaluados/{id}', [CampaniaHasEvaluadoController::class, 'destroy'])->name('campania_has_evaluados.destroy');
    // Route::post('/evaluados/{id}/toggle-competencias', [CampaniaHasEvaluadoController::class, 'toggleCompetencias'])->name('campania_has_evaluados.toggle_competencias');
    Route::post('/evaluados/{id}/toggle-objetivos', [CampaniaHasEvaluadoController::class, 'toggleObjetivos'])->name('campania_has_evaluados.toggle_objetivos');
    Route::get('/evaluados/{id}/subordinados', [CampaniaHasEvaluadoController::class, 'getSubordinados'])->name('campania_has_evaluados.subordinados');
    Route::get('/evaluados/{id}/pares', [CampaniaHasEvaluadoController::class, 'getPares'])->name('campania_has_evaluados.pares');
    Route::post('/campanias/{id}/importar-evaluados', [CampaniaHasEvaluadoController::class, 'importar'])->name('campanias.importar_evaluados');
    Route::post('/campanias/{campania}/generar-evaluador-has-evaluado/{tipo}', [CampaniaHasEvaluadoController::class, 'generarEvaluadorHasEvaluado'])->name('campanias.generarEvaluadorHasEvaluado');
    

    // Route::post('/campanias/exportar-personal-a-campania-actual', 
    // [CampaniaHasEvaluadoController::class, 'exportarPersonalACampaniaActual'])
    // ->name('campanias.exportarPersonalACampaniaActual');

    Route::post('campanias/exportar-todos-seleccionados', 
    [CampaniaHasEvaluadoController::class, 'exportarTodosSeleccionados'])
    ->name('campanias.exportarTodosSeleccionados');

    Route::post('campanias/exportar-personal', 
    [CampaniaHasEvaluadoController::class, 'exportarPersonalACampaniaActual'])
    ->name('campanias.exportarPersonalACampaniaActual');

    Route::prefix('campanias/{campania}/evaluador-has-evaluados')->group(function () {
        Route::get('/', [EvaluadorHasEvaluadoController::class, 'index']);
        Route::get('/selects', [EvaluadorHasEvaluadoController::class, 'selects']);
    });

    Route::post('update-evaluador-has-evaluados-competencias/{campania}', [EvaluadorHasEvaluadoController::class, 'updateEvaluacionDeCompetencias']);

    Route::resource('evaluador-has-evaluados', EvaluadorHasEvaluadoController::class)
        ->only(['show', 'store', 'update', 'destroy']);

    Route::get('personal/search', function(\Illuminate\Http\Request $request) {
        $q = $request->input('q');
        return \App\Models\Personal::where('name', 'like', "%$q%")
            ->select('id', 'name')
            ->limit(30)
            ->get();
    });
        
    Route::get('personal/select2/empresa', [PersonalController::class, 'select2Empresa'])->name('api.personal.select2.empresa');
    Route::get('personal/select2/gerencia', [PersonalController::class, 'select2Gerencia'])->name('api.personal.select2.gerencia');
    Route::get('personal/select2/area', [PersonalController::class, 'select2Area'])->name('api.personal.select2.area');
    Route::get('personal/select2/cargo', [PersonalController::class, 'select2Cargo'])->name('api.personal.select2.cargo');
    Route::get('personal/select2/reporta', [PersonalController::class, 'select2Reporta'])->name('api.personal.select2.reporta');

    Route::get('organigrama', [App\Http\Controllers\OrgChartController::class, 'index'])->name('organigrama.index');

    // Rutas para actualización de personal
    Route::post('personal/actualizacion-general', 
    [App\Http\Controllers\PersonalController::class, 
    'actualizacionGeneralCompleta']
    )->name('personal.actualizacion-general');

    Route::post('personal/actualizacion-individual/{dni}', 
    [App\Http\Controllers\PersonalController::class, 
    'actualizacionIndividual']
    )->name('personal.actualizacion-individual');

    Route::post('personal/buscar-por-dni', 
    [App\Http\Controllers\PersonalController::class, 
    'buscarPersonalPorDNI']
    )->name('personal.buscar-por-dni');

    Route::get('personal/historial-actualizaciones', 
    [App\Http\Controllers\PersonalController::class, 
    'historialActualizaciones']
    )->name('personal.historial-actualizaciones');

    Route::post('/personal/sync-user-email/{id}', [PersonalController::class, 'syncUserEmail'])->name('campania_has_evaluados.syncUserEmail');
    Route::post('/personal/create-user/{id}', [PersonalController::class, 'createUser'])->name('campania_has_evaluados.createUser');

    Route::resource('personal', PersonalController::class)->middleware(['can:ver-personal']);

    // Rutas para Objetivos Precargados
    Route::prefix('objetivos-precargados')->name('objetivos-precargados.')->middleware(['auth'])->group(function () {
        Route::get('/', [ObjetivosPrecargadosController::class, 'index'])->name('index');
        Route::get('/data', [ObjetivosPrecargadosController::class, 'getData'])->name('data');
        Route::post('/', [ObjetivosPrecargadosController::class, 'store'])->name('store');
        Route::get('/{id}', [ObjetivosPrecargadosController::class, 'show'])->name('show');
        Route::put('/{id}', [ObjetivosPrecargadosController::class, 'update'])->name('update');
        Route::delete('/{id}', [ObjetivosPrecargadosController::class, 'destroy'])->name('destroy');
        
        // Nuevas rutas
        Route::put('/{id}/actualizar-valor', [ObjetivosPrecargadosController::class, 'actualizarValor'])->name('actualizar-valor');
        Route::post('/{id}/subir-evidencia', [ObjetivosPrecargadosController::class, 'subirEvidencia'])->name('subir-evidencia');
        Route::delete('/evidencias/{id}', [ObjetivosPrecargadosController::class, 'eliminarEvidencia'])->name('eliminar-evidencia');
        Route::get('/{id}/evidencias', [ObjetivosPrecargadosController::class, 'getEvidencias'])->name('get-evidencias');
    });
        
});
// Auth::routes();
Route::get('/web/capacitaciones/{tipo_user}/{user_id}', [App\Http\Controllers\Api\ws\CapacitacionesController::class, 'getCapacitaciones'])->name('capacitaciones.getCapacitaciones'); //FALTA MODIFICAR

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/validar-codigo', [App\Http\Controllers\VerificationController::class, 'verifyCode'])->name('verification.verify');

Route::get('/enviar-correo', [App\Http\Controllers\VerificationController::class, 'enviarCorreo']);
