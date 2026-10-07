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

class RepeaterStationsRelationManager extends RelationManager
{
    protected static string $relationship = 'repeaterStations';

    protected static ?string $title = 'محطات الإعادة';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('type')->label('النوع')->maxLength(255)->required(),
            TextInput::make('count')->label('العدد')->numeric()->required(),
            TextInput::make('location')->label('الموقع')->maxLength(255)->required(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('count')->label('العدد'),
                TextColumn::make('type')->label('النوع'),
                TextColumn::make('location')->label('الموقع'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}