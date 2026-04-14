@extends('admin.layout')

@section('title', 'Nội dung Trang chủ | HOVI CMS')

@push('head')
    <style>
        .home-content-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: .75rem;
        }

        .home-content-stat {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: .9rem;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .04);
        }

        .home-content-stat p {
            margin: 0;
            color: #64748b;
            font-size: .86rem;
        }

        .home-content-stat strong {
            display: block;
            margin-top: .25rem;
            font-size: 1.4rem;
            line-height: 1.1;
            color: #0f172a;
        }

        .home-thumb-preview {
            width: 96px;
            height: 72px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #f8fafc;
        }

        .home-config-card {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .04);
            overflow: hidden;
        }

        .home-config-card .card-header {
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            font-weight: 600;
        }

        .home-preview-box {
            border: 1px solid #e2e8f0;
            background: #fff;
            border-radius: 10px;
            padding: .6rem;
        }

        .home-preview-box img {
            max-height: 210px;
            max-width: 100%;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
        }

        .home-click-mode {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            font-size: .78rem;
            font-weight: 600;
            border-radius: 999px;
            padding: .25rem .62rem;
            border: 1px solid transparent;
        }

        .home-click-mode--link {
            color: #1d4ed8;
            background: #eff6ff;
            border-color: #bfdbfe;
        }

        .home-click-mode--lightbox {
            color: #0f766e;
            background: #ecfeff;
            border-color: #99f6e4;
        }

        .home-slider-row {
            border: 1px solid #dbe5f0;
            border-radius: 12px;
            padding: .62rem;
            background: linear-gradient(180deg, #fbfdff 0%, #f4f8fc 100%);
            box-shadow: 0 6px 16px rgba(15, 23, 42, .04);
        }

        .home-slider-row .btn-outline-danger {
            white-space: nowrap;
        }

        .home-slider-row .home-preview-box {
            background: #ffffff;
        }
    </style>
@endpush

@section('content')
    @php
        $homeContent = $homeContent ?? \App\Models\SiteSetting::homeContentDefaults();
        $hero = data_get($homeContent, 'hero', []);
        $profile = data_get($homeContent, 'profile', []);
        $about = data_get($homeContent, 'about', []);
        $footerCta = data_get($homeContent, 'footer_cta', []);
        $consult = data_get($footerCta, 'consult', []);
        $partner = data_get($footerCta, 'partner', []);

        $sliderRows = old('profile_slider_images');
        if (!is_array($sliderRows)) {
            $sliderRows = array_values(array_filter((array) data_get($profile, 'slider_images', []), function ($item) {
                return is_string($item) && trim($item) !== '';
            }));
        }

        if (empty($sliderRows)) {
            $sliderRows = [''];
        }
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Nội dung Trang chủ</h1>
            <p class="text-muted mb-0">Quản trị Hero, Hồ sơ năng lực, Về HOVI và CTA footer theo chuẩn chuyên nghiệp.</p>
        </div>
        <a class="btn btn-outline-dark" href="{{ route('admin.projects.index') }}">Đi đến Quản trị dự án</a>
    </div>

    <form action="{{ route('admin.home-content.update') }}" method="post" enctype="multipart/form-data"
        class="d-grid gap-3 mb-4">
        @csrf
        @method('PUT')

        <div class="card home-config-card">
            <div class="card-header">1) Hero</div>
            <div class="card-body row g-3">
                <div class="col-md-8 js-home-image-field">
                    <label class="form-label" for="hero_background_image">Ảnh nền Hero</label>
                    <input class="form-control js-home-image-path" id="hero_background_image" name="hero_background_image"
                        type="text" value="{{ old('hero_background_image', data_get($hero, 'background_image')) }}"
                        data-preview-id="hero-bg-preview" data-meta-id="hero-bg-meta">
                    <div class="form-text">Khuyến nghị: <strong>1920 × 1080 px</strong>, định dạng JPG/WebP.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="hero_background_image_file">Upload ảnh Hero</label>
                    <input class="form-control js-home-image-file" id="hero_background_image_file"
                        name="hero_background_image_file" type="file" accept="image/*" data-preview-id="hero-bg-preview"
                        data-meta-id="hero-bg-meta">
                </div>
                <div class="col-12">
                    <div class="home-preview-box">
                        @php $heroBg = old('hero_background_image', data_get($hero, 'background_image')); @endphp
                        <img id="hero-bg-preview" src="{{ $heroBg ?: '' }}" alt="Preview hero"
                            style="{{ empty($heroBg) ? 'display:none;' : '' }}">
                        <div id="hero-bg-meta" class="small text-muted mt-2">
                            {{ empty($heroBg) ? 'Chưa có ảnh.' : 'Đang đọc thông tin ảnh...' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="hero_scroll_target">Điểm cuộn khi bấm mũi tên</label>
                    <input class="form-control" id="hero_scroll_target" name="hero_scroll_target" type="text"
                        value="{{ old('hero_scroll_target', data_get($hero, 'scroll_target', '#projects-1')) }}"
                        placeholder="#projects-1">
                </div>
            </div>
        </div>

        <div class="card home-config-card">
            <div class="card-header">2) Hồ sơ năng lực</div>
            <div class="card-body row g-3">
                <div class="col-md-8 js-home-image-field">
                    <label class="form-label" for="profile_background_image">Ảnh nền section Hồ sơ năng lực</label>
                    <input class="form-control js-home-image-path" id="profile_background_image" name="profile_background_image"
                        type="text" value="{{ old('profile_background_image', data_get($profile, 'background_image')) }}"
                        data-preview-id="profile-bg-preview" data-meta-id="profile-bg-meta">
                    <div class="form-text">Khuyến nghị: <strong>1920 × 1080 px</strong>.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="profile_background_image_file">Upload ảnh nền</label>
                    <input class="form-control js-home-image-file" id="profile_background_image_file"
                        name="profile_background_image_file" type="file" accept="image/*"
                        data-preview-id="profile-bg-preview" data-meta-id="profile-bg-meta">
                </div>
                <div class="col-12">
                    <div class="home-preview-box">
                        @php $profileBg = old('profile_background_image', data_get($profile, 'background_image')); @endphp
                        <img id="profile-bg-preview" src="{{ $profileBg ?: '' }}" alt="Preview profile"
                            style="{{ empty($profileBg) ? 'display:none;' : '' }}">
                        <div id="profile-bg-meta" class="small text-muted mt-2">
                            {{ empty($profileBg) ? 'Chưa có ảnh.' : 'Đang đọc thông tin ảnh...' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="profile_eyebrow">Eyebrow</label>
                    <input class="form-control" id="profile_eyebrow" name="profile_eyebrow" type="text"
                        value="{{ old('profile_eyebrow', data_get($profile, 'eyebrow')) }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="profile_title">Tiêu đề</label>
                    <input class="form-control" id="profile_title" name="profile_title" type="text"
                        value="{{ old('profile_title', data_get($profile, 'title')) }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="profile_description_1">Đoạn mô tả 1</label>
                    <textarea class="form-control" id="profile_description_1" name="profile_description_1" rows="4" required>{{ old('profile_description_1', data_get($profile, 'description_1')) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="profile_description_2">Đoạn mô tả 2</label>
                    <textarea class="form-control" id="profile_description_2" name="profile_description_2" rows="4">{{ old('profile_description_2', data_get($profile, 'description_2')) }}</textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="profile_button_label">Nhãn nút</label>
                    <input class="form-control" id="profile_button_label" name="profile_button_label" type="text"
                        value="{{ old('profile_button_label', data_get($profile, 'button_label')) }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="profile_button_url">Link nút</label>
                    <input class="form-control" id="profile_button_url" name="profile_button_url" type="text"
                        value="{{ old('profile_button_url', data_get($profile, 'button_url')) }}">
                </div>
                <div class="col-12">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2 flex-wrap">
                        <label class="form-label mb-0">Danh sách ảnh slider hồ sơ năng lực</label>
                        <button type="button" class="btn btn-sm btn-outline-dark" data-add-slider-row>+ Thêm dòng ảnh</button>
                    </div>

                    <div id="profile-slider-rows" class="d-grid gap-2">
                        @foreach ($sliderRows as $index => $sliderImage)
                            @php
                                $sliderImageValue = is_string($sliderImage) ? trim($sliderImage) : '';
                                $previewId = 'profile-slider-preview-' . $index;
                                $metaId = 'profile-slider-meta-' . $index;
                            @endphp

                            <div class="row g-2 align-items-start js-slider-row js-home-image-container home-slider-row">
                                <div class="col-md-6 js-home-image-field d-none">
                                    <input class="js-home-image-path" type="hidden" name="profile_slider_images[]"
                                        value="{{ $sliderImageValue }}" data-preview-id="{{ $previewId }}"
                                        data-meta-id="{{ $metaId }}">
                                </div>
                                <div class="col-md-10">
                                    <label class="form-label small mb-1">Upload ảnh slider #<span
                                            class="js-slider-row-index">{{ $index + 1 }}</span></label>
                                    <input class="form-control form-control-sm js-home-image-file" type="file"
                                        name="profile_slider_image_files[]" accept="image/*"
                                        data-preview-id="{{ $previewId }}" data-meta-id="{{ $metaId }}">
                                </div>
                                <div class="col-md-2 d-grid">
                                    <label class="form-label small mb-1 invisible">Xóa</label>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-remove-slider-row>Xóa dòng</button>
                                </div>
                                <div class="col-12">
                                    <div class="home-preview-box py-2">
                                        <img id="{{ $previewId }}" src="{{ $sliderImageValue }}"
                                            alt="Preview slider {{ $index + 1 }}"
                                            style="{{ $sliderImageValue === '' ? 'display:none;' : '' }}">
                                        <div id="{{ $metaId }}" class="small text-muted mt-2">
                                            {{ $sliderImageValue === '' ? 'Chưa có ảnh.' : 'Đang đọc thông tin ảnh...' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('profile_slider_images')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('profile_slider_images.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('profile_slider_image_files.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    <div class="form-text">Khuyến nghị mỗi ảnh: <strong>1200 × 800 px</strong> (tỷ lệ 3:2).</div>
                </div>
            </div>
        </div>

        <div class="card home-config-card">
            <div class="card-header">3) Về HOVI</div>
            <div class="card-body row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="about_title">Tiêu đề</label>
                    <input class="form-control" id="about_title" name="about_title" type="text"
                        value="{{ old('about_title', data_get($about, 'title')) }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label" for="about_description">Mô tả</label>
                    <textarea class="form-control" id="about_description" name="about_description" rows="4" required>{{ old('about_description', data_get($about, 'description')) }}</textarea>
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="about_stat_1_value">Chỉ số 1 - giá trị</label>
                    <input class="form-control" id="about_stat_1_value" name="about_stat_1_value" type="text"
                        value="{{ old('about_stat_1_value', data_get($about, 'stats.0.value')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="about_stat_1_label">Chỉ số 1 - nhãn</label>
                    <input class="form-control" id="about_stat_1_label" name="about_stat_1_label" type="text"
                        value="{{ old('about_stat_1_label', data_get($about, 'stats.0.label')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="about_stat_2_value">Chỉ số 2 - giá trị</label>
                    <input class="form-control" id="about_stat_2_value" name="about_stat_2_value" type="text"
                        value="{{ old('about_stat_2_value', data_get($about, 'stats.1.value')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="about_stat_2_label">Chỉ số 2 - nhãn</label>
                    <input class="form-control" id="about_stat_2_label" name="about_stat_2_label" type="text"
                        value="{{ old('about_stat_2_label', data_get($about, 'stats.1.label')) }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label" for="about_stat_3_value">Chỉ số 3 - giá trị</label>
                    <input class="form-control" id="about_stat_3_value" name="about_stat_3_value" type="text"
                        value="{{ old('about_stat_3_value', data_get($about, 'stats.2.value')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="about_stat_3_label">Chỉ số 3 - nhãn</label>
                    <input class="form-control" id="about_stat_3_label" name="about_stat_3_label" type="text"
                        value="{{ old('about_stat_3_label', data_get($about, 'stats.2.label')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="about_stat_4_value">Chỉ số 4 - giá trị</label>
                    <input class="form-control" id="about_stat_4_value" name="about_stat_4_value" type="text"
                        value="{{ old('about_stat_4_value', data_get($about, 'stats.3.value')) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="about_stat_4_label">Chỉ số 4 - nhãn</label>
                    <input class="form-control" id="about_stat_4_label" name="about_stat_4_label" type="text"
                        value="{{ old('about_stat_4_label', data_get($about, 'stats.3.label')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="about_cta_label">Nhãn nút</label>
                    <input class="form-control" id="about_cta_label" name="about_cta_label" type="text"
                        value="{{ old('about_cta_label', data_get($about, 'cta_label')) }}">
                </div>
                <div class="col-md-8">
                    <label class="form-label" for="about_cta_url">Link nút</label>
                    <input class="form-control" id="about_cta_url" name="about_cta_url" type="text"
                        value="{{ old('about_cta_url', data_get($about, 'cta_url')) }}">
                </div>

                <div class="col-md-8 js-home-image-field">
                    <label class="form-label" for="about_team_image">Ảnh đội ngũ</label>
                    <input class="form-control js-home-image-path" id="about_team_image" name="about_team_image"
                        type="text" value="{{ old('about_team_image', data_get($about, 'team_image')) }}"
                        data-preview-id="about-team-preview" data-meta-id="about-team-meta">
                    <div class="form-text">Khuyến nghị: <strong>1600 × 900 px</strong>.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="about_team_image_file">Upload ảnh đội ngũ</label>
                    <input class="form-control js-home-image-file" id="about_team_image_file" name="about_team_image_file"
                        type="file" accept="image/*" data-preview-id="about-team-preview" data-meta-id="about-team-meta">
                </div>
                <div class="col-12">
                    <div class="home-preview-box">
                        @php $aboutTeamImage = old('about_team_image', data_get($about, 'team_image')); @endphp
                        <img id="about-team-preview" src="{{ $aboutTeamImage ?: '' }}" alt="Preview team"
                            style="{{ empty($aboutTeamImage) ? 'display:none;' : '' }}">
                        <div id="about-team-meta" class="small text-muted mt-2">
                            {{ empty($aboutTeamImage) ? 'Chưa có ảnh.' : 'Đang đọc thông tin ảnh...' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card home-config-card">
            <div class="card-header">4) CTA cuối trang</div>
            <div class="card-body row g-3">
                <div class="col-md-6 border-end">
                    <h6 class="fw-semibold mb-3">CTA Đặt lịch tư vấn</h6>
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label" for="footer_consult_title">Tiêu đề</label>
                            <input class="form-control" id="footer_consult_title" name="footer_consult_title"
                                type="text" value="{{ old('footer_consult_title', data_get($consult, 'title')) }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label" for="footer_consult_button_label">Nhãn nút</label>
                            <input class="form-control" id="footer_consult_button_label"
                                name="footer_consult_button_label" type="text"
                                value="{{ old('footer_consult_button_label', data_get($consult, 'button_label')) }}">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label" for="footer_consult_button_url">Link nút</label>
                            <input class="form-control" id="footer_consult_button_url" name="footer_consult_button_url"
                                type="text" value="{{ old('footer_consult_button_url', data_get($consult, 'button_url')) }}">
                        </div>
                        <div class="col-md-8 js-home-image-field">
                            <label class="form-label" for="footer_consult_background_image">Ảnh nền CTA</label>
                            <input class="form-control js-home-image-path" id="footer_consult_background_image"
                                name="footer_consult_background_image" type="text"
                                value="{{ old('footer_consult_background_image', data_get($consult, 'background_image')) }}"
                                data-preview-id="footer-consult-preview" data-meta-id="footer-consult-meta">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="footer_consult_background_image_file">Upload ảnh</label>
                            <input class="form-control js-home-image-file" id="footer_consult_background_image_file"
                                name="footer_consult_background_image_file" type="file" accept="image/*"
                                data-preview-id="footer-consult-preview" data-meta-id="footer-consult-meta">
                        </div>
                        <div class="col-12">
                            <div class="home-preview-box">
                                @php $footerConsultBg = old('footer_consult_background_image', data_get($consult, 'background_image')); @endphp
                                <img id="footer-consult-preview" src="{{ $footerConsultBg ?: '' }}" alt="Preview CTA tư vấn"
                                    style="{{ empty($footerConsultBg) ? 'display:none;' : '' }}">
                                <div id="footer-consult-meta" class="small text-muted mt-2">
                                    {{ empty($footerConsultBg) ? 'Chưa có ảnh.' : 'Đang đọc thông tin ảnh...' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <h6 class="fw-semibold mb-3">CTA Trở thành đối tác</h6>
                    <div class="row g-2">
                        <div class="col-12">
                            <label class="form-label" for="footer_partner_title">Tiêu đề</label>
                            <input class="form-control" id="footer_partner_title" name="footer_partner_title"
                                type="text" value="{{ old('footer_partner_title', data_get($partner, 'title')) }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label" for="footer_partner_button_label">Nhãn nút</label>
                            <input class="form-control" id="footer_partner_button_label"
                                name="footer_partner_button_label" type="text"
                                value="{{ old('footer_partner_button_label', data_get($partner, 'button_label')) }}">
                        </div>
                        <div class="col-md-7">
                            <label class="form-label" for="footer_partner_button_url">Link nút</label>
                            <input class="form-control" id="footer_partner_button_url" name="footer_partner_button_url"
                                type="text" value="{{ old('footer_partner_button_url', data_get($partner, 'button_url')) }}">
                        </div>
                        <div class="col-md-8 js-home-image-field">
                            <label class="form-label" for="footer_partner_background_image">Ảnh nền CTA</label>
                            <input class="form-control js-home-image-path" id="footer_partner_background_image"
                                name="footer_partner_background_image" type="text"
                                value="{{ old('footer_partner_background_image', data_get($partner, 'background_image')) }}"
                                data-preview-id="footer-partner-preview" data-meta-id="footer-partner-meta">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label" for="footer_partner_background_image_file">Upload ảnh</label>
                            <input class="form-control js-home-image-file" id="footer_partner_background_image_file"
                                name="footer_partner_background_image_file" type="file" accept="image/*"
                                data-preview-id="footer-partner-preview" data-meta-id="footer-partner-meta">
                        </div>
                        <div class="col-12">
                            <div class="home-preview-box">
                                @php $footerPartnerBg = old('footer_partner_background_image', data_get($partner, 'background_image')); @endphp
                                <img id="footer-partner-preview" src="{{ $footerPartnerBg ?: '' }}" alt="Preview CTA đối tác"
                                    style="{{ empty($footerPartnerBg) ? 'display:none;' : '' }}">
                                <div id="footer-partner-meta" class="small text-muted mt-2">
                                    {{ empty($footerPartnerBg) ? 'Chưa có ảnh.' : 'Đang đọc thông tin ảnh...' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-text">Khuyến nghị ảnh nền CTA: <strong>1600 × 900 px</strong> (16:9).</div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <button class="btn btn-dark px-4" type="submit">Lưu nội dung Trang chủ</button>
        </div>
    </form>

    <div class="alert alert-info">
        <strong>Chuẩn ảnh khuyến nghị:</strong> 1200 × 1600 px (tỷ lệ 3:4), tối đa 5MB, ưu tiên JPG/WebP.
        <br>
        <span class="small">Muốn card hiển thị trên trang chủ: Trang chi tiết <code>Đang bật</code> + Dự án cha <code>Đang bật</code> + có thumbnail.</span>
    </div>

    <h2 class="h5 mb-3">Nguồn thumbnail khối dự án trên Trang chủ</h2>

    <div class="home-content-stats mb-3">
        <div class="home-content-stat">
            <p>Tổng trang chi tiết</p>
            <strong>{{ $stats['total'] }}</strong>
        </div>
        <div class="home-content-stat">
            <p>Trang chi tiết đang bật</p>
            <strong>{{ $stats['published'] }}</strong>
        </div>
        <div class="home-content-stat">
            <p>Có thumbnail</p>
            <strong>{{ $stats['with_thumbnail'] }}</strong>
        </div>
        <div class="home-content-stat">
            <p>Đủ điều kiện lên trang chủ</p>
            <strong>{{ $stats['eligible_for_home'] }}</strong>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Thumbnail</th>
                        <th>Tiêu đề</th>
                        <th>Dự án cha</th>
                        <th>Slug</th>
                        <th>Kiểu click</th>
                        <th>Hiển thị</th>
                        <th>Thứ tự</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($homeItems as $item)
                        <tr>
                            <td>
                                @if (!empty($item->thumbnail_image))
                                    <img class="home-thumb-preview" src="{{ $item->thumbnail_image }}"
                                        alt="{{ $item->title }}">
                                @else
                                    <span class="badge text-bg-warning text-dark">Thiếu ảnh</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $item->title }}</strong>
                                @if (!empty($item->summary))
                                    <div class="small text-muted">{{ \Illuminate\Support\Str::limit($item->summary, 90) }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($item->project)
                                    <div>{{ $item->project->name }}</div>
                                    <div class="small text-muted">/{{ $item->project->slug }}</div>
                                @else
                                    <span class="text-muted">Không có</span>
                                @endif
                            </td>
                            <td><code>/{{ $item->slug }}</code></td>
                            <td>
                                @if (($item->thumbnail_click_action ?? 'link') === 'lightbox')
                                    <span class="home-click-mode home-click-mode--lightbox">🖼 Mở ảnh</span>
                                @else
                                    <span class="home-click-mode home-click-mode--link">🔗 Mở bài viết</span>
                                @endif
                            </td>
                            <td>
                                @if ($item->is_published && optional($item->project)->is_published)
                                    <span class="badge text-bg-success">Đang bật</span>
                                @elseif ($item->is_published)
                                    <span class="badge text-bg-warning text-dark">Cha đang ẩn</span>
                                @else
                                    <span class="badge text-bg-secondary">Trang con đang ẩn</span>
                                @endif
                            </td>
                            <td>{{ $item->sort_order }}</td>
                            <td class="text-end">
                                @if ($item->project)
                                    <a class="btn btn-sm btn-outline-dark"
                                        href="{{ route('admin.projects.detail-pages.edit', [$item->project, $item]) }}">Sửa thumbnail</a>
                                @endif
                                <a class="btn btn-sm btn-outline-primary" href="{{ url('/' . $item->slug) }}" target="_blank"
                                    rel="noreferrer noopener">Xem</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Chưa có trang chi tiết dự án nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $homeItems->links() }}
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function formatBytes(bytes) {
                if (!bytes || bytes <= 0) {
                    return '0 B';
                }

                var units = ['B', 'KB', 'MB', 'GB'];
                var index = Math.floor(Math.log(bytes) / Math.log(1024));
                index = Math.min(index, units.length - 1);
                var value = bytes / Math.pow(1024, index);
                return value.toFixed(index === 0 ? 0 : 2) + ' ' + units[index];
            }

            function bindImageField(fieldWrapper) {
                if (!fieldWrapper || fieldWrapper.dataset.previewBound === '1') {
                    return;
                }

                var pathInput = fieldWrapper.querySelector('.js-home-image-path');
                if (!pathInput) {
                    return;
                }

                var previewId = pathInput.getAttribute('data-preview-id');
                var metaId = pathInput.getAttribute('data-meta-id');
                var searchContainer = fieldWrapper.closest('.js-home-image-container') || fieldWrapper.parentElement;
                var fileInput = searchContainer.querySelector(
                    '.js-home-image-file[data-preview-id="' + previewId + '"]'
                );
                var previewImg = document.getElementById(previewId);
                var metaEl = document.getElementById(metaId);

                if (!previewImg || !metaEl) {
                    return;
                }

                fieldWrapper.dataset.previewBound = '1';

                function showPathPreview() {
                    var path = (pathInput.value || '').trim();

                    if (path === '') {
                        previewImg.style.display = 'none';
                        previewImg.removeAttribute('src');
                        metaEl.textContent = 'Chưa có ảnh.';
                        return;
                    }

                    previewImg.style.display = 'block';
                    previewImg.src = path;
                    metaEl.textContent = 'Đang đọc thông tin ảnh...';

                    previewImg.onload = function() {
                        metaEl.textContent = 'Ảnh hiện tại · Kích thước: ' + previewImg.naturalWidth + ' x ' +
                            previewImg.naturalHeight + ' px';
                    };

                    previewImg.onerror = function() {
                        metaEl.textContent = 'Không tải được ảnh từ đường dẫn hiện tại.';
                    };
                }

                pathInput.addEventListener('input', function() {
                    if (!fileInput || !fileInput.files || fileInput.files.length === 0) {
                        showPathPreview();
                    }
                });

                if (fileInput) {
                    fileInput.addEventListener('change', function() {
                        var file = this.files && this.files[0] ? this.files[0] : null;
                        if (!file) {
                            showPathPreview();
                            return;
                        }

                        var objectUrl = URL.createObjectURL(file);
                        previewImg.style.display = 'block';
                        previewImg.src = objectUrl;
                        metaEl.textContent = 'Đang đọc thông tin file...';

                        previewImg.onload = function() {
                            metaEl.textContent = 'File mới: ' + file.name + ' · Dung lượng: ' + formatBytes(file
                                .size) + ' · Kích thước: ' + previewImg.naturalWidth + ' x ' + previewImg
                                .naturalHeight + ' px';
                        };

                        previewImg.onerror = function() {
                            metaEl.textContent = 'Không thể xem trước file ảnh đã chọn.';
                        };
                    });
                }

                showPathPreview();
            }

            function bindSliderRow(row, sliderRowsWrapper) {
                if (!row) {
                    return;
                }

                var removeBtn = row.querySelector('[data-remove-slider-row]');
                var imageField = row.querySelector('.js-home-image-field');
                bindImageField(imageField);

                if (!removeBtn) {
                    return;
                }

                removeBtn.addEventListener('click', function() {
                    if (!sliderRowsWrapper) {
                        return;
                    }

                    var rows = sliderRowsWrapper.querySelectorAll('.js-slider-row');
                    if (rows.length <= 1) {
                        var pathInput = row.querySelector('.js-home-image-path');
                        var fileInput = row.querySelector('.js-home-image-file');
                        var previewId = pathInput ? pathInput.getAttribute('data-preview-id') : null;
                        var metaId = pathInput ? pathInput.getAttribute('data-meta-id') : null;
                        var previewImg = previewId ? document.getElementById(previewId) : null;
                        var metaEl = metaId ? document.getElementById(metaId) : null;

                        if (pathInput) {
                            pathInput.value = '';
                        }

                        if (fileInput) {
                            fileInput.value = '';
                        }

                        if (previewImg) {
                            previewImg.style.display = 'none';
                            previewImg.removeAttribute('src');
                        }

                        if (metaEl) {
                            metaEl.textContent = 'Chưa có ảnh.';
                        }

                        refreshSliderRowLabels(sliderRowsWrapper);

                        return;
                    }

                    row.remove();
                    refreshSliderRowLabels(sliderRowsWrapper);
                });
            }

            function refreshSliderRowLabels(sliderRowsWrapper) {
                if (!sliderRowsWrapper) {
                    return;
                }

                sliderRowsWrapper.querySelectorAll('.js-slider-row').forEach(function(row, index) {
                    var displayIndex = index + 1;

                    row.querySelectorAll('.js-slider-row-index').forEach(function(label) {
                        label.textContent = displayIndex;
                    });

                    var previewImage = row.querySelector('img[id^="profile-slider-preview-"]');
                    if (previewImage) {
                        previewImage.alt = 'Preview slider ' + displayIndex;
                    }
                });
            }

            function createSliderRow(index) {
                var previewId = 'profile-slider-preview-' + index;
                var metaId = 'profile-slider-meta-' + index;
                var row = document.createElement('div');

                row.className = 'row g-2 align-items-start js-slider-row js-home-image-container home-slider-row';
                row.innerHTML = '' +
                    '<div class="col-md-6 js-home-image-field d-none">' +
                    '  <input class="js-home-image-path" type="hidden" name="profile_slider_images[]" value="" data-preview-id="' + previewId + '" data-meta-id="' + metaId + '">' +
                    '</div>' +
                    '<div class="col-md-10">' +
                    '  <label class="form-label small mb-1">Upload ảnh slider #<span class="js-slider-row-index">' + (index + 1) + '</span></label>' +
                    '  <input class="form-control form-control-sm js-home-image-file" type="file" name="profile_slider_image_files[]" accept="image/*" data-preview-id="' + previewId + '" data-meta-id="' + metaId + '">' +
                    '</div>' +
                    '<div class="col-md-2 d-grid">' +
                    '  <label class="form-label small mb-1 invisible">Xóa</label>' +
                    '  <button type="button" class="btn btn-sm btn-outline-danger" data-remove-slider-row>Xóa dòng</button>' +
                    '</div>' +
                    '<div class="col-12">' +
                    '  <div class="home-preview-box py-2">' +
                    '    <img id="' + previewId + '" src="" alt="Preview slider" style="display:none;">' +
                    '    <div id="' + metaId + '" class="small text-muted mt-2">Chưa có ảnh.</div>' +
                    '  </div>' +
                    '</div>';

                return row;
            }

            document.querySelectorAll('.js-home-image-field').forEach(bindImageField);

            var sliderRowsWrapper = document.getElementById('profile-slider-rows');
            var addSliderRowButton = document.querySelector('[data-add-slider-row]');
            var sliderRowIndex = sliderRowsWrapper ? sliderRowsWrapper.querySelectorAll('.js-slider-row').length : 0;

            if (sliderRowsWrapper) {
                sliderRowsWrapper.querySelectorAll('.js-slider-row').forEach(function(row) {
                    bindSliderRow(row, sliderRowsWrapper);
                });
                refreshSliderRowLabels(sliderRowsWrapper);
            }

            if (sliderRowsWrapper && addSliderRowButton) {
                addSliderRowButton.addEventListener('click', function() {
                    var row = createSliderRow(sliderRowIndex);
                    sliderRowIndex += 1;
                    sliderRowsWrapper.appendChild(row);
                    bindSliderRow(row, sliderRowsWrapper);
                    refreshSliderRowLabels(sliderRowsWrapper);
                });
            }
        });
    </script>
@endpush
