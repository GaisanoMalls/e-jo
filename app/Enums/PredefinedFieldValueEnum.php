<?php

namespace App\Enums;

enum PredefinedFieldValueEnum: string
{
    case CURRENT_DATE = 'current_date';
    case TICKET_NUMBER = 'ticket_number';
    case USER_BRANCH = 'user_branch';
    case USER_DEPARTMENT = 'user_department';
    case USER_FULL_NAME = 'user_full_name';

    public static function getOptions(): array
    {
        return [
            ['label' => "Current date", 'value' => self::CURRENT_DATE->value],
            ['label' => "Ticket number", 'value' => self::TICKET_NUMBER->value],
            ['label' => "User's branch", 'value' => self::USER_BRANCH->value],
            ['label' => "User's department", 'value' => self::USER_DEPARTMENT->value],
            ['label' => "User's full name", 'value' => self::USER_FULL_NAME->value],
        ];
    }
}
