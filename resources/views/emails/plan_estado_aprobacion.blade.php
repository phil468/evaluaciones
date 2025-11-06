@php
    $encargado = $encargadoPlan->encargado->name ?? 'Colaborador';
    $empleadoNombre = $empleado->name ?? 'Evaluado';
    $empleadoId = $empleado->id ?? 0;
    $totalPlanes = $planes->count();
    $validados = $planes->where('estado_aprobacion', 'validado')->count();
    $noValidados = $planes->where('estado_aprobacion', 'no_validado')->count();
    $urlPlanes = url('planes-de-mejora/dashboard/' . $empleadoId);
@endphp
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #222;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: linear-gradient(135deg, #568ca5 0%, #3a5f73 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            text-align: center;
        }

        .content {
            background: #f8f9fa;
            padding: 20px;
            border: 1px solid #dee2e6;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
            margin: 18px 0;
            background: white;
        }

        .table th {
            background: #568ca5;
            color: white;
            padding: 12px 8px;
            font-size: 13px;
            text-align: left;
        }

        .table td {
            border: 1px solid #dee2e6;
            padding: 10px 8px;
            font-size: 13px;
        }

        .table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }

        .badge-validado {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .badge-no_validado {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .badge-pendiente {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        .resumen {
            background: white;
            padding: 15px;
            border-radius: 8px;
            margin: 15px 0;
            border-left: 4px solid #568ca5;
        }

        .resumen-item {
            display: inline-block;
            margin-right: 20px;
            padding: 8px 15px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .btn {
            display: inline-block;
            background: #568ca5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 15px 0;
        }

        .btn:hover {
            background: #3a5f73;
        }

        .footer {
            font-size: 12px;
            color: #6c757d;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #dee2e6;
        }

        .alert-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 15px 0;
            border-radius: 4px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h2 style="margin: 0;">🎯 Resultados de Validación - Planes de Mejora Individual</h2>
        </div>

        <div class="content">
            <p><strong>Hola {{ $encargado }},</strong></p>

            <p>Los <strong>{{ $totalPlanes }} Planes de Mejora Individual (PMI)</strong> de
                <strong>{{ $empleadoNombre }}</strong> han sido revisados por el área de Gestión de Talento.</p>

            <div class="resumen">
                <strong>📊 Resumen de validación:</strong><br>
                <div style="margin-top: 10px;">
                    <span class="resumen-item">
                        <span class="badge badge-validado">✓ Validados:</span>
                        <strong>{{ $validados }}/{{ $totalPlanes }}</strong>
                    </span>
                    <span class="resumen-item">
                        <span class="badge badge-no_validado">✗ No validados:</span>
                        <strong>{{ $noValidados }}/{{ $totalPlanes }}</strong>
                    </span>
                </div>
            </div>

            @if ($noValidados > 0)
                <div class="alert-warning">
                    <strong>⚠️ Acción requerida:</strong> Hay {{ $noValidados }} plan(es) con observaciones que deben
                    ser atendidas.
                </div>
            @endif

            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 30%;">Compromiso / Acción de Mejora</th>
                        <th style="width: 20%;">Competencia</th>
                        <th style="width: 15%;">Estado</th>
                        <th style="width: 30%;">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($planes as $index => $plan)
                        <tr>
                            <td style="text-align: center;">{{ $index + 1 }}</td>
                            <td>{{ $plan->name }}</td>
                            <td>{{ $plan->competencia->name ?? '-' }}</td>
                            <td style="text-align: center;">
                                <span class="badge badge-{{ $plan->estado_aprobacion }}">
                                    {{ $plan->estado_aprobacion === 'validado' ? '✓ VALIDADO' : '✗ NO VALIDADO' }}
                                </span>
                            </td>
                            <td>
                                @if ($plan->observacion_validacion)
                                    {{ $plan->observacion_validacion }}
                                @else
                                    <span style="color: #6c757d; font-style: italic;">Sin observaciones</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ $urlPlanes }}" class="btn">
                    📋 Ver Planes de Mejora Completos
                </a>
            </div>

            @if ($noValidados > 0)
                <p style="background: #e7f3ff; padding: 12px; border-radius: 6px; border-left: 4px solid #0d6efd;">
                    <strong>💡 Próximos pasos:</strong><br>
                    1. Revisa las observaciones de cada plan marcado como "No validado"<br>
                    2. Realiza los ajustes sugeridos en conjunto con {{ $empleadoNombre }}<br>
                    3. Los planes serán re-evaluados automáticamente
                </p>
            @else
                <p style="background: #d4edda; padding: 12px; border-radius: 6px; border-left: 4px solid #28a745;">
                    <strong>✅ ¡Excelente trabajo!</strong> Todos los planes han sido validados correctamente.
                </p>
            @endif

            <div class="footer">
                <p style="margin: 5px 0;"><strong>Gestión de Talento</strong></p>
                <p style="margin: 5px 0;">Este es un mensaje automático, por favor no responder.</p>
                <p style="margin: 5px 0; font-size: 11px;">Si tienes consultas, contacta al área de Desarrollo
                    Organizacional.</p>
            </div>
        </div>
    </div>
</body>

</html>
