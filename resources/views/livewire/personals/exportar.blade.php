<table class="table table-striped">
    <thead class="thead">
        <tr> 
            <th>nro_identidad</th>
            <th>apenom</th>
            <th>nombres</th>
            <th>apaterno</th>
            <th>amaterno</th>
            <th>idempresa</th>
            <th>empresa</th>
            <th>idgerencia</th>
            <th>gerencia</th>
            <th>idarea</th>
            <th>area</th>
            <th>idsucursal</th>
            <th>sucursal</th>
            <th>idcargo</th>
            <th>cargo</th>
            <th>correo empresa</th>
            <th>celular empresa</th>
            <th>telefono</th>
            <th>celular</th>
            <th>email</th>
            <th>estado</th>
            <th>sexo</th>
            <th>fecha_ingreso</th>
        </tr>
    </thead>
    <tbody>
        @foreach($personal as $row)
        <tr>
            <td>{{ $row->dni}}</td>
            <td>{{ $row->name}}</td>
            <td>{{ $row->nombres}}</td>
            <td>{{ $row->apellido_paterno}}</td>
            <td>{{ $row->apellido_materno}}</td>
            <td>{{ $row->empresa->idempresa_nisira??''}}</td>
            <td>{{ $row->empresa->name??''}}</td>
            <td>{{ $row->gerencia->idarea_nisira??''}}</td>
            <td>{{ $row->gerencia->name??''}}</td>
            <td>{{ $row->area->idarea_nisira??''}}</td>
            <td>{{ $row->area->name??''}}</td>
            <td>{{ $row->sede->idsede_nisira??''}}</td>
            <td>{{ $row->sede->name??''}}</td>
            <td>{{ $row->cargo->idcargo_nisira??''}}</td>
            <td>{{ $row->cargo->name??''}}</td>
            <td>{{ $row->correo_empresa}}</td>
            <td>{{ $row->celular_empresa}}</td>
            <td>{{ $row->telefono_personal}}</td>
            <td>{{ $row->celular_personal}}</td>
            <td>{{ $row->correo_personal}}</td>
            <td>{{ $row->estado == 1 ? 'ACTIVO' : '' }}</td>
            <td>{{ $row->genero == 'H' ? 'Masculino' : ($row->genero == 'M' ? 'Femenino' : '') }}</td>            
            <td>{{ \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($row->fecha_ingreso)}}</td>            
        @endforeach
    </tbody>
</table>				
