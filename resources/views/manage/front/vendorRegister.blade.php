@extends('layouts.front.main')
@section('content')
    <style>
        /* ===========================================
           Become a Vendor Page Styles
           =========================================== */
        /* Benefits */
        .vendor-benefits .benefit-card {
            height: 100%;
            padding: 34px 28px;
            border-radius: 20px;
            border: 1px solid rgba(var(--dark-text), 0.08);
            background: rgba(var(--white), 1);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .vendor-benefits .benefit-card::before {
            content: "";
            position: absolute;
            inset: 0 auto 0 0;
            width: 4px;
            background: linear-gradient(to bottom, rgba(var(--theme-color), 1), rgba(var(--theme-color2), 1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .vendor-benefits .benefit-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 45px rgba(var(--theme-color), 0.16);
            border-color: rgba(var(--theme-color), 0.35);
        }

        .vendor-benefits .benefit-card:hover::before { opacity: 1; }

        .vendor-benefits .benefit-icon {
            width: 62px;
            height: 62px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            font-size: 28px;
            color: #fff;
            background: linear-gradient(135deg, rgba(var(--theme-color), 1), rgba(var(--theme-color2), 1));
            box-shadow: 0 10px 22px rgba(var(--theme-color), 0.35);
            margin-bottom: 22px;
        }

        .vendor-benefits .benefit-card h4 {
            font-size: 18px;
            font-weight: 600;
            color: rgba(var(--dark-text), 1);
            margin-bottom: 10px;
        }

        .vendor-benefits .benefit-card p {
            color: rgba(var(--content-color), 1);
            font-size: 14px;
            line-height: 1.75;
            margin: 0;
        }

        /* How it works */
        .how-it-works {
            background: linear-gradient(135deg, rgba(var(--box-bg), 1), rgba(var(--white), 1));
        }

        .steps-wrap {
            position: relative;
        }

        .steps-wrap::before {
            content: "";
            position: absolute;
            top: 34px;
            left: 8%;
            right: 8%;
            height: 2px;
            background: linear-gradient(to right, rgba(var(--theme-color), 0.6), rgba(var(--theme-color2), 0.6));
            border-radius: 4px;
        }

        .steps-wrap .step-box {
            position: relative;
            text-align: center;
            padding: 0 14px;
        }

        .steps-wrap .step-box .step-num {
            position: relative;
            z-index: 1;
            width: 68px;
            height: 68px;
            margin: 0 auto 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-size: 22px;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(135deg, rgba(var(--theme-color), 1), rgba(var(--theme-color2), 1));
            box-shadow: 0 10px 24px rgba(var(--theme-color), 0.35);
            border: 5px solid rgba(var(--white), 1);
        }

        .steps-wrap .step-box h4 {
            font-size: 17px;
            font-weight: 600;
            color: rgba(var(--dark-text), 1);
            margin-bottom: 8px;
        }

        .steps-wrap .step-box p {
            color: rgba(var(--content-color), 1);
            font-size: 14px;
            line-height: 1.7;
            margin: 0;
        }

        /* Register section */
        .vendor-register-section {
            background:
                radial-gradient(circle at 100% 0%, rgba(var(--theme-color), 0.1) 0%, rgba(var(--theme-color), 0) 45%),
                rgba(var(--box-bg), 1);
        }

        .register-panel {
            height: 100%;
            border-radius: 24px;
            padding: 44px 40px;
            color: #fff;
            background: linear-gradient(135deg, rgba(var(--theme-color), 1), rgba(var(--theme-color2), 1));
            box-shadow: 0 24px 60px rgba(var(--theme-color), 0.35);
            position: relative;
            overflow: hidden;
        }

        .register-panel::before,
        .register-panel::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .register-panel::before {
            width: 200px;
            height: 200px;
            top: -70px;
            right: -70px;
        }

        .register-panel::after {
            width: 260px;
            height: 260px;
            bottom: -110px;
            left: -90px;
        }

        .register-panel .panel-icon {
            width: 56px;
            height: 56px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 26px;
            background: rgba(255, 255, 255, 0.18);
            margin-bottom: 20px;
        }

        .register-panel h2 {
            font-size: calc(24px + 8 * (100vw - 320px) / 1600);
            font-weight: 700;
            margin-bottom: 12px;
        }

        .register-panel > p {
            opacity: 0.92;
            font-size: 15px;
            line-height: 1.7;
            margin-bottom: 26px;
        }

        .register-panel ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .register-panel ul li {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 11px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.18);
            font-size: 14.5px;
        }

        .register-panel ul li i {
            margin-top: 3px;
            font-size: 18px;
        }

        .vendor-form-card {
            height: 100%;
            border: none;
            border-radius: 24px;
            background: rgba(var(--white), 1);
            box-shadow: 0 18px 50px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .vendor-form-card .card-body {
            padding: 40px 38px;
        }

        .vendor-form-card h3 {
            font-size: 22px;
            font-weight: 700;
            color: rgba(var(--dark-text), 1);
            margin-bottom: 6px;
        }

        .vendor-form-card .form-sub {
            color: rgba(var(--content-color), 1);
            font-size: 14px;
            margin-bottom: 26px;
        }

        .vendor-form-card .form-input input {
            height: 52px;
            font-size: 14px;
        }

        .vendor-form-card .theme-btn {
            height: 52px;
            font-weight: 600;
            letter-spacing: 0.4px;
        }

        .vendor-form-card .terms-note {
            font-size: 12.5px;
            color: rgba(var(--content-color), 1);
            margin: 18px 0 0;
            text-align: center;
        }

        .vendor-form-card .login-link {
            display: block;
            text-align: center;
            margin-top: 14px;
            font-size: 14px;
            color: rgba(var(--content-color), 1);
        }

        /* Final CTA strip */
        .vendor-cta {
            background:
                linear-gradient(120deg, rgba(var(--theme-color), 1), rgba(var(--theme-color2), 1));
            border-radius: 24px;
            padding: 48px 40px;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .vendor-cta::before {
            content: "";
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            top: -120px;
            right: -80px;
        }

        .vendor-cta h2 {
            font-size: calc(24px + 10 * (100vw - 320px) / 1600);
            font-weight: 700;
            margin-bottom: 8px;
        }

        .vendor-cta p {
            opacity: 0.92;
            font-size: 15px;
            margin: 0;
        }

        .vendor-cta .btn-white {
            background: #fff;
            color: rgba(var(--theme-color2), 1);
            font-weight: 600;
            padding: 12px 30px;
            border-radius: 12px;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .vendor-cta .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 26px rgba(0, 0, 0, 0.2);
        }

        /* Dark mode adjustments */
        body.dark .vendor-benefits .benefit-card,
        body.dark .vendor-form-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        body.dark .vendor-benefits .benefit-card:hover {
            box-shadow: 0 20px 45px rgba(0, 0, 0, 0.45);
        }

        @media (max-width: 991px) {
            .steps-wrap::before { display: none; }
            .steps-wrap .step-box { margin-bottom: 30px; }
            .register-panel { margin-bottom: 24px; }
        }
    </style>

    <!-- ================================================== -->
    <!-- Page head section start -->
    <!-- ================================================== -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Become a Vendor</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Become a Vendor</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- Page head section end -->

    <!-- ================================================== -->
    <!-- Benefits section start -->
    <!-- ================================================== -->
    <section class="vendor-benefits section-b-space">
        <div class="container">
            <div class="title animated-title">
                <div class="loader-line"></div>
                <div class="d-flex align-items-center justify-content-between flex-wrap w-100">
                    <div>
                        <h2>Why Partner With Us?</h2>
                        <h6>
                            Everything you need to grow your food business, all in one place.
                        </h6>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-xxl-3 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon"><i class="ri-restaurant-2-line"></i></div>
                        <h4>Own a Restaurant</h4>
                        <p>List your restaurant and full menu on our platform and start receiving online orders instantly — no upfront cost.</p>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon"><i class="ri-line-chart-line"></i></div>
                        <h4>Boost Your Sales</h4>
                        <p>Reach a wider audience with smart tools, promotions and dining offers that bring more customers to your door.</p>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon"><i class="ri-truck-line"></i></div>
                        <h4>Delivery Network</h4>
                        <p>Our trusted delivery partners ensure your food reaches customers fresh, fast and right on time.</p>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon"><i class="ri-wallet-3-line"></i></div>
                        <h4>Easy Earnings</h4>
                        <p>Track earnings, COD settlements and payouts from a simple dashboard — anytime, anywhere.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Benefits section end -->

    <!-- ================================================== -->
    <!-- How it works section start -->
    <!-- ================================================== -->
    <section class="how-it-works section-b-space">
        <div class="container">
            <div class="title animated-title">
                <div class="loader-line"></div>
                <div class="d-flex align-items-center justify-content-between flex-wrap w-100">
                    <div>
                        <h2>How It Works</h2>
                        <h6>
                            Get your restaurant online in three simple steps.
                        </h6>
                    </div>
                </div>
            </div>

            <div class="row steps-wrap justify-content-center">
                <div class="col-lg-4 col-md-6">
                    <div class="step-box">
                        <div class="step-num">1</div>
                        <h4>Create Your Account</h4>
                        <p>Sign up with your name, email and password using the form below. It takes less than a minute.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="step-box">
                        <div class="step-num">2</div>
                        <h4>Set Up Your Restaurant</h4>
                        <p>Add your restaurant details, menu, prices and photos from your owner dashboard.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="step-box">
                        <div class="step-num">3</div>
                        <h4>Start Receiving Orders</h4>
                        <p>Go live on the platform and start receiving orders, managing deliveries and tracking earnings.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- How it works section end -->

    <!-- ================================================== -->
    <!-- Registration section start -->
    <!-- ================================================== -->
    <section id="register-form" class="vendor-register-section section-b-space">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <div class="col-xl-6">
                    <div class="register-panel">
                        <div class="panel-icon"><i class="ri-rocket-2-line"></i></div>
                        <h2>Everything you need to succeed</h2>
                        <p>
                            As a Zomo vendor you get access to powerful tools built to help
                            your restaurant grow and run smoothly every single day.
                        </p>
                        <ul>
                            <li><i class="ri-checkbox-circle-line"></i> Your own restaurant page with custom branding</li>
                            <li><i class="ri-checkbox-circle-line"></i> Instant order notifications &amp; live order tracking</li>
                            <li><i class="ri-checkbox-circle-line"></i> Menu &amp; pricing management with food variants</li>
                            <li><i class="ri-checkbox-circle-line"></i> Dining offers &amp; table bookings to boost walk-ins</li>
                            <li><i class="ri-checkbox-circle-line"></i> Transparent earnings, commissions &amp; COD settlements</li>
                            <li><i class="ri-checkbox-circle-line"></i> Dedicated delivery partner network for your orders</li>
                        </ul>
                    </div>
                </div>

                <div class="col-xl-6">
                    <div class="card vendor-form-card">
                        <div class="card-body">
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <h3>Register your Restaurant</h3>
                            <p class="form-sub">Fill in your details to create your vendor account.</p>

                            <form class="auth-form" method="POST" action="{{ route('vendor.register.submit') }}">
                                @csrf

                                <div class="form-input">
                                    <input type="text" name="name" value="{{ old('name') }}"
                                        class="form-control @error('name') is-invalid @enderror"
                                        placeholder="Enter your name" required autocomplete="name">
                                    <i class="ri-user-3-line"></i>
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-input">
                                    <input type="email" name="email" value="{{ old('email') }}"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Enter your email" required autocomplete="email">
                                    <i class="ri-mail-line"></i>
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-input">
                                    <input type="password" name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        placeholder="Enter your password" required autocomplete="new-password">
                                    <i class="ri-lock-password-line"></i>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-input">
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Confirm your password" required autocomplete="new-password">
                                    <i class="ri-lock-password-line"></i>
                                </div>

                                <button type="submit" class="btn theme-btn submit-btn w-100 rounded-2">
                                    <i class="ri-store-2-line me-2"></i>BECOME A VENDOR
                                </button>

                                <p class="terms-note">
                                    By registering, you agree to list your restaurant on our platform and accept the
                                    <span class="fw-semibold">Terms &amp; Conditions &amp; Privacy Policy</span>.
                                </p>
                            </form>

                            <a class="login-link" href="{{ route('restaurant.login') }}">
                                Already a vendor? <span class="theme-color fw-semibold">Login here</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Registration section end -->

    <!-- ================================================== -->
    <!-- Final CTA section start -->
    <!-- ================================================== -->
    <section class="section-b-space">
        <div class="container">
            <div class="vendor-cta d-flex align-items-center justify-content-between flex-wrap gap-4">
                <div class="position-relative">
                    <h2>Ready to grow your restaurant business?</h2>
                    <p>Join Zomo today and get your restaurant in front of thousands of hungry customers.</p>
                </div>
                <a href="#register-form" class="btn btn-white mt-0">
                    <i class="ri-arrow-right-up-line me-2"></i>Get Started Now
                </a>
            </div>
        </div>
    </section>
    <!-- Final CTA section end -->
@endsection