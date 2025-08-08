<?php

namespace Modules\Grievances\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GrievanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'grievance',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'status' => $this->status,
                'opened_at' => $this->opened_at,
            ]
        ];
    }
}
