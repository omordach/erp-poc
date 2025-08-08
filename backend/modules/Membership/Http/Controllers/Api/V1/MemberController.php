<?php

namespace Modules\Membership\Http\Controllers\Api\V1;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Modules\Membership\Models\Member;
use Modules\Membership\Http\Resources\MemberResource;
use Modules\Core\Events\MemberCreated;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $q = Member::query()
            ->when($request->filled('filter[local_id]'), fn($qq) =>
                $qq->where('local_id', $request->input('filter.local_id'))
            )
            ->when($request->filled('filter[q]'), function ($qq) use ($request) {
                $term = $request->input('filter.q');
                $qq->where(function($w) use ($term) {
                    $w->where('first_name','like',"%$term%")
                      ->orWhere('last_name','like',"%$term%")
                      ->orWhere('email','like',"%$term%");
                });
            });

        return MemberResource::collection($q->paginate(10));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'local_id'=>'required|integer',
            'first_name'=>'required|string',
            'last_name'=>'required|string',
            'email'=>'required|email|unique:members,email',
        ]);

        $member = Member::create($data);

        // Event-driven: notify other modules
        MemberCreated::dispatch($member->id, ['email' => $member->email]);

        return (new MemberResource($member))->response()->setStatusCode(201);
    }

    public function show(Member $member)
    {
        return new MemberResource($member);
    }

    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'local_id'=>'sometimes|integer',
            'first_name'=>'sometimes|string',
            'last_name'=>'sometimes|string',
            'email'=>'sometimes|email|unique:members,email,'.$member->id,
        ]);

        $member->update($data);
        return new MemberResource($member);
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return response()->json(null, 204);
    }
}
