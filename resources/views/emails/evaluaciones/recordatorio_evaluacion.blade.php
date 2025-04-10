@component('mail::message')
# Notificación de evaluaciones
Hola, {{ $name_evaluador }}
{{-- @foreach ($lista_de_correos_de_evaluadores as $x)
    Hola, {{ $x }}    
@endforeach --}}
@if ($primera_fase_activa)
Aún cuentas con Evaluaciones pendientes de realizar. Por favor, haz clic en el siguiente botón para ingresar a la Evaluación de Desempeño:

@component('mail::button', ['url' => $url])
Ir a la plataforma
@endcomponent

@endif

@if ($segunda_fase_activa)
Te recordamos que nos encontramos en las fechas establecidas para la revisión del cumplimiento de los objetivos pactados en la <b>Evaluación de Desempeño por Resultados.</b> Este seguimiento es clave para evaluar los avances alcanzados en la campaña 2024-2025.<br>
<br>
Para ello, te solicitamos que <b>actualices la información de tus colaboradores</b> a cargo en la plataforma de Evaluación de Desempeño. Haz clic en el siguiente botón para ingresar:
<br>
@component('mail::button', ['url' => url('/evaluaciones-de-desempeno/2')])
Ir a la plataforma
@endcomponent
@endif
Si tienes alguna consulta, puedes contactar a <a href="mailto:jimena.cordero@vanguardfresh.pe">Jimena Cordero</a> o <a href="mailto:victor.galvez@vanguardfresh.pe">Víctor Galvez</a>
<br>
<br>
Gracias,<br>
{{ config('app.name') }}
@endcomponent
