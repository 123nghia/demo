<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\ContactMessage;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectDetailPage;
use App\Models\ProjectVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function show($slug = null)
    {
        $resolvedSlug = trim((string) $slug, '/');
        $resolvedSlug = $resolvedSlug === '' ? 'home' : $resolvedSlug;

        if (Str::startsWith($resolvedSlug, 'admin')) {
            abort(404);
        }

        $project = $this->resolveProject($resolvedSlug);
        if ($project instanceof Project) {
            return view('site.projects.show', [
                'project' => $project,
            ]);
        }

        $detailPage = $this->resolveProjectDetailPage($resolvedSlug);
        if ($detailPage instanceof ProjectDetailPage) {
            return view('site.projects.detail', [
                'project' => $detailPage->project,
                'detailPage' => $detailPage,
            ]);
        }

        $video = $this->resolveProjectVideo($resolvedSlug);
        if ($video instanceof ProjectVideo) {
            return view('site.video.show', [
                'video' => $video,
                'relatedVideos' => $this->resolveRelatedVideos($video),
                'page' => new Page([
                    'name' => 'Chi tiết video',
                    'slug' => $video->slug,
                    'legacy_file' => 'site.video.show',
                    'page_key' => 'video',
                    'seo_title' => $video->seo_title ?: $video->title,
                    'seo_description' => $video->seo_description ?: $video->description,
                ]),
            ]);
        }

        $blog = $this->resolveBlogPost($resolvedSlug);
        if ($blog instanceof Blog) {
            return view('site.blog.show', [
                'blog' => $blog,
                'relatedBlogs' => $this->resolveRelatedBlogs($blog),
                'page' => new Page([
                    'name' => 'Chi tiết blog',
                    'slug' => $blog->slug,
                    'legacy_file' => 'site.blog.show',
                    'page_key' => 'blog',
                    'seo_title' => $blog->seo_title ?: $blog->title,
                    'seo_description' => $blog->seo_description ?: $blog->excerpt,
                ]),
            ]);
        }

        $page = $this->resolvePage($resolvedSlug);
        abort_unless($page instanceof Page, 404);

        $viewName = $this->resolveViewName((string) $page->legacy_file);
        abort_unless(view()->exists($viewName), 404, 'Trang chưa có blade view được cấu hình.');

        $viewData = [
            'page' => $page,
        ];

        if ($viewName === 'site.pages.home') {
            $viewData['homeProjectHighlights'] = $this->resolveHomeProjectHighlights();
        }

        return view($viewName, $viewData);
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'service' => 'nullable|string|max:255',
            'message' => 'required|string|max:4000',
            'source_page' => 'nullable|string|max:120',
        ]);

        if (empty($validated['source_page'])) {
            $previousPath = trim((string) parse_url(url()->previous(), PHP_URL_PATH), '/');
            $validated['source_page'] = $previousPath === '' ? 'home' : $previousPath;
        }

        try {
            ContactMessage::query()->create($validated);
        } catch (\Throwable $exception) {
            report($exception);

            $errorMessage = 'Hệ thống đang bận, vui lòng thử lại sau ít phút.';

            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $errorMessage,
                ], 500);
            }

            return back()
                ->withInput()
                ->withErrors([
                    'contact' => $errorMessage,
                ]);
        }

        $successMessage = 'HOVI đã nhận thông tin. Chúng tôi sẽ liên hệ bạn sớm nhất.';

        if ($request->expectsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => $successMessage,
            ]);
        }

        return back()->with('success', $successMessage);
    }

    private function resolveViewName(string $configuredView): string
    {
        $viewName = trim($configuredView);
        $viewName = preg_replace('/\.blade\.php$/i', '', $viewName);
        $viewName = preg_replace('/\.html$/i', '', $viewName);
        $viewName = str_replace(['\\', '/'], '.', $viewName);

        if (!Str::startsWith($viewName, 'site.pages.')) {
            $viewName = 'site.pages.' . $viewName;
        }

        return $viewName;
    }

    private function resolveProject(string $slug): ?Project
    {
        try {
            return Project::query()
                ->published()
                ->where('slug', $slug)
                ->with([
                    'detailPages' => function ($query) {
                        $query->published()->ordered();
                    },
                    'blogs' => function ($query) {
                        $query->published()->ordered();
                    },
                    'videos' => function ($query) {
                        $query->published()->inDisplayZones(['all', 'project'])->ordered();
                    },
                ])
                ->first();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function resolveProjectDetailPage(string $slug): ?ProjectDetailPage
    {
        try {
            return ProjectDetailPage::query()
                ->published()
                ->where('slug', $slug)
                ->whereHas('project', function ($query) {
                    $query->published();
                })
                ->with([
                    'project' => function ($query) {
                        $query->with([
                            'detailPages' => function ($detailQuery) {
                                $detailQuery->published()->ordered();
                            },
                        ]);
                    },
                ])
                ->first();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function resolveProjectVideo(string $slug): ?ProjectVideo
    {
        try {
            return ProjectVideo::query()
                ->published()
                ->where('slug', $slug)
                ->whereHas('project', function ($query) {
                    $query->published();
                })
                ->with([
                    'project' => function ($query) {
                        $query->select(['id', 'name', 'slug']);
                    },
                ])
                ->first();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function resolveBlogPost(string $slug): ?Blog
    {
        try {
            return Blog::query()
                ->published()
                ->where('slug', $slug)
                ->with([
                    'project' => function ($query) {
                        $query->select(['id', 'name', 'slug']);
                    },
                ])
                ->first();
        } catch (\Throwable $exception) {
            return null;
        }
    }

    private function resolveRelatedVideos(ProjectVideo $video)
    {
        try {
            return ProjectVideo::query()
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
        } catch (\Throwable $exception) {
            return collect();
        }
    }

    private function resolveRelatedBlogs(Blog $blog)
    {
        try {
            return Blog::query()
                ->published()
                ->where('id', '!=', $blog->id)
                ->when(!is_null($blog->project_id), function ($query) use ($blog) {
                    $query->orderByRaw('CASE WHEN project_id = ? THEN 0 ELSE 1 END', [$blog->project_id]);
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
        } catch (\Throwable $exception) {
            return collect();
        }
    }

    private function resolveHomeProjectHighlights()
    {
        try {
            return ProjectDetailPage::query()
                ->published()
                ->whereNotNull('thumbnail_image')
                ->where('thumbnail_image', '!=', '')
                ->whereHas('project', function ($query) {
                    $query->published();
                })
                ->with([
                    'project' => function ($query) {
                        $query->select(['id', 'name', 'slug']);
                    },
                ])
                ->ordered()
                ->limit(12)
                ->get();
        } catch (\Throwable $exception) {
            return collect();
        }
    }

    private function resolvePage(string $slug): ?Page
    {
        try {
            $page = Page::query()
                ->published()
                ->where('slug', $slug)
                ->first();

            if ($page instanceof Page) {
                return $page;
            }
        } catch (\Throwable $exception) {
            // Ignore and use fallback mapping.
        }

        $fallback = $this->fallbackPages()[$slug] ?? null;
        if (empty($fallback)) {
            return null;
        }

        return new Page($fallback);
    }

    private function fallbackPages(): array
    {
        return [
            'home' => [
                'name' => 'Trang chủ',
                'slug' => 'home',
                'legacy_file' => 'home',
                'page_key' => 'home',
            ],
            'about-us' => [
                'name' => 'Giới thiệu',
                'slug' => 'about-us',
                'legacy_file' => 'about-us',
                'page_key' => 'about',
            ],
            'lien-he' => [
                'name' => 'Liên hệ',
                'slug' => 'lien-he',
                'legacy_file' => 'lien-he',
                'page_key' => 'contact',
            ],
            'dang-ky-dich-vu' => [
                'name' => 'Đăng ký dịch vụ',
                'slug' => 'dang-ky-dich-vu',
                'legacy_file' => 'dang-ky-dich-vu',
                'page_key' => 'contact',
            ],
            'thiet-ke-biet-thu-vinhomes-ocean-park' => [
                'name' => 'Thiết kế biệt thự Vinhomes Ocean Park',
                'slug' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'legacy_file' => 'thiet-ke-biet-thu-vinhomes-ocean-park',
                'page_key' => 'oceanpark',
            ],
            'biet-thu-don-lap-m07-l14-dtm-duong-noi' => [
                'name' => 'Biệt thự đơn lập M07-L14 Dương Nội',
                'slug' => 'biet-thu-don-lap-m07-l14-dtm-duong-noi',
                'legacy_file' => 'biet-thu-don-lap-m07-l14-dtm-duong-noi',
                'page_key' => 'project',
            ],
        ];
    }
}
