<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectDetailPage;
use App\Models\ProjectVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VideoController extends Controller
{
    public function index()
    {
        $videos = ProjectVideo::query()
            ->with([
                'project' => function ($query) {
                    $query->select(['id', 'name', 'slug']);
                },
            ])
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.videos.index', compact('videos'));
    }

    public function create(Request $request)
    {
        return view('admin.videos.create', [
            'projects' => $this->projectOptions(),
            'defaultProjectId' => (string) $request->query('project_id', ''),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        ProjectVideo::query()->create($data);

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Đã tạo video thành công.');
    }

    public function edit(ProjectVideo $video)
    {
        return view('admin.videos.edit', [
            'video' => $video,
            'projects' => $this->projectOptions(),
            'defaultProjectId' => (string) ($video->project_id ?? ''),
        ]);
    }

    public function update(Request $request, ProjectVideo $video)
    {
        $data = $this->validatedData($request, $video);
        $video->update($data);

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Đã cập nhật video.');
    }

    public function destroy(ProjectVideo $video)
    {
        $video->delete();

        return redirect()
            ->route('admin.videos.index')
            ->with('success', 'Đã xoá video.');
    }

    private function validatedData(Request $request, ?ProjectVideo $video = null): array
    {
        $validated = $request->validate([
            'project_id' => 'required|integer|exists:projects,id',
            'title' => 'required|string|max:190',
            'slug' => 'nullable|string|max:190',
            'display_zone' => 'required|string|in:all,video,project',
            'video_url' => 'nullable|string|max:500',
            'thumbnail_image' => 'nullable|string|max:255',
            'thumbnail_image_file' => 'nullable|image|max:4096',
            'description' => 'nullable|string|max:2500',
            'content' => 'nullable|string|max:50000',
            'seo_title' => 'nullable|string|max:190',
            'seo_description' => 'nullable|string|max:1000',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_published' => 'nullable|boolean',
        ]);

        $slugSource = trim((string) ($validated['slug'] ?? ''));

        $validated['slug'] = $this->generateUniqueSlug(
            (string) ($validated['title'] ?? ''),
            $video,
            $slugSource
        );

        $validated['project_id'] = !empty($validated['project_id']) ? (int) $validated['project_id'] : null;
        $validated['display_zone'] = trim((string) ($validated['display_zone'] ?? 'all')) ?: 'all';

        if ($validated['display_zone'] === 'project' && is_null($validated['project_id'])) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'project_id' => 'Vui lòng chọn dự án khi phân vùng hiển thị là khu vực dự án.',
            ]);
        }

        if (array_key_exists('sort_order', $validated) && !is_null($validated['sort_order'])) {
            $validated['sort_order'] = (int) $validated['sort_order'];
        } else {
            $validated['sort_order'] = (int) ($video->sort_order ?? 0);
        }

        $validated['is_published'] = $request->boolean('is_published');

        $this->replaceWithUploadedFile($request, 'thumbnail_image_file', 'thumbnail_image', $validated);

        $validated['thumbnail_image'] = trim((string) ($validated['thumbnail_image'] ?? ''));
        $validated['thumbnail_image'] = $validated['thumbnail_image'] === '' ? null : $validated['thumbnail_image'];

        unset($validated['thumbnail_image_file']);

        return $validated;
    }

    private function replaceWithUploadedFile(Request $request, string $fileInput, string $field, array &$payload): void
    {
        if (!$request->hasFile($fileInput)) {
            return;
        }

        $file = $request->file($fileInput);
        if (!$file || !$file->isValid()) {
            return;
        }

        $uploadDirectory = public_path('uploads/videos');

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = 'video-thumb-' . date('YmdHis') . '-' . Str::random(8) . ($extension ? '.' . $extension : '');

        $file->move($uploadDirectory, $filename);

        $payload[$field] = '/uploads/videos/' . $filename;
    }

    private function generateUniqueSlug(string $title, ?ProjectVideo $video = null, ?string $preferred = null): string
    {
        $base = Str::slug((string) ($preferred ?: $title));
        $base = $base !== '' ? $base : 'video';

        $slug = $base;
        $counter = 2;

        while ($this->slugExistsInRouteSpace($slug, $video)) {
            $slug = $base . '-' . $counter;
            $counter++;

            if ($counter > 9999) {
                $slug = $base . '-' . Str::lower(Str::random(6));
                break;
            }
        }

        return $slug;
    }

    private function slugExistsInRouteSpace(string $slug, ?ProjectVideo $video = null): bool
    {
        if ($slug === '') {
            return true;
        }

        if (in_array($slug, $this->reservedSlugs(), true)) {
            return true;
        }

        $videoExists = ProjectVideo::query()
            ->when($video, function ($query) use ($video) {
                $query->where('id', '!=', $video->id);
            })
            ->where('slug', $slug)
            ->exists();

        if ($videoExists) {
            return true;
        }

        return Project::query()->where('slug', $slug)->exists()
            || ProjectDetailPage::query()->where('slug', $slug)->exists()
            || Blog::query()->where('slug', $slug)->exists()
            || Page::query()->where('slug', $slug)->exists();
    }

    private function reservedSlugs(): array
    {
        return [
            'admin',
            'blog',
            'video',
            'contact-submit',
            'login',
            'logout',
        ];
    }

    private function projectOptions()
    {
        return Project::query()
            ->ordered()
            ->get(['id', 'name', 'slug']);
    }
}
