<?php

namespace App\Enums;

enum Role: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case Student = 'student';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super admin',
            self::Admin => 'Admin',
            self::Student => 'Student',
        };
    }

    public function isStaff(): bool
    {
        return $this !== self::Student;
    }
}
