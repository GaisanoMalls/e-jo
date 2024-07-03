<?php

namespace App\Enums;

enum FieldTypesEnum: string
{
    case SHORT_ANSWER = 'short_answer';
    case LONG_ANSWER = 'long_answer';
    case NUMBER = 'number';
    case DATE = 'date';
    case TIME = 'time';
    case AMOUNT = 'amount';
    case FILE = 'file';
}