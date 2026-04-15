@extends('admin.layout')

@section('title', 'Tạo dự án | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Tạo dự án mới</h1>
            <p class="text-muted mb-0">Khởi tạo thông tin nền để tiếp tục thêm trang chi tiết, blog và video.</p>
        </div>
        <a class="btn btn-outline-dark" href="{{ route('admin.projects.index') }}">← Quay lại</a>
    </div>

    <form action="{{ route('admin.projects.store') }}" method="post" enctype="multipart/form-data"
        class="card border-0 shadow-sm">
        @csrf
        <div class="card-body">
            @include('admin.projects._form')
        </div>
        <div class="card-footer bg-white d-flex justify-content-end">
            <button class="btn btn-dark" type="submit">Lưu dự án</button>
        </div>
    </form>
@endsection
