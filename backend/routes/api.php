<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// API routes are automatically prefixed with `/api` via Laravel's routing
// configuration in `bootstrap/app.php`. We only need to prefix the version
// segment here to avoid generating paths like `/api/api/v1`.
Route::prefix('v1')->group(function () {

    // Health
    Route::get('health', fn () => response()->json(['status' => 'ok']))->name('health');

    // Auth: issue tenant-scoped token
    Route::post('auth/token', function (Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $user = \App\Models\User::where('email', $request->email)->first();

        if (! $user || ! \Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
            return response()->json(['error' => ['status'=>401,'code'=>'INVALID_CREDENTIALS','message'=>'Invalid credentials']], 401);
        }

        $tenant = \Spatie\Multitenancy\Models\Tenant::current();
        if (! $tenant) {
            return response()->json(['error'=>['status'=>400,'code'=>'NO_TENANT','message'=>'Tenant not resolved']], 400);
        }

        // Create token with stored tenant_id (custom column added in Part 2)
        $token = $user->createToken($request->device_name, abilities: ['*']);
        $token->accessToken->tenant_id = $tenant->id;
        $token->accessToken->save();

        return response()->json([
            'data' => [
                'type' => 'token',
                'attributes' => [
                    'token' => $token->plainTextToken
                ]
            ]
        ]);
    })->name('auth.token');

    // Module routes are loaded by each module service provider
});
