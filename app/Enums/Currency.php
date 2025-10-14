<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum Currency: string
{
    case USD  = 'usd';
    case PKR  = 'pkr';
    case GBP  =  'gbp';
    case EUR  =  'eur';

    public function label(): string
    {
        return match ($this) {
            self::USD => 'usd',
            self::PKR => 'pkr',
            self::GBP => 'gbp',
            self::EUR => 'eur',
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->label()] = $case->label();
        }
        return $options;
    }
}