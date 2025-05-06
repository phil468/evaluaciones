@extends('adminlte::page')

@section('title', 'Seguimiento Evaluadores')

@section('content_header')
    <h1></h1>
@stop

@section('content')

<div class="container-fluid">
	<div class="row justify-content-center">
		<div class="col-md-12">
			<div class="card rounded-xl">
                <div class="text-white card-header bg-vanguard rounded-t-xl">
					<div style="display: flex; justify-content: space-between; align-items: center;">
						<div class="float-left">
							<h5 class="h5">Seguimiento de Evaluadores</h5>
						</div>
                        
                        <div>
                            {{-- <button id="download-xlsx" class="mr-2 btn btn-default rounded-xl">
                                <i class="mr-1 fas fa-file-excel"></i> Exportar Excel
                            </button> --}}
                            {{-- <button id="download-pdf" class="btn btn-default rounded-xl">
                                <i class="mr-1 fas fa-file-pdf"></i> Exportar PDF
                            </button> --}}
                        </div>
						{{-- <button wire:click="enviarCorreoEvaluadores" class="btn btn-sm btn-default rounded-xl">Enviar correo a evaluadores</button> --}}
					</div>
				</div>				
				<div class="card-body">
                    
                    <!-- Selector de Campaña -->
                    <div class="mb-4 row">
                        <select id="campania-filter" class="form-control rounded-xl">
                            <option value="">Seleccionar Campaña</option>
                            @foreach($campanias as $campania)
                                <option value="{{ $campania->id }}">{{ $campania->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div id="resumen" class="mb-4" style="display: none;">
                        <div class="row">
                            <!-- Evaluación por Competencias -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="text-white card-header bg-vanguard text-bold">
                                        <h5 class="mb-0">Evaluación por Competencias</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-2 progress" style="height: 25px;">
                                            <div id="barra-competencias" class="progress-bar bg-primary" role="progressbar" style="width: 0%">
                                                0%
                                            </div>
                                        </div>
                                        <p class="text-center" id="texto-competencias">0 de 0 realizadas</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Evaluación por Objetivos -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="text-white card-header bg-vanguard text-bold">
                                        <h5 class="mb-0">Evaluación por Objetivos</h5>
                                    </div>
                                    <div class="card-body">
                                        <h6>Primera Fase</h6>
                                        <div class="mb-2 progress" style="height: 25px;">
                                            <div id="barra-objetivos-1" class="progress-bar bg-primary" role="progressbar" style="width: 0%">
                                                0%
                                            </div>
                                        </div>
                                        <p class="text-center" id="texto-objetivos-1">0 de 0 registrados</p>
                                        
                                        <h6>Segunda Fase</h6>
                                        <div class="mb-2 progress" style="height: 25px;">
                                            <div id="barra-objetivos-2" class="progress-bar bg-secondary" role="progressbar" style="width: 0%">
                                                0%
                                            </div>
                                        </div>
                                        <p class="text-center" id="texto-objetivos-2">0 de 0 realizados</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Planes de Mejora -->
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="text-white card-header bg-vanguard text-bold">
                                        <h5 class="mb-0">Planes de Mejora</h5>
                                    </div>
                                    <div class="card-body">
                                        <h6>Primera Fase</h6>
                                        <div class="mb-2 progress" style="height: 25px;">
                                            <div id="barra-planes-1" class="progress-bar bg-primary" role="progressbar" style="width: 0%">
                                                0%
                                            </div>
                                        </div>
                                        <p class="text-center" id="texto-planes-1">0 de 0 registrados</p>
                                        
                                        <h6>Segunda Fase</h6>
                                        <div class="mb-2 progress" style="height: 25px;">
                                            <div id="barra-planes-2" class="progress-bar bg-secondary" role="progressbar" style="width: 0%">
                                                0%
                                            </div>
                                        </div>
                                        <p class="text-center" id="texto-planes-2">0 de 0 realizados</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div style="display: flex; justify-content: space-between; align-items: center;" class="mb-2">
						<div class="float-left">
							<h5 class="h5">Resumen de evaluadores</h5>
						</div>
                        
                        <div>
                            <button id="send-emails" class="mr-2 btn btn-primary rounded-xl">
                                <i class="mr-1 fas fa-envelope"></i> Enviar Correos
                            </button>
                            <button id="download-xlsx" class="mr-2 btn btn-default rounded-xl">
                                <i class="mr-1 fas fa-file-excel"></i> Exportar Excel
                            </button>
                            <button id="download-pdf" class="btn btn-default rounded-xl">
                                <i class="mr-1 fas fa-file-pdf"></i> Exportar PDF
                            </button>
                        </div>
						{{-- <button wire:click="enviarCorreoEvaluadores" class="btn btn-sm btn-default rounded-xl">Enviar correo a evaluadores</button> --}}
					</div>
                    {{-- <h5 class="mb-0">Resumen de evaluadores</h5> --}}

                    <div id="evaluadores-table"></div>
                    <br>
                    <br>
                    <div style="display: flex; justify-content: space-between; align-items: center;" class="mb-2">
						<div class="float-left">
							<h5 class="h5">Resumen de Evaluaciones por Objetivos</h5>
						</div>
                        
                        {{-- <div>
                            <button id="download-xlsx" class="mr-2 btn btn-default rounded-xl">
                                <i class="mr-1 fas fa-file-excel"></i> Exportar Excel
                            </button>
                            <button id="download-pdf" class="btn btn-default rounded-xl">
                                <i class="mr-1 fas fa-file-pdf"></i> Exportar PDF
                            </button>
                        </div> --}}
						{{-- <button wire:click="enviarCorreoEvaluadores" class="btn btn-sm btn-default rounded-xl">Enviar correo a evaluadores</button> --}}
					</div>
                    <div id="resumen-objetivos-table"></div>
				</div>
			</div>
		</div>
	</div>	
</div>
 
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script type="text/javascript">
    // import Swal from 'sweetalert2';

    document.addEventListener('DOMContentLoaded', function() {     
        // if (typeof Swal === 'undefined') {
        //     console.error('❌ SweetAlert2 no está cargado correctamente');
        // } else {
        //     console.log('✅ SweetAlert2 está cargado correctamente');
        //     // Prueba visual
        //     Swal.fire({
        //         title: 'Prueba de SweetAlert2',
        //         text: 'Si ves este mensaje, SweetAlert2 está funcionando correctamente',
        //         icon: 'success',
        //         confirmButtonText: 'OK'
        //     });
        // }   
        // Función auxiliar para generar barras de progreso
        function generarBarraProgresoCompacta(realizados, total, porcentaje) {
            const clase = realizados === 0 ? 'bg-white' : 
                        (realizados === total ? 'bg-success' : 'bg-primary');
            
            return `<div class="progress">
                <div class="progress-bar ${clase}" role="progressbar" 
                    style="width: ${porcentaje}%" aria-valuenow="${porcentaje}" 
                    aria-valuemin="0" aria-valuemax="100">
                    ${realizados} de ${total}
                </div>
            </div>`;
        }
        
        let table = new Tabulator("#evaluadores-table", {
            ajaxURL: "{{ route('seguimiento-evaluadores.data') }}",
            ajaxParams: {
                campania_id: document.getElementById('campania-filter').value
            },
            ajaxConfig: {
                method: "GET",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            },
            layout: "fitColumns",
            pagination: true,
            paginationSize: 10,
            paginationSizeSelector: [10, 25, 50, 100],
            responsiveLayout: true,
            responsiveLayoutCollapseStart: 768,
            // placeholder: "No hay datos disponibles",
            // Reemplaza la configuración de columns en la inicialización de Tabulator
            columns: [
                {
                    title: "EVALUADOR", 
                    field: "evaluador", 
                    sorter: "string",
                    headerFilter: true,
                    // headerFilterPlaceholder: "Filtrar...",
                    headerSortTristate: true,
                    minWidth: 200
                },
                {
                    title: "ÁREA", 
                    field: "area",
                    headerFilter: true,
                    // headerFilterPlaceholder: "Filtrar área...",
                    minWidth: 150
                },
                {
                    title: "COMPETENCIAS", 
                    columns: [
                            {
                                title: "Avance",
                                field: "competencias.avance",
                                formatter: function(cell) {
                                    let data = cell.getRow().getData().competencias;
                                    // let data = cell.getValue();
                                    let porcentaje = (data[0]/data[1])*100 || 0;
                                    return generarBarraProgresoCompacta(data[0], data[1], porcentaje);
                                },
                                headerSort: false,
                                minWidth: 120,
                                accessorDownload: function(value,data) {
                                    return `${data.competencias[0]} de ${data.competencias[1]}`;
                                },
                            },
                            {
                                title: "Estado",
                                field: "competencias.estado",
                                formatter: function(cell) {
                                    let data = cell.getRow().getData().competencias;
                                    return `<div class="badge ${data[0] >= data[1] ? 'badge-success' : 'badge-warning'}">
                                        ${data[0] >= data[1] ? 'Completo' : 'Incompleto'}
                                    </div>`;
                                },
                                accessorDownload: function(value, data) {
                                    return data.competencias[0] >= data.competencias[1] ? 'Completo' : 'Incompleto';
                                },
                                minWidth: 50
                            }
                        ]
                },
                {
                    title: "OBJETIVOS",
                    columns: [
                        {
                            title: "1° Fase",
                            field: "objetivos_fase1.avance", // Cambiar field
                            formatter: function(cell) {
                                let data = cell.getRow().getData().objetivos_fase1;
                                return `<div class="badge ${data.completo ? 'badge-success' : 'badge-warning'}">
                                    ${data.registrados}/${data.requeridos}
                                </div>`;
                            },
                            accessorDownload: function(value, data) {
                                return `${data.objetivos_fase1.registrados} de ${data.objetivos_fase1.requeridos}`;
                            },
                            minWidth: 20
                        },
                        {
                            title: "Estado",
                            field: "objetivos_fase1.estado", // Cambiar field
                            formatter: function(cell) {
                                let data = cell.getRow().getData().objetivos_fase1;
                                return `<div class="badge ${data.completo ? 'badge-success' : 'badge-warning'}">
                                    ${data.completo ? 'Completo' : 'Incompleto'}
                                </div>`;
                            },
                            accessorDownload: function(value, data) {
                                return data.objetivos_fase1.completo ? 'Completo' : 'Incompleto';
                            },
                            sorter: function(a, b, aRow, bRow) {
                                let aData = aRow.getData().objetivos_fase1;
                                let bData = bRow.getData().objetivos_fase1;
                                return (aData.completo === bData.completo) ? 0 : (aData.completo ? -1 : 1);
                            },
                            headerFilter: "dropdown",
                            headerFilterParams: {
                                values: {
                                    "true": "Completo",
                                    "false": "Incompleto"
                                }
                            },
                            headerFilterFunc: function(headerValue, rowValue, rowData) {
                                return headerValue === rowData.objetivos_fase1.completo.toString();
                            },
                            headerSortTristate: true,
                            minWidth: 20
                        },
                        {
                            title: "2° Fase",
                            field: "objetivos_fase2.avance",
                            formatter: function(cell) {
                                let data = cell.getRow().getData().objetivos_fase2;
                                return `<div class="badge ${data.completo ? 'badge-success' : 'badge-warning'}">
                                    ${data.realizados}/${data.total}
                                </div>`;
                            },
                            minWidth: 20,
                            accessorDownload: function(value, data) {
                                return `${data.objetivos_fase2.realizados} de ${data.objetivos_fase2.total}`;
                            }
                        },
                        {
                            title: "Estado",
                            field: "objetivos_fase2.estado",
                            formatter: function(cell) {
                                let data = cell.getRow().getData().objetivos_fase2;
                                return `<div class="badge ${data.completo ? 'badge-success' : 'badge-warning'}">
                                    ${data.completo ? 'Completo' : 'Incompleto'}
                                </div>`;
                            },
                            accessorDownload: function(value, data) {
                                return data.objetivos_fase2.completo ? 'Completo' : 'Incompleto';
                            },
                            sorter: function(a, b) {
                                return (a.completo === b.completo) ? 0 : (a.completo ? -1 : 1);
                            },
                            headerFilter: "dropdown",
                            headerFilterParams: {
                                values: {
                                    "true": "Completo",
                                    "false": "Incompleto"
                                }
                            },
                            headerFilterFunc: function(headerValue, rowValue) {
                                return headerValue === rowValue.completo.toString();
                            },
                            headerSortTristate: true,
                            minWidth: 20
                        }
                    ]
                },
                {
                    title: "PLANES DE MEJORA",
                    columns: [
                        {
                            title: "1° Fase",
                            field: "planes_fase1.avance",
                            formatter: function(cell) {
                                let data = cell.getRow().getData().planes_fase1;
                                return `<div class="badge ${data.completo ? 'badge-success' : 'badge-warning'}">
                                    ${data.registrados}/${data.requeridos}
                                </div>`;
                            },
                            minWidth: 20,
                            accessorDownload: function(value, data) {
                                return `${data.planes_fase1.registrados} de ${data.planes_fase1.requeridos}`;
                            }
                        },
                        {
                            title: "Estado",
                            field: "planes_fase1.estado",
                            formatter: function(cell) {
                                let data = cell.getRow().getData().planes_fase1;
                                return `<div class="badge ${data.completo ? 'badge-success' : 'badge-warning'}">
                                    ${data.completo ? 'Completo' : 'Incompleto'}
                                </div>`;
                            },
                            accessorDownload: function(value,data) {
                                return data.planes_fase1.completo ? 'Completo' : 'Incompleto';
                                
                                // return value.completo ? 'Completo' : 'Incompleto';
                            },
                            sorter: function(a, b) {
                                return (a.completo === b.completo) ? 0 : (a.completo ? -1 : 1);
                            },
                            headerFilter: "dropdown",
                            headerFilterParams: {
                                values: {
                                    "true": "Completo",
                                    "false": "Incompleto"
                                }
                            },
                            headerFilterFunc: function(headerValue, rowValue) {
                                return headerValue === rowValue.completo.toString();
                            },
                            headerSortTristate: true,
                            minWidth: 20
                        },
                        {
                            title: "2° Fase",
                            field: "planes_fase2.avance",
                            formatter: function(cell) {                                
                                // let data = cell.getValue();
                                let data = cell.getRow().getData().planes_fase2;
                                return `<div class="badge ${data.completo ? 'badge-success' : 'badge-warning'}">
                                    ${data.realizados}/${data.total}
                                </div>`;
                            },
                            minWidth: 20,
                            accessorDownload: function(value, data) {
                                return `${data.planes_fase2.realizados} de ${data.planes_fase2.total}`;
                            }
                        },
                        {
                            title: "Estado",
                            field: "planes_fase2.estado",
                            formatter: function(cell) {
                                // let data = cell.getValue();
                                let data = cell.getRow().getData().planes_fase2;
                                return `<div class="badge ${data.completo ? 'badge-success' : 'badge-warning'}">
                                    ${data.completo ? 'Completo' : 'Incompleto'}
                                </div>`;
                            },
                            accessorDownload: function(value, data) {
                                
                                return data.planes_fase2.completo ? 'Completo' : 'Incompleto';
                            },
                            sorter: function(a, b) {
                                return (a.completo === b.completo) ? 0 : (a.completo ? -1 : 1);
                            },
                            headerFilter: "dropdown",
                            headerFilterParams: {
                                values: {
                                    "true": "Completo",
                                    "false": "Incompleto"
                                }
                            },
                            headerFilterFunc: function(headerValue, rowValue) {
                                return headerValue === rowValue.completo.toString();
                            },
                            headerSortTristate: true,
                            minWidth: 20
                        },
                    ]
                },
                // {
                //     title: "ACCIONES",
                //     formatter: function(cell) {
                //         return `
                //             <button class="btn btn-sm btn-info ver-detalles" data-id="${cell.getData().id}">
                //                 <i class="fas fa-eye"></i>
                //             </button>`;
                //     },
                //     headerSort: false,
                //     hozAlign: "center",
                //     width: 100
                // }
            ],
            rowFormatter: function(row) {
                // Añade clases personalizadas a las filas
                row.getElement().classList.add("hover:bg-gray-50");
            },
            // Agrupación por evaluador
            // groupBy: "evaluador",
            // groupHeader: function(value, count, data, group){
            //     return `${value} <span class="badge badge-info">${count} evaluaciones</span>`;
            // },
            locale: true,
            langs: {
                "es": {
                    "data":{
                        "loading":"Cargando", //data loader text
                        "error":"Error", //data error text
                    },
                    "pagination": {
                        "page_size":"Elementos", //label for the page size select element
                        "page_title":"Ver Página",//tooltip text for the numeric page button, appears in front of the page number (eg. "Show Page" will result in a tool tip of "Show Page 1" on the page 1 button)
                    
                        "first": "Primera",
                        "first_title": "Primera Página",
                        "last": "Última",
                        "last_title": "Última Página",
                        "prev": "Anterior",
                        "prev_title": "Página Anterior",
                        "next": "Siguiente",
                        "next_title": "Página Siguiente",
                    },
                    "headerFilters": {
                        "default": "filtrar columna...",
                    },
                    "groups": {
                        "item": "elemento",
                        "items": "elementos"
                    }
                }
            },
            downloadDataFormatter: function(data){
                data.forEach(function(row){
                    row.competencias = row.competencias.join("/");
                    row.resultados = row.resultados.join("/");
                });
                return data;
            },
            downloadConfig: {
                // Formatear toda la data
                formatData: function(data) {
                    return data.map(row => ({
                        "EVALUADOR": row.evaluador,
                        "ÁREA": row.area,
                        "COMPETENCIAS": `${row.competencias[0]}/${row.competencias[1]}`,
                        "OBJETIVOS FASE 1": `${row.objetivos_fase1.registrados}/${row.objetivos_fase1.requeridos}`,
                        "OBJETIVOS FASE 2": `${row.objetivos_fase2.realizados}/${row.objetivos_fase2.total}`,
                        "PLANES FASE 1": `${row.planes_fase1.registrados}/${row.planes_fase1.requeridos}`,
                        "PLANES FASE 2": `${row.planes_fase2.realizados}/${row.planes_fase2.total}`,
                        "ESTADO COMPETENCIAS": row.competencias[0] === row.competencias[1] ? "COMPLETO" : "PENDIENTE",
                        "ESTADO OBJETIVOS F1": row.objetivos_fase1.completo ? "COMPLETO" : "PENDIENTE",
                        "ESTADO OBJETIVOS F2": row.objetivos_fase2.completo ? "COMPLETO" : "PENDIENTE",
                        "ESTADO PLANES F1": row.planes_fase1.completo ? "COMPLETO" : "PENDIENTE",
                        "ESTADO PLANES F2": row.planes_fase2.completo ? "COMPLETO" : "PENDIENTE"
                    }));
                },
                columnHeaders: true,
                columnGroups: true
            }
            
        });

        let tablaResumen = new Tabulator("#resumen-objetivos-table", {
            ajaxURL: "{{ route('seguimiento-evaluadores.resumen-objetivos') }}",
            ajaxParams: {
                campania_id: document.getElementById('campania-filter').value
            },
            layout: "fitColumns",
            pagination: true,
            paginationSize: 10,
            paginationSizeSelector: [10, 25, 50, 100],
            columns: [
                {
                    title: "EVALUADO",
                    field: "evaluado",
                    headerFilter: true,
                    sorter: "string",
                    minWidth: 200
                },
                {
                    title: "EVALUADOR",
                    field: "evaluador",
                    headerFilter: true,
                    sorter: "string",
                    minWidth: 200
                },
                // {
                //     title: "ÁREA",
                //     field: "area_evaluado",
                //     headerFilter: true,
                //     sorter: "string",
                //     minWidth: 150
                // },
                {
                    title: "SUBTOTAL",
                    field: "subtotal",
                    sorter: "number",
                    formatter: function(cell) {
                        return cell.getValue() + '%';
                    },
                    minWidth: 120
                },
                {
                    title: "TOTAL",
                    field: "total",
                    sorter: "number",
                    formatter: function(cell) {
                        const valor = parseFloat(cell.getValue());
                        const minimo = parseFloat(cell.getRow().getData().minimo);
                        const maximo = parseFloat(cell.getRow().getData().maximo);
                        
                        let clase = 'badge-warning';
                        if (valor >= maximo) {
                            clase = 'badge-success';
                        } else if (valor >= minimo) {
                            clase = 'badge-info';
                        }
                        
                        return `<div class="badge ${clase}">${valor}%</div>`;
                    },
                    minWidth: 120
                }
            ],
            locale: true,
            langs: {
                "es": {
                    "data":{
                        "loading":"Cargando", //data loader text
                        "error":"Error", //data error text
                    },
                    "pagination": {
                        "page_size":"Elementos", //label for the page size select element
                        "page_title":"Ver Página",//tooltip text for the numeric page button, appears in front of the page number (eg. "Show Page" will result in a tool tip of "Show Page 1" on the page 1 button)
                    
                        "first": "Primera",
                        "first_title": "Primera Página",
                        "last": "Última",
                        "last_title": "Última Página",
                        "prev": "Anterior",
                        "prev_title": "Página Anterior",
                        "next": "Siguiente",
                        "next_title": "Página Siguiente",
                    },
                    "headerFilters": {
                        "default": "filtrar columna...",
                    },
                    "groups": {
                        "item": "elemento",
                        "items": "elementos"
                    }
                }
            },
            // Configuración de idioma y otros ajustes...
        });
        // Agregar después de la configuración de la tabla
        table.on("tableBuilt", function(){
            console.log("Tabla construida");
        });

        table.on("dataLoaded", function(data){
            console.log("Datos cargados:", data);
        });

        table.on("dataLoadError", function(error){
            console.error("Error al cargar datos:", error);
        });

        // Agregar evento para el filtro de campaña
        document.getElementById('campania-filter').addEventListener('change', function(e) {
            let campaniaId = e.target.value;
            table.setData("{{ route('seguimiento-evaluadores.data') }}", { campania_id: campaniaId });

            tablaResumen.setData("{{ route('seguimiento-evaluadores.resumen-objetivos') }}", { campania_id: campaniaId });

            // Cargar resumen
            if(campaniaId) {
                fetch("{{ route('seguimiento-evaluadores.resumen') }}?campania_id=" + campaniaId)
                    .then(response => response.json())
                    .then(data => {
                        // Mostrar sección de resumen
                        document.getElementById('resumen').style.display = 'block';
                        
                        // Actualizar Competencias
                        document.getElementById('barra-competencias').style.width = data.competencias.porcentaje + '%';
                        document.getElementById('barra-competencias').textContent = data.competencias.porcentaje + '%';
                        document.getElementById('texto-competencias').textContent = 
                            `${data.competencias.realizadas} de ${data.competencias.total} realizadas`;
                        
                        // Actualizar Objetivos
                        document.getElementById('barra-objetivos-1').style.width = data.objetivos.porcentaje_primera + '%';
                        document.getElementById('barra-objetivos-1').textContent = data.objetivos.porcentaje_primera + '%';
                        document.getElementById('texto-objetivos-1').textContent = 
                            `${data.objetivos.primera_fase} de ${data.objetivos.total} registrados`;
                            
                        document.getElementById('barra-objetivos-2').style.width = data.objetivos.porcentaje_segunda + '%';
                        document.getElementById('barra-objetivos-2').textContent = data.objetivos.porcentaje_segunda + '%';
                        document.getElementById('texto-objetivos-2').textContent = 
                            `${data.objetivos.segunda_fase} de ${data.objetivos.total} realizados`;
                        
                        // Actualizar Planes
                        document.getElementById('barra-planes-1').style.width = data.planes.porcentaje_primera + '%';
                        document.getElementById('barra-planes-1').textContent = data.planes.porcentaje_primera + '%';
                        document.getElementById('texto-planes-1').textContent = 
                            `${data.planes.primera_fase} de ${data.planes.total} registrados`;
                            
                        document.getElementById('barra-planes-2').style.width = data.planes.porcentaje_segunda + '%';
                        document.getElementById('barra-planes-2').textContent = data.planes.porcentaje_segunda + '%';
                        document.getElementById('texto-planes-2').textContent = 
                            `${data.planes.segunda_fase} de ${data.planes.total} realizados`;
                    });
            } else {
                document.getElementById('resumen').style.display = 'none';
            }
            
        });

        // Botón de exportación a Excel
        document.getElementById("download-xlsx").addEventListener("click", function(){
            table.download("xlsx", "evaluadores_seguimiento.xlsx", {
                sheetName: "Seguimiento",
                downloadFormatter: function(data){
                    return data.map(row => ({
                        "EVALUADOR": row.evaluador,
                        "AVANCE (Competencias)": `${row.competencias[0]}/${row.competencias[1]}`,
                        "AVANCE (Resultados)": `${row.resultados[0]}/${row.resultados[1]}`,
                        "EV. POR COMPETENCIAS": `${row.competencias[0]} de ${row.competencias[1]}`,
                        "EV. POR RESULTADOS": `${row.resultados[0]} de ${row.resultados[1]}`
                    }));
                },
                documentProcessing: function(workbook) {
                    var worksheet = workbook.Sheets["Seguimiento"];
                    worksheet["!cols"] = [
                        {wch: 60}, // Evaluador
                        {wch: 40}, // Avance
                        {wch: 20}, // Competencias
                        {wch: 20}  // Resultados
                    ];
                    return workbook;
                }
            });
        });

        // Botón de exportación a PDF
        document.getElementById("download-pdf").addEventListener("click", function(){
            table.download("pdf", "evaluadores_seguimiento.pdf", {
                orientation: "landscape",
                title: "Seguimiento de Evaluadores",
                autoTable: {
                    styles: {
                        cellPadding: 2,
                        fontSize: 8,
                        font: 'helvetica'
                    },
                    // columnStyles: {
                    //     0: {cellWidth: 100}, // Evaluador
                    //     1: {cellWidth: 80}, // Avance
                    //     2: {cellWidth: 40}, // Competencias
                    //     3: {cellWidth: 40}  // Resultados
                    // }
                }
            });
        });

        // Agregar después de los otros event listeners
        document.getElementById('send-emails').addEventListener('click', function() {
            // if (typeof Swal === 'undefined') {
            //     console.error('SweetAlert2 no está cargado');
            //     alert('No se puede mostrar el diálogo. Por favor, recarga la página.');
            //     return;
            // }
            
            let campaniaId = document.getElementById('campania-filter').value;
            
            if (!campaniaId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: 'Por favor seleccione una campaña primero'
                });
                return;
            }

            Swal.fire({
                title: '¿Está seguro?',
                text: "Se enviarán correos a todos los evaluadores (de objetivos) pendientes",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3c4651',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mostrar loading
                    Swal.fire({
                        title: 'Enviando correos...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Realizar la petición
                    fetch("{{ route('seguimiento-evaluadores.enviar-correos') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            campania_id: campaniaId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire(
                                '¡Enviado!',
                                'Los correos han sido enviados correctamente.',
                                'success'
                            );
                        } else {
                            throw new Error(data.message || 'Error al enviar los correos');
                        }
                    })
                    .catch(error => {
                        Swal.fire(
                            'Error',
                            error.message,
                            'error'
                        );
                    });
                }
            });
        });        
        
    });
</script>

{{-- <style>
    .tabulator {
        font-size: 14px;
        border-radius: 0.75rem;
        overflow: hidden;
    }

    .tabulator-header {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
    }

    .tabulator-row {
        border-bottom: 1px solid #dee2e6;
    }

    .tabulator-row.tabulator-row-even {
        background-color: #f8f9fa;
    }

    .tabulator-footer {
        background-color: #f8f9fa;
        border-top: 2px solid #dee2e6;
    }

    /* Estilos responsivos */
    @media (max-width: 768px) {
        .tabulator-col {
            display: block;
            width: 100% !important;
        }
    }

    /* Estilos para los filtros */
    .tabulator-header-filter input {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 4px 8px;
        width: 100%;
    }

    /* Estilos para el grupo */
    .tabulator-group {
        background-color: #e9ecef;
        padding: 10px;
        border-bottom: 2px solid #dee2e6;
    }
</style> --}}

<style>
    
    #campania-filter {
        background-color: white;
        border: 1px solid #d2d6dc;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        min-width: 200px;
    }

    #campania-filter:focus {
        outline: none;
        border-color: #6ecbc9;
        box-shadow: 0 0 0 2px rgba(110, 203, 201, 0.2);
    }
    
    /* Estilos principales de la tabla */
    .tabulator {
        font-family: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
        background-color: #ffffff;
        border: none;
        border-radius: 0.5rem;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    /* Cabecera de la tabla */
    .tabulator-header {
        background-color: #3c4651 !important;
        border: none;
        padding: 10px 0;
    }

    .tabulator-header .tabulator-col {
        background-color: #3c4651 !important;
        border-right: 1px solid rgba(255, 255, 255, 0.5);
    }

    .tabulator-header .tabulator-col-title {
        color: #ffffff;
        font-weight: 600;
        padding: 8px;
    }

    /* Filas de la tabla */
    .tabulator-row {
        border-bottom: 1px solid #f4f4f4;
        transition: background-color 0.3s;
    }

    .tabulator-row:hover {
        background-color: #f8f9fa !important;
    }

    .tabulator-row.tabulator-row-even {
        background-color: #fcfcfc;
    }

    .tabulator-row .tabulator-cell {
        padding: 12px 8px;
        border-right: none;
    }

    /* Filtros */
    .tabulator-header-filter input {
        border: 1px solid #e2e8f0;
        border-radius: 0.375rem;
        padding: 6px 12px;
        font-size: 0.875rem;
        width: 100%;
        background-color: #ffffff;
        transition: all 0.3s;
    }

    .tabulator-header-filter input:focus {
        outline: none;
        border-color: #3c8dbc;
        box-shadow: 0 0 0 2px rgba(60, 141, 188, 0.2);
    }

    /* Paginación */
    .tabulator-footer {
        background-color: #ffffff;
        border-top: 1px solid #f4f4f4;
        padding: 8px;
    }

    .tabulator-paginator {
        justify-content: flex-end;
    }

    .tabulator-page {
        margin: 0 2px;
        padding: 6px 12px;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        background: #ffffff;
        color: #444;
        transition: all 0.3s;
    }

    .tabulator-page:hover:not(.tabulator-page-disabled) {
        background: #3c8dbc;
        color: #ffffff;
        border-color: #6ecbc9;
    }

    .tabulator-page.active {
        background: #6ecbc9;
        color: #ffffff;
        border-color: #6ecbc9;
    }

    /* Barra de progreso personalizada */
    .progress {
        height: 25px;
        background-color: #f4f4f4;
        margin-bottom: 0.5rem;
    }

    .progress-bar {
        transition: width 0.6s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        color: #ffffff;
    }

    .progress-bar.bg-primary {
        background-color: #3c4651 !important;
    }

    .progress-bar.bg-secondary {
        background-color: #6ecbc9 !important;
    }

    /* Responsivo */
    @media (max-width: 768px) {
        .tabulator-col {
            display: block;
            width: 100% !important;
        }
        
        .progress {
            height: 20px;
        }
    }
</style>

@stop

@section('css')

@stop

@section('js')
    {{-- El script de Tabulator ahora viene del asset compilado --}}
    {{-- <script src="{{ asset('js/app.js') }}"></script> --}}

    {{-- SweetAlert2 --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    
    {{-- Scripts necesarios para exportación --}}
    {{-- <script type="text/javascript" src="https://oss.sheetjs.com/sheetjs/xlsx.full.min.js"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>

    {{-- Tu script actual --}}
    <script type="text/javascript">
        // ...existing code...
    </script>
    
@stop