@extends('admin.layout')

@section('title', 'Quản lý dự án | HOVI CMS')

@push('head')
    <style>
        .project-manage-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 991px) {
            .project-manage-grid {
                grid-template-columns: 1fr;
            }
        }

        .project-box {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 8px 20px rgba(15, 23, 42, .04);
            margin-bottom: 1rem;
        }

        .project-box__head {
            padding: .9rem 1rem;
            border-bottom: 1px solid #eef2f7;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: .75rem;
        }

        .project-box__body {
            padding: 1rem;
        }

        .project-mini-table td,
        .project-mini-table th {
            padding-top: .55rem;
            padding-bottom: .55rem;
            vertical-align: middle;
        }

        .project-mini-thumb {
            width: 74px;
            height: 56px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h1 class="h3 mb-1">{{ $project->name }}</h1>
            <p class="text-muted mb-0">Slug route: <code>/{{ $project->slug }}</code></p>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-primary" href="{{ url('/' . $project->slug) }}" target="_blank"
                rel="noreferrer noopener">Xem trang dự án</a>
            <a class="btn btn-outline-dark" href="{{ route('admin.projects.index') }}">← Danh sách dự án</a>
        </div>
    </div>

    <div class="project-manage-grid">
        <div>
            <form action="{{ route('admin.projects.update', $project) }}" method="post" class="project-box mb-3">
                @csrf
                @method('PUT')

                <div class="project-box__head">
                    <h2 class="h6 mb-0">Thông tin tổng quan dự án</h2>
                    <button class="btn btn-sm btn-dark" type="submit">Lưu thay đổi</button>
                </div>
                <div class="project-box__body">
                    @include('admin.projects._form', ['project' => $project])
                </div>
            </form>

            <div class="project-box">
                <div class="project-box__head">
                    <h2 class="h6 mb-0">Trang chi tiết thuộc dự án</h2>
                    <div class="d-flex gap-2">
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.home-content.index') }}">Nội dung Trang chủ</a>
                        <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.projects.detail-pages.create', $project) }}">+ Thêm trang chi tiết</a>
                    </div>
                </div>
                <div class="project-box__body pb-0">
                    <div class="alert alert-info small mb-0">
                        <strong>Lưu ý:</strong> card dự án ở Trang chủ lấy từ <strong>thumbnail</strong> của các trang chi tiết.
                        Khuyến nghị ảnh <strong>1200 × 1600 px (3:4)</strong> để lên layout đẹp.
                    </div>
                </div>
                <div class="project-box__body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover project-mini-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Tiêu đề</th>
                                    <th>Slug</th>
                                    <th>Kiểu click</th>
                                    <th>Trạng thái</th>
                                    <th>Thứ tự</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($project->detailPages as $detailPage)
                                    <tr>
                                        <td>
                                            @if (!empty($detailPage->thumbnail_image))
                                                <img class="project-mini-thumb" src="{{ $detailPage->thumbnail_image }}"
                                                    alt="{{ $detailPage->title }}">
                                            @else
                                                <span class="badge text-bg-warning text-dark">Thiếu ảnh</span>
                                            @endif
                                        </td>
                                        <td>{{ $detailPage->title }}</td>
                                        <td><code>/{{ $detailPage->slug }}</code></td>
                                        <td>
                                            @if (($detailPage->thumbnail_click_action ?? 'link') === 'lightbox')
                                                <span class="badge text-bg-info">Mở ảnh giữa màn hình</span>
                                            @else
                                                <span class="badge text-bg-primary">Đi đến trang chi tiết</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($detailPage->is_published)
                                                <span class="badge text-bg-success">Đang bật</span>
                                            @else
                                                <span class="badge text-bg-secondary">Đang ẩn</span>
                                            @endif
                                        </td>
                                        <td>{{ $detailPage->sort_order }}</td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-dark"
                                                href="{{ route('admin.projects.detail-pages.edit', [$project, $detailPage]) }}">Sửa</a>
                                            <a class="btn btn-sm btn-outline-primary" href="{{ url('/' . $detailPage->slug) }}"
                                                target="_blank" rel="noreferrer noopener">Xem</a>
                                            <form class="d-inline"
                                                action="{{ route('admin.projects.detail-pages.destroy', [$project, $detailPage]) }}"
                                                method="post"
                                                onsubmit="return confirm('Xóa trang chi tiết này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-3">Chưa có trang chi tiết.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="project-box">
                <div class="project-box__head">
                    <h2 class="h6 mb-0">Blog dự án</h2>
                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.projects.blogs.create', $project) }}">+ Thêm blog</a>
                </div>
                <div class="project-box__body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover project-mini-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Slug</th>
                                    <th>Ngày đăng</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($project->blogs as $blog)
                                    <tr>
                                        <td>{{ $blog->title }}</td>
                                        <td><code>/{{ $blog->slug }}</code></td>
                                        <td>{{ optional($blog->published_at)->format('d/m/Y H:i') ?: '—' }}</td>
                                        <td>
                                            @if ($blog->is_published)
                                                <span class="badge text-bg-success">Đang bật</span>
                                            @else
                                                <span class="badge text-bg-secondary">Đang ẩn</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a class="btn btn-sm btn-outline-dark"
                                                href="{{ route('admin.projects.blogs.edit', [$project, $blog]) }}">Sửa</a>
                                            <form class="d-inline"
                                                action="{{ route('admin.projects.blogs.destroy', [$project, $blog]) }}"
                                                method="post" onsubmit="return confirm('Xóa blog này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Chưa có blog nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="project-box">
                <div class="project-box__head">
                    <h2 class="h6 mb-0">Video thực tế</h2>
                    <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.videos.create', ['project_id' => $project->id]) }}">+ Thêm video</a>
                </div>
                <div class="project-box__body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover project-mini-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Tiêu đề</th>
                                    <th>Slug</th>
                                    <th>Ngày đăng</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($project->videos as $video)
                                    <tr>
                                        <td>{{ $video->title }}</td>
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
                                            <a class="btn btn-sm btn-outline-dark"
                                                href="{{ route('admin.videos.edit', $video) }}">Sửa</a>
                                            @if (!empty($video->slug))
                                                <a class="btn btn-sm btn-outline-primary" href="{{ url('/' . $video->slug) }}"
                                                    target="_blank" rel="noreferrer noopener">Xem</a>
                                            @endif
                                            <form class="d-inline"
                                                action="{{ route('admin.videos.destroy', $video) }}"
                                                method="post" onsubmit="return confirm('Xóa video này?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Chưa có video thực tế.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <aside>
            <div class="project-box">
                <div class="project-box__head">
                    <h2 class="h6 mb-0">Tóm tắt nhanh</h2>
                </div>
                <div class="project-box__body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Trang chi tiết</span>
                            <strong>{{ $project->detailPages->count() }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Blog</span>
                            <strong>{{ $project->blogs->count() }}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Video thực tế</span>
                            <strong>{{ $project->videos->count() }}</strong>
                        </li>
                    </ul>
                    <div class="alert alert-light border mt-3 small mb-0">
                        Gợi ý: đặt <strong>slug</strong> không dấu để route ổn định và dễ SEO.
                    </div>
                </div>
            </div>
        </aside>
    </div>
@endsection
