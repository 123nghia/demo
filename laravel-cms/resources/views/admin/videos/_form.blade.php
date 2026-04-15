@php
    /** @var \App\Models\ProjectVideo|null $video */
    $video = $video ?? null;
    $projects = $projects ?? collect();
    $defaultProjectId = $defaultProjectId ?? '';
@endphp

<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label" for="title">Tiêu đề video</label>
        <input class="form-control" id="title" name="title" type="text"
            value="{{ old('title', $video->title ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="slug">Slug route</label>
        <input class="form-control" id="slug" name="slug" type="text"
            value="{{ old('slug', $video->slug ?? '') }}"
            placeholder="tu-dong-theo-tieu-de">
        <div class="form-text">Tự sinh từ tiêu đề (duy nhất cho route động /{slug}).</div>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="project_id">Thuộc dự án</label>
        @php
            $resolvedProjectId = (string) old('project_id', $video->project_id ?? $defaultProjectId);
        @endphp
        <select class="form-select" id="project_id" name="project_id" required>
            <option value="">-- Chọn dự án --</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @selected($resolvedProjectId === (string) $project->id)>
                    {{ $project->name }} (/{{ $project->slug }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label" for="display_zone">Phân vùng hiển thị</label>
        @php $displayZone = old('display_zone', $video->display_zone ?? 'all'); @endphp
        <select class="form-select" id="display_zone" name="display_zone" required>
            <option value="all" @selected($displayZone === 'all')>Toàn site</option>
            <option value="video" @selected($displayZone === 'video')>Trang Video</option>
            <option value="project" @selected($displayZone === 'project')>Khu vực dự án</option>
        </select>
    </div>

    <div class="col-md-3">
        <label class="form-label" for="published_at">Thời điểm đăng</label>
        <input class="form-control" id="published_at" name="published_at" type="datetime-local"
            value="{{ old('published_at', optional($video->published_at ?? null)->format('Y-m-d\TH:i')) }}">
    </div>

    <div class="col-md-8">
        <label class="form-label" for="video_url">Link video gốc</label>
        <input class="form-control" id="video_url" name="video_url" type="text"
            value="{{ old('video_url', $video->video_url ?? '') }}"
            placeholder="https://youtube.com/watch?... hoặc https://...">
        <div class="form-text">Hỗ trợ YouTube/Vimeo và link ngoài. Nếu không nhận diện embed, hệ thống sẽ hiển thị nút mở link gốc.</div>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input type="hidden" name="is_published" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published"
                @checked(old('is_published', $video ? $video->is_published : true))>
            <label class="form-check-label" for="is_published">Hiển thị công khai</label>
        </div>
    </div>

    <div class="col-md-8">
        <label class="form-label" for="thumbnail_image">Ảnh thumbnail (URL hoặc đường dẫn)</label>
        <input class="form-control" id="thumbnail_image" name="thumbnail_image" type="text"
            value="{{ old('thumbnail_image', $video->thumbnail_image ?? '') }}"
            placeholder="/uploads/videos/... hoặc https://...">
    </div>

    <div class="col-md-4">
        <label class="form-label" for="thumbnail_image_file">Upload ảnh thumbnail</label>
        <input class="form-control" id="thumbnail_image_file" name="thumbnail_image_file" type="file"
            accept="image/*">
    </div>

    @php $thumbnailPreview = old('thumbnail_image', $video->thumbnail_image ?? ''); @endphp
    @if (!empty($thumbnailPreview))
        <div class="col-12">
            <p class="form-label mb-1">Thumbnail hiện tại</p>
            <img src="{{ $thumbnailPreview }}" alt="Thumbnail preview"
                style="max-width:260px;max-height:160px;object-fit:cover;border:1px solid #dee2e6;border-radius:.5rem;">
        </div>
    @endif

    <div class="col-12">
        <label class="form-label" for="description">Mô tả ngắn</label>
        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $video->description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label" for="content">Nội dung chi tiết video</label>
        <textarea class="form-control js-rich-editor" id="content" name="content" rows="10">{{ old('content', $video->content ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="seo_title">SEO title</label>
        <input class="form-control" id="seo_title" name="seo_title" type="text"
            value="{{ old('seo_title', $video->seo_title ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label" for="seo_description">SEO description</label>
        <textarea class="form-control" id="seo_description" name="seo_description" rows="2">{{ old('seo_description', $video->seo_description ?? '') }}</textarea>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var titleInput = document.getElementById('title');
            var slugInput = document.getElementById('slug');

            if (!titleInput || !slugInput) {
                return;
            }

            var hasManualSlug = slugInput.value.trim() !== '';

            var slugify = function(value) {
                return String(value || '')
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-')
                    .replace(/^-+|-+$/g, '')
                    .replace(/-{2,}/g, '-');
            };

            titleInput.addEventListener('input', function() {
                if (hasManualSlug) {
                    return;
                }

                slugInput.value = slugify(titleInput.value);
            });

            slugInput.addEventListener('input', function() {
                hasManualSlug = slugInput.value.trim() !== '';
            });
        });
    </script>
@endpush
