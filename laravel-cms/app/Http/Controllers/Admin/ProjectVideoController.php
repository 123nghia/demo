<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectVideo;
use Illuminate\Http\Request;

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
        $request->validate([
            'title' => 'required|string|max:190',
            'video_url' => 'nullable|string|max:500',
            'thumbnail_image' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2000',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_published' => 'nullable|boolean',
        ]);

        return [
            'title' => trim((string) $request->input('title')),
            'video_url' => $this->nullIfEmpty($request->input('video_url')),
            'thumbnail_image' => $this->nullIfEmpty($request->input('thumbnail_image')),
            'description' => $this->nullIfEmpty($request->input('description')),
            'sort_order' => (int) ($request->input('sort_order') ?? 0),
            'is_published' => $request->boolean('is_published'),
        ];
    }

    private function nullIfEmpty($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }

    private function ensureOwnership(Project $project, ProjectVideo $video): void
    {
        abort_unless((int) $video->project_id === (int) $project->id, 404);
    }
}
