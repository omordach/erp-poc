<?php

namespace Modules\Grievances\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Grievances\Models\Grievance;
use Modules\Grievances\Http\Resources\GrievanceResource;

class GrievanceController extends Controller
{
    public function index(Request $request)
    {
        $q = Grievance::query()
            ->when($request->filled('filter[status]'), fn($qq) =>
                $qq->where('status', $request->input('filter.status'))
            );

        return GrievanceResource::collection($q->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string',
            'description'=>'nullable|string',
            'status'=>'required|string|in:open,closed,pending',
        ]);
        $g = Grievance::create($data + ['opened_at' => now()]);
        return (new GrievanceResource($g))->response()->setStatusCode(201);
    }

    public function show(Grievance $grievance)
    {
        return new GrievanceResource($grievance);
    }

    public function update(Request $request, Grievance $grievance)
    {
        $data = $request->validate([
            'title'=>'sometimes|string',
            'description'=>'sometimes|string|nullable',
            'status'=>'sometimes|string|in:open,closed,pending',
        ]);
        $grievance->update($data);
        return new GrievanceResource($grievance);
    }

    public function destroy(Grievance $grievance)
    {
        $grievance->delete();
        return response()->json(null, 204);
    }
}
