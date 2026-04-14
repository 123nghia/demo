<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SiteSetting::setMany(SiteSetting::defaults());
        SiteSetting::setAboutContent(SiteSetting::aboutContentDefaults());
        SiteSetting::setHomeContent(SiteSetting::homeContentDefaults());
    }
}
