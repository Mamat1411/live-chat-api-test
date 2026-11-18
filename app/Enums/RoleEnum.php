<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public static function values(): array {
        return array_map(fn($role) => $role->value, self::cases());
    }

}
