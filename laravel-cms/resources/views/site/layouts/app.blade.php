<!DOCTYPE html>
<html lang="vi" class="no-js">

@php
    $siteSettings = $siteSettings ?? [];

    $pageTitle = trim($__env->yieldContent('title'));
    $pageTitle = $pageTitle !== '' ? $pageTitle : ($page->seo_title ?? ($siteSettings['seo_default_title'] ?? config('app.name')));

    $metaDescription = trim($__env->yieldContent('meta_description'));
    $metaDescription = $metaDescription !== ''
        ? $metaDescription
        : ($page->seo_description ?? ($siteSettings['seo_default_description'] ?? ''));

    $metaKeywords = trim($__env->yieldContent('meta_keywords'));
    $metaKeywords = $metaKeywords !== '' ? $metaKeywords : ($siteSettings['seo_keywords'] ?? '');

    $robotsContent = trim($__env->yieldContent('meta_robots'));
    $robotsContent = $robotsContent !== '' ? $robotsContent : ($siteSettings['seo_robots'] ?? 'index, follow');

    $canonicalBase = rtrim((string) ($siteSettings['seo_canonical_base'] ?? ''), '/');
    $requestPath = trim((string) request()->path(), '/');
    if ($requestPath === 'home') {
        $requestPath = '';
    }

    $canonicalUrl = $canonicalBase !== ''
        ? $canonicalBase . ($requestPath !== '' ? '/' . $requestPath : '')
        : url()->current();

    $resolvedPageKeyForMeta = trim((string) ($page->page_key ?? ''));
    $isDetailContentPage = in_array($resolvedPageKeyForMeta, ['blog', 'video', 'project'], true) && str_contains($requestPath, '/');
    $ogType = $isDetailContentPage ? 'article' : 'website';

    $toAbsoluteUrl = function ($value) {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        return url('/' . ltrim($value, '/'));
    };

    $faviconUrl = $toAbsoluteUrl($siteSettings['favicon'] ?? '/theme/logohome.png');
    $appleTouchIconUrl = $toAbsoluteUrl($siteSettings['apple_touch_icon'] ?? ($siteSettings['favicon'] ?? '/theme/logohome.png'));
    $ogImageUrl = $toAbsoluteUrl($siteSettings['seo_og_image'] ?? ($siteSettings['header_logo'] ?? null));
    $logoSchemaUrl = $toAbsoluteUrl($siteSettings['header_logo'] ?? null);
    $googleSiteVerification = trim((string) ($siteSettings['seo_google_site_verification'] ?? ''));

    $themeStylesVersion = is_file(public_path('theme/styles.css')) ? filemtime(public_path('theme/styles.css')) : null;
    $themeScriptVersion = is_file(public_path('theme/script.js')) ? filemtime(public_path('theme/script.js')) : null;
    $themeStylesheetUrl = asset('theme/styles.css') . ($themeStylesVersion ? '?v=' . $themeStylesVersion : '');
    $themeScriptUrl = asset('theme/script.js') . ($themeScriptVersion ? '?v=' . $themeScriptVersion : '');

    $homeHeroImage = null;
    $isHomeRequest = $requestPath === '';
    if ($isHomeRequest) {
        $heroFallbackImage = '/theme/assets/hero.jpg';
        $heroBackgroundImage = trim((string) data_get($homeContent ?? [], 'hero.background_image', ''));

        if ($heroBackgroundImage === '') {
            $heroBackgroundImage = $heroFallbackImage;
        }

        $homeHeroImage = $toAbsoluteUrl($heroBackgroundImage);
    }

    $websiteUrl = trim((string) ($siteSettings['footer_website'] ?? ''));
    $websiteUrl = $websiteUrl !== '' ? $websiteUrl : ($canonicalBase !== '' ? $canonicalBase : config('app.url'));

    $sameAs = array_values(array_filter([
        $siteSettings['social_facebook'] ?? null,
        $siteSettings['social_tiktok'] ?? null,
        $siteSettings['social_youtube'] ?? null,
    ]));

    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $siteSettings['site_name'] ?? config('app.name'),
        'url' => $websiteUrl,
        'logo' => $logoSchemaUrl,
        'sameAs' => $sameAs,
    ];

    if (!empty($siteSettings['footer_phone']) || !empty($siteSettings['footer_email'])) {
        $organizationSchema['contactPoint'] = [[
            '@type' => 'ContactPoint',
            'telephone' => $siteSettings['footer_phone'] ?? null,
            'email' => $siteSettings['footer_email'] ?? null,
            'contactType' => 'customer service',
            'availableLanguage' => ['vi'],
            'areaServed' => 'VN',
        ]];

        $organizationSchema['contactPoint'][0] = array_filter(
            $organizationSchema['contactPoint'][0],
            function ($value) {
                return !is_null($value) && $value !== '';
            }
        );
    }

    if (!empty($siteSettings['footer_address'])) {
        $organizationSchema['address'] = [
            '@type' => 'PostalAddress',
            'streetAddress' => $siteSettings['footer_address'],
            'addressCountry' => 'VN',
        ];
    }

    $organizationSchema = array_filter($organizationSchema, function ($value) {
        return !is_null($value) && $value !== '' && $value !== [];
    });

    $websiteSchema = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $siteSettings['site_name'] ?? config('app.name'),
        'url' => $websiteUrl,
        'inLanguage' => 'vi-VN',
    ], function ($value) {
        return !is_null($value) && $value !== '' && $value !== [];
    });

    $baseStructuredData = array_values(array_filter([
        $organizationSchema,
        $websiteSchema,
    ]));
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <script>
        (function() {
            var root = document.documentElement;
            if (!root) {
                return;
            }

            root.classList.remove('no-js');
            root.classList.add('js');
        })();
    </script>
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="author" content="{{ $siteSettings['site_name'] ?? 'HOVI Việt Nam' }}">
    <meta name="referrer" content="strict-origin-when-cross-origin">
    @if (!empty($metaKeywords))
        <meta name="keywords" content="{{ $metaKeywords }}">
    @endif
    @if (!empty($googleSiteVerification))
        <meta name="google-site-verification" content="{{ $googleSiteVerification }}">
    @endif
    <meta name="robots" content="{{ $robotsContent }}">
    <link rel="canonical" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="vi-VN" href="{{ $canonicalUrl }}">
    <link rel="alternate" hreflang="x-default" href="{{ $canonicalUrl }}">

    <meta property="og:locale" content="vi_VN">
    <meta property="og:type" content="{{ $ogType }}">
    <meta property="og:site_name" content="{{ $siteSettings['site_name'] ?? 'HOVI Việt Nam' }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonicalUrl }}">
    @if (!empty($ogImageUrl))
        <meta property="og:image" content="{{ $ogImageUrl }}">
        <meta property="og:image:alt" content="{{ $pageTitle }}">
    @endif
    @if (isset($page) && !empty($page->updated_at))
        <meta property="og:updated_time" content="{{ optional($page->updated_at)->toAtomString() }}">
    @endif

    <meta name="twitter:card" content="{{ !empty($ogImageUrl) ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $pageTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if (!empty($ogImageUrl))
        <meta name="twitter:image" content="{{ $ogImageUrl }}">
    @endif

    <meta name="theme-color" content="#050505">
    <link rel="dns-prefetch" href="//fonts.googleapis.com">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preload" as="style"
        href="https://fonts.googleapis.com/css2?family=Marcellus&family=Open+Sans:wght@400;600&display=swap">
    <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Open+Sans:wght@400;600&display=swap"
        rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Marcellus&family=Open+Sans:wght@400;600&display=swap"
            rel="stylesheet">
    </noscript>
    <link rel="icon" href="{{ $faviconUrl ?? '/theme/logohome.png' }}">
    <link rel="apple-touch-icon" href="{{ $appleTouchIconUrl ?? ($faviconUrl ?? '/theme/logohome.png') }}">
    @if (!empty($logoSchemaUrl))
        <link rel="preload" as="image" href="{{ $logoSchemaUrl }}" fetchpriority="high">
    @endif
    @if (!empty($homeHeroImage))
        <link rel="preload" as="image" href="{{ $homeHeroImage }}" fetchpriority="high">
    @endif
    <link rel="preload" as="style" href="{{ $themeStylesheetUrl }}">
    <link rel="stylesheet" href="{{ $themeStylesheetUrl }}">

    @if (!empty($baseStructuredData))
        <script type="application/ld+json">{!! json_encode(count($baseStructuredData) === 1 ? $baseStructuredData[0] : $baseStructuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
    @endif

    @stack('head')
    @stack('structured_data')
</head>

@php
    $resolvedPageKey = trim($__env->yieldContent('page_key'));
    if ($resolvedPageKey === '' && isset($page) && !empty($page->page_key)) {
        $resolvedPageKey = $page->page_key;
    }
@endphp

<body class="@yield('body_class')" data-page="{{ $resolvedPageKey }}">
    <div class="overlay" data-close-overlay hidden></div>

    @if (session('success'))
        <div id="flash-contact-success"
            style="position:fixed;top:14px;left:50%;transform:translateX(-50%);z-index:9999;background:#2e7d32;color:#fff;padding:10px 18px;border-radius:10px;font-size:14px;box-shadow:0 8px 20px rgba(0,0,0,.28);">
            {{ session('success') }}
        </div>
        <script>
            setTimeout(function() {
                var el = document.getElementById('flash-contact-success');
                if (el) {
                    el.remove();
                }
            }, 4500);
        </script>
    @endif

    @if ($errors->has('contact'))
        <div id="flash-contact-error"
            style="position:fixed;top:14px;left:50%;transform:translateX(-50%);z-index:9999;background:#b71c1c;color:#fff;padding:10px 18px;border-radius:10px;font-size:14px;box-shadow:0 8px 20px rgba(0,0,0,.28);">
            {{ $errors->first('contact') }}
        </div>
        <script>
            setTimeout(function() {
                var el = document.getElementById('flash-contact-error');
                if (el) {
                    el.remove();
                }
            }, 6000);
        </script>
    @endif

    @include('site.partials.header', ['pageKey' => $resolvedPageKey])

    @yield('before_main')

    @yield('content')

    @if (trim($__env->yieldContent('inline_footer')) !== '1')
        @include('site.partials.footer')
    @endif

    @include('site.partials.floating-contact')

    <script>
        (function() {
            if (!document.body || !document.body.classList.contains('project-detail-page')) {
                return;
            }

            window.setTimeout(function() {
                document.body.classList.add('detail-layout-ready');
            }, 2500);
        })();
    </script>
    <script src="{{ $themeScriptUrl }}" defer></script>
    @stack('scripts')
</body>

</html>
