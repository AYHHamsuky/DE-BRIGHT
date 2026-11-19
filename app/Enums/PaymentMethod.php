<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Paystack = 'paystack';
    case Cash = 'cash';
    case BankTransfer = 'bank_transfer';
    case Pos = 'pos';

    public function label(): string
    {
        return match ($this) {
            self::Paystack => 'Online (Paystack)',
            self::Cash => 'Cash',
            self::BankTransfer => 'Bank transfer',
            self::Pos => 'POS',
        };
    }

    /**
     * Methods a staff member can record by hand at the bursary.
     *
     * @return list<self>
     */
    public static function manual(): array
    {
        return [self::Cash, self::BankTransfer, self::Pos];
    }
}
