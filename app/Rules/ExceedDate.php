<?php

namespace App\Rules;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class ExceedDate implements DataAwareRule, ValidationRule
{
    /**
     * All the data under validation.
     *
     * @var array
     */
    protected array $data = [];

    public function __construct(private readonly string $compared, private readonly string $range)
    {
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $exceeded = Carbon::parse($this->data[$this->compared])
            ->diffAsCarbonInterval($value)
            ->greaterThan(CarbonInterval::fromString($this->range));

        if ($exceeded) {
            $message = sprintf(
                'The duration between the %s and %s cannot exceed %s.',
                ':attribute', str_replace('_', ' ', $this->compared), $this->range
                );

            $fail($message);
        }
    }

    /**
     * @param  array  $data
     * @return $this
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }
}
