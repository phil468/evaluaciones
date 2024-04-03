<table class="table table-striped">
    <thead class="thead">
        <tr> 
            <th>id area</th>
            <th>nombre</th>
            <th>id gerencia</th>
            <th>gerencia</th>
            <th>id subgerencia</th>
            <th>subgerencia</th>
            <th>habilitado</th>
            <th>idempresa nisira</th>
            <th>idarea nisira</th>
            <th>fechacreacion nisira</th>
        </tr>
    </thead>
    <tbody>
        @foreach($areas as $row)
        <tr>
            <td>{{ $row->id}}</td>
            <td>{{ $row->name}}</td>
            <td>{{ $row->gerencia->id ?? '' }}</td>
            <td>{{ $row->gerencia->name ?? '' }}</td>
            <td>{{ $row->subgerencia->id ?? '' }}</td>
            <td>{{ $row->subgerencia->name ?? '' }}</td>
            <td>{{ ($row->estado == 1) ? 'HABILITADO' : (($row->estado == 0) ? 'DESHABILITADO' : NULL) }}</td>
            <td>{{ $row->idempresa_nisira}}</td>
            <td>{{ $row->idarea_nisira}}</td>
            <td>{{ \PhpOffice\PhpSpreadsheet\Shared\Date::PHPToExcel($row->fechacreacion_nisira??NULL) }}</td>
        @endforeach 
    </tbody>
</table>				
