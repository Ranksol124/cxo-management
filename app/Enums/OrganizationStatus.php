<?php

namespace App\Enums;

enum OrganizationStatus: string
{
    case SelfEmployed   = 'self_employed';
    case Startup        = 'startup';
    case Small          = 'small';
    case Corporate      = 'corporate';
    case MultiNational  = 'multi_national';
    case PublicLimited  = 'public_limited';

    public function label(): string
    {
        return match ($this) {
            self::SelfEmployed  => 'Self Employed',
            self::Startup       => 'Startup',
            self::Small         => 'Small Medium Company',
            self::Corporate     => 'Corporate Organization',
            self::MultiNational => 'Multi-National',
            self::PublicLimited => 'Public Limited',
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
