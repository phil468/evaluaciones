{{-- filepath: c:\laragon\www\evaluaciones\resources\views\components\estado-validacion.blade.php --}}
{{-- @props(['estado', 'mensaje']) --}}

<div class="text-center">
    @php
    $clases = [
        'success' => 'badge badge-success',
        'warning' => 'badge badge-warning',
        'danger' => 'badge badge-danger'
    ];
    @endphp
    <span class="{{ $clases[$estado] }}" 
          data-toggle="tooltip" 
          data-placement="top" 
          title="{{ $mensaje }}">
        @if($estado === 'success')
            <i class="fas fa-check"></i>
        @elseif($estado === 'warning')
            <i class="fas fa-exclamation-triangle"></i>
        @else
            <i class="fas fa-times"></i>
        @endif
        {{ $mensaje }}
    </span>
</div>