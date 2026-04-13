@extends('admin.layout')

@section('title', 'Tổng quan | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Tổng quan quản trị</h1>
            <p class="text-muted mb-0">Theo dõi nhanh trạng thái nội dung website.</p>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Tổng số trang</p>
                    <p class="display-6 mb-0">{{ $stats['pages'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Trang đang hiển thị</p>
                    <p class="display-6 mb-0">{{ $stats['published_pages'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Tổng liên hệ</p>
                    <p class="display-6 mb-0">{{ $stats['messages'] }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-1">Liên hệ chưa đọc</p>
                    <p class="display-6 mb-0 text-danger">{{ $stats['unread_messages'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body d-flex flex-wrap gap-2">
            <a class="btn btn-dark" href="{{ route('admin.pages.index') }}">Quản lý trang</a>
            <a class="btn btn-outline-dark" href="{{ route('admin.contact-messages.index') }}">Xem liên hệ khách hàng</a>
        </div>
    </div>
@endsection
