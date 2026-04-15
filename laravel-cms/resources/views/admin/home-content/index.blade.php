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

        .home-highlight-mode-group .form-check {
            border: 1px solid #dbe5f0;
            border-radius: 10px;
            padding: .55rem .85rem;
            background: #fff;
            min-width: 230px;
        }

        .home-highlight-row {
            border: 1px solid #dbe5f0;
            border-radius: 12px;
            padding: .62rem;
            background: linear-gradient(180deg, #fbfdff 0%, #f4f8fc 100%);
            box-shadow: 0 6px 16px rgba(15, 23, 42, .04);
        }

        .home-highlight-row .home-preview-box {
            background: #ffffff;
        }

        .home-highlight-link-settings {
            border-top: 1px dashed #dbe5f0;
            margin-top: .25rem;
            padding-top: .65rem;
        }

        .home-highlight-link-settings .form-text {
            font-size: .76rem;
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

        $projectLinkSources = $projectLinkSources ?? [
            'detail' => collect(),
            'project' => collect(),
            'blog' => collect(),
            'video' => collect(),
        ];

        $projectHighlightsConfig = data_get($homeContent, 'project_highlights', []);
        $projectHighlightMode = old('project_highlights_mode', data_get($projectHighlightsConfig, 'mode', 'manual'));
        if (!in_array($projectHighlightMode, ['auto', 'manual'], true)) {
            $projectHighlightMode = 'manual';
        }

        $highlightTitlesInput = old('project_highlight_titles');
        $projectHighlightRows = [];

        if (is_array($highlightTitlesInput)) {
            $highlightDescriptionsInput = old('project_highlight_descriptions', []);
            $highlightImagesInput = old('project_highlight_images', []);
            $highlightActionsInput = old('project_highlight_actions', []);
            $highlightLinkTypesInput = old('project_highlight_link_types', []);
            $highlightLinkValuesInput = old('project_highlight_link_values', []);

            $maxHighlightRows = max(
                count($highlightTitlesInput),
                is_array($highlightDescriptionsInput) ? count($highlightDescriptionsInput) : 0,
                is_array($highlightImagesInput) ? count($highlightImagesInput) : 0,
                is_array($highlightActionsInput) ? count($highlightActionsInput) : 0,
                is_array($highlightLinkTypesInput) ? count($highlightLinkTypesInput) : 0,
                is_array($highlightLinkValuesInput) ? count($highlightLinkValuesInput) : 0
            );

            for ($index = 0; $index < $maxHighlightRows; $index++) {
                $projectHighlightRows[] = [
                    'title' => data_get($highlightTitlesInput, $index),
                    'description' => data_get($highlightDescriptionsInput, $index),
                    'image' => data_get($highlightImagesInput, $index),
                    'action' => data_get($highlightActionsInput, $index),
                    'link_type' => data_get($highlightLinkTypesInput, $index),
                    'link_value' => data_get($highlightLinkValuesInput, $index),
                ];
            }
        } else {
            $projectHighlightRows = array_values(array_filter((array) data_get($projectHighlightsConfig, 'items', []), function ($item) {
                return is_array($item);
            }));
        }

        $projectHighlightRows = array_map(function ($row) {
            $action = data_get($row, 'action') === 'lightbox' ? 'lightbox' : 'link';

            return [
                'title' => is_string(data_get($row, 'title')) ? trim((string) data_get($row, 'title')) : '',
                'description' => is_string(data_get($row, 'description')) ? trim((string) data_get($row, 'description')) : '',
                'image' => is_string(data_get($row, 'image')) ? trim((string) data_get($row, 'image')) : '',
                'action' => $action,
                'link_type' => is_string(data_get($row, 'link_type')) ? trim((string) data_get($row, 'link_type')) : '',
                'link_value' => data_get($row, 'link_value'),
            ];
        }, $projectHighlightRows);

        if (empty($projectHighlightRows)) {
            $projectHighlightRows = [
                [
                    'title' => '',
                    'description' => '',
                    'image' => '',
                    'action' => 'link',
                    'link_type' => '',
                    'link_value' => null,
                ],
            ];
        }

        $autoExcludedDetailPageIds = array_values(array_unique(array_filter(array_map(function ($value) {
            if (is_string($value)) {
                $value = trim($value);
            }

            return is_numeric($value) ? (int) $value : null;
        }, (array) ($autoExcludedDetailPageIds ?? data_get($homeContent, 'project_highlights.auto_excluded_detail_page_ids', []))), function ($value) {
            return is_int($value) && $value > 0;
        })));
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">Nội dung Trang chủ</h1>
            <p class="text-muted mb-0">Quản trị Hero, Hồ sơ năng lực, Về HOVI và CTA footer theo chuẩn chuyên nghiệp.</p>
        </div>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a class="btn btn-outline-primary" href="#home-highlight-manual-panel">Đi tới mục thủ công</a>
            <a class="btn btn-outline-secondary" href="#home-auto-source-section">Đi tới nguồn tự động</a>
            <a class="btn btn-outline-dark" href="{{ route('admin.projects.index') }}">Đi đến Quản trị dự án</a>
        </div>
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

        <div class="card home-config-card">
            <div class="card-header">5) Thumbnail thủ công (admin chủ động thêm)</div>
            <div class="card-body row g-3">
                <div class="col-12">
                    <input type="hidden" name="project_highlights_mode" value="manual">
                    <label class="form-label d-block mb-1">Danh sách card thumbnail thêm thủ công</label>
                    <div class="small text-muted">
                        Mục này là khu riêng để admin chủ động thêm / xóa item. Card thủ công sẽ được ưu tiên hiển thị trước trên Trang chủ.
                    </div>
                    @error('project_highlights_mode')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12" id="home-highlight-manual-panel">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-2 flex-wrap">
                        <label class="form-label mb-0">Danh sách item thumbnail thủ công</label>
                        <button type="button" class="btn btn-sm btn-outline-dark" data-add-highlight-row>+ Thêm item</button>
                    </div>

                    <div id="home-highlight-rows" class="d-grid gap-2">
                        @foreach ($projectHighlightRows as $index => $highlightRow)
                            @php
                                $highlightImage = is_string(data_get($highlightRow, 'image')) ? trim((string) data_get($highlightRow, 'image')) : '';
                                $highlightTitle = is_string(data_get($highlightRow, 'title')) ? trim((string) data_get($highlightRow, 'title')) : '';
                                $highlightDescription = is_string(data_get($highlightRow, 'description')) ? trim((string) data_get($highlightRow, 'description')) : '';
                                $highlightAction = data_get($highlightRow, 'action') === 'lightbox' ? 'lightbox' : 'link';
                                $highlightLinkType = is_string(data_get($highlightRow, 'link_type')) ? trim((string) data_get($highlightRow, 'link_type')) : '';
                                $highlightLinkValue = data_get($highlightRow, 'link_value');
                                $isLinkAction = $highlightAction === 'link';
                                $highlightPreviewId = 'home-highlight-preview-' . $index;
                                $highlightMetaId = 'home-highlight-meta-' . $index;
                            @endphp

                            <div class="row g-2 align-items-start js-home-highlight-row js-home-image-container home-highlight-row">
                                <div class="col-md-6 js-home-image-field">
                                    <label class="form-label small mb-1">Ảnh thumbnail #<span
                                            class="js-home-highlight-row-index">{{ $index + 1 }}</span></label>
                                    <input class="form-control form-control-sm js-home-image-path" type="text"
                                        name="project_highlight_images[]" value="{{ $highlightImage }}"
                                        placeholder="/uploads/home/thumbnail.jpg" data-preview-id="{{ $highlightPreviewId }}"
                                        data-meta-id="{{ $highlightMetaId }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small mb-1">Upload thumbnail</label>
                                    <input class="form-control form-control-sm js-home-image-file" type="file"
                                        name="project_highlight_image_files[]" accept="image/*"
                                        data-preview-id="{{ $highlightPreviewId }}" data-meta-id="{{ $highlightMetaId }}">
                                </div>
                                <div class="col-md-2 d-grid">
                                    <label class="form-label small mb-1 invisible">Xóa</label>
                                    <button type="button" class="btn btn-sm btn-outline-danger" data-remove-highlight-row>Xóa item</button>
                                </div>

                                <div class="col-12">
                                    <div class="home-preview-box py-2">
                                        <img id="{{ $highlightPreviewId }}" src="{{ $highlightImage }}"
                                            alt="Preview highlight {{ $index + 1 }}"
                                            style="{{ $highlightImage === '' ? 'display:none;' : '' }}">
                                        <div id="{{ $highlightMetaId }}" class="small text-muted mt-2">
                                            {{ $highlightImage === '' ? 'Chưa có ảnh.' : 'Đang đọc thông tin ảnh...' }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small mb-1">Tiêu đề card</label>
                                    <input class="form-control form-control-sm" type="text" name="project_highlight_titles[]"
                                        value="{{ $highlightTitle }}" placeholder="Ví dụ: Biệt thự sân vườn cao cấp">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small mb-1">Mô tả ngắn</label>
                                    <input class="form-control form-control-sm" type="text"
                                        name="project_highlight_descriptions[]" value="{{ $highlightDescription }}"
                                        placeholder="Ví dụ: Dự án thiết kế và thi công trọn gói">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label small mb-1">Hành vi click</label>
                                    <select class="form-select form-select-sm" name="project_highlight_actions[]"
                                        data-highlight-action>
                                        <option value="link" {{ $highlightAction === 'link' ? 'selected' : '' }}>Mở liên kết</option>
                                        <option value="lightbox" {{ $highlightAction === 'lightbox' ? 'selected' : '' }}>Zoom ảnh (không link)</option>
                                    </select>
                                </div>

                                <div class="col-md-9 js-home-highlight-link-settings {{ $isLinkAction ? '' : 'd-none' }}"
                                    data-highlight-link-settings>
                                    <div class="row g-2 home-highlight-link-settings">
                                        <div class="col-md-4">
                                            <label class="form-label small mb-1">Nguồn liên kết</label>
                                            <select class="form-select form-select-sm" name="project_highlight_link_types[]"
                                                data-highlight-link-type {{ $isLinkAction ? '' : 'disabled' }}>
                                                <option value="">-- Chọn loại nội dung --</option>
                                                <option value="detail" {{ $highlightLinkType === 'detail' ? 'selected' : '' }}>Trang chi tiết dự án</option>
                                                <option value="project" {{ $highlightLinkType === 'project' ? 'selected' : '' }}>Dự án</option>
                                                <option value="blog" {{ $highlightLinkType === 'blog' ? 'selected' : '' }}>Blog</option>
                                                <option value="video" {{ $highlightLinkType === 'video' ? 'selected' : '' }}>Video</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label class="form-label small mb-1">Nội dung mở khi click</label>
                                            <select class="form-select form-select-sm" name="project_highlight_link_values[]"
                                                data-highlight-link-value {{ $isLinkAction ? '' : 'disabled' }}>
                                                <option value="">-- Chọn nội dung để mở --</option>
                                                @foreach (($projectLinkSources['detail'] ?? collect()) as $detail)
                                                    <option value="{{ $detail->id }}" data-link-type="detail"
                                                        {{ $highlightLinkType === 'detail' && (string) $highlightLinkValue === (string) $detail->id ? 'selected' : '' }}>
                                                        [Trang chi tiết] {{ $detail->title }}
                                                        @if ($detail->project)
                                                            · {{ $detail->project->name }}
                                                        @endif
                                                    </option>
                                                @endforeach
                                                @foreach (($projectLinkSources['project'] ?? collect()) as $projectLink)
                                                    <option value="{{ $projectLink->id }}" data-link-type="project"
                                                        {{ $highlightLinkType === 'project' && (string) $highlightLinkValue === (string) $projectLink->id ? 'selected' : '' }}>
                                                        [Dự án] {{ $projectLink->name }}
                                                    </option>
                                                @endforeach
                                                @foreach (($projectLinkSources['blog'] ?? collect()) as $blogLink)
                                                    <option value="{{ $blogLink->id }}" data-link-type="blog"
                                                        {{ $highlightLinkType === 'blog' && (string) $highlightLinkValue === (string) $blogLink->id ? 'selected' : '' }}>
                                                        [Blog] {{ $blogLink->title }}
                                                    </option>
                                                @endforeach
                                                @foreach (($projectLinkSources['video'] ?? collect()) as $videoLink)
                                                    <option value="{{ $videoLink->id }}" data-link-type="video"
                                                        {{ $highlightLinkType === 'video' && (string) $highlightLinkValue === (string) $videoLink->id ? 'selected' : '' }}>
                                                        [Video] {{ $videoLink->title }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div class="form-text">Nếu chọn "Zoom ảnh", card sẽ không điều hướng mà mở ảnh phóng to.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('project_highlight_titles')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('project_highlight_titles.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('project_highlight_descriptions.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('project_highlight_images.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('project_highlight_image_files.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('project_highlight_actions.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('project_highlight_link_types.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @error('project_highlight_link_values.*')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror

                    <div class="form-text">Khuyến nghị mỗi thumbnail card: <strong>1200 × 1600 px</strong> (tỷ lệ 3:4), tối đa 5MB.</div>
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
        <span class="small">
            Item thêm thủ công sẽ được ưu tiên hiển thị trước trên Trang chủ.
            Bảng bên dưới là nguồn tự động từ Trang chi tiết <code>Đang bật</code> + Dự án cha <code>Đang bật</code> + có thumbnail.
        </span>
    </div>

    <h2 id="home-auto-source-section" class="h5 mb-3">Nguồn thumbnail tự động (tham khảo)</h2>

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
                        @php
                            $isAutoExcluded = in_array((int) $item->id, $autoExcludedDetailPageIds, true);
                        @endphp
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
                                    @if ($isAutoExcluded)
                                        <span class="badge text-bg-danger">Đã ẩn khỏi Trang chủ</span>
                                    @else
                                        <span class="badge text-bg-success">Đang bật</span>
                                    @endif
                                @elseif ($item->is_published)
                                    <span class="badge text-bg-warning text-dark">Cha đang ẩn</span>
                                @else
                                    <span class="badge text-bg-secondary">Trang con đang ẩn</span>
                                @endif
                            </td>
                            <td>{{ $item->sort_order }}</td>
                            <td class="text-end">
                                <form class="d-inline" action="{{ route('admin.home-content.auto-source.visibility', $item) }}"
                                    method="post"
                                    @if (!$isAutoExcluded)
                                        onsubmit="return confirm('Xóa mục này khỏi hiển thị tự động trên Trang chủ?');"
                                    @endif>
                                    @csrf
                                    <input type="hidden" name="action" value="{{ $isAutoExcluded ? 'include' : 'exclude' }}">
                                    <button class="btn btn-sm {{ $isAutoExcluded ? 'btn-outline-success' : 'btn-outline-danger' }}"
                                        type="submit">
                                        {{ $isAutoExcluded ? 'Khôi phục hiển thị' : 'Xóa khỏi Trang chủ' }}
                                    </button>
                                </form>
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

            function refreshHighlightRowLabels(highlightRowsWrapper) {
                if (!highlightRowsWrapper) {
                    return;
                }

                highlightRowsWrapper.querySelectorAll('.js-home-highlight-row').forEach(function(row, index) {
                    var displayIndex = index + 1;

                    row.querySelectorAll('.js-home-highlight-row-index').forEach(function(label) {
                        label.textContent = displayIndex;
                    });

                    var previewImage = row.querySelector('img[id^="home-highlight-preview-"]');
                    if (previewImage) {
                        previewImage.alt = 'Preview highlight ' + displayIndex;
                    }
                });
            }

            function syncHighlightRowLinkControls(row) {
                if (!row) {
                    return;
                }

                var actionSelect = row.querySelector('[data-highlight-action]');
                var linkSettings = row.querySelector('[data-highlight-link-settings]');
                var linkTypeSelect = row.querySelector('[data-highlight-link-type]');
                var linkValueSelect = row.querySelector('[data-highlight-link-value]');

                if (!actionSelect || !linkSettings || !linkTypeSelect || !linkValueSelect) {
                    return;
                }

                var isLinkAction = actionSelect.value === 'link';
                linkSettings.classList.toggle('d-none', !isLinkAction);
                linkTypeSelect.disabled = !isLinkAction;
                linkValueSelect.disabled = !isLinkAction;

                if (!isLinkAction) {
                    return;
                }

                var selectedLinkType = (linkTypeSelect.value || '').trim();
                var selectedLinkValue = (linkValueSelect.value || '').trim();
                var selectedStillVisible = selectedLinkValue === '';

                Array.prototype.forEach.call(linkValueSelect.options, function(option) {
                    if (option.value === '') {
                        option.hidden = false;
                        option.disabled = false;
                        return;
                    }

                    var optionType = option.getAttribute('data-link-type') || '';
                    var isVisible = selectedLinkType !== '' && optionType === selectedLinkType;
                    option.hidden = !isVisible;
                    option.disabled = !isVisible;

                    if (isVisible && option.value === selectedLinkValue) {
                        selectedStillVisible = true;
                    }
                });

                if (!selectedStillVisible) {
                    linkValueSelect.value = '';
                }
            }

            function bindHighlightRow(row, highlightRowsWrapper) {
                if (!row || row.dataset.highlightBound === '1') {
                    return;
                }

                row.dataset.highlightBound = '1';

                var imageField = row.querySelector('.js-home-image-field');
                bindImageField(imageField);

                var actionSelect = row.querySelector('[data-highlight-action]');
                var linkTypeSelect = row.querySelector('[data-highlight-link-type]');
                var removeBtn = row.querySelector('[data-remove-highlight-row]');

                if (actionSelect) {
                    actionSelect.addEventListener('change', function() {
                        syncHighlightRowLinkControls(row);
                    });
                }

                if (linkTypeSelect) {
                    linkTypeSelect.addEventListener('change', function() {
                        syncHighlightRowLinkControls(row);
                    });
                }

                syncHighlightRowLinkControls(row);

                if (!removeBtn) {
                    return;
                }

                removeBtn.addEventListener('click', function() {
                    if (!highlightRowsWrapper) {
                        return;
                    }

                    var rows = highlightRowsWrapper.querySelectorAll('.js-home-highlight-row');

                    if (rows.length <= 1) {
                        var imagePathInput = row.querySelector('input[name="project_highlight_images[]"]');
                        var imageFileInput = row.querySelector('input[name="project_highlight_image_files[]"]');
                        var titleInput = row.querySelector('input[name="project_highlight_titles[]"]');
                        var descriptionInput = row.querySelector('input[name="project_highlight_descriptions[]"]');
                        var actionSelectInput = row.querySelector('select[name="project_highlight_actions[]"]');
                        var linkTypeSelectInput = row.querySelector('select[name="project_highlight_link_types[]"]');
                        var linkValueSelectInput = row.querySelector('select[name="project_highlight_link_values[]"]');

                        if (imagePathInput) {
                            imagePathInput.value = '';
                        }

                        if (imageFileInput) {
                            imageFileInput.value = '';
                        }

                        if (titleInput) {
                            titleInput.value = '';
                        }

                        if (descriptionInput) {
                            descriptionInput.value = '';
                        }

                        if (actionSelectInput) {
                            actionSelectInput.value = 'link';
                        }

                        if (linkTypeSelectInput) {
                            linkTypeSelectInput.value = '';
                        }

                        if (linkValueSelectInput) {
                            linkValueSelectInput.value = '';
                        }

                        var previewId = imagePathInput ? imagePathInput.getAttribute('data-preview-id') : null;
                        var metaId = imagePathInput ? imagePathInput.getAttribute('data-meta-id') : null;
                        var previewImg = previewId ? document.getElementById(previewId) : null;
                        var metaEl = metaId ? document.getElementById(metaId) : null;

                        if (previewImg) {
                            previewImg.style.display = 'none';
                            previewImg.removeAttribute('src');
                        }

                        if (metaEl) {
                            metaEl.textContent = 'Chưa có ảnh.';
                        }

                        syncHighlightRowLinkControls(row);
                        refreshHighlightRowLabels(highlightRowsWrapper);

                        return;
                    }

                    row.remove();
                    refreshHighlightRowLabels(highlightRowsWrapper);
                });
            }

            function createHighlightRow(index, highlightRowsWrapper) {
                if (!highlightRowsWrapper) {
                    return null;
                }

                var firstRow = highlightRowsWrapper.querySelector('.js-home-highlight-row');
                if (!firstRow) {
                    return null;
                }

                var row = firstRow.cloneNode(true);
                delete row.dataset.highlightBound;

                var previewId = 'home-highlight-preview-' + index;
                var metaId = 'home-highlight-meta-' + index;

                var imageField = row.querySelector('.js-home-image-field');
                if (imageField) {
                    delete imageField.dataset.previewBound;
                }

                var imagePathInput = row.querySelector('input[name="project_highlight_images[]"]');
                if (imagePathInput) {
                    imagePathInput.value = '';
                    imagePathInput.setAttribute('data-preview-id', previewId);
                    imagePathInput.setAttribute('data-meta-id', metaId);
                }

                var imageFileInput = row.querySelector('input[name="project_highlight_image_files[]"]');
                if (imageFileInput) {
                    imageFileInput.value = '';
                    imageFileInput.setAttribute('data-preview-id', previewId);
                    imageFileInput.setAttribute('data-meta-id', metaId);
                }

                var titleInput = row.querySelector('input[name="project_highlight_titles[]"]');
                if (titleInput) {
                    titleInput.value = '';
                }

                var descriptionInput = row.querySelector('input[name="project_highlight_descriptions[]"]');
                if (descriptionInput) {
                    descriptionInput.value = '';
                }

                var actionSelect = row.querySelector('select[name="project_highlight_actions[]"]');
                if (actionSelect) {
                    actionSelect.value = 'link';
                }

                var linkTypeSelect = row.querySelector('select[name="project_highlight_link_types[]"]');
                if (linkTypeSelect) {
                    linkTypeSelect.value = '';
                }

                var linkValueSelect = row.querySelector('select[name="project_highlight_link_values[]"]');
                if (linkValueSelect) {
                    linkValueSelect.value = '';
                    Array.prototype.forEach.call(linkValueSelect.options, function(option) {
                        option.hidden = false;
                        option.disabled = false;
                    });
                }

                var previewImg = row.querySelector('img[id^="home-highlight-preview-"]');
                if (previewImg) {
                    previewImg.id = previewId;
                    previewImg.style.display = 'none';
                    previewImg.removeAttribute('src');
                    previewImg.alt = 'Preview highlight';
                }

                var previewMeta = row.querySelector('[id^="home-highlight-meta-"]');
                if (previewMeta) {
                    previewMeta.id = metaId;
                    previewMeta.textContent = 'Chưa có ảnh.';
                }

                return row;
            }

            var highlightRowsWrapper = document.getElementById('home-highlight-rows');
            var addHighlightRowButton = document.querySelector('[data-add-highlight-row]');
            var highlightRowIndex = highlightRowsWrapper ? highlightRowsWrapper.querySelectorAll('.js-home-highlight-row').length : 0;

            if (highlightRowsWrapper) {
                highlightRowsWrapper.querySelectorAll('.js-home-highlight-row').forEach(function(row) {
                    bindHighlightRow(row, highlightRowsWrapper);
                });
                refreshHighlightRowLabels(highlightRowsWrapper);
            }

            if (highlightRowsWrapper && addHighlightRowButton) {
                addHighlightRowButton.addEventListener('click', function() {
                    var row = createHighlightRow(highlightRowIndex, highlightRowsWrapper);
                    if (!row) {
                        return;
                    }

                    highlightRowIndex += 1;
                    highlightRowsWrapper.appendChild(row);
                    bindHighlightRow(row, highlightRowsWrapper);
                    refreshHighlightRowLabels(highlightRowsWrapper);
                });
            }
        });
    </script>
@endpush
