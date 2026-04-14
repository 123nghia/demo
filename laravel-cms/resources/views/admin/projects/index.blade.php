@extends('admin.layout')

@section('title', 'Quản trị dự án | HOVI CMS')

@push('head')
    <style>
        .project-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 1rem;
        }

        .project-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .06);
            overflow: hidden;
        }

        .project-card__head {
            background: linear-gradient(120deg, #0f172a 0%, #1d4ed8 100%);
            color: #fff;
            padding: 14px 16px;
        }

        .project-meta {
            display: flex;
            gap: .5rem;
            flex-wrap: wrap;
        }

        .project-meta .badge {
            font-weight: 500;
        }
    </style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Quản trị dự án</h1>
            <p class="text-muted mb-0">Mỗi dự án có thể quản lý nhiều trang chi tiết, blog và video thực tế.</p>
        </div>

        <a class="btn btn-dark" href="{{ route('admin.projects.create') }}">+ Tạo dự án mới</a>
    </div>

    <div class="project-grid">
        @forelse ($projects as $project)
            <article class="card project-card">
                <div class="project-card__head">
                    <div class="d-flex justify-content-between align-items-start gap-3">
                        <div>
                            <h2 class="h5 mb-1">{{ $project->name }}</h2>
                            <div class="small opacity-75"><code class="text-white">/{{ $project->slug }}</code></div>
                        </div>
                        @if ($project->is_published)
                            <span class="badge text-bg-success">Đang bật</span>
                        @else
                            <span class="badge text-bg-secondary">Đang ẩn</span>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <p class="text-muted small mb-3">
                        {{ \Illuminate\Support\Str::limit($project->short_description ?: 'Chưa có mô tả ngắn.', 130) }}
                    </p>

                    <div class="project-meta mb-3">
                        <span class="badge text-bg-primary">Chi tiết: {{ $project->detail_pages_count }}</span>
                        <span class="badge text-bg-info">Blog: {{ $project->blogs_count }}</span>
                        <span class="badge text-bg-warning text-dark">Video: {{ $project->videos_count }}</span>
                        <span class="badge text-bg-light text-dark">Thứ tự: {{ $project->sort_order }}</span>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <a class="btn btn-sm btn-outline-dark" href="{{ route('admin.projects.edit', $project) }}">Quản lý nội dung</a>
                        <a class="btn btn-sm btn-outline-primary" href="{{ url('/' . $project->slug) }}" target="_blank"
                            rel="noreferrer noopener">Xem trang</a>

                        <form action="{{ route('admin.projects.destroy', $project) }}" method="post"
                            onsubmit="return confirm('Xóa dự án này và toàn bộ dữ liệu con?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger" type="submit">Xóa</button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="alert alert-light border text-muted">Chưa có dự án nào. Bấm “Tạo dự án mới” để bắt đầu.</div>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $projects->links() }}
    </div>
@endsection
