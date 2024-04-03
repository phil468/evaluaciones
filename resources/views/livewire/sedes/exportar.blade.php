<table class="table table-striped">
    <thead class="thead">
        <tr> 
            <th>descripcion</th>
            <th>estado</th>
            <th>idsucursal</th>
            <th>fechacreacion</th>
        </tr>
    </thead>
    <tbody>
        @foreach($sedes as $row)
        <tr>
            <td>{{ $row->name}}</td>
            <td>{{ $row->estado}}</td>
            <td>{{ $row->idsucursal_nisira}}</td>
            <td>{{ \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($row->fechacreacion_nisira??NULL) }}</td>
        @endforeach 
    </tbody>
</table>				
