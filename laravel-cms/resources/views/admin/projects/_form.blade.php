@php
    /** @var \App\Models\Project|null $project */
    $project = $project ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Tên dự án</label>
        <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $project->name ?? '') }}" required>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="slug">Slug route</label>
        <input class="form-control" id="slug" name="slug" type="text" value="{{ old('slug', $project->slug ?? '') }}"
            required>
        <div class="form-text">Ví dụ: <code>thiet-ke-biet-thu-vinhomes-ocean-park</code></div>
    </div>

    <div class="col-md-2">
        <label class="form-label" for="sort_order">Thứ tự</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0"
            value="{{ old('sort_order', $project->sort_order ?? 0) }}">
    </div>

    <div class="col-md-8 d-none">
        <input id="cover_image" name="cover_image" type="hidden"
            value="{{ old('cover_image', $project->cover_image ?? '') }}">
    </div>

    <div class="col-md-8">
        <label class="form-label" for="cover_image_file">Upload ảnh đại diện dự án</label>
        <input class="form-control" id="cover_image_file" name="cover_image_file" type="file" accept="image/*">
        <div class="form-text">Upload file mới để thay ảnh đại diện hiện tại.</div>
    </div>

    <div class="col-md-4 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input type="hidden" name="is_published" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published"
                @checked(old('is_published', $project ? $project->is_published : true))>
            <label class="form-check-label" for="is_published">Đang hiển thị công khai</label>
        </div>
    </div>

    @php $coverPreview = old('cover_image', $project->cover_image ?? ''); @endphp
    @if (!empty($coverPreview))
        <div class="col-12">
            <p class="form-label mb-1">Ảnh đại diện hiện tại</p>
            <img src="{{ $coverPreview }}" alt="Project cover preview"
                style="max-width:320px;max-height:200px;object-fit:cover;border:1px solid #dee2e6;border-radius:.5rem;">
        </div>
    @endif

    <div class="col-12">
        <label class="form-label" for="short_description">Mô tả ngắn</label>
        <textarea class="form-control" id="short_description" name="short_description" rows="2">{{ old('short_description', $project->short_description ?? '') }}</textarea>
    </div>

    <div class="col-12">
        <label class="form-label" for="intro">Giới thiệu tổng quan dự án</label>
        <textarea class="form-control js-rich-editor" id="intro" name="intro" rows="5">{{ old('intro', $project->intro ?? '') }}</textarea>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="seo_title">SEO title</label>
        <input class="form-control" id="seo_title" name="seo_title" type="text"
            value="{{ old('seo_title', $project->seo_title ?? '') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label" for="seo_description">SEO description</label>
        <textarea class="form-control" id="seo_description" name="seo_description" rows="2">{{ old('seo_description', $project->seo_description ?? '') }}</textarea>
    </div>
</div>
