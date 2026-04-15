<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectDetailPage;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminHomeContentAutoSourceVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_update_auto_source_visibility()
    {
        $detailPage = $this->createPublishedDetailPage('guest-cannot-update-auto-source');

        $response = $this->post(route('admin.home-content.auto-source.visibility', $detailPage), [
            'action' => 'exclude',
        ]);

        $response->assertRedirect(route('admin.login'));
    }

    public function test_authenticated_admin_can_exclude_auto_source_detail_page_from_homepage()
    {
        $this->actingAs(User::factory()->create());

        $detailPage = $this->createPublishedDetailPage('exclude-auto-source-from-homepage');

        SiteSetting::setHomeContent(SiteSetting::homeContentDefaults());

        $response = $this->post(route('admin.home-content.auto-source.visibility', $detailPage), [
            'action' => 'exclude',
        ]);

        $response->assertRedirect(route('admin.home-content.index'));
        $response->assertSessionHas('success');

        $excludedDetailPageIds = data_get(
            SiteSetting::homeContent(),
            'project_highlights.auto_excluded_detail_page_ids',
            []
        );

        $this->assertSame([$detailPage->id], $excludedDetailPageIds);
    }

    public function test_authenticated_admin_can_restore_excluded_auto_source_detail_page_to_homepage()
    {
        $this->actingAs(User::factory()->create());

        $detailPage = $this->createPublishedDetailPage('restore-auto-source-to-homepage');

        $homeContent = SiteSetting::homeContentDefaults();
        $homeContent['project_highlights']['auto_excluded_detail_page_ids'] = [$detailPage->id];
        SiteSetting::setHomeContent($homeContent);

        $response = $this->post(route('admin.home-content.auto-source.visibility', $detailPage), [
            'action' => 'include',
        ]);

        $response->assertRedirect(route('admin.home-content.index'));
        $response->assertSessionHas('success');

        $excludedDetailPageIds = data_get(
            SiteSetting::homeContent(),
            'project_highlights.auto_excluded_detail_page_ids',
            []
        );

        $this->assertSame([], $excludedDetailPageIds);
    }

    public function test_invalid_action_keeps_existing_auto_source_exclusion_list()
    {
        $this->actingAs(User::factory()->create());

        $detailPage = $this->createPublishedDetailPage('invalid-action-keeps-existing-list');

        $homeContent = SiteSetting::homeContentDefaults();
        $homeContent['project_highlights']['auto_excluded_detail_page_ids'] = [$detailPage->id];
        SiteSetting::setHomeContent($homeContent);

        $response = $this->post(route('admin.home-content.auto-source.visibility', $detailPage), [
            'action' => 'invalid-action',
        ]);

        $response->assertSessionHasErrors('action');

        $excludedDetailPageIds = data_get(
            SiteSetting::homeContent(),
            'project_highlights.auto_excluded_detail_page_ids',
            []
        );

        $this->assertSame([$detailPage->id], $excludedDetailPageIds);
    }

    private function createPublishedDetailPage(string $slugSuffix): ProjectDetailPage
    {
        $project = Project::query()->create([
            'name' => 'Dự án ' . $slugSuffix,
            'slug' => 'du-an-' . $slugSuffix,
            'is_published' => true,
        ]);

        return ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Trang chi tiết ' . $slugSuffix,
            'slug' => 'trang-chi-tiet-' . $slugSuffix,
            'thumbnail_image' => '/uploads/projects/' . $slugSuffix . '.jpg',
            'thumbnail_click_action' => 'link',
            'sort_order' => 1,
            'is_published' => true,
        ]);
    }
}
