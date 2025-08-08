<?php

namespace Modules\Events\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'event',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'starts_at' => $this->starts_at,
                'ends_at' => $this->ends_at,
                'location' => $this->location,
            ]
        ];
    }
}
