@extends('adminlte::master')

@php($dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home'))

@if (config('adminlte.use_route_url', false))
    @php($dashboard_url = $dashboard_url ? route($dashboard_url) : '')
@else
    @php($dashboard_url = $dashboard_url ? url($dashboard_url) : '')
@endif

@section('adminlte_css')
    @stack('css')
    @yield('css')

    <style nonce="{{ $nonce }}">
        body {
            background-image: url('{{ asset('img/evaluacion/login-10s.mp4') }}');
            background-size: cover;
            background-repeat: no-repeat;
        }

        video {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 105%;
            min-height: 100%;
            transform: translateX(calc((100% - 100vw) / 2));
            z-index: -2;
            max-width: none !important;
        }
    </style>

@stop

@section('classes_body'){{ ($auth_type ?? 'login') . '-page' }}@stop

@section('body')

    <div class="video-background">
        <video class="video-background-content" 
        src="{{ url('img/evaluacion/Login - Plataforma de Desarrollo.mp4') }}" 
        autoplay="true" 
        muted="true"
        loop="true">
        </video>
    </div>

    <div class="body-bg d-flex h-100 w-75 justify-content-center ">
        <div class="{{ $auth_type ?? 'login' }}-box ">
            <div class="h-25 d-inline-block"></div>
            <div class="text-center">
                <h5 class="text-white font-style-class">Evaluación<br>de Desempeño</h5>
                <br>
            </div>

            {{-- Card Box --}}
            <div class="card {{ config('adminlte.classes_auth_card', 'card-primary') }} opacity-95">
                <div class="mt-4" align="center">
                    <img src="{{ asset('img/evaluacion/logoEVD.png') }}" 
                    height="50%" style="max-width: 66%;"
                    >
                </div>
                {{-- Card Header --}}

                {{-- Card Body --}}
                <div
                    class="card-body {{ $auth_type ?? 'login' }}-card-body {{ config('adminlte.classes_auth_body', '') }} opacity-95">

                    <div class="mb-2 text-center text-bold">
                        <h3 class="float-none card-title text-bold">
                            @yield('auth_header')
                        </h3>
                    </div>
                    @yield('auth_body')
                    <div class="mt-2 mb-4" align="center">
                        <a href="{{ $dashboard_url }}">
                            <img src="{{ asset(config('adminlte.logo_img_xl_alt')) }}"
                            height="100"
                            style="max-width: 50%;">
                        </a>
                    </div>
                </div>

                {{-- Card Footer --}}
                @hasSection('auth_footer')
                    <div class="card-footer {{ config('adminlte.classes_auth_footer', '') }}">
                        @yield('auth_footer')
                    </div>
                @endif

            </div>

        </div>
    </div>

    {{-- <div class="py-2 bg-info w-100">
        <div class="text-center container-fluid">
            <span class="text-white h6 text-bold">
              Bienvenido a la Plataforma de Capacitaciones
            </span>
          </div>
    </div> --}}


@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
@stop
