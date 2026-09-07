@extends('layouts.front.main')
@section('title', 'Contact Us - ' . ($companyName ?? 'Food Management'))

@section('content')
    <!-- Page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Contact Us</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line me-1"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Contact Us</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- Page head section end -->

    <!-- Contact section starts -->
    <section class="section-b-space contact-page-wrap">
        <div class="container">
            <div class="title animated-title">
                <div class="loader-line"></div>
                <div class="d-flex align-items-center justify-content-between flex-wrap w-100">
                    <div>
                        <h2>Get in Touch with Our Team</h2>
                        <h6>
                            Have a question, feedback on your food order, or interested in partnering? We'd love to hear from you.
                        </h6>
                    </div>
                </div>
            </div>

            <!-- Contact Detail Cards -->
            <div class="contact-detail">
                <div class="row g-4">
                    <!-- Phone Support -->
                    <div class="col-xxl-3 col-md-6">
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactInfo['phone'] ?? '+18004567890') }}" class="contact-detail-box text-decoration-none h-100">
                            <div class="contact-icon">
                                <i class="ri-phone-fill"></i>
                            </div>
                            <div>
                                <div class="contact-detail-title">
                                    <h4>Call Us</h4>
                                </div>
                                <div class="contact-detail-contain">
                                    <p class="mb-0 text-dark fw-semibold">{{ $contactInfo['phone'] ?? '+1 (800) 456-7890' }}</p>
                                    <span class="fs-12 text-muted">24/7 Helpline Support</span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Email Support -->
                    <div class="col-xxl-3 col-md-6">
                        <a href="mailto:{{ $contactInfo['email'] ?? 'support@foodexpress.com' }}" class="contact-detail-box text-decoration-none h-100">
                            <div class="contact-icon">
                                <i class="ri-mail-open-fill"></i>
                            </div>
                            <div>
                                <div class="contact-detail-title">
                                    <h4>Email Support</h4>
                                </div>
                                <div class="contact-detail-contain">
                                    <p class="mb-0 text-dark fw-semibold text-break">{{ $contactInfo['email'] ?? 'support@foodexpress.com' }}</p>
                                    <span class="fs-12 text-muted">Typical response within 2 hours</span>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Headquarters Office -->
                    <div class="col-xxl-3 col-md-6">
                        <div class="contact-detail-box h-100">
                            <div class="contact-icon">
                                <i class="ri-map-pin-fill"></i>
                            </div>
                            <div>
                                <div class="contact-detail-title">
                                    <h4>Headquarters</h4>
                                </div>
                                <div class="contact-detail-contain">
                                    <p class="mb-0 text-dark fw-semibold">{{ $contactInfo['headquarters'] ?? '742 Gourmet Plaza, NY 10001' }}</p>
                                    <span class="fs-12 text-muted">Main Operations & Culinary HQ</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Operating Hours -->
                    <div class="col-xxl-3 col-md-6">
                        <div class="contact-detail-box h-100">
                            <div class="contact-icon">
                                <i class="ri-time-fill"></i>
                            </div>
                            <div>
                                <div class="contact-detail-title">
                                    <h4>Service Hours</h4>
                                </div>
                                <div class="contact-detail-contain">
                                    <p class="mb-0 text-dark fw-semibold">{{ $contactInfo['hours'] ?? 'Mon - Sun: 08:00 AM - 11:00 PM' }}</p>
                                    <span class="fs-12 text-muted">Online Deliveries & Inquiries</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form and Map Container -->
            <div class="row g-4 mt-2">
                <!-- Contact Form -->
                <div class="col-xl-8">
                    <div class="contact-form p-4 p-sm-5 rounded-4 border bg-white shadow-sm">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4 pb-2 border-bottom">
                            <div>
                                <h3 class="fw-bold text-dark mb-1 d-flex align-items-center gap-2">
                                    <i class="ri-mail-send-line text-primary"></i> Send Us a Message
                                </h3>
                                <p class="text-muted fs-13 mb-0">Fill in the form below and we will respond as soon as possible.</p>
                            </div>
                            @auth
                                <span class="badge bg-soft-success text-success px-3 py-1 rounded-pill fs-12 fw-semibold">
                                    <i class="ri-user-smile-line me-1"></i> Logged in as {{ auth()->user()->name }}
                                </span>
                            @endauth
                        </div>

                        <!-- Success Alert -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show rounded-3 mb-4 shadow-sm border-0 d-flex align-items-center gap-2" role="alert">
                                <i class="ri-checkbox-circle-fill fs-20 text-success flex-shrink-0"></i>
                                <div class="fs-14">{{ session('success') }}</div>
                                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Validation Errors Alert -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-4 shadow-sm border-0" role="alert">
                                <div class="fw-bold mb-1 d-flex align-items-center gap-1">
                                    <i class="ri-error-warning-fill text-danger fs-18"></i> Please check the form errors:
                                </div>
                                <ul class="mb-0 ps-3 fs-13">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.submit') }}" method="POST" class="row g-3 g-sm-4" id="contactUsForm">
                            @csrf

                            @php
                                $userName = auth()->check() ? auth()->user()->name : '';
                                $nameParts = explode(' ', $userName, 2);
                                $defaultFirstName = $nameParts[0] ?? '';
                                $defaultLastName = $nameParts[1] ?? '';
                                $defaultEmail = auth()->check() ? auth()->user()->email : '';
                                $defaultPhone = auth()->check() ? (auth()->user()->phone ?? auth()->user()->mobile) : '';
                            @endphp

                            <!-- First Name -->
                            <div class="col-md-6">
                                <label for="inputFirstname" class="form-label fw-semibold text-dark fs-14">
                                    First Name <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="ri-user-line"></i></span>
                                    <input type="text" name="first_name" class="form-control border-start-0 ps-0 @error('first_name') is-invalid @enderror"
                                           id="inputFirstname" placeholder="E.g. Rahul"
                                           value="{{ old('first_name', $defaultFirstName) }}" required>
                                </div>
                                @error('first_name')
                                    <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Last Name -->
                            <div class="col-md-6">
                                <label for="inputLastname" class="form-label fw-semibold text-dark fs-14">Last Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="ri-user-3-line"></i></span>
                                    <input type="text" name="last_name" class="form-control border-start-0 ps-0 @error('last_name') is-invalid @enderror"
                                           id="inputLastname" placeholder="E.g. Sharma"
                                           value="{{ old('last_name', $defaultLastName) }}">
                                </div>
                                @error('last_name')
                                    <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="col-md-6">
                                <label for="inputEmail" class="form-label fw-semibold text-dark fs-14">
                                    Email Address <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="ri-mail-line"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                           id="inputEmail" placeholder="rahul@example.com"
                                           value="{{ old('email', $defaultEmail) }}" required>
                                </div>
                                @error('email')
                                    <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-6">
                                <label for="inputPhone" class="form-label fw-semibold text-dark fs-14">Phone Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="ri-phone-line"></i></span>
                                    <input type="tel" name="phone" class="form-control border-start-0 ps-0 @error('phone') is-invalid @enderror"
                                           id="inputPhone" placeholder="+91 98765 43210"
                                           value="{{ old('phone', $defaultPhone) }}">
                                </div>
                                @error('phone')
                                    <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Subject / Purpose -->
                            <div class="col-12">
                                <label for="inputSubject" class="form-label fw-semibold text-dark fs-14">Topic / Subject</label>
                                <select name="subject" id="inputSubject" class="form-select @error('subject') is-invalid @enderror">
                                    <option value="General Inquiry" {{ old('subject') == 'General Inquiry' ? 'selected' : '' }}>General Inquiry & Feedback</option>
                                    <option value="Order & Delivery Assistance" {{ old('subject') == 'Order & Delivery Assistance' ? 'selected' : '' }}>Order & Delivery Assistance</option>
                                    <option value="Restaurant Partnership" {{ old('subject') == 'Restaurant Partnership' ? 'selected' : '' }}>Restaurant Listing & Partnership</option>
                                    <option value="Technical Issue" {{ old('subject') == 'Technical Issue' ? 'selected' : '' }}>Technical Support / App Issue</option>
                                    <option value="Catering & Bulk Orders" {{ old('subject') == 'Catering & Bulk Orders' ? 'selected' : '' }}>Catering & Bulk Orders</option>
                                </select>
                                @error('subject')
                                    <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Message Body -->
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label for="inputText" class="form-label fw-semibold text-dark fs-14 mb-1">
                                        How Can We Help You? <span class="text-danger">*</span>
                                    </label>
                                    <span class="fs-12 text-muted" id="contactCharCount">0 / 3000 chars</span>
                                </div>
                                <textarea name="message" class="form-control @error('message') is-invalid @enderror"
                                          id="inputText" rows="5"
                                          placeholder="Please describe your query, food order details, or how we can assist you..."
                                          maxlength="3000"
                                          oninput="document.getElementById('contactCharCount').textContent = this.value.length + ' / 3000 chars'" required>{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="text-danger fs-12 mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-12">
                                <div class="buttons d-flex align-items-center justify-content-end gap-3 pt-2">
                                    <button type="reset" class="btn gray-btn mt-0 px-4">Reset</button>
                                    <button type="submit" class="btn theme-btn mt-0 px-4 d-flex align-items-center gap-2" id="submitContactBtn">
                                        <span>Send Message</span>
                                        <i class="ri-send-plane-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Google Map & Quick Info Box -->
                <div class="col-xl-4">
                    <div class="h-100 d-flex flex-column gap-3">
                        <div class="rounded-4 overflow-hidden shadow-sm border flex-grow-1" style="min-height: 380px;">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3332.2625191604434!2d-81.27475172358754!3d33.364211773423776!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88f91bc85b065379%3A0x2d14689bf5c52e3d!2s93%20Songbird%20Cir%2C%20Blackville%2C%20SC%2029817%2C%20USA!5e0!3m2!1sen!2sin!4v1690353019073!5m2!1sen!2sin"
                                width="100%" height="100%" class="border-0 w-100 h-100" style="min-height: 380px;"
                                allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>

                        <div class="p-3 bg-white rounded-4 border shadow-sm">
                            <div class="d-flex align-items-center gap-3">
                                <div class="p-2 bg-soft-primary text-primary rounded-circle fs-20">
                                    <i class="ri-customer-service-2-fill"></i>
                                </div>
                                <div>
                                    <h6 class="mb-0 fw-bold text-dark">Need Live Assistance?</h6>
                                    <p class="fs-12 text-muted mb-0">Our support representatives are active and ready to help you.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact section end -->

    <!-- Custom CSS for Contact Page -->
    <style>
        .contact-detail-box {
            transition: all 0.25s ease;
            background: #ffffff;
            border-radius: 16px;
            padding: 24px 20px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.03);
            border: 1px solid #f0f0f4;
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .contact-detail-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(255, 141, 47, 0.12);
            border-color: #ffd2b2;
        }
        .contact-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, #fff0e2, #ffe4cf);
            color: #ff8d2f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }
        .contact-form {
            border: 1px solid #f0f0f4 !important;
        }
        .contact-form .form-control:focus,
        .contact-form .form-select:focus {
            border-color: #ff8d2f;
            box-shadow: 0 0 0 3px rgba(255, 141, 47, 0.15);
        }
        .bg-soft-success {
            background-color: rgba(16, 185, 129, 0.12) !important;
        }
        .bg-soft-primary {
            background-color: rgba(255, 141, 47, 0.12) !important;
            color: #ff8d2f !important;
        }
    </style>
@endsection