@extends('layouts.front.main')
@section('content')
    <style>
        /* ===========================================
           Become a Delivery Partner Page Styles
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
            background:
                radial-gradient(circle at 0% 0%, rgba(var(--theme-color), 0.08) 0%, rgba(var(--theme-color), 0) 40%),
                linear-gradient(135deg, rgba(var(--box-bg), 1), rgba(var(--white), 1));
        }

        .steps-wrap .step-card {
            position: relative;
            height: 100%;
            background: rgba(var(--white), 1);
            border: 1px solid rgba(var(--dark-text), 0.08);
            border-radius: 20px;
            padding: 32px 26px 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
            overflow: hidden;
        }

        .steps-wrap .step-card::after {
            content: "";
            position: absolute;
            top: 0;
            right: 0;
            width: 96px;
            height: 96px;
            border-radius: 0 0 0 96px;
            background: linear-gradient(135deg, transparent 50%, rgba(var(--theme-color), 0.09) 50%);
        }

        .steps-wrap .step-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 22px 48px rgba(var(--theme-color), 0.16);
            border-color: rgba(var(--theme-color), 0.35);
        }

        .steps-wrap .step-head {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 18px;
            position: relative;
            z-index: 1;
        }

        .steps-wrap .step-icon {
            width: 58px;
            height: 58px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 16px;
            font-size: 26px;
            color: #fff;
            background: linear-gradient(135deg, rgba(var(--theme-color), 1), rgba(var(--theme-color2), 1));
            box-shadow: 0 10px 22px rgba(var(--theme-color), 0.35);
            flex-shrink: 0;
        }

        .steps-wrap .step-tag {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            color: rgba(var(--theme-color2), 1);
            background: rgba(var(--theme-color), 0.1);
            border: 1px solid rgba(var(--theme-color), 0.25);
        }

        .steps-wrap .step-card h4 {
            font-size: 18px;
            font-weight: 600;
            color: rgba(var(--dark-text), 1);
            margin-bottom: 10px;
            position: relative;
            z-index: 1;
        }

        .steps-wrap .step-card p {
            color: rgba(var(--content-color), 1);
            font-size: 14px;
            line-height: 1.75;
            margin: 0;
            position: relative;
            z-index: 1;
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
            .steps-wrap .step-card { margin-bottom: 16px; }
            .register-panel { margin-bottom: 24px; }
        }
    </style>

    <!-- ================================================== -->
    <!-- Page head section start -->
    <!-- ================================================== -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Become a Delivery Partner</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Become a Delivery Partner</li>
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
                        <h2>Why Become a Delivery Partner?</h2>
                        <h6>
                            Earn money on your own schedule by delivering food to customers near you.
                        </h6>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-xxl-3 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon"><i class="ri-time-line"></i></div>
                        <h4>Work on Your Schedule</h4>
                        <p>Choose your own hours and deliver whenever it suits you — mornings, evenings or weekends.</p>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon"><i class="ri-money-rupee-circle-line"></i></div>
                        <h4>Earn on Every Delivery</h4>
                        <p>Get paid per delivery plus extra earnings on Cash on Delivery settlements.</p>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon"><i class="ri-bank-card-line"></i></div>
                        <h4>Weekly Payouts</h4>
                        <p>Your earnings are paid out directly to your bank account, fast and hassle-free.</p>
                    </div>
                </div>
                <div class="col-xxl-3 col-md-6">
                    <div class="benefit-card">
                        <div class="benefit-icon"><i class="ri-smartphone-line"></i></div>
                        <h4>Simple &amp; Easy App</h4>
                        <p>A user-friendly dashboard to accept orders, track deliveries and view your earnings anytime.</p>
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
                        <h2>How to Get Started</h2>
                        <h6>
                            Follow these simple steps to start delivering with us.
                        </h6>
                    </div>
                </div>
            </div>

            <div class="row steps-wrap g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-head">
                            <div class="step-icon"><i class="ri-user-2-line"></i></div>
                            <span class="step-tag"><i class="ri-hashtag"></i> Step 1</span>
                        </div>
                        <h4>Personal Details</h4>
                        <p>Fill in your basic details — name, age, address and city. Registration only requires your basic information.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-head">
                            <div class="step-icon"><i class="ri-file-copy-2-line"></i></div>
                            <span class="step-tag"><i class="ri-hashtag"></i> Step 2</span>
                        </div>
                        <h4>Document Upload</h4>
                        <p>Upload your Aadhar card (front and back), PAN card, driving license (if you will be using a vehicle), bank account details (passbook or cancelled cheque) and a passport-size photo.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-head">
                            <div class="step-icon"><i class="ri-bike-line"></i></div>
                            <span class="step-tag"><i class="ri-hashtag"></i> Step 3</span>
                        </div>
                        <h4>Vehicle Details</h4>
                        <p>If you are using a bike, provide the RC (registration certificate) and your vehicle number details.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-head">
                            <div class="step-icon"><i class="ri-shield-check-line"></i></div>
                            <span class="step-tag"><i class="ri-hashtag"></i> Step 4</span>
                        </div>
                        <h4>Verification &amp; Background Check</h4>
                        <p>The company will verify your documents; occasionally a local verification is also carried out (this may take 2–5 days).</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-head">
                            <div class="step-icon"><i class="ri-graduation-cap-line"></i></div>
                            <span class="step-tag"><i class="ri-hashtag"></i> Step 5</span>
                        </div>
                        <h4>Training &amp; Orientation</h4>
                        <p>A short training session is provided — you will be guided on how to use the app and how the delivery process works.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="step-card">
                        <div class="step-head">
                            <div class="step-icon"><i class="ri-rocket-2-line"></i></div>
                            <span class="step-tag"><i class="ri-hashtag"></i> Step 6</span>
                        </div>
                        <h4>Approval &amp; Start</h4>
                        <p>Once approved, you can log in and start accepting orders and deliveries right away.</p>
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
                        <div class="panel-icon"><i class="ri-bike-line"></i></div>
                        <h2>Perks of being a Delivery Partner</h2>
                        <p>
                            Join our delivery partner network and enjoy benefits designed
                            to help you earn more and grow every single day.
                        </p>
                        <ul>
                            <li><i class="ri-checkbox-circle-line"></i> Flexible working hours — deliver when it suits you</li>
                            <li><i class="ri-checkbox-circle-line"></i> Earn per delivery plus COD settlement earnings</li>
                            <li><i class="ri-checkbox-circle-line"></i> Weekly payouts directly to your bank account</li>
                            <li><i class="ri-checkbox-circle-line"></i> Easy-to-use dashboard for orders &amp; earnings</li>
                            <li><i class="ri-checkbox-circle-line"></i> Dedicated support team for all your queries</li>
                            <li><i class="ri-checkbox-circle-line"></i> Grow your income with more deliveries every day</li>
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

                            <h3>Register as a Delivery Partner</h3>
                            <p class="form-sub">Fill in your details to create your delivery partner account.</p>

                            <form class="auth-form" method="POST" action="{{ route('delivery-partner.register.submit') }}">
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
                                    <i class="ri-bike-line me-2"></i>BECOME A DELIVERY PARTNER
                                </button>

                                <p class="terms-note">
                                    By registering, you agree to deliver food on our platform and accept the
                                    <span class="fw-semibold">Terms &amp; Conditions &amp; Privacy Policy</span>.
                                </p>
                            </form>

                            <a class="login-link" href="{{ route('delivery-partner.login') }}">
                                Already a delivery partner? <span class="theme-color fw-semibold">Login here</span>
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
                    <h2>Ready to start delivering?</h2>
                    <p>Join {{ $companyName }} today and start earning with every delivery you make.</p>
                </div>
                <a href="#register-form" class="btn btn-white mt-0">
                    <i class="ri-arrow-right-up-line me-2"></i>Get Started Now
                </a>
            </div>
        </div>
    </section>
    <!-- Final CTA section end -->
@endsection