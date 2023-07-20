<?php

namespace App\Http\Traits;

trait SlugGenerator
{
    public static function slugify(string $word): string
    {
        return mt_rand(10000, 99999) . "-" . \Str::slug($word);
    }
}