<?php

namespace Modules\Payments\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Payments\Models\Invoice;
use Modules\Payments\Http\Resources\InvoiceResource;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $q = Invoice::query()
            ->when($request->filled('filter[status]'), fn($qq) =>
                $qq->where('status', $request->input('filter.status'))
            );

        return InvoiceResource::collection($q->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'number'=>'required|string|unique:invoices,number',
            'amount'=>'required|numeric|min:0',
            'status'=>'required|string|in:draft,issued,paid,void',
            'issued_at'=>'required|date',
            'due_at'=>'required|date|after_or_equal:issued_at',
        ]);

        $invoice = Invoice::create($data);
        return (new InvoiceResource($invoice))->response()->setStatusCode(201);
    }

    public function show(Invoice $invoice)
    {
        return new InvoiceResource($invoice);
    }

    public function update(Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'number'=>'sometimes|string|unique:invoices,number,'.$invoice->id,
            'amount'=>'sometimes|numeric|min:0',
            'status'=>'sometimes|string|in:draft,issued,paid,void',
            'issued_at'=>'sometimes|date',
            'due_at'=>'sometimes|date|after_or_equal:issued_at',
        ]);

        $invoice->update($data);
        return new InvoiceResource($invoice);
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return response()->json(null, 204);
    }
}
