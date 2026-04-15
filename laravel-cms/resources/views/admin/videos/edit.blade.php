@extends('admin.layout')

@section('title', 'Sửa video | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Sửa video</h1>
            <p class="text-muted mb-0">Cập nhật nội dung, SEO, thumbnail và link phát video.</p>
        </div>

        <a class="btn btn-outline-dark" href="{{ route('admin.videos.index') }}">← Danh sách video</a>
    </div>

    <form action="{{ route('admin.videos.update', $video) }}" method="post" enctype="multipart/form-data"
        class="card border-0 shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.videos._form', ['video' => $video])
        </div>
        <div class="card-footer bg-white d-flex justify-content-end">
            <button class="btn btn-dark" type="submit">Cập nhật video</button>
        </div>
    </form>
@endsection
