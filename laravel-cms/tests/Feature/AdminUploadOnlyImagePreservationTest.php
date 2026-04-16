<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Project;
use App\Models\ProjectBlog;
use App\Models\ProjectDetailPage;
use App\Models\ProjectVideo;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminUploadOnlyImagePreservationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(User::factory()->create());
    }

    public function test_settings_update_preserves_existing_images_when_no_new_uploads(): void
    {
        SiteSetting::setMany([
            'header_logo' => '/uploads/settings/existing-header.png',
            'footer_logo' => '/uploads/settings/existing-footer.png',
            'favicon' => '/uploads/settings/existing-favicon.png',
            'apple_touch_icon' => '/uploads/settings/existing-apple-touch.png',
            'seo_og_image' => '/uploads/settings/existing-og.png',
        ]);

        $payload = $this->settingsPayload();
        unset($payload['header_logo'], $payload['footer_logo'], $payload['favicon'], $payload['seo_og_image']);

        $response = $this->put(route('admin.settings.update'), $payload);

        $response->assertRedirect(route('admin.settings.edit'));

        $settings = SiteSetting::allAsArray();
        $this->assertSame('/uploads/settings/existing-header.png', data_get($settings, 'header_logo'));
        $this->assertSame('/uploads/settings/existing-footer.png', data_get($settings, 'footer_logo'));
        $this->assertSame('/uploads/settings/existing-favicon.png', data_get($settings, 'favicon'));
        $this->assertSame('/uploads/settings/existing-apple-touch.png', data_get($settings, 'apple_touch_icon'));
        $this->assertSame('/uploads/settings/existing-og.png', data_get($settings, 'seo_og_image'));
    }

    public function test_home_content_update_preserves_existing_images_when_no_new_uploads(): void
    {
        $homeContent = SiteSetting::homeContentDefaults();
        data_set($homeContent, 'hero.background_image', '/uploads/home/existing-hero.jpg');
        data_set($homeContent, 'profile.background_image', '/uploads/home/existing-profile.jpg');
        data_set($homeContent, 'about.team_image', '/uploads/home/existing-team.jpg');
        data_set($homeContent, 'footer_cta.consult.background_image', '/uploads/home/existing-consult.jpg');
        data_set($homeContent, 'footer_cta.partner.background_image', '/uploads/home/existing-partner.jpg');

        SiteSetting::setHomeContent($homeContent);

        $payload = $this->homeContentPayloadFrom($homeContent);

        $response = $this->put(route('admin.home-content.update'), $payload);

        $response->assertRedirect(route('admin.home-content.index'));

        $updated = SiteSetting::homeContent();
        $this->assertSame('/uploads/home/existing-hero.jpg', data_get($updated, 'hero.background_image'));
        $this->assertSame('/uploads/home/existing-profile.jpg', data_get($updated, 'profile.background_image'));
        $this->assertSame('/uploads/home/existing-team.jpg', data_get($updated, 'about.team_image'));
        $this->assertSame('/uploads/home/existing-consult.jpg', data_get($updated, 'footer_cta.consult.background_image'));
        $this->assertSame('/uploads/home/existing-partner.jpg', data_get($updated, 'footer_cta.partner.background_image'));
    }

    public function test_about_content_update_preserves_existing_images_when_no_new_uploads(): void
    {
        $aboutContent = SiteSetting::aboutContentDefaults();
        data_set($aboutContent, 'hero.image', '/uploads/about-us/existing-hero.jpg');
        data_set($aboutContent, 'mission.image', '/uploads/about-us/existing-mission.jpg');
        data_set($aboutContent, 'vision.image', '/uploads/about-us/existing-vision.jpg');
        data_set($aboutContent, 'inspiration.image', '/uploads/about-us/existing-inspiration.jpg');
        data_set($aboutContent, 'advantages.image', '/uploads/about-us/existing-advantages.jpg');
        data_set($aboutContent, 'ceo.image', '/uploads/about-us/existing-ceo.jpg');

        SiteSetting::setAboutContent($aboutContent);

        $payload = $this->aboutContentPayloadFrom($aboutContent);

        $response = $this->put(route('admin.about-content.update'), $payload);

        $response->assertRedirect(route('admin.about-content.edit'));

        $updated = SiteSetting::aboutContent();
        $this->assertSame('/uploads/about-us/existing-hero.jpg', data_get($updated, 'hero.image'));
        $this->assertSame('/uploads/about-us/existing-mission.jpg', data_get($updated, 'mission.image'));
        $this->assertSame('/uploads/about-us/existing-vision.jpg', data_get($updated, 'vision.image'));
        $this->assertSame('/uploads/about-us/existing-inspiration.jpg', data_get($updated, 'inspiration.image'));
        $this->assertSame('/uploads/about-us/existing-advantages.jpg', data_get($updated, 'advantages.image'));
        $this->assertSame('/uploads/about-us/existing-ceo.jpg', data_get($updated, 'ceo.image'));
    }

    public function test_project_update_preserves_existing_cover_image_when_no_new_uploads(): void
    {
        $project = Project::query()->create([
            'name' => 'Project preserve cover',
            'slug' => 'project-preserve-cover',
            'cover_image' => '/uploads/projects/existing-cover.jpg',
            'is_published' => true,
        ]);

        $response = $this->put(route('admin.projects.update', $project), [
            'name' => 'Project preserve cover updated',
            'slug' => 'project-preserve-cover',
            'short_description' => 'Updated short description',
            'intro' => 'Updated intro',
            'seo_title' => 'Updated seo title',
            'seo_description' => 'Updated seo description',
            'sort_order' => 4,
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.projects.edit', $project));

        $project->refresh();
        $this->assertSame('/uploads/projects/existing-cover.jpg', $project->cover_image);
    }

    public function test_blog_update_preserves_existing_thumbnail_when_no_new_uploads(): void
    {
        $blog = Blog::query()->create([
            'title' => 'Blog preserve thumbnail',
            'slug' => 'blog-preserve-thumbnail',
            'display_zone' => 'all',
            'thumbnail_image' => '/uploads/blogs/existing-blog-thumb.jpg',
            'is_published' => true,
        ]);

        $response = $this->put(route('admin.blogs.update', $blog), [
            'project_id' => null,
            'title' => 'Blog preserve thumbnail updated',
            'slug' => 'blog-preserve-thumbnail',
            'display_zone' => 'all',
            'excerpt' => 'Updated excerpt',
            'content' => 'Updated content',
            'seo_title' => 'Updated seo title',
            'seo_description' => 'Updated seo description',
            'published_at' => null,
            'sort_order' => 3,
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.blogs.index'));

        $blog->refresh();
        $this->assertSame('/uploads/blogs/existing-blog-thumb.jpg', $blog->thumbnail_image);
    }

    public function test_video_update_preserves_existing_thumbnail_when_no_new_uploads(): void
    {
        $project = Project::query()->create([
            'name' => 'Project for video preserve',
            'slug' => 'project-for-video-preserve',
            'is_published' => true,
        ]);

        $video = ProjectVideo::query()->create([
            'project_id' => $project->id,
            'title' => 'Video preserve thumbnail',
            'slug' => 'video-preserve-thumbnail',
            'display_zone' => 'all',
            'thumbnail_image' => '/uploads/videos/existing-video-thumb.jpg',
            'is_published' => true,
        ]);

        $response = $this->put(route('admin.videos.update', $video), [
            'project_id' => $project->id,
            'title' => 'Video preserve thumbnail updated',
            'slug' => 'video-preserve-thumbnail',
            'display_zone' => 'all',
            'video_url' => 'https://example.com/video.mp4',
            'description' => 'Updated description',
            'content' => 'Updated content',
            'seo_title' => 'Updated seo title',
            'seo_description' => 'Updated seo description',
            'published_at' => null,
            'sort_order' => 6,
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.videos.index'));

        $video->refresh();
        $this->assertSame('/uploads/videos/existing-video-thumb.jpg', $video->thumbnail_image);
    }

    public function test_project_blog_update_preserves_existing_thumbnail_when_no_new_uploads(): void
    {
        $project = Project::query()->create([
            'name' => 'Project for nested blog',
            'slug' => 'project-for-nested-blog',
            'is_published' => true,
        ]);

        $projectBlog = ProjectBlog::query()->create([
            'project_id' => $project->id,
            'title' => 'Nested blog preserve thumbnail',
            'slug' => 'nested-blog-preserve-thumbnail',
            'thumbnail_image' => '/uploads/blogs/existing-nested-blog-thumb.jpg',
            'is_published' => true,
        ]);

        $response = $this->put(route('admin.projects.blogs.update', [$project, $projectBlog]), [
            'title' => 'Nested blog preserve thumbnail updated',
            'slug' => 'nested-blog-preserve-thumbnail',
            'excerpt' => 'Updated excerpt',
            'content' => 'Updated content',
            'target_url' => '/updated-target-url',
            'published_at' => null,
            'sort_order' => 2,
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.projects.edit', $project));

        $projectBlog->refresh();
        $this->assertSame('/uploads/blogs/existing-nested-blog-thumb.jpg', $projectBlog->thumbnail_image);
    }

    public function test_project_video_update_preserves_existing_thumbnail_when_no_new_uploads(): void
    {
        $project = Project::query()->create([
            'name' => 'Project for nested video',
            'slug' => 'project-for-nested-video',
            'is_published' => true,
        ]);

        $projectVideo = ProjectVideo::query()->create([
            'project_id' => $project->id,
            'title' => 'Nested video preserve thumbnail',
            'slug' => 'nested-video-preserve-thumbnail',
            'display_zone' => 'project',
            'thumbnail_image' => '/uploads/videos/existing-nested-video-thumb.jpg',
            'is_published' => true,
        ]);

        $response = $this->put(route('admin.projects.videos.update', [$project, $projectVideo]), [
            'title' => 'Nested video preserve thumbnail updated',
            'slug' => 'nested-video-preserve-thumbnail',
            'display_zone' => 'project',
            'video_url' => 'https://example.com/nested-video.mp4',
            'description' => 'Updated description',
            'content' => 'Updated content',
            'seo_title' => 'Updated seo title',
            'seo_description' => 'Updated seo description',
            'published_at' => null,
            'sort_order' => 5,
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.projects.edit', $project));

        $projectVideo->refresh();
        $this->assertSame('/uploads/videos/existing-nested-video-thumb.jpg', $projectVideo->thumbnail_image);
    }

    public function test_project_detail_page_update_preserves_existing_thumbnail_when_no_new_uploads(): void
    {
        $project = Project::query()->create([
            'name' => 'Project for detail page',
            'slug' => 'project-for-detail-page',
            'is_published' => true,
        ]);

        $detailPage = ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Detail page preserve thumbnail',
            'slug' => 'detail-page-preserve-thumbnail',
            'thumbnail_image' => '/uploads/projects/existing-detail-thumb.jpg',
            'thumbnail_click_action' => 'link',
            'gallery_images' => ['/uploads/projects/gallery-1.jpg'],
            'is_published' => true,
        ]);

        $response = $this->put(route('admin.projects.detail-pages.update', [$project, $detailPage]), [
            'title' => 'Detail page preserve thumbnail updated',
            'slug' => 'detail-page-preserve-thumbnail',
            'summary' => 'Updated summary',
            'content' => 'Updated content',
            'thumbnail_click_action' => 'link',
            'gallery_images_input' => "/uploads/projects/gallery-1.jpg\n/uploads/projects/gallery-2.jpg",
            'sort_order' => 7,
            'is_published' => 1,
        ]);

        $response->assertRedirect(route('admin.projects.edit', $project));

        $detailPage->refresh();
        $this->assertSame('/uploads/projects/existing-detail-thumb.jpg', $detailPage->thumbnail_image);
    }

    private function settingsPayload(array $overrides = []): array
    {
        $settings = SiteSetting::allAsArray();

        $payload = [
            'site_name' => data_get($settings, 'site_name', 'HOVI Việt Nam'),
            'site_tagline' => data_get($settings, 'site_tagline'),
            'header_logo' => data_get($settings, 'header_logo'),
            'footer_logo' => data_get($settings, 'footer_logo'),
            'favicon' => data_get($settings, 'favicon'),
            'seo_og_image' => data_get($settings, 'seo_og_image'),
            'seo_default_title' => data_get($settings, 'seo_default_title', 'SEO title'),
            'seo_default_description' => data_get($settings, 'seo_default_description', 'SEO description'),
            'seo_keywords' => data_get($settings, 'seo_keywords'),
            'seo_robots' => data_get($settings, 'seo_robots', 'index, follow'),
            'seo_canonical_base' => data_get($settings, 'seo_canonical_base'),
            'footer_company_name' => data_get($settings, 'footer_company_name', 'HOVI'),
            'footer_tax_code' => data_get($settings, 'footer_tax_code'),
            'footer_address' => data_get($settings, 'footer_address', 'Address'),
            'footer_website' => data_get($settings, 'footer_website'),
            'footer_email' => data_get($settings, 'footer_email'),
            'footer_phone' => data_get($settings, 'footer_phone'),
            'footer_copyright' => data_get($settings, 'footer_copyright'),
            'social_facebook' => data_get($settings, 'social_facebook'),
            'social_tiktok' => data_get($settings, 'social_tiktok'),
            'social_youtube' => data_get($settings, 'social_youtube'),
            'social_messenger' => data_get($settings, 'social_messenger'),
            'social_zalo' => data_get($settings, 'social_zalo'),
        ];

        return array_merge($payload, $overrides);
    }

    private function homeContentPayloadFrom(array $homeContent, array $overrides = []): array
    {
        $aboutStats = array_values((array) data_get($homeContent, 'about.stats', []));

        $payload = [
            'hero_scroll_target' => data_get($homeContent, 'hero.scroll_target', '#projects-1'),
            'profile_eyebrow' => data_get($homeContent, 'profile.eyebrow'),
            'profile_title' => data_get($homeContent, 'profile.title', 'Profile title'),
            'profile_description_1' => data_get($homeContent, 'profile.description_1', 'Profile description'),
            'profile_description_2' => data_get($homeContent, 'profile.description_2'),
            'profile_button_label' => data_get($homeContent, 'profile.button_label'),
            'profile_button_url' => data_get($homeContent, 'profile.button_url'),
            'profile_slider_images' => array_values((array) data_get($homeContent, 'profile.slider_images', [])),
            'about_title' => data_get($homeContent, 'about.title', 'About title'),
            'about_description' => data_get($homeContent, 'about.description', 'About description'),
            'about_stat_1_value' => data_get($aboutStats, '0.value'),
            'about_stat_1_label' => data_get($aboutStats, '0.label'),
            'about_stat_2_value' => data_get($aboutStats, '1.value'),
            'about_stat_2_label' => data_get($aboutStats, '1.label'),
            'about_stat_3_value' => data_get($aboutStats, '2.value'),
            'about_stat_3_label' => data_get($aboutStats, '2.label'),
            'about_stat_4_value' => data_get($aboutStats, '3.value'),
            'about_stat_4_label' => data_get($aboutStats, '3.label'),
            'about_cta_label' => data_get($homeContent, 'about.cta_label'),
            'about_cta_url' => data_get($homeContent, 'about.cta_url'),
            'footer_consult_title' => data_get($homeContent, 'footer_cta.consult.title'),
            'footer_consult_button_label' => data_get($homeContent, 'footer_cta.consult.button_label'),
            'footer_consult_button_url' => data_get($homeContent, 'footer_cta.consult.button_url'),
            'footer_partner_title' => data_get($homeContent, 'footer_cta.partner.title'),
            'footer_partner_button_label' => data_get($homeContent, 'footer_cta.partner.button_label'),
            'footer_partner_button_url' => data_get($homeContent, 'footer_cta.partner.button_url'),
            'project_highlights_mode' => data_get($homeContent, 'project_highlights.mode', 'auto'),
            'project_highlight_titles' => [],
            'project_highlight_descriptions' => [],
            'project_highlight_images' => [],
            'project_highlight_actions' => [],
            'project_highlight_link_types' => [],
            'project_highlight_link_values' => [],
        ];

        return array_merge($payload, $overrides);
    }

    private function aboutContentPayloadFrom(array $aboutContent, array $overrides = []): array
    {
        $coreItems = collect((array) data_get($aboutContent, 'core.items', []))
            ->map(function ($item) {
                return [
                    'title' => data_get($item, 'title'),
                    'description' => data_get($item, 'description'),
                    'image' => data_get($item, 'image'),
                    'image_alt' => data_get($item, 'image_alt'),
                ];
            })
            ->values()
            ->all();

        $manifestoItems = collect((array) data_get($aboutContent, 'manifesto.items', []))
            ->map(function ($item) {
                return [
                    'quote' => data_get($item, 'quote'),
                    'image' => data_get($item, 'image'),
                    'image_alt' => data_get($item, 'image_alt'),
                ];
            })
            ->values()
            ->all();

        $advantagesItems = collect((array) data_get($aboutContent, 'advantages.items', []))
            ->map(function ($item) {
                return [
                    'title' => data_get($item, 'title'),
                    'description' => data_get($item, 'description'),
                ];
            })
            ->values()
            ->all();

        $capacityStats = collect((array) data_get($aboutContent, 'capacity.stats', []))
            ->map(function ($item) {
                return [
                    'value' => data_get($item, 'value'),
                    'label' => data_get($item, 'label'),
                ];
            })
            ->values()
            ->all();

        $payload = [
            'hero_enabled' => 1,
            'hero_eyebrow' => data_get($aboutContent, 'hero.eyebrow'),
            'hero_title' => data_get($aboutContent, 'hero.title', 'Hero title'),
            'hero_description' => data_get($aboutContent, 'hero.description', 'Hero description'),
            'hero_image_alt' => data_get($aboutContent, 'hero.image_alt'),

            'mission_enabled' => 1,
            'mission_title' => data_get($aboutContent, 'mission.title', 'Mission title'),
            'mission_description' => data_get($aboutContent, 'mission.description', 'Mission description'),
            'mission_image_alt' => data_get($aboutContent, 'mission.image_alt'),

            'vision_enabled' => 1,
            'vision_title' => data_get($aboutContent, 'vision.title', 'Vision title'),
            'vision_description' => data_get($aboutContent, 'vision.description', 'Vision description'),
            'vision_image_alt' => data_get($aboutContent, 'vision.image_alt'),

            'inspiration_enabled' => 1,
            'inspiration_title' => data_get($aboutContent, 'inspiration.title', 'Inspiration title'),
            'inspiration_subtitle' => data_get($aboutContent, 'inspiration.subtitle'),
            'inspiration_description' => data_get($aboutContent, 'inspiration.description', 'Inspiration description'),
            'inspiration_image_alt' => data_get($aboutContent, 'inspiration.image_alt'),

            'definition_enabled' => 1,
            'definition_title' => data_get($aboutContent, 'definition.title', 'Definition title'),
            'definition_description' => data_get($aboutContent, 'definition.description', 'Definition description'),

            'core_enabled' => 1,
            'core_heading' => data_get($aboutContent, 'core.heading', 'Core heading'),
            'core_items' => $coreItems,

            'manifesto_enabled' => 1,
            'manifesto_heading' => data_get($aboutContent, 'manifesto.heading', 'Manifesto heading'),
            'manifesto_items' => $manifestoItems,

            'advantages_enabled' => 1,
            'advantages_title' => data_get($aboutContent, 'advantages.title', 'Advantages title'),
            'advantages_image_alt' => data_get($aboutContent, 'advantages.image_alt'),
            'advantages_items' => $advantagesItems,

            'ceo_enabled' => 1,
            'ceo_eyebrow' => data_get($aboutContent, 'ceo.eyebrow'),
            'ceo_title' => data_get($aboutContent, 'ceo.title', 'CEO title'),
            'ceo_description_1' => data_get($aboutContent, 'ceo.description_1', 'CEO description 1'),
            'ceo_description_2' => data_get($aboutContent, 'ceo.description_2', 'CEO description 2'),
            'ceo_image_alt' => data_get($aboutContent, 'ceo.image_alt'),

            'capacity_enabled' => 1,
            'capacity_heading' => data_get($aboutContent, 'capacity.heading', 'Capacity heading'),
            'capacity_lead' => data_get($aboutContent, 'capacity.lead', 'Capacity lead'),
            'capacity_stats' => $capacityStats,
            'capacity_action_1_label' => data_get($aboutContent, 'capacity.action_1_label'),
            'capacity_action_1_url' => data_get($aboutContent, 'capacity.action_1_url'),
            'capacity_action_2_label' => data_get($aboutContent, 'capacity.action_2_label'),
            'capacity_action_2_url' => data_get($aboutContent, 'capacity.action_2_url'),
        ];

        return array_merge($payload, $overrides);
    }
}
