@extends('layouts.front.main')

@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Support &amp; Help</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i> Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Support</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <section class="section-b-space">
        <div class="container">
            <!-- action cards -->
            <div class="row g-4 mb-5">
                <div class="col-md-6">
                    <div class="card support-action-card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="support-action-icon">
                                <i class="ri-ticket-2-line fs-2"></i>
                            </div>
                            <h4 class="fw-bold mt-3">Raise a Ticket</h4>
                            <p class="text-muted">Facing an issue with an order or your account? Create a support
                                ticket and our team will get back to you shortly.</p>
                            <a href="{{ route('tickets-create') }}" class="btn theme-btn w-100">Raise a Ticket</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card support-action-card border-0 shadow-sm h-100">
                        <div class="card-body p-4 text-center">
                            <div class="support-action-icon support-action-icon--alt">
                                <i class="ri-file-list-3-line fs-2"></i>
                            </div>
                            <h4 class="fw-bold mt-3">My Tickets</h4>
                            <p class="text-muted">Track the status of tickets you have already raised and continue the
                                conversation with our support team.</p>
                            <a href="{{ route('tickets-index') }}" class="btn btn-outline-secondary w-100">View My Tickets</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- faq -->
            <div class="faq-title mb-4">
                <h2>Frequently Asked Questions</h2>
            </div>
            <div class="row g-4">
                <div class="col-xl-4 d-none d-xl-block">
                    <div class="side-img">
                        <img class="img-fluid" src="{{ asset('front/assets/images/faq.svg') }}" alt="faq">
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="accordion accordion-flush help-accordion" id="faqAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faqOne">
                                    How do I get a refund for a failed or cancelled order?
                                </button>
                            </h2>
                            <div id="faqOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    If the payment was deducted but the order failed or was cancelled, raise a
                                    <b>Payment Issue</b> ticket with the Order ID. Refunds are processed back to your
                                    original mode of payment within 3–5 business days.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faqTwo">
                                    My delivery partner did not arrive / order is delayed.
                                </button>
                            </h2>
                            <div id="faqTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Raise a <b>Delivery Issue</b> ticket (choose High priority) with the Order ID so we
                                    can investigate, re-assign a partner, or process compensation.
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#faqThree">
                                    How do I update my account or app settings?
                                </button>
                            </h2>
                            <div id="faqThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body">
                                    Profile, address and payment changes can be done from the My Account menu. If an
                                    option is not working, raise an <b>Account Issue</b> ticket and we will fix it.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-light border mt-4 d-flex align-items-center gap-2">
                        <i class="ri-customer-service-2-line fs-4 text-theme"></i>
                        <span>Still need help? <a href="{{ route('tickets-create') }}" class="fw-semibold">Raise a ticket</a>
                            and we will respond as soon as possible.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
