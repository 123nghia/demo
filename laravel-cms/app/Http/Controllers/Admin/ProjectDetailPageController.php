<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectDetailPage;
use App\Services\ImageProcessor;
use Illuminate\Http\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ProjectDetailPageController extends Controller
{
    public function create(Project $project)
    {
        return view('admin.projects.detail-pages.create', compact('project'));
    }

    public function store(Request $request, Project $project)
    {
        $data = $this->validatedData($request);
        $project->detailPages()->create($data);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Đã thêm trang chi tiết cho dự án.');
    }

    public function edit(Project $project, ProjectDetailPage $detailPage)
    {
        $this->ensureOwnership($project, $detailPage);

        return view('admin.projects.detail-pages.edit', compact('project', 'detailPage'));
    }

    public function update(Request $request, Project $project, ProjectDetailPage $detailPage)
    {
        $this->ensureOwnership($project, $detailPage);

        $data = $this->validatedData($request, $detailPage);
        $detailPage->update($data);

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Đã cập nhật trang chi tiết.');
    }

    public function destroy(Project $project, ProjectDetailPage $detailPage)
    {
        $this->ensureOwnership($project, $detailPage);
        $detailPage->delete();

        return redirect()
            ->route('admin.projects.edit', $project)
            ->with('success', 'Đã xoá trang chi tiết.');
    }

    private function validatedData(Request $request, ProjectDetailPage $detailPage = null): array
    {
        $detailId = $detailPage ? $detailPage->id : 'NULL';

        $validated = $request->validate([
            'title' => 'required|string|max:190',
            'slug' => 'required|string|max:190|regex:/^[a-z0-9\-]+$/|unique:project_detail_pages,slug,' . $detailId,
            'summary' => 'nullable|string|max:1500',
            'content' => 'nullable|string|max:30000',
            'thumbnail_image' => 'nullable|string|max:255',
            'thumbnail_image_file' => 'nullable|image|max:5120',
            'thumbnail_click_action' => 'nullable|in:link,lightbox',
            'gallery_images_input' => 'nullable|string|max:30000',
            'gallery_image_files' => 'nullable|array|max:80',
            'gallery_image_files.*' => 'nullable|image|max:5120',
            'sort_order' => 'nullable|integer|min:0|max:9999',
            'is_published' => 'nullable|boolean',
        ]);

        $galleryImages = $this->parseGalleryImages((string) ($validated['gallery_images_input'] ?? ''));
        $uploadedGalleryImages = $this->storeUploadedImages(
            $request,
            'gallery_image_files',
            'project-detail-gallery'
        );

        $validated['gallery_images'] = $this->mergeGalleryImages($galleryImages, $uploadedGalleryImages);
        unset($validated['gallery_images_input'], $validated['gallery_image_files']);

        $validated['thumbnail_click_action'] = ($validated['thumbnail_click_action'] ?? 'link') === 'lightbox'
            ? 'lightbox'
            : 'link';

        $validated['sort_order'] = $validated['sort_order'] ?? 0;
        $validated['is_published'] = $request->boolean('is_published');

        $validated['thumbnail_image'] = trim((string) ($validated['thumbnail_image'] ?? ''));
        $validated['thumbnail_image'] = $validated['thumbnail_image'] === '' ? null : $validated['thumbnail_image'];

        $hasUploadedThumbnail = $request->hasFile('thumbnail_image_file');
        $uploadedThumbnail = $this->storeUploadedImage(
            $request,
            'thumbnail_image_file',
            'project-detail-thumbnail'
        );

        if (!is_null($uploadedThumbnail)) {
            $validated['thumbnail_image'] = $uploadedThumbnail;
        } elseif (is_null($validated['thumbnail_image']) && !$hasUploadedThumbnail && $detailPage) {
            $validated['thumbnail_image'] = $detailPage->thumbnail_image;
        }

        unset($validated['thumbnail_image_file']);

        $projectSlugExists = Project::query()->where('slug', $validated['slug'])->exists();
        if ($projectSlugExists) {
            throw ValidationException::withMessages([
                'slug' => 'Slug này đang được dùng bởi một dự án. Vui lòng chọn slug khác.',
            ]);
        }

        return $validated;
    }

    private function parseGalleryImages(string $raw): array
    {
        $normalized = str_replace(["\r\n", "\r", ','], "\n", $raw);
        $lines = array_map('trim', explode("\n", $normalized));
        $lines = array_filter($lines, function ($line) {
            return $line !== '';
        });

        return array_values(array_unique($lines));
    }

    private function mergeGalleryImages(array $typedImages, array $uploadedImages): array
    {
        return array_values(array_unique(array_filter(
            array_merge($typedImages, $uploadedImages),
            function ($path) {
                return is_string($path) && trim($path) !== '';
            }
        )));
    }

    private function ensureOwnership(Project $project, ProjectDetailPage $detailPage): void
    {
        abort_unless((int) $detailPage->project_id === (int) $project->id, 404);
    }

    private function storeUploadedImage(Request $request, string $fileInput, string $filenamePrefix): ?string
    {
        if (!$request->hasFile($fileInput)) {
            return null;
        }

        $file = $request->file($fileInput);
        if (!$file instanceof UploadedFile || !$file->isValid()) {
            return null;
        }

        return $this->storeUploadedFile($file, $filenamePrefix);
    }

    private function storeUploadedImages(Request $request, string $fileInput, string $filenamePrefix): array
    {
        if (!$request->hasFile($fileInput)) {
            return [];
        }

        $files = $request->file($fileInput, []);
        if ($files instanceof UploadedFile) {
            $files = [$files];
        }

        return collect($files)
            ->filter(function ($file) {
                return $file instanceof UploadedFile && $file->isValid();
            })
            ->map(function (UploadedFile $file) use ($filenamePrefix) {
                return $this->storeUploadedFile($file, $filenamePrefix);
            })
            ->filter()
            ->values()
            ->all();
    }

    private function storeUploadedFile(UploadedFile $file, string $filenamePrefix): ?string
    {
        $uploadDirectory = public_path('uploads/projects');
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = $filenamePrefix . '-' . date('YmdHis') . '-' . Str::random(8) . ($extension ? '.' . $extension : '');
        $destinationPath = $uploadDirectory . '/' . $filename;

        // Process image to fix EXIF orientation
        ImageProcessor::processAndSave($file, $destinationPath);

        return '/uploads/projects/' . $filename;
    }
}
