@php
    /** @var \App\Models\ProjectBlog|null $blog */
    $blog = $blog ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-7">
        <label class="form-label" for="title">Tiêu đề blog</label>
        <input class="form-control" id="title" name="title" type="text" value="{{ old('title', $blog->title ?? '') }}"
            required>
    </div>

    <div class="col-md-3">
        <label class="form-label" for="slug">Slug route</label>
        <input class="form-control" id="slug" name="slug" type="text" value="{{ old('slug', $blog->slug ?? '') }}"
            required>
    </div>

    <div class="col-md-2">
        <label class="form-label" for="sort_order">Thứ tự</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0"
            value="{{ old('sort_order', $blog->sort_order ?? 0) }}">
    </div>

    <div class="col-md-6 d-none">
        <input id="thumbnail_image" name="thumbnail_image" type="hidden"
            value="{{ old('thumbnail_image', $blog->thumbnail_image ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label" for="thumbnail_image_file">Upload ảnh thumbnail</label>
        <input class="form-control" id="thumbnail_image_file" name="thumbnail_image_file" type="file"
            accept="image/*">
        <div class="form-text">Upload file mới để thay thumbnail hiện tại.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="target_url">Link đích (nếu có)</label>
        <input class="form-control" id="target_url" name="target_url" type="text"
            value="{{ old('target_url', $blog->target_url ?? '') }}">
        <div class="form-text">Có thể nhập URL ngoài hoặc route nội bộ, ví dụ: <code>/some-blog</code>.</div>
    </div>

    @php $thumbnailPreview = old('thumbnail_image', $blog->thumbnail_image ?? ''); @endphp
    @if (!empty($thumbnailPreview))
        <div class="col-12">
            <p class="form-label mb-1">Thumbnail hiện tại</p>
            <img src="{{ $thumbnailPreview }}" alt="Thumbnail preview"
                style="max-width:260px;max-height:160px;object-fit:cover;border:1px solid #dee2e6;border-radius:.5rem;">
        </div>
    @endif

    <div class="col-md-6">
        <label class="form-label" for="published_at">Thời điểm đăng</label>
        <input class="form-control" id="published_at" name="published_at" type="datetime-local"
            value="{{ old('published_at', optional($blog->published_at ?? null)->format('Y-m-d\TH:i')) }}">
    </div>

    <div class="col-md-6 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input type="hidden" name="is_published" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published"
                @checked(old('is_published', $blog ? $blog->is_published : true))>
            <label class="form-check-label" for="is_published">Hiển thị công khai</label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label" for="excerpt">Mô tả ngắn</label>
        <textarea class="form-control" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label" for="content">Nội dung blog</label>
        <textarea class="form-control js-rich-editor" id="content" name="content" rows="8">{{ old('content', $blog->content ?? '') }}</textarea>
    </div>
</div>
