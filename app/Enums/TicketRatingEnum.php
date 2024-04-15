<?php

namespace App\Enums;

enum TicketRatingEnum: int
{
    const TERRIBLE = 1;
    const BAD = 2;
    const GOOD = 3;
    const VERY_GOOD = 4;
    const EXCELLENT = 5;
}