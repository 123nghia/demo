<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Project;
use App\Models\ProjectDetailPage;
use App\Models\ProjectVideo;
use App\Models\SiteSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeProjectThumbnailsTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_excludes_thumbnail_when_parent_project_unpublished()
    {
        $project = Project::query()->create([
            'name' => 'Dự án chưa publish',
            'slug' => 'du-an-chua-publish',
            'is_published' => false,
        ]);

        ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Chi tiết vẫn publish',
            'slug' => 'chi-tiet-van-publish',
            'thumbnail_image' => '/uploads/projects/thumb-unpublished-project.jpg',
            'thumbnail_click_action' => 'link',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();

        $html = $response->getContent();

        $this->assertSame(0, substr_count($html, 'class="project-card"'));
        $response->assertDontSee('data-dot="projects-1"', false);
    }

    public function test_homepage_does_not_render_fallback_project_cards_when_no_thumbnail_data()
    {
        $response = $this->get('/');

        $response->assertOk();

        $html = $response->getContent();

        $this->assertSame(0, substr_count($html, 'class="project-card"'));

        $response->assertDontSee('data-dot="projects-1"', false);
        $response->assertDontSee('data-dot="projects-2"', false);
        $response->assertDontSee('id="projects-1"', false);
        $response->assertDontSee('id="projects-2"', false);
    }

    public function test_homepage_renders_exact_thumbnail_count_without_duplication()
    {
        $project = Project::query()->create([
            'name' => 'Dự án test homepage',
            'slug' => 'du-an-test-homepage',
            'is_published' => true,
        ]);

        ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Chi tiết 1',
            'slug' => 'chi-tiet-1',
            'thumbnail_image' => '/uploads/projects/thumb-1.jpg',
            'thumbnail_click_action' => 'link',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Chi tiết 2',
            'slug' => 'chi-tiet-2',
            'thumbnail_image' => '/uploads/projects/thumb-2.jpg',
            'thumbnail_click_action' => 'link',
            'sort_order' => 2,
            'is_published' => true,
        ]);

        ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Chi tiết 3',
            'slug' => 'chi-tiet-3',
            'thumbnail_image' => '/uploads/projects/thumb-3.jpg',
            'thumbnail_click_action' => 'lightbox',
            'sort_order' => 3,
            'is_published' => true,
        ]);

        $response = $this->get('/');

        $response->assertOk();

        $html = $response->getContent();

        $this->assertSame(3, substr_count($html, 'class="project-card"'));
        $this->assertSame(2, substr_count($html, 'data-hover-redirect='));
        $this->assertSame(1, substr_count($html, 'data-image-preview='));

        $response->assertDontSee('id="projects-2"', false);
        $response->assertDontSee('data-dot="projects-2"', false);
    }

    public function test_homepage_manual_mode_supports_detail_project_blog_video_and_lightbox_cards()
    {
        $project = Project::query()->create([
            'name' => 'Dự án manual',
            'slug' => 'du-an-manual',
            'cover_image' => '/uploads/projects/project-cover.jpg',
            'is_published' => true,
        ]);

        $detailPage = ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Trang chi tiết manual',
            'slug' => 'trang-chi-tiet-manual',
            'summary' => 'Mô tả trang chi tiết',
            'thumbnail_image' => '/uploads/projects/detail-manual.jpg',
            'thumbnail_click_action' => 'link',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $blog = Blog::query()->create([
            'project_id' => $project->id,
            'title' => 'Blog manual',
            'slug' => 'blog-manual',
            'excerpt' => 'Mô tả blog manual',
            'thumbnail_image' => '/uploads/blog/blog-manual.jpg',
            'display_zone' => 'all',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $video = ProjectVideo::query()->create([
            'project_id' => $project->id,
            'title' => 'Video manual',
            'slug' => 'video-manual',
            'description' => 'Mô tả video manual',
            'thumbnail_image' => '/uploads/video/video-manual.jpg',
            'display_zone' => 'all',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $homeContent = SiteSetting::homeContentDefaults();
        $homeContent['project_highlights'] = [
            'mode' => 'manual',
            'items' => [
                [
                    'title' => null,
                    'description' => null,
                    'image' => null,
                    'action' => 'link',
                    'link_type' => 'detail',
                    'link_value' => $detailPage->id,
                ],
                [
                    'title' => 'Card dự án thủ công',
                    'description' => 'Đi đến trang dự án',
                    'image' => '/uploads/home/manual-project.jpg',
                    'action' => 'link',
                    'link_type' => 'project',
                    'link_value' => $project->id,
                ],
                [
                    'title' => 'Card blog thủ công',
                    'description' => 'Đi đến blog',
                    'image' => '/uploads/home/manual-blog.jpg',
                    'action' => 'link',
                    'link_type' => 'blog',
                    'link_value' => $blog->id,
                ],
                [
                    'title' => 'Card video thủ công',
                    'description' => 'Đi đến video',
                    'image' => '/uploads/home/manual-video.jpg',
                    'action' => 'link',
                    'link_type' => 'video',
                    'link_value' => $video->id,
                ],
                [
                    'title' => 'Card zoom ảnh',
                    'description' => 'Chỉ phóng to ảnh',
                    'image' => '/uploads/home/manual-lightbox.jpg',
                    'action' => 'lightbox',
                    'link_type' => null,
                    'link_value' => null,
                ],
            ],
        ];

        SiteSetting::setHomeContent($homeContent);

        $response = $this->get('/');

        $response->assertOk();

        $html = $response->getContent();

        $this->assertSame(5, substr_count($html, 'class="project-card"'));
        $this->assertSame(4, substr_count($html, 'data-hover-redirect='));
        $this->assertSame(1, substr_count($html, 'data-image-preview='));

        $response->assertSee('data-hover-redirect="' . url('/' . $detailPage->slug) . '"', false);
        $response->assertSee('data-hover-redirect="' . url('/' . $project->slug) . '"', false);
        $response->assertSee('data-hover-redirect="' . route('site.blog.show', ['slug' => $blog->slug]) . '"', false);
        $response->assertSee('data-hover-redirect="' . route('site.video.show', ['slug' => $video->slug]) . '"', false);
        $response->assertSee('data-image-preview="/uploads/home/manual-lightbox.jpg"', false);

        $response->assertSee($detailPage->title);
    }

    public function test_homepage_manual_mode_with_empty_items_falls_back_to_auto_cards()
    {
        $project = Project::query()->create([
            'name' => 'Dự án auto fallback',
            'slug' => 'du-an-auto-fallback',
            'is_published' => true,
        ]);

        ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Chi tiết đáng lẽ tự động lên',
            'slug' => 'chi-tiet-dang-le-tu-dong-len',
            'thumbnail_image' => '/uploads/projects/auto-fallback.jpg',
            'thumbnail_click_action' => 'link',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $homeContent = SiteSetting::homeContentDefaults();
        $homeContent['project_highlights'] = [
            'mode' => 'manual',
            'items' => [],
        ];

        SiteSetting::setHomeContent($homeContent);

        $response = $this->get('/');

        $response->assertOk();

        $html = $response->getContent();

        $this->assertSame(1, substr_count($html, 'class="project-card"'));
        $response->assertSee('data-dot="projects-1"', false);
        $response->assertSee('id="projects-1"', false);
    }

    public function test_homepage_manual_items_are_used_even_when_mode_is_auto()
    {
        $project = Project::query()->create([
            'name' => 'Dự án ưu tiên thủ công',
            'slug' => 'du-an-uu-tien-thu-cong',
            'is_published' => true,
        ]);

        $detailPage = ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Nguồn tự động vẫn còn',
            'slug' => 'nguon-tu-dong-van-con',
            'thumbnail_image' => '/uploads/projects/auto-card.jpg',
            'thumbnail_click_action' => 'link',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $homeContent = SiteSetting::homeContentDefaults();
        $homeContent['project_highlights'] = [
            'mode' => 'auto',
            'items' => [
                [
                    'title' => 'Card thủ công riêng',
                    'description' => 'Admin chủ động thêm',
                    'image' => '/uploads/home/manual-only-card.jpg',
                    'action' => 'lightbox',
                    'link_type' => null,
                    'link_value' => null,
                ],
            ],
        ];

        SiteSetting::setHomeContent($homeContent);

        $response = $this->get('/');

        $response->assertOk();

        $html = $response->getContent();

        $this->assertSame(2, substr_count($html, 'class="project-card"'));
        $response->assertSee('data-image-preview="/uploads/home/manual-only-card.jpg"', false);
        $response->assertSee('data-hover-redirect="' . url('/' . $detailPage->slug) . '"', false);
    }

    public function test_homepage_auto_source_hides_excluded_detail_pages_from_settings()
    {
        $project = Project::query()->create([
            'name' => 'Dự án lọc auto source',
            'slug' => 'du-an-loc-auto-source',
            'is_published' => true,
        ]);

        $excludedDetailPage = ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Card bị ẩn khỏi trang chủ',
            'slug' => 'card-bi-an-khoi-trang-chu',
            'thumbnail_image' => '/uploads/projects/excluded-card.jpg',
            'thumbnail_click_action' => 'link',
            'sort_order' => 1,
            'is_published' => true,
        ]);

        $visibleDetailPage = ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Card vẫn hiển thị',
            'slug' => 'card-van-hien-thi',
            'thumbnail_image' => '/uploads/projects/visible-card.jpg',
            'thumbnail_click_action' => 'link',
            'sort_order' => 2,
            'is_published' => true,
        ]);

        $homeContent = SiteSetting::homeContentDefaults();
        $homeContent['project_highlights']['auto_excluded_detail_page_ids'] = [$excludedDetailPage->id];
        SiteSetting::setHomeContent($homeContent);

        $response = $this->get('/');

        $response->assertOk();

        $html = $response->getContent();

        $this->assertSame(1, substr_count($html, 'class="project-card"'));
        $response->assertDontSee('data-hover-redirect="' . url('/' . $excludedDetailPage->slug) . '"', false);
        $response->assertSee('data-hover-redirect="' . url('/' . $visibleDetailPage->slug) . '"', false);
    }
}
