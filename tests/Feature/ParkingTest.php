<?php

namespace Tests\Feature;

use App\Models\Parking;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Zone;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ParkingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can start parking
     */
    public function testUserCanStartParking()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'user_id' => $user->id
        ]);
        $zone = Zone::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $vehicle->id,
            'zone_id' => $zone->id
        ]);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['data'])
            ->assertJson([
                'data' => [
                    'start_time' => now()->toDateTimeString(),
                    'stop_time' => null,
                    'total_price' => 0
                ]
            ]);
        $this->assertDatabaseCount('parkings', '1');
    }

    /**
     * Test user can get ongoing parking with correct price
     */

    public function testUserCanGetOngoingParkingWithCorrectPrice()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'user_id' => $user->id
        ]);
        $zone = Zone::factory()->create();
        $this->actingAs($user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $vehicle->id,
            'zone_id' => $zone->id
        ]);
        $this->travel(2)->hours();
        $parking = Parking::first();

        $response = $this->actingAs($user)->getJson('/api/v1/parkings/' . $parking->id);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data'])
            ->assertJson([
                'data' => [
                    'stop_time' => null,
                    'total_price' => $zone->price_per_hour * 2
                ]
            ]);
    }

    /**
     * Test user can stop parking
     */

    public function testUserCanStopParking()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'user_id' => $user->id
        ]);
        $zone = Zone::factory()->create();
        $this->actingAs($user)->postJson('/api/v1/parkings/start', [
            'vehicle_id' => $vehicle->id,
            'zone_id' => $zone->id
        ]);
        $this->travel(2)->hours();
        $parking = Parking::first();
        $response = $this->actingAs($user)->putJson('/api/v1/parkings/' . $parking->id);
        $updatedParking = Parking::find($parking->id);
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data'])
            ->assertJson([
                'data' => [
                    'start_time' => $updatedParking->start_time->toDateTimeString(),
                    'stop_time' => $updatedParking->stop_time->toDateTimeString(),
                    'total_price' => $updatedParking->total_price
                ]
            ]);
        $this->assertDatabaseCount('parkings', '1');
    }
}
