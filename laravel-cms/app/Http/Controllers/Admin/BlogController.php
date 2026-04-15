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

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::query()
            ->with([
                'project' => function ($query) {
                    $query->select(['id', 'name', 'slug']);
                },
            ])
            ->orderByDesc('published_at')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        return view('admin.blogs.create', [
            'projects' => $this->projectOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        Blog::query()->create($data);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Đã tạo bài blog thành công.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', [
            'blog' => $blog,
            'projects' => $this->projectOptions(),
        ]);
    }

    public function update(Request $request, Blog $blog)
    {
        $data = $this->validatedData($request, $blog);
        $blog->update($data);

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Đã cập nhật bài blog.');
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Đã xoá bài blog.');
    }

    private function validatedData(Request $request, ?Blog $blog = null): array
    {
        $validated = $request->validate([
            'project_id' => 'nullable|integer|exists:projects,id',
            'title' => 'required|string|max:190',
            'slug' => 'nullable|string|max:190',
            'display_zone' => 'required|string|in:all,blog,project',
            'excerpt' => 'nullable|string|max:2500',
            'content' => 'nullable|string|max:50000',
            'thumbnail_image' => 'nullable|string|max:255',
            'thumbnail_image_file' => 'nullable|image|max:4096',
            'seo_title' => 'nullable|string|max:190',
            'seo_description' => 'nullable|string|max:1000',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_published' => 'nullable|boolean',
        ]);

        $slugSource = trim((string) ($validated['slug'] ?? ''));

        $validated['slug'] = $this->generateUniqueSlug(
            (string) ($validated['title'] ?? ''),
            $blog,
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
            $validated['sort_order'] = (int) ($blog->sort_order ?? 0);
        }

        $validated['is_published'] = $request->boolean('is_published');

        $hasUploadedThumbnail = $request->hasFile('thumbnail_image_file');
        $this->replaceWithUploadedFile($request, 'thumbnail_image_file', 'thumbnail_image', $validated);

        $validated['thumbnail_image'] = trim((string) ($validated['thumbnail_image'] ?? ''));
        $validated['thumbnail_image'] = $validated['thumbnail_image'] === '' ? null : $validated['thumbnail_image'];

        if (is_null($validated['thumbnail_image']) && !$hasUploadedThumbnail && $blog) {
            $validated['thumbnail_image'] = $blog->thumbnail_image;
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

        $uploadDirectory = public_path('uploads/blogs');

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = 'blog-thumb-' . date('YmdHis') . '-' . Str::random(8) . ($extension ? '.' . $extension : '');

        $file->move($uploadDirectory, $filename);

        $payload[$field] = '/uploads/blogs/' . $filename;
    }

    private function generateUniqueSlug(string $title, ?Blog $blog = null, ?string $preferred = null): string
    {
        $base = Str::slug((string) ($preferred ?: $title));
        $base = $base !== '' ? $base : 'blog';

        $slug = $base;
        $counter = 2;

        while ($this->slugExistsInRouteSpace($slug, $blog)) {
            $slug = $base . '-' . $counter;
            $counter++;

            if ($counter > 9999) {
                $slug = $base . '-' . Str::lower(Str::random(6));
                break;
            }
        }

        return $slug;
    }

    private function slugExistsInRouteSpace(string $slug, ?Blog $blog = null): bool
    {
        if ($slug === '') {
            return true;
        }

        if (in_array($slug, $this->reservedSlugs(), true)) {
            return true;
        }

        $blogExists = Blog::query()
            ->when($blog, function ($query) use ($blog) {
                $query->where('id', '!=', $blog->id);
            })
            ->where('slug', $slug)
            ->exists();

        if ($blogExists) {
            return true;
        }

        return Project::query()->where('slug', $slug)->exists()
            || ProjectDetailPage::query()->where('slug', $slug)->exists()
            || ProjectVideo::query()->where('slug', $slug)->exists()
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
