@extends('admin.layout')

@section('title', 'Nội dung About Us | HOVI CMS')

@push('head')
    <style>
        .about-admin-page .accordion-item {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
            margin-bottom: 14px;
        }

        .about-admin-page .accordion-button {
            font-weight: 600;
            background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 100%);
            color: #0f172a;
        }

        .about-admin-page .accordion-button:not(.collapsed) {
            color: #0f172a;
            background: linear-gradient(180deg, #eef2ff 0%, #e2e8f0 100%);
            box-shadow: none;
        }

        .about-admin-page .accordion-body {
            background: #ffffff;
        }

        .about-admin-page .section-tip {
            font-size: .86rem;
            color: #64748b;
            margin-top: 2px;
        }

        .about-admin-page .about-repeater-item {
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 12px;
            background: #f8fafc;
            margin-bottom: 10px;
        }

        .about-admin-page .about-repeater-item__head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .about-admin-page .about-repeater-item__title {
            font-weight: 600;
            color: #0f172a;
            margin: 0;
            font-size: .92rem;
        }

        .about-admin-page .js-image-field .border {
            border-color: #dbe2ea !important;
            border-radius: 10px !important;
            background: #fff;
        }

        .about-admin-page .about-image-uploader {
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 10px;
            background: #f8fafc;
        }

        .about-admin-page .about-image-uploader .form-text {
            color: #64748b;
            font-size: .8rem;
        }

        .about-admin-page .about-image-uploader .btn-outline-secondary {
            border-color: #cbd5e1;
            color: #334155;
        }

        .about-admin-page .about-image-uploader .btn-outline-secondary:hover {
            background: #e2e8f0;
            border-color: #94a3b8;
            color: #0f172a;
        }

        .about-admin-page .js-image-field img {
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
    </style>
@endpush

@section('content')
    @php
        $aboutContent = $aboutContent ?? \App\Models\SiteSetting::aboutContentDefaults();

        $hero = data_get($aboutContent, 'hero', []);
        $mission = data_get($aboutContent, 'mission', []);
        $vision = data_get($aboutContent, 'vision', []);
        $inspiration = data_get($aboutContent, 'inspiration', []);
        $definition = data_get($aboutContent, 'definition', []);
        $core = data_get($aboutContent, 'core', []);
        $manifesto = data_get($aboutContent, 'manifesto', []);
        $advantages = data_get($aboutContent, 'advantages', []);
        $ceo = data_get($aboutContent, 'ceo', []);
        $capacity = data_get($aboutContent, 'capacity', []);

        $heroEnabled = old('hero_enabled', data_get($hero, 'enabled', true));
        $missionEnabled = old('mission_enabled', data_get($mission, 'enabled', true));
        $visionEnabled = old('vision_enabled', data_get($vision, 'enabled', true));
        $inspirationEnabled = old('inspiration_enabled', data_get($inspiration, 'enabled', true));
        $definitionEnabled = old('definition_enabled', data_get($definition, 'enabled', true));
        $coreEnabled = old('core_enabled', data_get($core, 'enabled', true));
        $manifestoEnabled = old('manifesto_enabled', data_get($manifesto, 'enabled', true));
        $advantagesEnabled = old('advantages_enabled', data_get($advantages, 'enabled', true));
        $ceoEnabled = old('ceo_enabled', data_get($ceo, 'enabled', true));
        $capacityEnabled = old('capacity_enabled', data_get($capacity, 'enabled', true));

        $coreItems = data_get($core, 'items', []);
        $manifestoItems = data_get($manifesto, 'items', []);
        $advantagesItems = data_get($advantages, 'items', []);
        $capacityStats = data_get($capacity, 'stats', []);

        $resolveRows = function (string $oldKey, $defaultRows, array $emptyTemplate) {
            $rows = old($oldKey);
            if (!is_array($rows) || empty($rows)) {
                $rows = is_array($defaultRows) ? $defaultRows : [];
            }

            if (!is_array($rows)) {
                $rows = [];
            }

            $rows = array_values($rows);

            if (empty($rows)) {
                $rows = [$emptyTemplate];
            }

            return $rows;
        };

        $coreRows = $resolveRows('core_items', $coreItems, ['title' => '', 'description' => '', 'image' => '', 'image_alt' => '']);
        $manifestoRows = $resolveRows('manifesto_items', $manifestoItems, ['quote' => '', 'image' => '', 'image_alt' => '']);
        $advantagesRows = $resolveRows('advantages_items', $advantagesItems, ['title' => '', 'description' => '']);
        $statsRows = $resolveRows('capacity_stats', $capacityStats, ['value' => '', 'label' => '']);

        $isChecked = function ($value): bool {
            return $value === true || $value === 1 || $value === '1' || $value === 'on' || $value === 'true';
        };

        $coreNextIndex = count($coreRows);
        $manifestoNextIndex = count($manifestoRows);
        $advantagesNextIndex = count($advantagesRows);
        $statsNextIndex = count($statsRows);
    @endphp

    <div class="about-admin-page">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Nội dung trang About Us</h1>
                <p class="text-muted mb-0">Chỉnh sửa theo từng section để quản lý dễ hơn, không cần sửa Blade thủ công.</p>
            </div>

            <a class="btn btn-outline-dark" href="{{ route('site.page', ['slug' => 'about-us']) }}" target="_blank"
                rel="noreferrer noopener">Xem trang About Us</a>
        </div>

        <form action="{{ route('admin.about-content.update') }}" method="post" enctype="multipart/form-data"
            class="d-grid gap-3">
            @csrf
            @method('PUT')

            <div class="alert alert-info mb-0">
                <strong>Mẹo:</strong> Upload file để thay ảnh nhanh. Trường đường dẫn ảnh đã được ẩn để tránh nhập tay sai.
            </div>

            <div class="accordion" id="aboutContentAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingHero">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseHero" aria-expanded="true" aria-controls="collapseHero">
                            1) Hero section
                        </button>
                    </h2>
                    <div id="collapseHero" class="accordion-collapse collapse show" aria-labelledby="headingHero"
                        data-bs-parent="#aboutContentAccordion">
                        <div class="accordion-body row g-3">
                            <div class="col-12">
                                <input type="hidden" name="hero_enabled" value="0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="hero_enabled"
                                        name="hero_enabled" value="1" {{ $isChecked($heroEnabled) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="hero_enabled">Hiển thị Hero section</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Eyebrow</label>
                                <input class="form-control" name="hero_eyebrow" type="text"
                                    value="{{ old('hero_eyebrow', data_get($hero, 'eyebrow')) }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Tiêu đề</label>
                                <input class="form-control" name="hero_title" type="text"
                                    value="{{ old('hero_title', data_get($hero, 'title')) }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Mô tả</label>
                                <textarea class="form-control" name="hero_description" rows="3" required>{{ old('hero_description', data_get($hero, 'description')) }}</textarea>
                            </div>
                            <div class="col-12">
                                @include('admin.about-content._image_upload', [
                                    'label' => 'Ảnh hero',
                                    'name' => 'hero_image',
                                    'oldKey' => 'hero_image',
                                    'value' => data_get($hero, 'image'),
                                    'fileName' => 'hero_image_file',
                                    'altName' => 'hero_image_alt',
                                    'altOldKey' => 'hero_image_alt',
                                    'altValue' => data_get($hero, 'image_alt'),
                                ])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingMissionVision">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseMissionVision" aria-expanded="false"
                            aria-controls="collapseMissionVision">
                            2) Sứ mệnh & Tầm nhìn
                        </button>
                    </h2>
                    <div id="collapseMissionVision" class="accordion-collapse collapse"
                        aria-labelledby="headingMissionVision" data-bs-parent="#aboutContentAccordion">
                        <div class="accordion-body row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-semibold">Sứ mệnh</h6>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <input type="hidden" name="mission_enabled" value="0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="mission_enabled" name="mission_enabled" value="1"
                                                {{ $isChecked($missionEnabled) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="mission_enabled">Hiển thị phần Sứ mệnh</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Tiêu đề</label>
                                        <input class="form-control" name="mission_title" type="text"
                                            value="{{ old('mission_title', data_get($mission, 'title')) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Mô tả</label>
                                        <textarea class="form-control" name="mission_description" rows="4" required>{{ old('mission_description', data_get($mission, 'description')) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        @include('admin.about-content._image_upload', [
                                            'label' => 'Ảnh sứ mệnh',
                                            'name' => 'mission_image',
                                            'oldKey' => 'mission_image',
                                            'value' => data_get($mission, 'image'),
                                            'fileName' => 'mission_image_file',
                                            'altName' => 'mission_image_alt',
                                            'altOldKey' => 'mission_image_alt',
                                            'altValue' => data_get($mission, 'image_alt'),
                                        ])
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-semibold">Tầm nhìn</h6>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <input type="hidden" name="vision_enabled" value="0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="vision_enabled" name="vision_enabled" value="1"
                                                {{ $isChecked($visionEnabled) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="vision_enabled">Hiển thị phần Tầm nhìn</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Tiêu đề</label>
                                        <input class="form-control" name="vision_title" type="text"
                                            value="{{ old('vision_title', data_get($vision, 'title')) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Mô tả</label>
                                        <textarea class="form-control" name="vision_description" rows="4" required>{{ old('vision_description', data_get($vision, 'description')) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        @include('admin.about-content._image_upload', [
                                            'label' => 'Ảnh tầm nhìn',
                                            'name' => 'vision_image',
                                            'oldKey' => 'vision_image',
                                            'value' => data_get($vision, 'image'),
                                            'fileName' => 'vision_image_file',
                                            'altName' => 'vision_image_alt',
                                            'altOldKey' => 'vision_image_alt',
                                            'altValue' => data_get($vision, 'image_alt'),
                                        ])
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingInspirationDefinition">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseInspirationDefinition" aria-expanded="false"
                            aria-controls="collapseInspirationDefinition">
                            3) Cảm hứng & Định nghĩa thương hiệu
                        </button>
                    </h2>
                    <div id="collapseInspirationDefinition" class="accordion-collapse collapse"
                        aria-labelledby="headingInspirationDefinition" data-bs-parent="#aboutContentAccordion">
                        <div class="accordion-body row g-4">
                            <div class="col-md-6">
                                <h6 class="fw-semibold">Cảm hứng thương hiệu</h6>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <input type="hidden" name="inspiration_enabled" value="0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="inspiration_enabled" name="inspiration_enabled" value="1"
                                                {{ $isChecked($inspirationEnabled) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="inspiration_enabled">Hiển thị phần Cảm hứng</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Tiêu đề</label>
                                        <input class="form-control" name="inspiration_title" type="text"
                                            value="{{ old('inspiration_title', data_get($inspiration, 'title')) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Tiêu đề phụ</label>
                                        <input class="form-control" name="inspiration_subtitle" type="text"
                                            value="{{ old('inspiration_subtitle', data_get($inspiration, 'subtitle')) }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Mô tả</label>
                                        <textarea class="form-control" name="inspiration_description" rows="4" required>{{ old('inspiration_description', data_get($inspiration, 'description')) }}</textarea>
                                    </div>
                                    <div class="col-12">
                                        @include('admin.about-content._image_upload', [
                                            'label' => 'Ảnh cảm hứng',
                                            'name' => 'inspiration_image',
                                            'oldKey' => 'inspiration_image',
                                            'value' => data_get($inspiration, 'image'),
                                            'fileName' => 'inspiration_image_file',
                                            'altName' => 'inspiration_image_alt',
                                            'altOldKey' => 'inspiration_image_alt',
                                            'altValue' => data_get($inspiration, 'image_alt'),
                                        ])
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <h6 class="fw-semibold">Định nghĩa thương hiệu</h6>
                                <div class="row g-2">
                                    <div class="col-12">
                                        <input type="hidden" name="definition_enabled" value="0">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch"
                                                id="definition_enabled" name="definition_enabled" value="1"
                                                {{ $isChecked($definitionEnabled) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="definition_enabled">Hiển thị phần Định nghĩa</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Tiêu đề</label>
                                        <input class="form-control" name="definition_title" type="text"
                                            value="{{ old('definition_title', data_get($definition, 'title')) }}" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Mô tả</label>
                                        <textarea class="form-control" name="definition_description" rows="7" required>{{ old('definition_description', data_get($definition, 'description')) }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingCoreValues">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseCoreValues" aria-expanded="false" aria-controls="collapseCoreValues">
                            4) Giá trị cốt lõi
                        </button>
                    </h2>
                    <div id="collapseCoreValues" class="accordion-collapse collapse" aria-labelledby="headingCoreValues"
                        data-bs-parent="#aboutContentAccordion">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="hidden" name="core_enabled" value="0">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" role="switch" id="core_enabled"
                                            name="core_enabled" value="1"
                                            {{ $isChecked($coreEnabled) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="core_enabled">Hiển thị section này</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Tiêu đề section</label>
                                    <input class="form-control" name="core_heading" type="text"
                                        value="{{ old('core_heading', data_get($core, 'heading')) }}" required>
                                    <div class="section-tip">Bạn có thể thêm/xóa item linh hoạt.</div>
                                </div>
                            </div>

                            <div class="about-repeater mt-3" data-repeater data-template-id="tpl-core-item"
                                data-next-index="{{ $coreNextIndex }}" data-min-items="1">
                                <div class="about-repeater-items">
                                    @foreach ($coreRows as $idx => $item)
                                        <div class="about-repeater-item" data-repeater-item>
                                            <div class="about-repeater-item__head">
                                                <p class="about-repeater-item__title">Giá trị #<span class="js-item-order">{{ $idx + 1 }}</span></p>
                                                <button class="btn btn-sm btn-outline-danger" type="button" data-repeater-remove>Xóa</button>
                                            </div>

                                            <div class="row g-2">
                                                <div class="col-md-3">
                                                    <label class="form-label">Tên giá trị</label>
                                                    <input class="form-control" name="core_items[{{ $idx }}][title]" type="text"
                                                        value="{{ data_get($item, 'title') }}">
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Mô tả</label>
                                                    <textarea class="form-control" name="core_items[{{ $idx }}][description]" rows="2">{{ data_get($item, 'description') }}</textarea>
                                                </div>
                                                <div class="col-md-5">
                                                    @include('admin.about-content._image_upload', [
                                                        'label' => 'Ảnh giá trị',
                                                        'name' => 'core_items[' . $idx . '][image]',
                                                        'oldKey' => 'core_items.' . $idx . '.image',
                                                        'value' => data_get($item, 'image'),
                                                        'fileName' => 'core_items[' . $idx . '][image_file]',
                                                        'altName' => 'core_items[' . $idx . '][image_alt]',
                                                        'altOldKey' => 'core_items.' . $idx . '.image_alt',
                                                        'altValue' => data_get($item, 'image_alt'),
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="btn btn-sm btn-outline-primary" type="button" data-repeater-add>
                                    + Thêm giá trị cốt lõi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingManifesto">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseManifesto" aria-expanded="false" aria-controls="collapseManifesto">
                            5) Cam kết thương hiệu
                        </button>
                    </h2>
                    <div id="collapseManifesto" class="accordion-collapse collapse" aria-labelledby="headingManifesto"
                        data-bs-parent="#aboutContentAccordion">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <input type="hidden" name="manifesto_enabled" value="0">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="manifesto_enabled" name="manifesto_enabled" value="1"
                                            {{ $isChecked($manifestoEnabled) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="manifesto_enabled">Hiển thị section này</label>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Tiêu đề section</label>
                                    <input class="form-control" name="manifesto_heading" type="text"
                                        value="{{ old('manifesto_heading', data_get($manifesto, 'heading')) }}" required>
                                </div>
                            </div>

                            <div class="about-repeater mt-3" data-repeater data-template-id="tpl-manifesto-item"
                                data-next-index="{{ $manifestoNextIndex }}" data-min-items="1">
                                <div class="about-repeater-items">
                                    @foreach ($manifestoRows as $idx => $item)
                                        <div class="about-repeater-item" data-repeater-item>
                                            <div class="about-repeater-item__head">
                                                <p class="about-repeater-item__title">Cam kết #<span class="js-item-order">{{ $idx + 1 }}</span></p>
                                                <button class="btn btn-sm btn-outline-danger" type="button" data-repeater-remove>Xóa</button>
                                            </div>

                                            <div class="row g-2">
                                                <div class="col-md-7">
                                                    <label class="form-label">Quote</label>
                                                    <textarea class="form-control" name="manifesto_items[{{ $idx }}][quote]" rows="3">{{ data_get($item, 'quote') }}</textarea>
                                                </div>
                                                <div class="col-md-5">
                                                    @include('admin.about-content._image_upload', [
                                                        'label' => 'Ảnh cam kết',
                                                        'name' => 'manifesto_items[' . $idx . '][image]',
                                                        'oldKey' => 'manifesto_items.' . $idx . '.image',
                                                        'value' => data_get($item, 'image'),
                                                        'fileName' => 'manifesto_items[' . $idx . '][image_file]',
                                                        'altName' => 'manifesto_items[' . $idx . '][image_alt]',
                                                        'altOldKey' => 'manifesto_items.' . $idx . '.image_alt',
                                                        'altValue' => data_get($item, 'image_alt'),
                                                    ])
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="btn btn-sm btn-outline-primary" type="button" data-repeater-add>
                                    + Thêm cam kết
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingAdvantages">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseAdvantages" aria-expanded="false" aria-controls="collapseAdvantages">
                            6) Lợi thế cạnh tranh
                        </button>
                    </h2>
                    <div id="collapseAdvantages" class="accordion-collapse collapse" aria-labelledby="headingAdvantages"
                        data-bs-parent="#aboutContentAccordion">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <input type="hidden" name="advantages_enabled" value="0">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="advantages_enabled" name="advantages_enabled" value="1"
                                            {{ $isChecked($advantagesEnabled) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="advantages_enabled">Hiển thị section này</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tiêu đề</label>
                                    <input class="form-control" name="advantages_title" type="text"
                                        value="{{ old('advantages_title', data_get($advantages, 'title')) }}" required>
                                </div>
                                <div class="col-12">
                                    @include('admin.about-content._image_upload', [
                                        'label' => 'Ảnh lợi thế cạnh tranh',
                                        'name' => 'advantages_image',
                                        'oldKey' => 'advantages_image',
                                        'value' => data_get($advantages, 'image'),
                                        'fileName' => 'advantages_image_file',
                                        'altName' => 'advantages_image_alt',
                                        'altOldKey' => 'advantages_image_alt',
                                        'altValue' => data_get($advantages, 'image_alt'),
                                    ])
                                </div>
                            </div>

                            <div class="about-repeater mt-3" data-repeater data-template-id="tpl-advantages-item"
                                data-next-index="{{ $advantagesNextIndex }}" data-min-items="1">
                                <div class="about-repeater-items">
                                    @foreach ($advantagesRows as $idx => $item)
                                        <div class="about-repeater-item" data-repeater-item>
                                            <div class="about-repeater-item__head">
                                                <p class="about-repeater-item__title">Lợi thế #<span class="js-item-order">{{ $idx + 1 }}</span></p>
                                                <button class="btn btn-sm btn-outline-danger" type="button" data-repeater-remove>Xóa</button>
                                            </div>

                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <label class="form-label">Tiêu đề</label>
                                                    <input class="form-control" name="advantages_items[{{ $idx }}][title]"
                                                        type="text" value="{{ data_get($item, 'title') }}">
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label">Mô tả</label>
                                                    <textarea class="form-control" name="advantages_items[{{ $idx }}][description]" rows="2">{{ data_get($item, 'description') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="btn btn-sm btn-outline-primary" type="button" data-repeater-add>
                                    + Thêm lợi thế
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingCeo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseCeo" aria-expanded="false" aria-controls="collapseCeo">
                            7) Đội ngũ sáng lập
                        </button>
                    </h2>
                    <div id="collapseCeo" class="accordion-collapse collapse" aria-labelledby="headingCeo"
                        data-bs-parent="#aboutContentAccordion">
                        <div class="accordion-body row g-3">
                            <div class="col-12">
                                <input type="hidden" name="ceo_enabled" value="0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" role="switch" id="ceo_enabled"
                                        name="ceo_enabled" value="1" {{ $isChecked($ceoEnabled) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="ceo_enabled">Hiển thị section này</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Eyebrow</label>
                                <input class="form-control" name="ceo_eyebrow" type="text"
                                    value="{{ old('ceo_eyebrow', data_get($ceo, 'eyebrow')) }}">
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Tiêu đề</label>
                                <input class="form-control" name="ceo_title" type="text"
                                    value="{{ old('ceo_title', data_get($ceo, 'title')) }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mô tả đoạn 1</label>
                                <textarea class="form-control" name="ceo_description_1" rows="4" required>{{ old('ceo_description_1', data_get($ceo, 'description_1')) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Mô tả đoạn 2</label>
                                <textarea class="form-control" name="ceo_description_2" rows="4" required>{{ old('ceo_description_2', data_get($ceo, 'description_2')) }}</textarea>
                            </div>
                            <div class="col-12">
                                @include('admin.about-content._image_upload', [
                                    'label' => 'Ảnh đội ngũ sáng lập',
                                    'name' => 'ceo_image',
                                    'oldKey' => 'ceo_image',
                                    'value' => data_get($ceo, 'image'),
                                    'fileName' => 'ceo_image_file',
                                    'altName' => 'ceo_image_alt',
                                    'altOldKey' => 'ceo_image_alt',
                                    'altValue' => data_get($ceo, 'image_alt'),
                                ])
                            </div>
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingCapacity">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseCapacity" aria-expanded="false" aria-controls="collapseCapacity">
                            8) Hồ sơ năng lực
                        </button>
                    </h2>
                    <div id="collapseCapacity" class="accordion-collapse collapse" aria-labelledby="headingCapacity"
                        data-bs-parent="#aboutContentAccordion">
                        <div class="accordion-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <input type="hidden" name="capacity_enabled" value="0">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch"
                                            id="capacity_enabled" name="capacity_enabled" value="1"
                                            {{ $isChecked($capacityEnabled) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="capacity_enabled">Hiển thị section này</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tiêu đề</label>
                                    <input class="form-control" name="capacity_heading" type="text"
                                        value="{{ old('capacity_heading', data_get($capacity, 'heading')) }}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Lead</label>
                                    <textarea class="form-control" name="capacity_lead" rows="3" required>{{ old('capacity_lead', data_get($capacity, 'lead')) }}</textarea>
                                </div>
                            </div>

                            <div class="about-repeater mt-3" data-repeater data-template-id="tpl-capacity-stat"
                                data-next-index="{{ $statsNextIndex }}" data-min-items="1">
                                <div class="about-repeater-items">
                                    @foreach ($statsRows as $idx => $stat)
                                        <div class="about-repeater-item" data-repeater-item>
                                            <div class="about-repeater-item__head">
                                                <p class="about-repeater-item__title">Chỉ số #<span class="js-item-order">{{ $idx + 1 }}</span></p>
                                                <button class="btn btn-sm btn-outline-danger" type="button" data-repeater-remove>Xóa</button>
                                            </div>
                                            <div class="row g-2">
                                                <div class="col-md-3">
                                                    <label class="form-label">Giá trị</label>
                                                    <input class="form-control" name="capacity_stats[{{ $idx }}][value]"
                                                        type="text" value="{{ data_get($stat, 'value') }}">
                                                </div>
                                                <div class="col-md-9">
                                                    <label class="form-label">Mô tả</label>
                                                    <input class="form-control" name="capacity_stats[{{ $idx }}][label]"
                                                        type="text" value="{{ data_get($stat, 'label') }}">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button class="btn btn-sm btn-outline-primary" type="button" data-repeater-add>
                                    + Thêm chỉ số
                                </button>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6 border rounded p-3 bg-light-subtle">
                                    <p class="fw-semibold mb-2">Nút hành động 1</p>
                                    <label class="form-label">Nhãn nút</label>
                                    <input class="form-control mb-2" name="capacity_action_1_label" type="text"
                                        value="{{ old('capacity_action_1_label', data_get($capacity, 'action_1_label')) }}">
                                    <label class="form-label">URL</label>
                                    <input class="form-control" name="capacity_action_1_url" type="text"
                                        value="{{ old('capacity_action_1_url', data_get($capacity, 'action_1_url')) }}">
                                </div>

                                <div class="col-md-6 border rounded p-3 bg-light-subtle">
                                    <p class="fw-semibold mb-2">Nút hành động 2</p>
                                    <label class="form-label">Nhãn nút</label>
                                    <input class="form-control mb-2" name="capacity_action_2_label" type="text"
                                        value="{{ old('capacity_action_2_label', data_get($capacity, 'action_2_label')) }}">
                                    <label class="form-label">URL</label>
                                    <input class="form-control" name="capacity_action_2_url" type="text"
                                        value="{{ old('capacity_action_2_url', data_get($capacity, 'action_2_url')) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end">
                <button class="btn btn-dark px-4" type="submit">Lưu nội dung About Us</button>
            </div>
        </form>

        <template id="tpl-core-item">
            <div class="about-repeater-item" data-repeater-item>
                <div class="about-repeater-item__head">
                    <p class="about-repeater-item__title">Giá trị #<span class="js-item-order">0</span></p>
                    <button class="btn btn-sm btn-outline-danger" type="button" data-repeater-remove>Xóa</button>
                </div>

                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">Tên giá trị</label>
                        <input class="form-control" name="core_items[__INDEX__][title]" type="text" value="">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="core_items[__INDEX__][description]" rows="2"></textarea>
                    </div>
                    <div class="col-md-5">
                        @include('admin.about-content._image_upload', [
                            'label' => 'Ảnh giá trị',
                            'name' => 'core_items[__INDEX__][image]',
                            'oldKey' => 'core_items.__INDEX__.image',
                            'value' => '',
                            'fileName' => 'core_items[__INDEX__][image_file]',
                            'altName' => 'core_items[__INDEX__][image_alt]',
                            'altOldKey' => 'core_items.__INDEX__.image_alt',
                            'altValue' => '',
                        ])
                    </div>
                </div>
            </div>
        </template>

        <template id="tpl-manifesto-item">
            <div class="about-repeater-item" data-repeater-item>
                <div class="about-repeater-item__head">
                    <p class="about-repeater-item__title">Cam kết #<span class="js-item-order">0</span></p>
                    <button class="btn btn-sm btn-outline-danger" type="button" data-repeater-remove>Xóa</button>
                </div>

                <div class="row g-2">
                    <div class="col-md-7">
                        <label class="form-label">Quote</label>
                        <textarea class="form-control" name="manifesto_items[__INDEX__][quote]" rows="3"></textarea>
                    </div>
                    <div class="col-md-5">
                        @include('admin.about-content._image_upload', [
                            'label' => 'Ảnh cam kết',
                            'name' => 'manifesto_items[__INDEX__][image]',
                            'oldKey' => 'manifesto_items.__INDEX__.image',
                            'value' => '',
                            'fileName' => 'manifesto_items[__INDEX__][image_file]',
                            'altName' => 'manifesto_items[__INDEX__][image_alt]',
                            'altOldKey' => 'manifesto_items.__INDEX__.image_alt',
                            'altValue' => '',
                        ])
                    </div>
                </div>
            </div>
        </template>

        <template id="tpl-advantages-item">
            <div class="about-repeater-item" data-repeater-item>
                <div class="about-repeater-item__head">
                    <p class="about-repeater-item__title">Lợi thế #<span class="js-item-order">0</span></p>
                    <button class="btn btn-sm btn-outline-danger" type="button" data-repeater-remove>Xóa</button>
                </div>

                <div class="row g-2">
                    <div class="col-md-4">
                        <label class="form-label">Tiêu đề</label>
                        <input class="form-control" name="advantages_items[__INDEX__][title]" type="text" value="">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label">Mô tả</label>
                        <textarea class="form-control" name="advantages_items[__INDEX__][description]" rows="2"></textarea>
                    </div>
                </div>
            </div>
        </template>

        <template id="tpl-capacity-stat">
            <div class="about-repeater-item" data-repeater-item>
                <div class="about-repeater-item__head">
                    <p class="about-repeater-item__title">Chỉ số #<span class="js-item-order">0</span></p>
                    <button class="btn btn-sm btn-outline-danger" type="button" data-repeater-remove>Xóa</button>
                </div>

                <div class="row g-2">
                    <div class="col-md-3">
                        <label class="form-label">Giá trị</label>
                        <input class="form-control" name="capacity_stats[__INDEX__][value]" type="text" value="">
                    </div>
                    <div class="col-md-9">
                        <label class="form-label">Mô tả</label>
                        <input class="form-control" name="capacity_stats[__INDEX__][label]" type="text" value="">
                    </div>
                </div>
            </div>
        </template>
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

            function bindPreviewField(fieldWrapper) {
                if (!fieldWrapper || fieldWrapper.dataset.previewBound === '1') {
                    return;
                }

                var pathInput = fieldWrapper.querySelector('.js-image-path-input');
                var fileInput = fieldWrapper.querySelector('.js-image-file-input');
                if (!pathInput) {
                    return;
                }

                var previewId = pathInput.getAttribute('data-preview-id');
                var metaId = pathInput.getAttribute('data-meta-id');
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

            function initImageFields(scope) {
                var root = scope || document;
                root.querySelectorAll('.js-image-field').forEach(bindPreviewField);
            }

            function updateRepeaterOrder(repeater) {
                var items = repeater.querySelectorAll('[data-repeater-item]');
                items.forEach(function(item, index) {
                    item.querySelectorAll('.js-item-order').forEach(function(el) {
                        el.textContent = index + 1;
                    });
                });
            }

            function createNodeFromTemplate(html) {
                var tmp = document.createElement('div');
                tmp.innerHTML = html.trim();
                return tmp.firstElementChild;
            }

            initImageFields(document);

            document.querySelectorAll('[data-repeater]').forEach(function(repeater) {
                updateRepeaterOrder(repeater);

                var addBtn = repeater.querySelector('[data-repeater-add]');
                var templateId = repeater.getAttribute('data-template-id');
                var minItems = parseInt(repeater.getAttribute('data-min-items') || '1', 10);

                if (addBtn && templateId) {
                    addBtn.addEventListener('click', function() {
                        var template = document.getElementById(templateId);
                        if (!template) {
                            return;
                        }

                        var nextIndex = parseInt(repeater.getAttribute('data-next-index') || '0', 10);
                        var html = template.innerHTML.replace(/__INDEX__/g, String(nextIndex));
                        var node = createNodeFromTemplate(html);
                        if (!node) {
                            return;
                        }

                        repeater.setAttribute('data-next-index', String(nextIndex + 1));
                        repeater.querySelector('.about-repeater-items').appendChild(node);
                        initImageFields(node);
                        updateRepeaterOrder(repeater);
                    });
                }

                repeater.addEventListener('click', function(event) {
                    var removeBtn = event.target.closest('[data-repeater-remove]');
                    if (!removeBtn) {
                        return;
                    }

                    var item = removeBtn.closest('[data-repeater-item]');
                    if (!item) {
                        return;
                    }

                    var totalItems = repeater.querySelectorAll('[data-repeater-item]').length;
                    if (totalItems <= minItems) {
                        alert('Cần ít nhất ' + minItems + ' item để hiển thị section này.');
                        return;
                    }

                    item.remove();
                    updateRepeaterOrder(repeater);
                });
            });
        });
    </script>
@endpush
