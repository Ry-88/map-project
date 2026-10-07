<?php

namespace App\Filament\Resources\Cites\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class CentralsRelationManager extends RelationManager
{
    protected static string $relationship = 'centrals';

    protected static ?string $title = 'السنترالات';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('type')->label('النوع')->maxLength(255),
            TextInput::make('version')->label('الإصدار')->maxLength(255),
            TextInput::make('location')->label('الموقع')->maxLength(255),
            TextInput::make('analog_count')->label('تماثلية')->numeric()->default(0),
            TextInput::make('digital_count')->label('رقمية')->numeric()->default(0),
            TextInput::make('direct_count')->label('مباشرة')->numeric()->default(0),
            TextInput::make('ac_units')->label('مكيفات')->numeric()->default(0),
            TextInput::make('batteries')->label('بطاريات')->numeric()->default(0),
            TextInput::make('ups')->label('UPS')->numeric()->default(0),
            TextInput::make('external_capacity')->label('سعة خارجية')->numeric()->default(0),
            TextInput::make('internal_capacity')->label('سعة داخلية')->numeric()->default(0),
            TextInput::make('central_capacity')->label('سعة السنترال')->numeric()->default(0),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')->label('النوع'),
                TextColumn::make('version')->label('الإصدار'),
                TextColumn::make('location')->label('الموقع'),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
