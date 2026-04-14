@extends('admin.layout')

@section('title', 'Sửa video dự án | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Sửa video thực tế</h1>
            <p class="text-muted mb-0">Dự án: <strong>{{ $project->name }}</strong></p>
        </div>
        <a class="btn btn-outline-dark" href="{{ route('admin.projects.edit', $project) }}">← Quay lại dự án</a>
    </div>

    <form action="{{ route('admin.projects.videos.update', [$project, $video]) }}" method="post"
        class="card border-0 shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.projects.videos._form', ['video' => $video])
        </div>
        <div class="card-footer bg-white d-flex justify-content-end">
            <button class="btn btn-dark" type="submit">Cập nhật video</button>
        </div>
    </form>
@endsection
