<?php

namespace App\Http\Requests;

use App\Rules\ExceedDate;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @property int $id
 */
class EventReplaceRequest extends BaseRequest implements EventUpdateRequestInterface
{
    use EventAuthorization;

    /**
     * @var array
     */
    protected array $urlParams = [
        'id'
    ];

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => [
                'required',
                'integer',
                'exists:events,id',
            ],
            'event_title' => [
                'required',
                'string',
                'max:200',
            ],
            'event_start_date' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'before:event_end_date',
            ],
            'event_end_date' => [
                'required',
                'date_format:Y-m-d H:i:s',
                'after:event_start_date',
                new ExceedDate('event_start_date', '12 hours'),
            ],
        ];
    }
}
