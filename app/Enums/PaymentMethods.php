<?php

namespace App\Enums;

use Illuminate\Support\Str;

enum PaymentMethods: string
{
    case JazzCash      = 'jazzcash';
    case EasyPaisa     = 'easypaisa';
    case BankTransfer = 'bank transfer';
    case CreditCard   = 'credit card';
    

    public function label(): string
    {
        return match ($this) {
            self::JazzCash => 'JazzCash',
            self::EasyPaisa => 'EasyPaisa',
            self::BankTransfer => 'Bank Transfer',
            self::CreditCard => 'Credit Card',
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