<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

/**
 * @property int $id
 */
class EventShowRequest extends BaseRequest
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
        ];
    }
}
