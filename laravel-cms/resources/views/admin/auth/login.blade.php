<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Đăng nhập quản trị | HOVI CMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        body {
            min-height: 100vh;
            margin: 0;
            font-family: 'Inter', 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background:
                radial-gradient(circle at 18% 8%, rgba(37, 99, 235, .24), transparent 38%),
                radial-gradient(circle at 86% 90%, rgba(14, 165, 233, .2), transparent 36%),
                linear-gradient(180deg, #0b1220 0%, #101c33 100%);
            color: #0f172a;
            display: grid;
            place-items: center;
            padding: 1rem;
        }

        .login-shell {
            width: 100%;
            max-width: 430px;
        }

        .login-card {
            border: 1px solid rgba(203, 213, 225, .44);
            border-radius: 20px;
            background: rgba(255, 255, 255, .96);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 24px 50px rgba(15, 23, 42, .28);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 56%, #0ea5e9 100%);
            color: #fff;
            padding: 18px 22px;
        }

        .login-logo {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            color: #fff;
            font-weight: 800;
            font-size: .92rem;
            background: rgba(255, 255, 255, .2);
            border: 1px solid rgba(255, 255, 255, .38);
        }

        .login-title {
            margin: 0;
            font-size: 1.15rem;
            font-weight: 700;
            letter-spacing: -.02em;
        }

        .login-subtitle {
            margin: 2px 0 0;
            color: rgba(255, 255, 255, .78);
            font-size: .85rem;
        }

        .login-card .card-body {
            padding: 1.25rem 1.25rem 1.35rem;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: .86rem;
        }

        .form-control {
            border-radius: 12px;
            border-color: #d5dfec;
            padding: .58rem .75rem;
            box-shadow: none;
        }

        .form-control:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 .2rem rgba(37, 99, 235, .12);
        }

        .form-check-label {
            font-size: .86rem;
            color: #475569;
        }

        .btn-dark {
            border-radius: 12px;
            background: #0f172a;
            border-color: #0f172a;
            font-weight: 600;
            letter-spacing: .01em;
            padding-block: .58rem;
        }

        .btn-dark:hover,
        .btn-dark:focus {
            background: #0b1220;
            border-color: #0b1220;
        }

        .alert {
            border: 0;
            border-radius: 12px;
            font-size: .86rem;
            margin-bottom: .9rem;
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
    </style>
</head>

<body>
    <div class="login-shell">
        <div class="login-card card">
            <div class="login-header d-flex align-items-center gap-3">
                <span class="login-logo">HC</span>
                <div>
                    <h1 class="login-title">Đăng nhập quản trị</h1>
                    <p class="login-subtitle">Hệ quản trị nội dung website HOVI Việt Nam</p>
                </div>
            </div>

            <div class="card-body">
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
</body>

</html>
