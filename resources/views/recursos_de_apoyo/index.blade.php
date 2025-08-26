@extends('adminlte::page')

@section('title', 'Recursos de Apoyo')

@section('content_header')
    <h1 class="text-center h1">
        Recursos de Apoyo
    </h1>
@stop

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <!-- Sección de Videos tutoriales -->
            @if($videosTutoriales['habilitado'])
                <div class="mb-4">
                    <h4 class="font-weight-bold">Videos tutoriales</h4>
                    <div class="list-group">
                        {{-- Aquí se pueden cargar dinámicamente los videos desde el controlador --}}
                        @foreach($videosTutoriales['archivos'] ?? [] as $video)
                        {{-- enlaces deshabilitados para evitar errores de carga --}}
                            @if ($video['habilitado'])
                                <a href="{{ $video['url'] ?? '#' }}" class="pl-0 border-0 list-group-item list-group-item-action disabled">
                                    <i class="mr-2 fas fa-play-circle text-vanguard"></i>
                                    {{ $video['titulo'] ?? 'Tutorial' }}
                                </a>                            
                            @endif
                        @endforeach
                        
                        {{-- Ejemplos estáticos (reemplazar con datos dinámicos) --}}
                        {{-- <a href="#" class="pl-0 border-0 list-group-item list-group-item-action">
                            <i class="mr-2 fas fa-play-circle text-vanguard"></i> Tutorial ED por Competencias
                        </a>
                        <a href="#" class="pl-0 border-0 list-group-item list-group-item-action">
                            <i class="mr-2 fas fa-play-circle text-vanguard"></i> Tutorial ED por Objetivos
                        </a> --}}
                    </div>
                </div>
            @endif

            <!-- Sección de Guías informativas -->
            @if($guiasInformativas['habilitado'])
                <div class="mb-4">
                    <h4 class="font-weight-bold">Guías informativas</h4>
                    <div class="list-group">
                        {{-- Aquí se pueden cargar dinámicamente las guías desde el controlador --}}
                        @foreach($guiasInformativas['archivos'] ?? [] as $guia)
                            @if($guia['habilitado'])

                                {{-- <div class="container" style="height: 85vh;"> 
                                    <iframe src="{{ $guia['url'] ?? '#' }}#view=FitH" style="width:100%; height:100%; border:0;" title="Manual de Usuario"> </iframe> 
                                </div> --}}
                                
                                <a href="{{ $guia['url'] ?? '#' }}" class="ml-2 border-0 list-group-item list-group-item-action" target="_blank" rel="noopener">
                                    <i class="mr-2 {{ $guia['icon'] ?? 'fas fa-file-alt' }} text-vanguard"></i> {{ $guia['titulo'] ?? 'Guía' }}
                                </a>
                            @endif
                        @endforeach
                        
                        {{-- Ejemplos estáticos (reemplazar con datos dinámicos) --}}
                        {{-- <a href="#" class="pl-0 border-0 list-group-item list-group-item-action">
                            <i class="mr-2 fas fa-file-alt text-vanguard"></i> Ejemplos Plan de Desarrollo de Competencias
                        </a>
                        <a href="#" class="pl-0 border-0 list-group-item list-group-item-action">
                            <i class="mr-2 fas fa-file-alt text-vanguard"></i> Diccionario de Competencias
                        </a> --}}
                    </div>
                </div>
            @endif

            <!-- Sección de Formatos -->
            @if($formatos['habilitado'])
                <div class="mb-4">
                    <h4 class="font-weight-bold">Formatos</h4>
                    <div class="list-group">
                        {{-- Aquí se pueden cargar dinámicamente los formatos desde el controlador --}}

                        @foreach($formatos['archivos'] ?? [] as $formato)
                            @if ($formato['habilitado'])
                                <a href="{{ $formato['url'] ?? '#' }}" class="pl-0 border-0 list-group-item list-group-item-action disabled">
                                    <i class="mr-2 fas fa-file-download text-vanguard"></i> {{ $formato['titulo'] ?? 'Formato' }}
                                </a>                            
                            @endif
                        @endforeach
                        
                        {{-- Ejemplos estáticos (reemplazar con datos dinámicos) --}}
                        {{-- <a href="#" class="pl-0 border-0 list-group-item list-group-item-action">
                            <i class="mr-2 fas fa-file-download text-vanguard"></i> Formato Plan de Desarrollo de Competencias
                        </a> --}}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    .card {
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }
    
    .list-group-item {
        padding: 0.75rem 0;
        background-color: transparent;
        transition: all 0.3s ease;
    }
    
    .list-group-item:hover {
        background-color: rgba(0,0,0,0.03);
        padding-left: 0.5rem;
    }
    
    .text-vanguard {
        color: #568ca5;
    }
    
    h4 {
        color: #333;
        margin-bottom: 1rem;
    }
</style>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Aquí puedes agregar cualquier JavaScript necesario
        console.log('Página de recursos de apoyo cargada');
    });
</script>
@stop