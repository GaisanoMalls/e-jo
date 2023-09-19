<?php

namespace App\Http\Requests\SysAdmin\Manage\SLA;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSLARequest extends FormRequest
{
    protected $errorBag = 'storeSLA';
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'countdown_approach' => [
                'required',
                'numeric',
                Rule::unique('service_level_agreements', 'countdown_approach')
            ],
            'time_unit' => [
                'required',
                Rule::unique('service_level_agreements', 'time_unit')
            ]
        ];
    }
}