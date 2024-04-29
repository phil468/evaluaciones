<?php

namespace App\Filament\Resources\SubgerenciaResource\Pages;

use App\Filament\Resources\SubgerenciaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSubgerencias extends ListRecords
{
    protected static string $resource = SubgerenciaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
