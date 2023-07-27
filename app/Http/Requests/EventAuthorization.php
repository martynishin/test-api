<?php

namespace App\Http\Requests;

use App\Models\Event;

trait EventAuthorization
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return Event::query()->whereKey($this->id)->value('organization_id') === $this->organization()->getAuthIdentifier();
    }
}
