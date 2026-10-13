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
use App\Enums\AssetStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\ImageColumn;

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
            ->recordTitleAttribute('الجهاز')
            ->columns([
                TextColumn::make('type')->label('النوع')->searchable(),
                TextColumn::make('count')->label('العدد')->searchable(),
                TextColumn::make('location')->label('الموقع')->searchable(),

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
