<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StartParkingRequest;
use App\Http\Resources\ParkingResource;
use App\Models\Parking;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParkingController extends Controller
{
    public function start(StartParkingRequest $request)
    {
        $parkingData = $request->validated();
        if (Parking::active()->where('vehicle_id', $request->input('vehicle_id'))->exists()) {
            return response()->json([
                "errors" => ["general" => "Can't start parking twice using same vehicle. Please stop currently active parking."]
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $parking = Parking::create($parkingData);
        $parking->load('vehicle', 'zone');
        return ParkingResource::make($parking);
    }

    public function show(Parking $parking)
    {
        return ParkingResource::make($parking);
    }

    public function stop(Parking $parking)
    {
        $parking->update([
            'stop_time' => now()
        ]);
        return ParkingResource::make($parking);
    }
}
