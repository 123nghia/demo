<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Project;
use Illuminate\Http\Request;

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
        $blogId = $blog ? $blog->id : 'NULL';

        $validated = $request->validate([
            'project_id' => 'nullable|integer|exists:projects,id',
            'title' => 'required|string|max:190',
            'slug' => 'required|string|max:190|regex:/^[a-z0-9\-]+$/|unique:blogs,slug,' . $blogId,
            'excerpt' => 'nullable|string|max:2500',
            'content' => 'nullable|string|max:50000',
            'thumbnail_image' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:190',
            'seo_description' => 'nullable|string|max:1000',
            'published_at' => 'nullable|date',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['project_id'] = !empty($validated['project_id']) ? (int) $validated['project_id'] : null;
        $validated['sort_order'] = (int) ($validated['sort_order'] ?? 0);
        $validated['is_published'] = $request->boolean('is_published');

        return $validated;
    }

    private function projectOptions()
    {
        return Project::query()
            ->ordered()
            ->get(['id', 'name', 'slug']);
    }
}
