<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class VehicleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test user can get their own vehicles
     */

    public function testUserCanGetTheirOwnVehicles()
    {
        $user1 = User::factory()->create();
        $vehicleForUser1 = Vehicle::factory()->create([
            'user_id' => $user1->id
        ]);
        $user2 = User::factory()->create();
        $vehicleForUser2 = Vehicle::factory()->create([
            'user_id' => $user2->id
        ]);
        $response = $this->actingAs($user1)->getJson('/api/v1/vehicles');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.plate_number', $vehicleForUser1->plate_number)
            ->assertJsonMissing($vehicleForUser2->toArray());
    }

    /**
     * Test user can create vehicle
     */
    public function testUserCanCreateVehicle()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/api/v1/vehicles', [
            'plate_number' => 'AAA111'
        ]);
        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(['data'])
            ->assertJsonCount(2, 'data')
            ->assertJsonStructure([
                'data' => ['0' => 'plate_number'],
            ])
            ->assertJsonPath('data.plate_number', 'AAA111');
        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'AAA111'
        ]);
    }

    /**
     * Test user can update their vehicle
     */

    public function testUserCanUpdateTheirVehicle()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'user_id' => $user->id
        ]);

        $response = $this->actingAs($user)->putJson('/api/v1/vehicles/' . $vehicle->id, [
            'plate_number' => 'AAA123'
        ]);
        $response->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(['plate_number'])
            ->assertJsonPath('plate_number', 'AAA123');

        $this->assertDatabaseHas('vehicles', [
            'plate_number' => 'AAA123'
        ]);
    }

    /**
     * Test user can delete their vehicles
     */
    public function testUserCanDeleteTheirVehicles()
    {
        $user = User::factory()->create();
        $vehicle = Vehicle::factory()->create([
            'user_id' => $user->id
        ]);
        $response = $this->actingAs($user)->deleteJson('/api/v1/vehicles/' . $vehicle->id);
        $response->assertNoContent();
        $this->assertDatabaseMissing('vehicles', [
            'id' => $vehicle->id,
            'deleted_at' => NULL
        ])
            ->assertDatabaseCount('vehicles', 1);
    }
}
