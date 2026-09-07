<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Zomato Partner</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .card-custom {
            max-width: 440px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.08);
        }
        .brand-title {
            color: #cb202d;
            font-weight: 700;
        }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100 py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card card-custom p-4 mx-auto">
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <h2 class="brand-title h3 mb-1">Forgot Password</h2>
                            <p class="text-muted small">Enter your email address to receive password reset instructions.</p>
                        </div>

                        <form action="#" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label font-weight-bold">Registered Email</label>
                                <input type="email" name="email" id="email" class="form-control" required placeholder="owner@restaurant.com">
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-danger py-2 fw-semibold" style="background-color: #cb202d; border: none;">Send Password Reset Link</button>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('restaurant.login') }}" class="text-decoration-none small text-secondary">Back to Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
