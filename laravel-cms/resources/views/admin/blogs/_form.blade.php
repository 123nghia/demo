@php
    /** @var \App\Models\Blog|null $blog */
    $blog = $blog ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-7">
        <label class="form-label" for="title">Tiêu đề blog</label>
        <input class="form-control" id="title" name="title" type="text"
            value="{{ old('title', $blog->title ?? '') }}" required>
    </div>

    <div class="col-md-3">
        <label class="form-label" for="slug">Slug route</label>
        <input class="form-control" id="slug" name="slug" type="text"
            value="{{ old('slug', $blog->slug ?? '') }}" required>
    </div>

    <div class="col-md-2">
        <label class="form-label" for="sort_order">Thứ tự</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0"
            value="{{ old('sort_order', $blog->sort_order ?? 0) }}">
    </div>

    <div class="col-md-6">
        <label class="form-label" for="thumbnail_image">Ảnh thumbnail</label>
        <input class="form-control" id="thumbnail_image" name="thumbnail_image" type="text"
            value="{{ old('thumbnail_image', $blog->thumbnail_image ?? '') }}"
            placeholder="/theme/assets/hovi/gallery/... hoặc https://...">
    </div>

    <div class="col-md-6">
        <label class="form-label" for="published_at">Thời điểm đăng</label>
        <input class="form-control" id="published_at" name="published_at" type="datetime-local"
            value="{{ old('published_at', optional($blog->published_at ?? null)->format('Y-m-d\TH:i')) }}">
    </div>

    <div class="col-12">
        <label class="form-label" for="excerpt">Mô tả ngắn</label>
        <textarea class="form-control" id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $blog->excerpt ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label" for="content">Nội dung chi tiết blog</label>
        <textarea class="form-control" id="content" name="content" rows="10">{{ old('content', $blog->content ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="seo_title">SEO title</label>
        <input class="form-control" id="seo_title" name="seo_title" type="text"
            value="{{ old('seo_title', $blog->seo_title ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label" for="seo_description">SEO description</label>
        <textarea class="form-control" id="seo_description" name="seo_description" rows="2">{{ old('seo_description', $blog->seo_description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <div class="form-check form-switch mb-1">
            <input type="hidden" name="is_published" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published"
                @checked(old('is_published', $blog ? $blog->is_published : true))>
            <label class="form-check-label" for="is_published">Hiển thị công khai</label>
        </div>
    </div>
</div>
