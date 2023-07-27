<?php

namespace App\Http\Requests;

use App\Models\Event;
use App\Rules\ExceedDate;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @property int $id
 */
class EventUpdateRequest extends BaseRequest implements EventUpdateRequestInterface
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
                'string',
                'max:200',
            ],
            'event_start_date' => [
                'date_format:Y-m-d H:i:s',
                'before:event_end_date',
            ],
            'event_end_date' => [
                'date_format:Y-m-d H:i:s',
                'after:event_start_date',
                new ExceedDate('event_start_date', '12 hours'),
            ],
        ];
    }

    /**
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'event_start_date' => $this->event_start_date ?? Event::query()->whereKey($this->id)->value('event_start_date'),
            'event_end_date' => $this->event_end_date ?? Event::query()->whereKey($this->id)->value('event_end_date'),
        ]);
    }
}
