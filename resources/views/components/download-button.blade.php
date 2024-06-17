<div>
    @if ($evidencias && $evidencias->count() > 0)
        @foreach ($evidencias as $evidencia)
            <div class="mb-2 btn-group" role="group" aria-label="Basic example">
                <a href="{{ route('download', $evidencia->id) }}" class="btn btn-link">
                    {{ $evidencia->name }}
                </a>
            </div>
            <br>
        @endforeach
    @else
        <div class="alert alert-light" role="alert">
            No hay evidencias
        </div>
    @endif
</div>