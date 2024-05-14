<?php

namespace App\Filament\Resources\RangosDePlanDeAccionResource\Pages;

use App\Filament\Resources\RangosDePlanDeAccionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRangosDePlanDeAccions extends ListRecords
{
    protected static string $resource = RangosDePlanDeAccionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
