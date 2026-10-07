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
use Filament\Forms\Components\TagsInput;

class WorkshopsRelationManager extends RelationManager
{
    protected static string $relationship = 'workshops';

    protected static ?string $title = 'الورش والطاقم الفني';

    public function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('workshop_name')->label('اسم الورشة')->required()->maxLength(255),
            TextInput::make('location')->label('الموقع')->maxLength(255)->required(),
            TextInput::make('employees_count')->label('عدد الموظفين')->numeric()->default(0),
            TextInput::make('engineers_count')->label('عدد المهندسين')->numeric()->default(0),
            TextInput::make('technicians_count')->label('عدد الفنيين')->numeric()->default(0),
            TagsInput::make('engineers')->label('أسماء المهندسين')->placeholder('أضف اسم مهندس'),
            TagsInput::make('technicians')->label('أسماء الفنيين')->placeholder('أضف اسم الفني')
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('workshop_name')
            ->columns([
                TextColumn::make('workshop_name')->label('اسم الورشة'),
                TextColumn::make('location')->label('الموقع'),
                TextColumn::make('employees_count')->label('الموظفين'),
                TextColumn::make('engineers_count')->label('المهندسين'),
                TextColumn::make('technicians_count')->label('الفنيين'),
            ])
            ->headerActions([CreateAction::make()])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}