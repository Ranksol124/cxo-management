<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum JobType: string
{
    case FullTime  = 'full time';
    case PartTime  = 'part time';
    case Hybrid     = 'hybrid';
    

    public function label(): string
    {
        return match ($this) {
            self::FullTime => 'full time',
            self::PartTime => 'part time',
            self::Hybrid => 'CXO',
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