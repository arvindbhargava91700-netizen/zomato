(function ($) {
    var SYMBOL = window.CURRENCY_SYMBOL || '$';

    var DELIVERY_CHARGE = (parseFloat(window.DELIVERY_CHARGE_PER_KM) || 20) * 2;

    var TAX_PERCENT = parseFloat(window.TAX_PERCENTAGE) || 0;

    var DELIVERY_OPTIONS = {
        standard: { label: 'Standard Delivery', charge: DELIVERY_CHARGE },
        express: { label: 'Express Delivery', charge: DELIVERY_CHARGE }
    };

    var PAYMENT_METHODS = {
        cash_on_delivery: 'Cash on Delivery',
        card: 'Card',
        wallet: 'Wallet'
    };

    function esc(s) {
        return $('<div>').text(s == null ? '' : s).html();
    }

    function getCarts() {
        var map = {};
        for (var i = 0; i < localStorage.length; i++) {
            var k = localStorage.key(i);
            if (k && k.indexOf('food_cart_') === 0) {
                try {
                    var o = JSON.parse(localStorage.getItem(k));
                    if (o && typeof o === 'object') map[k] = o;
                } catch (e) {}
            }
        }
        return map;
    }

    function getItems() {
        var carts = getCarts();
        var items = [];
        for (var k in carts) {
            for (var id in carts[k]) {
                if (carts[k][id]) items.push(carts[k][id]);
            }
        }
        return items;
    }

    function deliveryOption() {
        var opt = localStorage.getItem('checkout_delivery_option');
        return DELIVERY_OPTIONS[opt] ? opt : 'standard';
    }

    function deliveryCharge() {
        if (typeof window.DELIVERY_CHARGE !== 'undefined') {
            return parseFloat(window.DELIVERY_CHARGE);
        }
        var opt = deliveryOption();
        return parseFloat(DELIVERY_OPTIONS[opt].charge);
    }

    function paymentMethod() {
        var m = localStorage.getItem('checkout_payment_method');
        return PAYMENT_METHODS[m] ? m : '';
    }

    function getTotals() {
        var items = getItems();
        var subtotal = 0;
        items.forEach(function (it) {
            subtotal += (parseFloat(it.price) || 0) * (parseInt(it.qty) || 1);
        });
        var tax = subtotal * (TAX_PERCENT / 100);
        var dc = deliveryCharge();
        var promo = parseFloat(localStorage.getItem('checkout_promo_discount')) || 0;
        var total = subtotal - promo + dc + tax;
        return { subtotal: subtotal, tax: tax, deliveryCharge: dc, promo: promo, total: total };
    }

    function render() {
        var $ul = $('#checkout-items');
        if (!$ul.length) return;

        var items = getItems();
        var t = getTotals();
        $ul.empty();

        if (items.length === 0) {
            $ul.append('<li><div class="text-center py-4"><p class="content-color">Your cart is empty.</p></div></li>');
        } else {
            items.forEach(function (it) {
                var qty = parseInt(it.qty) || 1;
                var price = parseFloat(it.price) || 0;
                var html =
                    '<li data-id="' + esc(it.id) + '">' +
                        '<div class="horizontal-product-box">' +
                            '<div class="product-content">' +
                                '<div class="d-flex align-items-center justify-content-between">' +
                                    '<h5>' + esc(it.name) + '</h5>' +
                                    '<div class="d-flex align-items-center gap-2">' +
                                        '<h6 class="product-price">' + SYMBOL + price.toFixed(2) + '</h6>' +
                                        '<i class="ri-delete-bin-line co-remove" data-id="' + esc(it.id) + '" style="cursor:pointer"></i>' +
                                    '</div>' +
                                '</div>' +
                                (it.desc ? '<h6 class="ingredients-text">' + esc(it.desc) + '</h6>' : '') +
                                '<div class="d-flex align-items-center justify-content-between mt-md-2 mt-1 gap-1">' +
                                    '<h6 class="place">Serve ' + qty + '</h6>' +
                                    '<div class="plus-minus">' +
                                        '<i class="ri-subtract-line sub co-minus" data-id="' + esc(it.id) + '"></i>' +
                                        '<input type="number" value="' + qty + '" min="1" max="99" readonly>' +
                                        '<i class="ri-add-line add co-plus" data-id="' + esc(it.id) + '"></i>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</div>' +
                    '</li>';
                $ul.append(html);
            });
        }

        var dc = t.deliveryCharge;
        $('#co-subtotal').text(SYMBOL + t.subtotal.toFixed(2));
        $('#co-tax').text(SYMBOL + t.tax.toFixed(2));

        if (t.promo > 0) {
            $('#co-promo-wrap').show();
            $('#co-promo').text('-' + SYMBOL + t.promo.toFixed(2));
        } else {
            $('#co-promo-wrap').hide();
        }

        $('#co-delivery').text(dc > 0 ? SYMBOL + dc.toFixed(2) : 'Free');
        $('#co-total').text(SYMBOL + t.total.toFixed(2));

        if (window.ZomoCart) window.ZomoCart.refresh();
    }

    function sourceKey(id) {
        var carts = getCarts();
        for (var k in carts) {
            if (carts[k][id]) return k;
        }
        return null;
    }

    function changeQty(id, delta) {
        id = String(id);
        var k = sourceKey(id);
        if (!k) return;
        var carts = getCarts();
        var o = carts[k];
        if (!o[id]) return;
        o[id].qty += delta;
        if (o[id].qty <= 0) delete o[id];
        localStorage.setItem(k, JSON.stringify(o));
        render();
    }

    function removeItem(id) {
        id = String(id);
        var k = sourceKey(id);
        if (!k) return;
        var carts = getCarts();
        var o = carts[k];
        if (!o[id]) return;
        delete o[id];
        localStorage.setItem(k, JSON.stringify(o));
        render();
    }

    function clearCart() {
        for (var i = 0; i < localStorage.length; i++) {
            var k = localStorage.key(i);
            if (k && k.indexOf('food_cart_') === 0) {
                localStorage.removeItem(k);
            }
        }
        localStorage.removeItem('checkout_address_id');
        localStorage.removeItem('checkout_delivery_option');
        localStorage.removeItem('checkout_payment_method');
        localStorage.removeItem('checkout_promo_code');
        localStorage.removeItem('checkout_promo_code_id');
        localStorage.removeItem('checkout_promo_discount');
    }

    $(function () {
        $(document).on('click', '.co-plus', function () {
            changeQty($(this).data('id'), 1);
        });
        $(document).on('click', '.co-minus', function () {
            changeQty($(this).data('id'), -1);
        });
        $(document).on('click', '.co-remove', function () {
            removeItem($(this).data('id'));
        });
        $(window).on('storage', render);
        render();
    });

    window.CheckoutCart = {
        getItems: getItems,
        getTotals: getTotals,
        render: render,
        clearCart: clearCart,
        removeItem: removeItem,
        deliveryOption: deliveryOption,
        deliveryCharge: deliveryCharge,
        paymentMethod: paymentMethod,
        deliveryOptions: DELIVERY_OPTIONS,
        paymentMethods: PAYMENT_METHODS
    };
})(jQuery);
