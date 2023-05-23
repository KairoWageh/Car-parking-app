<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(LoginRequest $request)
    {
        $validated = $request->safe()->only('email', 'password');
        $user = User::where('email', $validated['email'])->first();
        if(!auth()->attempt($validated)){
            throw ValidationException::withMessages([
                'error' => __('The provided credentials are incorrect.')
            ]);
        }

        $device = substr($request->userAgent()?? '', 0, 255);
        $expiredAt = $request->remember? null: now()->addMinutes(config('session.lifetime'));

        return response()->json([
            'access_token' => $user->createToken($device, expiresAt: $expiredAt)->plainTextToken
        ], Response::HTTP_CREATED);
    }
}
