<?php

namespace App\Filament\Resources\Cites\Pages;

use App\Filament\Resources\Cites\CitesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use BackedEnum;

class EditCites extends EditRecord
{
    protected static string $resource = CitesResource::class;

    protected static ?string $navigationLabel = 'الاتصالات';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-signal';

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

class EditRegionSecurity extends EditRecord
{
    protected static string $resource = CitesResource::class;

    protected static ?string $navigationLabel = 'الأنظمة الأمنية';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shield-check';
}
