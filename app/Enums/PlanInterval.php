<?php

namespace App\Enums;

enum PlanInterval: string
{
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';
    case YEAR = 'year';
    case ONE_TIME = 'one_time';

    public function label(): string
    {
        return match($this) {
            self::DAY => 'Day',
            self::WEEK => 'Week',
            self::MONTH => 'Month',
            self::YEAR => 'Year',
            self::ONE_TIME => 'One Time',
        };
    }


    public static function options(): array
    {
        return [
            self::DAY->value => 'Day',
            self::WEEK->value => 'Week',
            self::MONTH->value => 'Month',
            self::YEAR->value => 'Year',
            self::ONE_TIME->value => 'One Time',
        ];
    }
}