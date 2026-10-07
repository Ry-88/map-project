<?php

namespace App\Filament\Resources\Cites\RelationManagers;

use Filament\Actions\AssociateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DevicesRelationManager extends RelationManager
{
    protected static string $relationship = 'devices';
        protected static ?string $title = 'الأجهزة';


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')->label('النوع')->maxLength(255)->required(),
                TextInput::make('count')->label('العدد')->numeric()->required(),
                TextInput::make('location')->label('الموقع')->maxLength(255)->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('الجهاز')
            ->columns([
                TextColumn::make('type')->label('النوع')->searchable(),
                TextColumn::make('count')->label('العدد')->searchable(),
                TextColumn::make('location')->label('الموقع')->searchable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                AssociateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
