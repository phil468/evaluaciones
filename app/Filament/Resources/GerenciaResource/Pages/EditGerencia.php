<?php

namespace App\Filament\Resources\GerenciaResource\Pages;

use App\Filament\Resources\GerenciaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGerencia extends EditRecord
{
    protected static string $resource = GerenciaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
