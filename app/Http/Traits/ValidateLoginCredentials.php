<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Validator;

trait ValidateLoginCredentials
{   
    public function validateLoginCrendentials($request, string $field1, string $field2)
    {
        $validator = Validator::make($request->all(), [
            $field1 => ['required', 'email'],
            $field2 => ['required']
        ]);

        return $validator->validate();
    }
}