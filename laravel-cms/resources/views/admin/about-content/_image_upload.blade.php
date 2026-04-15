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
@endphp

<div class="js-image-field about-image-uploader">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-2">
        <div>
            <label class="form-label mb-0">{{ $label }}</label>
            <div class="form-text mt-0">Upload trực tiếp để thay ảnh nhanh (không cần nhập đường dẫn).</div>
        </div>
        @if (!empty($fileName))
            <input class="form-control form-control-sm js-image-file-input" name="{{ $fileName }}" type="file"
                accept="image/*" data-preview-id="{{ $previewId }}" data-meta-id="{{ $metaId }}"
                style="max-width: 220px;">
        @endif
    </div>

    <input class="js-image-path-input" name="{{ $name }}" type="hidden" value="{{ $pathValue }}"
        data-preview-id="{{ $previewId }}" data-meta-id="{{ $metaId }}">

    @if (!empty($altName))
        <div class="mb-2">
            <label class="form-label small mb-1">ALT ảnh (SEO)</label>
            <input class="form-control form-control-sm" name="{{ $altName }}" type="text"
                value="{{ $altValue }}" placeholder="Mô tả ngắn nội dung ảnh">
        </div>
    @endif

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
