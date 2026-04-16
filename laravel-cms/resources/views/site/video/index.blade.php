@extends('site.layouts.app')

@section('title', $page->seo_title ?? ($siteSettings['seo_default_title'] ?? 'Video HOVI Việt Nam | Công trình thực tế'))
@section('meta_description',
    $page->seo_description ??
    ($siteSettings['seo_default_description'] ??
        'Khám phá video công trình thực tế, chia sẻ hậu trường thiết kế thi công và kinh nghiệm triển khai từ HOVI Việt Nam.'))
@section('body_class', 'contact-page project-category-page')
@section('page_key', 'video')

@push('head')
    <style>
        .video-card__meta {
            margin: -8px 14px 10px;
            color: rgba(255, 255, 255, 0.66);
            font-size: .82rem;
        }

        .video-card__excerpt {
            margin: 0;
            padding: 0 14px 16px;
            color: rgba(255, 255, 255, 0.8);
            font-size: .9rem;
            line-height: 1.58;
        }

        .video-empty {
            color: rgba(255, 255, 255, .78);
            font-size: .96rem;
        }

        .video-card__project {
            margin: -6px 14px 12px;
            display: inline-flex;
            align-items: center;
            min-height: 24px;
            padding: 0 10px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.36);
            background: rgba(0, 0, 0, 0.28);
            color: rgba(255, 255, 255, 0.9);
            font-size: .74rem;
            letter-spacing: .05em;
            text-transform: uppercase;
        }
    </style>
@endpush

@section('content')
    @php
        $resolveImage = function ($raw, $fallback = '/theme/assets/hovi/gallery/hovi-034.jpg') {
            $value = trim((string) $raw);
            if ($value === '') {
                return $fallback;
            }

            if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://', '/'])) {
                return $value;
            }

            return '/theme/assets/hovi/gallery/' . ltrim($value, '/');
        };
    @endphp

    <main class="category-main">
        <section class="category-head category-head--lined" id="tong-quan">
            <h1 class="category-title">VIDEO HOVI VIỆT NAM</h1>
        </section>

        <section class="category-section" id="video-list">
            <div class="category-section-heading category-section-heading--lined">
                <h2>Video mới nhất</h2>
            </div>

            <div class="category-grid category-grid--media">
                @forelse ($videos as $video)
                    <article class="category-card category-card--video">
                        <a href="{{ !empty($video->slug) ? route('site.video.show', ['slug' => $video->slug]) : route('site.video.index') }}">
                            <img src="{{ $resolveImage($video->thumbnail_image) }}" alt="{{ $video->title }}" loading="lazy"
                                decoding="async">
                            <h3>{{ $video->title }}</h3>
                        </a>
                        <p class="video-card__meta">
                            {{ optional($video->published_at)->format('d/m/Y') ?: optional($video->created_at)->format('d/m/Y') }}
                        </p>
                        @if ($video->project)
                            <p class="video-card__project">Dự án: {{ $video->project->name }}</p>
                        @endif
                        @if (!empty($video->description))
                            <p class="video-card__excerpt">{{ \Illuminate\Support\Str::limit($video->description, 130) }}</p>
                        @endif
                    </article>
                @empty
                    <p class="video-empty">Hiện chưa có video nào được xuất bản.</p>
                @endforelse
            </div>
        </section>
    </main>
@endsection
