<table class="table table-striped">
    <thead class="thead">
        <tr> 
            <th>idcargo</th>
            <th>descripcion</th>
            <th>fechacreacion</th>
            <th>estado</th>
        </tr>
    </thead>
    <tbody>
        @foreach($cargos as $row)
        <tr>
            <td>{{ $row->idcargo_nisira}}</td>
            <td>{{ $row->name}}</td>
            <td>{{ \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($row->fechacreacion_nisira??NULL) }}</td>
            <td>{{ $row->estado}}</td>
        @endforeach 
    </tbody>
</table>				
