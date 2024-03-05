<?php

namespace App\Enums;

enum SpecialProjectStatusEnum: string
{
    case DONE = 'Done';
    case ON_ORDERED = 'On-ordered';
    case DELIVERED = 'Delivered';
    case PROCESSED = 'Processed';
}