<div class="nxl-content">
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <div class="page-header-title">
                <h5 class="m-b-10">Help &amp; Support</h5>
            </div>
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route($rp . 'index') }}">Support</a></li>
                <li class="breadcrumb-item">Help</li>
            </ul>
        </div>
    </div>

    <div class="main-content">
        <div class="row g-4">
            <!-- Help / FAQ -->
            <div class="col-lg-8">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-header border-bottom py-3">
                        <h5 class="card-title mb-0 fw-bold">Help &amp; Support</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Before raising a ticket, check our most common questions below.</p>

                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">How do I get a refund for a failed order?</button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">If payment was deducted but the order failed, raise a <b>Payment Issue</b> ticket with the Order ID. Refunds are processed within 3-5 business days.</div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">My delivery partner did not arrive.</button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">Raise a <b>Delivery Issue</b> ticket (High priority) with the Order ID so we can investigate and re-assign a partner.</div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">How do I update my restaurant menu?</button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">Menu updates are done from your restaurant panel. If the option is not working, raise an <b>Account / Technical</b> ticket.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="col-lg-4">
                <div class="card stretch stretch-full border-0 shadow-sm rounded-3">
                    <div class="card-body text-center">
                        <i class="feather-life-buoy fs-1 text-danger"></i>
                        <h6 class="mt-2">Need more help?</h6>
                        <p class="text-muted small">Raise a ticket and our support team will respond.</p>
                        <a href="{{ route('ticket-create') }}" class="btn btn-danger text-white w-100 mb-2 fw-semibold" style="background-color: #cb202d; border: none;">Raise a Ticket</a>
                        <a href="{{ route($rp . 'index') }}" class="btn btn-light border w-100 fw-semibold">My Tickets</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
