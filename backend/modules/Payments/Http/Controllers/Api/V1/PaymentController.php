<?php

namespace Modules\Payments\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Payments\Models\Payment;
use Modules\Payments\Http\Resources\PaymentResource;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $q = Payment::query()
            ->when($request->filled('filter[invoice_id]'), fn($qq) =>
                $qq->where('invoice_id', $request->input('filter.invoice_id'))
            );

        return PaymentResource::collection($q->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'invoice_id'=>'required|integer',
            'amount'=>'required|numeric|min:0',
            'paid_at'=>'required|date',
            'method'=>'required|string',
        ]);

        $payment = Payment::create($data);
        return (new PaymentResource($payment))->response()->setStatusCode(201);
    }

    public function show(Payment $payment)
    {
        return new PaymentResource($payment);
    }

    public function update(Request $request, Payment $payment)
    {
        $data = $request->validate([
            'amount'=>'sometimes|numeric|min:0',
            'paid_at'=>'sometimes|date',
            'method'=>'sometimes|string',
        ]);

        $payment->update($data);
        return new PaymentResource($payment);
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return response()->json(null, 204);
    }
}
