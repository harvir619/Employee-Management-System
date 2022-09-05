<?php

namespace App\Filament\Resources\CitiesResource\Pages;

use App\Filament\Resources\CitiesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCities extends EditRecord
{
    protected static string $resource = CitiesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
