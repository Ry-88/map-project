<?php

namespace App\Filament\Resources\Cites\RelationManagers;

use App\Filament\Resources\Cites\Pages\EditRegionSecurity;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class AccessControlsRelationManager extends RelationManager
{
    protected static string $relationship = 'accessControls';

    protected static ?string $title = 'فلير / التحكم بالدخول';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $pageClass === EditRegionSecurity::class;
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('item')->label('النوع')->required(),
            TextInput::make('count')->label('العدد')->numeric()->default(1)->required(),
            TextInput::make('location')->label('الموقع'),
            TextInput::make('serial_number')->label('الرقم التسلسلي'),
            Select::make('status')->label('الحالة')->options([
                'active'      => 'يعمل',
                'faulty'      => 'معطّل',
                'maintenance' => 'تحت الصيانة',
                'retired'     => 'خارج الخدمة',
            ]),
            FileUpload::make('image')->label('الصورة')->image()->directory('access'),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('item')
            ->columns([
                TextColumn::make('item')->label('النوع')->searchable(),
                TextColumn::make('count')->label('العدد')->sortable(),
                TextColumn::make('location')->label('الموقع'),
                TextColumn::make('serial_number')->label('الرقم التسلسلي'),
                TextColumn::make('status')->label('الحالة')->badge(),
                ImageColumn::make('image')->label('الصورة'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }
}
