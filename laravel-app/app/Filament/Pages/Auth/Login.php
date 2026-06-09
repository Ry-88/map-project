<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Forms\Components\TextInput;

class Login extends BaseLogin
{
    public function getHeading(): string
    {
        return 'Welcome Back 👋';
    }

    public function getSubheading(): ?string
    {
        return 'تسجيل الدخول';
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('email')
                ->label('Email Address')
                ->email()
                ->required(),

            TextInput::make('password')
                ->label('Password')
                ->password()
                ->required(),
        ];
    }
}