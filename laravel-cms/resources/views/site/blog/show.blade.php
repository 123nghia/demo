@extends('site.layouts.app')

@section('title', $blog->seo_title ?: ($blog->title . ' | Blog HOVI Việt Nam'))
@section('meta_description', $blog->seo_description ?: \Illuminate\Support\Str::limit(strip_tags((string) $blog->excerpt), 160))
@section('body_class', 'contact-page project-detail-page')
@section('page_key', 'blog')

@push('head')
    <style>
        .blog-detail-main {
            min-height: 100vh;
            padding-top: 92px;
            background:
                radial-gradient(circle at 14% 10%, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0) 30%),
                linear-gradient(180deg, rgba(0, 0, 0, 0.78), rgba(0, 0, 0, 0.95)),
                url('/theme/assets/hovi/gallery/hovi-060.jpg') center top / cover no-repeat;
        }

        .blog-article-wrap {
            width: min(1040px, calc(100% - 40px));
            margin: 0 auto;
            padding-bottom: 26px;
        }

        .blog-article-card {
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: linear-gradient(180deg, rgba(7, 7, 7, 0.74), rgba(0, 0, 0, 0.92));
            box-shadow: 0 18px 44px rgba(0, 0, 0, .3);
            padding: clamp(20px, 3vw, 34px);
        }

        .blog-article-title {
            margin: 0;
            text-align: center;
            font-family: 'SVN Aptima', serif;
            font-weight: 400;
            font-size: clamp(1.86rem, 2.9vw, 2.9rem);
            line-height: 1.1;
            text-wrap: balance;
        }

        .blog-article-meta {
            margin: 10px 0 0;
            text-align: center;
            color: rgba(255, 255, 255, .7);
            font-size: .88rem;
        }

        .blog-article-project {
            margin: 14px auto 0;
            width: fit-content;
            min-height: 28px;
            padding: 0 12px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .34);
            background: rgba(0, 0, 0, .26);
            color: rgba(255, 255, 255, .92);
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .76rem;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .blog-article-project-wrap {
            text-align: center;
        }

        .blog-detail-cover {
            width: 100%;
            margin-top: 20px;
            max-height: 560px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 14px 36px rgba(0, 0, 0, .28);
        }

        .blog-article-excerpt {
            margin: 16px auto 0;
            max-width: 90ch;
            color: rgba(255, 255, 255, .82);
            line-height: 1.8;
            font-size: .98rem;
            text-align: center;
        }

        .blog-detail-richtext {
            margin-top: 18px;
            color: rgba(255, 255, 255, .86);
        }

        .blog-detail-richtext,
        .blog-detail-richtext * {
            font-family: 'Open Sans', Arial, sans-serif;
        }

        .blog-detail-richtext p,
        .blog-detail-richtext li,
        .blog-detail-richtext blockquote,
        .blog-detail-richtext pre,
        .blog-detail-richtext td,
        .blog-detail-richtext th {
            color: rgba(255, 255, 255, .86);
            font-size: 1rem;
            line-height: 1.85;
        }

        .blog-detail-richtext h1,
        .blog-detail-richtext h2,
        .blog-detail-richtext h3,
        .blog-detail-richtext h4,
        .blog-detail-richtext h5,
        .blog-detail-richtext h6 {
            font-family: 'SVN Aptima', 'Open Sans', serif;
            font-weight: 400;
            line-height: 1.25;
            color: #fff;
            margin: 1.25rem 0 .8rem;
        }

        .blog-detail-richtext img {
            display: block;
            max-width: min(100%, 920px);
            height: auto;
            margin: 18px auto;
            border: 1px solid rgba(255, 255, 255, .14);
        }

        .blog-detail-richtext a {
            color: #f1b45a;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .blog-detail-empty {
            margin-top: 16px;
            color: rgba(255, 255, 255, .72);
            font-style: italic;
            text-align: center;
        }

        .blog-related-main {
            width: min(1040px, calc(100% - 40px));
            margin: 0 auto;
        }

        @media (max-width: 820px) {
            .blog-detail-main {
                padding-top: 88px;
            }

            .blog-article-wrap,
            .blog-related-main {
                width: calc(100% - 20px);
            }

            .blog-article-card {
                padding: 20px 16px;
            }

            .blog-article-title {
                font-size: 1.52rem;
                line-height: 1.2;
            }

            .blog-article-meta,
            .blog-article-excerpt,
            .blog-detail-richtext p,
            .blog-detail-richtext li,
            .blog-detail-richtext blockquote,
            .blog-detail-richtext pre,
            .blog-detail-richtext td,
            .blog-detail-richtext th {
                font-size: .94rem;
                line-height: 1.72;
            }

            .blog-detail-cover {
                max-height: none;
                aspect-ratio: 16 / 10;
            }

            .blog-detail-richtext img {
                margin: 12px auto;
            }
        }

        @media (max-width: 480px) {
            .blog-detail-main {
                padding-top: 78px;
            }

            .blog-article-card {
                padding: 18px 14px;
            }

            .blog-article-title {
                font-size: 1.32rem;
            }

            .blog-article-project {
                width: 100%;
                justify-content: center;
                text-align: center;
                line-height: 1.35;
                padding-block: 5px;
            }
        }
    </style>
@endpush

@php
    $cleanSchema = function ($value) use (&$cleanSchema) {
        if (is_array($value)) {
            $cleaned = [];

            foreach ($value as $key => $nestedValue) {
                $nestedValue = $cleanSchema($nestedValue);

                if (is_null($nestedValue) || $nestedValue === '' || $nestedValue === []) {
                    continue;
                }

                $cleaned[$key] = $nestedValue;
            }

            return $cleaned;
        }

        return $value;
    };

    $toAbsoluteSchemaUrl = function (?string $raw, ?string $fallback = null) {
        $value = trim((string) $raw);
        if ($value === '') {
            $value = trim((string) $fallback);
        }

        if ($value === '') {
            return null;
        }

        if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://'])) {
            return $value;
        }

        return url('/' . ltrim($value, '/'));
    };

    $blogSchemaImage = $toAbsoluteSchemaUrl($blog->thumbnail_image, '/theme/assets/hovi/gallery/hovi-036.jpg');
    $blogPublisherLogo = $toAbsoluteSchemaUrl(data_get($siteSettings ?? [], 'header_logo', '/theme/logohome.png'));
    $blogPublishedDate = $blog->published_at ?: $blog->created_at;
    $blogUrl = route('site.blog.show', ['slug' => $blog->slug]);
    $blogImageObject = !empty($blogSchemaImage)
        ? [
            '@context' => 'https://schema.org',
            '@type' => 'ImageObject',
            '@id' => $blogUrl . '#primaryimage',
            'url' => $blogSchemaImage,
            'contentUrl' => $blogSchemaImage,
            'caption' => $blog->title,
        ]
        : null;

    $blogBreadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            [
                '@type' => 'ListItem',
                'position' => 1,
                'name' => 'Trang chu',
                'item' => url('/'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 2,
                'name' => 'Blog',
                'item' => route('site.blog.index'),
            ],
            [
                '@type' => 'ListItem',
                'position' => 3,
                'name' => $blog->title,
                'item' => $blogUrl,
            ],
        ],
    ];

    $blogArticleSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $blogUrl,
        ],
        'headline' => $blog->title,
        'description' => trim((string) ($blog->seo_description ?: $blog->excerpt ?: $blog->title)),
        'image' => !empty($blogImageObject) ? [$blogImageObject] : null,
        'thumbnailUrl' => $blogSchemaImage,
        'datePublished' => $blogPublishedDate ? $blogPublishedDate->toAtomString() : null,
        'dateModified' => $blog->updated_at ? $blog->updated_at->toAtomString() : null,
        'author' => [
            '@type' => 'Organization',
            'name' => data_get($siteSettings ?? [], 'site_name', 'HOVI Việt Nam'),
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => data_get($siteSettings ?? [], 'site_name', 'HOVI Việt Nam'),
            'logo' => !empty($blogPublisherLogo)
                ? [
                    '@type' => 'ImageObject',
                    'url' => $blogPublisherLogo,
                ]
                : null,
        ],
        'articleSection' => data_get($blog, 'project.name'),
    ];

    $blogStructuredData = $cleanSchema([
        $blogBreadcrumbSchema,
        $blogArticleSchema,
        $blogImageObject,
    ]);
@endphp

@push('structured_data')
    <script type="application/ld+json">{!! json_encode($blogStructuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
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

        $rawBlogContent = (string) ($blog->content ?? '');
        $blogContentHasHtml = strip_tags($rawBlogContent) !== $rawBlogContent;

        $contactPhoneDigits = preg_replace('/\D+/', '', (string) ($siteSettings['footer_phone'] ?? ''));
        $contactPhoneHref = 'tel:' . ($contactPhoneDigits !== '' ? $contactPhoneDigits : '0988991635');
    @endphp

    <main class="blog-detail-main">
        <section class="blog-article-wrap" id="tong-quan">
            <article class="blog-article-card">
                <h1 class="blog-article-title">{{ $blog->title }}</h1>
                <p class="blog-article-meta">
                    Admin HOVI &nbsp;|&nbsp;
                    {{ optional($blog->published_at)->format('d/m/Y') ?: optional($blog->created_at)->format('d/m/Y') }}
                </p>

                @if ($blog->project)
                    <p class="blog-article-project-wrap">
                        <span class="blog-article-project">Dự án liên quan: {{ $blog->project->name }}</span>
                    </p>
                @endif

                <img class="blog-detail-cover" src="{{ $resolveImage($blog->thumbnail_image) }}" alt="{{ $blog->title }}"
                    loading="eager" fetchpriority="high" decoding="async">

                @if (!empty($blog->excerpt))
                    <p class="blog-article-excerpt">{{ $blog->excerpt }}</p>
                @endif

                @if (!empty(trim($rawBlogContent)))
                    <div class="blog-detail-richtext">
                        {!! $blogContentHasHtml ? $rawBlogContent : nl2br(e($rawBlogContent)) !!}
                    </div>
                @else
                    <p class="blog-detail-empty">Nội dung bài viết đang được cập nhật.</p>
                @endif
            </article>
        </section>

        <section class="detail-contact">
            <div class="detail-contact__inner">
                <div>
                    <p class="eyebrow">Liên hệ HOVI VIỆT NAM</p>
                    <h2>Đặt lịch hẹn tư vấn thiết kế</h2>
                    <p class="detail-lead">
                        Gửi nhu cầu để đội ngũ tư vấn liên hệ lại, hoặc gọi trực tiếp để trao đổi nhanh về công trình của bạn.
                    </p>
                </div>
                <form class="contact-form detail-contact-form" action="{{ route('site.contact.submit', [], false) }}" method="post">
                    @csrf
                    <input type="hidden" name="source_page" value="{{ $blog->slug }}">
                    <input type="text" name="name" autocomplete="name" placeholder="Họ tên*" required>
                    <input type="tel" name="phone" autocomplete="tel" placeholder="Số điện thoại*" required>
                    <input type="email" name="email" autocomplete="email" placeholder="Email*">
                    <textarea name="message" rows="6" placeholder="Nội dung*" required></textarea>
                    <div class="contact-actions">
                        <button type="submit" class="contact-submit">ĐẶT LỊCH</button>
                        <a class="contact-call-btn" href="{{ $contactPhoneHref }}">GỌI ĐIỆN</a>
                    </div>
                </form>
            </div>
        </section>

        @if ($relatedBlogs->isNotEmpty())
            <section class="detail-related blog-related-main">
                <div class="detail-section-heading">
                    <p class="eyebrow">Bài viết liên quan</p>
                    <a class="detail-section-heading__link" href="{{ route('site.blog.index') }}">
                        <h2>Xem thêm bài Blog</h2>
                    </a>
                </div>

                <div class="detail-related__grid">
                    @foreach ($relatedBlogs as $item)
                        <article class="detail-related__card">
                            <a href="{{ route('site.blog.show', ['slug' => $item->slug]) }}">
                                <img src="{{ $resolveImage($item->thumbnail_image) }}" alt="{{ $item->title }}"
                                    loading="lazy" decoding="async">
                                <h3>{{ $item->title }}</h3>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
@endsection
