<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AboutUsContentController extends Controller
{
    public function edit()
    {
        return view('admin.about-content.edit', [
            'aboutContent' => SiteSetting::aboutContent(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_enabled' => 'nullable|boolean',
            'hero_eyebrow' => 'nullable|string|max:120',
            'hero_title' => 'required|string|max:255',
            'hero_description' => 'required|string|max:3000',
            'hero_image' => 'nullable|string|max:255',
            'hero_image_alt' => 'nullable|string|max:255',
            'hero_image_file' => 'nullable|image|max:5120',

            'mission_enabled' => 'nullable|boolean',
            'mission_title' => 'required|string|max:120',
            'mission_description' => 'required|string|max:2000',
            'mission_image' => 'nullable|string|max:255',
            'mission_image_alt' => 'nullable|string|max:255',
            'mission_image_file' => 'nullable|image|max:5120',

            'vision_enabled' => 'nullable|boolean',
            'vision_title' => 'required|string|max:120',
            'vision_description' => 'required|string|max:2000',
            'vision_image' => 'nullable|string|max:255',
            'vision_image_alt' => 'nullable|string|max:255',
            'vision_image_file' => 'nullable|image|max:5120',

            'inspiration_enabled' => 'nullable|boolean',
            'inspiration_title' => 'required|string|max:120',
            'inspiration_subtitle' => 'nullable|string|max:160',
            'inspiration_description' => 'required|string|max:2500',
            'inspiration_image' => 'nullable|string|max:255',
            'inspiration_image_alt' => 'nullable|string|max:255',
            'inspiration_image_file' => 'nullable|image|max:5120',

            'definition_enabled' => 'nullable|boolean',
            'definition_title' => 'required|string|max:255',
            'definition_description' => 'required|string|max:2500',

            'core_enabled' => 'nullable|boolean',
            'core_heading' => 'required|string|max:160',
            'core_items' => 'required|array|min:1',
            'core_items.*.title' => 'nullable|string|max:120',
            'core_items.*.description' => 'nullable|string|max:1500',
            'core_items.*.image' => 'nullable|string|max:255',
            'core_items.*.image_alt' => 'nullable|string|max:255',
            'core_items.*.image_file' => 'nullable|image|max:5120',

            'manifesto_enabled' => 'nullable|boolean',
            'manifesto_heading' => 'required|string|max:160',
            'manifesto_items' => 'required|array|min:1',
            'manifesto_items.*.quote' => 'nullable|string|max:1500',
            'manifesto_items.*.image' => 'nullable|string|max:255',
            'manifesto_items.*.image_alt' => 'nullable|string|max:255',
            'manifesto_items.*.image_file' => 'nullable|image|max:5120',

            'advantages_enabled' => 'nullable|boolean',
            'advantages_title' => 'required|string|max:160',
            'advantages_image' => 'nullable|string|max:255',
            'advantages_image_alt' => 'nullable|string|max:255',
            'advantages_image_file' => 'nullable|image|max:5120',
            'advantages_items' => 'required|array|min:1',
            'advantages_items.*.title' => 'nullable|string|max:140',
            'advantages_items.*.description' => 'nullable|string|max:1000',

            'ceo_enabled' => 'nullable|boolean',
            'ceo_eyebrow' => 'nullable|string|max:160',
            'ceo_title' => 'required|string|max:160',
            'ceo_description_1' => 'required|string|max:2500',
            'ceo_description_2' => 'required|string|max:2500',
            'ceo_image' => 'nullable|string|max:255',
            'ceo_image_alt' => 'nullable|string|max:255',
            'ceo_image_file' => 'nullable|image|max:5120',

            'capacity_enabled' => 'nullable|boolean',
            'capacity_heading' => 'required|string|max:160',
            'capacity_lead' => 'required|string|max:2500',
            'capacity_stats' => 'required|array|min:1',
            'capacity_stats.*.value' => 'nullable|string|max:120',
            'capacity_stats.*.label' => 'nullable|string|max:255',
            'capacity_action_1_label' => 'nullable|string|max:120',
            'capacity_action_1_url' => 'nullable|string|max:255',
            'capacity_action_2_label' => 'nullable|string|max:120',
            'capacity_action_2_url' => 'nullable|string|max:255',
        ]);

        $aboutContent = [
            'hero' => [
                'enabled' => $request->boolean('hero_enabled'),
                'eyebrow' => $this->cleanString($validated['hero_eyebrow'] ?? null),
                'title' => $this->cleanString($validated['hero_title'] ?? null),
                'description' => $this->cleanString($validated['hero_description'] ?? null),
                'image' => $this->cleanString($validated['hero_image'] ?? null),
                'image_alt' => $this->cleanString($validated['hero_image_alt'] ?? null),
            ],
            'mission' => [
                'enabled' => $request->boolean('mission_enabled'),
                'title' => $this->cleanString($validated['mission_title'] ?? null),
                'description' => $this->cleanString($validated['mission_description'] ?? null),
                'image' => $this->cleanString($validated['mission_image'] ?? null),
                'image_alt' => $this->cleanString($validated['mission_image_alt'] ?? null),
            ],
            'vision' => [
                'enabled' => $request->boolean('vision_enabled'),
                'title' => $this->cleanString($validated['vision_title'] ?? null),
                'description' => $this->cleanString($validated['vision_description'] ?? null),
                'image' => $this->cleanString($validated['vision_image'] ?? null),
                'image_alt' => $this->cleanString($validated['vision_image_alt'] ?? null),
            ],
            'inspiration' => [
                'enabled' => $request->boolean('inspiration_enabled'),
                'title' => $this->cleanString($validated['inspiration_title'] ?? null),
                'subtitle' => $this->cleanString($validated['inspiration_subtitle'] ?? null),
                'description' => $this->cleanString($validated['inspiration_description'] ?? null),
                'image' => $this->cleanString($validated['inspiration_image'] ?? null),
                'image_alt' => $this->cleanString($validated['inspiration_image_alt'] ?? null),
            ],
            'definition' => [
                'enabled' => $request->boolean('definition_enabled'),
                'title' => $this->cleanString($validated['definition_title'] ?? null),
                'description' => $this->cleanString($validated['definition_description'] ?? null),
            ],
            'core' => [
                'enabled' => $request->boolean('core_enabled'),
                'heading' => $this->cleanString($validated['core_heading'] ?? null),
                'items' => $this->normalizeItems(
                    $validated['core_items'] ?? [],
                    ['title', 'description', 'image', 'image_alt'],
                    $request,
                    'core_items',
                    'image',
                    'core-item'
                ),
            ],
            'manifesto' => [
                'enabled' => $request->boolean('manifesto_enabled'),
                'heading' => $this->cleanString($validated['manifesto_heading'] ?? null),
                'items' => $this->normalizeItems(
                    $validated['manifesto_items'] ?? [],
                    ['quote', 'image', 'image_alt'],
                    $request,
                    'manifesto_items',
                    'image',
                    'manifesto-item'
                ),
            ],
            'advantages' => [
                'enabled' => $request->boolean('advantages_enabled'),
                'title' => $this->cleanString($validated['advantages_title'] ?? null),
                'image' => $this->cleanString($validated['advantages_image'] ?? null),
                'image_alt' => $this->cleanString($validated['advantages_image_alt'] ?? null),
                'items' => $this->normalizeItems($validated['advantages_items'] ?? [], ['title', 'description']),
            ],
            'ceo' => [
                'enabled' => $request->boolean('ceo_enabled'),
                'eyebrow' => $this->cleanString($validated['ceo_eyebrow'] ?? null),
                'title' => $this->cleanString($validated['ceo_title'] ?? null),
                'description_1' => $this->cleanString($validated['ceo_description_1'] ?? null),
                'description_2' => $this->cleanString($validated['ceo_description_2'] ?? null),
                'image' => $this->cleanString($validated['ceo_image'] ?? null),
                'image_alt' => $this->cleanString($validated['ceo_image_alt'] ?? null),
            ],
            'capacity' => [
                'enabled' => $request->boolean('capacity_enabled'),
                'heading' => $this->cleanString($validated['capacity_heading'] ?? null),
                'lead' => $this->cleanString($validated['capacity_lead'] ?? null),
                'stats' => $this->normalizeItems($validated['capacity_stats'] ?? [], ['value', 'label']),
                'action_1_label' => $this->cleanString($validated['capacity_action_1_label'] ?? null),
                'action_1_url' => $this->cleanString($validated['capacity_action_1_url'] ?? null),
                'action_2_label' => $this->cleanString($validated['capacity_action_2_label'] ?? null),
                'action_2_url' => $this->cleanString($validated['capacity_action_2_url'] ?? null),
            ],
        ];

        $this->replaceWithUploadedImage($request, 'hero_image_file', $aboutContent['hero'], 'image', 'hero-image');
        $this->replaceWithUploadedImage($request, 'mission_image_file', $aboutContent['mission'], 'image', 'mission-image');
        $this->replaceWithUploadedImage($request, 'vision_image_file', $aboutContent['vision'], 'image', 'vision-image');
        $this->replaceWithUploadedImage(
            $request,
            'inspiration_image_file',
            $aboutContent['inspiration'],
            'image',
            'inspiration-image'
        );
        $this->replaceWithUploadedImage(
            $request,
            'advantages_image_file',
            $aboutContent['advantages'],
            'image',
            'advantages-image'
        );
        $this->replaceWithUploadedImage($request, 'ceo_image_file', $aboutContent['ceo'], 'image', 'ceo-image');

        SiteSetting::setAboutContent($aboutContent);

        return redirect()
            ->route('admin.about-content.edit')
            ->with('success', 'Đã cập nhật nội dung trang About Us theo từng section.');
    }

    private function cleanString($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $trimmed = trim($value);
        return $trimmed === '' ? null : $trimmed;
    }

    private function normalizeItems(
        array $items,
        array $keys,
        ?Request $request = null,
        ?string $fileInputPrefix = null,
        string $uploadTargetKey = 'image',
        string $uploadFilePrefix = 'about-item'
    ): array
    {
        $normalized = [];

        foreach ($items as $index => $item) {
            if (!is_array($item)) {
                continue;
            }

            $row = [];
            foreach ($keys as $key) {
                $row[$key] = $this->cleanString($item[$key] ?? null);
            }

            if ($request instanceof Request && !empty($fileInputPrefix)) {
                $uploadedPath = $this->storeUploadedImage(
                    $request,
                    $fileInputPrefix . '.' . $index . '.image_file',
                    $uploadFilePrefix . '-' . $index
                );

                if (!is_null($uploadedPath)) {
                    $row[$uploadTargetKey] = $uploadedPath;
                }
            }

            $hasMeaningfulValue = collect($row)->contains(function ($value) {
                return !is_null($value);
            });

            if ($hasMeaningfulValue) {
                $normalized[] = $row;
            }
        }

        return $normalized;
    }

    private function replaceWithUploadedImage(
        Request $request,
        string $fileInput,
        array &$target,
        string $targetImageKey,
        string $filenamePrefix
    ): void {
        $uploadedPath = $this->storeUploadedImage($request, $fileInput, $filenamePrefix);
        if (!is_null($uploadedPath)) {
            $target[$targetImageKey] = $uploadedPath;
        }
    }

    private function storeUploadedImage(Request $request, string $fileInput, string $filenamePrefix): ?string
    {
        if (!$request->hasFile($fileInput)) {
            return null;
        }

        $file = $request->file($fileInput);
        if (!$file || !$file->isValid()) {
            return null;
        }

        $uploadDirectory = public_path('uploads/about-us');
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = $filenamePrefix . '-' . date('YmdHis') . '-' . Str::random(8) . ($extension ? '.' . $extension : '');

        $file->move($uploadDirectory, $filename);

        return '/uploads/about-us/' . $filename;
    }
}
