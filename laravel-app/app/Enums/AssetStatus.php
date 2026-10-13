<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum AssetStatus: string implements HasLabel, HasColor
{
    case Active      = 'active';
    case Faulty      = 'faulty';
    case Maintenance = 'maintenance';
    case Retired     = 'retired';

    public function getLabel(): string
    {
        return match ($this) {
            self::Active      => 'يعمل',
            self::Faulty      => 'معطل',
            self::Maintenance => 'تحت الصيانة',
            self::Retired     => 'خارج الخدمة',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::Active      => 'success',
            self::Faulty      => 'danger',
            self::Maintenance => 'warning',
            self::Retired     => 'gray',
        };
    }
}
