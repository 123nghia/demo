@extends('admin.layout')

@section('title', 'Thêm mục menu | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Thêm mục menu mới</h1>
        <a class="btn btn-outline-dark" href="{{ route('admin.menu-items.index') }}">Quay lại</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.menu-items.store') }}" method="post" class="d-grid gap-3">
                @csrf
                @include('admin.menu-items._form', [
                    'menuItem' => $menuItem,
                    'zones' => $zones,
                    'parentOptions' => $parentOptions,
                ])

                <div>
                    <button class="btn btn-dark" type="submit">Lưu menu</button>
                </div>
            </form>
        </div>
    </div>
@endsection
