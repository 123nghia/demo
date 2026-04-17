<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\Page;
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
        $response->assertSee('<changefreq>weekly</changefreq>', false);
        $response->assertSee('<priority>0.7</priority>', false);
    }

    public function test_sitemap_excludes_unpublished_and_deleted_content(): void
    {
        Page::query()->create([
            'name' => 'Hidden page',
            'slug' => 'hidden-page',
            'legacy_file' => 'home',
            'page_key' => 'hidden',
            'is_published' => false,
        ]);

        $hiddenProject = Project::query()->create([
            'name' => 'Hidden Project',
            'slug' => 'hidden-project',
            'is_published' => false,
        ]);

        ProjectDetailPage::query()->create([
            'project_id' => $hiddenProject->id,
            'title' => 'Hidden Detail',
            'slug' => 'hidden-detail',
            'is_published' => true,
        ]);

        Blog::query()->create([
            'title' => 'Hidden Blog',
            'slug' => 'hidden-blog',
            'display_zone' => 'all',
            'is_published' => false,
        ]);

        ProjectVideo::query()->create([
            'project_id' => $hiddenProject->id,
            'title' => 'Hidden Video',
            'slug' => 'hidden-video',
            'display_zone' => 'all',
            'is_published' => false,
        ]);

        $deletedBlog = Blog::query()->create([
            'title' => 'Deleted Blog',
            'slug' => 'deleted-blog',
            'display_zone' => 'all',
            'is_published' => true,
        ]);
        $deletedBlogUrl = route('site.blog.show', ['slug' => $deletedBlog->slug]);
        $deletedBlog->delete();

        $response = $this->get('/sitemap.xml');

        $response->assertOk();
        $response->assertDontSee(url('/hidden-page'));
        $response->assertDontSee(url('/hidden-project'));
        $response->assertDontSee(url('/hidden-detail'));
        $response->assertDontSee(route('site.blog.show', ['slug' => 'hidden-blog']));
        $response->assertDontSee(route('site.video.show', ['slug' => 'hidden-video']));
        $response->assertDontSee($deletedBlogUrl);
    }

    public function test_detail_pages_output_breadcrumb_article_and_image_schema(): void
    {
        $project = Project::query()->create([
            'name' => 'Schema Project',
            'slug' => 'schema-project',
            'cover_image' => '/uploads/projects/schema-cover.jpg',
            'is_published' => true,
        ]);

        $detail = ProjectDetailPage::query()->create([
            'project_id' => $project->id,
            'title' => 'Schema Detail',
            'slug' => 'schema-detail',
            'summary' => 'Schema detail summary',
            'thumbnail_image' => '/uploads/projects/schema-detail.jpg',
            'is_published' => true,
        ]);

        $blog = Blog::query()->create([
            'title' => 'Schema Blog',
            'slug' => 'schema-blog',
            'display_zone' => 'all',
            'excerpt' => 'Schema blog excerpt',
            'thumbnail_image' => '/uploads/blogs/schema-blog.jpg',
            'is_published' => true,
        ]);

        $video = ProjectVideo::query()->create([
            'project_id' => $project->id,
            'title' => 'Schema Video',
            'slug' => 'schema-video',
            'display_zone' => 'all',
            'thumbnail_image' => '/uploads/videos/schema-video.jpg',
            'video_url' => 'https://example.com/schema-video.mp4',
            'is_published' => true,
        ]);

        $this->get('/' . $detail->slug)
            ->assertOk()
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('"@type":"CreativeWork"', false)
            ->assertSee(url('/' . $detail->slug) . '#primaryimage', false);

        $this->get(route('site.blog.show', ['slug' => $blog->slug]))
            ->assertOk()
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('"@type":"Article"', false)
            ->assertSee(route('site.blog.show', ['slug' => $blog->slug]) . '#primaryimage', false);

        $this->get(route('site.video.show', ['slug' => $video->slug]))
            ->assertOk()
            ->assertSee('"@type":"BreadcrumbList"', false)
            ->assertSee('"@type":"VideoObject"', false)
            ->assertSee(route('site.video.show', ['slug' => $video->slug]) . '#primaryimage', false);
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
