@extends('site.layouts.app')

@section('title', $page->seo_title ?? 'Giới Thiệu HOVI Việt Nam | Tầm Nhìn, Sứ Mệnh & Năng Lực')
@section('meta_description',
    $page->seo_description ??
    'Khám phá HOVI Việt Nam, đơn vị thiết kế thi công cảnh quan và sân vườn cao cấp với quy trình rõ ràng, giá trị cốt lõi khác biệt.')
@section('body_class', 'contact-page about-us-page')
@section('page_key', 'about')

@section('content')
    @php
        if (!is_array($aboutContent ?? null)) {
            try {
                $aboutContent = \App\Models\SiteSetting::aboutContent();
            } catch (\Throwable $exception) {
                $aboutContent = \App\Models\SiteSetting::aboutContentDefaults();
            }
        }

        $hero = data_get($aboutContent, 'hero', []);
        $mission = data_get($aboutContent, 'mission', []);
        $vision = data_get($aboutContent, 'vision', []);
        $inspiration = data_get($aboutContent, 'inspiration', []);
        $definition = data_get($aboutContent, 'definition', []);
        $core = data_get($aboutContent, 'core', []);
        $manifesto = data_get($aboutContent, 'manifesto', []);
        $advantages = data_get($aboutContent, 'advantages', []);
        $ceo = data_get($aboutContent, 'ceo', []);
        $capacity = data_get($aboutContent, 'capacity', []);

        $heroEnabled = (bool) data_get($hero, 'enabled', true);
        $missionEnabled = (bool) data_get($mission, 'enabled', true);
        $visionEnabled = (bool) data_get($vision, 'enabled', true);
        $inspirationEnabled = (bool) data_get($inspiration, 'enabled', true);
        $definitionEnabled = (bool) data_get($definition, 'enabled', true);
        $coreEnabled = (bool) data_get($core, 'enabled', true);
        $manifestoEnabled = (bool) data_get($manifesto, 'enabled', true);
        $advantagesEnabled = (bool) data_get($advantages, 'enabled', true);
        $ceoEnabled = (bool) data_get($ceo, 'enabled', true);
        $capacityEnabled = (bool) data_get($capacity, 'enabled', true);

        $coreItems = collect(data_get($core, 'items', []))->values();
        $manifestoItems = collect(data_get($manifesto, 'items', []))->values();
        $advantagesItems = collect(data_get($advantages, 'items', []))->values();
        $capacityStats = collect(data_get($capacity, 'stats', []))->values();

    @endphp

    <main class="about-main">
        @if ($heroEnabled)
            <section class="about-shell about-hero" id="tong-quan">
                <p class="eyebrow">{{ data_get($hero, 'eyebrow', 'About Us') }}</p>
                <h1>{{ data_get($hero, 'title') }}</h1>
                <p>{{ data_get($hero, 'description') }}</p>
                <img src="{{ data_get($hero, 'image') }}" alt="{{ data_get($hero, 'image_alt', 'About HOVI') }}">
            </section>
        @endif

        @if ($missionEnabled || $visionEnabled)
            <section class="about-shell about-dual-grid" id="su-menh">
                @if ($missionEnabled)
                    <article class="about-card">
                        <div class="about-card__media">
                            <img src="{{ data_get($mission, 'image') }}"
                                alt="{{ data_get($mission, 'image_alt', data_get($mission, 'title')) }}">
                        </div>
                        <div class="about-card__body">
                            <h2>{{ data_get($mission, 'title') }}</h2>
                            <p>{{ data_get($mission, 'description') }}</p>
                        </div>
                    </article>
                @endif

                @if ($visionEnabled)
                    <article class="about-card">
                        <div class="about-card__media">
                            <img src="{{ data_get($vision, 'image') }}"
                                alt="{{ data_get($vision, 'image_alt', data_get($vision, 'title')) }}">
                        </div>
                        <div class="about-card__body">
                            <h2>{{ data_get($vision, 'title') }}</h2>
                            <p>{{ data_get($vision, 'description') }}</p>
                        </div>
                    </article>
                @endif
            </section>
        @endif

        @if ($inspirationEnabled)
            <section class="about-shell about-inspire">
                <div class="about-inspire__media">
                    <img src="{{ data_get($inspiration, 'image') }}"
                        alt="{{ data_get($inspiration, 'image_alt', data_get($inspiration, 'title')) }}">
                </div>
                <div class="about-inspire__body">
                    <h2>{{ data_get($inspiration, 'title') }}</h2>
                    <h3>{{ data_get($inspiration, 'subtitle') }}</h3>
                    <p>{{ data_get($inspiration, 'description') }}</p>
                </div>
            </section>
        @endif

        @if ($definitionEnabled)
            <section class="about-shell about-definition">
                <h2>{{ data_get($definition, 'title') }}</h2>
                <p>{{ data_get($definition, 'description') }}</p>
            </section>
        @endif

        @if ($coreEnabled)
            <section class="about-shell about-core" id="gia-tri">
                <div class="about-section-heading">
                    <h2>{{ data_get($core, 'heading') }}</h2>
                </div>
                <div class="about-core-grid">
                    @foreach ($coreItems as $item)
                        <article class="about-value-item">
                            <img src="{{ data_get($item, 'image') }}"
                                alt="{{ data_get($item, 'image_alt', data_get($item, 'title')) }}">
                            <h3>{{ data_get($item, 'title') }}</h3>
                        </article>
                    @endforeach
                </div>

                <div class="about-value-details-grid">
                    @foreach ($coreItems as $item)
                        <article class="about-value-detail">
                            <h3>{{ data_get($item, 'title') }}</h3>
                            <p>{{ data_get($item, 'description') }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($manifestoEnabled)
            <section class="about-shell about-manifesto">
                <div class="about-section-heading">
                    <h2>{{ data_get($manifesto, 'heading') }}</h2>
                </div>
                <div class="about-manifesto-grid">
                    @foreach ($manifestoItems as $item)
                        <article class="about-quote">
                            <img src="{{ data_get($item, 'image') }}"
                                alt="{{ data_get($item, 'image_alt', 'Cam kết') }}">
                            <p>{{ data_get($item, 'quote') }}</p>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($advantagesEnabled)
            <section class="about-shell about-advantages" id="loi-the">
                <div class="about-advantages__media"><img src="{{ data_get($advantages, 'image') }}"
                        alt="{{ data_get($advantages, 'image_alt', data_get($advantages, 'title')) }}"></div>
                <div class="about-advantages__content">
                    <h2>{{ data_get($advantages, 'title') }}</h2>
                    <ol>
                        @foreach ($advantagesItems as $item)
                            <li><strong>{{ data_get($item, 'title') }}:</strong> {{ data_get($item, 'description') }}</li>
                        @endforeach
                    </ol>
                </div>
            </section>
        @endif

        @if ($ceoEnabled)
            <section class="about-shell about-ceo" id="ceo">
                <div class="about-ceo__media"><img src="{{ data_get($ceo, 'image') }}"
                        alt="{{ data_get($ceo, 'image_alt', data_get($ceo, 'title')) }}">
                </div>
                <div class="about-ceo__content">
                    <p class="eyebrow">{{ data_get($ceo, 'eyebrow') }}</p>
                    <h2>{{ data_get($ceo, 'title') }}</h2>
                    <p>{{ data_get($ceo, 'description_1') }}</p>
                    <p>{{ data_get($ceo, 'description_2') }}</p>
                </div>
            </section>
        @endif

        @if ($capacityEnabled)
            <section class="about-shell about-capacity" id="ho-so">
                <div class="about-section-heading">
                    <h2>{{ data_get($capacity, 'heading') }}</h2>
                </div>
                <p class="about-capacity__lead">{{ data_get($capacity, 'lead') }}</p>
                <div class="about-stats-grid">
                    @foreach ($capacityStats as $stat)
                        <article><strong>{{ data_get($stat, 'value') }}</strong><span>{{ data_get($stat, 'label') }}</span>
                        </article>
                    @endforeach
                </div>
                <div class="about-capacity__actions">
                    @if (!empty(data_get($capacity, 'action_1_label')))
                        <a class="outline-button" href="{{ data_get($capacity, 'action_1_url') }}">{{ data_get($capacity, 'action_1_label') }}</a>
                    @endif
                    @if (!empty(data_get($capacity, 'action_2_label')))
                        <a class="outline-button" href="{{ data_get($capacity, 'action_2_url') }}">{{ data_get($capacity, 'action_2_label') }}</a>
                    @endif
                </div>
            </section>
        @endif
    </main>
@endsection
