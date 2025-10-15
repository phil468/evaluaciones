@php
$encargado = $plan->encargado->name ?? 'Colaborador';
$estado = $plan->estado_aprobacion;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<style>
body {font-family: Arial, sans-serif; color:#222;}
.table {border-collapse: collapse; width:100%; margin:18px 0;}
.table th, .table td {border:1px solid #555; padding:6px 8px; font-size:13px;}
.badge {display:inline-block; padding:4px 10px; border-radius:12px; background:#0d6efd; color:#fff; font-size:12px;}
.badge-validado {background:#198754;}
.badge-no_validado {background:#dc3545;}
.badge-pendiente {background:#ffc107; color:#222;}
.small {font-size:12px; color:#555;}
</style>
</head>
<body>
    <h3 style="text-align:center; margin-bottom:4px;">
        PLAN DE MEJORA {{ strtoupper($estado) }}
    </h3>

    <p>
        Buenos días {{ $encargado }},<br>
        @if($estado==='validado')
            El Plan de Mejora ha sido revisado y validado conforme por el área de Desarrollo.
        @elseif($estado==='no_validado')
            El Plan de Mejora fue revisado y presenta observaciones detalladas abajo.  
            Por favor realice los ajustes y vuelva a enviarlo para validación.
        @elseif($estado==='pendiente')
            Tu Plan de Mejora fue enviado y está pendiente de validación.
        @else
            Tienes un borrador de Plan de Mejora aún no enviado.
        @endif
    </p>

    <table class="table">
        <thead>
            <tr>
                <th>Descripción</th>
                <th>Competencia</th>
                <th>Empleado</th>
                <th>Estado Aprobación</th>
                <th>% Cumpl.</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $plan->name }}</td>
                <td>{{ $plan->competencia->name ?? '' }}</td>
                <td>{{ $plan->empleado->name ?? '' }}</td>
                <td>
                    <span class="badge badge-{{ $estado }}">{{ strtoupper(str_replace('_',' ',$estado)) }}</span>
                </td>
                <td>{{ $plan->porcentaje_cumplimiento ?? '-' }}</td>
            </tr>
        </tbody>
    </table>

    @if($estado==='no_validado' && $plan->observacion_validacion)
        <p><strong>Observaciones:</strong><br>{{ $plan->observacion_validacion }}</p>
    @endif

    <p class="small">
        Gracias por tu compromiso.<br>
        (Mensaje generado automáticamente – no responder)
    </p>
</body>
</html>