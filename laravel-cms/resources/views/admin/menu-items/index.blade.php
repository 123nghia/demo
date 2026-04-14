@extends('admin.layout')

@section('title', 'Quản lý menu | HOVI CMS')

@section('content')
    @php
        $zoneFilter = $zoneFilter ?? 'all';
        $zones = $zones ?? \App\Models\MenuItem::zones();
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Quản lý menu</h1>
            <p class="text-muted mb-0">Quản lý menu theo vùng, menu cha và menu con để admin thao tác nhanh hơn.</p>
        </div>

        <a class="btn btn-dark" href="{{ route('admin.menu-items.create') }}">+ Thêm mục menu</a>
    </div>

    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a class="nav-link {{ $zoneFilter === 'all' ? 'active' : '' }}"
                href="{{ route('admin.menu-items.index', ['menu_zone' => 'all']) }}">Tất cả vùng menu</a>
        </li>
        @foreach ($zones as $zoneValue => $zoneLabel)
            <li class="nav-item">
                <a class="nav-link {{ $zoneFilter === $zoneValue ? 'active' : '' }}"
                    href="{{ route('admin.menu-items.index', ['menu_zone' => $zoneValue]) }}">{{ $zoneLabel }}</a>
            </li>
        @endforeach
    </ul>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Vùng</th>
                        <th>Cấp menu</th>
                        <th>Tên menu</th>
                        <th>URL</th>
                        <th>Page key</th>
                        <th>Hiển thị</th>
                        <th>Thứ tự</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($menuItems as $menuItem)
                        @php
                            $isChild = !is_null($menuItem->parent_id);
                            $zoneLabel = $zones[$menuItem->menu_zone] ?? $menuItem->menu_zone;
                        @endphp

                        <tr>
                            <td>{{ $menuItem->id }}</td>
                            <td>
                                <span class="badge text-bg-light border">{{ $zoneLabel }}</span>
                            </td>
                            <td>
                                @if ($isChild)
                                    <span class="badge text-bg-info">Menu con</span>
                                    <div class="small text-muted mt-1">Cha: {{ $menuItem->parent->label ?? '—' }}</div>
                                @else
                                    <span class="badge text-bg-dark">Menu cha</span>
                                @endif
                            </td>
                            <td>
                                <strong>{{ $isChild ? '↳ ' : '' }}{{ $menuItem->label }}</strong>
                                <div class="small text-muted">
                                    @if ($menuItem->open_in_new_tab)
                                        Mở tab mới
                                    @else
                                        Mở cùng tab
                                    @endif

                                    @if ($menuItem->is_home_icon)
                                        · Có icon home
                                    @endif
                                </div>
                            </td>
                            <td><code>{{ $menuItem->url }}</code></td>
                            <td>{{ $menuItem->page_key ?: '—' }}</td>
                            <td>
                                @if ($menuItem->is_active)
                                    <span class="badge text-bg-success">Đang bật</span>
                                @else
                                    <span class="badge text-bg-secondary">Đang ẩn</span>
                                @endif
                            </td>
                            <td>{{ $menuItem->sort_order }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-dark"
                                    href="{{ route('admin.menu-items.edit', $menuItem) }}">Sửa</a>

                                <form action="{{ route('admin.menu-items.destroy', $menuItem) }}" method="post"
                                    class="d-inline" onsubmit="return confirm('Xoá mục menu này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4 text-muted">Chưa có mục menu nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $menuItems->links() }}
    </div>
@endsection
