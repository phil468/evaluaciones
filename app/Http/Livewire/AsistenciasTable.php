<?php

namespace App\Http\Livewire;

use App\Models\Asistencium;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\Column;
use PhpOffice\PhpSpreadsheet\Calculation\Logical\Boolean;

class AsistenciasTable extends LivewireDatatable
{
    public $hideable = 'inline';
    public $exportable = true;
    public $afterTableSlot = 'components.selected';
    public $numeroSerieValidado=true, $fileUpload;
    public $updateMode = false;

    public function builder()
    {
        return Asistencium::query()
        ->where('asistencia.active',1)->where('capacitaciones.activo',1)
        ->where('capacitaciones.deleted_at',null)->where('sesiones.deleted_at',null)->where('asistencia.deleted_at',null)

        ->leftJoin('capacitacion_has_personal','capacitacion_has_personal.id','=','asistencia.capacitacion_has_personal_id')
        ->leftJoin('sesiones','sesiones.id','=','asistencia.sesion_id')
        ->leftJoin('capacitaciones','capacitaciones.id','=','sesiones.capacitacion_id')
        ->leftJoin('modalidades','modalidades.id','=','capacitaciones.modalidad_id')
        ->leftJoin('tipo_de_capacitaciones','tipo_de_capacitaciones.id','=','capacitaciones.capacitaciones_tipo_id')
        ->leftJoin('personal as expositor','expositor.id','=','capacitaciones.expositor_id')
        ->leftJoin('temas','temas.id','=','capacitaciones.tema_id')
        ->leftJoin('personal','personal.id','=','capacitacion_has_personal.personal_id')
        ->leftJoin('empresas','empresas.id','=','capacitacion_has_personal.empresa_id')
        ->leftJoin('gerencias','gerencias.id','=','capacitacion_has_personal.gerencia_id')
        ->leftJoin('sedes','sedes.id','=','capacitacion_has_personal.sede_id')
        ->leftJoin('areas','areas.id','=','capacitacion_has_personal.area_id')
        ->leftJoin('cargos','cargos.id','=','capacitacion_has_personal.cargo_id')
        ->leftJoin('planillas','planillas.id','=','capacitacion_has_personal.planilla_id')
        ->leftJoin('tipo_de_trabajador','tipo_de_trabajador.id','=','capacitacion_has_personal.tipo_de_trabajador_id')
        ->leftJoin('tipo_de_personal','tipo_de_personal.id','=','capacitacion_has_personal.tipo_de_personal_id')
        ;

    }

    public $model = Asistencium::class;

    public function columns()
    {
        return [
        Column::name('personal.dni')->label('DNI')->searchable()->filterable()->defaultSort('asc'),
        Column::name('personal.name')->label('Personal')->searchable()->filterable()->defaultSort('asc'),
        Column::name('personal.sexo')->label('Sexo')->searchable()->defaultSort('asc'),
        Column::name('empresas.name')->label('Empresa')->searchable()->filterable()->defaultSort('asc'),
        Column::name('gerencias.name')->label('Gerencia')->searchable()->filterable()->defaultSort('asc'),
        Column::name('sedes.name')->label('Sede')->searchable()->filterable()->defaultSort('asc'),
        Column::name('areas.name')->label('Area')->searchable()->filterable()->defaultSort('asc'),
        Column::name('cargos.name')->label('Cargo')->searchable()->filterable()->defaultSort('asc'),
        Column::name('planillas.name')->label('Planilla')->searchable()->filterable()->defaultSort('asc'),
        Column::name('tipo_de_trabajador.name')->label('Tipo de trabajador')->searchable()->filterable()->defaultSort('asc'),
        Column::name('tipo_de_personal.name')->label('Tipo de personal')->searchable()->filterable()->defaultSort('asc'),
        Column::name('personal.fecha_ingreso')->label('Fecha de ingreso')->searchable()->filterable()->defaultSort('asc'),
        Column::callback('personal.cesado', function ($cesado) {
            if ($cesado == 1) {
                return 'CESADO';
            } else {
                return 'ACTIVO';
            }
        })->label('ESTADO')->searchable()->filterable()->defaultSort('asc'),
        Column::name('temas.name')->label('Tema')->searchable()->filterable()->defaultSort('asc'),
        Column::name('sesiones.numero_de_sesion')->label('Sesion')->searchable()->filterable()->defaultSort('asc'),
        Column::name('sesiones.fecha')->label('Fecha de sesion')->searchable()->filterable()->defaultSort('asc'),
        Column::name('modalidades.name')->label('Modalidad')->searchable()->filterable()->defaultSort('asc'),
        Column::name('tipo_de_capacitaciones.name')->searchable()->filterable()->defaultSort('asc'),
        BooleanColumn::name('capacitaciones.expositor_externo')->label('Externo')->filterable()->defaultSort('asc'),
        Column::callback('capacitaciones.expositor_externo,capacitaciones.nombre_expositor_externo,expositor.name', function ($expositorExterno, $nombreExpositorExterno, $expositorInterno) {
            if ($expositorExterno == 1) {
                return $nombreExpositorExterno;
            } else {
                return $expositorInterno;
            }
        })->label('Expositor')->searchable()->filterable()->defaultSort('asc')
        ];
    }
}