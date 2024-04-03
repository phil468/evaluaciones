@extends('adminlte::master')

@inject('layoutHelper', 'JeroenNoten\LaravelAdminLte\Helpers\LayoutHelper')

@section('adminlte_css')
    @stack('css')
    @yield('css')
    @stack('styles')
@stop

@section('classes_body', $layoutHelper->makeBodyClasses())

@section('body_data', $layoutHelper->makeBodyData())

@section('body')

    <div class="wrapper">

        {{-- Top Navbar --}}
        @if($layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.navbar.navbar-layout-topnav')
        @else
            @include('adminlte::partials.navbar.navbar')
        @endif

        {{-- Left Main Sidebar --}}
        @if(!$layoutHelper->isLayoutTopnavEnabled())
            @include('adminlte::partials.sidebar.left-sidebar')
        @endif

        {{-- Content Wrapper --}}
        @empty($iFrameEnabled)
            @include('adminlte::partials.cwrapper.cwrapper-default')
        @else
            @include('adminlte::partials.cwrapper.cwrapper-iframe')
        @endempty

        {{-- Footer --}}
        @hasSection('footer')
            @include('adminlte::partials.footer.footer')
        @endif

        {{-- Right Control Sidebar --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.sidebar.right-sidebar')
        @endif

    </div>
@stop

@section('adminlte_js')
    @stack('js')
    @yield('js')
    <script type="text/javascript">
        window.livewire.on('closeModal', () => {
            $('#createDataModal').modal('hide');
            $('#updateModal').modal('hide');
            $('#updateRegistroModal').modal('hide');
            $('#updateActivoModal').modal('hide');
            $('#importDataModal').modal('hide');
            $('#firmaModal').modal('hide');
            $('#seleccionarActivoModal').modal('hide');
            $('#resultadoModal').modal('hide');
            $('#guardarNoAsignacionModal').modal('hide');
        });
        
        window.livewire.on('openSeleccionarActivoModal', () => {
            $('#seleccionarActivoModal').modal('show');
        });
        
        window.livewire.on('confirmarIngresoDNI', () => {
            $('#confirmarIngresoDNIModal').modal('show');
        });

        window.livewire.on('openResultadoModal', () => {
            $('#resultadoModal').modal('show');
        });
        
        window.livewire.on('openGuardarNoAsignacionModal', () => {
            $('#guardarNoAsignacionModal').modal('show');
        });
        
        window.livewire.on('limpiarFile', () => {
            // console.log('Se limpia campo con id File')
            document.getElementById('file').value = null;
        });

        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
        });
    </script>
@stop
