@extends('admin.layout')

@section('title', 'Tổng quan | HOVI CMS')

@push('head')
    <style>
        .admin-dashboard-hero {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 52%, #0284c7 100%);
            color: #fff;
            border: 0 !important;
        }

        .admin-dashboard-hero .text-muted {
            color: rgba(255, 255, 255, .75) !important;
        }

        .admin-dashboard-hero .btn-outline-light {
            border-color: rgba(255, 255, 255, .44);
        }

        .admin-dashboard-hero .btn-outline-light:hover {
            color: #0f172a;
            background: #fff;
            border-color: #fff;
        }

        .admin-kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: .85rem;
        }

        .admin-kpi-card {
            border-radius: 16px;
            padding: 14px 16px;
            position: relative;
            overflow: hidden;
            border: 1px solid #dce5f0;
            background: #fff;
            box-shadow: 0 12px 28px rgba(15, 23, 42, .07);
        }

        .admin-kpi-card::after {
            content: '';
            position: absolute;
            inset: -50% auto auto -28%;
            width: 150px;
            height: 150px;
            border-radius: 999px;
            background: var(--kpi-bg, rgba(37, 99, 235, .14));
            opacity: .9;
            pointer-events: none;
        }

        .admin-kpi-card__label {
            margin: 0;
            color: #64748b;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .02em;
            position: relative;
            z-index: 1;
        }

        .admin-kpi-card__value {
            margin: 6px 0 0;
            font-size: 2rem;
            line-height: 1;
            font-weight: 800;
            letter-spacing: -.02em;
            color: #0f172a;
            position: relative;
            z-index: 1;
        }

        .admin-kpi-card__meta {
            margin: 7px 0 0;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 8px;
            border-radius: 999px;
            background: var(--kpi-bg, rgba(37, 99, 235, .14));
            color: #1e3a8a;
            font-size: .73rem;
            font-weight: 700;
            position: relative;
            z-index: 1;
        }

        .admin-action-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: .65rem;
        }

        .admin-action-link {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            border: 1px solid #dbe5f1;
            border-radius: 12px;
            padding: 12px;
            color: #0f172a;
            background: #f8fbff;
            transition: border-color .2s ease, background-color .2s ease, transform .2s ease;
        }

        .admin-action-link:hover {
            border-color: #93c5fd;
            background: #eff6ff;
            transform: translateY(-1px);
            color: #0f172a;
        }

        .admin-action-link__icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            display: grid;
            place-items: center;
            background: rgba(37, 99, 235, .14);
            font-size: .9rem;
            flex: 0 0 auto;
        }

        .admin-focus-list {
            display: grid;
            gap: .65rem;
        }

        .admin-focus-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 9px 0;
            border-bottom: 1px dashed #d9e2ef;
            font-size: .9rem;
        }

        .admin-focus-item:last-child {
            border-bottom: 0;
            padding-bottom: 0;
        }

        .admin-focus-item strong {
            font-size: 1.02rem;
            letter-spacing: -.01em;
        }
    </style>
@endpush

@section('content')
    @php
        $kpis = [
            [
                'label' => 'Tổng số trang',
                'value' => $stats['pages'],
                'meta' => '📄 Nội dung tĩnh',
                'bg' => 'rgba(37, 99, 235, .14)',
            ],
            [
                'label' => 'Trang đang hiển thị',
                'value' => $stats['published_pages'],
                'meta' => '✅ Đang public',
                'bg' => 'rgba(5, 150, 105, .14)',
            ],
            [
                'label' => 'Tổng dự án',
                'value' => $stats['projects'],
                'meta' => '🏗️ Portfolio',
                'bg' => 'rgba(2, 132, 199, .14)',
            ],
            [
                'label' => 'Dự án đang bật',
                'value' => $stats['published_projects'],
                'meta' => '🚀 Đang chạy',
                'bg' => 'rgba(168, 85, 247, .14)',
            ],
            [
                'label' => 'Tổng liên hệ',
                'value' => $stats['messages'],
                'meta' => '📨 Tất cả inbox',
                'bg' => 'rgba(14, 165, 233, .14)',
            ],
            [
                'label' => 'Liên hệ chưa đọc',
                'value' => $stats['unread_messages'],
                'meta' => '🔔 Cần xử lý',
                'bg' => 'rgba(239, 68, 68, .14)',
            ],
        ];
    @endphp

    <section class="card admin-dashboard-hero mb-3">
        <div class="card-body p-4 p-lg-4">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <h1 class="h3 text-white mb-1">Bảng điều khiển quản trị</h1>
                    <p class="mb-0 text-muted">Theo dõi trạng thái nội dung website và truy cập nhanh các tác vụ quan trọng.</p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <a class="btn btn-sm btn-light" href="{{ route('admin.pages.create') }}">+ Tạo trang mới</a>
                    <a class="btn btn-sm btn-outline-light" href="{{ route('admin.projects.create') }}">+ Tạo dự án mới</a>
                </div>
            </div>
        </div>
    </section>

    <section class="admin-kpi-grid mb-3">
        @foreach ($kpis as $kpi)
            <article class="admin-kpi-card" style="--kpi-bg: {{ $kpi['bg'] }};">
                <p class="admin-kpi-card__label">{{ $kpi['label'] }}</p>
                <p class="admin-kpi-card__value">{{ $kpi['value'] }}</p>
                <span class="admin-kpi-card__meta">{{ $kpi['meta'] }}</span>
            </article>
        @endforeach
    </section>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header">Tác vụ nhanh</div>
                <div class="card-body">
                    <div class="admin-action-list">
                        <a class="admin-action-link" href="{{ route('admin.pages.index') }}">
                            <span class="admin-action-link__icon">📄</span>
                            <span>Quản lý trang</span>
                        </a>
                        <a class="admin-action-link" href="{{ route('admin.projects.index') }}">
                            <span class="admin-action-link__icon">🏗️</span>
                            <span>Quản trị dự án</span>
                        </a>
                        <a class="admin-action-link" href="{{ route('admin.blogs.index') }}">
                            <span class="admin-action-link__icon">📝</span>
                            <span>Quản lý blog</span>
                        </a>
                        <a class="admin-action-link" href="{{ route('admin.videos.index') }}">
                            <span class="admin-action-link__icon">🎬</span>
                            <span>Quản lý video</span>
                        </a>
                        <a class="admin-action-link" href="{{ route('admin.home-content.index') }}">
                            <span class="admin-action-link__icon">🏠</span>
                            <span>Nội dung trang chủ</span>
                        </a>
                        <a class="admin-action-link" href="{{ route('admin.about-content.edit') }}">
                            <span class="admin-action-link__icon">🌿</span>
                            <span>Nội dung About Us</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">Trọng tâm hôm nay</div>
                <div class="card-body">
                    <div class="admin-focus-list">
                        <div class="admin-focus-item">
                            <span>Liên hệ chưa đọc</span>
                            <strong class="text-danger">{{ $stats['unread_messages'] }}</strong>
                        </div>
                        <div class="admin-focus-item">
                            <span>Tỷ lệ trang hiển thị</span>
                            <strong>
                                {{ $stats['pages'] > 0 ? round(($stats['published_pages'] / $stats['pages']) * 100) : 0 }}%
                            </strong>
                        </div>
                        <div class="admin-focus-item">
                            <span>Tỷ lệ dự án hiển thị</span>
                            <strong>
                                {{ $stats['projects'] > 0 ? round(($stats['published_projects'] / $stats['projects']) * 100) : 0 }}%
                            </strong>
                        </div>
                    </div>

                    <div class="mt-3 d-grid gap-2">
                        <a class="btn btn-outline-dark" href="{{ route('admin.contact-messages.index') }}">Xử lý liên hệ khách hàng</a>
                        <a class="btn btn-dark" href="{{ route('admin.settings.edit') }}">Cập nhật SEO & Footer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
