<?php

namespace App\Filament\Resources\StatesResource\Pages;

use App\Filament\Resources\StatesResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListStates extends ListRecords
{
    protected static string $resource = StatesResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
