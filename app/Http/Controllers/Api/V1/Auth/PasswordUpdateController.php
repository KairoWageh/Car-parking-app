<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\PasswordUpdateRequest;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class PasswordUpdateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PasswordUpdateRequest $request)
    {
        $password = Hash::make($request->input('password'));
        auth()->user()->update([
            'password' => $password
        ]);

        return response()->json([
            'message' => __('Your password changed successfully')
        ], Response::HTTP_ACCEPTED);
    }
}
