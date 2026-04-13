@extends('admin.layout')

@section('title', 'Sửa trang | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Sửa trang: {{ $page->name }}</h1>
            <p class="text-muted mb-0">Đường dẫn hiện tại: <code>/{{ $page->slug === 'home' ? '' : $page->slug }}</code></p>
        </div>

        <a class="btn btn-outline-dark" href="{{ route('admin.pages.index') }}">Quay lại</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.pages.update', $page) }}" method="post" class="d-grid gap-3">
                @csrf
                @method('PUT')
                @include('admin.pages._form', ['page' => $page])

                <div>
                    <button class="btn btn-dark" type="submit">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
@endsection
