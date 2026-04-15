@extends('admin.layout')

@section('title', 'Tạo Blog mới | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Tạo bài Blog mới</h1>
            <p class="text-muted mb-0">Nội dung này sẽ hiển thị ở danh sách Blog và có trang chi tiết riêng.</p>
        </div>
        <a class="btn btn-outline-dark" href="{{ route('admin.blogs.index') }}">← Quay lại</a>
    </div>

    <form action="{{ route('admin.blogs.store') }}" method="post" enctype="multipart/form-data"
        class="card border-0 shadow-sm">
        @csrf
        <div class="card-body">
            @include('admin.blogs._form')
        </div>
        <div class="card-footer bg-white d-flex justify-content-end">
            <button class="btn btn-dark" type="submit">Lưu blog</button>
        </div>
    </form>
@endsection
