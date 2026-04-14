@extends('admin.layout')

@section('title', 'Quản lý Blog | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Quản lý Blog</h1>
            <p class="text-muted mb-0">Quản trị danh sách bài blog và trang chi tiết blog.</p>
        </div>

        <a class="btn btn-dark" href="{{ route('admin.blogs.create') }}">+ Tạo bài blog</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tiêu đề</th>
                        <th>Dự án</th>
                        <th>Slug</th>
                        <th>Ngày đăng</th>
                        <th>Hiển thị</th>
                        <th>Thứ tự</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($blogs as $blog)
                        <tr>
                            <td>{{ $blog->id }}</td>
                            <td>
                                <strong>{{ $blog->title }}</strong>
                                @if (!empty($blog->excerpt))
                                    <div class="small text-muted">{{ \Illuminate\Support\Str::limit($blog->excerpt, 90) }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($blog->project)
                                    <span class="badge text-bg-info">{{ $blog->project->name }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td><code>/blog/{{ $blog->slug }}</code></td>
                            <td>{{ optional($blog->published_at)->format('d/m/Y H:i') ?: '—' }}</td>
                            <td>
                                @if ($blog->is_published)
                                    <span class="badge text-bg-success">Đang bật</span>
                                @else
                                    <span class="badge text-bg-secondary">Đang ẩn</span>
                                @endif
                            </td>
                            <td>{{ $blog->sort_order }}</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.blogs.edit', $blog) }}">Sửa</a>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('site.blog.show', $blog->slug) }}" target="_blank"
                                    rel="noreferrer noopener">Xem chi tiết</a>
                                <form action="{{ route('admin.blogs.destroy', $blog) }}" method="post" class="d-inline"
                                    onsubmit="return confirm('Xóa bài blog này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Chưa có bài blog nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $blogs->links() }}
    </div>
@endsection
