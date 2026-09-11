@extends('layouts.front.main')
@section('content')


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
                        <p>List your restaurant and full menu on our platform and start receiving online orders instantly â€” no upfront cost.</p>
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
                        <p>Track earnings, COD settlements and payouts from a simple dashboard â€” anytime, anywhere.</p>
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
                                    <i class="ri-eye-line toggle-password"></i>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-input">
                                    <input type="password" name="password_confirmation" class="form-control"
                                        placeholder="Confirm your password" required autocomplete="new-password">
                                    <i class="ri-lock-password-line"></i>
                                    <i class="ri-eye-line toggle-password"></i>
                                </div>

                                <button type="submit" class="btn theme-btn submit-btn w-100 rounded-2">
                                    <span class="btn-icon">
                                        <i class="ri-store-2-line me-2"></i>BECOME A VENDOR
                                    </span>
                                    <span class="btn-loading d-none">
                                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                        Submitting...
                                    </span>
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

@push('scripts')
    <script>
        $(function () {
            $('.toggle-password').on('click', function () {
                var $toggle = $(this);
                var $input = $toggle.closest('.form-input').find('input');
                var show = $input.attr('type') === 'password';
                $input.attr('type', show ? 'text' : 'password');
                $toggle.toggleClass('ri-eye-line ri-eye-off-line');
            });

            $('.auth-form').on('submit', function () {
                var $btn = $(this).find('.submit-btn');
                if ($btn.hasClass('is-loading')) {
                    return false;
                }
                $btn.addClass('is-loading').prop('disabled', true);
                $btn.find('.btn-icon').addClass('d-none');
                $btn.find('.btn-loading').removeClass('d-none');
            });
        });
    </script>
@endpush