@extends('admin.layout')

@section('title', 'Chi tiết liên hệ | HOVI CMS')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Chi tiết liên hệ #{{ $contactMessage->id }}</h1>
            <p class="text-muted mb-0">Gửi lúc {{ $contactMessage->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <a class="btn btn-outline-dark" href="{{ route('admin.contact-messages.index') }}">Quay lại danh sách</a>
    </div>

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-3">Họ tên</dt>
                <dd class="col-sm-9">{{ $contactMessage->name }}</dd>

                <dt class="col-sm-3">Điện thoại</dt>
                <dd class="col-sm-9">{{ $contactMessage->phone ?: '-' }}</dd>

                <dt class="col-sm-3">Email</dt>
                <dd class="col-sm-9">{{ $contactMessage->email ?: '-' }}</dd>

                <dt class="col-sm-3">Dịch vụ</dt>
                <dd class="col-sm-9">{{ $contactMessage->service ?: '-' }}</dd>

                <dt class="col-sm-3">Nguồn trang</dt>
                <dd class="col-sm-9"><code>{{ $contactMessage->source_page ?: '-' }}</code></dd>
            </dl>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h2 class="h5">Nội dung khách hàng</h2>
            <p class="mb-0" style="white-space: pre-wrap;">{{ $contactMessage->message }}</p>
        </div>
    </div>
@endsection
