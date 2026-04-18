# Image Interactions - Code Snippets Reference

## PHP Upload Handlers

### ProjectDetailPageController - Gallery & Thumbnail Upload
```php
// Storage method
private function storeUploadedFile(UploadedFile $file, string $filenamePrefix): ?string
{
    $uploadDirectory = public_path('uploads/projects');
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $extension = strtolower((string) $file->getClientOriginalExtension());
    $filename = $filenamePrefix . '-' . date('YmdHis') . '-' . Str::random(8) . 
        ($extension ? '.' . $extension : '');

    $file->move($uploadDirectory, $filename);

    return '/uploads/projects/' . $filename;
}

// Single file upload
private function storeUploadedImage(Request $request, string $fileInput, string $filenamePrefix): ?string
{
    if (!$request->hasFile($fileInput)) {
        return null;
    }

    $file = $request->file($fileInput);
    if (!$file instanceof UploadedFile || !$file->isValid()) {
        return null;
    }

    return $this->storeUploadedFile($file, $filenamePrefix);
}

// Multiple files upload (gallery)
private function storeUploadedImages(Request $request, string $fileInput, string $filenamePrefix): array
{
    if (!$request->hasFile($fileInput)) {
        return [];
    }

    $files = $request->file($fileInput, []);
    if ($files instanceof UploadedFile) {
        $files = [$files];
    }

    return collect($files)
        ->filter(function ($file) {
            return $file instanceof UploadedFile && $file->isValid();
        })
        ->map(function (UploadedFile $file) use ($filenamePrefix) {
            return $this->storeUploadedFile($file, $filenamePrefix);
        })
        ->filter()
        ->values()
        ->all();
}

// Parse gallery images from text input
private function parseGalleryImages(string $raw): array
{
    $normalized = str_replace(["\r\n", "\r", ','], "\n", $raw);
    $lines = array_map('trim', explode("\n", $normalized));
    $lines = array_filter($lines, function ($line) {
        return $line !== '';
    });

    return array_values(array_unique($lines));
}

// Merge typed paths with uploaded paths
private function mergeGalleryImages(array $typedImages, array $uploadedImages): array
{
    return array_values(array_unique(array_filter(
        array_merge($typedImages, $uploadedImages),
        function ($path) {
            return is_string($path) && trim($path) !== '';
        }
    )));
}

// In validatedData() method:
$validated = $request->validate([
    'thumbnail_image' => 'nullable|string|max:255',
    'thumbnail_image_file' => 'nullable|image|max:5120',
    'thumbnail_click_action' => 'nullable|in:link,lightbox',
    'gallery_images_input' => 'nullable|string|max:30000',
    'gallery_image_files' => 'nullable|array|max:80',
    'gallery_image_files.*' => 'nullable|image|max:5120',
]);

$galleryImages = $this->parseGalleryImages((string) ($validated['gallery_images_input'] ?? ''));
$uploadedGalleryImages = $this->storeUploadedImages(
    $request,
    'gallery_image_files',
    'project-detail-gallery'
);

$validated['gallery_images'] = $this->mergeGalleryImages($galleryImages, $uploadedGalleryImages);
unset($validated['gallery_images_input'], $validated['gallery_image_files']);

$validated['thumbnail_click_action'] = ($validated['thumbnail_click_action'] ?? 'link') === 'lightbox'
    ? 'lightbox'
    : 'link';
```

### Generic Upload Handler Pattern
```php
private function replaceWithUploadedFile(Request $request, string $fileInput, string $field, array &$payload): void
{
    if (!$request->hasFile($fileInput)) {
        return;
    }

    $file = $request->file($fileInput);
    if (!$file || !$file->isValid()) {
        return;
    }

    $uploadDirectory = public_path('uploads/videos'); // Change path as needed
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $extension = strtolower((string) $file->getClientOriginalExtension());
    $filename = 'video-thumb-' . date('YmdHis') . '-' . Str::random(8) . 
        ($extension ? '.' . $extension : '');

    $file->move($uploadDirectory, $filename);
    $payload[$field] = '/uploads/videos/' . $filename;
}
```

### HomeContentController - Dynamic Highlight Processing
```php
private function resolveProjectHighlightItems(Request $request, array $validated): array
{
    $titles = $validated['project_highlight_titles'] ?? [];
    $descriptions = $validated['project_highlight_descriptions'] ?? [];
    $imagePaths = $validated['project_highlight_images'] ?? [];
    $actions = $validated['project_highlight_actions'] ?? [];
    $linkTypes = $validated['project_highlight_link_types'] ?? [];
    $linkValues = $validated['project_highlight_link_values'] ?? [];
    $imageFiles = $request->file('project_highlight_image_files', []);

    if (!is_array($imageFiles)) {
        $imageFiles = [];
    }

    $maxRows = max(
        count($titles),
        count($descriptions),
        count($imagePaths),
        count($actions),
        count($linkTypes),
        count($linkValues),
        count($imageFiles)
    );

    $resolved = [];

    for ($index = 0; $index < $maxRows; $index++) {
        $title = $this->cleanString($titles[$index] ?? null);
        $description = $this->cleanString($descriptions[$index] ?? null);
        $typedPath = $this->cleanString($imagePaths[$index] ?? null);

        $uploadedPath = null;
        $uploadedFile = $imageFiles[$index] ?? null;
        if ($uploadedFile && $uploadedFile->isValid()) {
            $uploadedPath = $this->storeUploadedFile($uploadedFile, 'home-highlight');
        }

        $finalImage = $uploadedPath ?: $typedPath;
        $action = ($actions[$index] ?? null) === 'lightbox' ? 'lightbox' : 'link';

        $linkType = $this->cleanString($linkTypes[$index] ?? null);
        if (!in_array($linkType, ['detail', 'project', 'blog', 'video'], true)) {
            $linkType = null;
        }

        $linkValue = $linkValues[$index] ?? null;
        if (is_string($linkValue)) {
            $linkValue = trim($linkValue);
        }

        if (is_numeric($linkValue)) {
            $linkValue = (int) $linkValue;
        } else {
            $linkValue = null;
        }

        // If action is lightbox, clear link settings
        if ($action === 'lightbox') {
            $linkType = null;
            $linkValue = null;
        }

        $resolved[] = [
            'title' => $title,
            'description' => $description,
            'image' => $finalImage,
            'action' => $action,
            'link_type' => $linkType,
            'link_value' => $linkValue,
        ];

        if (count($resolved) >= 24) {
            break;
        }
    }

    return $resolved;
}
```

---

## Blade Template - Views

### Project Detail Gallery Display
```blade
@php
    $galleryImages = collect($detailPage->gallery_images ?? [])->values();

    $resolveImage = function ($raw, $fallback = '') {
        $value = trim((string) $raw);
        if ($value === '') {
            return $fallback;
        }

        if (\Illuminate\Support\Str::startsWith($value, ['http://', 'https://', '/'])) {
            return $value;
        }

        return '/theme/assets/hovi/gallery/' . ltrim($value, '/');
    };
@endphp

@if ($galleryImages->isNotEmpty())
    <section class="detail-gallery-section" id="gallery">
        <div class="detail-gallery">
            @foreach ($galleryImages as $index => $image)
                <figure class="detail-gallery__item">
                    <img src="{{ $resolveImage($image, $resolveImage($detailPage->thumbnail_image, $resolveImage($project->cover_image))) }}"
                        alt="{{ $detailPage->title }} - ảnh {{ $index + 1 }}" 
                        loading="{{ $index === 0 ? 'eager' : 'lazy' }}">
                </figure>
            @endforeach
        </div>
    </section>
@endif
```

### Home Page Project Highlights with Action Configuration
```blade
@php
    $cards = $homeProjectHighlights
        ->map(function ($item) use ($toImageUrl) {
            $resolvedUrl = trim((string) data_get($item, 'url'));
            $resolvedSlug = trim((string) data_get($item, 'slug'), '/');
            $action = data_get($item, 'thumbnail_click_action') === 'lightbox' ? 'lightbox' : 'link';

            // Resolve URL if link action and no URL provided
            if ($action === 'link' && $resolvedUrl === '' && $resolvedSlug !== '') {
                $resolvedUrl = url('/' . $resolvedSlug);
            }

            // Fallback to lightbox if no URL available
            if ($action === 'link' && $resolvedUrl === '') {
                $action = 'lightbox';
            }

            return [
                'image' => $toImageUrl(
                    data_get($item, 'thumbnail_image'),
                    $toImageUrl(data_get($item, 'project.cover_image'))
                ),
                'title' => data_get($item, 'title'),
                'desc' => data_get($item, 'description', data_get($item, 'summary', data_get($item, 'project.name', 'Dự án thiết kế'))),
                'url' => $action === 'link' ? $resolvedUrl : null,
                'action' => $action,
            ];
        })
        ->filter(function ($item) {
            return !empty($item['title']) && !empty($item['image']);
        })
        ->values();
@endphp

<!-- In loop: -->
@forelse ($cards as $card)
    <article class="project-card" data-action="{{ $card['action'] }}" 
        @if ($card['action'] === 'link') href="{{ $card['url'] }}" @endif>
        <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}">
        <h3>{{ $card['title'] }}</h3>
        <p>{{ $card['desc'] }}</p>
    </article>
@empty
    <p>No projects available</p>
@endforelse
```

### Admin - Thumbnail Upload Form
```blade
@php
    $thumbnailValue = old('thumbnail_image', $detailPage->thumbnail_image ?? '');
    $thumbnailClickAction = old('thumbnail_click_action', $detailPage->thumbnail_click_action ?? 'link');
    $thumbnailPreviewId = 'project-detail-thumbnail-preview';
    $thumbnailMetaId = 'project-detail-thumbnail-meta';
@endphp

<div class="col-md-12 thumb-upload-box">
    <label class="form-label" for="thumbnail_image_file">Upload thumbnail</label>
    <input class="form-control js-project-thumbnail-file" 
        id="thumbnail_image_file" 
        name="thumbnail_image_file"
        type="file" 
        accept="image/*" 
        data-preview-id="{{ $thumbnailPreviewId }}" 
        data-meta-id="{{ $thumbnailMetaId }}">
    <div class="form-text">
        Recommended: <strong>1200 × 1600 px</strong> (3:4 ratio), max 5MB, JPG/WebP preferred.
    </div>
</div>

<div class="col-12">
    <div class="thumb-preview-panel">
        <img id="{{ $thumbnailPreviewId }}" 
            src="{{ $thumbnailValue ?: '' }}" 
            alt="Thumbnail preview"
            style="max-height: 220px; max-width: 100%; object-fit: cover; {{ empty($thumbnailValue) ? 'display:none;' : '' }}">
        <div id="{{ $thumbnailMetaId }}" class="small text-muted mt-2">
            {{ empty($thumbnailValue) ? 'No thumbnail image.' : 'Reading image info...' }}
        </div>
    </div>
</div>

<div class="col-12">
    <div class="thumb-behavior-box">
        <div class="fw-semibold">Thumbnail Click Behavior on Homepage</div>
        <div class="thumb-behavior-options">
            <label class="thumb-behavior-option">
                <input type="radio" name="thumbnail_click_action" value="link"
                    @checked($thumbnailClickAction === 'link')>
                <div>
                    <strong>Link to Detail Page</strong>
                    <span>Clicking card navigates to the article</span>
                </div>
            </label>

            <label class="thumb-behavior-option">
                <input type="radio" name="thumbnail_click_action" value="lightbox"
                    @checked($thumbnailClickAction === 'lightbox')>
                <div>
                    <strong>View Image Only (No Link)</strong>
                    <span>Clicking card opens full image in modal</span>
                </div>
            </label>
        </div>
    </div>
</div>
```

### Admin - Gallery Image Upload Form
```blade
@php
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

<div class="col-12">
    <div class="gallery-upload-box" data-gallery-upload>
        <label class="form-label" for="gallery_image_files">Upload Gallery Images</label>
        <input class="form-control" 
            id="gallery_image_files" 
            name="gallery_image_files[]" 
            type="file"
            accept="image/*" 
            multiple 
            data-gallery-file-input 
            data-preview-target="gallery-selected-preview">
        <div class="form-text">
            Select one or multiple images. New uploads append to gallery. Max 5MB each, 80 total.
        </div>

        <div id="gallery-selected-preview" class="gallery-preview-grid d-none" aria-live="polite"></div>

        @if ($galleryPreviewImages->isNotEmpty())
            <div class="small fw-semibold text-muted mt-3">Current Gallery Images</div>
            <div class="gallery-preview-grid">
                @foreach ($galleryPreviewImages as $galleryImage)
                    <div class="gallery-preview-item">
                        <img src="{{ $galleryImage }}" alt="Gallery image {{ $loop->iteration }}" loading="lazy">
                        <span title="{{ $galleryImage }}">{{ $galleryImage }}</span>
                    </div>
                @endforeach
            </div>
        @else
            <div class="small text-muted mt-3">No gallery images yet.</div>
        @endif

        <textarea class="d-none" id="gallery_images_input" name="gallery_images_input">{{ $galleryImages }}</textarea>

        @error('gallery_image_files')
            <div class="text-danger small mt-2">{{ $message }}</div>
        @enderror
    </div>
</div>
```

---

## JavaScript - Image Preview & Upload

### Thumbnail File Preview with Metadata
```javascript
document.addEventListener('DOMContentLoaded', function() {
    function formatBytes(bytes) {
        if (!bytes || bytes <= 0) return '0 B';

        var units = ['B', 'KB', 'MB', 'GB'];
        var index = Math.floor(Math.log(bytes) / Math.log(1024));
        index = Math.min(index, units.length - 1);
        var value = bytes / Math.pow(1024, index);
        return value.toFixed(index === 0 ? 0 : 2) + ' ' + units[index];
    }

    function bindThumbnailPreview(field) {
        if (!field || field.dataset.previewBound === '1') return;

        var pathInput = field.querySelector('.js-project-thumbnail-path');
        if (!pathInput) return;

        var previewId = pathInput.getAttribute('data-preview-id');
        var metaId = pathInput.getAttribute('data-meta-id');
        var fileInput = document.querySelector('.js-project-thumbnail-file[data-preview-id="' + previewId + '"]');
        var previewImg = document.getElementById(previewId);
        var metaEl = document.getElementById(metaId);

        if (!previewImg || !metaEl) return;

        field.dataset.previewBound = '1';

        function showPathPreview() {
            var path = (pathInput.value || '').trim();

            if (path === '') {
                previewImg.style.display = 'none';
                previewImg.removeAttribute('src');
                metaEl.textContent = 'No thumbnail image.';
                return;
            }

            previewImg.style.display = 'block';
            previewImg.src = path;
            metaEl.textContent = 'Reading image info...';

            previewImg.onload = function() {
                metaEl.textContent = 'Current Image · Size: ' + 
                    previewImg.naturalWidth + ' x ' + previewImg.naturalHeight + ' px';
            };

            previewImg.onerror = function() {
                metaEl.textContent = 'Cannot load image from current path.';
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
                metaEl.textContent = 'Reading file info...';

                previewImg.onload = function() {
                    metaEl.textContent = 'New File: ' + file.name + ' · Size: ' + 
                        formatBytes(file.size) + ' · Dimensions: ' + 
                        previewImg.naturalWidth + ' x ' + previewImg.naturalHeight + ' px';
                };

                previewImg.onerror = function() {
                    metaEl.textContent = 'Cannot preview selected image.';
                };
            });
        }

        showPathPreview();
    }

    // Bind all thumbnail preview fields
    var thumbnailFields = document.querySelectorAll('.js-project-thumbnail-field');
    thumbnailFields.forEach(bindThumbnailPreview);
});
```

### Gallery Multi-File Preview
```javascript
function bindGalleryFilePreview(input) {
    if (!input || input.dataset.previewBound === '1') return;

    var previewTargetId = input.getAttribute('data-preview-target');
    var previewTarget = previewTargetId ? document.getElementById(previewTargetId) : null;
    if (!previewTarget) return;

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

            var span = document.createElement('span');
            span.title = file.name;
            span.textContent = file.name;

            item.appendChild(image);
            item.appendChild(span);
            previewTarget.appendChild(item);
        });

        previewTarget.classList.remove('d-none');
    });
}

// Bind all gallery inputs
var galleryInputs = document.querySelectorAll('[data-gallery-file-input]');
galleryInputs.forEach(bindGalleryFilePreview);
```

### Dynamic Row Management (Add/Remove)
```javascript
// Add new row
addRowButton.addEventListener('click', function() {
    var newRow = rowTemplate.cloneNode(true);
    
    // Clear all inputs in new row
    newRow.querySelectorAll('input, textarea, select').forEach(function(input) {
        input.value = '';
        input.name = input.name.replace(/\[\d+\]/, '[' + rowIndex + ']');
    });
    
    container.appendChild(newRow);
    rowIndex++;
});

// Remove row
removeBtn.addEventListener('click', function() {
    var row = this.closest('[data-row]');
    row.remove();
});

// Toggle visibility based on action
actionSelect.addEventListener('change', function() {
    var isLinkAction = this.value === 'link';
    linkSettings.classList.toggle('d-none', !isLinkAction);
    
    // Clear link fields if lightbox
    if (!isLinkAction) {
        linkTypeSelect.value = '';
        linkValueInput.value = '';
    }
});
```

---

## CSS - Hover & Transform Effects

### Admin Preview Panels
```css
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
    max-height: 220px;
    object-fit: cover;
}
```

### Behavior Options with Hover & Active States
```css
.thumb-behavior-option {
    display: flex;
    gap: .6rem;
    align-items: flex-start;
    border: 1px solid #dbe5f0;
    border-radius: 10px;
    background: #fff;
    padding: .7rem .8rem;
    cursor: pointer;
    transition: all .2s ease;
}

.thumb-behavior-option:hover {
    border-color: #93c5fd;
    box-shadow: 0 6px 14px rgba(59, 130, 246, .08);
    background-color: #f8fbff;
}

.thumb-behavior-option input:checked ~ div {
    color: #1e40af;
}

.thumb-behavior-option input:checked {
    border-color: #3b82f6;
}

.thumb-behavior-option.is-selected {
    border-color: #3b82f6;
    background: #eff6ff;
    box-shadow: 0 8px 18px rgba(59, 130, 246, .14);
}
```

### Gallery Preview Grid
```css
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
    transition: transform .2s ease, box-shadow .2s ease;
}

.gallery-preview-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
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
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
```

### Frontend Gallery Hover Effects
```css
.detail-gallery__item {
    cursor: pointer;
    overflow: hidden;
    border-radius: 8px;
    transition: transform .3s ease, box-shadow .3s ease;
}

.detail-gallery__item:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 24px rgba(0, 0, 0, .2);
}

.detail-gallery__item img {
    width: 100%;
    height: auto;
    display: block;
    transition: brightness .3s ease;
}

.detail-gallery__item:hover img {
    filter: brightness(1.1);
}
```

### Video Player Button Hover
```css
.video-player__link {
    display: inline-flex;
    align-items: center;
    min-height: 40px;
    padding: 0 16px;
    border-radius: 999px;
    border: 1px solid rgba(255, 255, 255, .34);
    color: #fff;
    text-decoration: none;
    font-size: .9rem;
    transition: background-color .2s ease, border-color .2s ease;
}

.video-player__link:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, .6);
}
```

---

## Form Validation Examples

### Backend Validation (Laravel)
```php
$validated = $request->validate([
    // Thumbnail
    'thumbnail_image' => 'nullable|string|max:255',
    'thumbnail_image_file' => 'nullable|image|max:5120',
    'thumbnail_click_action' => 'nullable|in:link,lightbox',
    
    // Gallery
    'gallery_images_input' => 'nullable|string|max:30000',
    'gallery_image_files' => 'nullable|array|max:80',
    'gallery_image_files.*' => 'nullable|image|max:5120',
]);
```

### Frontend Validation (HTML5)
```html
<!-- Thumbnail File Input -->
<input type="file" 
    name="thumbnail_image_file" 
    accept="image/*"
    required>

<!-- Gallery Multiple Upload -->
<input type="file" 
    name="gallery_image_files[]" 
    accept="image/*" 
    multiple 
    required>
```

