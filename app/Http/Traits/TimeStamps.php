<?php

namespace App\Http\Traits;

use Carbon\Carbon;

trait TimeStamps
{
    public function updatedAt($created_field, $updated_field)
    {
        $created_at = Carbon::parse($created_field)->isoFormat('MMM DD, YYYY HH:mm:ss');
        $updated_at = Carbon::parse($updated_field)->isoFormat('MMM DD, YYYY HH:mm:ss');

        return $updated_at === $created_at
            ? "----"
            : Carbon::parse($updated_field)->format('M d, Y | h:i A');
    }

    public function createdAt($created_field)
    {
        return Carbon::parse($created_field)->format('M d, Y');
    }
}