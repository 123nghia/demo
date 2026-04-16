@extends('site.layouts.app')

@section('title', $page->seo_title ?? 'Blog HOVI Việt Nam | Chia sẻ thiết kế cảnh quan')
@section('meta_description',
    $page->seo_description ??
    'Cập nhật bài viết mới từ HOVI Việt Nam: kiến thức thiết kế cảnh quan, thi công sân vườn và kinh nghiệm triển khai thực tế.')
@section('body_class', 'contact-page project-category-page')
@section('page_key', 'blog')

@push('head')
    <style>
        .blog-card__meta {
            margin: -8px 14px 10px;
            color: rgba(255, 255, 255, 0.66);
            font-size: .82rem;
        }

        .blog-card__excerpt {
            margin: 0;
            padding: 0 14px 16px;
            color: rgba(255, 255, 255, 0.8);
            font-size: .9rem;
            line-height: 1.58;
        }

        .blog-empty {
            color: rgba(255, 255, 255, .78);
            font-size: .96rem;
        }

        .blog-card__project {
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
        $resolveImage = function ($raw, $fallback = '/theme/assets/hovi/gallery/hovi-036.jpg') {
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
            <h1 class="category-title">BLOG HOVI VIỆT NAM</h1>
        </section>

        <section class="category-section" id="blog-list">
            <div class="category-section-heading category-section-heading--lined">
                <h2>Bài viết mới nhất</h2>
            </div>

            <div class="category-grid category-grid--media">
                @forelse ($blogs as $blog)
                    <article class="category-card category-card--blog">
                        <a href="{{ route('site.blog.show', ['slug' => $blog->slug]) }}">
                            <img src="{{ $resolveImage($blog->thumbnail_image) }}" alt="{{ $blog->title }}" loading="lazy"
                                decoding="async">
                            <h3>{{ $blog->title }}</h3>
                        </a>
                        <p class="blog-card__meta">
                            {{ optional($blog->published_at)->format('d/m/Y') ?: optional($blog->created_at)->format('d/m/Y') }}
                        </p>
                        @if ($blog->project)
                            <p class="blog-card__project">Dự án: {{ $blog->project->name }}</p>
                        @endif
                        @if (!empty($blog->excerpt))
                            <p class="blog-card__excerpt">{{ \Illuminate\Support\Str::limit($blog->excerpt, 130) }}</p>
                        @endif
                    </article>
                @empty
                    <p class="blog-empty">Hiện chưa có bài blog nào được xuất bản.</p>
                @endforelse
            </div>
        </section>
    </main>
@endsection
