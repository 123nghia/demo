<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SiteController extends Controller
{
    public function show($slug = null)
    {
        $resolvedSlug = trim((string) $slug, '/');
        $resolvedSlug = $resolvedSlug === '' ? 'home' : $resolvedSlug;

        if (Str::startsWith($resolvedSlug, 'admin')) {
            abort(404);
        }

        $page = Page::query()
            ->published()
            ->where('slug', $resolvedSlug)
            ->firstOrFail();

        $legacyPath = resource_path('legacy/' . $page->legacy_file);
        abort_unless(File::exists($legacyPath), 404, 'Trang chưa có giao diện được cấu hình.');

        $html = File::get($legacyPath);
        $html = $this->transformHtml($html, $page);

        return response($html)->header('Content-Type', 'text/html; charset=UTF-8');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:30',
            'email' => 'nullable|email|max:150',
            'service' => 'nullable|string|max:255',
            'message' => 'required|string|max:4000',
            'source_page' => 'nullable|string|max:120',
        ]);

        if (empty($validated['source_page'])) {
            $previousPath = trim((string) parse_url(url()->previous(), PHP_URL_PATH), '/');
            $validated['source_page'] = $previousPath === '' ? 'home' : $previousPath;
        }

        ContactMessage::query()->create($validated);

        return back()->with('success', 'HOVI đã nhận thông tin. Chúng tôi sẽ liên hệ bạn sớm nhất.');
    }

    private function transformHtml(string $html, Page $page): string
    {
        $replacements = [
            'href="../styles.css"' => 'href="/theme/styles.css"',
            'href="styles.css"' => 'href="/theme/styles.css"',
            'src="../script.js"' => 'src="/theme/script.js"',
            'src="script.js"' => 'src="/theme/script.js"',
            'src="../shared-layout.js"' => 'src="/theme/shared-layout.js"',
            'src="shared-layout.js"' => 'src="/theme/shared-layout.js"',
            'href="../logohome.png"' => 'href="/theme/logohome.png"',
            'href="logohome.png"' => 'href="/theme/logohome.png"',
            'src="../assets/' => 'src="/theme/assets/',
            'src="assets/' => 'src="/theme/assets/',
            'href="../assets/' => 'href="/theme/assets/',
            'href="assets/' => 'href="/theme/assets/',
            'url("assets/' => 'url("/theme/assets/',
            'action="#" method="post"' => 'action="/contact-submit" method="post"',
        ];

        $html = str_replace(array_keys($replacements), array_values($replacements), $html);

        $html = preg_replace(
            '#(href|src)="(?:\./|\.\./)?(about-us|lien-he|thiet-ke-biet-thu-vinhomes-ocean-park|biet-thu-don-lap-m07-l14-dtm-duong-noi)/?"#i',
            '$1="/$2"',
            $html
        );

        $html = preg_replace('#(href|src)="/?([^"?]+)/index\.html"#i', '$1="/$2"', $html);
        $html = preg_replace('#data-hover-redirect="([^"?]+)/index\.html"#i', 'data-hover-redirect="/$1"', $html);

        $html = preg_replace(
            '#<form([^>]*class="[^"]*contact-form[^"]*"[^>]*)>#i',
            '<form$1><input type="hidden" name="source_page" value="' . e($page->slug) . '">',
            $html
        );

        if (!empty($page->seo_title)) {
            $html = preg_replace('#<title>.*?</title>#is', '<title>' . e($page->seo_title) . '</title>', $html, 1);
        }

        if (!empty($page->seo_description)) {
            $html = preg_replace(
                '#<meta\s+name="description"\s+content="[^"]*"\s*/?>#i',
                '<meta name="description" content="' . e($page->seo_description) . '">',
                $html,
                1
            );
        }

        $flashBanner = $this->renderFlashBanner();
        if ($flashBanner !== '') {
            $html = preg_replace('#<body([^>]*)>#i', '<body$1>' . $flashBanner, $html, 1);
        }

        return $html;
    }

    private function renderFlashBanner(): string
    {
        $message = session('success');
        if (empty($message)) {
            return '';
        }

        $safeMessage = e($message);

        return '<div id="flash-contact-success" style="position:fixed;top:14px;left:50%;transform:translateX(-50%);z-index:9999;background:#2e7d32;color:#fff;padding:10px 18px;border-radius:10px;font-size:14px;box-shadow:0 8px 20px rgba(0,0,0,.28);">'
            . $safeMessage
            . '</div><script>setTimeout(function(){var el=document.getElementById("flash-contact-success");if(el){el.remove();}},4500);</script>';
    }
}
