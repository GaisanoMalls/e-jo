<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait AppErrorLog
{
    public static function getError(Exception|string $e, bool $notify = true)
    {
        Log::channel('appErrorLog')->error($e, [url()->full()]);

        if ($notify) {
            noty()->addError('Oops, something went wrong');
        }
    }
}