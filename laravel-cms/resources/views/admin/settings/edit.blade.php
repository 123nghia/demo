@extends('admin.layout')

@section('title', 'SEO, Footer & Logo | HOVI CMS')

@section('content')
    @php
        $settings = $settings ?? [];
        $value = function (string $key, string $default = '') use ($settings) {
            return old($key, $settings[$key] ?? $default);
        };
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">SEO, Footer, Logo & Mạng xã hội</h1>
            <p class="text-muted mb-0">Quản lý cấu hình toàn site để đảm bảo chuẩn SEO và nhất quán thương hiệu.</p>
        </div>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="post" enctype="multipart/form-data" class="d-grid gap-4">
        @csrf
        @method('PUT')

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">1) Thương hiệu & logo</div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="site_name">Tên website</label>
                    <input class="form-control" id="site_name" name="site_name" type="text"
                        value="{{ $value('site_name') }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="site_tagline">Tagline</label>
                    <input class="form-control" id="site_tagline" name="site_tagline" type="text"
                        value="{{ $value('site_tagline') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="header_logo">Logo header (đường dẫn)</label>
                    <input class="form-control" id="header_logo" name="header_logo" type="text"
                        value="{{ $value('header_logo') }}" placeholder="/uploads/settings/logo-header.png">
                    <div class="form-text">Có thể nhập đường dẫn nội bộ hoặc URL tuyệt đối.</div>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="header_logo_file">Upload logo header</label>
                    <input class="form-control" id="header_logo_file" name="header_logo_file" type="file"
                        accept="image/*">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="footer_logo">Logo footer (đường dẫn)</label>
                    <input class="form-control" id="footer_logo" name="footer_logo" type="text"
                        value="{{ $value('footer_logo') }}" placeholder="/uploads/settings/logo-footer.png">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="footer_logo_file">Upload logo footer</label>
                    <input class="form-control" id="footer_logo_file" name="footer_logo_file" type="file"
                        accept="image/*">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="favicon">Favicon (đường dẫn)</label>
                    <input class="form-control" id="favicon" name="favicon" type="text"
                        value="{{ $value('favicon') }}" placeholder="/uploads/settings/favicon.png">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="favicon_file">Upload favicon</label>
                    <input class="form-control" id="favicon_file" name="favicon_file" type="file" accept="image/*">
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">2) Thiết lập SEO chuẩn</div>
            <div class="card-body row g-3">
                <div class="col-md-8">
                    <label class="form-label" for="seo_default_title">SEO title mặc định</label>
                    <input class="form-control" id="seo_default_title" name="seo_default_title" type="text"
                        value="{{ $value('seo_default_title') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="seo_robots">Robots</label>
                    <input class="form-control" id="seo_robots" name="seo_robots" type="text"
                        value="{{ $value('seo_robots') }}" required>
                </div>

                <div class="col-12">
                    <label class="form-label" for="seo_default_description">SEO description mặc định</label>
                    <textarea class="form-control" id="seo_default_description" name="seo_default_description" rows="3" required>{{ $value('seo_default_description') }}</textarea>
                </div>

                <div class="col-12">
                    <label class="form-label" for="seo_keywords">Meta keywords</label>
                    <input class="form-control" id="seo_keywords" name="seo_keywords" type="text"
                        value="{{ $value('seo_keywords') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="seo_canonical_base">Canonical base URL</label>
                    <input class="form-control" id="seo_canonical_base" name="seo_canonical_base" type="text"
                        value="{{ $value('seo_canonical_base') }}" placeholder="https://example.com">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="seo_og_image">OG image (đường dẫn)</label>
                    <input class="form-control" id="seo_og_image" name="seo_og_image" type="text"
                        value="{{ $value('seo_og_image') }}" placeholder="/uploads/settings/og-image.jpg">
                </div>

                <div class="col-12">
                    <label class="form-label" for="seo_og_image_file">Upload OG image</label>
                    <input class="form-control" id="seo_og_image_file" name="seo_og_image_file" type="file"
                        accept="image/*">
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">3) Footer</div>
            <div class="card-body row g-3">
                <div class="col-md-8">
                    <label class="form-label" for="footer_company_name">Tên công ty</label>
                    <input class="form-control" id="footer_company_name" name="footer_company_name" type="text"
                        value="{{ $value('footer_company_name') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="footer_tax_code">Mã số thuế</label>
                    <input class="form-control" id="footer_tax_code" name="footer_tax_code" type="text"
                        value="{{ $value('footer_tax_code') }}">
                </div>

                <div class="col-12">
                    <label class="form-label" for="footer_address">Địa chỉ</label>
                    <input class="form-control" id="footer_address" name="footer_address" type="text"
                        value="{{ $value('footer_address') }}" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="footer_website">Website</label>
                    <input class="form-control" id="footer_website" name="footer_website" type="text"
                        value="{{ $value('footer_website') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="footer_email">Email</label>
                    <input class="form-control" id="footer_email" name="footer_email" type="text"
                        value="{{ $value('footer_email') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="footer_phone">Số điện thoại</label>
                    <input class="form-control" id="footer_phone" name="footer_phone" type="text"
                        value="{{ $value('footer_phone') }}">
                </div>

                <div class="col-12">
                    <label class="form-label" for="footer_copyright">Copyright</label>
                    <input class="form-control" id="footer_copyright" name="footer_copyright" type="text"
                        value="{{ $value('footer_copyright') }}">
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-semibold">4) Mạng xã hội & liên hệ nhanh</div>
            <div class="card-body row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="social_facebook">Facebook URL</label>
                    <input class="form-control" id="social_facebook" name="social_facebook" type="text"
                        value="{{ $value('social_facebook') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="social_tiktok">TikTok URL</label>
                    <input class="form-control" id="social_tiktok" name="social_tiktok" type="text"
                        value="{{ $value('social_tiktok') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="social_youtube">YouTube URL</label>
                    <input class="form-control" id="social_youtube" name="social_youtube" type="text"
                        value="{{ $value('social_youtube') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="social_messenger">Messenger URL</label>
                    <input class="form-control" id="social_messenger" name="social_messenger" type="text"
                        value="{{ $value('social_messenger') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="social_zalo">Zalo URL</label>
                    <input class="form-control" id="social_zalo" name="social_zalo" type="text"
                        value="{{ $value('social_zalo') }}">
                </div>
            </div>
        </div>

        <div>
            <button class="btn btn-dark" type="submit">Lưu cấu hình</button>
        </div>
    </form>
@endsection
