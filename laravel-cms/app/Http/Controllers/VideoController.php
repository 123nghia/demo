<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\ProjectVideo;

class VideoController extends Controller
{
    public function index()
    {
        $videos = ProjectVideo::query()
            ->published()
            ->inDisplayZones(['all', 'video'])
            ->with([
                'project' => function ($query) {
                    $query->select(['id', 'name', 'slug']);
                },
            ])
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $page = $this->resolveVideoPage();

        return view('site.video.index', [
            'page' => $page,
            'videos' => $videos,
        ]);
    }

    public function show(string $slug)
    {
        $video = ProjectVideo::query()
            ->published()
            ->where('slug', trim($slug))
            ->with([
                'project' => function ($query) {
                    $query->select(['id', 'name', 'slug']);
                },
            ])
            ->firstOrFail();

        $relatedVideos = ProjectVideo::query()
            ->published()
            ->whereNotNull('slug')
            ->where('id', '!=', $video->id)
            ->when(!is_null($video->project_id), function ($query) use ($video) {
                $query->orderByRaw('CASE WHEN project_id = ? THEN 0 ELSE 1 END', [$video->project_id]);
            })
            ->with([
                'project' => function ($query) {
                    $query->select(['id', 'name', 'slug']);
                },
            ])
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->limit(4)
            ->get();

        return view('site.video.show', [
            'video' => $video,
            'relatedVideos' => $relatedVideos,
            'page' => new Page([
                'name' => 'Chi tiết video',
                'slug' => 'video/' . $video->slug,
                'legacy_file' => 'site.video.show',
                'page_key' => 'video',
                'seo_title' => $video->seo_title ?: $video->title,
                'seo_description' => $video->seo_description ?: $video->description,
            ]),
        ]);
    }

    private function resolveVideoPage(): Page
    {
        try {
            $page = Page::query()
                ->published()
                ->where('slug', 'video')
                ->first();

            if ($page instanceof Page) {
                return $page;
            }
        } catch (\Throwable $exception) {
            // Fallback below.
        }

        return new Page([
            'name' => 'Video',
            'slug' => 'video',
            'legacy_file' => 'site.video.index',
            'page_key' => 'video',
            'seo_title' => 'Video HOVI Việt Nam | Công trình thực tế & chia sẻ chuyên môn',
            'seo_description' => 'Tổng hợp video công trình thực tế, hậu trường triển khai và kinh nghiệm thiết kế thi công từ HOVI Việt Nam.',
        ]);
    }
}
