<?php

namespace App\Http\Requests;

use App\Models\Enum\ActivityType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActivityListRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'dateTimeFrom' => 'date_format:Y-m-d H:i:s',
            'dateTimeTo' => 'date_format:Y-m-d H:i:s',
            'type' => Rule::enum(ActivityType::class),
            'period' => [
                Rule::in('nextWeek')
            ],
        ];
    }

    public function isPeriodNextWeek(): bool
    {
        return $this->period === 'nextWeek';
    }
}
