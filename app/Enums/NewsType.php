<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum NewsType: string
{
    case TRENDING = 'trending';
    case POPULAR  = 'popular';
    case CXO      = 'cxo';
    case Magazine = 'magazine';
    

    public function label(): string
    {
        return match ($this) {
            self::TRENDING => 'Trending',
            self::POPULAR => 'Popular',
            self::CXO => 'CXO',
            self::Magazine => 'Magazine',
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