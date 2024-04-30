@extends('adminlte::page')

@section('title', 'Roles')

@section('content_header')
    <h1></h1>
@stop

@section('content')
<div class="row">
<div class="col-md-12">
    <div class="card rounded-xl">
      <div class="card-header bg-primary">
        <h3 class="h4">Roles</h3>
      </div>
      <!-- /.card-header -->
      <div class="p-0 card-body table-responsive">
            @can('crear-rol')
            
        		<a class="float-right mt-1 mr-1 btn btn-success" href="{{ route('roles.create') }}">Nuevo</a>                        
                
        	@endcan
            
            <table class="table mt-2 table-striped table-hover">
                <thead>
                    <th>Acciones</th>
                    <th>Rol</th>
                    <th>Permisos</th>
                </thead>  
                <tbody>
                @foreach ($roles as $role)
                <tr>
                    <td>

                        @can('editar-rol')
                            <a class="btn btn-primary" href="{{ route('roles.edit',$role->id) }}"><i class="fa fa-edit"></i></a>
                        @endcan
                        
                        @can('borrar-rol')
                            {!! Form::open(['method' => 'DELETE','route' => ['roles.destroy', $role->id],'style'=>'display:inline']) !!}
                            <button title="Eliminar" class="btn btn-danger" type="submit" 
                            value="borrar"
                            ><i class="fa fa-trash"></i></button>
                            {!! Form::close() !!}
                        @endcan
                    </td>
                    <td>{{ $role->name }}</td>                    
                    <td>
                        @if(!empty($role->getPermissionNames()))
                        @foreach($role->getPermissionNames() as $name)
                            <span class="badge badge-primary">{{ $name }}</span>
                        @endforeach
                        @endif
                    </td>  
                </tr>
                @endforeach
                </tbody>               
            </table>    
                                   
      </div>
      <!-- /.card-body -->
      
      <div class="pagination justify-content-end">
        {!! $roles->links() !!} 
      </div>        

    </div>
    <!-- /.card -->
</div>
</div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop