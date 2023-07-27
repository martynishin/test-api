<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property integer $id
 * @property string $event_title
 * @property Carbon $event_start_date
 * @property Carbon $event_end_date
 * @property integer $organization_id
 */
class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event_title' => $this->event_title,
            'event_start_date' => $this->event_start_date,
            'event_end_date' => $this->event_end_date,
            'organization_id' => $this->organization_id,
        ];
    }
}
