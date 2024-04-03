            {{-- @if (!$pdfMode) --}}
                <div @if (!$createMode || $pdfMode) style="display:none" @endif>
                    <div>
                        <div class="row">
                            <h4>
                                <div class="h4 col-xs-12">Datos del usuario</div>
                            </h4>
                        </div>
                        <div class="row">
                            @if (!$viewMode)
                                <div class="form-group col-sm-4">
                                    <label for="search_personal_id">Buscar por DNI</label>
                                    <div class="input-group">
                                    <input type="text" 
                                    inputmode="numeric" 
                                    name="search_personal_dni" 
                                    wire:model.defer="search_personal_dni"
                                    wire:keydown.enter='buscar_dni' 
                                    wire:keydown.tab="buscar_dni"
                                    wire:keydown.arrow-right="buscar_dni"
                                    id="search_personal_dni" 
                                    class="form-control form-control-lg"
                                    placeholder="DNI" 
                                    >
                                      <a wire:click="buscar_dni()" type="button" class="btn btn-lg btn-primary" 
                                      ><i class="fas fa-search"></i></a>
                                    </div>
                                </div>
                                                              
                                <div class="form-group col-sm-4">
                                    <label for="recargar">Recargar listas seleccionables</label>
                                    <div class="input-group">
                                      <a wire:click="listarSelects()" type="button" class="btn btn-primary" id="button-addon2">Recargar <i class="fas fa-sync-alt"></i></a>
                                    </div>
                                </div>
                                {{-- @error('search_personal_dni') <span class="error text-danger">{{ $message }}</span> @enderror --}}
                            @endif

                        </div>
                        
                        <div class="row">

                            <div class="form-group col-sm-4">
                                <label for="personal_id">Personal *</label>
                                <div wire:ignore>
                                    <select
                                    @if ($viewMode) readonly disabled @endif
                                    name="personal_id"
                                    class="form-control"
                                    id="personal_id"
                                    placeholder="Personal">
                                    </select>
                                </div>
                                @error('personal_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                                
                            <div>@if ($dni) {{ 'DNI: '.$dni }} @endif </div>
                            </div>

                            <div class="form-group col-sm-4">
                                <label for="empresa_id">Empresa *</label>
                                <div wire:ignore>
                                <select @if ($viewMode) readonly disabled @endif 
                                name="empresa_id"
                                    class="form-control" 
                                    id="empresa_id" 
                                    placeholder="Empresas">
                                    {{-- <option value="">Seleccione</option> --}}
                                </select>
                                </div>
                                @error('empresa_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="gerencia_id">Gerencia *</label>
                                <div wire:ignore>
                                <select @if ($viewMode) readonly disabled @endif 
                                name="gerencia_id"
                                    class="form-control" 
                                    id="gerencia_id" placeholder="Gerencias">
                                </select>
                                </div>
                                @error('gerencia_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <label for="sede_id">Sede *</label>
                                <div wire:ignore>
                                <select @if ($viewMode) readonly disabled @endif name="sede_id"
                                    class="form-control" id="sede_id"
                                    placeholder="Sede">
                                </select>
                                </div>
                                @error('sede_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="area_id">Area *</label>
                                <div wire:ignore>
                                <select @if ($viewMode) readonly disabled @endif name="area_id"
                                    class="form-control" id="area_id"
                                    placeholder="Area">
                                </select>
                                </div>
                                @error('area_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="cargo_id">Cargo *</label>
                                <div wire:ignore>
                                <select @if ($viewMode) readonly disabled @endif name="cargo_id"
                                    class="form-control" id="cargo_id"
                                    placeholder="Cargo">
                                </select>
                                </div>
                                @error('cargo_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <h4>
                                <div class="mt-3 h4 col-xs-12">Datos del gestor</div>
                            </h4>
                        </div>
                        <div class="row">

                            <div class="form-group col-sm-4">
                                <label for="responsable_id">Responsable *</label>
                                <div wire:ignore>
                                    <select @if ($viewMode) readonly disabled @endif name="responsable_id"
                                        class="form-control"
                                        id="responsable_id" placeholder="Responsable">
                                    </select>
                                </div>
                                @error('responsable_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group col-sm-4">
                                <label for="responsable_area_id">Area *</label>
                                <div wire:ignore>
                                    <select @if ($viewMode) readonly disabled @endif
                                        name="responsable_area_id" class="form-control"
                                        id="responsable_area_id"
                                        placeholder="Responsable Área">
                                    </select>
                                </div>
                                @error('responsable_area_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-sm-4">
                                <label for="responsable_cargo_id">Cargo *</label>
                                <div wire:ignore>
                                    <select @if ($viewMode) readonly disabled @endif
                                        name="responsable_cargo_id" class="form-control"                                       
                                        id="responsable_cargo_id" placeholder="Responsable Cargo">
                                    </select>
                                </div>
                                @error('responsable_cargo_id')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <h4>
                                <div class="mt-3 h4 col-xs-12">Fecha de entrega</div>
                            </h4>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-4">
                                <input wire:model="fecha" type="date" class="form-control" id="fecha"
                                    placeholder="Fecha">
                                @error('fecha')
                                    <span class="error text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        @if (!$viewMode)
                            <div class="mt-3 row">
                                <h4>
                                    <div class="h4 col-xs-12">Activos</div>
                                </h4>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="activo_id">Buscar activo por número de serie/IMEI (mín. 4 carácteres)*</label>
                                    <div class="input-group">
                                        <input type="text" 
                                        {{-- inputmode="search"  --}}
                                        name="activo_id" 
                                        wire:model.defer="activo_id"
                                            wire:keydown.enter='agregar' 
                                            id="activo_id" 
                                            class="form-control"
                                            placeholder="Serial number / IMEI1 activo">
                                        <a wire:click="agregar" type="button" class="btn btn-primary" id="button-addon2"><i class="fas fa-search"></i></a>
                                    </div>
                                    @error('activo_id')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                    @error('activos_list')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group col-sm-4">
                                    <label for="Cargar Preasignados">Activos Preasignados</label>
                                    <div class="input-group">
                                      <a wire:click="cargar_preasignados()" type="button" class="btn btn-primary" id="button-addon2">Cargar <i class="fas fa-retweet"></i></a>
                                    </div>
                                </div>
                            </div>
                            @error('activos_selected')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-sm">
                                <thead style="{z-index: 1 ;}">
                                    <tr>
                                        @can('borrar-activos-asignacion')
                                            @if (!$viewMode)
                                                <th>Opc.</th>
                                            @endif
                                        @endcan
                                        <th>Activo</th>
                                        <th>Accesorios</th>
                                        <th>Condicion</th>
                                        <th>Vigencia</th>
                                        <th>Fecha Vigencia</th>
                                        <th>Observaciones de entrega</th>
                                        <th class="table-warning">Observaciones de activo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($activos_list)
                                        @php
                                        @endphp
                                        @foreach ($activos_list as $index => $row)
                                            @if ($row['eliminado'] == 0)
                                                <tr @if ($row['preasignado'])
                                                    class="table-warning"
                                                @endif >
                                                    @can('borrar-activos-asignacion')
                                                        @if (!$viewMode)
                                                            <td width="45">
                                                                <div class="btn-group">
                                                                    @can('borrar-activos-asignacion')
                                                                        <a class="btn btn-sm btn-danger"
                                                                            wire:click="quitar_activo({{ $index }})">
                                                                            <i class="fas fa-times"></i></a>
                                                                        <a data-toggle="modal"
                                                                            data-target="#updateActivoModal"
                                                                            class="btn btn-sm btn-warning"
                                                                            wire:click="edit_activo({{ $index }})"> <i
                                                                                class="fas fa-edit"></i> </a>
                                                                    @endcan
                                                                </div>
                                                            </td>
                                                        @endif
                                                    @endcan
                                                    <td>
                                                        {!! $row['descripcion'] !!} 
                                                        @if ($row['preasignado']) <em>(Preasignado)</em> @endif 
                                                        @if ($row['regularizacion']) <em>(Regularizacion)</em> @endif
                                                        @if (!empty($row['fecha_de_asignacion'])) <em>Fecha de asignacion : {{$row['fecha_de_asignacion']}}</em> @endif
                                                    </td>
                                                    <td>
                                                        @foreach ($row['accesorios_names'] as $index => $item)
                                                            @if (count($row['accesorios_names']) - 1 > $index)
                                                                {{ $item . ', ' ?? '' }}
                                                            @else
                                                                {{ $item ?? '' }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        {{ $row['performance'] ?? '' }}
                                                        @error('activos_list.'.$index.'.performance_id')
                                                            <span class="error text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ $row['vigencia'] ?? '' }}
                                                        @error('activos_list.'.$index.'.vigencia_id')
                                                            <span class="error text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        {{ $row['fecha_vigencia'] ? $row['fecha_vigencia']: '' }}
                                                    </td>
                                                    <td>
                                                        {{ $row['observaciones'] ?? '' }}
                                                    </td>
                                                    <td>
                                                        {{ $row['observaciones_activo'] ?? '' }}
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            {{-- @else --}}
                <div id='pdf_asignacion' @if (!$pdfMode) style="display:none" @endif>
                    <table border="0" cellpadding="0" cellspacing="0" id="sheet0" class="sheet0 gridlines">
                        <col class="col0">
                        <col class="col1">
                        <col class="col2">
                        <col class="col3">
                        <col class="col4">
                        <col class="col5">
                        <col class="col6">
                        <tbody>
                            <tr class="row0">
                                <td class="" rowspan="5" style="border: 1px solid black;" align="center">
                                    <img src="{{ asset('img/icon/VanguardPeru-Sp-2cWeb-xsm.png') }}"
                                        alt="logo_vanguard_peru" />
                                </td>
                                <td class="column1 style2 s" colspan="6">ACTA DE ENTREGA DE ACTIVOS DE TI
                                </td>
                            </tr>
                            <tr class="row1">
                                <td class="column1 style4 s style7" colspan="5"><span
                                        style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Documento:
                                    </span><span
                                        style="color:#000000; font-family:'Calibri'; font-size:11pt">Registro</span>
                                </td>
                                <td class="column6 style8 s"><span
                                        style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Código:
                                    </span><span
                                        style="color:#000000; font-family:'Calibri'; font-size:11pt">LOV-TI-RE-01</span>
                                </td>
                            </tr>
                            <tr class="row2">
                                <td class="column1 style4 s style7" colspan="5"><span
                                        style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Elaborado
                                        por: </span><span
                                        style="color:#000000; font-family:'Calibri'; font-size:11pt">Administrador de
                                        Infraestructura de TI</span></td>
                                <td class="column6 style9 s"><span
                                        style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Versión:
                                    </span><span style="color:#000000; font-family:'Calibri'; font-size:11pt">01</span>
                                </td>
                            </tr>
                            <tr class="row3">
                                <td class="column1 style4 s style7" colspan="5"><span
                                        style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Revisado
                                        por: </span><span
                                        style="color:#000000; font-family:'Calibri'; font-size:11pt">Jefatura de
                                        TI</span></td>
                                <td class="column6 style9 s"><span
                                        style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Fecha:
                                    </span><span
                                        style="color:#000000; font-family:'Calibri'; font-size:11pt">04/07/2022</span>
                                </td>
                            </tr>
                            <tr class="row4">
                                <td class="column1 style11 s style13" colspan="5"><span
                                        style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Aprobado
                                        por: </span><span
                                        style="color:#000000; font-family:'Calibri'; font-size:11pt">Gerente
                                        Corporativo de Operaciones I.</span></td>
                                <td class="column6 style14 s"><span
                                        style="font-weight:bold; color:#000000; font-family:'Calibri'; font-size:11pt">Página
                                    </span><span style="color:#000000; font-family:'Calibri'; font-size:11pt">1 de
                                        1</span></td>
                            </tr>
                            <tr class="row5">
                                <td class="column0 style15 null"></td>
                                <td class="column1 style15 null"></td>
                                <td class="column2 style15 null"></td>
                                <td class="column3 style15 null"></td>
                                <td class="column4 style15 null"></td>
                                <td class="column5 style16 null"></td>
                                <td class="column6 style16 null"></td>
                            </tr>
                            <tr class="row6">
                                <td class="column0 style15 null"></td>
                                <td class="column1 style15 null"></td>
                                <td class="column2 style15 null"></td>
                                <td class="column3 style15 null"></td>
                                <td class="column4 style15 null"></td>
                                <td class="column5 style15 s">FECHA</td>
                                <td class="column6 style17 f">
                                    {{-- {{ date('d-m-Y', $fecha) }}</td> --}}
                                    {{ date('d-m-Y', strtotime($fecha)) }}</td>
                                    {{-- {{ date('d-m-Y', strtotime($asignacion_guardada->fecha)) }}</td> --}}
                            </tr>
                            <tr class="row7">
                                <td class="column0 style18 s">EMPRESA</td>
                                <td class="column1 style19 s" colspan="3">
                                    {{ App\Models\Empresa::find($empresa_id)->name ?? '' }}</td>
                                <td class="column4 style20 null"></td>
                                <td class="column5 style15 null"></td>
                                <td class="column6 style15 null"></td>
                            </tr>
                            <tr class="row8">
                                <td class="column0 style18 s">SEDE DE TRABAJO</td>
                                <td class="column1 style19 s" colspan="3">
                                    {{ App\Models\Sede::find($sede_id)->name ?? '' }}</td>
                                <td class="column4 style20 null"></td>
                                <td class="column5 style15 null"></td>
                                <td class="column6 style15 null"></td>
                            </tr>
                            <tr class="row9">
                                <td class="column0 style18 null"></td>
                                <td class="column1 style20 null"></td>
                                <td class="column2 style20 null"></td>
                                <td class="column3 style20 null"></td>
                                <td class="column4 style20 null"></td>
                                <td class="column5 style15 null"></td>
                                <td class="column6 style15 null"></td>
                            </tr>
                            <tr class="row10">
                                <td class="column0 style18 s">USUARIO</td>
                                <td class="column1 style19 s" colspan="6">
                                    {{ App\Models\Personal::find($personal_id)->name ?? '' }}</td>
                            </tr>
                            <tr class="row11">
                                <td class="column0 style18 s">AREA</td>
                                <td class="column1 style19 s" colspan="6">
                                    {{ App\Models\Area::find($area_id)->name ?? '' }}</td>
                            </tr>
                            <tr class="row12">
                                <td class="column0 style18 s">PUESTO </td>
                                <td class="column1 style21 s" colspan="6">
                                    {{ App\Models\Cargo::find($cargo_id)->name ?? '' }}</td>
                            </tr>
                            <tr class="row13">
                                <td class="column0 style18 null"></td>
                                <td class="column1 style20 null"></td>
                                <td class="column2 style20 null"></td>
                                <td class="column3 style20 null"></td>
                                <td class="column4 style20 null"></td>
                                <td class="column5 style18 null"></td>
                                <td class="column6 style18 null"></td>
                            </tr>
                            <tr class="row14">
                                <td class="column0 style18 s">RESPONSABLE</td>
                                <td class="column1 style19 s" colspan="6">
                                    {{ App\Models\Personal::find($responsable_id)->name ?? '' }}</td>
                            </tr>
                            <tr class="row15">
                                <td class="column0 style18 s">AREA</td>
                                <td class="column1 style19 s" colspan="6">
                                    {{ App\Models\Area::find($responsable_area_id)->name ?? '' }}</td>
                            </tr>
                            <tr class="row16">
                                <td class="column0 style22 null"></td>
                                <td class="column1 style22 null"></td>
                                <td class="column2 style22 null"></td>
                                <td class="column3 style22 null"></td>
                                <td class="column4 style22 null"></td>
                                <td class="column5 style23 null"></td>
                                <td class="column6 style23 null"></td>
                            </tr>
                            <tr class="row17">
                                <td class="column0 style24 s style25" colspan="7">Por medio de la presente acta, la
                                    EMPRESA, por intermedio de su GESTOR, hace entrega de los EQUIPOS con sus
                                    respectivos accesorios detallados líneas abajo al USUARIO para desarrollar
                                    exclusivamente sus actividades designadas. </td>
                            </tr>
                            <tr class="row18">
                                <td class="column0 style26 null"></td>
                                <td class="column1 style27 null"></td>
                                <td class="column2 style27 null"></td>
                                <td class="column3 style27 null"></td>
                                <td class="column4 style27 null"></td>
                                <td class="column5 style27 null"></td>
                                <td class="column6 style27 null"></td>
                            </tr>
                            <tr class="row19">
                                <td class="column0 style28 s" colspan="2">Los Equipos Incluyen:</td>
                                <td class="column2 style29 null"></td>
                                <td class="column3 style16 null"></td>
                                <td class="column4 style16 null"></td>
                                <td class="column5 style16 null"></td>
                                <td class="column6 style16 null"></td>
                            </tr>
                            <tr class="row20">
                                <td class="column0 style30 s style32" colspan="2">EQUIPO(S)</td>
                                <td class="column1 style30 s style32" colspan="1">ACCESORIOS</td>
                                <td class="column2 style30 s style32" colspan="1">CONDICION</td>
                                <td class="column3 style30 s style32" colspan="1">VIGENCIA</td>
                                <td class="column4 style30 s style32" colspan="1">FECHA VIGENCIA</td>
                                <td class="column5 style33 s">OBSERVACION</td>
                            </tr>
                            @foreach ($activos_list as $activo)
                                <tr class="row21">
                                    <td class="column0 style34 null" colspan="2">{!! $activo['descripcion'] !!} </td>
                                    <td class="column1 style34 null" colspan="1">                                      
                                      @foreach ($activo['accesorios_names'] as $index => $item)
                                        @if (count($activo['accesorios_names']) - 1 > $index)
                                            {{ $item . ', ' ?? '' }}
                                        @else
                                            {{ $item ?? '' }}
                                        @endif
                                      @endforeach
                                    </td>
                                    <td class="column2 style34 null" colspan="1">{{ $activo['performance'] }} </td>
                                    <td class="column3 style34 null" colspan="1">{{ $activo['vigencia'] }} </td>
                                    <td class="column4 style34 null" colspan="1">                                    
                                    {{ $activo['fecha_vigencia'] ? date('d-m-Y', strtotime($activo['fecha_vigencia'])) : '' }}
                                    </td>
                                    <td class="column5 style35 null">{{$activo['observaciones']}}</td>
                                </tr>
                            @endforeach

                            <tr class="row26">
                                <td class="column0 style23 null"></td>
                                <td class="column1 style23 null"></td>
                                <td class="column2 style23 null"></td>
                                <td class="column3 style23 null"></td>
                                <td class="column4 style23 null"></td>
                                <td class="column5 style23 null"></td>
                                <td class="column6 style23 null"></td>
                            </tr>
                            <tr class="row27">
                                <td class="column0 style37 s style38" colspan="7">Las computadoras portátiles son
                                    propiedad de la empresa.El monto por reposición para el EQUIPO se consultará al área
                                    de TI, el mismo que será asumido en su totalidad por el USUARIO en caso de daño,
                                    pérdida o robo en cualquiera de sus modalidades previstas dentro o fuera de las
                                    instalaciones de la EMPRESA de acuerdo con lo mencionado en las políticas de gestión
                                    de activos de la empresa.</td>
                            </tr>
                            <tr class="row28">
                                <td class="column0 style37 s style38" colspan="7">Por tanto, EL USUARIO se obliga a usar de manera responsable las condiciones de trabajo que le está entregando en este acto. De comprobarse el uso de estos sin sustento debido, diligencia debida y/o con mala fe por parte de EL USUARIO, entonces AUTORIZA a LA EMPRESA a realizar los descuentos pertinentes sobre sus remuneraciones, beneficios sociales y liquidación al cese hasta reponer el valor total de la condición y/o condiciones de trabajo que se le hace entrega en este acto.</td>
                            </tr>
                            <tr class="row29" rowspan="4">
                                <td class="column0 style39 null" colspan="3" rowspan="4">
                                    {{-- <canvas id="firma_responsable"></canvas> --}}
                                    
                                    @if ($firma_responsable)
                                      <img style="border: 0; max-width: 100%; height: auto;" src="{{ $firma_responsable ?? '' }}"
                                        alt="Firma del responsable" id="firma_responsable">                                      
                                    @else
                                      <img style="border: 0; max-width: 100%; height: auto;" src="{{ asset('img/logos/sin_firma.png') }}"
                                        alt="firma no registrada" id="firma_responsable"/>
                                    @endif
                                    
                                    @error('firma_responsable')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror

                                    <a class="btn btn-default" data-toggle="modal" data-target="#firmaModal"
                                        wire:click='agregarFirma("responsable",{{ $responsable_id }})'>Agregar
                                        Firma</a>
                                    <br>
                                    <br>
                                </td>
                                <td class="column3 style39 null" rowspan="4"></td>
                                <td class="column4 style39 null" colspan="3" rowspan="4">
                                    {{-- <canvas id="firma_personal"></canvas>   --}}
                                    @if ($firma_personal )
                                      <img style="border: 0; max-width: 100%; height: auto;" src="{{ $firma_personal ?? '' }}"
                                        alt="Firma del usuario" id="firma_personal">                                      
                                    @else
                                      <img style="border: 0; max-width: 100%; height: auto;" src="{{ asset('img/logos/sin_firma.png') }}"
                                        alt="firma no registrada" id="firma_personal"/>
                                    @endif                                        

                                    @error('firma_personal')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror

                                    <a class="btn btn-default" data-toggle="modal" data-target="#firmaModal"
                                        wire:click='agregarFirma("personal",{{ $personal_id }})'>Agregar
                                        Firma</a>

                                    <br>
                                    <br>
                                </td>
                            </tr>
                            <tr class="row30">
                                <td class="column0 style16 null"></td>
                                <td class="column1 style16 null"></td>
                                <td class="column2 style16 null"></td>
                                <td class="column3 style16 null"></td>
                                <td class="column4 style16 null"></td>
                                <td class="column5 style16 null"></td>
                                <td class="column6 style16 null"></td>
                            </tr>
                            <tr class="row31">
                                <td class="column0 style16 null"></td>
                                <td class="column1 style16 null"></td>
                                <td class="column2 style16 null"></td>
                                <td class="column3 style16 null"></td>
                                <td class="column4 style16 null"></td>
                                <td class="column5 style16 null"></td>
                                <td class="column6 style16 null"></td>
                            </tr>
                            <tr class="row32">
                                <td class="column0 style16 null"></td>
                                <td class="column1 style16 null"></td>
                                <td class="column2 style39 null"></td>
                                <td class="column3 style16 null"></td>
                                <td class="column4 style16 null"></td>
                                <td class="column5 style16 null"></td>
                                <td class="column6 style16 null"></td>
                            </tr>
                            <tr class="row33">
                                <td class="column0 style40 s" colspan="3">
                                    {{ App\Models\Personal::find($responsable_id)->name ?? ''}}</td>
                                <td class="column3 style41 null"></td>
                                <td class="column4 style42 f" colspan="3">
                                    {{ App\Models\Personal::find($personal_id)->name ?? ''}}</td>
                            </tr>
                            <tr class="row34">
                                <td class="column0 style43 s" colspan="3">
                                    {{ App\Models\Cargo::find($responsable_cargo_id)->name ?? ''}}</td>
                                <td class="column3 style16 null"></td>
                                <td class="column4 style18 s">DNI:</td>
                                <td class="column5 style44 n style45" colspan="2">
                                    {{ App\Models\Personal::find($personal_id)->dni ?? '' }}</td>
                            </tr>
                            <tr class="row35">
                                <td class="column0 style16 null"></td>
                                <td class="column1 style16 null"></td>
                                <td class="column2 style16 null"></td>
                                <td class="column3 style16 null"></td>
                                <td class="column4 style16 null"></td>
                                <td class="column5 style16 null"></td>
                                <td class="column6 style16 null"></td>
                            </tr>
                            <tr class="row36">
                                <td class="column0 style46 s style47" colspan="7">Prohibida su copia total o
                                    parcial de este documento sin la autorización de la Gerencia de Los Olivos de
                                    Villacurí S.A.C</td>
                            </tr>
                            <tr class="row37">
                                <td class="column0 style16 null" colspan="7">
                                    <div class="row">
                                        <div class="form-group col-sm-8">
                                            <label for="correo_personal">Enviar al siguiente correo</label>
                                            <input type="text" inputmode="email" name="correo_personal"
                                                wire:model.defer="correo_personal" id="correo_personal"
                                                class="form-control" placeholder="Correo a enviar">
                                                <small id="correo_personal" class="form-text text-muted">Siempre verifique que el correo esté correctamente ingresado.</small>
                                                
                                              @error('correo_personal')
                                                  <span class="error text-danger">{{ $message }}</span>
                                              @enderror
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            {{-- @endif --}}
