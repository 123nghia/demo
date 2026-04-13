<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập quản trị | HOVI CMS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <h1 class="h4 mb-3">Đăng nhập quản trị</h1>
                        <p class="text-muted small mb-4">Hệ quản trị nội dung website HOVI Việt Nam.</p>

                        @if (session('success'))
                            <div class="alert alert-success py-2">{{ session('success') }}</div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger py-2">
                                <ul class="mb-0 small">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.login.submit') }}" method="post" class="d-grid gap-3">
                            @csrf
                            <div>
                                <label for="email" class="form-label">Email</label>
                                <input class="form-control" id="email" type="email" name="email"
                                    value="{{ old('email') }}" required autofocus>
                            </div>

                            <div>
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input class="form-control" id="password" type="password" name="password" required>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="remember" name="remember">
                                <label class="form-check-label" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>

                            <button class="btn btn-dark" type="submit">Đăng nhập</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
