<?php

namespace App\Http\Traits;

trait MultiSelect
{
    public function getSelectedValue($field)
    {
        return array_map('intval', explode(',', $field[0]));
    }
}