<?php

namespace App\Enums;

enum RecommendationApprovalStatusEnum: string
{
    case PENDING = 'Pending';
    case APPROVED = 'Approved';
    case DISAPPROVED = 'Disapproved';

    public static function toArray(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}