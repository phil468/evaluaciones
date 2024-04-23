<?php

namespace App\Filament\Resources\EvaluacioneResource\Pages;

use App\Filament\Resources\EvaluacioneResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEvaluacione extends CreateRecord
{
    protected static string $resource = EvaluacioneResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
