<?php

namespace App\Enums;

enum FieldTypesEnum: string
{
    case STRING      = 'string';
    case TEXT        = 'text';
    case INTEGER     = 'integer';
    case BIG_INTEGER = 'bigInteger';
    case BOOLEAN     = 'boolean';
    case DATE        = 'date';
    case TIME        = 'time';
    case DATE_TIME   = 'dateTime';
    case DECIMAL     = 'decimal';

}