<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectBlog;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProjectBlogController extends Controller
{
    public function create(Project $project)
    {
        return view('admin.projects.blogs.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $data = $this->validatedData($request);
        $project->blogs()->create($data);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Đã thêm blog cho dự án.');
    }

    public function edit(Project $project, ProjectBlog $blog)
    {
        $this->ensureOwnership($project, $blog);

        return view('admin.projects.blogs.edit', compact('project', 'blog'));
    }

    public function update(Request $request, Project $project, ProjectBlog $blog)
    {
        $this->ensureOwnership($project, $blog);

        $data = $this->validatedData($request, $blog);
        $blog->update($data);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Đã cập nhật blog.');
    }

    public function destroy(Project $project, ProjectBlog $blog)
    {
        $this->ensureOwnership($project, $blog);
        $blog->delete();

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Đã xoá blog.');
    }

    private function validatedData(Request $request, ProjectBlog $blog = null): array
    {
        $blogId = $blog ? $blog->id : 'NULL';

        $validated = $request->validate([
            'title' => 'required|string|max:190',
            'slug' => 'required|string|max:190|regex:/^[a-z0-9\-]+$/|unique:project_blogs,slug,' . $blogId,
            'excerpt' => 'nullable|string|max:1500',
            'content' => 'nullable|string|max:30000',
            'thumbnail_image' => 'nullable|string|max:255',
            'thumbnail_image_file' => 'nullable|image|max:4096',
            'target_url' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
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
        $filename = 'project-blog-thumb-' . date('YmdHis') . '-' . Str::random(8) . ($extension ? '.' . $extension : '');
        $destinationPath = $uploadDirectory . '/' . $filename;

        ImageProcessor::processAndSave($file, $destinationPath);

        $payload[$field] = '/uploads/blogs/' . $filename;
    }

    private function ensureOwnership(Project $project, ProjectBlog $blog): void
    {
        abort_unless((int) $blog->project_id === (int) $project->id, 404);
    }
}
