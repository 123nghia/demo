@php
    $label = $label ?? 'Ảnh';
    $name = $name ?? 'image';
    $oldKey = $oldKey ?? $name;
    $fileName = $fileName ?? null;
    $altName = $altName ?? null;
    $altOldKey = $altOldKey ?? $altName;
    $altValue = $altName ? old($altOldKey, $altValue ?? '') : ($altValue ?? '');
    $pathValue = old($oldKey, $value ?? '');
    $sizeHint = $sizeHint ?? 'Tối đa 5MB';

    $idBase = trim(preg_replace('/[^a-z0-9]+/i', '-', $oldKey), '-');
    if ($idBase === '') {
        $idBase = uniqid('img', false);
    }

    $previewId = 'preview-' . $idBase;
    $metaId = 'meta-' . $idBase;
    $advancedId = 'advanced-' . $idBase;
@endphp

<div class="js-image-field about-image-uploader">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <div>
            <label class="form-label mb-0">{{ $label }}</label>
            <div class="form-text mt-0">Upload trực tiếp để thay ảnh nhanh.</div>
        </div>
        <div class="d-flex gap-2 align-items-center">
            @if (!empty($fileName))
                <input class="form-control form-control-sm js-image-file-input" name="{{ $fileName }}" type="file"
                    accept="image/*" data-preview-id="{{ $previewId }}" data-meta-id="{{ $metaId }}"
                    style="max-width: 220px;">
            @endif
            <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse"
                data-bs-target="#{{ $advancedId }}" aria-expanded="false" aria-controls="{{ $advancedId }}">
                Tùy chọn nâng cao
            </button>
        </div>
    </div>

    <div class="collapse" id="{{ $advancedId }}">
        <div class="p-2 border rounded bg-light-subtle mb-2">
            <label class="form-label small mb-1">Đường dẫn ảnh thủ công</label>
            <input class="form-control form-control-sm js-image-path-input" name="{{ $name }}" type="text"
                value="{{ $pathValue }}" data-preview-id="{{ $previewId }}" data-meta-id="{{ $metaId }}"
                placeholder="/theme/assets/... hoặc https://...">

            @if (!empty($altName))
                <label class="form-label small mt-2 mb-1">ALT ảnh (SEO)</label>
                <input class="form-control form-control-sm" name="{{ $altName }}" type="text"
                    value="{{ $altValue }}" placeholder="Mô tả ngắn nội dung ảnh">
            @endif
        </div>
    </div>

    <div class="border rounded bg-white p-2">
        <img id="{{ $previewId }}" src="{{ $pathValue ?: '' }}" alt="Xem trước {{ $label }}"
            style="max-height: 180px; max-width: 100%; object-fit: contain; {{ empty($pathValue) ? 'display:none;' : '' }}">
        <div id="{{ $metaId }}" class="small text-muted mt-2">
            {{ empty($pathValue) ? 'Chưa có ảnh.' : 'Đang đọc thông tin ảnh...' }}
        </div>

        @if (!empty($sizeHint))
            <div class="small text-muted mt-1">{{ $sizeHint }}</div>
        @endif
    </div>
</div>
