<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectDetailPage;
use App\Models\ProjectVideo;
use Illuminate\Support\Collection;

class SitemapController extends Controller
{
    public function index()
    {
        $urls = collect();

        $urls = $urls->merge($this->buildPageUrls());
        $urls = $urls->merge($this->buildProjectUrls());
        $urls = $urls->merge($this->buildProjectDetailUrls());
        $urls = $urls->merge($this->buildBlogUrls());
        $urls = $urls->merge($this->buildVideoUrls());

        $urls = $urls
            ->map(function ($item) {
                return $this->normalizeUrlItem($item);
            })
            ->filter(function ($item) {
                return !empty(data_get($item, 'loc'));
            })
            ->unique('loc')
            ->sortBy(function ($item) {
                return data_get($item, 'loc');
            })
            ->values();

        $xml = view('site.sitemap.index', [
            'urls' => $urls,
        ])->render();

        return response(
            '<?xml version="1.0" encoding="UTF-8"?>' . "\n" . $xml,
            200,
            ['Content-Type' => 'application/xml; charset=UTF-8']
        );
    }

    private function normalizeUrlItem($item): array
    {
        $loc = trim((string) data_get($item, 'loc'));
        $changefreq = trim((string) data_get($item, 'changefreq', 'monthly'));
        $priority = data_get($item, 'priority', '0.5');

        if (!in_array($changefreq, ['always', 'hourly', 'daily', 'weekly', 'monthly', 'yearly', 'never'], true)) {
            $changefreq = 'monthly';
        }

        if (!is_numeric($priority)) {
            $priority = 0.5;
        }

        $priority = max(0, min(1, (float) $priority));

        return [
            'loc' => $loc,
            'lastmod' => data_get($item, 'lastmod'),
            'changefreq' => $changefreq,
            'priority' => number_format($priority, 1, '.', ''),
        ];
    }

    private function buildPageUrls(): Collection
    {
        try {
            $pages = Page::query()
                ->published()
                ->whereNotNull('slug')
                ->get(['slug', 'page_key', 'updated_at', 'created_at']);
        } catch (\Throwable $exception) {
            $pages = collect();
        }

        $fallback = collect([
            [
                'slug' => 'home',
                'page_key' => 'home',
                'updated_at' => now(),
                'created_at' => now(),
            ],
            [
                'slug' => 'about-us',
                'page_key' => 'about',
                'updated_at' => now(),
                'created_at' => now(),
            ],
            [
                'slug' => 'blog',
                'page_key' => 'blog',
                'updated_at' => now(),
                'created_at' => now(),
            ],
            [
                'slug' => 'video',
                'page_key' => 'video',
                'updated_at' => now(),
                'created_at' => now(),
            ],
            [
                'slug' => 'lien-he',
                'page_key' => 'contact',
                'updated_at' => now(),
                'created_at' => now(),
            ],
            [
                'slug' => 'dang-ky-dich-vu',
                'page_key' => 'contact',
                'updated_at' => now(),
                'created_at' => now(),
            ],
        ]);

        $pages = $pages->isNotEmpty() ? $pages : $fallback;

        return $pages
            ->map(function ($page) {
                $slug = trim((string) data_get($page, 'slug'), '/');
                if ($slug === '' || $slug === 'home') {
                    $slug = '';
                }

                $loc = $slug === '' ? url('/') : url('/' . $slug);
                $pageKey = (string) data_get($page, 'page_key');

                return [
                    'loc' => $loc,
                    'lastmod' => data_get($page, 'updated_at') ?: data_get($page, 'created_at'),
                    'changefreq' => in_array($pageKey, ['home', 'blog', 'video'], true) ? 'weekly' : 'monthly',
                    'priority' => $slug === '' ? '1.0' : '0.8',
                ];
            })
            ->values();
    }

    private function buildProjectUrls(): Collection
    {
        try {
            $projects = Project::query()
                ->published()
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->get(['slug', 'updated_at', 'created_at']);
        } catch (\Throwable $exception) {
            $projects = collect();
        }

        return $projects
            ->map(function ($project) {
                $slug = trim((string) data_get($project, 'slug'), '/');
                if ($slug === '') {
                    return null;
                }

                return [
                    'loc' => url('/' . $slug),
                    'lastmod' => data_get($project, 'updated_at') ?: data_get($project, 'created_at'),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            })
            ->filter()
            ->values();
    }

    private function buildProjectDetailUrls(): Collection
    {
        try {
            $detailPages = ProjectDetailPage::query()
                ->published()
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->whereHas('project', function ($query) {
                    $query->published();
                })
                ->get(['slug', 'updated_at', 'created_at']);
        } catch (\Throwable $exception) {
            $detailPages = collect();
        }

        return $detailPages
            ->map(function ($detailPage) {
                $slug = trim((string) data_get($detailPage, 'slug'), '/');
                if ($slug === '') {
                    return null;
                }

                return [
                    'loc' => url('/' . $slug),
                    'lastmod' => data_get($detailPage, 'updated_at') ?: data_get($detailPage, 'created_at'),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            })
            ->filter()
            ->values();
    }

    private function buildBlogUrls(): Collection
    {
        try {
            $blogs = Blog::query()
                ->published()
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->get(['slug', 'published_at', 'updated_at', 'created_at']);
        } catch (\Throwable $exception) {
            $blogs = collect();
        }

        return $blogs
            ->map(function ($blog) {
                $slug = trim((string) data_get($blog, 'slug'), '/');
                if ($slug === '') {
                    return null;
                }

                return [
                    'loc' => route('site.blog.show', ['slug' => $slug]),
                    'lastmod' => data_get($blog, 'updated_at') ?: data_get($blog, 'published_at') ?: data_get($blog, 'created_at'),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            })
            ->filter()
            ->values();
    }

    private function buildVideoUrls(): Collection
    {
        try {
            $videos = ProjectVideo::query()
                ->published()
                ->whereNotNull('slug')
                ->where('slug', '!=', '')
                ->get(['slug', 'published_at', 'updated_at', 'created_at']);
        } catch (\Throwable $exception) {
            $videos = collect();
        }

        return $videos
            ->map(function ($video) {
                $slug = trim((string) data_get($video, 'slug'), '/');
                if ($slug === '') {
                    return null;
                }

                return [
                    'loc' => route('site.video.show', ['slug' => $slug]),
                    'lastmod' => data_get($video, 'updated_at') ?: data_get($video, 'published_at') ?: data_get($video, 'created_at'),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            })
            ->filter()
            ->values();
    }
}
