<?php

namespace App\Enums;

enum ApprovalStatusEnum: string
{
    const FOR_APPROVAL = 'for_approval';
    const APPROVED = 'approved';
    const DISAPPROVED = 'disapproved';
    const CLAIMED = 'claimed';
}
