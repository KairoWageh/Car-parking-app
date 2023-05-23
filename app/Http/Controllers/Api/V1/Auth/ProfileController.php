<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Auth\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends Controller
{
    public function show()
    {
//        $profileData = [
//            'name' => auth()->user()->name,
//            'email' => auth()->user()->email
//        ];
        return response()->json(UserResource::make(auth()->user()));
    }

    public function update(UpdateProfileRequest $request)
    {
        $validatedData = $request->safe()->only('name', 'email');
        auth()->user()->update($validatedData);
        return response()->json($validatedData, Response::HTTP_ACCEPTED);
    }
}
