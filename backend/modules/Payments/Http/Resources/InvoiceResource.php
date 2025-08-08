<?php

namespace Modules\Payments\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'invoice',
            'id' => $this->id,
            'attributes' => [
                'number' => $this->number,
                'amount' => (float) $this->amount,
                'status' => $this->status,
                'issued_at' => $this->issued_at,
                'due_at' => $this->due_at,
            ]
        ];
    }
}
