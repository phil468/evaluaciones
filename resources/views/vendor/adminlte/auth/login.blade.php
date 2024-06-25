@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif

{{-- @section('auth_header', __('adminlte::adminlte.login_message')) --}}

@section('js')
<script type="text/javascript">
    function mostrarPassword(){
            var cambio = document.getElementById("password");
            if(cambio.type == "password"){
                cambio.type = "text";
                $('.icon-password').removeClass('fa fa-eye-slash').addClass('fa fa-eye');
            }else{
                cambio.type = "password";
                $('.icon-password').removeClass('fa fa-eye').addClass('fa fa-eye-slash');
            }
        }
    
    function mostrarOcultarFormularioSesionLocal(){
        var formulario = document.querySelector('form');
        if(formulario.style.display == "none"){
            formulario.style.display = "block";
        }else{
            formulario.style.display = "none";
        }
    }

    </script>
@stop

@section('auth_body')
<div class="text-center">
    <a 
    {{-- href = "#" --}}
    href="{{ url('/auth/redirect') }}" 
    class="btn btn-primary btn-lg">
        <span><i class="fab fa-windows"></i></span>
        <span>Iniciar sesión</span>
    </a>
</div>
<button onclick="mostrarOcultarFormularioSesionLocal()" style="display: none;" accesskey="f">Mostrar Formulario de Sesion Local</button>

    <form action="{{ $login_url }}" method="post" 
    {{-- style="display: none;" --}}
    >
        <br>
        @if ($errors->has('email_corporativo'))
            <div class="alert alert-info">
                {{ $errors->first('email_corporativo') }}
            </div>
        @endif
        <hr>
        <div class="mt-2 text-center h5">
            Inicio de Sesión Local
        </div>
            
        @csrf

        {{-- Email field --}}
        <div class="mb-3 input-group">
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror border border-primary"
                   value="{{ old('email') }}" placeholder="{{ __('adminlte::adminlte.email') }}" autofocus>

            <div class="input-group-append">
                <div class="input-group-text bg-primary">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password field --}}
        <div class="mb-3 input-group">
            <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror border border-primary"
                   placeholder="{{ __('adminlte::adminlte.password') }}">

            <div class="input-group-append">
                <div class="input-group-text bg-primary">
                    <a id="show_password" type="button" onclick="mostrarPassword()"> 
                        <span class="fa fa-eye-slash icon-password"></span> 
                    </a>
                    {{-- <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span> --}}
                </div>
            </div>

            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Login field --}}
        <div class="row">
            <div class="col-7">
                <div class="icheck-primary" title="{{ __('adminlte::adminlte.remember_me_hint') }}">
                    <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label for="remember">
                        {{ __('adminlte::adminlte.remember_me') }}
                    </label>
                </div>
            </div>

            <div class="col-5">
                <button type=submit class="btn btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat btn-primary') }}">
                    <span class="fas fa-sign-in-alt"></span>
                    {{ __('adminlte::adminlte.sign_in') }}
                </button>
            </div>
        </div>
        @if($password_reset_url)
        <p class="my-0">
            <a href="{{ $password_reset_url }}" class="btn btn-link">
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
        @endif
        <hr>

    </form>
@stop

@section('auth_footer')
    {{-- Password reset link --}}
    @if($password_reset_url)
        <p class="my-0">
            <a href="{{ $password_reset_url }}" class="d-none" >
                {{ __('adminlte::adminlte.i_forgot_my_password') }}
            </a>
        </p>
    @endif

    {{-- Register link --}}
    {{-- @if($register_url)
        <p class="my-0">
            <a href="{{ $register_url }}">
                {{ __('adminlte::adminlte.register_a_new_membership') }}
            </a>
        </p>
    @endif --}}
@stop
