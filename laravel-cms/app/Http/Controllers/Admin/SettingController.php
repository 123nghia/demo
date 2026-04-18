<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\ImageProcessor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    public function edit()
    {
        return view('admin.settings.edit', [
            'settings' => SiteSetting::allAsArray(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:150',
            'site_tagline' => 'nullable|string|max:255',

            'header_logo' => 'nullable|string|max:255',
            'footer_logo' => 'nullable|string|max:255',
            'favicon' => 'nullable|string|max:255',
            'apple_touch_icon' => 'nullable|string|max:255',
            'seo_og_image' => 'nullable|string|max:255',

            'seo_default_title' => 'required|string|max:255',
            'seo_default_description' => 'required|string|max:1000',
            'seo_keywords' => 'nullable|string|max:1000',
            'seo_robots' => 'required|string|max:255',
            'seo_canonical_base' => 'nullable|string|max:255',
            'seo_google_site_verification' => 'nullable|string|max:255',

            'footer_company_name' => 'required|string|max:255',
            'footer_tax_code' => 'nullable|string|max:120',
            'footer_address' => 'required|string|max:255',
            'footer_website' => 'nullable|string|max:255',
            'footer_email' => 'nullable|string|max:150',
            'footer_phone' => 'nullable|string|max:40',
            'footer_copyright' => 'nullable|string|max:255',

            'social_facebook' => 'nullable|string|max:255',
            'social_tiktok' => 'nullable|string|max:255',
            'social_youtube' => 'nullable|string|max:255',
            'social_messenger' => 'nullable|string|max:255',
            'social_zalo' => 'nullable|string|max:255',

            'gtm_id' => 'nullable|string|max:255',
            'facebook_pixel_id' => 'nullable|string|max:255',
            'analytics_id' => 'nullable|string|max:255',

            'header_logo_file' => 'nullable|image|max:3072',
            'footer_logo_file' => 'nullable|image|max:3072',
            'favicon_file' => 'nullable|image|max:2048',
            'apple_touch_icon_file' => 'nullable|image|max:3072',
            'seo_og_image_file' => 'nullable|image|max:4096',
        ]);

        $existingSettings = SiteSetting::allAsArray();

        $settings = collect($validated)
            ->except(['header_logo_file', 'footer_logo_file', 'favicon_file', 'apple_touch_icon_file', 'seo_og_image_file'])
            ->map(function ($value) {
                if (!is_string($value)) {
                    return $value;
                }

                $trimmed = trim($value);
                return $trimmed === '' ? null : $trimmed;
            })
            ->toArray();

        $this->replaceWithUploadedFile($request, 'header_logo_file', 'header_logo', $settings);
        $this->replaceWithUploadedFile($request, 'footer_logo_file', 'footer_logo', $settings);
        $this->replaceWithUploadedFile($request, 'favicon_file', 'favicon', $settings);
        $this->replaceWithUploadedFile($request, 'apple_touch_icon_file', 'apple_touch_icon', $settings);
        $this->replaceWithUploadedFile($request, 'seo_og_image_file', 'seo_og_image', $settings);

        foreach ([
            ['file' => 'header_logo_file', 'key' => 'header_logo'],
            ['file' => 'footer_logo_file', 'key' => 'footer_logo'],
            ['file' => 'favicon_file', 'key' => 'favicon'],
            ['file' => 'apple_touch_icon_file', 'key' => 'apple_touch_icon'],
            ['file' => 'seo_og_image_file', 'key' => 'seo_og_image'],
        ] as $imageField) {
            $settingKey = $imageField['key'];

            if ($request->hasFile($imageField['file'])) {
                continue;
            }

            if (array_key_exists($settingKey, $settings) && !is_null($settings[$settingKey])) {
                continue;
            }

            $settings[$settingKey] = $existingSettings[$settingKey] ?? null;
        }

        SiteSetting::setMany($settings);

        return redirect()
            ->route('admin.settings.edit')
            ->with('success', 'Đã cập nhật cấu hình SEO, footer, logo, mạng xã hội và tracking.');
    }

    private function replaceWithUploadedFile(Request $request, string $fileInput, string $settingKey, array &$settings): void
    {
        if (!$request->hasFile($fileInput)) {
            return;
        }

        $file = $request->file($fileInput);
        $uploadDirectory = public_path('uploads/settings');

        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0755, true);
        }

        $extension = strtolower((string) $file->getClientOriginalExtension());
        $filename = $settingKey . '-' . date('YmdHis') . '-' . Str::random(8) . ($extension ? '.' . $extension : '');
        $destinationPath = $uploadDirectory . '/' . $filename;

        ImageProcessor::processAndSave($file, $destinationPath);

        $settings[$settingKey] = '/uploads/settings/' . $filename;
    }
}
