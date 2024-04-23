<?php

namespace App\Filament\Resources\EvaluacioneResource\Pages;

use App\Filament\Resources\EvaluacioneResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditEvaluacione extends EditRecord
{
    protected static string $resource = EvaluacioneResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    // protected function handleRecordUpdate(Model $record, array $data): Model
    // {
    //     $record->update($data);

    //     //retornar a página del recurso (filament 2.0)

    
    //     return $record;
    // }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

}
