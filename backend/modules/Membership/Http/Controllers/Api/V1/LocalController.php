<?php

namespace Modules\Membership\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Membership\Models\Local;
use Modules\Membership\Http\Resources\LocalResource;

class LocalController extends Controller
{
    public function index(Request $request)
    {
        $q = Local::query()
            ->when($request->filled('filter[union_id]'), fn($qq) =>
                $qq->where('union_id', $request->input('filter.union_id'))
            );

        return LocalResource::collection($q->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'union_id'=>'required|integer',
            'name'=>'required|string',
            'number'=>'required|string',
        ]);

        $local = Local::create($data);
        return (new LocalResource($local))->response()->setStatusCode(201);
    }

    public function show(Local $local)
    {
        return new LocalResource($local);
    }

    public function update(Request $request, Local $local)
    {
        $data = $request->validate([
            'union_id'=>'sometimes|integer',
            'name'=>'sometimes|string',
            'number'=>'sometimes|string',
        ]);
        $local->update($data);
        return new LocalResource($local);
    }

    public function destroy(Local $local)
    {
        $local->delete();
        return response()->json(null, 204);
    }
}
