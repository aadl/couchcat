<?php

namespace Tests\Unit;

use App\License;
use App\Vendor;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function should_be_able_to_create_a_vendor()
    {
        $vendor = Vendor::make([
            'name' => 'Bob Ross',
        ]);
        $this->assertEquals('Bob Ross', $vendor->name);
    }

    /** @test */
    function vendors_can_have_licenses()
    {
        $vendor = factory(Vendor::class)->create();
        
        $licenses = factory(License::class,10)->create(['vendor_id' => $vendor->id]);

        $this->assertCount(10, $vendor->licenses);
    }

    /** @test */
    function vendors_should_be_soft_deleted()
    {
        $vendor = factory(Vendor::class)->create();
        $vendor->delete();
        $this->assertTrue($vendor->trashed());
    }
}
