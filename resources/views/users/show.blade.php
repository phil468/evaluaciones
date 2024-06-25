@extends('adminlte::page')

@section('title', 'Perfil de Usuario')

@section('content_header')
    <h1></h1>
@stop

@section('content')
    <div class="row">
		<div class="col-md-12">
			<div class="card rounded-xl">
				<div class="text-white card-header bg-vanguard rounded-t-xl">
				<h3 class="card-title">Perfil de Usuario</h3>
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
						{!! Form::model($user, ['method' => 'PATCH','route' => ['users.update', $user->id]]) !!}
						
						<div class="mb-2">
							<label for="name" class="form-label">Nombre:</label>
						{{$user->name}}
						{{-- {!! Form::text('name', $value=$user->name, array('class' => 'form-control', 'disabled' => 'disabled')) !!} --}}
						{{-- @error('name')<div class="alert alert-danger">{{ $message }}</div>@enderror --}}
						</div>

						<div class="mb-2">
						<label for="email" class="form-label">Email:</label>
						{{$user->email}}
						{{-- {!! Form::text('email', $value=$user->email, array('class' => 'form-control', 'disabled' => 'disabled')) !!} --}}
						{{-- @error('email')<div class="alert alert-danger">{{ $message }}</div>@enderror --}}
						</div>

						{{-- <div class="mb-2">
						<label for="password" class="form-label">Password:</label>
						{!! Form::text('password', '', array('class' => 'form-control', 'disabled' => 'disabled')) !!}
						@error('password')<div class="alert alert-danger">{{ $message }}</div>@enderror
						</div>

						<div class="mb-2">
						<label for="confirm-password" class="form-label">Confirmar password:</label>
						{!! Form::text('confirm-password', old('confirm-password'), array('class' => 'form-control', 'disabled' => 'disabled')) !!}
						@error('confirm-password')<div class="alert alert-danger">{{ $message }}</div>@enderror
						</div> --}}

						<div class="mb-2">
							<label for="personal_id" class="form-label">Personal:</label>
							{{$user->personal->name}}
							{{-- {!! Form::select('personal_id', $personal,[$user->personal_id], array('class' => 'form-control', 'placeholder' => 'Seleccione uno personal', 'disabled' => 'disabled')) !!} --}}
							{{-- @error('personal_id')<div class="alert alert-danger">{{ $message }}</div>@enderror --}}
						</div>

							
						{{-- <div class="mb-2">
							<label>Registrador</label>
							<div class="form-check">
								{!! Form::checkbox('registrador', 1, $user->registrador ? true:false, array('class' => 'form-check-input', 'disabled' => 'disabled')) !!}
								<label class="form-check-label" for="registrador">
									Sí
								</label>
							</div>
							@error('registrador') <span class="error text-danger">{{ $message }}</span> @enderror
						</div> --}}
						
						<div class="mb-2">
							<label for="roles" class="form-label">Roles:</label>
							{{ implode(', ', $userRole) }}
							{{-- {!! Form::select('roles[]', $roles,$userRole, array('class' => 'form-control', 'placeholder' => 'Seleccione uno o varios roles...','multiple', 'disabled' => 'disabled')) !!} --}}
							{{-- @error('roles')<div class="alert alert-danger">{{ $message }}</div>@enderror --}}
						</div>		
						
						<div class="mb-2">
							<label>Estado:</label>
							{{$user->estado ? 'Activo' : 'Inactivo'}}
							{{-- <div class="form-check">
								{!! Form::checkbox('estado', 1, $user->estado ? true:false, array('class' => 'form-check-input', 'disabled' => 'disabled')) !!}				
								<label class="form-check-label" for="estado">
									Activo
								</label>
							</div>
							@error('estado') <span class="error text-danger">{{ $message }}</span> @enderror --}}
						</div>

						{{-- <div class="mb-2">	 
							<a class="mb-3 btn btn-default btn-sm" href="{{route('users.index')}}">Regresar</a>
						<button type="submit" class="mb-3 btn btn-vanguard">Editar Usuario</button>
						</div> --}}
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