@component('mail::message')
# Notificación Planes de Mejora
Hola, {{ $name_evaluador }}
{{-- @foreach ($lista_de_correos_de_evaluadores as $x)
    Hola, {{ $x }}    
@endforeach --}}
@if ($primera_fase_activa)
Aún cuentas con Planes pendientes por ingresar. Por favor, haz clic en el siguiente botón para ingresar a la plataforma y revisar tus planes de mejora:
@component('mail::button', ['url' => url('/planes-de-mejora/ingreso')])
Ir a la plataforma
@endcomponent
@endif

@if ($segunda_fase_activa)
Te recordamos que nos encontramos en las fechas establecidas para la revisión de compromisos de los <b>Planes de Mejora de Competencias.</b> Este seguimiento es clave para evaluar los avances alcanzados y reforzar el desarrollo de cada colaborador.
<br>
<br>
Para ello, te solicitamos que <b>actualices la información de tus colaboradores</b> a cargo en la plataforma de Evaluación de Desempeño. Haz clic en el siguiente botón para ingresar:
<br>
@component('mail::button', ['url' => url('/planes-de-mejora/ingreso')])
Ir a la plataforma
@endcomponent
@endif
Si tienes alguna consulta, puedes contactar a <a href="mailto:jimena.cordero@vanguardfresh.pe">Jimena Cordero</a> o <a href="mailto:victor.galvez@vanguardfresh.pe">Víctor Galvez</a>
<br>
<br>
Gracias,<br>
{{ config('app.name') }}
@endcomponent
