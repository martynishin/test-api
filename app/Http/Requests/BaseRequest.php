<?php

namespace App\Http\Requests;

use App\Models\Authorization;
use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    /**
     * @var array
     */
    protected array $urlParams = [];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return Authorization|null
     */
    public function organization(): ?Authorization
    {
        return $this->user();
    }

    /**
     * @param  null  $keys
     * @return array
     */
    public function all($keys = null): array
    {
        $data = parent::all($keys);

        return $this->mergeUrlParametersWithRequestData($data);
    }

    /**
     * @param  array  $data
     * @return array
     */
    private function mergeUrlParametersWithRequestData(array $data): array
    {
        foreach ($this->urlParams as $param) {
            $data[$param] = $this->route($param);
        }

        return $data;
    }
}
