<?php

use App\License;
use App\Vendor;
use Illuminate\Cache;
use Illuminate\Database\Seeder;

class LicenseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Vendor::class, 10)->create()->each(function ($vendor) {
            $vendor->licenses()->save(factory(License::class)->make());
        });
        Artisan::call('cache:clear');
    }
}
