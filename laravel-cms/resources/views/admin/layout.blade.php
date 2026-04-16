<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HOVI CMS')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        :root {
            --admin-bg: #f3f7fb;
            --admin-surface: #ffffff;
            --admin-surface-soft: #f8fbff;
            --admin-border: #dce5f0;
            --admin-text: #0f172a;
            --admin-muted: #64748b;
            --admin-primary: #2563eb;
            --admin-shadow: 0 14px 34px rgba(15, 23, 42, .08);
        }

        html,
        body {
            min-height: 100%;
        }

        body.admin-app {
            font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: var(--admin-text);
            background:
                radial-gradient(circle at 18% 4%, rgba(37, 99, 235, .14), transparent 44%),
                radial-gradient(circle at 86% 96%, rgba(14, 165, 233, .12), transparent 42%),
                var(--admin-bg);
            line-height: 1.55;
        }

        body.admin-app code {
            background: #eef2ff;
            color: #1e3a8a;
            border-radius: 8px;
            padding: .2rem .48rem;
            font-size: .84em;
            font-weight: 600;
        }

        .admin-shell {
            min-height: 100vh;
            display: flex;
            background: transparent;
        }

        .admin-sidebar {
            width: 304px;
            min-width: 304px;
            background: linear-gradient(180deg, #0b1220 0%, #101c33 100%);
            color: #e2e8f0;
            border-right: 1px solid rgba(148, 163, 184, .24);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            box-shadow: 12px 0 34px rgba(15, 23, 42, .18);
        }

        .admin-sidebar__brand {
            padding: 18px 18px 16px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }

        .admin-sidebar__brand a {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #fff;
            font-weight: 700;
            letter-spacing: .015em;
        }

        .admin-sidebar__brand-logo {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            font-size: .92rem;
            font-weight: 800;
            color: #fff;
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            box-shadow: 0 12px 22px rgba(14, 116, 144, .35);
        }

        .admin-sidebar__brand-text {
            display: grid;
            line-height: 1.12;
            gap: 2px;
        }

        .admin-sidebar__brand-text strong {
            font-size: 1rem;
            letter-spacing: .01em;
        }

        .admin-sidebar__brand-text small {
            color: #94a3b8;
            font-size: .72rem;
            font-weight: 500;
        }

        .admin-sidebar__group {
            padding: 14px 10px;
        }

        .admin-sidebar__label {
            color: #94a3b8;
            font-size: .72rem;
            letter-spacing: .08em;
            text-transform: uppercase;
            margin: 0 10px 8px;
            font-weight: 700;
        }

        .admin-sidebar__nav {
            display: grid;
            gap: 5px;
        }

        .admin-sidebar__nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 40px;
            border-radius: 12px;
            padding: 8px 12px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: .93rem;
            border: 1px solid transparent;
            transition: background-color .2s ease, color .2s ease, border-color .2s ease, transform .2s ease;
            font-weight: 500;
        }

        .admin-sidebar__icon {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            display: grid;
            place-items: center;
            font-size: .82rem;
            color: #bfdbfe;
            background: rgba(37, 99, 235, .2);
            flex: 0 0 auto;
        }

        .admin-sidebar__nav-link:hover {
            background: rgba(148, 163, 184, .16);
            color: #fff;
            border-color: rgba(148, 163, 184, .24);
            transform: translateX(2px);
        }

        .admin-sidebar__nav-link.active {
            background: rgba(37, 99, 235, .18);
            border-color: rgba(96, 165, 250, .5);
            color: #fff;
            box-shadow: inset 0 0 0 1px rgba(96, 165, 250, .22);
        }

        .admin-sidebar__nav-link.active .admin-sidebar__icon {
            color: #fff;
            background: linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%);
            box-shadow: 0 10px 20px rgba(14, 116, 144, .34);
        }

        .admin-sidebar__footer {
            margin-top: auto;
            padding: 14px 12px 16px;
            border-top: 1px solid rgba(148, 163, 184, 0.2);
            display: grid;
            gap: 10px;
        }

        .admin-sidebar::-webkit-scrollbar {
            width: 10px;
        }

        .admin-sidebar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, .32);
            border-radius: 999px;
        }

        .admin-content {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .admin-mobile-top {
            background: rgba(255, 255, 255, .86);
            border-bottom: 1px solid var(--admin-border);
            padding: 10px 14px;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .admin-toolbar {
            margin: 14px 18px 0;
            background: rgba(255, 255, 255, .8);
            border: 1px solid var(--admin-border);
            border-radius: 14px;
            padding: 10px 14px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, .05);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        .admin-toolbar__eyebrow {
            margin: 0;
            font-size: .72rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .08em;
            color: #64748b;
        }

        .admin-toolbar__title {
            margin: 2px 0 0;
            font-size: .94rem;
            color: #0f172a;
            font-weight: 700;
        }

        .admin-toolbar__user {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: .84rem;
            font-weight: 600;
            color: #1e293b;
            background: #f8fbff;
            border: 1px solid var(--admin-border);
            border-radius: 999px;
            padding: 7px 12px;
        }

        .admin-toolbar__avatar {
            width: 24px;
            height: 24px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            color: #fff;
            font-size: .78rem;
            background: linear-gradient(135deg, #3b82f6 0%, #0ea5e9 100%);
        }

        .admin-page {
            padding: 1.35rem 1.45rem 1.65rem;
        }

        .admin-page h1,
        .admin-page .h3 {
            letter-spacing: -.02em;
            font-weight: 700;
            color: var(--admin-text);
        }

        .admin-page .text-muted {
            color: var(--admin-muted) !important;
        }

        .card {
            border: 1px solid var(--admin-border) !important;
            border-radius: 16px;
            box-shadow: var(--admin-shadow);
            overflow: hidden;
            background: var(--admin-surface);
        }

        .card-header {
            border-bottom: 1px solid var(--admin-border);
            background: linear-gradient(180deg, #ffffff 0%, var(--admin-surface-soft) 100%) !important;
            color: #1e293b;
            font-weight: 700;
        }

        .card-body {
            background: var(--admin-surface);
        }

        .table-responsive {
            border-radius: 16px;
        }

        .table {
            --bs-table-bg: transparent;
            --bs-table-border-color: #e7edf5;
        }

        .table thead th {
            font-size: .73rem;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: #64748b;
            font-weight: 700;
            background: #f7fbff;
            border-bottom-width: 1px;
        }

        .table>:not(caption)>*>* {
            padding: .78rem .8rem;
            vertical-align: middle;
        }

        .table-hover>tbody>tr:hover>* {
            background: #f8fbff;
        }

        .badge {
            border-radius: 999px;
            font-weight: 700;
            letter-spacing: .01em;
            padding: .45em .64em;
        }

        .form-label {
            font-size: .86rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: .45rem;
        }

        .form-control,
        .form-select,
        .form-check-input {
            border-radius: 12px;
            border-color: #d5e0ec;
            box-shadow: none;
        }

        .form-control,
        .form-select {
            padding: .58rem .78rem;
            font-size: .94rem;
        }

        textarea.form-control {
            min-height: 108px;
        }

        .form-control:focus,
        .form-select:focus,
        .form-check-input:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .12);
        }

        .form-text {
            font-size: .78rem;
            color: #64748b;
        }

        .btn {
            border-radius: 12px;
            font-weight: 600;
            letter-spacing: .01em;
            box-shadow: none;
        }

        .btn-sm {
            border-radius: 10px;
        }

        .btn-dark {
            background: #0f172a;
            border-color: #0f172a;
        }

        .btn-dark:hover,
        .btn-dark:focus {
            background: #0b1220;
            border-color: #0b1220;
        }

        .btn-outline-dark {
            color: #1e293b;
            border-color: #c7d5e6;
        }

        .btn-outline-dark:hover,
        .btn-outline-dark:focus {
            background: #0f172a;
            border-color: #0f172a;
            color: #fff;
        }

        .alert {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 10px 24px rgba(15, 23, 42, .08);
        }

        .alert-success {
            color: #065f46;
            background: #ecfdf3;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            color: #7f1d1d;
            background: #fef2f2;
            border-left: 4px solid #ef4444;
        }

        .pagination {
            --bs-pagination-border-radius: 10px;
            --bs-pagination-color: #334155;
            --bs-pagination-active-bg: #2563eb;
            --bs-pagination-active-border-color: #2563eb;
            --bs-pagination-hover-color: #1d4ed8;
        }

        .pagination .page-link {
            border-color: #d9e4f1;
            margin-inline: 2px;
            border-radius: 10px !important;
            box-shadow: none;
        }

        .pagination .page-item.active .page-link {
            box-shadow: 0 8px 18px rgba(37, 99, 235, .28);
        }

        @media (max-width: 991.98px) {
            .admin-shell {
                display: block;
            }

            .admin-sidebar {
                width: 100%;
                min-width: 0;
                height: auto;
                position: relative;
                border-right: 0;
                border-bottom: 1px solid rgba(148, 163, 184, 0.2);
                box-shadow: 0 12px 24px rgba(15, 23, 42, .1);
            }

            .admin-page {
                padding: 1rem;
            }

            .admin-toolbar {
                display: none !important;
            }
        }
    </style>
    @stack('head')
</head>

<body class="admin-app">
    @auth
        <div class="admin-shell">
            <aside class="admin-sidebar">
                <div class="admin-sidebar__brand">
                    <a href="{{ route('admin.dashboard') }}">
                        <span class="admin-sidebar__brand-logo">HC</span>
                        <span class="admin-sidebar__brand-text">
                            <strong>HOVI CMS</strong>
                            <small>Content Management</small>
                        </span>
                    </a>
                </div>

                <div class="admin-sidebar__group">
                    <p class="admin-sidebar__label">Quản trị nội dung</p>
                    <nav class="admin-sidebar__nav">
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">
                            <span class="admin-sidebar__icon">📊</span>
                            <span>Tổng quan</span>
                        </a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}"
                            href="{{ route('admin.pages.index') }}">
                            <span class="admin-sidebar__icon">📄</span>
                            <span>Quản lý trang</span>
                        </a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"
                            href="{{ route('admin.projects.index') }}">
                            <span class="admin-sidebar__icon">🏗️</span>
                            <span>Quản trị dự án</span>
                        </a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}"
                            href="{{ route('admin.blogs.index') }}">
                            <span class="admin-sidebar__icon">📝</span>
                            <span>Quản lý Blog</span>
                        </a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}"
                            href="{{ route('admin.videos.index') }}">
                            <span class="admin-sidebar__icon">🎬</span>
                            <span>Quản lý Video</span>
                        </a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.home-content.*') ? 'active' : '' }}"
                            href="{{ route('admin.home-content.index') }}">
                            <span class="admin-sidebar__icon">🏠</span>
                            <span>Nội dung Trang chủ</span>
                        </a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.about-content.*') ? 'active' : '' }}"
                            href="{{ route('admin.about-content.edit') }}">
                            <span class="admin-sidebar__icon">🌿</span>
                            <span>Nội dung About Us</span>
                        </a>
                    </nav>
                </div>

                <div class="admin-sidebar__group pt-0">
                    <p class="admin-sidebar__label">Điều hướng website</p>
                    <nav class="admin-sidebar__nav">
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}"
                            href="{{ route('admin.menu-items.index') }}">
                            <span class="admin-sidebar__icon">🧭</span>
                            <span>Menu chính</span>
                        </a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                            href="{{ route('admin.settings.edit') }}">
                            <span class="admin-sidebar__icon">⚙️</span>
                            <span>SEO & Footer</span>
                        </a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}"
                            href="{{ route('admin.contact-messages.index') }}">
                            <span class="admin-sidebar__icon">📨</span>
                            <span>Liên hệ khách hàng</span>
                        </a>
                    </nav>
                </div>

                <div class="admin-sidebar__footer">
                    <a class="btn btn-sm btn-outline-light" href="{{ route('site.page') }}" target="_blank"
                        rel="noreferrer noopener">Xem website</a>
                    <form action="{{ route('admin.logout') }}" method="post">
                        @csrf
                        <button class="btn btn-sm btn-light w-100" type="submit">Đăng xuất</button>
                    </form>
                </div>
            </aside>

            <section class="admin-content">
                <header class="admin-mobile-top d-lg-none d-flex justify-content-between align-items-center">
                    <strong>HOVI CMS</strong>
                    <form action="{{ route('admin.logout') }}" method="post">
                        @csrf
                        <button class="btn btn-sm btn-outline-dark" type="submit">Đăng xuất</button>
                    </form>
                </header>

                <header class="admin-toolbar d-none d-lg-flex justify-content-between align-items-center">
                    <div>
                        <p class="admin-toolbar__eyebrow">HOVI Việt Nam</p>
                        <p class="admin-toolbar__title">Trang quản trị nội dung</p>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <a class="btn btn-sm btn-outline-dark" href="{{ route('site.page') }}" target="_blank"
                            rel="noreferrer noopener">Mở website</a>
                        <span class="admin-toolbar__user">
                            <span class="admin-toolbar__avatar">{{ strtoupper(substr((string) (auth()->user()->name ?? auth()->user()->email ?? 'A'), 0, 1)) }}</span>
                            <span>{{ auth()->user()->name ?? auth()->user()->email }}</span>
                        </span>
                    </div>
                </header>

                <main class="admin-page">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </section>
        </div>
    @else
        <main class="container py-4">
            @yield('content')
        </main>
    @endauth

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@7/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var richEditorSelector = 'textarea.js-rich-editor';
            var hasRichEditorField = document.querySelector(richEditorSelector);

            if (!hasRichEditorField || typeof window.tinymce === 'undefined') {
                return;
            }

            var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            var uploadUrl = @json(route('admin.editor.upload-image'));

            tinymce.init({
                selector: richEditorSelector,
                height: 420,
                menubar: 'file edit view insert format tools table help',
                branding: false,
                promotion: false,
                plugins: 'advlist autolink lists link image table code fullscreen preview wordcount charmap',
                toolbar: 'undo redo | styles fontsize | bold italic underline forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image table | code fullscreen preview',
                toolbar_mode: 'sliding',
                fontsize_formats: '12px 14px 16px 18px 20px 24px 28px 32px',
                block_formats: 'Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Blockquote=blockquote',
                image_title: true,
                image_caption: true,
                relative_urls: false,
                remove_script_host: false,
                convert_urls: false,
                content_style: `
                    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&display=swap');

                    @font-face {
                        font-family: 'SVN Aptima';
                        src: url('/theme/assets/SVN-Aptima.ttf') format('truetype');
                        font-weight: 400;
                        font-style: normal;
                    }

                    @font-face {
                        font-family: 'SVN Aptima';
                        src: url('/theme/assets/SVN-AptimaBold.ttf') format('truetype');
                        font-weight: 700;
                        font-style: normal;
                    }

                    body {
                        font-family: 'Open Sans', Arial, sans-serif;
                        font-size: 16px;
                        line-height: 1.75;
                        color: #111827;
                    }

                    h1, h2, h3, h4, h5, h6 {
                        font-family: 'SVN Aptima', 'Marcellus', 'Open Sans', serif;
                        line-height: 1.25;
                        font-weight: 400;
                        color: #111827;
                        margin: 1.15em 0 .65em;
                    }

                    p, li, blockquote {
                        font-family: 'Open Sans', Arial, sans-serif;
                        line-height: 1.8;
                    }

                    img {
                        max-width: 100%;
                        height: auto;
                        display: block;
                        margin: 14px auto;
                    }
                `,
                images_upload_handler: function(blobInfo, progress) {
                    return new Promise(function(resolve, reject) {
                        var formData = new FormData();
                        formData.append('image', blobInfo.blob(), blobInfo.filename());
                        formData.append('_token', csrfToken);

                        fetch(uploadUrl, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest',
                                },
                            })
                            .then(function(response) {
                                return response.json().then(function(payload) {
                                    return {
                                        ok: response.ok,
                                        status: response.status,
                                        payload: payload,
                                    };
                                });
                            })
                            .then(function(result) {
                                if (!result.ok || !result.payload || !result.payload.location) {
                                    reject((result.payload && result.payload.message) || 'Upload ảnh thất bại.');
                                    return;
                                }

                                resolve(result.payload.location);
                            })
                            .catch(function() {
                                reject('Không thể tải ảnh lên. Vui lòng kiểm tra kết nối và thử lại.');
                            });
                    });
                },
                setup: function(editor) {
                    editor.on('change input undo redo', function() {
                        editor.save();
                    });
                },
            });
        });
    </script>
    @stack('scripts')
</body>

</html>
