# Laravel CMS - Image Interactions & Handling Analysis

## Overview
This document provides a comprehensive analysis of all image-related functionality in the HOVI CMS project, including views with image interactions, CSS transforms, JavaScript handling, and server-side storage logic.

---

## 1. VIEWS/BLADE TEMPLATES WITH IMAGE INTERACTIONS

### 1.1 Project Detail Page Gallery with Lightbox Support
**File:** [resources/views/site/projects/detail.blade.php](resources/views/site/projects/detail.blade.php#L147-L155)

**Feature:** Gallery display with click/hover interactions and thumbnail action configuration

```blade
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

**Key Interactions:**
- Gallery images display with lazy loading (except first image)
- Image fallback chain: gallery image → thumbnail → project cover
- Supports both eager and lazy loading strategies
- CSS class: `.detail-gallery__item` for styling hover effects
- CSS class: `.detail-gallery` for grid layout

**Related Model:** `ProjectDetailPage::gallery_images` (array of image paths)

---

### 1.2 Project Detail Form - Thumbnail Click Action Toggle (Admin)
**File:** [resources/views/admin/projects/detail-pages/_form.blade.php](resources/views/admin/projects/detail-pages/_form.blade.php#L85-L110)

**Feature:** Admin interface to configure thumbnail click behavior (link vs lightbox)

```blade
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
</div>
```

**Database Field:** `ProjectDetailPage::thumbnail_click_action` (enum: 'link' | 'lightbox')

**CSS Classes for Styling:**
- `.thumb-behavior-option` - Individual option button
- `.thumb-behavior-option:hover` - Hover state
- `.thumb-behavior-option.is-selected` - Selected state

---

### 1.3 Home Page Project Highlights with Dynamic Link/Lightbox
**File:** [resources/views/site/pages/home.blade.php](resources/views/site/pages/home.blade.php#L70-L95)

**Feature:** Dynamic rendering of project highlight cards with configurable click behavior

```blade
$cards = $homeProjectHighlights
    ->map(function ($item) use ($toImageUrl) {
        $resolvedUrl = trim((string) data_get($item, 'url'));
        $resolvedSlug = trim((string) data_get($item, 'slug'), '/');
        $action = data_get($item, 'thumbnail_click_action') === 'lightbox' ? 'lightbox' : 'link';

        if ($action === 'link' && $resolvedUrl === '' && $resolvedSlug !== '') {
            $resolvedUrl = url('/' . $resolvedSlug);
        }

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
```

**Logic Flow:**
1. Check `thumbnail_click_action` field
2. If 'lightbox' → set URL to null (no navigation)
3. If 'link' but no URL → try slug, else fallback to lightbox
4. Returns card array with `action` and `url` keys

**Supported Display Sections:**
- `#projects-1` - First grid (6 items)
- `#projects-2` - Second grid (next 6 items)

---

### 1.4 Admin Home Content - Image Upload & Gallery Management
**File:** [resources/views/admin/home-content/index.blade.php](resources/views/admin/home-content/index.blade.php#L274-L405)

**Features:**
- Hero background image upload
- Profile slider images upload (multiple)
- Project highlights with image upload
- Image preview on file selection

**Example - Hero Image Upload:**
```blade
<label class="form-label" for="hero_background_image_file">Upload ảnh Hero</label>
<input class="form-control js-home-image-file" id="hero_background_image_file"
    name="hero_background_image_file" type="file" accept="image/*" 
    data-preview-id="hero-bg-preview" data-preview-type="bg">
```

**CSS Classes for Upload Components:**
- `.js-home-image-file` - File input trigger
- `data-preview-id` - Target element for preview
- `data-preview-type` - Preview rendering type ('bg' for background, default for img)

**Project Highlight Action Options:**
```blade
<option value="link">Link đến trang chi tiết</option>
<option value="lightbox">Zoom ảnh (không link)</option>
```

---

### 1.5 Video Detail Page with Thumbnail & Gallery
**File:** [resources/views/site/video/show.blade.php](resources/views/site/video/show.blade.php#L442-L515)

**Features:**
- Video thumbnail display with hover effects
- Responsive image sizing
- Image fallback mechanism
- Lazy loading support

```blade
<img class="video-detail-cover" 
    src="{{ $resolveImage($video->thumbnail_image) }}" 
    alt="{{ $video->title }}"
    loading="eager">

<img src="{{ $resolveImage($item->thumbnail_image) }}" 
    alt="{{ $item->title }}"
    loading="lazy">
```

**CSS Classes:**
- `.video-detail-cover` - Main video thumbnail
- `.video-detail-richtext img` - Content embedded images
- `.video-player__link:hover` - Play button hover effect

---

## 2. CSS FILES WITH IMAGE TRANSFORMS & HOVER EFFECTS

### 2.1 Video Page Inline Styles
**File:** [resources/views/site/video/show.blade.php](resources/views/site/video/show.blade.php#L10-L232)

**Hover Effects:**

```css
.video-player__link:hover {
    background: rgba(255, 255, 255, 0.12);
    border-color: rgba(255, 255, 255, .6);
}
```

**Image Styling:**

```css
.video-detail-richtext img {
    display: block;
    max-width: min(100%, 920px);
    height: auto;
    margin: 18px auto;
    border: 1px solid rgba(255, 255, 255, .14);
}
```

**Responsive Image Adjustments:**
```css
@media (max-width: 820px) {
    .video-detail-cover {
        max-height: none;
        aspect-ratio: 16 / 10;
    }
    
    .video-detail-richtext img {
        margin: 12px auto;
    }
}
```

### 2.2 Admin Layout Hover Styles
**File:** [resources/views/admin/layout.blade.php](resources/views/admin/layout.blade.php#L162-L323)

```css
.admin-sidebar__nav-link:hover {
    /* Navigation link hover effect */
}

.table-hover>tbody>tr:hover>* {
    /* Table row hover effect - applies to all cells */
}
```

### 2.3 Admin Home Content - Lightbox Mode Indicator
**File:** [resources/views/admin/home-content/index.blade.php](resources/views/admin/home-content/index.blade.php#L88)

```css
.home-click-mode--lightbox {
    /* Visual indicator for lightbox-mode items */
}
```

**HTML Usage:**
```blade
@if (($item->thumbnail_click_action ?? 'link') === 'lightbox')
    <span class="home-click-mode home-click-mode--lightbox">🖼 Mở ảnh</span>
@endif
```

### 2.4 Admin Form Image Preview Styling
**File:** [resources/views/admin/projects/detail-pages/_form.blade.php](resources/views/admin/projects/detail-pages/_form.blade.php#L25-L75)

**Preview Panels:**

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
}
```

**Behavior Option Styling:**

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
```

**Gallery Preview Grid:**

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
}

.gallery-preview-item img {
    width: 100%;
    aspect-ratio: 4 / 3;
    object-fit: cover;
    background: #eef2f7;
}
```

---

## 3. JAVASCRIPT CODE FOR IMAGE INTERACTIONS

### 3.1 Admin Detail Form - Thumbnail Preview Script
**File:** [resources/views/admin/projects/detail-pages/_form.blade.php](resources/views/admin/projects/detail-pages/_form.blade.php#L285-L375)

**Functionality:**
- Live preview of thumbnail image on file selection
- Display image dimensions and file size
- Handle both URL paths and file uploads
- Show preview metadata

```javascript
function bindThumbnailPreview(field) {
    var pathInput = field.querySelector('.js-project-thumbnail-path');
    var fileInput = document.querySelector('.js-project-thumbnail-file[data-preview-id="' + previewId + '"]');
    var previewImg = document.getElementById(previewId);
    var metaEl = document.getElementById(metaId);

    fileInput.addEventListener('change', function() {
        var file = this.files && this.files[0] ? this.files[0] : null;
        if (!file) return;

        var objectUrl = URL.createObjectURL(file);
        previewImg.style.display = 'block';
        previewImg.src = objectUrl;
        metaEl.textContent = 'Đang đọc thông tin file...';

        previewImg.onload = function() {
            metaEl.textContent = 'File mới: ' + file.name + ' · Dung lượng: ' + 
                formatBytes(file.size) + ' · Kích thước: ' + 
                previewImg.naturalWidth + ' x ' + previewImg.naturalHeight + ' px';
        };
    });
}
```

**Features:**
- Uses `URL.createObjectURL()` for local file preview
- Displays file name, size, and dimensions
- Real-time validation on load/error

### 3.2 Admin Detail Form - Gallery File Preview Script
**File:** [resources/views/admin/projects/detail-pages/_form.blade.php](resources/views/admin/projects/detail-pages/_form.blade.php#L376-L420)

**Functionality:**
- Multi-file gallery image preview
- Dynamic grid rendering
- Show file information on hover

```javascript
function bindGalleryFilePreview(input) {
    var previewTargetId = input.getAttribute('data-preview-target');
    var previewTarget = document.getElementById(previewTargetId);

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
            item.appendChild(image);

            var span = document.createElement('span');
            span.title = file.name;
            span.textContent = file.name;
            item.appendChild(span);

            previewTarget.appendChild(item);
        });

        previewTarget.classList.remove('d-none');
    });
}
```

**Features:**
- Dynamic DOM element creation
- Grid layout with image preview
- File name display with tooltip

### 3.3 Admin Home Content - Slider & Highlight Management
**File:** [resources/views/admin/home-content/index.blade.php](resources/views/admin/home-content/index.blade.php#L909-L1230)

**Event Listeners:**
```javascript
addSliderRowButton.addEventListener('click', function() {
    // Add new slider image row
});

addHighlightRowButton.addEventListener('click', function() {
    // Add new highlight row
});

actionSelect.addEventListener('change', function() {
    linkSettings.classList.toggle('d-none', !isLinkAction);
});

removeBtn.addEventListener('click', function() {
    // Remove row from DOM
});
```

**Key Interactions:**
- Toggle visibility of link settings when action changes to 'lightbox'
- Add/remove dynamic form rows
- Real-time UI state management

### 3.4 Admin Layout - TinyMCE Editor Image Upload
**File:** [resources/views/admin/layout.blade.php](resources/views/admin/layout.blade.php#L610-L710)

**Functionality:**
- Handle image uploads from rich text editor
- Process upload via AJAX
- Return image URL to editor

```javascript
var uploadUrl = @json(route('admin.editor.upload-image'));

images_upload_handler: function(blobInfo, progress) {
    fetch(uploadUrl, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData,
    })
    .then(response => response.json())
    .then(result => {
        if (result.success && result.payload) {
            success(result.payload.url);
        } else {
            reject((result.payload && result.payload.message) || 'Upload ảnh thất bại.');
        }
    });
}
```

**Route Reference:** `admin.editor.upload-image`

---

## 4. IMAGE UPLOAD & STORAGE HANDLING

### 4.1 Project Detail Page - Image Upload Handler
**File:** [app/Http/Controllers/Admin/ProjectDetailPageController.php](app/Http/Controllers/Admin/ProjectDetailPageController.php#L149-L200)

**Thumbnail Upload:**
```php
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
```

**Upload Path:** `public/uploads/projects/`
**Filename Pattern:** `{prefix}-{YmdHis}-{random8}.{ext}`
**Prefix Examples:** 
- `project-detail-thumbnail`
- `project-detail-gallery`
- `project-cover`

**Gallery Images Upload:**
```php
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
```

**Gallery Image Management:**
```php
private function parseGalleryImages(string $raw): array
{
    $normalized = str_replace(["\r\n", "\r", ','], "\n", $raw);
    $lines = array_map('trim', explode("\n", $normalized));
    $lines = array_filter($lines, function ($line) {
        return $line !== '';
    });

    return array_values(array_unique($lines));
}

private function mergeGalleryImages(array $typedImages, array $uploadedImages): array
{
    return array_values(array_unique(array_filter(
        array_merge($typedImages, $uploadedImages),
        function ($path) {
            return is_string($path) && trim($path) !== '';
        }
    )));
}
```

**Validation Rules:**
```php
'thumbnail_image_file' => 'nullable|image|max:5120',
'gallery_image_files' => 'nullable|array|max:80',
'gallery_image_files.*' => 'nullable|image|max:5120',
```

**Database Storage:**
- `thumbnail_image` - Single image path (nullable string, max 255)
- `gallery_images` - Array of image paths (stored as JSON, max 30000 chars)
- `thumbnail_click_action` - 'link' or 'lightbox' (default: 'link')

---

### 4.2 Video Controller - Thumbnail Upload Handler
**File:** [app/Http/Controllers/Admin/VideoController.php](app/Http/Controllers/Admin/VideoController.php#L137-L160)

**Upload Implementation:**
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

    $uploadDirectory = public_path('uploads/videos');
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

**Upload Path:** `public/uploads/videos/`
**Filename Pattern:** `video-thumb-{YmdHis}-{random8}.{ext}`

**Validation:**
```php
'thumbnail_image_file' => 'nullable|image|max:4096',
```

---

### 4.3 Project Controller - Cover Image Upload
**File:** [app/Http/Controllers/Admin/ProjectController.php](app/Http/Controllers/Admin/ProjectController.php#L117-L142)

**Upload Implementation:**
```php
private function replaceWithUploadedFile(Request $request, string $fileInput, string $field, array &$payload): void
{
    $uploadDirectory = public_path('uploads/projects');
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $extension = strtolower((string) $file->getClientOriginalExtension());
    $filename = 'project-cover-' . date('YmdHis') . '-' . Str::random(8) . 
        ($extension ? '.' . $extension : '');

    $file->move($uploadDirectory, $filename);
    $payload[$field] = '/uploads/projects/' . $filename;
}
```

**Upload Path:** `public/uploads/projects/`
**Filename Pattern:** `project-cover-{YmdHis}-{random8}.{ext}`

---

### 4.4 Home Content Controller - Multi-Purpose Image Upload
**File:** [app/Http/Controllers/Admin/HomeContentController.php](app/Http/Controllers/Admin/HomeContentController.php#L418-L500)

**Profile Slider Images:**
```php
private function resolveProfileSliderImages(Request $request, array $validated): array
{
    $pathRows = $validated['profile_slider_image_paths'] ?? [];
    $fileRows = $request->file('profile_slider_image_files', []);

    for ($index = 0; $index < $maxRows; $index++) {
        $typedPath = $this->cleanString($pathRows[$index] ?? null);
        
        $uploadedPath = null;
        $uploadedFile = $fileRows[$index] ?? null;
        if ($uploadedFile && $uploadedFile->isValid()) {
            $uploadedPath = $this->storeUploadedFile($uploadedFile, 'home-profile-slider');
        }

        $finalPath = $uploadedPath ?: $typedPath;
        if (!is_null($finalPath)) {
            $resolved[] = $finalPath;
        }
    }
    return array_values(array_unique($resolved));
}
```

**Project Highlight Images:**
```php
private function resolveProjectHighlightItems(Request $request, array $validated): array
{
    $imageFiles = $request->file('project_highlight_image_files', []);

    for ($index = 0; $index < $maxRows; $index++) {
        $uploadedPath = null;
        $uploadedFile = $imageFiles[$index] ?? null;
        if ($uploadedFile && $uploadedFile->isValid()) {
            $uploadedPath = $this->storeUploadedFile($uploadedFile, 'home-highlight');
        }

        $finalImage = $uploadedPath ?: $typedPath;
        $action = ($actions[$index] ?? null) === 'lightbox' ? 'lightbox' : 'link';

        $resolved[] = [
            'title' => $title,
            'description' => $description,
            'image' => $finalImage,
            'action' => $action,
            'link_type' => $linkType,
            'link_value' => $linkValue,
        ];
    }
    return $resolved;
}

private function storeUploadedFile($file, string $filenamePrefix): ?string
{
    if (!$file || !$file->isValid()) {
        return null;
    }

    $uploadDirectory = public_path('uploads/home');
    if (!is_dir($uploadDirectory)) {
        mkdir($uploadDirectory, 0755, true);
    }

    $extension = strtolower((string) $file->getClientOriginalExtension());
    $filename = $filenamePrefix . '-' . date('YmdHis') . '-' . Str::random(8) . 
        ($extension ? '.' . $extension : '');

    $file->move($uploadDirectory, $filename);
    return '/uploads/home/' . $filename;
}
```

**Upload Paths:**
- `public/uploads/home/` - General home content
- Filename Prefixes:
  - `home-profile-slider` - Profile section slider
  - `home-highlight` - Project highlights
  - `hero-background`, `profile-background` - Background images
  - `about-team` - Team images
  - `footer-consult` - Footer CTA background

---

### 4.5 Blog & Blog Video Controllers - Image Upload
**File:** [app/Http/Controllers/Admin/BlogController.php](app/Http/Controllers/Admin/BlogController.php#L119-L160)

**Pattern:** Similar to other controllers
- Upload Path: `public/uploads/blogs/`
- Filename: `blog-thumb-{YmdHis}-{random8}.{ext}`
- Max Size: 5120 KB (5 MB)

---

### 4.6 Settings Controller - Site-Wide Images
**File:** [app/Http/Controllers/Admin/SettingController.php](app/Http/Controllers/Admin/SettingController.php#L77-L125)

**Managed Images:**
```php
$this->replaceWithUploadedFile($request, 'header_logo_file', 'header_logo', $settings);
$this->replaceWithUploadedFile($request, 'footer_logo_file', 'footer_logo', $settings);
$this->replaceWithUploadedFile($request, 'favicon_file', 'favicon', $settings);
$this->replaceWithUploadedFile($request, 'apple_touch_icon_file', 'apple_touch_icon', $settings);
$this->replaceWithUploadedFile($request, 'seo_og_image_file', 'seo_og_image', $settings);
```

**Upload Path:** `public/uploads/settings/`
**Stored In:** `SiteSetting` model (JSON storage)

---

### 4.7 Editor Upload Handler
**Route:** `admin.editor.upload-image` (referenced in TinyMCE config)
**Usage:** Image uploads from rich text editor in content forms
**Directory:** Likely `public/uploads/editor/` or similar

---

## 5. IMAGE-RELATED MODELS & DATABASE

### 5.1 ProjectDetailPage Model
**File:** [app/Models/ProjectDetailPage.php](app/Models/ProjectDetailPage.php)

**Image Fields:**
```php
protected $fillable = [
    'thumbnail_image',      // Single image path
    'thumbnail_click_action', // 'link' or 'lightbox'
    'gallery_images',       // JSON array of image paths
    // ... other fields
];

protected $casts = [
    'gallery_images' => 'array',
];
```

---

### 5.2 ProjectVideo Model
**Image Fields:**
- `thumbnail_image` - Video thumbnail path

---

### 5.3 Blog & ProjectBlog Models
**Image Fields:**
- `thumbnail_image` - Blog thumbnail path

---

### 5.4 Project Model
**Image Fields:**
- `cover_image` - Project cover image path

---

### 5.5 SiteSetting Model
**Image Fields (JSON):**
```php
'header_logo'
'footer_logo'
'favicon'
'apple_touch_icon'
'seo_og_image'
'seo_robots'  // Contains 'max-image-preview:large'
'hero' => [
    'background_image',
]
'profile' => [
    'background_image',
    'slider_images' => [],
]
'about' => [
    'team_image',
]
'footer_cta' => [
    'consult' => [
        'background_image',
    ],
]
'project_highlights' => [
    'items' => [
        ['image', 'action', 'title', 'description', 'link_type', 'link_value'],
    ],
]
```

---

## 6. UPLOAD DIRECTORY STRUCTURE

```
public/
├── uploads/
│   ├── projects/          # Project covers & detail page images
│   ├── videos/            # Video thumbnails
│   ├── blogs/             # Blog thumbnails
│   ├── home/              # Home page hero, profile, highlights
│   ├── settings/          # Logo, favicon, OG image
│   └── editor/            # Editor (TinyMCE) uploaded images
├── theme/
│   └── assets/hovi/gallery/  # Static/theme gallery images
```

---

## 7. VALIDATION RULES SUMMARY

| Field | Max Size | Type | Location |
|-------|----------|------|----------|
| Project thumbnail | 5120 KB | image | ProjectDetailPageController |
| Gallery images | 5120 KB each | image | ProjectDetailPageController |
| Video thumbnail | 4096 KB | image | VideoController |
| Project cover | 4096 KB | image | ProjectController |
| Blog thumbnail | 5120 KB | image | BlogController |
| Home images | - | image | HomeContentController |
| Gallery images max count | 80 items | array | ProjectDetailPageController |

---

## 8. FRONTEND IMAGE RESOLUTION LOGIC

### Image URL Resolution Function
Used across multiple blade templates to resolve image paths:

```php
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
```

**Resolution Order:**
1. If path is absolute URL (http/https) → use as-is
2. If path starts with `/` → use as-is (site root path)
3. Otherwise → prepend `/theme/assets/hovi/gallery/`

---

## 9. KEY FEATURES SUMMARY

| Feature | Implementation | Status |
|---------|-----------------|--------|
| Image Gallery | Multiple images stored in JSON array | ✅ Active |
| Lightbox Support | `thumbnail_click_action` field | ✅ Configured (frontend implementation needed) |
| Lazy Loading | `loading="lazy"` attribute on img tags | ✅ Active |
| Image Fallback Chain | Cascade of thumbnail → cover → fallback | ✅ Active |
| Image Transforms | CSS transitions & hover effects | ✅ Active |
| Image Preview Admin | Real-time file preview in forms | ✅ Active |
| Multi-file Upload | Gallery image batch upload | ✅ Active |
| TinyMCE Integration | Editor image upload handler | ✅ Configured |
| Responsive Images | aspect-ratio, object-fit, max-width | ✅ Active |
| Image Validation | Size & type validation | ✅ Active |

---

## 10. NOTES & RECOMMENDATIONS

1. **Lightbox Implementation**: The database structure supports lightbox click action ('lightbox' vs 'link'), but the frontend JavaScript implementation for actual lightbox modal is not visible in the analyzed views. A lightbox library (e.g., GLightbox, Fancybox) should be integrated.

2. **Image Transforms**: CSS transforms for hover effects exist in the admin panel (`.thumb-behavior-option:hover`) but are minimal on the front-end. Consider adding more sophisticated transforms for:
   - Scale on hover
   - Rotation effects
   - Filter/brightness changes

3. **CDN/Image Optimization**: The project serves images from `public/uploads/` and `/theme/assets/`. Consider implementing:
   - Image optimization/compression
   - Responsive image sizes
   - WebP format support
   - CDN distribution

4. **Gallery Component**: The gallery rendering is basic. The template shows a simple figure grid. Could be enhanced with:
   - Lightbox modal on click
   - Keyboard navigation
   - Swipe gestures on mobile
   - Image descriptions/captions

5. **Schema Markup**: The project includes structured data (schema.org) for images, which is good for SEO.

