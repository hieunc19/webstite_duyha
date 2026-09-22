<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_data_endpoint_returns_the_live_payload_shape(): void
    {
        $response = $this->getJson('/api/homepage-data');

        $response
            ->assertOk()
            ->assertJsonStructure([
                'places',
                'officials',
                'departments',
                'neighborhoods',
                'meritorious_families',
                'tdp_officials',
                'settings',
                'homepage_sections',
                'waste_schedules',
                'procedure_categories',
            ]);
    }
}
