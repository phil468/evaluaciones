@php
    $total = is_countable($evaluadores_enviados ?? []) ? count($evaluadores_enviados) : 0;
@endphp

@component('mail::message')
# Reporte de correos enviados

Se enviaron {{ $total }} correos de recordatorio de evaluación.

@component('mail::table')
| # | Evaluador | Email | Tipo de evaluación | Estado |
|:-:|---|---|---|---|
@forelse(($evaluadores_enviados ?? []) as $i => $e)
| {{ $i+1 }} | {{ $e['evaluador'] ?? '-' }} | {{ $e['email'] ?? '-' }} | {{ $e['tipo_de_evaluacion'] ?? '-' }} | {{ $e['estado'] ?? '-' }} |
@empty
|  | No hay registros |  |  |  |
@endforelse
@endcomponent

Gracias,<br>
{{ config('app.name') }}
@endcomponent