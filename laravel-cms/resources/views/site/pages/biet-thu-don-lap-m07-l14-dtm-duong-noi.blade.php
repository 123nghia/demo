@extends('site.layouts.app')

@section('title', $page->seo_title ?? 'Biệt Thự Đơn Lập M07-L14 ĐTM Dương Nội | Dự Án HOVI Việt Nam')
@section('meta_description',
    $page->seo_description ??
    'Chi tiết dự án biệt thự đơn lập M07-L14 ĐTM Dương Nội của HOVI Việt Nam với hình ảnh phối cảnh và giải pháp thiết kế.')
@section('body_class', 'contact-page project-detail-page')
@section('page_key', 'project')

@section('content')
    @php
        $galleryImages = [
            'hovi-035.jpg',
            'hovi-036.jpg',
            'hovi-037.jpg',
            'hovi-038.jpg',
            'hovi-039.jpg',
            'hovi-040.jpg',
            'hovi-041.jpg',
            'hovi-042.jpg',
            'hovi-043.jpg',
            'hovi-044.jpg',
            'hovi-045.jpg',
            'hovi-046.jpg',
            'hovi-047.jpg',
            'hovi-048.jpg',
            'hovi-049.jpg',
            'hovi-050.jpg',
            'hovi-051.jpg',
            'hovi-052.jpg',
            'hovi-053.jpg',
            'hovi-054.jpg',
            'hovi-055.jpg',
            'hovi-056.jpg',
            'hovi-057.jpg',
            'hovi-058.jpg',
            'hovi-059.jpg',
            'hovi-060.jpg',
            'hovi-061.png',
            'hovi-062.png',
            'hovi-063.jpg',
            'hovi-064.jpg',
            'hovi-065.jpg',
            'hovi-066.png',
            'hovi-067.png',
            'hovi-068.png',
            'hovi-069.png',
            'hovi-070.png',
            'hovi-071.png',
            'hovi-001.jpg',
            'hovi-002.jpg',
            'hovi-003.jpg',
            'hovi-004.jpg',
            'hovi-005.jpg',
            'hovi-006.jpg',
            'hovi-007.jpg',
            'hovi-008.jpg',
            'hovi-009.jpg',
            'hovi-010.jpg',
            'hovi-011.jpg',
            'hovi-012.jpg',
            'hovi-013.jpg',
            'hovi-014.jpg',
            'hovi-015.jpg',
            'hovi-016.jpg',
            'hovi-017.jpg',
            'hovi-018.jpg',
            'hovi-019.jpg',
            'hovi-020.jpg',
            'hovi-021.jpg',
            'hovi-022.jpg',
        ];

        $relatedProjects = [
            ['image' => 'hovi-023.jpg', 'title' => 'Biệt thự đơn lập DD2-02, Vinhomes Ocean Park'],
            ['image' => 'hovi-024.jpg', 'title' => 'Biệt thự đơn lập K5, KĐT Starlake'],
            ['image' => 'hovi-025.jpg', 'title' => 'Biệt thự song lập Starlake Khu H'],
        ];
    @endphp

    <main class="detail-main">
        <section class="detail-article-head" id="tong-quan">
            <h1 class="detail-title">Biệt thự đơn lập M07-L14, ĐTM Dương Nội</h1>
        </section>

        <section class="detail-gallery-section" id="gallery">
            <div class="detail-gallery">
                @foreach ($galleryImages as $index => $image)
                    <figure class="detail-gallery__item">
                        <img src="/theme/assets/hovi/gallery/{{ $image }}"
                            alt="Phối cảnh dự án M07-L14 số {{ $index + 1 }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                    </figure>
                @endforeach
            </div>
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
                    <input type="hidden" name="source_page" value="biet-thu-don-lap-m07-l14-dtm-duong-noi">
                    <input type="text" name="name" autocomplete="name" placeholder="Họ tên*" required>
                    <input type="tel" name="phone" autocomplete="tel" placeholder="Số điện thoại*" required>
                    <input type="email" name="email" autocomplete="email" placeholder="Email*" required>
                    <textarea name="message" rows="6" placeholder="Nội dung*" required></textarea>
                    <div class="contact-actions">
                        <button type="submit" class="contact-submit">ĐẶT LỊCH</button>
                        <a class="contact-call-btn" href="tel:0988991635">GỌI ĐIỆN</a>
                    </div>
                </form>
            </div>
        </section>

        <section class="detail-related">
            <div class="detail-section-heading">
                <p class="eyebrow">Dự án liên quan</p>
                <a class="detail-section-heading__link" href="javascript:void(0)" rel="noreferrer noopener">
                    <h2>Xem thêm công trình cùng nhóm</h2>
                </a>
            </div>
            <div class="detail-related__grid">
                @foreach ($relatedProjects as $item)
                    <article class="detail-related__card">
                        <a href="{{ url('/biet-thu-don-lap-m07-l14-dtm-duong-noi') }}" rel="noreferrer noopener">
                            <img src="/theme/assets/hovi/gallery/{{ $item['image'] }}" alt="{{ $item['title'] }}">
                            <h3>{{ $item['title'] }}</h3>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>
    </main>
@endsection
