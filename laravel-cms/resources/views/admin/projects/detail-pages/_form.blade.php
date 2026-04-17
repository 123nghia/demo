@php
    /** @var \App\Models\ProjectDetailPage|null $detailPage */
    $detailPage = $detailPage ?? null;

    $thumbnailValue = old('thumbnail_image', $detailPage->thumbnail_image ?? '');
    $thumbnailClickAction = old('thumbnail_click_action', $detailPage->thumbnail_click_action ?? 'link');
    $thumbnailPreviewId = 'project-detail-thumbnail-preview';
    $thumbnailMetaId = 'project-detail-thumbnail-meta';

    $galleryImages = old('gallery_images_input');
    if (is_null($galleryImages)) {
        $galleryImages = implode("\n", is_array($detailPage->gallery_images ?? null) ? $detailPage->gallery_images : []);
    }

    $galleryPreviewImages = collect(preg_split('/\r\n|\r|\n|,/', (string) $galleryImages))
        ->map(function ($image) {
            return trim((string) $image);
        })
        ->filter()
        ->unique()
        ->values();
@endphp

@once
    @push('head')
        <style>
            .thumb-upload-box {
                border: 1px solid #e2e8f0;
                border-radius: 12px;
                background: linear-gradient(180deg, #fbfdff 0%, #f6f9fc 100%);
                padding: .8rem;
            }

            .thumb-preview-panel {
                border: 1px solid #dbe5f0;
                border-radius: 12px;
                background: #f8fafc;
                padding: .7rem;
            }

            .thumb-preview-panel img {
                border-radius: 10px;
                border: 1px solid #dbe5f0;
                background: #fff;
            }

            .thumb-behavior-box {
                border: 1px solid #dbe5f0;
                border-radius: 12px;
                background: linear-gradient(180deg, #f8fbff 0%, #f1f7ff 100%);
                padding: .9rem;
            }

            .thumb-behavior-options {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
                gap: .65rem;
                margin-top: .55rem;
            }

            .thumb-behavior-option {
                display: flex;
                gap: .6rem;
                align-items: flex-start;
                border: 1px solid #dbe5f0;
                border-radius: 10px;
                background: #fff;
                padding: .7rem .8rem;
                cursor: pointer;
                transition: border-color .2s ease, box-shadow .2s ease, background-color .2s ease;
            }

            .thumb-behavior-option:hover {
                border-color: #93c5fd;
                box-shadow: 0 6px 14px rgba(59, 130, 246, .08);
            }

            .thumb-behavior-option.is-selected {
                border-color: #3b82f6;
                background: #eff6ff;
                box-shadow: 0 8px 18px rgba(59, 130, 246, .14);
            }

            .thumb-behavior-option input {
                margin-top: .15rem;
            }

            .thumb-behavior-option strong {
                display: block;
                line-height: 1.25;
            }

            .thumb-behavior-option span {
                display: block;
                color: #64748b;
                font-size: .84rem;
                line-height: 1.35;
            }

            .gallery-upload-box {
                border: 1px solid #dbe5f0;
                border-radius: 12px;
                background: linear-gradient(180deg, #fbfdff 0%, #f6f9fc 100%);
                padding: .95rem;
            }

            .gallery-preview-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(132px, 1fr));
                gap: .75rem;
                margin-top: .85rem;
            }

            .gallery-preview-item {
                border: 1px solid #dbe5f0;
                border-radius: 10px;
                background: #fff;
                overflow: hidden;
            }

            .gallery-preview-item img {
                width: 100%;
                aspect-ratio: 4 / 3;
                object-fit: cover;
                background: #eef2f7;
            }

            .gallery-preview-item span {
                display: block;
                padding: .45rem .55rem;
                color: #64748b;
                font-size: .78rem;
                line-height: 1.25;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .gallery-url-panel {
                margin-top: .9rem;
            }
        </style>
    @endpush
@endonce

<div class="row g-3">
    <div class="col-md-7">
        <label class="form-label" for="title">Tiêu đề trang chi tiết</label>
        <input class="form-control" id="title" name="title" type="text"
            value="{{ old('title', $detailPage->title ?? '') }}" required>
    </div>

    <div class="col-md-3">
        <label class="form-label" for="slug">Slug route</label>
        <input class="form-control" id="slug" name="slug" type="text"
            value="{{ old('slug', $detailPage->slug ?? '') }}" required>
    </div>

    <div class="col-md-2">
        <label class="form-label" for="sort_order">Thứ tự</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0"
            value="{{ old('sort_order', $detailPage->sort_order ?? 0) }}">
    </div>

    <div class="col-md-8 js-project-thumbnail-field thumb-upload-box d-none">
        <input class="js-project-thumbnail-path" id="thumbnail_image" name="thumbnail_image" type="hidden"
            value="{{ $thumbnailValue }}" data-preview-id="{{ $thumbnailPreviewId }}" data-meta-id="{{ $thumbnailMetaId }}">
    </div>

    <div class="col-md-12 thumb-upload-box">
        <label class="form-label" for="thumbnail_image_file">Upload thumbnail</label>
        <input class="form-control js-project-thumbnail-file" id="thumbnail_image_file" name="thumbnail_image_file"
            type="file" accept="image/*" data-preview-id="{{ $thumbnailPreviewId }}" data-meta-id="{{ $thumbnailMetaId }}">
        <div class="form-text">
            Khuyến nghị: <strong>1200 × 1600 px</strong> (tỷ lệ 3:4), tối đa 5MB, ưu tiên JPG/WebP để tải nhanh.
        </div>
    </div>

    <div class="col-12">
        <div class="thumb-preview-panel">
            <img id="{{ $thumbnailPreviewId }}" src="{{ $thumbnailValue ?: '' }}" alt="Xem trước thumbnail"
                style="max-height: 220px; max-width: 100%; object-fit: cover; {{ empty($thumbnailValue) ? 'display:none;' : '' }}">
            <div id="{{ $thumbnailMetaId }}" class="small text-muted mt-2">
                {{ empty($thumbnailValue) ? 'Chưa có ảnh thumbnail.' : 'Đang đọc thông tin ảnh...' }}
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="thumb-behavior-box">
            <div class="fw-semibold">Hành vi click thumbnail trên Trang chủ</div>
            <div class="small text-muted">Bạn có thể cho card điều hướng vào trang chi tiết, hoặc chỉ mở ảnh ở giữa màn hình (lightbox).</div>

            <div class="thumb-behavior-options">
                <label class="thumb-behavior-option" data-thumb-option>
                    <input type="radio" name="thumbnail_click_action" value="link"
                        @checked($thumbnailClickAction === 'link')>
                    <div>
                        <strong>Có link đến trang chi tiết</strong>
                        <span>Hover/click card sẽ điều hướng đến bài viết theo slug hiện tại.</span>
                    </div>
                </label>

                <label class="thumb-behavior-option" data-thumb-option>
                    <input type="radio" name="thumbnail_click_action" value="lightbox"
                        @checked($thumbnailClickAction === 'lightbox')>
                    <div>
                        <strong>Chỉ xem ảnh (không link)</strong>
                        <span>Hover/click card sẽ mở ảnh lớn ở giữa màn hình, không chuyển trang.</span>
                    </div>
                </label>
            </div>

            @error('thumbnail_click_action')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input type="hidden" name="is_published" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published"
                @checked(old('is_published', $detailPage ? $detailPage->is_published : true))>
            <label class="form-check-label" for="is_published">Hiển thị công khai</label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label" for="summary">Mô tả ngắn</label>
        <textarea class="form-control" id="summary" name="summary" rows="2">{{ old('summary', $detailPage->summary ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <div class="gallery-upload-box" data-gallery-upload>
            <label class="form-label" for="gallery_image_files">Upload ảnh gallery</label>
            <input class="form-control" id="gallery_image_files" name="gallery_image_files[]" type="file"
                accept="image/*" multiple data-gallery-file-input data-preview-target="gallery-selected-preview">
            <div class="form-text">
                Có thể chọn 1 hoặc nhiều ảnh cùng lúc. Ảnh upload mới sẽ được thêm vào cuối gallery, tối đa 5MB/mỗi ảnh.
            </div>

            <div id="gallery-selected-preview" class="gallery-preview-grid d-none" aria-live="polite"></div>

            @if ($galleryPreviewImages->isNotEmpty())
                <div class="small fw-semibold text-muted mt-3">Ảnh đang có trong gallery</div>
                <div class="gallery-preview-grid">
                    @foreach ($galleryPreviewImages as $galleryImage)
                        <div class="gallery-preview-item">
                            <img src="{{ $galleryImage }}" alt="Gallery image {{ $loop->iteration }}" loading="lazy">
                            <span title="{{ $galleryImage }}">{{ $galleryImage }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="small text-muted mt-3">Chưa có ảnh gallery.</div>
            @endif

            <textarea class="d-none" id="gallery_images_input" name="gallery_images_input">{{ $galleryImages }}</textarea>

            @error('gallery_image_files')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
            @error('gallery_image_files.*')
                <div class="text-danger small mt-2">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-12">
        <label class="form-label" for="content">Nội dung chi tiết</label>
        <textarea class="form-control js-rich-editor" id="content" name="content" rows="8">{{ old('content', $detailPage->content ?? '') }}</textarea>
    </div>
</div>

@once
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

                function bindThumbnailPreview(field) {
                    if (!field || field.dataset.previewBound === '1') {
                        return;
                    }

                    var pathInput = field.querySelector('.js-project-thumbnail-path');
                    if (!pathInput) {
                        return;
                    }

                    var previewId = pathInput.getAttribute('data-preview-id');
                    var metaId = pathInput.getAttribute('data-meta-id');
                    var fileInput = document.querySelector('.js-project-thumbnail-file[data-preview-id="' + previewId + '"]');
                    var previewImg = document.getElementById(previewId);
                    var metaEl = document.getElementById(metaId);

                    if (!previewImg || !metaEl) {
                        return;
                    }

                    field.dataset.previewBound = '1';

                    function showPathPreview() {
                        var path = (pathInput.value || '').trim();

                        if (path === '') {
                            previewImg.style.display = 'none';
                            previewImg.removeAttribute('src');
                            metaEl.textContent = 'Chưa có ảnh thumbnail.';
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

                function bindGalleryFilePreview(input) {
                    if (!input || input.dataset.previewBound === '1') {
                        return;
                    }

                    var previewTargetId = input.getAttribute('data-preview-target');
                    var previewTarget = previewTargetId ? document.getElementById(previewTargetId) : null;
                    if (!previewTarget) {
                        return;
                    }

                    input.dataset.previewBound = '1';

                    input.addEventListener('change', function() {
                        previewTarget.innerHTML = '';

                        var files = Array.prototype.slice.call(input.files || []);
                        if (!files.length) {
                            previewTarget.classList.add('d-none');
                            return;
                        }

                        files.forEach(function(file) {
                            var item = document.createElement('div');
                            item.className = 'gallery-preview-item';

                            var image = document.createElement('img');
                            image.alt = file.name || 'Gallery image';
                            image.src = URL.createObjectURL(file);

                            var caption = document.createElement('span');
                            caption.title = file.name || '';
                            caption.textContent = (file.name || 'Ảnh mới') + ' · ' + formatBytes(file.size);

                            image.onload = function() {
                                URL.revokeObjectURL(image.src);
                            };

                            item.appendChild(image);
                            item.appendChild(caption);
                            previewTarget.appendChild(item);
                        });

                        previewTarget.classList.remove('d-none');
                    });
                }

                function bindThumbnailActionOptions() {
                    var options = document.querySelectorAll('[data-thumb-option]');
                    if (!options.length) {
                        return;
                    }

                    var syncOptionState = function() {
                        options.forEach(function(option) {
                            var input = option.querySelector('input[type="radio"]');
                            option.classList.toggle('is-selected', !!(input && input.checked));
                        });
                    };

                    options.forEach(function(option) {
                        option.addEventListener('click', syncOptionState);
                    });

                    document.querySelectorAll('input[name="thumbnail_click_action"]').forEach(function(input) {
                        input.addEventListener('change', syncOptionState);
                    });

                    syncOptionState();
                }

                document.querySelectorAll('.js-project-thumbnail-field').forEach(bindThumbnailPreview);
                document.querySelectorAll('[data-gallery-file-input]').forEach(bindGalleryFilePreview);
                bindThumbnailActionOptions();
            });
        </script>
    @endpush
@endonce
