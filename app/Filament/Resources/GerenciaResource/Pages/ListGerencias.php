<?php

namespace App\Filament\Resources\GerenciaResource\Pages;

use App\Filament\Resources\GerenciaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGerencias extends ListRecords
{
    protected static string $resource = GerenciaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
