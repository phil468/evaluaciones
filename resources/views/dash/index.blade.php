@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card">

				
				<div class="pr-md-0 pr-sm-0 card-body">
                    {{--Colocar iamgen de fondo para el card body--}}
                    {{--css para que la imagen quede en todo el fondo del card body--}}
                        
                    <div class="">
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6">
                                <div class="h1 font-style-class">
                                    ¡Bienvenido(a)!                        
                                </div>
                                <br>                        
                                <p style = "font-size: x-large;
                                font-family: 'poppins', sans-serif;
                                font-weight: 400;
                                font-style: normal;">
                                    En este proceso, tienes la misión de compartir tus opiniones sobre las fortalezas y oportunidades de mejora de las personas a evaluar. De esta manera, las ayudarás a potenciar su plan de desarrollo individual.
                                </p>    
                                <br>                        
                                <div class="h4 bold font-weight-bold">                        
                                    ¡Contamos contigo!
                                    {{--boton para ir a evaluaciones--}}
                                    <br>
                                    <a class="btn btn-vanguard" href={{url('evaluaciones-de-desempeno')}}>
                                        Ir a Evaluaciones
                                    </a>
                                </div>
                                <br>
                                <video 
                                class="video-background-content" 
                                src="{{asset('img/evaluacion/VIDEO BIENVENIDA - ED 2024 Vanguard Perú.mp4')}}"
                                autoplay="true"
                                muted="true" 
                                loop="true"
                                ></video>
                                <br>
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-6">
                                <img src="{{ asset('img/evaluacion/fondo_de_bienvenida_2.png') }}" class="" alt="Responsive image">
                            </div>                            
                        </div>                      
                    </div>
                    </div>
                </div>
			</div>
		</div>
	</div>

	

</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
    <style>
        /* Estilos personalizados */
        .font-style-class {
            font-family: "poppins", sans-serif;
            font-weight: 700;
            font-style: normal;
            font-size: 2.5em;
            font-weight: bold;
        }

        .font-weight-bold {
            font-family: "poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .video-background-content {
            border-color: #568ca5;
            border-width: 7px;
            border-style: solid;
            border-radius: 12px;
        }
    </style>
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop