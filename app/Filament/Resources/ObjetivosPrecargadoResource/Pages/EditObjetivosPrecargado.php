<?php

namespace App\Filament\Resources\ObjetivosPrecargadoResource\Pages;

use App\Filament\Resources\ObjetivosPrecargadoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObjetivosPrecargado extends EditRecord
{
    protected static string $resource = ObjetivosPrecargadoResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
