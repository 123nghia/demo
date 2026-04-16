<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Project;
use App\Models\ProjectDetailPage;
use App\Models\ProjectVideo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTechnicalFoundationTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_xml_is_accessible_and_contains_core_urls(): void
    {
        $project = Project::query()->create([
            'name' => 'SEO Project',
            'slug' => 'seo-project',
            'is_published' => true,
        ]);

        $detail = ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'SEO Detail Page',
            'slug' => 'seo-detail-page',
            'thumbnail_image' => '/uploads/projects/seo-detail.jpg',
            'thumbnail_click_action' => 'link',
            'is_published' => true,
        ]);

        $blog = Blog::query()->create([
            'title' => 'SEO Blog',
            'slug' => 'seo-blog',
            'display_zone' => 'all',
            'excerpt' => 'Blog excerpt for SEO testing',
            'is_published' => true,
        ]);

        $video = ProjectVideo::query()->create([
            'project_id' => $project->id,
            'title' => 'SEO Video',
            'slug' => 'seo-video',
            'display_zone' => 'all',
            'video_url' => 'https://example.com/video.mp4',
            'is_published' => true,
        ]);

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $response->assertSee('<urlset', false);

        $response->assertSee(url('/'));
        $response->assertSee(url('/' . $project->slug));
        $response->assertSee(url('/' . $detail->slug));
        $response->assertSee(route('site.blog.show', ['slug' => $blog->slug]));
        $response->assertSee(route('site.video.show', ['slug' => $video->slug]));
    }

    public function test_legacy_blog_slug_url_redirects_to_canonical_blog_route(): void
    {
        $blog = Blog::query()->create([
            'title' => 'Canonical blog redirect',
            'slug' => 'canonical-blog-redirect',
            'display_zone' => 'all',
            'excerpt' => 'Testing canonical redirect',
            'is_published' => true,
        ]);

        $response = $this->get('/' . $blog->slug);

        $response->assertStatus(301);
        $response->assertRedirect(route('site.blog.show', ['slug' => $blog->slug]));
    }

    public function test_legacy_video_slug_url_redirects_to_canonical_video_route(): void
    {
        $project = Project::query()->create([
            'name' => 'Canonical video project',
            'slug' => 'canonical-video-project',
            'is_published' => true,
        ]);

        $video = ProjectVideo::query()->create([
            'project_id' => $project->id,
            'title' => 'Canonical video redirect',
            'slug' => 'canonical-video-redirect',
            'display_zone' => 'all',
            'video_url' => 'https://example.com/video.mp4',
            'is_published' => true,
        ]);

        $response = $this->get('/' . $video->slug);

        $response->assertStatus(301);
        $response->assertRedirect(route('site.video.show', ['slug' => $video->slug]));
    }

    public function test_home_slug_redirects_to_root_for_canonical_url(): void
    {
        $response = $this->get('/home');

        $response->assertStatus(301);
        $response->assertRedirect(url('/'));
    }
}
