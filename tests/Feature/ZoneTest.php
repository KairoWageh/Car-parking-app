<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ZoneTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test public user can get all zones
     */
    public function testPublicUserCanGetAllZones()
    {
        $response = $this->getJson('/api/v1/zones');
        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(["data"])
            ->assertJsonCount(3, "data")
            ->assertJsonStructure(['data' => [
                ['*' => 'id', 'name', 'price_per_hour'],
            ]])
            ->assertJsonPath('data.0.id', 1)
            ->assertJsonPath('data.0.name', 'Green Zone')
            ->assertJsonPath('data.0.price_per_hour', 100)
        ;
    }
}
