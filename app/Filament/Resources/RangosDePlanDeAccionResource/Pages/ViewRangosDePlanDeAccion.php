<?php

namespace App\Filament\Resources\RangosDePlanDeAccionResource\Pages;

use App\Filament\Resources\RangosDePlanDeAccionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRangosDePlanDeAccion extends ViewRecord
{
    protected static string $resource = RangosDePlanDeAccionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
