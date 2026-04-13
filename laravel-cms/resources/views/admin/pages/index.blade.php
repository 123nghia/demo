@extends('admin.layout')

@section('title', 'Quản lý trang | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Quản lý trang</h1>
            <p class="text-muted mb-0">Thiết lập slug, file giao diện và SEO cho từng trang.</p>
        </div>

        <a class="btn btn-dark" href="{{ route('admin.pages.create') }}">+ Thêm trang</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tên</th>
                        <th>Slug</th>
                        <th>Legacy file</th>
                        <th>Hiển thị</th>
                        <th>Thứ tự</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pages as $page)
                        <tr>
                            <td>{{ $page->id }}</td>
                            <td>
                                <strong>{{ $page->name }}</strong>
                                <div class="small text-muted">{{ $page->page_key }}</div>
                            </td>
                            <td><code>{{ $page->slug }}</code></td>
                            <td><code>{{ $page->legacy_file }}</code></td>
                            <td>
                                @if ($page->is_published)
                                    <span class="badge text-bg-success">Đang bật</span>
                                @else
                                    <span class="badge text-bg-secondary">Đang ẩn</span>
                                @endif
                            </td>
                            <td>{{ $page->sort_order }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.pages.edit', $page) }}">Sửa</a>

                                <form action="{{ route('admin.pages.destroy', $page) }}" method="post" class="d-inline"
                                    onsubmit="return confirm('Xoá trang này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Chưa có dữ liệu trang.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $pages->links() }}
    </div>
@endsection
