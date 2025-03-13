<?php

namespace App\Http\Traits;

use Exception;
use Illuminate\Support\Facades\Log;

trait AppErrorLog
{
    public static function getError(Exception|string $exception, bool $notify = true)
    {
        Log::channel('appErrorLog')->error($exception, [url()->full()]);

        if ($notify) {
            // Show pop-up toastr/notification
            noty()->addError('Oops, something went wrong.');
        }
    }
}