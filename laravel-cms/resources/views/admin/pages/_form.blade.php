@php
    /** @var \App\Models\Page|null $page */
    $page = $page ?? null;
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="name">Tên trang</label>
        <input class="form-control" id="name" name="name" type="text" value="{{ old('name', $page->name ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="slug">Slug</label>
        <input class="form-control" id="slug" name="slug" type="text" value="{{ old('slug', $page->slug ?? '') }}"
            required>
        <div class="form-text">Ví dụ: <code>about-us</code> hoặc <code>home</code>.</div>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="legacy_file">File giao diện (resources/legacy)</label>
        <input class="form-control" id="legacy_file" name="legacy_file" list="legacyFiles"
            value="{{ old('legacy_file', $page->legacy_file ?? '') }}" required>
        <datalist id="legacyFiles">
            <option value="home.html"></option>
            <option value="about-us.html"></option>
            <option value="lien-he.html"></option>
            <option value="ocean-park.html"></option>
            <option value="duong-noi.html"></option>
        </datalist>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="page_key">Page key (active menu)</label>
        <input class="form-control" id="page_key" name="page_key" list="pageKeys"
            value="{{ old('page_key', $page->page_key ?? 'home') }}" required>
        <datalist id="pageKeys">
            <option value="home"></option>
            <option value="about"></option>
            <option value="oceanpark"></option>
            <option value="contact"></option>
            <option value="project"></option>
        </datalist>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="seo_title">SEO title</label>
        <input class="form-control" id="seo_title" name="seo_title" type="text"
            value="{{ old('seo_title', $page->seo_title ?? '') }}">
    </div>

    <div class="col-md-3">
        <label class="form-label" for="sort_order">Thứ tự hiển thị</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0"
            value="{{ old('sort_order', $page->sort_order ?? 0) }}">
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox" value="1" id="is_published" name="is_published"
                @checked(old('is_published', $page ? $page->is_published : true))>
            <label class="form-check-label" for="is_published">
                Hiển thị công khai
            </label>
        </div>
    </div>

    <div class="col-12">
        <label class="form-label" for="seo_description">SEO description</label>
        <textarea class="form-control" id="seo_description" name="seo_description" rows="3">{{ old('seo_description', $page->seo_description ?? '') }}</textarea>
    </div>
</div>
