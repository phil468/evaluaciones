<?php

namespace App\Filament\Resources\SubgerenciaResource\Pages;

use App\Filament\Resources\SubgerenciaResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSubgerencia extends EditRecord
{
    protected static string $resource = SubgerenciaResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
