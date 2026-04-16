<?php

namespace Tests\Feature;

use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutUsContentRenderSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_about_us_page_renders_latest_admin_content(): void
    {
        $aboutContent = SiteSetting::aboutContentDefaults();
        data_set($aboutContent, 'hero.title', 'ABOUT-US ADMIN CUSTOM TITLE 2026');
        data_set($aboutContent, 'hero.description', 'ABOUT-US ADMIN CUSTOM DESCRIPTION 2026');

        SiteSetting::setAboutContent($aboutContent);

        $response = $this->get('/about-us');

        $response->assertOk();
        $response->assertSee('ABOUT-US ADMIN CUSTOM TITLE 2026');
        $response->assertSee('ABOUT-US ADMIN CUSTOM DESCRIPTION 2026');
    }
}
