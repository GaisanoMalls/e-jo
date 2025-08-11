<?php

namespace App\Enums;

enum FieldTypesEnum: string
{
    case TEXT = 'text';
    case NUMBER = 'number';
    case DATE = 'date';
    case TIME = 'time';
    case AMOUNT = 'amount';
    case CHECKBOX = 'checkbox';
    case DROPDOWN = 'dropdown';
}