<?php

namespace App\Enums;

enum RecommendationApprovalStatusEnum: string
{
    case PENDING = 'Pending';
    case APPROVED = 'Approved';
    case DISAPPROVED = 'Disapproved';
}