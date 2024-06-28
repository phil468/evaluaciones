<?php

namespace App\Http\Livewire;

use App\Models\User;
use Mediconesystems\LivewireDatatables\BooleanColumn;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class UsersTable extends LivewireDatatable
{
    public $model = User::class;

    public function columns()
    {
        return [
            // Column::callback(['id'], function ($id) {
            //     return '<a class="btn btn-primary btn-sm" href="'.route('users.edit',$id).'"><i class="fa fa-edit"></i></a>';
            Column::callback('id,name', function ($id,$name) {
                return view('users.table-actions', ['id' => $id, 'name'=>$name]);
            })
                ->label('Acciones')
                ->unsortable()
                ->excludeFromExport(),
            
                // Column::callback('id,name', function ($id,$name) {
                //     return view('livewire.personals.table-actions', ['id' => $id, 'name'=>$name]);
                // })->unsortable()
                // ->label('Acciones')
                // ->excludeFromExport()
                // ->hide(), 
                
            NumberColumn::name('id')
                ->label('ID')
                ->sortBy('id'),

            Column::name('name')
                ->label('Name')
                ->filterable()
                ->searchable(),

            Column::name('email')
                ->label('Email')
                ->filterable()
                ->searchable(),

            //'personal_id',
            
            Column::name('personal.name')
                ->label('Personal NISIRA')
                ->filterable()
                ->searchable(),
                
            BooleanColumn::name('registrador')
                ->label('Registrador')
                ->filterable(),

            column::name('roles.name')
                ->label('Roles')
                ->filterable(),
            
            BooleanColumn::name('estado')
                ->label('Estado')
                ->filterable(),

            Column::name('created_at')
                ->label('Creado en')
                ->filterable()
                ->searchable(),

        ];
    }

    public function destroy($id)
    {
        if ($id) {
            $user = User::find($id);
            // Cambiar el email a un valor temporal único antes de eliminar
            $user->email = 'deleted_' . time() . '_' . $user->email;
            $user->save();
        
            // Ahora eliminar (soft delete) el usuario
            $user->delete();
        }
    }
    
}