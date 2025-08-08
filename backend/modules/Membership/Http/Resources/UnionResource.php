<?php

namespace Modules\Membership\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UnionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'union',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'code' => $this->code,
            ]
        ];
    }
}
