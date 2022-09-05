<?php

namespace App\Filament\Resources\CitiesResource\Pages;

use App\Filament\Resources\CitiesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCities extends ListRecords
{
    protected static string $resource = CitiesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
