<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContactFormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_contact_form_submission_is_saved_and_redirects_with_success_message()
    {
        $payload = [
            'source_page' => 'lien-he',
            'name' => 'Nguyen Van A',
            'phone' => '0988991635',
            'email' => 'nguyenvana@example.com',
            'service' => 'Thiết kế sân vườn',
            'message' => 'Tôi muốn được tư vấn phương án sân vườn cho biệt thự.',
        ];

        $response = $this->post(route('site.contact.submit'), $payload);

        $response
            ->assertStatus(302)
            ->assertSessionHas('success');

        $this->assertDatabaseHas('contact_messages', [
            'source_page' => 'lien-he',
            'name' => 'Nguyen Van A',
            'phone' => '0988991635',
            'email' => 'nguyenvana@example.com',
            'service' => 'Thiết kế sân vườn',
            'message' => 'Tôi muốn được tư vấn phương án sân vườn cho biệt thự.',
            'is_read' => false,
        ]);
    }

    public function test_contact_form_submission_returns_json_success_for_ajax_requests()
    {
        $payload = [
            'source_page' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
            'name' => 'Tran Thi B',
            'phone' => '0901234567',
            'email' => 'tranthib@example.com',
            'message' => 'Cho tôi xin báo giá tổng thể theo diện tích sân.',
        ];

        $response = $this->postJson(route('site.contact.submit'), $payload);

        $response
            ->assertStatus(200)
            ->assertJson([
                'status' => 'success',
            ])
            ->assertJsonStructure(['status', 'message']);

        $this->assertDatabaseHas('contact_messages', [
            'source_page' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
            'name' => 'Tran Thi B',
            'phone' => '0901234567',
            'email' => 'tranthib@example.com',
            'message' => 'Cho tôi xin báo giá tổng thể theo diện tích sân.',
            'is_read' => false,
        ]);
    }
}
