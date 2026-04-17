@extends('site.layouts.app')

@section('title', $video->seo_title ?: ($video->title . ' | Video HOVI Việt Nam'))
@section('meta_description',
    $video->seo_description ?: \Illuminate\Support\Str::limit(strip_tags((string) $video->description), 160))
@section('body_class', 'contact-page project-detail-page')
@section('page_key', 'video')

@push('head')
    <style>
        .video-detail-main {
            min-height: 100vh;
            padding-top: 92px;
            background:
                radial-gradient(circle at 14% 10%, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0) 30%),
                linear-gradient(180deg, rgba(0, 0, 0, 0.78), rgba(0, 0, 0, 0.95)),
                url('/theme/assets/hovi/gallery/hovi-059.jpg') center top / cover no-repeat;
        }

        .video-article-wrap {
            width: min(1040px, calc(100% - 40px));
            margin: 0 auto;
            padding-bottom: 26px;
        }

        .video-article-card {
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: linear-gradient(180deg, rgba(7, 7, 7, 0.74), rgba(0, 0, 0, 0.92));
            box-shadow: 0 18px 44px rgba(0, 0, 0, .3);
            padding: clamp(20px, 3vw, 34px);
        }

        .video-article-title {
            margin: 0;
            text-align: center;
            font-family: 'SVN Aptima', serif;
            font-weight: 400;
            font-size: clamp(1.86rem, 2.9vw, 2.9rem);
            line-height: 1.1;
            text-wrap: balance;
        }

        .video-article-meta {
            margin: 10px 0 0;
            text-align: center;
            color: rgba(255, 255, 255, .7);
            font-size: .88rem;
        }

        .video-article-project {
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

        .video-article-project-wrap {
            text-align: center;
        }

        .video-detail-cover {
            width: 100%;
            margin-top: 20px;
            max-height: 560px;
            object-fit: cover;
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 14px 36px rgba(0, 0, 0, .28);
        }

        .video-article-excerpt {
            margin: 16px auto 0;
            max-width: 90ch;
            color: rgba(255, 255, 255, .82);
            line-height: 1.8;
            font-size: .98rem;
            text-align: center;
        }

        .video-player {
            margin: 20px auto 0;
            width: min(920px, 100%);
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(0, 0, 0, 0.45);
            box-shadow: 0 16px 34px rgba(0, 0, 0, .3);
            aspect-ratio: 16/9;
            overflow: hidden;
        }

        .video-player iframe,
        .video-player video {
            width: 100%;
            height: 100%;
            border: 0;
            display: block;
            object-fit: cover;
            background: #000;
        }

        .video-player__fallback {
            margin-top: 16px;
            text-align: center;
        }

        .video-player__link {
            display: inline-flex;
            align-items: center;
            min-height: 40px;
            padding: 0 16px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, .34);
            color: #fff;
            text-decoration: none;
            font-size: .9rem;
            letter-spacing: .03em;
            transition: background-color .2s ease, border-color .2s ease;
        }

        .video-player__link:hover {
            background: rgba(255, 255, 255, 0.12);
            border-color: rgba(255, 255, 255, .6);
        }

        .video-detail-richtext {
            margin-top: 18px;
            color: rgba(255, 255, 255, .86);
        }

        .video-detail-richtext,
        .video-detail-richtext * {
            font-family: 'Open Sans', Arial, sans-serif;
        }

        .video-detail-richtext p,
        .video-detail-richtext li,
        .video-detail-richtext blockquote,
        .video-detail-richtext pre,
        .video-detail-richtext td,
        .video-detail-richtext th {
            color: rgba(255, 255, 255, .86);
            font-size: 1rem;
            line-height: 1.85;
        }

        .video-detail-richtext h1,
        .video-detail-richtext h2,
        .video-detail-richtext h3,
        .video-detail-richtext h4,
        .video-detail-richtext h5,
        .video-detail-richtext h6 {
            font-family: 'SVN Aptima', 'Open Sans', serif;
            font-weight: 400;
            line-height: 1.25;
            color: #fff;
            margin: 1.25rem 0 .8rem;
        }

        .video-detail-richtext img {
            display: block;
            max-width: min(100%, 920px);
            height: auto;
            margin: 18px auto;
            border: 1px solid rgba(255, 255, 255, .14);
        }

        .video-detail-richtext a {
            color: #f1b45a;
            text-decoration: underline;
            text-underline-offset: 2px;
        }

        .video-detail-empty {
            margin-top: 16px;
            color: rgba(255, 255, 255, .72);
            font-style: italic;
            text-align: center;
        }

        .video-related-main {
            width: min(1040px, calc(100% - 40px));
            margin: 0 auto;
        }
    </style>
@endpush

@php
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

    $videoSchemaThumbnail = $toAbsoluteSchemaUrl($video->thumbnail_image, '/theme/assets/hovi/gallery/hovi-034.jpg');
    $videoPublisherLogo = $toAbsoluteSchemaUrl(data_get($siteSettings ?? [], 'header_logo', '/theme/logohome.png'));
    $videoSourceUrl = $toAbsoluteSchemaUrl($video->video_url);
    $videoPublishedDate = $video->published_at ?: $video->created_at;

    $videoSchemaDescription = trim((string) ($video->seo_description ?: $video->description ?: $video->title));
    $videoStructuredData = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'VideoObject',
        'name' => $video->title,
        'description' => $videoSchemaDescription,
        'thumbnailUrl' => !empty($videoSchemaThumbnail) ? [$videoSchemaThumbnail] : null,
        'uploadDate' => $videoPublishedDate ? $videoPublishedDate->toAtomString() : null,
        'dateModified' => $video->updated_at ? $video->updated_at->toAtomString() : null,
        'contentUrl' => $videoSourceUrl,
        'embedUrl' => $videoSourceUrl,
        'url' => route('site.video.show', ['slug' => $video->slug]),
        'publisher' => [
            '@type' => 'Organization',
            'name' => data_get($siteSettings ?? [], 'site_name', 'HOVI Việt Nam'),
            'logo' => !empty($videoPublisherLogo)
                ? [
                    '@type' => 'ImageObject',
                    'url' => $videoPublisherLogo,
                ]
                : null,
        ],
    ], function ($value) {
        return !is_null($value) && $value !== '' && $value !== [];
    });
@endphp

@push('structured_data')
    <script type="application/ld+json">{!! json_encode($videoStructuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
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

        $resolveLink = function ($raw) {
            $value = trim((string) $raw);
            if ($value === '') {
                return null;
            }

            if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://', '/', '#', 'mailto:', 'tel:'])) {
                return $value;
            }

            return url('/' . ltrim($value, '/'));
        };

        $rawVideoContent = (string) ($video->content ?? '');
        $videoContentHasHtml = strip_tags($rawVideoContent) !== $rawVideoContent;

        $videoUrl = trim((string) ($video->video_url ?? ''));
        $resolvedVideoUrl = $resolveLink($videoUrl);
        $embedUrl = null;
        $videoFileUrl = null;

        $contactPhoneDigits = preg_replace('/\D+/', '', (string) ($siteSettings['footer_phone'] ?? ''));
        $contactPhoneHref = 'tel:' . ($contactPhoneDigits !== '' ? $contactPhoneDigits : '0988991635');

        if ($resolvedVideoUrl) {
            if (preg_match('~(?:youtube\\.com/watch\\?v=|youtu\\.be/)([A-Za-z0-9_-]{6,})~i', $resolvedVideoUrl, $matches)) {
                $embedUrl = 'https://www.youtube.com/embed/' . $matches[1];
            } elseif (preg_match('~vimeo\\.com/(?:video/)?([0-9]{5,})~i', $resolvedVideoUrl, $matches)) {
                $embedUrl = 'https://player.vimeo.com/video/' . $matches[1];
            } elseif (preg_match('/\\.(mp4|webm|ogg)(\\?.*)?$/i', $resolvedVideoUrl)) {
                $videoFileUrl = $resolvedVideoUrl;
            }
        }
    @endphp

    <main class="video-detail-main">
        <section class="video-article-wrap" id="tong-quan">
            <article class="video-article-card">
                <h1 class="video-article-title">{{ $video->title }}</h1>
                <p class="video-article-meta">
                    Admin HOVI &nbsp;|&nbsp;
                    {{ optional($video->published_at)->format('d/m/Y') ?: optional($video->created_at)->format('d/m/Y') }}
                </p>

                @if ($video->project)
                    <p class="video-article-project-wrap">
                        <span class="video-article-project">Dự án liên quan: {{ $video->project->name }}</span>
                    </p>
                @endif

                <img class="video-detail-cover" src="{{ $resolveImage($video->thumbnail_image) }}" alt="{{ $video->title }}"
                    loading="eager" fetchpriority="high" decoding="async">

                @if (!empty($video->description))
                    <p class="video-article-excerpt">{{ $video->description }}</p>
                @endif

                @if (!empty($embedUrl))
                    <div class="video-player">
                        <iframe src="{{ $embedUrl }}" title="{{ $video->title }}" allowfullscreen
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                    </div>
                @elseif (!empty($videoFileUrl))
                    <div class="video-player">
                        <video controls preload="metadata">
                            <source src="{{ $videoFileUrl }}">
                        </video>
                    </div>
                @elseif (!empty($resolvedVideoUrl))
                    <div class="video-player__fallback">
                        <a class="video-player__link" href="{{ $resolvedVideoUrl }}" target="_blank" rel="noreferrer noopener">
                            Mở video gốc
                        </a>
                    </div>
                @endif

                @if (!empty(trim($rawVideoContent)))
                    <div class="video-detail-richtext">
                        {!! $videoContentHasHtml ? $rawVideoContent : nl2br(e($rawVideoContent)) !!}
                    </div>
                @else
                    <p class="video-detail-empty">Nội dung video đang được cập nhật.</p>
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
                    <input type="hidden" name="source_page" value="video/{{ $video->slug }}">
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

        @if ($relatedVideos->isNotEmpty())
            <section class="detail-related video-related-main">
                <div class="detail-section-heading">
                    <p class="eyebrow">Video liên quan</p>
                    <a class="detail-section-heading__link" href="{{ route('site.video.index') }}">
                        <h2>Xem thêm video</h2>
                    </a>
                </div>

                <div class="detail-related__grid">
                    @foreach ($relatedVideos as $item)
                        <article class="detail-related__card">
                            <a href="{{ !empty($item->slug) ? route('site.video.show', ['slug' => $item->slug]) : route('site.video.index') }}">
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
