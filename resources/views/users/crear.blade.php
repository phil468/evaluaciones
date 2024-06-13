@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
<div class="col-md-12">
	<div class="card rounded-xl">
		<div class="text-white card-header bg-vanguard rounded-t-xl">
        <h3 class="card-title">Crear Usuario</h3>
      </div>
      <!-- /.card-header -->
      <div class="card-body">
      	@if ($errors->any())
		    <div class="alert alert-danger alert-dismissable">
		    	<button type="button" class="close" data-dismiss="alert">&times;</button>
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif
		{!! Form::open(array('route' => 'users.store','method'=>'POST')) !!}
		
		<div class="mb-3">
		  <label for="name" class="form-label">Nombre:</label>
		  {!! Form::text('name', old('name'), array('class' => 'form-control')) !!}
		  @error('name')<div class="alert alert-danger">{{ $message }}</div>@enderror
		</div>

		<div class="mb-3">
		  <label for="email" class="form-label">Email:</label>
		  {!! Form::text('email', old('email'), array('class' => 'form-control')) !!}
		  @error('email')<div class="alert alert-danger">{{ $message }}</div>@enderror
		</div>

		<div class="mb-3">
		  <label for="password" class="form-label">Password:</label>
		  {!! Form::text('password', old('password'), array('class' => 'form-control')) !!}
		  @error('password')<div class="alert alert-danger">{{ $message }}</div>@enderror
		</div>

		<div class="mb-3">
		  <label for="confirm-password" class="form-label">Confirmar password:</label>
		  {!! Form::text('confirm-password', old('confirm-password'), array('class' => 'form-control')) !!}
		  @error('confirm-password')<div class="alert alert-danger">{{ $message }}</div>@enderror
		</div>

		<div class="mb-3">
			<label for="personal_id" class="form-label">Personal:</label>
			{{-- {{ dd($personal) }} --}}
			{!! Form::select('personal_id', $personal,[], array('class' => 'form-control', 'placeholder' => 'Seleccione uno personal')) !!}
			@error('personal_id')<div class="alert alert-danger">{{ $message }}</div>@enderror
		</div>
		<div class="mb-3">
                <label for="registrador" class="form-label">Registrador</label>
				
			<div class="form-check">
				{!! Form::checkbox('registrador', 1, false ) !!}
				<label class="form-check-label" for="registrador">
					Sí
				</label>
			</div>
				@error('registrador') <span class="error text-danger">{{ $message }}</span> @enderror
		</div>
		
		<div class="mb-3">
		  <label for="roles" class="form-label">Roles:</label>
		  {!! Form::select('roles[]', $roles,[], array('class' => 'form-control', 'placeholder' => 'Seleccione uno o varios roles...','multiple')) !!}
		  @error('roles')<div class="alert alert-danger">{{ $message }}</div>@enderror
		</div>		
		
		<div class="mb-3">
			<label>Estado</label>
			<div class="form-check">
				{{-- {!! Form::checkbox($name, $value, $checked, [$options]) !!} --}}
				{!! Form::checkbox('estado', 1, true) !!}				
				<label class="form-check-label" for="estado">
					Activo
				</label>
			</div>
			@error('estado') <span class="error text-danger">{{ $message }}</span> @enderror
		</div>		
		
        <div class="mb-3">	 
			<a class="mb-3 btn btn-default btn-sm" href="{{route('users.index')}}">Regresar</a>
		  	<button type="submit" class="mb-3 btn btn-vanguard">Crear Usuario</button>
		</div>
		{!! Form::close() !!}                        
      </div>
      <!-- /.card-body -->                 
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