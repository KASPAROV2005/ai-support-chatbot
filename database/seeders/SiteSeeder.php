<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Site;

class SiteSeeder extends Seeder
{
    public function run(): void
    {
        Site::create([
            'name' => 'Demo Site',
            'domain' => null,
            'site_key' => Str::random(40),
            'is_active' => true,
        ]);
    }
}
