<?php

namespace App\Filament\Resources\ObjetivoResource\Pages;

use App\Filament\Resources\ObjetivoResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditObjetivo extends EditRecord
{
    protected static string $resource = ObjetivoResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
