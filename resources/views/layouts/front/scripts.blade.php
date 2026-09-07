       <script src="{{asset('front/assets/js/bootstrap.bundle.min.js')}}"></script>

    {{-- Laravel Echo + Pusher (sets window.Echo for realtime features) --}}
    @vite(['resources/js/app.js'])

    <script>
        window.CURRENCY_SYMBOL = "{{ $currencySymbol }}";
        window.DELIVERY_CHARGE_PER_KM = "{{ $deliveryChargePerKm }}";
        window.TAX_PERCENTAGE = "{{ $taxPercentage }}";
    </script>

    <!-- jquery (required for dynamic header cart) -->
    <script src="{{ asset('admin/assets/vendors/js/jquery.min.js') }}"></script>

    <!-- dynamic header cart (count + popup from localStorage) -->
    <script>
        window.ZomoCart = (function ($) {
            function esc(s) {
                return $('<div>').text(s == null ? '' : s).html();
            }

            function getItems() {
                var items = {};
                for (var i = 0; i < localStorage.length; i++) {
                    var k = localStorage.key(i);
                    if (k && k.indexOf('food_cart_') === 0) {
                        try {
                            var obj = JSON.parse(localStorage.getItem(k));
                            if (obj && typeof obj === 'object') {
                                for (var id in obj) {
                                    if (!obj.hasOwnProperty(id)) continue;
                                    var it = obj[id];
                                    if (!it) continue;
                                    if (items[id]) {
                                        items[id].qty += it.qty;
                                    } else {
                                        items[id] = {
                                            id: id,
                                            name: it.name,
                                            price: it.price,
                                            img: it.img,
                                            qty: it.qty
                                        };
                                    }
                                }
                            }
                        } catch (e) {}
                    }
                }
                return items;
            }

            function refresh() {
                var $list = $('#header-cart-list');
                if (!$list.length) return;

                var items = getItems();
                var ids = Object.keys(items);
                var totalQty = 0;
                var total = 0;

                $list.empty();

                if (ids.length === 0) {
                    $list.append('<li class="product-box-contain"><div class="drop-cart"><div class="drop-contain"><p class="content-color">Your cart is empty.</p></div></div></li>');
                    $('#cart-count').text('0').hide();
                    $('#header-cart-total').text(window.CURRENCY_SYMBOL + '0.00');
                    return;
                }

                ids.forEach(function (id) {
                    var it = items[id];
                    totalQty += it.qty;
                    total += parseFloat(it.price) * it.qty;
                    var img = it.img ? it.img : '{{ asset('front/assets/images/product/vp-3.png') }}';
                    var html =
                        '<li class="product-box-contain">' +
                            '<div class="drop-cart">' +
                                '<a href="#!" class="drop-image">' +
                                    '<img src="' + esc(img) + '" class="blur-up lazyloaded" alt="">' +
                                '</a>' +
                                '<div class="drop-contain">' +
                                    '<a href="#!"><h5>' + esc(it.name) + '</h5></a>' +
                                    '<h6><span>' + it.qty + ' x </span> ' + window.CURRENCY_SYMBOL + parseFloat(it.price).toFixed(2) + '</h6>' +
                                    '<button class="close-button close_button" data-id="' + esc(id) + '">' +
                                        '<i class="fa-solid fa-xmark"></i>' +
                                    '</button>' +
                                '</div>' +
                            '</div>' +
                        '</li>';
                    $list.append(html);
                });

                $('#cart-count').text(totalQty).show();
                $('#header-cart-total').text(window.CURRENCY_SYMBOL + total.toFixed(2));
            }

            function removeItem(id) {
                id = String(id);
                for (var i = 0; i < localStorage.length; i++) {
                    var k = localStorage.key(i);
                    if (k && k.indexOf('food_cart_') === 0) {
                        try {
                            var obj = JSON.parse(localStorage.getItem(k));
                            if (obj && obj[id]) {
                                delete obj[id];
                                localStorage.setItem(k, JSON.stringify(obj));
                            }
                        } catch (e) {}
                    }
                }
                refresh();
            }

            $(function () {
                refresh();
                $(document).on('click', '.close_button', function () {
                    removeItem($(this).data('id'));
                });
                window.addEventListener('storage', function () {
                    refresh();
                });
            });

            return { refresh: refresh, removeItem: removeItem };
        })(jQuery);
    </script>

    <!-- footer accordion js -->
    <script src="{{asset('front/assets/js/footer-accordion.js')}}"></script>

    <!-- loader js -->
    <script src="{{asset('front/assets/js/loader.js')}}"></script>

    <!-- swiper js -->
    <script src="{{asset('front/assets/js/swiper-bundle.min.js')}}"></script>
    <script src="{{asset('front/assets/js/custom-swiper.js')}}"></script>

    <!-- menu offcanvas js -->
    <script src="{{asset('front/assets/js/menu-button.js')}}"></script>

    <!-- script js -->
    <script src="{{asset('front/assets/js/script.js')}}"></script>

    <!-- shared checkout cart (summary + totals used on checkout/address/payment/confirm) -->
    <script src="{{ asset('front/assets/js/checkout-cart.js') }}"></script>
    <!-- google map js -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDGCQvcXUsXwCdYArPXo72dLZ31WS3WQRw"></script>
    <script src="{{ asset('front/assets/js/route-map.js') }}"></script>