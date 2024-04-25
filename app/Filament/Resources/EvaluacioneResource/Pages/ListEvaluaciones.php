<?php

namespace App\Filament\Resources\EvaluacioneResource\Pages;

use App\Filament\Resources\EvaluacioneResource;
use App\Filament\Actions\SendMassEmailAction;
use Filament\Pages\Actions;
use Filament\Pages\Actions\ButtonAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListEvaluaciones extends ListRecords
{
    protected static string $resource = EvaluacioneResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make()->icon('heroicon-o-plus')->label('Crear Evaluación'),
            SendMassEmailAction::make('send_mass_email')
            ->label('Enviar Correo Masivo')
            ->icon('heroicon-o-mail') ,
        ];
    }

    // protected function getFooter(): View
    //     {
    //         return view('footer');
    //     }

}
