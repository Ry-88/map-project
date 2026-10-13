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
use App\Enums\AssetStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;

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


                Select::make('status')
                    ->label('الحالة')
                    ->options(AssetStatus::class)
                    ->placeholder('غير محدد')
                    ->nullable(),

                TextInput::make('serial_number')
                    ->label('الرقم التسلسلي')
                    ->maxLength(255)
                    ->nullable(),

                FileUpload::make('image')
                    ->label('الصورة')
                    ->image()
                    ->disk('public')
                    ->directory('assets')
                    ->imageEditor()
                    ->nullable(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('count')->label('العدد'),
                TextColumn::make('type')->label('النوع'),
                TextColumn::make('location')->label('الموقع'),

                ImageColumn::make('image')
                    ->label('الصورة')
                    ->disk('public')
                    ->square(),

                TextColumn::make('serial_number')
                    ->label('الرقم التسلسلي')
                    ->placeholder('—')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('الحالة')
                    ->badge()
                    ->placeholder('غير محدد'),
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
