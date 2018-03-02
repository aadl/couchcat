<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LicenseTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    function the_license_index_should_return_ok_status()
    {
        $response = $this->get('/license');
        $response->assertStatus(200);
    }
}
