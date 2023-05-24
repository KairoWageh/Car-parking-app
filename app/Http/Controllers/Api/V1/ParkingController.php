<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StartParkingRequest;
use App\Http\Resources\ParkingResource;
use App\Models\Parking;
use App\Services\ParkingPriceService;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ParkingController extends Controller
{
    private $parkingPriceService;

    public function __construct(ParkingPriceService $parkingPriceService)
    {
        $this->parkingPriceService = $parkingPriceService;
    }

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
            'stop_time' => now(),
            'total_price' => $this->parkingPriceService->calculatePrice($parking->zone_id, $parking->start_time)
        ]);
        return ParkingResource::make($parking);
    }
}
