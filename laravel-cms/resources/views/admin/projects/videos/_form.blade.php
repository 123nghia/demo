@php
    /** @var \App\Models\ProjectVideo|null $video */
    $video = $video ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-7">
        <label class="form-label" for="title">Tiêu đề video</label>
        <input class="form-control" id="title" name="title" type="text"
            value="{{ old('title', $video->title ?? '') }}" required>
    </div>

    <div class="col-md-3">
        <label class="form-label" for="sort_order">Thứ tự</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0"
            value="{{ old('sort_order', $video->sort_order ?? 0) }}">
    </div>

    <div class="col-md-2 d-flex align-items-end">
        <div class="form-check form-switch mb-2">
            <input type="hidden" name="is_published" value="0">
            <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published"
                @checked(old('is_published', $video ? $video->is_published : true))>
            <label class="form-check-label" for="is_published">Hiển thị</label>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="video_url">Link video</label>
        <input class="form-control" id="video_url" name="video_url" type="text"
            value="{{ old('video_url', $video->video_url ?? '') }}">
        <div class="form-text">Ví dụ: YouTube, Facebook Watch, TikTok, hoặc link nội bộ.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="thumbnail_image">Thumbnail video</label>
        <input class="form-control" id="thumbnail_image" name="thumbnail_image" type="text"
            value="{{ old('thumbnail_image', $video->thumbnail_image ?? '') }}">
    </div>

    <div class="col-12">
        <label class="form-label" for="description">Mô tả ngắn</label>
        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $video->description ?? '') }}</textarea>
    </div>
</div>
