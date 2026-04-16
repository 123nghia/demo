@extends('site.layouts.app')

@section('title', $page->seo_title ?? ($siteSettings['seo_default_title'] ?? 'HOVI Việt Nam | Thiết Kế & Thi Công Cảnh Quan, Sân Vườn Cao Cấp'))
@section('meta_description',
    $page->seo_description ??
    ($siteSettings['seo_default_description'] ??
        'HOVI Việt Nam chuyên thiết kế, thi công cảnh quan và sân vườn cao cấp cho biệt thự, penthouse và khu đô thị.'))
@section('body_class', 'home-page')
@section('page_key', 'home')
@section('inline_footer', '1')

@php
    $homeHighlightsForNav = collect();

    if (isset($homeProjectHighlights)) {
        $homeHighlightsForNav = collect($homeProjectHighlights);
    } else {
        try {
            $homeHighlightsForNav = \App\Models\ProjectDetailPage::query()
                ->published()
                ->whereNotNull('thumbnail_image')
                ->where('thumbnail_image', '!=', '')
                ->whereHas('project', function ($query) {
                    $query->published();
                })
                ->with([
                    'project' => function ($query) {
                        $query->select(['id', 'name', 'slug', 'is_published']);
                    },
                ])
                ->ordered()
                ->limit(12)
                ->get();
        } catch (\Throwable $exception) {
            $homeHighlightsForNav = collect();
        }
    }

    $eligibleHomeHighlightCount = $homeHighlightsForNav
        ->filter(function ($item) {
            return !empty(data_get($item, 'title'))
                && (!empty(data_get($item, 'thumbnail_image')) || !empty(data_get($item, 'project.cover_image')));
        })
        ->count();

    $hasProjectsSection1ForNav = $eligibleHomeHighlightCount > 0;
    $hasProjectsSection2ForNav = $eligibleHomeHighlightCount > 6;
@endphp

@section('before_main')
    <nav class="section-dots" aria-label="Điều hướng section">
        <a href="#hero" class="section-dots__dot is-active" data-dot="hero" aria-label="Hero"></a>
        @if ($hasProjectsSection1ForNav)
            <a href="#projects-1" class="section-dots__dot" data-dot="projects-1" aria-label="Dự án 1"></a>
        @endif
        @if ($hasProjectsSection2ForNav)
            <a href="#projects-2" class="section-dots__dot" data-dot="projects-2" aria-label="Dự án 2"></a>
        @endif
        <a href="#profile" class="section-dots__dot" data-dot="profile" aria-label="Hồ sơ năng lực"></a>
        <a href="#about" class="section-dots__dot" data-dot="about" aria-label="Giới thiệu"></a>
        <a href="#footer" class="section-dots__dot" data-dot="footer" aria-label="Liên hệ"></a>
    </nav>
@endsection

@section('content')
    @php
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

        $homeProjectHighlights = $homeHighlightsForNav;

        $cards = $homeProjectHighlights
            ->map(function ($item) use ($toImageUrl) {
                $resolvedUrl = trim((string) data_get($item, 'url'));
                $resolvedSlug = trim((string) data_get($item, 'slug'), '/');
                $action = data_get($item, 'thumbnail_click_action') === 'lightbox' ? 'lightbox' : 'link';

                if ($action === 'link' && $resolvedUrl === '' && $resolvedSlug !== '') {
                    $resolvedUrl = url('/' . $resolvedSlug);
                }

                if ($action === 'link' && $resolvedUrl === '') {
                    $action = 'lightbox';
                }

                return [
                    'image' => $toImageUrl(
                        data_get($item, 'thumbnail_image'),
                        $toImageUrl(data_get($item, 'project.cover_image'))
                    ),
                    'title' => data_get($item, 'title'),
                    'desc' => data_get($item, 'description', data_get($item, 'summary', data_get($item, 'project.name', 'Dự án thiết kế'))),
                    'url' => $action === 'link' ? $resolvedUrl : null,
                    'action' => $action,
                ];
            })
            ->filter(function ($item) {
                return !empty($item['title']) && !empty($item['image']);
            })
            ->values();

        $cards = $cards->take(12)->values();

        $lazyPlaceholderImage = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==';

        $projectsSection1 = $cards->take(6)->values();
        $hasProjectsSection1 = $projectsSection1->isNotEmpty();
        $projectsSection2 = $cards->slice(6, 6)->values();
        $hasProjectsSection2 = $projectsSection2->isNotEmpty();

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

        $heroFallbackImage = '/theme/assets/hero.jpg';
        $rawHeroBackgroundImage = trim((string) data_get($heroContent, 'background_image', ''));
        if ($rawHeroBackgroundImage === '') {
            $rawHeroBackgroundImage = $heroFallbackImage;
        }
        $heroBackgroundImage = $toImageUrl($rawHeroBackgroundImage, $heroFallbackImage);
        $heroDefaultScrollTarget = $hasProjectsSection1 ? '#projects-1' : '#profile';
        $heroScrollTarget = $toHref(data_get($heroContent, 'scroll_target'), $heroDefaultScrollTarget);

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
        $rawConsultButtonUrl = (string) data_get($consultCta, 'button_url', '');
        $consultButtonUrl = $toHref($rawConsultButtonUrl, url('/dang-ky-dich-vu'));
        $consultButtonPath = trim((string) parse_url($rawConsultButtonUrl, PHP_URL_PATH), '/');
        if ($consultButtonPath === 'dang-ky-dich-vu') {
            $consultButtonUrl = url('/dang-ky-dich-vu');
        }
        $consultBackgroundImage = $toImageUrl(data_get($consultCta, 'background_image'), '/theme/assets/hovi/gallery/hovi-001.jpg');

        $partnerTitle = data_get($partnerCta, 'title', 'TRỞ THÀNH ĐỐI TÁC HOVI VIỆT NAM');
        $partnerButtonLabel = data_get($partnerCta, 'button_label', 'Tham gia');
        $partnerButtonUrl = $toHref(data_get($partnerCta, 'button_url'), url('/lien-he'));
        $partnerBackgroundImage = $toImageUrl(data_get($partnerCta, 'background_image'), '/theme/assets/hovi/gallery/hovi-055.jpg');
    @endphp

    <main class="snap-container" id="main-scroll">
        <h1 class="sr-only">{{ $page->seo_title ?? 'HOVI Việt Nam - Thiết kế thi công cảnh quan sân vườn cao cấp' }}</h1>

        <section class="section hero" id="hero" data-section="hero">
            <img class="hero__media" src="{{ $heroBackgroundImage }}" alt="" loading="eager" fetchpriority="high"
                decoding="async">
            <div class="hero__overlay"></div>
            <a class="scroll-cue" href="{{ $heroScrollTarget }}" aria-label="Cuộn xuống">
                <span></span>
            </a>
        </section>

        @if ($hasProjectsSection1)
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
                            <img src="{{ $lazyPlaceholderImage }}" data-lazy-src="{{ $project['image'] }}"
                                alt="{{ $project['title'] }}" loading="lazy" decoding="async" fetchpriority="low">
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
        @endif

        @if ($hasProjectsSection2)
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
                            <img src="{{ $lazyPlaceholderImage }}" data-lazy-src="{{ $project['image'] }}"
                                alt="{{ $project['title'] }}" loading="lazy" decoding="async" fetchpriority="low">
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
        @endif

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
                                    <img src="{{ $lazyPlaceholderImage }}" data-lazy-src="{{ $image }}"
                                        alt="Catalog HOVI VIỆT NAM {{ $index + 1 }}" loading="lazy" decoding="async"
                                        fetchpriority="low">
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
                <img src="{{ $lazyPlaceholderImage }}" data-lazy-src="{{ $aboutTeamImage }}" alt="Đội ngũ HOVI Việt Nam"
                    loading="lazy" decoding="async" fetchpriority="low">
            </div>
        </section>

        <section class="section footer-section" id="footer" data-section="footer">
            <div class="cta-grid">
                <article class="cta-card cta-card--consult"
                    data-lazy-bg="{{ $consultBackgroundImage }}"
                    style="background-position: center center; background-size: cover; background-repeat: no-repeat;">
                    <div class="cta-card__overlay"></div>
                    <div class="cta-card__content">
                        <h3>{{ $consultTitle }}</h3>
                        <a class="outline-button" href="{{ $consultButtonUrl }}">{{ $consultButtonLabel }}</a>
                    </div>
                </article>
                <article class="cta-card cta-card--partner"
                    data-lazy-bg="{{ $partnerBackgroundImage }}"
                    style="background-position: center center; background-size: cover; background-repeat: no-repeat;">
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
