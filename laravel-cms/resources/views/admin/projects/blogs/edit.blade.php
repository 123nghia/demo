@extends('admin.layout')

@section('title', 'Sửa blog dự án | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Sửa blog dự án</h1>
            <p class="text-muted mb-0">Dự án: <strong>{{ $project->name }}</strong></p>
        </div>
        <a class="btn btn-outline-dark" href="{{ route('admin.projects.edit', $project) }}">← Quay lại dự án</a>
    </div>

    <form action="{{ route('admin.projects.blogs.update', [$project, $blog]) }}" method="post"
        enctype="multipart/form-data" class="card border-0 shadow-sm">
        @csrf
        @method('PUT')
        <div class="card-body">
            @include('admin.projects.blogs._form', ['blog' => $blog])
        </div>
        <div class="card-footer bg-white d-flex justify-content-end">
            <button class="btn btn-dark" type="submit">Cập nhật blog</button>
        </div>
    </form>
@endsection
