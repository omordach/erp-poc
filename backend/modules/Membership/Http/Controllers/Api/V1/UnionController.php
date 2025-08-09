<?php

namespace Modules\Membership\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Membership\Http\Resources\UnionResource;
use Modules\Membership\Models\Union;

class UnionController extends Controller
{
    public function index(Request $request)
    {
        $q = Union::query()->when(
            $request->filled('filter.name'),
            fn ($qq) => $qq->where('name', 'like', '%'.$request->input('filter.name').'%')
        );

        return UnionResource::collection($q->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'code' => 'required|string|unique:unions,code',
        ]);

        $union = Union::create($data);

        return (new UnionResource($union))->response()->setStatusCode(201);
    }

    public function show(Union $union)
    {
        return new UnionResource($union);
    }

    public function update(Request $request, Union $union)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'code' => 'sometimes|string|unique:unions,code,'.$union->id,
        ]);
        $union->update($data);

        return new UnionResource($union);
    }

    public function destroy(Union $union)
    {
        $union->delete();

        return response()->json(null, 204);
    }
}
