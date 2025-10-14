<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum PaymentTimeline: string
{
    case Now = 'now';
    case Today = 'today';
    case InThreeDays = 'in_three_days';
    case AfterThreeDays = 'after_three_days';

    public function label(): string
    {
        return match ($this) {
            self::Now => 'Now',
            self::Today => 'Today',
            self::InThreeDays => 'In Three Days',
            self::AfterThreeDays => 'After three days (This will cancel the registration, you can re-apply when you ready)',
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