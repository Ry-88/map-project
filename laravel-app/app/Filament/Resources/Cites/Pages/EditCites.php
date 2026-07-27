<?php

namespace App\Filament\Resources\Cites\Pages;

use App\Filament\Resources\Cites\CitesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCites extends EditRecord
{
    protected static string $resource = CitesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
