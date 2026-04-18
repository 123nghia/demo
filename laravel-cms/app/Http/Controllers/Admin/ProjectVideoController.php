<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Page;
use App\Models\Project;
use App\Models\ProjectDetailPage;
use App\Models\ProjectVideo;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectVideoController extends Controller
{
    public function create(Project $project)
    {
        return view('admin.projects.videos.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $data = $this->validatedData($request);
        $project->videos()->create($data);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Đã thêm video thực tế cho dự án.');
    }

    public function edit(Project $project, ProjectVideo $video)
    {
        $this->ensureOwnership($project, $video);

        return view('admin.projects.videos.edit', compact('project', 'video'));
    }

    public function update(Request $request, Project $project, ProjectVideo $video)
    {
        $this->ensureOwnership($project, $video);

        $data = $this->validatedData($request, $video);
        $video->update($data);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Đã cập nhật video thực tế.');
    }

    public function destroy(Project $project, ProjectVideo $video)
    {
        $this->ensureOwnership($project, $video);
        $video->delete();

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Đã xoá video thực tế.');
    }

    private function validatedData(Request $request, ProjectVideo $video = null): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:190',
            'slug' => 'nullable|string|max:190',
            'display_zone' => 'nullable|string|in:all,video,project',
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

        $validated['display_zone'] = trim((string) ($validated['display_zone'] ?? 'project')) ?: 'project';

        if (array_key_exists('sort_order', $validated) && !is_null($validated['sort_order'])) {
            $validated['sort_order'] = (int) $validated['sort_order'];
        } else {
            $validated['sort_order'] = (int) ($video->sort_order ?? 0);
        }

        $validated['is_published'] = $request->boolean('is_published');

        $hasUploadedThumbnail = $request->hasFile('thumbnail_image_file');
        $this->replaceWithUploadedFile($request, 'thumbnail_image_file', 'thumbnail_image', $validated);

        $validated['thumbnail_image'] = trim((string) ($validated['thumbnail_image'] ?? ''));
        $validated['thumbnail_image'] = $validated['thumbnail_image'] === '' ? null : $validated['thumbnail_image'];

        if (is_null($validated['thumbnail_image']) && !$hasUploadedThumbnail && $video) {
            $validated['thumbnail_image'] = $video->thumbnail_image;
        }

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
        $destinationPath = $uploadDirectory . '/' . $filename;

        ImageProcessor::processAndSave($file, $destinationPath);

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

    private function ensureOwnership(Project $project, ProjectVideo $video): void
    {
        abort_unless((int) $video->project_id === (int) $project->id, 404);
    }
}
