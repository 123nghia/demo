<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'HOVI CMS')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .admin-shell {
            min-height: 100vh;
            display: flex;
            background: #f8fafc;
        }

        .admin-sidebar {
            width: 280px;
            min-width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #111827 100%);
            color: #e2e8f0;
            border-right: 1px solid rgba(148, 163, 184, 0.22);
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .admin-sidebar__brand {
            padding: 16px 18px;
            border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        }

        .admin-sidebar__brand a {
            text-decoration: none;
            color: #fff;
            font-weight: 700;
            letter-spacing: .02em;
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
            gap: 4px;
        }

        .admin-sidebar__nav-link {
            display: flex;
            align-items: center;
            min-height: 40px;
            border-radius: 10px;
            padding: 8px 12px;
            color: #cbd5e1;
            text-decoration: none;
            font-size: .93rem;
            transition: background-color .2s ease, color .2s ease;
        }

        .admin-sidebar__nav-link:hover {
            background: rgba(148, 163, 184, .18);
            color: #fff;
        }

        .admin-sidebar__nav-link.active {
            background: #1d4ed8;
            color: #fff;
            box-shadow: 0 10px 20px rgba(29, 78, 216, .26);
        }

        .admin-sidebar__footer {
            margin-top: auto;
            padding: 14px 12px 16px;
            border-top: 1px solid rgba(148, 163, 184, 0.2);
            display: grid;
            gap: 10px;
        }

        .admin-content {
            flex: 1;
            min-width: 0;
        }

        .admin-mobile-top {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 10px 14px;
        }

        .admin-page {
            padding: 1.35rem;
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
            }

            .admin-page {
                padding: 1rem;
            }
        }
    </style>
    @stack('head')
</head>

<body class="bg-light">
    @auth
        <div class="admin-shell">
            <aside class="admin-sidebar">
                <div class="admin-sidebar__brand">
                    <a href="{{ route('admin.dashboard') }}">HOVI CMS</a>
                </div>

                <div class="admin-sidebar__group">
                    <p class="admin-sidebar__label">Quản trị nội dung</p>
                    <nav class="admin-sidebar__nav">
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">Tổng quan</a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}"
                            href="{{ route('admin.pages.index') }}">Quản lý trang</a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}"
                            href="{{ route('admin.projects.index') }}">Quản trị dự án</a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.blogs.*') ? 'active' : '' }}"
                            href="{{ route('admin.blogs.index') }}">Quản lý Blog</a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.videos.*') ? 'active' : '' }}"
                            href="{{ route('admin.videos.index') }}">Quản lý Video</a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.home-content.*') ? 'active' : '' }}"
                            href="{{ route('admin.home-content.index') }}">Nội dung Trang chủ</a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.about-content.*') ? 'active' : '' }}"
                            href="{{ route('admin.about-content.edit') }}">Nội dung About Us</a>
                    </nav>
                </div>

                <div class="admin-sidebar__group pt-0">
                    <p class="admin-sidebar__label">Điều hướng website</p>
                    <nav class="admin-sidebar__nav">
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.menu-items.*') ? 'active' : '' }}"
                            href="{{ route('admin.menu-items.index') }}">Menu chính</a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"
                            href="{{ route('admin.settings.edit') }}">SEO & Footer</a>
                        <a class="admin-sidebar__nav-link {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}"
                            href="{{ route('admin.contact-messages.index') }}">Liên hệ khách hàng</a>
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
