<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum MemberStatus: string
{
    case ACTIVE   = 'active';
    case INACTIVE = 'in-active';
    case BLOCK    = 'blocked';
    case PENDING  = 'pending';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Active',
            self::INACTIVE => 'In-active',
            self::BLOCK => 'Block',
            self::PENDING => 'Pending'
        };
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}