<?php

namespace Modules\Membership\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'member',
            'id' => $this->id,
            'attributes' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'local_id' => $this->local_id,
            ]
        ];
    }
}
