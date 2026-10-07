<?php

namespace App\Filament\Resources\Cites;

use App\Filament\Resources\Cites\Pages\CreateCites;
use App\Filament\Resources\Cites\Pages\EditCites;
use App\Filament\Resources\Cites\Pages\ListCites;
use App\Filament\Resources\Cites\Schemas\CitesForm;
use App\Filament\Resources\Cites\Tables\CitesTable;
use App\Models\City;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Filament\Resources\Cites\RelationManagers\RepeaterStationsRelationManager;
use App\Filament\Resources\Cites\RelationManagers\AntennasRelationManager;
use App\Filament\Resources\Cites\RelationManagers\DevicesRelationManager;
use App\Filament\Resources\Cites\RelationManagers\CentralsRelationManager;
use App\Filament\Resources\Cites\RelationManagers\FiberOpticsRelationManager;
use App\Filament\Resources\Cites\RelationManagers\WorkshopsRelationManager;

class CitesResource extends Resource
{
    protected static ?string $model = City::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'المناطق';

    protected static ?string $modelLabel = 'منطقة';

    protected static ?string $pluralModelLabel = 'المناطق';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getRelations(): array
    {
        return [
            RepeaterStationsRelationManager::class,
            AntennasRelationManager::class,
            DevicesRelationManager::class,
            CentralsRelationManager::class,
            FiberOpticsRelationManager::class,
            WorkshopsRelationManager::class,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return CitesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CitesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCites::route('/'),
            'create' => CreateCites::route('/create'),
            'edit' => EditCites::route('/{record}/edit'),
        ];
    }
}