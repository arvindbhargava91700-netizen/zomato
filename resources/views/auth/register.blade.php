@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Create Account</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="index.html"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Signup</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- signup page start -->
    <section class="login-hero-section section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-6 col-md-10 m-auto">
                    <div class="login-data">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                {{-- Success Message --}}
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}

                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                                        </button>
                                    </div>
                                @endif
                                <form class="auth-form" method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <h2>Sign up</h2>

                                    <h5>
                                        or
                                        <a href="{{ route('login') }}">
                                            <span class="theme-color">login to your account</span>
                                        </a>
                                    </h5>

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
                                        CONTINUE
                                    </button>

                                    <p class="fw-normal content-color">
                                        By creating an account, I accept the
                                        <span class="fw-semibold">
                                            Terms & Conditions & Privacy Policy
                                        </span>
                                    </p>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- signup page end -->

@endsection