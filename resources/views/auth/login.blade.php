@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Login</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="index.html"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Login</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- signin page start -->
    <section class="login-hero-section section-b-space">
        <div class="container">
            <div class="row">
                <div class="col-xl-5 col-lg-6 col-md-10 m-auto">
                    
                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ $errors->first() }}
                        </div>
                    @endif
                    <div class="login-data">

  

            <form method="POST" action="{{ route('login') }}" class="auth-form">
                @csrf
                            <h2>Login </h2>
                            <h5>
                                or
                                <a href="{{route('register')}}"><span class="theme-color">create an a account</span></a>
                            </h5>
                            <div class="form-input">
                                <input type="tel" class="form-control  @error('email') is-invalid @enderror"  name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                                <i class="ri-mail-line"></i>
                                      @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                            </div>
                            <div class="form-input">
                                <input type="password" id="password-field" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Enter your password">
                                <i class="ri-lock-password-line"></i>
                                <button type="button" id="toggle-password" class="toggle-password" tabindex="-1" aria-label="Toggle password visibility">
                                    <i class="ri-eye-off-line" id="eye-icon"></i>
                                </button>
                                                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                            </div>
                            <button  type="submit"  class="btn btn-login theme-btn submit-btn w-100 rounded-2">CONTINUE</button>
                            <p class="fw-normal content-color">
                                By creating an account, I accept the
                                <span class="fw-semibold">
                                    Terms & Conditions & Privacy Policy</span>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- signin page end -->

    <script>
        document.getElementById('toggle-password').addEventListener('click', function () {
            const input = document.getElementById('password-field');
            const icon = document.getElementById('eye-icon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('ri-eye-off-line');
                icon.classList.add('ri-eye-line');
            } else {
                input.type = 'password';
                icon.classList.remove('ri-eye-line');
                icon.classList.add('ri-eye-off-line');
            }
        });
    </script>

@endsection