<?php

namespace Modules\Payments\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PaymentResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'type' => 'payment',
            'id' => $this->id,
            'attributes' => [
                'invoice_id' => $this->invoice_id,
                'amount' => (float) $this->amount,
                'paid_at' => $this->paid_at,
                'method' => $this->method,
            ]
        ];
    }
}
