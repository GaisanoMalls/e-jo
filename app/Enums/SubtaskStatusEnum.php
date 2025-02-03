<?php

namespace App\Enums;

enum SubtaskStatusEnum: string
{
    case DONE = 'Done';
    case OPEN = 'Open';

    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}