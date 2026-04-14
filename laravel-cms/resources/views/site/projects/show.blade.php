@extends('site.layouts.app')

@section('title', $project->seo_title ?? ($project->name . ' | Dự án HOVI Việt Nam'))
@section('meta_description',
    $project->seo_description ??
    \Illuminate\Support\Str::limit(strip_tags((string) $project->short_description), 160))
@section('body_class', 'contact-page project-category-page')
@section('page_key', 'project')

@section('content')
    @php
        $details = $project->detailPages ?? collect();
        $blogs = $project->blogs ?? collect();
        $videos = $project->videos ?? collect();
        $rawProjectIntro = (string) ($project->intro ?? '');
        $projectIntroHasHtml = strip_tags($rawProjectIntro) !== $rawProjectIntro;

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

        $resolveLink = function ($raw) {
            $value = trim((string) $raw);
            if ($value === '') {
                return '#';
            }

            if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://', '/', '#', 'mailto:', 'tel:'])) {
                return $value;
            }

            return url('/' . ltrim($value, '/'));
        };
    @endphp

    <main class="category-main">
        <section class="category-head category-head--lined" id="tong-quan">
            <h1 class="category-title">{{ $project->name }}</h1>
            @if (!empty($project->short_description))
                <p class="category-intro mt-3 mb-0">{{ $project->short_description }}</p>
            @endif
        </section>

        @if (!empty($project->intro))
            <section class="category-section pt-2">
                <div class="category-intro editor-render-content">
                    {!! $projectIntroHasHtml ? $rawProjectIntro : nl2br(e($rawProjectIntro)) !!}
                </div>
            </section>
        @endif

        <section class="category-section" id="du-an">
            <div class="category-section-heading">
                <h2>Dự án nổi bật</h2>
            </div>
            <div class="category-grid">
                @forelse ($details as $detailPage)
                    <article class="category-card">
                        <a href="{{ url('/' . $detailPage->slug) }}" rel="noreferrer noopener">
                            <img src="{{ $resolveImage($detailPage->thumbnail_image, $resolveImage($project->cover_image)) }}"
                                alt="{{ $detailPage->title }}">
                            <h3>{{ $detailPage->title }}</h3>
                        </a>
                    </article>
                @empty
                    <p class="text-light-50">Chưa có trang chi tiết nào cho dự án này.</p>
                @endforelse
            </div>
        </section>

        @if ($videos->isNotEmpty())
            <section class="category-section" id="video">
                <div class="category-section-heading category-section-heading--lined">
                    <h2>Video thực tế</h2>
                </div>
                <div class="category-grid category-grid--media">
                    @foreach ($videos as $video)
                        <article class="category-card category-card--video">
                            <a href="{{ $resolveLink($video->video_url) }}" rel="noreferrer noopener" target="_blank">
                                <img src="{{ $resolveImage($video->thumbnail_image, $resolveImage($project->cover_image)) }}"
                                    alt="{{ $video->title }}">
                                <h3>{{ $video->title }}</h3>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if ($blogs->isNotEmpty())
            <section class="category-section category-section--blog" id="blog">
                <div class="category-section-heading category-section-heading--lined">
                    <h2>Blog</h2>
                </div>
                @if (!empty($project->short_description))
                    <p class="category-intro">{{ $project->short_description }}</p>
                @endif
                <div class="category-grid category-grid--media">
                    @foreach ($blogs as $blog)
                        <article class="category-card category-card--blog">
                            <a href="{{ $resolveLink($blog->target_url) }}" rel="noreferrer noopener" target="_blank">
                                <img src="{{ $resolveImage($blog->thumbnail_image, $resolveImage($project->cover_image)) }}"
                                    alt="{{ $blog->title }}">
                                <h3>{{ $blog->title }}</h3>
                            </a>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        <section class="detail-contact" id="lien-he-tu-van">
            <div class="detail-contact__inner">
                <div>
                    <p class="eyebrow">Đăng ký tư vấn miễn phí</p>
                    <h2>Để lại thông tin để HOVI VIỆT NAM liên hệ nhanh</h2>
                    <p class="detail-lead">
                        Chia sẻ nhu cầu công trình, đội ngũ HOVI VIỆT NAM sẽ liên hệ và tư vấn giải pháp phù hợp trong thời gian sớm nhất.
                    </p>
                </div>
                <form class="contact-form detail-contact-form" action="{{ route('site.contact.submit') }}" method="post">
                    @csrf
                    <input type="hidden" name="source_page" value="{{ $project->slug }}">
                    <input type="text" name="name" autocomplete="name" placeholder="Tên của Anh/Chị*" required>
                    <input type="tel" name="phone" autocomplete="tel" placeholder="Số điện thoại*" required>
                    <input type="text" name="service" placeholder="Loại công trình*" required>
                    <textarea name="message" rows="6" placeholder="Mô tả sơ bộ về công trình Anh/Chị mong muốn" required></textarea>
                    <div class="contact-actions">
                        <button type="submit" class="contact-submit">GỬI THÔNG TIN</button>
                        <a class="contact-call-btn" href="tel:0988991635">GỌI ĐIỆN</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
