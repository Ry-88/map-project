<?php

namespace App\Filament\Resources\Cites\Pages;

use App\Filament\Resources\Cites\CitesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCites extends ListRecords
{
    protected static string $resource = CitesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
