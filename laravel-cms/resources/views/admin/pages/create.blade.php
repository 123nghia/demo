@extends('admin.layout')

@section('title', 'Thêm trang | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Thêm trang mới</h1>
        <a class="btn btn-outline-dark" href="{{ route('admin.pages.index') }}">Quay lại</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.pages.store') }}" method="post" class="d-grid gap-3">
                @csrf
                @include('admin.pages._form')

                <div>
                    <button class="btn btn-dark" type="submit">Lưu trang</button>
                </div>
            </form>
        </div>
    </div>
@endsection
