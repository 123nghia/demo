<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectDetailPage;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HomeContentController extends Controller
{
    public function index()
    {
        $homeItems = ProjectDetailPage::query()
            ->with([
                'project' => function ($query) {
                    $query->select(['id', 'name', 'slug', 'is_published']);
                },
            ])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(24);

        $homeContent = SiteSetting::homeContent();

        $stats = [
            'total' => ProjectDetailPage::query()->count(),
            'published' => ProjectDetailPage::query()->where('is_published', true)->count(),
            'with_thumbnail' => ProjectDetailPage::query()
                ->whereNotNull('thumbnail_image')
                ->where('thumbnail_image', '!=', '')
                ->count(),
            'eligible_for_home' => ProjectDetailPage::query()
                ->where('is_published', true)
                ->whereNotNull('thumbnail_image')
                ->where('thumbnail_image', '!=', '')
                ->whereHas('project', function ($query) {
                    $query->where('is_published', true);
                })
                ->count(),
        ];

        return view('admin.home-content.index', compact('homeItems', 'stats', 'homeContent'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'hero_background_image' => 'nullable|string|max:255',
            'hero_scroll_target' => 'nullable|string|max:120',
            'hero_background_image_file' => 'nullable|image|max:5120',

            'profile_background_image' => 'nullable|string|max:255',
            'profile_background_image_file' => 'nullable|image|max:5120',
            'profile_eyebrow' => 'nullable|string|max:120',
            'profile_title' => 'required|string|max:255',
            'profile_description_1' => 'required|string|max:5000',
            'profile_description_2' => 'nullable|string|max:5000',
            'profile_button_label' => 'nullable|string|max:120',
            'profile_button_url' => 'nullable|string|max:255',
            'profile_slider_images' => 'nullable|array|max:100',
            'profile_slider_images.*' => 'nullable|string|max:255',
            'profile_slider_image_files' => 'nullable|array|max:100',
            'profile_slider_image_files.*' => 'nullable|image|max:5120',

            'about_title' => 'required|string|max:255',
            'about_description' => 'required|string|max:8000',
            'about_stat_1_value' => 'nullable|string|max:40',
            'about_stat_1_label' => 'nullable|string|max:140',
            'about_stat_2_value' => 'nullable|string|max:40',
            'about_stat_2_label' => 'nullable|string|max:140',
            'about_stat_3_value' => 'nullable|string|max:40',
            'about_stat_3_label' => 'nullable|string|max:140',
            'about_stat_4_value' => 'nullable|string|max:40',
            'about_stat_4_label' => 'nullable|string|max:140',
            'about_cta_label' => 'nullable|string|max:120',
            'about_cta_url' => 'nullable|string|max:255',
            'about_team_image' => 'nullable|string|max:255',
            'about_team_image_file' => 'nullable|image|max:5120',

            'footer_consult_title' => 'nullable|string|max:255',
            'footer_consult_button_label' => 'nullable|string|max:120',
            'footer_consult_button_url' => 'nullable|string|max:255',
            'footer_consult_background_image' => 'nullable|string|max:255',
            'footer_consult_background_image_file' => 'nullable|image|max:5120',

            'footer_partner_title' => 'nullable|string|max:255',
            'footer_partner_button_label' => 'nullable|string|max:120',
            'footer_partner_button_url' => 'nullable|string|max:255',
            'footer_partner_background_image' => 'nullable|string|max:255',
            'footer_partner_background_image_file' => 'nullable|image|max:5120',
        ]);

        $aboutStats = collect([
            [
                'value' => $this->cleanString($validated['about_stat_1_value'] ?? null),
                'label' => $this->cleanString($validated['about_stat_1_label'] ?? null),
            ],
            [
                'value' => $this->cleanString($validated['about_stat_2_value'] ?? null),
                'label' => $this->cleanString($validated['about_stat_2_label'] ?? null),
            ],
            [
                'value' => $this->cleanString($validated['about_stat_3_value'] ?? null),
                'label' => $this->cleanString($validated['about_stat_3_label'] ?? null),
            ],
            [
                'value' => $this->cleanString($validated['about_stat_4_value'] ?? null),
                'label' => $this->cleanString($validated['about_stat_4_label'] ?? null),
            ],
        ])
            ->filter(function ($item) {
                return !is_null($item['value']) || !is_null($item['label']);
            })
            ->values()
            ->all();

        $homeContent = [
            'hero' => [
                'background_image' => $this->cleanString($validated['hero_background_image'] ?? null),
                'scroll_target' => $this->cleanString($validated['hero_scroll_target'] ?? null),
            ],
            'profile' => [
                'background_image' => $this->cleanString($validated['profile_background_image'] ?? null),
                'eyebrow' => $this->cleanString($validated['profile_eyebrow'] ?? null),
                'title' => $this->cleanString($validated['profile_title'] ?? null),
                'description_1' => $this->cleanString($validated['profile_description_1'] ?? null),
                'description_2' => $this->cleanString($validated['profile_description_2'] ?? null),
                'button_label' => $this->cleanString($validated['profile_button_label'] ?? null),
                'button_url' => $this->cleanString($validated['profile_button_url'] ?? null),
                'slider_images' => $this->resolveProfileSliderImages($request, $validated),
            ],
            'about' => [
                'title' => $this->cleanString($validated['about_title'] ?? null),
                'description' => $this->cleanString($validated['about_description'] ?? null),
                'stats' => $aboutStats,
                'cta_label' => $this->cleanString($validated['about_cta_label'] ?? null),
                'cta_url' => $this->cleanString($validated['about_cta_url'] ?? null),
                'team_image' => $this->cleanString($validated['about_team_image'] ?? null),
            ],
            'footer_cta' => [
                'consult' => [
                    'title' => $this->cleanString($validated['footer_consult_title'] ?? null),
                    'button_label' => $this->cleanString($validated['footer_consult_button_label'] ?? null),
                    'button_url' => $this->cleanString($validated['footer_consult_button_url'] ?? null),
                    'background_image' => $this->cleanString($validated['footer_consult_background_image'] ?? null),
                ],
                'partner' => [
                    'title' => $this->cleanString($validated['footer_partner_title'] ?? null),
                    'button_label' => $this->cleanString($validated['footer_partner_button_label'] ?? null),
                    'button_url' => $this->cleanString($validated['footer_partner_button_url'] ?? null),
                    'background_image' => $this->cleanString($validated['footer_partner_background_image'] ?? null),
                ],
            ],
        ];

        $this->replaceWithUploadedImage(
            $request,
            'hero_background_image_file',
            $homeContent['hero'],
            'background_image',
            'home-hero-bg'
        );

        $this->replaceWithUploadedImage(
            $request,
            'profile_background_image_file',
            $homeContent['profile'],
            'background_image',
            'home-profile-bg'
        );

        $this->replaceWithUploadedImage(
            $request,
            'about_team_image_file',
            $homeContent['about'],
            'team_image',
            'home-about-team'
        );

        $this->replaceWithUploadedImage(
            $request,
            'footer_consult_background_image_file',
            $homeContent['footer_cta']['consult'],
            'background_image',
            'home-footer-consult'
        );

        $this->replaceWithUploadedImage(
            $request,
            'footer_partner_background_image_file',
            $homeContent['footer_cta']['partner'],
            'background_image',
            'home-footer-partner'
        );

        SiteSetting::setHomeContent($homeContent);

        return redirect()
            ->route('admin.home-content.index')
            ->with('success', 'Đã cập nhật nội dung Trang chủ thành công.');
    }

    private function cleanString($value): ?string
    {
        if (!is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }

    private function parseMultilineValues(string $raw): array
    {
        $normalized = str_replace(["\r\n", "\r", ','], "\n", $raw);
        $lines = array_map('trim', explode("\n", $normalized));
        $lines = array_filter($lines, function ($line) {
            return $line !== '';
        });

        return array_values(array_unique($lines));
    }

    private function resolveProfileSliderImages(Request $request, array $validated): array
    {
        $pathRows = $validated['profile_slider_images'] ?? [];
        if (!is_array($pathRows)) {
            $pathRows = [];
        }

        $fileRows = $request->file('profile_slider_image_files', []);
        if (!is_array($fileRows)) {
            $fileRows = [];
        }

        $maxRows = max(count($pathRows), count($fileRows));
        $resolved = [];

        for ($index = 0; $index < $maxRows; $index++) {
            $typedPath = $this->cleanString($pathRows[$index] ?? null);

            $uploadedPath = null;
            $uploadedFile = $fileRows[$index] ?? null;
            if ($uploadedFile && $uploadedFile->isValid()) {
                $uploadedPath = $this->storeUploadedFile($uploadedFile, 'home-profile-slider');
            }

            $finalPath = $uploadedPath ?: $typedPath;
            if (!is_null($finalPath)) {
                $resolved[] = $finalPath;
            }
        }

        return array_values(array_unique($resolved));
    }

    private function replaceWithUploadedImage(
        Request $request,
        string $fileInput,
        array &$target,
        string $targetKey,
        string $filenamePrefix
    ): void {
        $uploadedPath = $this->storeUploadedImage($request, $fileInput, $filenamePrefix);
        if (!is_null($uploadedPath)) {
            $target[$targetKey] = $uploadedPath;
        }
    }

    private function storeUploadedImage(Request $request, string $fileInput, string $filenamePrefix): ?string
    {
        if (!$request->hasFile($fileInput)) {
            return null;
        }

        $file = $request->file($fileInput);
        return $this->storeUploadedFile($file, $filenamePrefix);
    }

    private function storeUploadedFile($file, string $filenamePrefix): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        $uploadDirectory = public_path('uploads/home');
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = $filenamePrefix . '-' . date('YmdHis') . '-' . Str::random(8) . ($extension ? '.' . $extension : '');

        $file->move($uploadDirectory, $filename);

        return '/uploads/home/' . $filename;
    }
}
