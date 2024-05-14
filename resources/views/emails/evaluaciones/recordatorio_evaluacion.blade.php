@component('mail::message')
# Notificación de evaluaciones
Hola, {{ $name_evaluador }}
{{-- @foreach ($lista_de_correos_de_evaluadores as $x)
    Hola, {{ $x }}    
@endforeach --}}
Aún cuentas con Evaluaciones pendientes de realizar. Por favor, haz clic en el siguiente botón para ingresar a la Evaluación de Desempeño:

@component('mail::button', ['url' => url('/')])
Ir a la plataforma
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent
