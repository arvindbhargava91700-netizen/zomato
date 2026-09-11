<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Zomato Clone</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-card {
            max-width: 420px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        .brand-title {
            color: #cb202d;
            font-weight: 700;
        }
        /* Proper Zomato button + spinner */
        .btn-zomato {
            background-color: #cb202d !important;
            border: none !important;
            color: #ffffff !important;
            border-radius: 10px !important;
            height: 46px !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            gap: 6px;
            box-shadow: 0 4px 14px rgba(203, 32, 45, .25) !important;
            transition: all .2s ease;
            letter-spacing: .3px;
        }
        .btn-zomato:hover,
        .btn-zomato:focus,
        .btn-zomato:active {
            background-color: #a81a25 !important;
            color: #ffffff !important;
            box-shadow: 0 6px 20px rgba(203, 32, 45, .35) !important;
            transform: translateY(-1px);
        }
        .btn-zomato:disabled {
            background-color: #a81a25 !important;
            color: #ffffff !important;
            opacity: .85;
            cursor: not-allowed;
            transform: none !important;
        }
        .btn-loading {
            display: inline-flex;
            align-items: center;
        }
        .btn-loading.d-none {
            display: none !important;
        }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card login-card p-4 mx-auto">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h2 class="brand-title h3 mb-1">Zomato Admin</h2>
                            <p class="text-muted small">Sign in to your administration panel</p>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('admin.login.submit') }}" method="POST" id="adminLoginForm">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label font-weight-bold">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autofocus placeholder="admin@admin.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required placeholder="••••••••">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label text-secondary" for="remember">Remember me</label>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-zomato py-2 fw-semibold" id="adminLoginBtn">
                                    <span class="btn-icon">Sign In</span>
                                    <span class="btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Signing in...
                                    </span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('adminLoginForm').addEventListener('submit', function () {
            var btn = document.getElementById('adminLoginBtn');
            if (btn.classList.contains('is-loading')) {
                return false;
            }
            btn.classList.add('is-loading');
            btn.disabled = true;
            btn.querySelector('.btn-icon').classList.add('d-none');
            btn.querySelector('.btn-loading').classList.remove('d-none');
        });
    </script>
</body>
</html>
