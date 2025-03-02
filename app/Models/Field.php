<?php

namespace App\Models;

use App\Enums\PredefinedFieldValueEnum;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'name',
        'label',
        'type',
        'variable_name',
        'is_required',
        'is_enabled',
        'assigned_column',
        'is_header_field',
        'config'
    ];

    protected $casts = [
        'is_required' => 'bool',
        'is_enabled' => 'bool',
        'config' => AsArrayObject::class
    ];

    public static function setConfig($configField)
    {
        $fieldLabel = "";

        foreach (PredefinedFieldValueEnum::getOptions() as $option) {
            if ($option['value'] === $configField) {
                $fieldLabel = $option['label'];
                break;
            }
        }

        return [
            'get_value_from' => $configField ? ['label' => $fieldLabel, 'value' => $configField] : ['label' => null, 'value' => null]
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function helpTopics(): BelongsToMany
    {
        return $this->belongsToMany(HelpTopic::class, 'help_topic_field');
    }

    public function isEnabled(): bool
    {
        return $this->is_enabled;
    }
}
