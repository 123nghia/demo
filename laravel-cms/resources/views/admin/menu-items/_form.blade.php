@php
    /** @var \App\Models\MenuItem|null $menuItem */
    $menuItem = $menuItem ?? null;
    $zones = $zones ?? \App\Models\MenuItem::zones();
    $parentOptions = $parentOptions ?? collect();
@endphp

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label" for="label">Tên menu</label>
        <input class="form-control" id="label" name="label" type="text"
            value="{{ old('label', $menuItem->label ?? '') }}" required>
    </div>

    <div class="col-md-6">
        <label class="form-label" for="url">Đường dẫn (URL)</label>
        <input class="form-control" id="url" name="url" type="text"
            value="{{ old('url', $menuItem->url ?? '') }}" placeholder="/about-us hoặc https://example.com" required>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="menu_zone">Vùng menu</label>
        <select class="form-select" id="menu_zone" name="menu_zone" required>
            @foreach ($zones as $zoneValue => $zoneLabel)
                <option value="{{ $zoneValue }}" @selected(old('menu_zone', $menuItem->menu_zone ?? 'main') === $zoneValue)>
                    {{ $zoneLabel }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="parent_id">Menu cha</label>
        <select class="form-select" id="parent_id" name="parent_id">
            <option value="">-- Không có (menu cha cấp 1) --</option>
            @foreach ($parentOptions as $parent)
                <option value="{{ $parent->id }}" @selected((string) old('parent_id', $menuItem->parent_id ?? '') === (string) $parent->id)>
                    {{ $parent->label }}
                    ({{ $zones[$parent->menu_zone] ?? $parent->menu_zone }})
                </option>
            @endforeach
        </select>
        <div class="form-text">Chọn menu cha để tạo menu con (chỉ 1 cấp con).</div>
    </div>

    <div class="col-md-4">
        <label class="form-label" for="page_key">Page key (active menu)</label>
        <input class="form-control" id="page_key" name="page_key" list="pageKeys"
            value="{{ old('page_key', $menuItem->page_key ?? '') }}">
        <datalist id="pageKeys">
            <option value="home"></option>
            <option value="about"></option>
            <option value="blog"></option>
            <option value="oceanpark"></option>
            <option value="contact"></option>
            <option value="project"></option>
        </datalist>
    </div>

    <div class="col-md-3">
        <label class="form-label" for="sort_order">Thứ tự hiển thị</label>
        <input class="form-control" id="sort_order" name="sort_order" type="number" min="0"
            value="{{ old('sort_order', $menuItem->sort_order ?? 0) }}">
    </div>

    <div class="col-md-3 d-flex align-items-end">
        <div class="d-grid gap-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active"
                    @checked(old('is_active', $menuItem ? $menuItem->is_active : true))>
                <label class="form-check-label" for="is_active">Hiển thị menu</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="open_in_new_tab" name="open_in_new_tab"
                    @checked(old('open_in_new_tab', $menuItem ? $menuItem->open_in_new_tab : false))>
                <label class="form-check-label" for="open_in_new_tab">Mở tab mới</label>
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="is_home_icon" name="is_home_icon"
                    @checked(old('is_home_icon', $menuItem ? $menuItem->is_home_icon : false))>
                <label class="form-check-label" for="is_home_icon">Hiển thị icon home</label>
            </div>
        </div>
    </div>
</div>
