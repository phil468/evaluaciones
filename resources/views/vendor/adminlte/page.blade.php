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
            $('#importObjetivosDataModal').modal('hide');
            $('#firmaModal').modal('hide');
            $('#seleccionarActivoModal').modal('hide');
            $('#resultadoModal').modal('hide');
            $('#guardarNoAsignacionModal').modal('hide');
            $('#indicacionesModal').modal('hide');
            $('#confirmacionModal').modal('hide');
            $('#graciasModal').modal('hide');
            $('#createPlanDataModal').modal('hide');
            $('#updatePlanDataModal').modal('hide');
            $('#actualizarValorModal').modal('hide');
            $('#evidenciaModal').modal('hide');
            $('#updatePlanesConfiguracionModal').modal('hide');
            $('#importEncargadosPlanesDataModal').modal('hide');
            $('#updateEncargadosPlanesModal').modal('hide');
        });
        
        window.livewire.on('opencreatePlanDataModal', () => {
            $('#createPlanDataModal').modal('show');
        });
        
        window.livewire.on('openUpdatePlanDataModal', () => {
            $('#updatePlanDataModal').modal('show');
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
        
        window.livewire.on('openGraciasModal', () => {
            $('#confirmacionModal').modal('hide');
            $('#graciasModal').modal('show');
        });
        
        window.livewire.on('openHistorialModal', function () {
            $('#auditoriaModal').modal('show');
        });
        
        window.livewire.on('openUpdateModal', function () {
            $('#updateModal').modal('show');
        });
        
        window.livewire.on('openUpdatePlanesConfiguracionModal', function () {
            $('#updatePlanesConfiguracionModal').modal('show');
        });

        window.livewire.on('actualizarValorModal', function () {
            $('#actualizarValorModal').modal('show');
        });
        
        window.livewire.on('openModalEvidencias', function () {
            $('#evidenciaModal').modal('show');
        });
        
        window.livewire.on('openEncargadosPlanesModal', function () {
            $('#updateEncargadosPlanesModal').modal('show');
        });
        
        window.livewire.on('limpiarFile', () => {
            // dd('cargado');
            console.log('Se limpia campo con id File');
            document.getElementById('file').value = null;
            document.getElementById('file_objetivos').value = null;
            document.getElementById('file_planes').value = null;
        });

        $(document).ready(function() {
            $('.dropdown-toggle').dropdown();
        });
    </script>
@stop 
