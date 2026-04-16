@extends('site.layouts.app')

@section('title', $page->seo_title ?? ($siteSettings['seo_default_title'] ?? 'Đăng Ký Dịch Vụ HOVI Việt Nam | Tư Vấn Thiết Kế Thi Công Biệt Thự'))
@section('meta_description',
    $page->seo_description ??
    ($siteSettings['seo_default_description'] ??
        'Đăng ký dịch vụ thiết kế thi công biệt thự cao cấp tại HOVI Việt Nam. Để lại thông tin để đội ngũ tư vấn liên hệ sớm nhất.'))
@section('body_class', 'contact-page')
@section('page_key', $page->page_key ?? 'contact')

@php
    $contactPhone = trim((string) ($siteSettings['footer_phone'] ?? '0988991635'));
    $contactPhone = $contactPhone !== '' ? $contactPhone : '0988991635';
    $contactPhoneDigits = preg_replace('/\D+/', '', $contactPhone);
    $contactPhoneHref = $contactPhoneDigits !== '' ? 'tel:' . $contactPhoneDigits : 'tel:+84988991635';

    $contactEmail = trim((string) ($siteSettings['footer_email'] ?? 'hovivietnam99@gmail.com'));
    $contactEmail = $contactEmail !== '' ? $contactEmail : 'hovivietnam99@gmail.com';
@endphp

@section('content')
    <main class="contact-main">
        <section class="contact-section">
            <div class="contact-grid">
                <div class="contact-form-col">
                    <p class="eyebrow">Dịch vụ HOVI VIỆT NAM</p>
                    <h1 class="contact-page__title">ĐĂNG KÝ DỊCH VỤ THIẾT KẾ THI CÔNG BIỆT THỰ CAO CẤP HOVI</h1>
                    <p class="contact-page__lead">
                        HOVI VIỆT NAM xin chào Quý khách! Cảm ơn Quý khách đã lựa chọn dịch vụ thiết kế và thi công biệt thự cao cấp
                        của chúng tôi. Vui lòng để lại thông tin theo biểu mẫu dưới đây, đội ngũ tư vấn sẽ liên hệ lại trong thời gian
                        sớm nhất để trao đổi định hướng phù hợp với nhu cầu của bạn.
                    </p>
                    <div class="contact-highlights" aria-label="Liên hệ nhanh">
                        <a class="contact-highlight" href="{{ $contactPhoneHref }}">{{ $contactPhone }}</a>
                        <a class="contact-highlight" href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a>
                    </div>

                    <form class="contact-form" action="{{ route('site.contact.submit', [], false) }}" method="post">
                        @csrf
                        <input type="hidden" name="source_page" value="dang-ky-dich-vu">
                        <input type="text" name="name" autocomplete="name" placeholder="Họ và tên*" required>
                        <input type="tel" name="phone" autocomplete="tel" placeholder="Số điện thoại*" required>
                        <input type="email" name="email" autocomplete="email" placeholder="Email*" required>
                        <input type="text" name="service" placeholder="Dịch vụ quan tâm*" required>
                        <textarea name="message" rows="6" placeholder="Nhu cầu chi tiết*" required></textarea>
                        <div class="contact-actions">
                            <button type="submit" class="contact-submit">GỬI THÔNG TIN</button>
                            <a class="contact-call-btn" href="{{ $contactPhoneHref }}" aria-label="Gọi điện">
                                <svg class="phone-contact" height="20" width="20" viewBox="0 0 473.806 473.806" aria-hidden="true">
                                    <path d="M374.456,293.506c-9.7-10.1-21.4-15.5-33.8-15.5c-12.3,0-24.1,5.3-34.2,15.4l-31.6,31.5c-2.6-1.4-5.2-2.7-7.7-4c-3.6-1.8-7-3.5-9.9-5.3c-29.6-18.8-56.5-43.3-82.3-75c-12.5-15.8-20.9-29.1-27-42.6c8.2-7.5,15.8-15.3,23.2-22.8c2.8-2.8,5.6-5.7,8.4-8.5c21-21,21-48.2,0-69.2l-27.3-27.3c-3.1-3.1-6.3-6.3-9.3-9.5c-6-6.2-12.3-12.6-18.8-18.6c-9.7-9.6-21.3-14.7-33.5-14.7s-24,5.1-34,14.7c-0.1,0.1-0.1,0.1-0.2,0.2l-34,34.3c-12.8,12.8-20.1,28.4-21.7,46.5c-2.4,29.2,6.2,56.4,12.8,74.2c16.2,43.7,40.4,84.2,76.5,127.6c43.8,52.3,96.5,93.6,156.7,122.7c23,10.9,53.7,23.8,88,26c2.1,0.1,4.3,0.2,6.3,0.2c23.1,0,42.5-8.3,57.7-24.8c0.1-0.2,0.3-0.3,0.4-0.5c5.2-6.3,11.2-12,17.5-18.1c4.3-4.1,8.7-8.4,13-12.9c9.9-10.3,15.1-22.3,15.1-34.6c0-12.4-5.3-24.3-15.4-34.3L374.456,293.506z" />
                                </svg>
                                <span>GỌI ĐIỆN</span>
                            </a>
                        </div>
                        <p class="contact-form__note">HOVI VIỆT NAM sẽ phản hồi trong thời gian làm việc gần nhất.</p>
                    </form>
                </div>

                <div class="contact-info-col">
                    <div class="contact-info-panel">
                        <p class="eyebrow">Hoặc liên hệ với chúng tôi</p>
                        <h2>Kết nối trực tiếp với đội ngũ HOVI VIỆT NAM</h2>
                        <p class="contact-info-intro">
                            Bạn có thể gọi điện hoặc gửi email cho chúng tôi để được hỗ trợ nhanh chóng về định hướng thiết kế, ngân
                            sách đầu tư và tiến độ triển khai công trình.
                        </p>

                        <article class="contact-info-item">
                            <img src="https://www.hovi.com.vn/wp-content/uploads/2024/02/icon-footer-1.webp"
                                alt="Địa chỉ văn phòng">
                            <div>
                                <h3>Văn phòng</h3>
                                <p>SH2-12, THE MATRIX ONE - Số 1 Lê Quang Đạo, Mễ Trì, Nam Từ Liêm, Hà Nội</p>
                            </div>
                        </article>

                        <article class="contact-info-item">
                            <img src="https://www.hovi.com.vn/wp-content/uploads/2024/02/icon-footer-2.webp" alt="Nhà máy">
                            <div>
                                <h3>Nhà máy</h3>
                                <p>Xâm Dương, Ninh Sở, Thường Tín, Hà Nội</p>
                            </div>
                        </article>

                        <article class="contact-info-item">
                            <img src="https://www.hovi.com.vn/wp-content/uploads/2024/02/icon-footer-3.webp" alt="Email">
                            <div>
                                <h3>Email</h3>
                                <p><a href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
                            </div>
                        </article>

                        <article class="contact-info-item">
                            <img src="https://www.hovi.com.vn/wp-content/uploads/2024/02/icon-footer-4.webp" alt="Số điện thoại">
                            <div>
                                <h3>Hotline tư vấn</h3>
                                <p><a href="{{ $contactPhoneHref }}">{{ $contactPhone }}</a></p>
                            </div>
                        </article>

                        <div class="contact-note">
                            <strong>Thời gian làm việc</strong>
                            <p>Thứ Hai - Thứ Bảy, 08:00 - 18:00. Vui lòng gọi trước nếu bạn muốn đặt lịch gặp trực tiếp tại văn phòng.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
