@extends('site.layouts.app')

@section('title', $detailPage->title . ' | ' . $project->name . ' | HOVI Việt Nam')
@section('meta_description',
    \Illuminate\Support\Str::limit(strip_tags((string) ($detailPage->summary ?: $project->short_description)), 160))
@section('body_class', 'contact-page project-detail-page')
@section('page_key', 'project')

@section('content')
    @php
        $galleryImages = collect($detailPage->gallery_images ?? [])->values();
        $relatedDetails = collect($project->detailPages ?? [])->where('id', '!=', $detailPage->id)->take(6)->values();
        $rawDetailContent = (string) ($detailPage->content ?? '');
        $detailContentHasHtml = strip_tags($rawDetailContent) !== $rawDetailContent;

        $resolveImage = function ($raw, $fallback = '') {
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
            <h1 class="detail-title">{{ $detailPage->title }}</h1>
            @if (!empty($detailPage->summary))
                <p class="detail-lead mt-3 mb-0">{{ $detailPage->summary }}</p>
            @endif
        </section>

        @if ($galleryImages->isNotEmpty())
            <section class="detail-gallery-section" id="gallery">
                <div class="detail-gallery">
                    @foreach ($galleryImages as $index => $image)
                        <figure class="detail-gallery__item">
                            <img src="{{ $resolveImage($image, $resolveImage($detailPage->thumbnail_image, $resolveImage($project->cover_image))) }}"
                                alt="{{ $detailPage->title }} - ảnh {{ $index + 1 }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                        </figure>
                    @endforeach
                </div>
            </section>
        @endif

        @if (!empty($detailPage->content))
            <section class="category-section pt-2">
                <div class="category-intro editor-render-content">
                    {!! $detailContentHasHtml ? $rawDetailContent : nl2br(e($rawDetailContent)) !!}
                </div>
            </section>
        @endif

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
                    <input type="hidden" name="source_page" value="{{ $detailPage->slug }}">
                    <input type="text" name="name" autocomplete="name" placeholder="Họ tên*" required>
                    <input type="tel" name="phone" autocomplete="tel" placeholder="Số điện thoại*" required>
                    <input type="email" name="email" autocomplete="email" placeholder="Email*">
                    <textarea name="message" rows="6" placeholder="Nội dung*" required></textarea>
                    <div class="contact-actions">
                        <button type="submit" class="contact-submit">ĐẶT LỊCH</button>
                        <a class="contact-call-btn" href="tel:0988991635">GỌI ĐIỆN</a>
                    </div>
                </form>
            </div>
        </section>

        @if ($relatedDetails->isNotEmpty())
            <section class="detail-related">
                <div class="detail-section-heading">
                    <p class="eyebrow">Dự án liên quan</p>
                    <a class="detail-section-heading__link" href="{{ url('/' . $project->slug) }}" rel="noreferrer noopener">
                        <h2>Xem thêm công trình cùng nhóm</h2>
                    </a>
                </div>
                <div class="detail-related__grid">
                    @foreach ($relatedDetails as $item)
                        <article class="detail-related__card">
                            <a href="{{ url('/' . $item->slug) }}" rel="noreferrer noopener">
                                <img src="{{ $resolveImage($item->thumbnail_image, $resolveImage($project->cover_image)) }}"
                                    alt="{{ $item->title }}" loading="lazy" decoding="async">
                                <h3>{{ $item->title }}</h3>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>
@endsection
