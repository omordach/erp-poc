<?php

namespace Modules\Membership\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LocalResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'local',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'number' => $this->number,
                'union_id' => $this->union_id,
            ]
        ];
    }
}
