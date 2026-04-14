@extends('admin.layout')

@section('title', 'Sửa mục menu | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-0">Sửa mục menu: {{ $menuItem->label }}</h1>
            <p class="text-muted mb-0">URL hiện tại: <code>{{ $menuItem->url }}</code></p>
        </div>

        <a class="btn btn-outline-dark" href="{{ route('admin.menu-items.index') }}">Quay lại</a>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form action="{{ route('admin.menu-items.update', $menuItem) }}" method="post" class="d-grid gap-3">
                @csrf
                @method('PUT')
                @include('admin.menu-items._form', [
                    'menuItem' => $menuItem,
                    'zones' => $zones,
                    'parentOptions' => $parentOptions,
                ])

                <div>
                    <button class="btn btn-dark" type="submit">Cập nhật menu</button>
                </div>
            </form>
        </div>
    </div>
@endsection
