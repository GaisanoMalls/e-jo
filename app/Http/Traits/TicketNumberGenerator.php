<?php

namespace App\Http\Traits;

use Exception;

trait TicketNumberGenerator
{
    public static function generatedTicketNumber()
    {
        return  self::alphaNum() . "-" . self::currentMonth();
    }

    private static function currentMonth(): string
    {
        return date('m');
    }

    private static function alphaNum(): string
    {
        $generatedValues = []; // Array to store previously generated values
        $maxAttempts = 10; // Maximum number of attempts to generate a unique value
        $letters = 'abcdefghijklmnopqrstuvwxyz';

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            $alpha = strtoupper(str_shuffle($letters));
            $num = mt_rand(100, 999);
            $value = $alpha[0] . $num;

            if (!in_array($value, $generatedValues)) {
                $generatedValues[] = $value;
                return $value;
            }
        }

        throw new Exception('Unable to generate a unique value after '.$maxAttempts.' attempts.');
    }
}