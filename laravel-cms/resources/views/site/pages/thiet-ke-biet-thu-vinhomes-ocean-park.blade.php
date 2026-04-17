@extends('site.layouts.app')

@section('title', $page->seo_title ?? ($siteSettings['seo_default_title'] ?? 'Thiết Kế Biệt Thự Vinhomes Ocean Park | Dự Án HOVI Việt Nam'))
@section('meta_description',
    $page->seo_description ??
    ($siteSettings['seo_default_description'] ??
        'Tổng hợp các dự án thiết kế biệt thự Vinhomes Ocean Park do HOVI Việt Nam thực hiện, gồm hình ảnh, video thực tế và nội dung tư vấn.'))
@section('body_class', 'contact-page project-category-page')
@section('page_key', 'oceanpark')

@section('content')
    @php
        $projectDetailUrl = url('/biet-thu-don-lap-m07-l14-dtm-duong-noi');

        $featuredProjects = [
            ['image' => 'hovi-023.jpg', 'title' => 'Biệt thự đơn lập Sao Biển Vinhomes Oceanpark 1'],
            ['image' => 'hovi-024.jpg', 'title' => 'BIỆT THỰ ĐƠN LẬP NT17-10 [NỘI THẤT], VINHOMES OCEANPARK'],
            ['image' => 'hovi-025.jpg', 'title' => 'BIỆT THỰ ĐƠN LẬP DD2-02, VINHOMES OCEAN PARK'],
            ['image' => 'hovi-026.jpg', 'title' => 'BIỆT THỰ ĐƠN LẬP NT15-08 [KIẾN TRÚC], VINHOMES OCEAN PARK'],
            ['image' => 'hovi-027.jpg', 'title' => 'BIỆT THỰ ĐƠN LẬP NT15-08 [NỘI THẤT], VINHOMES OCEANPARK'],
            ['image' => 'hovi-028.jpg', 'title' => 'BIỆT THỰ ĐƠN LẬP NT11-34 [NỘI THẤT], VINHOMES OCEAN PARK'],
            ['image' => 'hovi-029.jpg', 'title' => 'Biệt thự đơn lập NT18-01, Vinhomes Ocean Park'],
            ['image' => 'hovi-030.jpg', 'title' => 'Biệt thự đơn lập NT 1-36, Vinhomes Oceanpark'],
            ['image' => 'hovi-031.jpg', 'title' => 'Biệt thự Đơn Lập NT11-34, Vinhomes Ocean Park'],
            ['image' => 'hovi-032.jpg', 'title' => 'Biệt thự Đơn Lập NT17-10 – Vinhomes Ocean Park'],
        ];

        $videos = [
            ['image' => 'hovi-033.jpg', 'title' => 'The Exhibition | Biệt thự đơn lập Ngọc Trai Vinhomes OceanPark 1'],
            ['image' => 'hovi-034.jpg', 'title' => 'Thực chiến bảo vệ thiết kế 3D biệt thự Ngọc Trai Ocean Park 1'],
            ['image' => 'hovi-035.jpg', 'title' => 'Biệt thự đơn lập góc view hồ NT05-02 Vinhomes OceanPark 1'],
        ];

        $blogs = [
            ['image' => 'hovi-036.jpg', 'title' => 'TỔNG HỢP THIẾT KẾ PHÒNG NGỦ MASTER BIỆT THỰ TẠI VINHOMES'],
            ['image' => 'hovi-037.jpg', 'title' => 'Biệt thự đơn lập Ngọc Trai 05-02, bản giao hưởng kiến trúc tại Vinhomes Oceanpark'],
            ['image' => 'hovi-038.jpg', 'title' => 'Top 10 biệt thự đơn lập đẹp nhất tại Vinhomes Ocean Park 1 được thiết kế bởi HOVI VIỆT NAM'],
        ];

        $contactPhoneDigits = preg_replace('/\D+/', '', (string) ($siteSettings['footer_phone'] ?? ''));
        $contactPhoneHref = 'tel:' . ($contactPhoneDigits !== '' ? $contactPhoneDigits : '0988991635');
    @endphp

    <main class="category-main">
        <section class="category-head category-head--lined" id="tong-quan">
            <h1 class="category-title">THIẾT KẾ BIỆT THỰ VINHOMES OCEAN PARK</h1>
        </section>

        <section class="category-section" id="du-an">
            <div class="category-section-heading">
                <h2>Dự án nổi bật</h2>
            </div>
            <div class="category-grid">
                @foreach ($featuredProjects as $item)
                    <article class="category-card">
                        <a href="{{ $projectDetailUrl }}" rel="noreferrer noopener">
                            <img src="/theme/assets/hovi/gallery/{{ $item['image'] }}" alt="{{ $item['title'] }}"
                                loading="{{ $loop->first ? 'eager' : 'lazy' }}" decoding="async"
                                fetchpriority="{{ $loop->first ? 'high' : 'auto' }}">
                            <h3>{{ $item['title'] }}</h3>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="category-section" id="video">
            <div class="category-section-heading category-section-heading--lined">
                <h2>Video thực tế</h2>
            </div>
            <div class="category-grid category-grid--media">
                @foreach ($videos as $item)
                    <article class="category-card category-card--video">
                        <a href="{{ $projectDetailUrl }}" rel="noreferrer noopener">
                            <img src="/theme/assets/hovi/gallery/{{ $item['image'] }}" alt="{{ $item['title'] }}"
                                loading="lazy" decoding="async">
                            <h3>{{ $item['title'] }}</h3>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="category-section category-section--blog" id="blog">
            <div class="category-section-heading category-section-heading--lined">
                <h2>Blog</h2>
            </div>
            <p class="category-intro">
                Vinhomes Ocean Park không chỉ nổi tiếng với môi trường sống đẳng cấp mà còn có sức hút lớn bởi những thiết kế
                biệt thự độc đáo, phù hợp với xu hướng kiến trúc thức thời. Trong bài viết này, HOVI VIỆT NAM giới thiệu các mẫu
                thiết kế thịnh hành nhất để bạn dễ dàng tìm cảm hứng cho không gian sống lý tưởng.
            </p>
            <div class="category-grid category-grid--media">
                @foreach ($blogs as $item)
                    <article class="category-card category-card--blog">
                        <a href="{{ $projectDetailUrl }}" rel="noreferrer noopener">
                            <img src="/theme/assets/hovi/gallery/{{ $item['image'] }}" alt="{{ $item['title'] }}"
                                loading="lazy" decoding="async">
                            <h3>{{ $item['title'] }}</h3>
                        </a>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="detail-contact" id="lien-he-tu-van">
            <div class="detail-contact__inner">
                <div>
                    <p class="eyebrow">Đăng ký tư vấn miễn phí</p>
                    <h2>Để lại thông tin để HOVI VIỆT NAM liên hệ nhanh</h2>
                    <p class="detail-lead">
                        Chia sẻ loại công trình và nhu cầu thiết kế/thi công, đội ngũ HOVI VIỆT NAM sẽ liên hệ tư vấn giải pháp phù hợp
                        trong thời gian sớm nhất.
                    </p>
                </div>
                <form class="contact-form detail-contact-form" action="{{ route('site.contact.submit', [], false) }}" method="post">
                    @csrf
                    <input type="hidden" name="source_page" value="thiet-ke-biet-thu-vinhomes-ocean-park">
                    <input type="text" name="name" autocomplete="name" placeholder="Tên của Anh/Chị*" required>
                    <input type="tel" name="phone" autocomplete="tel" placeholder="Số điện thoại*" required>
                    <input type="text" name="service" placeholder="Loại công trình*" required>
                    <textarea name="message" rows="6" placeholder="Mô tả sơ bộ về công trình Anh/Chị mong muốn" required></textarea>
                    <div class="contact-actions">
                        <button type="submit" class="contact-submit">GỬI THÔNG TIN</button>
                        <a class="contact-call-btn" href="{{ $contactPhoneHref }}">GỌI ĐIỆN</a>
                    </div>
                </form>
            </div>
        </section>
    </main>
@endsection
