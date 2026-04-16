@php
    $siteSettings = $siteSettings ?? [];
    $pageKey = $pageKey ?? '';
    $isHomePage = $pageKey === 'home';

    $logoPath = trim((string) ($siteSettings['header_logo'] ?? '/theme/logoMenuRight1.png'));
    $logoAlt = trim((string) ($siteSettings['site_name'] ?? 'HOVI Việt Nam'));
    $mobileMenuMeta = trim(
        (string) ($siteSettings['site_tagline'] ??
            'Thiết kế và thi công cảnh quan, sân vườn cao cấp cho biệt thự và penthouse.')
    );

    $normalizeMenuHref = function ($rawUrl, $menuPageKey = null) use ($isHomePage) {
        $url = trim((string) $rawUrl);
        if ($url === '') {
            $url = '/';
        }

        if ($menuPageKey === 'home' && $url === '/') {
            return $isHomePage ? '#hero' : url('/') . '#hero';
        }

        if (\Illuminate\Support\Str::startsWith($url, ['http://', 'https://', 'mailto:', 'tel:'])) {
            return $url;
        }

        if (\Illuminate\Support\Str::startsWith($url, '#')) {
            return $isHomePage ? $url : url('/') . $url;
        }

        return url('/' . ltrim($url, '/'));
    };

    $menuItems = collect($siteMenuItems ?? []);
    if ($menuItems->isEmpty()) {
        $menuItems = collect([
            ['label' => 'Trang chủ', 'url' => '/', 'page_key' => 'home', 'is_home_icon' => true],
            [
                'label' => 'Vinhomes Ocean Park',
                'url' => '/thiet-ke-biet-thu-vinhomes-ocean-park',
                'page_key' => 'oceanpark',
            ],
            ['label' => 'Blog', 'url' => '/blog', 'page_key' => 'blog'],
            ['label' => 'Video', 'url' => '/video', 'page_key' => 'video'],
            ['label' => 'Giới thiệu', 'url' => '/about-us', 'page_key' => 'about'],
            ['label' => 'Liên hệ', 'url' => '/lien-he', 'page_key' => 'contact'],
        ]);
    }

    $resolvedMenuLinks =
        $menuItems
            ->map(function ($item) use ($normalizeMenuHref) {
                $resolvedPageKey = trim((string) data_get($item, 'page_key', data_get($item, 'key', '')));
                $resolvedUrl = data_get($item, 'url', data_get($item, 'href', '/'));
                $resolvedLabel = trim((string) data_get($item, 'label', data_get($item, 'title', '')));

                return [
                    'page_key' => $resolvedPageKey,
                    'label' => $resolvedLabel,
                    'href' => $normalizeMenuHref($resolvedUrl, $resolvedPageKey),
                    'open_in_new_tab' => (bool) data_get($item, 'open_in_new_tab', false),
                    'is_home_icon' => (bool) data_get($item, 'is_home_icon', data_get($item, 'homeIcon', false)),
                ];
            })
            ->filter(function ($item) {
                return !empty($item['label']);
            })
            ->values();

    $homeMenu = $resolvedMenuLinks->firstWhere('page_key', 'home');
    $homeHref = $homeMenu['href'] ?? ($isHomePage ? '#hero' : url('/') . '#hero');
@endphp

<aside class="mobile-menu" id="mobile-menu" aria-hidden="true">
    <div class="mobile-menu__header">
        <img src="{{ $logoPath }}" alt="{{ $logoAlt }}" class="mobile-menu__logo" loading="eager"
            decoding="async">
        <button class="mobile-menu__close" type="button" data-close-menu aria-label="Đóng menu">×</button>
    </div>
    <nav class="mobile-menu__nav" aria-label="Điều hướng di động">
        @foreach ($resolvedMenuLinks as $link)
            <a href="{{ $link['href'] }}" class="mobile-menu__link" data-mobile-link
                data-page-key="{{ $link['page_key'] }}"
                @if ($pageKey === $link['page_key']) aria-current="page" @endif
                @if ($link['open_in_new_tab']) target="_blank" rel="noreferrer noopener" @endif>
                {{ $link['label'] }}
            </a>
        @endforeach
    </nav>
    <p class="mobile-menu__meta">{{ $mobileMenuMeta }}</p>
</aside>

<section class="search-modal" id="search-modal" aria-hidden="true">
    <div class="search-modal__card">
        <button class="search-modal__close" type="button" data-close-search aria-label="Đóng tìm kiếm">×</button>
        <p class="search-modal__eyebrow">Tìm kiếm</p>
        <h2>Tìm kiếm dự án bạn quan tâm</h2>
        <form class="search-modal__form" action="#" method="get">
            <input type="search" placeholder="Nhập tên dự án, khu đô thị hoặc từ khóa">
            <button type="submit">Tìm</button>
        </form>
    </div>
</section>

<header class="site-header">
    <div class="header-main">
        <div class="header-actions header-actions--left">
            <a class="icon-button" href="tel:+84988991635" aria-label="Gọi {{ $logoAlt }}">
                <svg viewBox="0 0 473.806 473.806" aria-hidden="true" class="phone-icon">
                    <path
                        d="M374.456,293.506c-9.7-10.1-21.4-15.5-33.8-15.5c-12.3,0-24.1,5.3-34.2,15.4l-31.6,31.5c-2.6-1.4-5.2-2.7-7.7-4c-3.6-1.8-7-3.5-9.9-5.3c-29.6-18.8-56.5-43.3-82.3-75c-12.5-15.8-20.9-29.1-27-42.6c8.2-7.5,15.8-15.3,23.2-22.8c2.8-2.8,5.6-5.7,8.4-8.5c21-21,21-48.2,0-69.2l-27.3-27.3c-3.1-3.1-6.3-6.3-9.3-9.5c-6-6.2-12.3-12.6-18.8-18.6c-9.7-9.6-21.3-14.7-33.5-14.7s-24,5.1-34,14.7c-0.1,0.1-0.1,0.1-0.2,0.2l-34,34.3c-12.8,12.8-20.1,28.4-21.7,46.5c-2.4,29.2,6.2,56.4,12.8,74.2c16.2,43.7,40.4,84.2,76.5,127.6c43.8,52.3,96.5,93.6,156.7,122.7c23,10.9,53.7,23.8,88,26c2.1,0.1,4.3,0.2,6.3,0.2c23.1,0,42.5-8.3,57.7-24.8c0.1-0.2,0.3-0.3,0.4-0.5c5.2-6.3,11.2-12,17.5-18.1c4.3-4.1,8.7-8.4,13-12.9c9.9-10.3,15.1-22.3,15.1-34.6c0-12.4-5.3-24.3-15.4-34.3L374.456,293.506z" />
                    <path
                        d="M256.056,112.706c26.2,4.4,50,16.8,69,35.8s31.3,42.8,35.8,69c1.1,6.6,6.8,11.2,13.3,11.2c0.8,0,1.5-0.1,2.3-0.2c7.4-1.2,12.3-8.2,11.1-15.6c-5.4-31.7-20.4-60.6-43.3-83.5s-51.8-37.9-83.5-43.3c-7.4-1.2-14.3,3.7-15.6,11S248.656,111.506,256.056,112.706z" />
                    <path
                        d="M473.256,209.006c-8.9-52.2-33.5-99.7-71.3-137.5s-85.3-62.4-137.5-71.3c-7.3-1.3-14.2,3.7-15.5,11c-1.2,7.4,3.7,14.3,11.1,15.6c46.6,7.9,89.1,30,122.9,63.7c33.8,33.8,55.8,76.3,63.7,122.9c1.1,6.6,6.8,11.2,13.3,11.2c0.8,0,1.5-0.1,2.3-0.2C469.556,223.306,474.556,216.306,473.256,209.006z" />
                </svg>
            </a>
            <button class="icon-button" type="button" data-open-search aria-label="Mở tìm kiếm">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path
                        d="M10.5 4a6.5 6.5 0 1 0 4.02 11.6l4.44 4.45 1.06-1.06-4.45-4.44A6.5 6.5 0 0 0 10.5 4Zm0 1.5a5 5 0 1 1 0 10 5 5 0 0 1 0-10Z"
                        fill="currentColor" />
                </svg>
            </button>
        </div>

        <a class="site-logo" href="{{ $homeHref }}" aria-label="{{ $logoAlt }}">
            <img src="{{ $logoPath }}" alt="{{ $logoAlt }}" loading="eager" fetchpriority="high"
                decoding="async">
        </a>

        <div class="header-actions header-actions--right">
            <button class="menu-trigger" type="button" data-open-menu aria-label="Mở menu">
                <svg class="menu-icon" width="50" height="30" viewBox="0 0 50 30" xmlns="http://www.w3.org/2000/svg"
                    aria-hidden="true">
                    <defs>
                        <linearGradient id="MenuGradientShared">
                            <stop offset="0%" stop-color="#D28BE3" />
                            <stop offset="20%" stop-color="#F3DBCB" />
                            <stop offset="40%" stop-color="#91B2E7" />
                            <stop offset="60%" stop-color="#E5DFCB" />
                            <stop offset="80%" stop-color="#B9E9D1" />
                            <stop offset="100%" stop-color="#D891DF" />
                        </linearGradient>
                    </defs>
                    <path d="M2 6.5h46v3H2z" fill="url(#MenuGradientShared)" />
                    <path d="M2 14h46v3H2z" fill="url(#MenuGradientShared)" />
                    <path d="M2 21.5h46v3H2z" fill="url(#MenuGradientShared)" />
                </svg>
            </button>
        </div>
    </div>

    <nav class="header-nav" aria-label="Điều hướng chính">
        @foreach ($resolvedMenuLinks as $link)
            @php
                $isActive = $pageKey === $link['page_key'];
                $classes = 'header-nav__link' . ($isActive ? ' header-nav__link--active' : '');
                if (!empty($link['is_home_icon'])) {
                    $classes .= ' header-nav__link--home';
                }
            @endphp

            <a class="{{ $classes }}" href="{{ $link['href'] }}" data-page-key="{{ $link['page_key'] }}"
                @if ($isActive) aria-current="page" @endif
                @if ($link['open_in_new_tab']) target="_blank" rel="noreferrer noopener" @endif>
                @if (!empty($link['is_home_icon']))
                    <img src="/theme/assets/icons/icon-home.webp" alt="" loading="lazy" decoding="async">
                @endif
                <span>{{ $link['label'] }}</span>
            </a>
        @endforeach
    </nav>
</header>
