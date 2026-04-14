@extends('site.layouts.app')

@section('title', $blog->seo_title ?: ($blog->title . ' | Blog HOVI Việt Nam'))
@section('meta_description', $blog->seo_description ?: \Illuminate\Support\Str::limit(strip_tags((string) $blog->excerpt), 160))
@section('body_class', 'contact-page project-detail-page')
@section('page_key', 'blog')

@push('head')
    <style>
        .blog-detail-cover {
            width: 100%;
            max-height: 520px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 14px 36px rgba(0, 0, 0, .28);
        }

        .blog-detail-content {
            margin-top: 14px;
            color: rgba(255, 255, 255, .84);
            line-height: 1.8;
            font-size: .98rem;
            white-space: pre-wrap;
        }

        .blog-detail-meta {
            margin-top: 10px;
            color: rgba(255, 255, 255, .7);
            font-size: .86rem;
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

    <main class="detail-main">
        <section class="detail-article-head" id="tong-quan">
            <div class="detail-breadcrumb">
                <a href="{{ route('site.page') }}">Trang chủ</a>
                <span>/</span>
                <a href="{{ route('site.blog.index') }}">Blog</a>
            </div>
            <h1 class="detail-title">{{ $blog->title }}</h1>
            <p class="blog-detail-meta">
                Đăng ngày {{ optional($blog->published_at)->format('d/m/Y H:i') ?: optional($blog->created_at)->format('d/m/Y H:i') }}
            </p>
        </section>

        <section class="detail-gallery-section">
            <img class="blog-detail-cover" src="{{ $resolveImage($blog->thumbnail_image) }}" alt="{{ $blog->title }}">

            @if (!empty($blog->excerpt))
                <p class="detail-lead mt-3 mb-0">{{ $blog->excerpt }}</p>
            @endif

            <div class="blog-detail-content">{!! nl2br(e((string) $blog->content)) !!}</div>
        </section>

        @if ($relatedBlogs->isNotEmpty())
            <section class="detail-related">
                <div class="detail-section-heading">
                    <p class="eyebrow">Bài viết liên quan</p>
                    <a class="detail-section-heading__link" href="{{ route('site.blog.index') }}">
                        <h2>Xem thêm bài Blog</h2>
                    </a>
                </div>

                <div class="detail-related__grid">
                    @foreach ($relatedBlogs as $item)
                        <article class="detail-related__card">
                            <a href="{{ route('site.blog.show', $item->slug) }}">
                                <img src="{{ $resolveImage($item->thumbnail_image) }}" alt="{{ $item->title }}">
                                <h3>{{ $item->title }}</h3>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
@endsection
