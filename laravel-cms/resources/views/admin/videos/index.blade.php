@extends('admin.layout')

@section('title', 'Quản lý Video | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Quản lý Video</h1>
            <p class="text-muted mb-0">Quản trị danh sách video và trang chi tiết video trên frontend.</p>
        </div>

        <a class="btn btn-dark" href="{{ route('admin.videos.create') }}">+ Tạo video</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Tiêu đề</th>
                        <th>Dự án</th>
                        <th>Phân vùng</th>
                        <th>Slug</th>
                        <th>Ngày đăng</th>
                        <th>Hiển thị</th>
                        <th class="text-end">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($videos as $video)
                        <tr>
                            <td>{{ $video->id }}</td>
                            <td>
                                <strong>{{ $video->title }}</strong>
                                @if (!empty($video->description))
                                    <div class="small text-muted">{{ \Illuminate\Support\Str::limit($video->description, 90) }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($video->project)
                                    <span class="badge text-bg-info">{{ $video->project->name }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if (($video->display_zone ?? 'all') === 'project')
                                    <span class="badge text-bg-warning">Khu vực dự án</span>
                                @elseif (($video->display_zone ?? 'all') === 'video')
                                    <span class="badge text-bg-primary">Trang Video</span>
                                @else
                                    <span class="badge text-bg-dark">Toàn site</span>
                                @endif
                            </td>
                            <td><code>/{{ $video->slug }}</code></td>
                            <td>{{ optional($video->published_at)->format('d/m/Y H:i') ?: '—' }}</td>
                            <td>
                                @if ($video->is_published)
                                    <span class="badge text-bg-success">Đang bật</span>
                                @else
                                    <span class="badge text-bg-secondary">Đang ẩn</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.videos.edit', $video) }}">Sửa</a>
                                @if (!empty($video->slug))
                                    <a class="btn btn-sm btn-outline-primary" href="{{ url('/' . $video->slug) }}" target="_blank"
                                        rel="noreferrer noopener">Xem chi tiết</a>
                                @endif
                                <form action="{{ route('admin.videos.destroy', $video) }}" method="post" class="d-inline"
                                    onsubmit="return confirm('Xóa video này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Xoá</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">Chưa có video nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $videos->links() }}
    </div>
@endsection
