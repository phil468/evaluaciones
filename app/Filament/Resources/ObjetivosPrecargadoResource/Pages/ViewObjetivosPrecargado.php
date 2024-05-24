<?php

namespace App\Filament\Resources\ObjetivosPrecargadoResource\Pages;

use App\Filament\Resources\ObjetivosPrecargadoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewObjetivosPrecargado extends ViewRecord
{
    protected static string $resource = ObjetivosPrecargadoResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
