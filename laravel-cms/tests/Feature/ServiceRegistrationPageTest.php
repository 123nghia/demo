<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ServiceRegistrationPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_service_registration_page_is_accessible_from_slug()
    {
        $response = $this->get('/dang-ky-dich-vu');

        $response->assertOk();
        $response->assertSee('ĐĂNG KÝ DỊCH VỤ THIẾT KẾ THI CÔNG BIỆT THỰ CAO CẤP HOVI', false);
        $response->assertSee('action="' . route('site.contact.submit', [], false) . '"', false);
    }

    public function test_home_consult_cta_points_to_service_registration_page()
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee(url('/dang-ky-dich-vu'), false);
    }
}
