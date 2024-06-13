@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card rounded-xl">
          <div class="text-white card-header bg-vanguard rounded-t-xl">
            <div class="float-left">

              <h3 class="h4">Usuarios</h3>
            </div>
            
            @can('crear-user')            
              <a class="float-right mt-1 mr-1 btn btn-default" href="{{ route('users.create') }}">Nuevo</a>
            @endcan
            
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            
            
            @livewire('users-table')

            {{-- <table class="table table-striped table-hover text-nowrap">
              <thead>
                <tr>
                  <th>Acciones</th>
                  <th style="width: 10px">
                    <a href="{{ url('/users') }}?sort=id<?php 
                    if(isset($_GET['sort']) AND ($_GET['sort']=='id')){
                        if($_GET['desc']==0){echo('&desc=1');} 
                        else {echo('&desc=0');}
                    } else {echo('&desc=0');}
                    ?>">ID</a>
                  </th>
                  <th>
                    <a href="{{ url('/users') }}?sort=name<?php 
                    if(isset($_GET['sort']) AND ($_GET['sort']=='name')){
                        if($_GET['desc']==0){echo('&desc=1');} 
                        else {echo('&desc=0');}
                    } else {echo('&desc=0');}
                    ?>">Nombre</a>
                  </th>
                  <th><a href="{{ url('/users') }}?sort=email<?php 
                    if(isset($_GET['sort']) AND ($_GET['sort']=='email')){
                        if($_GET['desc']==0){echo('&desc=1');} 
                        else {echo('&desc=0');}
                    } else {echo('&desc=0');}
                    ?>">Email</a></th>
                  <th>Registrador</th>              
                  <th>Rol</th>
                  <th>Estado</th>
                </tr>
              </thead>
              <tbody>
              @foreach($users as $user)
                <tr>
                  <td>
                    @can('editar-user')
                        <a class="btn btn-primary btn-sm" href="{{ route('users.edit',$user->id) }}"><i class="fa fa-edit"></i></a>
                    @endcan
                    
                    @can('borrar-user')
                        {!! Form::open(['method' => 'DELETE','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                        <button title="Eliminar" class="btn btn-danger btn-sm" type="submit" 
                        value="borrar"
                        ><i class="fa fa-trash"></i></button>
                        {!! Form::close() !!}
                    @endcan
                  </td>    
                    <td>{{$user->id}}</td>
                    <td>{{$user->name}}</td>
                    <td>{{$user->email}}</td>
                    <td>
                      @if ($user->registrador)
                        <h5><span class="badge badge-primary">Sí</span></h5>                      
                      @else
                        <h5><span class="badge badge-light">No</span></h5>                      
                      @endif 
                    </td>
                    <td>
                      @if(!empty($user->getRoleNames()))
                        @foreach($user->getRoleNames() as $rolNombre)
                          <h5><span class="badge badge-dark">{{ $rolNombre }}</span></h5>
                        @endforeach
                      @endif
                    </td>                
                    <td>
                      @if ($user->estado)
                        <h5><span class="badge badge-success">Activo</span></h5>                      
                      @else
                        <h5><span class="badge badge-danger">Inactivo</span></h5>                      
                      @endif                  
                    </td>          
                </tr>
              @endforeach
              </tbody>
            </table> --}}
          </div>
          <!-- /.card-body -->
          
          {{-- <div class="clearfix card-footer">
            {{ $users->onEachSide(1)->links('vendor.pagination.bootstrap-4') }}
          </div>         --}}

        </div>
        <!-- /.card -->
    </div>
  </div>
  
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop