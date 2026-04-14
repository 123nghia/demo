<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDetailPage;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::query()
            ->withCount(['detailPages', 'blogs', 'videos'])
            ->ordered()
            ->paginate(12);

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $data = $this->validatedData($request);
        Project::query()->create($data);

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Đã tạo dự án thành công.');
    }

    public function edit(Project $project)
    {
        $project->load([
            'detailPages' => function ($query) {
                $query->ordered();
            },
            'blogs' => function ($query) {
                $query->ordered();
            },
            'videos' => function ($query) {
                $query->ordered();
            },
        ]);

        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $this->validatedData($request, $project);
        $project->update($data);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Đã cập nhật dự án thành công.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()
            ->route('admin.projects.index')
            ->with('success', 'Đã xoá dự án thành công.');
    }

    private function validatedData(Request $request, Project $project = null): array
    {
        $projectId = $project ? $project->id : 'NULL';

        $validated = $request->validate([
            'name' => 'required|string|max:180',
            'slug' => 'required|string|max:180|regex:/^[a-z0-9\-]+$/|unique:projects,slug,' . $projectId,
            'short_description' => 'nullable|string|max:1200',
            'intro' => 'nullable|string|max:15000',
            'cover_image' => 'nullable|string|max:255',
            'seo_title' => 'nullable|string|max:190',
            'seo_description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_published' => 'nullable|boolean',
        ]);

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_published'] = $request->boolean('is_published');

        $detailSlugExists = ProjectDetailPage::query()->where('slug', $validated['slug'])->exists();
        if ($detailSlugExists) {
            throw ValidationException::withMessages([
                'slug' => 'Slug này đang được dùng bởi một trang chi tiết dự án. Vui lòng chọn slug khác.',
            ]);
        }

        return $validated;
    }
}
