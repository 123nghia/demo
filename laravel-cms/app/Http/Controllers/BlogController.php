<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Page;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::query()
            ->published()
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $page = $this->resolveBlogPage();

        return view('site.blog.index', [
            'page' => $page,
            'blogs' => $blogs,
        ]);
    }

    public function show(string $slug)
    {
        $blog = Blog::query()
            ->published()
            ->where('slug', trim($slug))
            ->firstOrFail();

        $relatedBlogs = Blog::query()
            ->published()
            ->where('id', '!=', $blog->id)
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(3)
            ->get();

        return view('site.blog.show', [
            'blog' => $blog,
            'relatedBlogs' => $relatedBlogs,
            'page' => new Page([
                'name' => 'Chi tiết blog',
                'slug' => 'blog/' . $blog->slug,
                'legacy_file' => 'site.blog.show',
                'page_key' => 'blog',
                'seo_title' => $blog->seo_title ?: $blog->title,
                'seo_description' => $blog->seo_description ?: $blog->excerpt,
            ]),
        ]);
    }

    private function resolveBlogPage(): Page
    {
        try {
            $page = Page::query()
                ->published()
                ->where('slug', 'blog')
                ->first();

            if ($page instanceof Page) {
                return $page;
            }
        } catch (\Throwable $exception) {
            // Fallback below.
        }

        return new Page([
            'name' => 'Blog',
            'slug' => 'blog',
            'legacy_file' => 'site.blog.index',
            'page_key' => 'blog',
            'seo_title' => 'Blog HOVI Việt Nam | Chia sẻ thiết kế cảnh quan',
            'seo_description' => 'Chuyên mục Blog của HOVI Việt Nam: xu hướng thiết kế cảnh quan, kinh nghiệm thi công và tư vấn không gian sống.',
        ]);
    }
}
