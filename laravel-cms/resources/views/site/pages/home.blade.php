@extends('site.layouts.app')

@section('title', $page->seo_title ?? 'HOVI Việt Nam | Thiết Kế & Thi Công Cảnh Quan, Sân Vườn Cao Cấp')
@section('meta_description',
    $page->seo_description ??
    'HOVI Việt Nam chuyên thiết kế, thi công cảnh quan và sân vườn cao cấp cho biệt thự, penthouse và khu đô thị.')
@section('body_class', 'home-page')
@section('page_key', 'home')
@section('inline_footer', '1')

@section('before_main')
    <nav class="section-dots" aria-label="Điều hướng section">
        <a href="#hero" class="section-dots__dot is-active" data-dot="hero" aria-label="Hero"></a>
        <a href="#projects-1" class="section-dots__dot" data-dot="projects-1" aria-label="Dự án 1"></a>
        <a href="#projects-2" class="section-dots__dot" data-dot="projects-2" aria-label="Dự án 2"></a>
        <a href="#profile" class="section-dots__dot" data-dot="profile" aria-label="Hồ sơ năng lực"></a>
        <a href="#about" class="section-dots__dot" data-dot="about" aria-label="Giới thiệu"></a>
        <a href="#footer" class="section-dots__dot" data-dot="footer" aria-label="Liên hệ"></a>
    </nav>
@endsection

@section('content')
    @php
        $projectDetailUrl = url('/biet-thu-don-lap-m07-l14-dtm-duong-noi');
        if (!is_array($homeContent ?? null)) {
            try {
                $homeContent = \App\Models\SiteSetting::homeContent();
            } catch (\Throwable $exception) {
                $homeContent = \App\Models\SiteSetting::homeContentDefaults();
            }
        }

        $heroContent = data_get($homeContent, 'hero', []);
        $profileContent = data_get($homeContent, 'profile', []);
        $aboutSection = data_get($homeContent, 'about', []);
        $footerCta = data_get($homeContent, 'footer_cta', []);
        $consultCta = data_get($footerCta, 'consult', []);
        $partnerCta = data_get($footerCta, 'partner', []);

        $fallbackSection1 = [
            ['image' => 'hovi-002.jpg', 'title' => 'Tổ hợp biệt thự BT61-62-63, Starlake', 'desc' => 'Dự án thiết kế'],
            ['image' => 'hovi-003.jpg', 'title' => 'BIỆT THỰ SONG LẬP BT48-56, LOUIS CITY', 'desc' => 'Dự án thiết kế'],
            ['image' => 'hovi-004.jpg', 'title' => 'BT ĐƠN LẬP NT18-01, VINHOMES OCEAN PARK', 'desc' => 'Dự án thiết kế'],
            ['image' => 'hovi-005.jpg', 'title' => 'BIỆT THỰ ĐƠN LẬP GV12-09, VINHOMES GREEN VILLAS', 'desc' => 'Dự án thiết kế'],
            ['image' => 'hovi-006.jpg', 'title' => 'HAI BIỆT THỰ ĐƠN LẬP ĐẬP THÔNG, VINHOMES GREENVILLAS', 'desc' => 'Vinhomes Green Villas'],
            ['image' => 'hovi-007.jpg', 'title' => 'TỔ HỢP 4 BIỆT THỰ VINHOMES RIVERSIDE ĐẬP THÔNG', 'desc' => 'Vinhomes Riverside'],
        ];

        $fallbackSection2 = [
            ['image' => 'hovi-008.jpg', 'title' => 'BIỆT THỰ ĐƠN LẬP BT46-47, KĐT STARLAKE', 'desc' => 'Dự án thiết kế'],
            ['image' => 'hovi-009.jpg', 'title' => 'PENTHOUSE CAO CẤP, MỸ ĐÌNH PEARL', 'desc' => 'Dự án thiết kế'],
            ['image' => 'hovi-010.jpg', 'title' => 'BIỆT THỰ ĐƠN LẬP 64-K7, KĐT STARLAKE', 'desc' => 'Dự án thiết kế'],
            ['image' => 'hovi-011.jpg', 'title' => 'BIỆT THỰ ĐƠN LẬP 2-6, KĐT PARK CITY', 'desc' => 'Dự án thiết kế'],
            ['image' => 'hovi-012.jpg', 'title' => 'BIỆT THỰ ĐƠN LẬP AD6-06, KĐT VINHOMES RIVERSIDE', 'desc' => 'Dự án thi công'],
            ['image' => 'hovi-013.jpg', 'title' => 'BIỆT THỰ ĐƠN LẬP 41-42 K7, KĐT STARLAKE', 'desc' => 'Dự án thiết kế'],
        ];

        $toImageUrl = function ($value, $fallback = null) {
            $raw = trim((string) $value);
            if ($raw === '') {
                return $fallback;
            }

            if (\Illuminate\Support\Str::startsWith($raw, ['http://', 'https://', '/'])) {
                return $raw;
            }

            return '/theme/assets/hovi/gallery/' . ltrim($raw, '/');
        };

        $toHref = function ($value, $fallback = '#') {
            $raw = trim((string) $value);
            if ($raw === '') {
                return $fallback;
            }

            if (\Illuminate\Support\Str::startsWith($raw, ['http://', 'https://', '#', 'mailto:', 'tel:'])) {
                return $raw;
            }

            return url('/' . ltrim($raw, '/'));
        };

        $fallbackCards = collect(array_merge($fallbackSection1, $fallbackSection2))->map(function ($item) use ($projectDetailUrl) {
            return [
                'image' => '/theme/assets/hovi/gallery/' . $item['image'],
                'title' => $item['title'],
                'desc' => $item['desc'],
                'url' => $projectDetailUrl,
                'action' => 'link',
            ];
        });

        $homeProjectHighlights = collect($homeProjectHighlights ?? []);

        $cards = $homeProjectHighlights
            ->map(function ($item) use ($toImageUrl) {
                $action = data_get($item, 'thumbnail_click_action') === 'lightbox' ? 'lightbox' : 'link';

                return [
                    'image' => $toImageUrl(
                        data_get($item, 'thumbnail_image'),
                        $toImageUrl(data_get($item, 'project.cover_image'))
                    ),
                    'title' => data_get($item, 'title'),
                    'desc' => data_get($item, 'project.name', 'Dự án thiết kế'),
                    'url' => $action === 'link' ? url('/' . ltrim((string) data_get($item, 'slug'), '/')) : null,
                    'action' => $action,
                ];
            })
            ->filter(function ($item) {
                return !empty($item['title']) && !empty($item['image']);
            })
            ->values();

        if ($cards->isEmpty()) {
            $cards = $fallbackCards;
        }

        if ($cards->isNotEmpty()) {
            while ($cards->count() < 12) {
                $cards = $cards->merge($cards);
            }

            $cards = $cards->take(12)->values();
        }

        $projectsSection1 = $cards->take(6)->values();
        $projectsSection2 = $cards->slice(6, 6)->values();

        $sliderImages = collect(data_get($profileContent, 'slider_images', []))
            ->map(function ($image) use ($toImageUrl) {
                return $toImageUrl($image);
            })
            ->filter()
            ->values();

        if ($sliderImages->isEmpty()) {
            $sliderImages = collect(['hovi-014.jpg', 'hovi-015.jpg', 'hovi-016.jpg', 'hovi-017.jpg', 'hovi-018.jpg', 'hovi-019.jpg'])
                ->map(function ($image) {
                    return '/theme/assets/hovi/gallery/' . $image;
                });
        }

        $heroBackgroundImage = $toImageUrl(data_get($heroContent, 'background_image'), '/theme/assets/hero.jpg');
        $heroScrollTarget = $toHref(data_get($heroContent, 'scroll_target'), '#projects-1');

        $profileBackgroundImage = $toImageUrl(data_get($profileContent, 'background_image'), '/theme/assets/hovi/gallery/hovi-060.jpg');
        $profileEyebrow = data_get($profileContent, 'eyebrow', 'Hồ sơ năng lực');
        $profileTitle = data_get($profileContent, 'title', 'Không gian được thiết kế như một tuyên ngôn sống');
        $profileDescription1 = data_get($profileContent, 'description_1');
        $profileDescription2 = data_get($profileContent, 'description_2');
        $profileButtonLabel = data_get($profileContent, 'button_label', 'Catalogue HOVI VIỆT NAM');
        $profileButtonUrl = $toHref(data_get($profileContent, 'button_url'), '#footer');

        $aboutTitle = data_get($aboutSection, 'title', 'Về HOVI VIỆT NAM');
        $aboutDescription = data_get($aboutSection, 'description');
        $aboutStats = collect(data_get($aboutSection, 'stats', []))
            ->filter(function ($item) {
                return !empty(data_get($item, 'value')) || !empty(data_get($item, 'label'));
            })
            ->values();

        if ($aboutStats->isEmpty()) {
            $aboutStats = collect([
                ['value' => '10+', 'label' => 'Năm kinh nghiệm'],
                ['value' => '80', 'label' => 'Nhân sự'],
                ['value' => '100', 'label' => 'Khách hàng'],
                ['value' => '100+', 'label' => 'Dự án thi công'],
            ]);
        }

        $aboutCtaLabel = data_get($aboutSection, 'cta_label', 'XEM THÊM');
        $aboutCtaUrl = $toHref(data_get($aboutSection, 'cta_url'), url('/about-us'));
        $aboutTeamImage = $toImageUrl(data_get($aboutSection, 'team_image'), '/theme/assets/hovi/gallery/hovi-060.jpg');

        $consultTitle = data_get($consultCta, 'title', 'ĐẶT LỊCH TƯ VẤN');
        $consultButtonLabel = data_get($consultCta, 'button_label', 'Đặt lịch');
        $consultButtonUrl = $toHref(data_get($consultCta, 'button_url'), 'https://www.hovi.com.vn/dang-ky-dich-vu/');
        $consultBackgroundImage = $toImageUrl(data_get($consultCta, 'background_image'), '/theme/assets/hovi/gallery/hovi-001.jpg');

        $partnerTitle = data_get($partnerCta, 'title', 'TRỞ THÀNH ĐỐI TÁC HOVI VIỆT NAM');
        $partnerButtonLabel = data_get($partnerCta, 'button_label', 'Tham gia');
        $partnerButtonUrl = $toHref(data_get($partnerCta, 'button_url'), url('/lien-he'));
        $partnerBackgroundImage = $toImageUrl(data_get($partnerCta, 'background_image'), '/theme/assets/hovi/gallery/hovi-055.jpg');
    @endphp

    <main class="snap-container" id="main-scroll">
        <section class="section hero" id="hero" data-section="hero"
            style="background: linear-gradient(180deg, rgba(255, 255, 255, 0.2), rgba(255, 255, 255, 0.08) 46%, rgba(255, 255, 255, 0.02) 72%, rgba(255, 255, 255, 0)), url('{{ $heroBackgroundImage }}') center center / cover no-repeat;">
            <div class="hero__overlay"></div>
            <a class="scroll-cue" href="{{ $heroScrollTarget }}" aria-label="Cuộn xuống">
                <span></span>
            </a>
        </section>

        <section class="section project-section" id="projects-1" data-section="projects-1">
            <div class="project-grid">
                @foreach ($projectsSection1 as $project)
                    @php $cardAction = $project['action'] ?? 'link'; @endphp
                    <article class="project-card"
                        @if ($cardAction === 'lightbox')
                            data-image-preview="{{ $project['image'] }}" data-image-preview-title="{{ $project['title'] }}"
                        @else
                            data-hover-redirect="{{ $project['url'] }}"
                        @endif>
                        <img src="{{ $project['image'] }}" alt="{{ $project['title'] }}">
                        <div class="project-card__content">
                            @if ($cardAction === 'lightbox')
                                <span class="project-card__mode">Xem ảnh</span>
                            @endif
                            <h2>{{ $project['title'] }}</h2>
                            <p>{{ $project['desc'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="section project-section" id="projects-2" data-section="projects-2">
            <div class="project-grid">
                @foreach ($projectsSection2 as $project)
                    @php $cardAction = $project['action'] ?? 'link'; @endphp
                    <article class="project-card"
                        @if ($cardAction === 'lightbox')
                            data-image-preview="{{ $project['image'] }}" data-image-preview-title="{{ $project['title'] }}"
                        @else
                            data-hover-redirect="{{ $project['url'] }}"
                        @endif>
                        <img src="{{ $project['image'] }}" alt="{{ $project['title'] }}">
                        <div class="project-card__content">
                            @if ($cardAction === 'lightbox')
                                <span class="project-card__mode">Xem ảnh</span>
                            @endif
                            <h2>{{ $project['title'] }}</h2>
                            <p>{{ $project['desc'] }}</p>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="section profile-section" id="profile" data-section="profile"
            style="background: linear-gradient(180deg, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.38)), url('{{ $profileBackgroundImage }}') center center / cover no-repeat;">
            <div class="profile-section__inner">
                <div class="profile-copy">
                    <p class="eyebrow">{{ $profileEyebrow }}</p>
                    <h2>{{ $profileTitle }}</h2>
                    @if (!empty($profileDescription1))
                        <p>{{ $profileDescription1 }}</p>
                    @endif
                    @if (!empty($profileDescription2))
                        <p>{{ $profileDescription2 }}</p>
                    @endif
                    @if (!empty($profileButtonLabel))
                        <a class="outline-button" href="{{ $profileButtonUrl }}">{{ $profileButtonLabel }}</a>
                    @endif
                </div>

                <div class="profile-slider">
                    <div class="profile-slider__viewport">
                        <div class="profile-slider__track" data-slider-track>
                            @foreach ($sliderImages as $index => $image)
                                <div class="profile-slide">
                                    <img src="{{ $image }}" alt="Catalog HOVI VIỆT NAM {{ $index + 1 }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="profile-slider__controls">
                        <button type="button" class="slider-arrow" data-slider-prev aria-label="Ảnh trước">←</button>
                        <button type="button" class="slider-arrow" data-slider-next aria-label="Ảnh sau">→</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="section about-section" id="about" data-section="about">
            <div class="about-center">
                <p class="about-title">{{ $aboutTitle }}</p>
                @if (!empty($aboutDescription))
                    <p class="about-desc">{{ $aboutDescription }}</p>
                @endif
                <div class="about-stats">
                    @foreach ($aboutStats as $stat)
                        <div class="about-stat">
                            <strong>{{ data_get($stat, 'value') }}</strong>
                            <span>{{ data_get($stat, 'label') }}</span>
                        </div>
                    @endforeach
                </div>
                @if (!empty($aboutCtaLabel))
                    <a class="about-cta" href="{{ $aboutCtaUrl }}">{{ $aboutCtaLabel }}</a>
                @endif
            </div>

            <div class="about-team">
                <img src="{{ $aboutTeamImage }}" alt="Đội ngũ HOVI Việt Nam">
            </div>
        </section>

        <section class="section footer-section" id="footer" data-section="footer">
            <div class="cta-grid">
                <article class="cta-card cta-card--consult"
                    style="background: url('{{ $consultBackgroundImage }}') center center / cover no-repeat;">
                    <div class="cta-card__overlay"></div>
                    <div class="cta-card__content">
                        <h3>{{ $consultTitle }}</h3>
                        <a class="outline-button" href="{{ $consultButtonUrl }}" target="_blank"
                            rel="noreferrer noopener">{{ $consultButtonLabel }}</a>
                    </div>
                </article>
                <article class="cta-card cta-card--partner"
                    style="background: url('{{ $partnerBackgroundImage }}') center center / cover no-repeat;">
                    <div class="cta-card__overlay"></div>
                    <div class="cta-card__content">
                        <h3>{{ $partnerTitle }}</h3>
                        <a class="outline-button" href="{{ $partnerButtonUrl }}">{{ $partnerButtonLabel }}</a>
                    </div>
                </article>
            </div>

            @include('site.partials.footer')
        </section>
    </main>
@endsection
