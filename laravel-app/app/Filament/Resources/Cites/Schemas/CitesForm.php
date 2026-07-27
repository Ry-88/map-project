<?php

namespace App\Filament\Resources\Cites\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class CitesForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label('اسم المنطقة')
                    ->required()
                    ->maxLength(255),

                // 👇 هنا بنضيف لاحقًا الحقول الخاصة بكل منطقة
            ]);
    }
}