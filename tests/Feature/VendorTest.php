<?php

namespace Tests\Feature;

use App\Vendor;
use Carbon\Carbon;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    function the_index_should_return_ok_status()
    {
        $request = $this->get('/vendor');
        $request->assertStatus(200);
    }

    /** @test */
    function the_vendor_page_should_display_the_vendor_information()
    {
        $vendor = factory(Vendor::class)->create([
            'name' => 'Bob Ross',
        ]);
        $request = $this->get('/vendor/'. $vendor->id);
        $request->assertStatus(200)
                 ->assertSee('Bob Ross')
                 ->assertSee(Carbon::now()->toDayDateTimeString());
    }
}
