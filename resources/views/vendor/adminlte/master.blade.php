<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    {{-- Base Meta Tags --}}
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom Meta Tags --}}
    @yield('meta_tags')

    {{-- Title --}}
    <title>
        @yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', config('adminlte.title', 'AdminLTE 3'))
        @yield('title_postfix', config('adminlte.title_postfix', ''))
    </title>

    {{-- Custom stylesheets (pre AdminLTE) --}}
    @yield('adminlte_css_pre')

    {{-- Base Stylesheets --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('vendor/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

        {{-- Configured Stylesheets --}}
        @include('adminlte::plugins', ['type' => 'css'])

        <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href={{ asset('css/jfl4jsk.css') }}
        {{-- "https://use.typekit.net/jfl4jsk.css" --}}
        >
    @else
        <link rel="stylesheet" href="{{ mix(config('adminlte.laravel_mix_css_path', 'css/app.css')) }}">
    @endif

    <link rel="stylesheet" href="{{ asset('css/no-more-tables.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    {{-- Livewire Styles --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireStyles
        @else
            <livewire:styles />
        @endif
    @endif
      
    <script src="{{ asset('js/alpine.min.js') }}" defer></script>

    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css"> --}}
    
    {{-- Custom Stylesheets (post AdminLTE) --}}
    @yield('adminlte_css')
    <link href=
    "{{ asset('css/bootstrap4-toggle.min.css') }}"
    {{-- "https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css"  --}}
    rel="stylesheet">
    {{-- <link href="{{ asset('css/app.css') }}" rel="stylesheet">
     --}}
     <link href="{{ asset('css/tailwind.min.css') }}" rel="stylesheet">

    {{-- <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet"> --}}
  
    <link
    rel="stylesheet"
    href="{{ asset('css/choices.min.css') }}"
    {{-- "https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" --}}
  />
  
    {{-- Favicon --}}
    @if(config('adminlte.use_ico_only'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
    @elseif(config('adminlte.use_full_favicon'))
        <link rel="shortcut icon" href="{{ asset('favicons/favicon.ico') }}" />
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="192x192"  href="{{ asset('favicons/android-icon-192x192.png') }}">
        <link rel="manifest" crossorigin="use-credentials" href="{{ asset('favicons/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
    @endif

    <style>
        .body-bg {
            /* background-color: #6ECBC9; */
            background-image: url('{{asset('img/evaluacion/login-10s.mp4')}}');
            /* url('/img/evaluacion/login-10s.mp4'); */

            /* background-image: url('{{asset('img/evaluacion/login-10s.mp4')}}'); */
            background-repeat: no-repeat;
            background-position: center center;
            background-attachment: fixed;
            background-size: cover;
        }
    </style>

</head>

<body class="@yield('classes_body')" @yield('body_data')>

    @if (env('APP_ENV')!='production')
    
        @if (env('APP_ENV')=='local')
            <div class="text-center w-100 bg-danger">
                <span>
                    SE ENCUENTRA EN ENTORNO LOCAL ({{env('DB_HOST')}} - {{env('DB_DATABASE')}})
                </span>
            </div>
            <div class="text-center w-100 bg-danger fixed-top">
                <span>
                    SE ENCUENTRA EN ENTORNO LOCAL ({{env('DB_HOST')}} - {{env('DB_DATABASE')}})
                </span>
            </div>
        @endif
        @if ( env('APP_ENV')=='testing')
        <div class="text-center w-100 bg-danger">
            <span>
                SE ENCUENTRA EN ENTORNO DE PRUEBA
            </span>
        </div>
        <div class="text-center w-100 bg-danger fixed-top">
            <span>
                SE ENCUENTRA EN ENTORNO DE PRUEBA
            </span>
        </div>
        @endif        
    @endif
    {{-- Body Content --}}
    @yield('body')

    {{-- Base Scripts --}}
    @if(!config('adminlte.enabled_laravel_mix'))
        <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('vendor/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>

        {{-- Configured Scripts --}}
        @include('adminlte::plugins', ['type' => 'js'])

        <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @else
        <script src="{{ mix(config('adminlte.laravel_mix_js_path', 'js/app.js')) }}"></script>
    @endif

    {{-- Livewire Script --}}
    @if(config('adminlte.livewire'))
        @if(app()->version() >= 7)
            @livewireScripts
        @else
            <livewire:scripts />
        @endif
    @endif

    <script src=
    "{{ asset('js/bootstrap4-toggle.min.js')}}"
    ></script>

    @yield('adminlte_js')
    <script src=
    "{{ asset('js/bootstrap4-toggle.min.js')}}"
    {{-- "https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js" --}}
    ></script>

    <!-- Include Choices JavaScript (latest) -->
    <script src=
    "{{ asset('js/choices.min.js')}}"
    {{-- "https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js" --}}
    ></script>
</body>

</html>
