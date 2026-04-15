@extends('admin.layout')

@section('title', 'Tạo video | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Tạo video mới</h1>
            <p class="text-muted mb-0">Thêm video để hiển thị ở trang Video, trang dự án hoặc cả hai.</p>
        </div>

        <a class="btn btn-outline-dark" href="{{ route('admin.videos.index') }}">← Danh sách video</a>
    </div>

    <form action="{{ route('admin.videos.store') }}" method="post" enctype="multipart/form-data"
        class="card border-0 shadow-sm">
        @csrf
        <div class="card-body">
            @include('admin.videos._form')
        </div>
        <div class="card-footer bg-white d-flex justify-content-end">
            <button class="btn btn-dark" type="submit">Lưu video</button>
        </div>
    </form>
@endsection
