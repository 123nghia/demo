@extends('admin.layout')

@section('title', 'Thêm trang chi tiết dự án | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Thêm trang chi tiết</h1>
            <p class="text-muted mb-0">Dự án: <strong>{{ $project->name }}</strong></p>
        </div>
        <a class="btn btn-outline-dark" href="{{ route('admin.projects.edit', $project) }}">← Quay lại dự án</a>
    </div>

    <form action="{{ route('admin.projects.detail-pages.store', $project) }}" method="post"
        enctype="multipart/form-data" class="card border-0 shadow-sm">
        @csrf
        <div class="card-body">
            @include('admin.projects.detail-pages._form')
        </div>
        <div class="card-footer bg-white d-flex justify-content-end">
            <button class="btn btn-dark" type="submit">Lưu trang chi tiết</button>
        </div>
    </form>
@endsection
