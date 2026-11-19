<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case Pending = 'pending';
    case Successful = 'success';
    case Failed = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Successful => 'Paid',
            self::Failed => 'Failed',
        };
    }
}
