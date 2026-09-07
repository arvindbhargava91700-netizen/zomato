<div class="order-summery-section sticky-top">
    <div class="checkout-detail">
        <ul id="checkout-items"></ul>
        <h5 class="bill-details-title fw-semibold dark-text">
            Bill Details
        </h5>
        <div class="sub-total">
            <h6 class="content-color fw-normal">Sub Total</h6>
            <h6 class="fw-semibold" id="co-subtotal">{{ $currencySymbol }}0.00</h6>
        </div>
        <div class="sub-total">
            <h6 class="content-color fw-normal">
                Delivery Charge
            </h6>
            <h6 class="fw-semibold success-color" id="co-delivery">{{ $currencySymbol }}{{ number_format($deliveryChargePerKm, 2) }}</h6>
        </div>
        <div class="sub-total">
            <h6 class="content-color fw-normal">Discount (10%)</h6>
            <h6 class="fw-semibold" id="co-discount">{{ $currencySymbol }}0.00</h6>
        </div>
        <div class="sub-total">
            <h6 class="content-color fw-normal">Tax ({{ $taxGst ?: $taxPercentage . '%' }})</h6>
            <h6 class="fw-semibold" id="co-tax">{{ $currencySymbol }}0.00</h6>
        </div>
        <div class="grand-total">
            <h6 class="fw-semibold dark-text">To Pay</h6>
            <h6 class="fw-semibold amount" id="co-total">{{ $currencySymbol }}0.00</h6>
        </div>
    </div>
</div>
