@extends('admin.layout')

@section('title', 'Sửa Blog | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Sửa bài Blog</h1>
            <p class="text-muted mb-0">Slug: <code>/blog/{{ $blog->slug }}</code></p>
        </div>
        <a class="btn btn-outline-dark" href="{{ route('admin.blogs.index') }}">← Quay lại</a>
    </div>

    <form action="{{ route('admin.blogs.update', $blog) }}" method="post" class="card border-0 shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.blogs._form', ['blog' => $blog])
        </div>
        <div class="card-footer bg-white d-flex justify-content-end gap-2">
            <a class="btn btn-outline-primary" href="{{ route('site.blog.show', $blog->slug) }}" target="_blank"
                rel="noreferrer noopener">Xem chi tiết</a>
            <button class="btn btn-dark" type="submit">Cập nhật</button>
        </div>
    </form>
@endsection
