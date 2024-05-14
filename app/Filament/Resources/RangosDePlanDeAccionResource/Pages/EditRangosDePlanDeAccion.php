<?php

namespace App\Filament\Resources\RangosDePlanDeAccionResource\Pages;

use App\Filament\Resources\RangosDePlanDeAccionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRangosDePlanDeAccion extends EditRecord
{
    protected static string $resource = RangosDePlanDeAccionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
