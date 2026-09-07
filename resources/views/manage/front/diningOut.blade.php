@extends('layouts.front.main')

@section('title', 'Dining Restaurants in ' . ($location ?? 'Lucknow'))

@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Dining Out in {{ $location ?? 'Lucknow' }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i> Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Dining Out
                    </li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <style>
        /* Zomato-style restaurant filters */
        .restaurant-filter-bar {
            flex-wrap: wrap;
        }
        .restaurant-filter-bar .filter-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 17px;
            border-radius: 999px;
            border: 1px solid rgba(var(--dark-text), 0.14);
            background: rgba(var(--white), 1);
            color: rgba(var(--dark-text), 1);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            text-decoration: none;
        }
        .restaurant-filter-bar .filter-chip i {
            font-size: 16px;
        }
        .restaurant-filter-bar .filter-chip:hover {
            border-color: rgba(var(--theme-color), 0.6);
            color: rgba(var(--theme-color2), 1);
            transform: translateY(-2px);
        }
        .restaurant-filter-bar .filter-chip.active {
            background: linear-gradient(135deg, rgba(var(--theme-color), 1), rgba(var(--theme-color2), 1));
            border-color: transparent;
            color: #fff;
            box-shadow: 0 8px 18px rgba(var(--theme-color), 0.35);
        }
        .restaurant-filter-bar .filter-chip.active i {
            color: #fff;
        }

        /* Rating badge on restaurant cards */
        .vertical-product-box-img {
            position: relative;
        }
        .product-rating-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 9px;
            border-radius: 8px;
            background: #16943e;
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.25);
        }

        /* ===== Restaurant open-status badge ===== */
        .product-status-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(2px);
        }
        .product-status-badge .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.7);
        }
        .product-status-badge.open {
            background: linear-gradient(135deg, #1bb957, #149a47);
            animation: pulseOpen 1.8s infinite;
        }
        .product-status-badge.closed {
            background: linear-gradient(135deg, #ff8d2f, #f4622e);
        }
        @keyframes pulseOpen {
            0%   { box-shadow: 0 0 0 0 rgba(27, 185, 87, 0.5); }
            70%  { box-shadow: 0 0 0 8px rgba(27, 185, 87, 0); }
            100% { box-shadow: 0 0 0 0 rgba(27, 185, 87, 0); }
        }
        .product-open-at {
            display: flex;
            align-items: center;
            gap: 6px;
            margin-top: 6px;
            font-size: 13px;
            font-weight: 600;
            color: rgba(var(--dark-text), 0.7);
        }
        .product-open-at i {
            color: rgba(var(--theme-color), 1);
        }

        /* ===== Location modal ===== */
        .location-suggestions {
            margin-top: 10px;
            max-height: 240px;
            overflow-y: auto;
        }
        .location-suggestions .suggestion-empty {
            padding: 8px 4px;
            color: #999;
            font-size: 13px;
        }
        .location-suggestions .recent-location,
        #recent_locations .recent-location {
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.15s ease;
        }
        .location-suggestions .recent-location:hover,
        #recent_locations .recent-location:hover {
            background: rgba(255, 141, 47, 0.08);
        }

        /* ===== Zomato-style Filters modal ===== */
        .filter-modal-dialog {
            max-width: 720px;
        }
        .filter-modal {
            border-radius: 14px;
            overflow: hidden;
        }
        .filter-modal-header {
            border-bottom: 1px solid rgba(var(--dark-text), 0.08);
        }
        .filter-modal-body {
            max-height: 60vh;
            overflow: hidden;
        }
        .filter-nav {
            border-right: 1px solid rgba(var(--dark-text), 0.08);
            background: rgba(var(--white), 1);
        }
        .filter-nav-item {
            display: block;
            width: 100%;
            text-align: left;
            padding: 13px 18px;
            border: none;
            background: transparent;
            font-weight: 600;
            font-size: 14px;
            color: rgba(var(--dark-text), 1);
            border-left: 3px solid transparent;
            cursor: pointer;
        }
        .filter-nav-item:hover {
            background: rgba(var(--theme-color), 0.06);
        }
        .filter-nav-item.active {
            background: rgba(var(--theme-color), 0.10);
            border-left-color: rgba(var(--theme-color), 1);
            color: rgba(var(--theme-color2), 1);
        }
        .filter-nav-item .nav-count {
            margin-left: 4px;
            font-size: 12px;
            font-weight: 700;
            color: rgba(var(--theme-color), 1);
        }
        .filter-panels {
            max-height: 60vh;
            overflow-y: auto;
        }
        .filter-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 8px;
        }
        .filter-option:hover {
            background: rgba(var(--theme-color), 0.05);
        }
        .filter-option input {
            width: 17px;
            height: 17px;
            accent-color: rgba(var(--theme-color), 1);
        }
        .cuisine-list {
            max-height: 46vh;
            overflow-y: auto;
        }
        .cuisine-item:has(input:checked) {
            background: rgba(var(--theme-color), 0.10);
            color: rgba(var(--theme-color2), 1);
        }
        .cost-range {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .cost-range input[type="range"] {
            width: 100%;
            accent-color: rgba(var(--theme-color), 1);
        }
        .filter-modal-footer {
            border-top: 1px solid rgba(var(--dark-text), 0.08);
        }
    </style>

    <!-- Dining Restaurants listing section -->
    <section id="home-popular" class="section-b-space pt-4">
        <div class="container">
            <!-- Filter Bar Chips -->
            <div class="restaurant-filter-bar d-flex align-items-center flex-wrap gap-2 mb-sm-4 mb-3">
                <button type="button" class="filter-chip" id="openFilterModal">
                    <i class="ri-equalizer-line"></i> Filters
                </button>
                <button type="button" class="filter-chip quick-filter" data-qf="open_now">
                    <i class="ri-time-line"></i> Open Now
                </button>
                <button type="button" class="filter-chip quick-filter {{ $presetOffers ? 'active' : '' }}" data-qf="offers">
                    <i class="ri-discount-percent-line"></i> Offers
                </button>
                <button type="button" class="filter-chip quick-filter" data-qf="rating">
                    <i class="ri-star-line"></i> Rating: 4.5+
                </button>
                <button type="button" class="filter-chip quick-filter" data-qf="pet_friendly">
                    <i class="ri-paw-line"></i> Pet friendly
                </button>
                <button type="button" class="filter-chip quick-filter" data-qf="outdoor_seating">
                    <i class="ri-umbrella-line"></i> Outdoor seating
                </button>
                <button type="button" class="filter-chip quick-filter" data-qf="serves_alcohol">
                    <i class="ri-cup-line"></i> Serves Alcohol
                </button>
            </div>

            <!-- Restaurant Cards Grid -->
            <div class="row g-md-4 g-3" id="popular-restaurants-list">
                @for ($i = 0; $i < 8; $i++)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="vertical-product-box">
                            <div class="vertical-product-box-img placeholder-glow">
                                <span class="product-img-top w-100 bg-img d-block" style="height:180px;background:#eee;"></span>
                            </div>
                            <div class="vertical-product-body placeholder-glow">
                                <span class="placeholder col-7 mb-2"></span>
                                <span class="placeholder col-10 mb-2"></span>
                                <span class="placeholder col-5"></span>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <!-- Location modal (opened by header .location-btn) -->
    <div class="modal fade" id="location" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered location-modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-semibold" id="locationModalLabel">Select a Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="search-section">
                        <form class="form_search" role="form" id="location_search_form">
                            <input type="search" placeholder="Search Location" class="nav-search nav-search-field"
                                id="location_search_input" autocomplete="off">
                        </form>
                        <div id="location_suggestions" class="location-suggestions"></div>
                    </div>
                    <a href="#!" class="current-location" id="use_current_location">
                        <div class="current-address">
                            <i class="ri-focus-3-line focus"></i>
                            <div>
                                <h5>Use current-location</h5>
                                <h6 id="currentLocation">Detecting your location...</h6>
                            </div>
                        </div>
                        <i class="ri-arrow-right-s-line arrow"></i>
                    </a>
                    <h5 class="mt-sm-3 mt-2 fw-medium recent-title dark-text">
                        Recent Location
                    </h5>
                    <div id="recent_locations"></div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn gray-btn" data-bs-dismiss="modal">Close</a>
                    <a href="#" class="btn theme-btn mt-0" data-bs-dismiss="modal" id="location_save">Save</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Modal (Zomato style) -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered filter-modal-dialog">
            <div class="modal-content filter-modal">
                <div class="modal-header filter-modal-header d-flex align-items-center justify-content-between">
                    <h5 class="modal-title fw-bold" id="filterModalLabel">Filters</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body filter-modal-body p-0">
                    <div class="row g-0 h-100">
                        <div class="col-4 filter-nav py-2">
                            <button type="button" class="filter-nav-item active" data-target="panel-sort">
                                Sort by <span class="nav-count" id="navCount-sort"></span>
                            </button>
                            <button type="button" class="filter-nav-item" data-target="panel-cuisines">
                                Cuisines <span class="nav-count" id="navCount-cuisines"></span>
                            </button>
                            <button type="button" class="filter-nav-item" data-target="panel-rating">
                                Rating <span class="nav-count" id="navCount-rating"></span>
                            </button>
                            <button type="button" class="filter-nav-item" data-target="panel-cost">
                                Cost for two <span class="nav-count" id="navCount-cost"></span>
                            </button>
                            <button type="button" class="filter-nav-item" data-target="panel-offers">
                                Offers <span class="nav-count" id="navCount-offers"></span>
                            </button>
                            <button type="button" class="filter-nav-item" data-target="panel-more">
                                More filters <span class="nav-count" id="navCount-more"></span>
                            </button>
                        </div>
                        <div class="col-8 filter-panels p-3">
                            <!-- Sort panel -->
                            <div class="filter-panel" id="panel-sort">
                                <h6 class="fw-bold mb-2">Sort by</h6>
                                <label class="filter-option"><input type="radio" name="f_sort" value="popularity" checked> Popularity</label>
                                <label class="filter-option"><input type="radio" name="f_sort" value="rating_high"> Rating: High to Low</label>
                                <label class="filter-option"><input type="radio" name="f_sort" value="cost_low"> Cost: Low to High</label>
                                <label class="filter-option"><input type="radio" name="f_sort" value="cost_high"> Cost: High to Low</label>
                                <label class="filter-option"><input type="radio" name="f_sort" value="distance"> Distance</label>
                            </div>

                            <!-- Cuisines panel -->
                            <div class="filter-panel d-none" id="panel-cuisines">
                                <h6 class="fw-bold mb-2">Cuisines</h6>
                                <input type="text" class="form-control form-control-sm mb-2" id="cuisineSearch" placeholder="Search cuisines...">
                                <div class="cuisine-list">
                                    @foreach($cuisines as $c)
                                        <label class="filter-option cuisine-item" data-name="{{ strtolower($c->name) }}">
                                            <input type="checkbox" name="f_cuisine" value="{{ $c->id }}"> {{ $c->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Rating panel -->
                            <div class="filter-panel d-none" id="panel-rating">
                                <h6 class="fw-bold mb-2">Rating</h6>
                                <label class="filter-option"><input type="radio" name="f_rating" value="" checked> Any rating</label>
                                <label class="filter-option"><input type="radio" name="f_rating" value="4.5"> 4.5+</label>
                                <label class="filter-option"><input type="radio" name="f_rating" value="4.0"> 4.0+</label>
                                <label class="filter-option"><input type="radio" name="f_rating" value="3.5"> 3.5+</label>
                            </div>

                            <!-- Cost panel -->
                            <div class="filter-panel d-none" id="panel-cost">
                                <h6 class="fw-bold mb-2">Cost for two</h6>
                                <label class="filter-option"><input type="radio" name="f_cost" value="" checked> Any cost</label>
                                <label class="filter-option"><input type="radio" name="f_cost" value="0-300"> Less than ₹300</label>
                                <label class="filter-option"><input type="radio" name="f_cost" value="300-600"> ₹300 to ₹600</label>
                                <label class="filter-option"><input type="radio" name="f_cost" value="600-1000"> ₹600 to ₹1000</label>
                                <label class="filter-option"><input type="radio" name="f_cost" value="1000-99999"> ₹1000+</label>
                            </div>

                            <!-- Offers panel -->
                            <div class="filter-panel d-none" id="panel-offers">
                                <h6 class="fw-bold mb-2">Offers</h6>
                                <label class="filter-option">
                                    <input type="checkbox" name="f_offers" value="1" {{ $presetOffers ? 'checked' : '' }}> Restaurants with dining offers
                                </label>
                            </div>

                            <!-- More panel -->
                            <div class="filter-panel d-none" id="panel-more">
                                <h6 class="fw-bold mb-2">More filters</h6>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="credit_card"> Credit card</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="buffet"> Buffet</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="happy_hours"> Happy hours</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="serves_alcohol"> Serves Alcohol</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="open_now"> Open now</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="pubs_bars"> Pubs & Bars</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="fine_dining"> Fine Dining</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="wifi"> Wifi</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="outdoor_seating"> Outdoor seating</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="cafes"> Cafés</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="hygiene_rated"> Hygiene Rated</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="online_bookings"> Online bookings</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="pure_veg"> Pure veg</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer filter-modal-footer">
                    <button type="button" class="btn gray-btn" id="clearAllFilters">Clear all</button>
                    <button type="button" class="btn theme-btn" id="applyFiltersBtn">Show restaurants</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/assets/vendors/js/jquery.min.js') }}"></script>
    <script>
        $(function () {
            var $list = $('#popular-restaurants-list');
            var baseUrl = '{{ url('/restaurants') }}';
            var menuUrl = '{{ route('menu.list') }}';
            var assetBase = '{{ asset('') }}';
            var locationModal = new bootstrap.Modal(document.getElementById('location'));
            var filterModal = new bootstrap.Modal(document.getElementById('filterModal'));
            var RECENT_KEY = 'recent_locations';

            function escapeHtml(str) {
                if (str === null || str === undefined) return '';
                return String(str).replace(/[&<>"']/g, function (m) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m];
                });
            }

            function skeletonCard() {
                return ''
                    + '<div class="col-xl-3 col-lg-4 col-md-6">'
                    +   '<div class="vertical-product-box">'
                    +     '<div class="vertical-product-box-img placeholder-glow">'
                    +       '<span class="product-img-top w-100 bg-img d-block" style="height:180px;background:#eee;"></span>'
                    +     '</div>'
                    +     '<div class="vertical-product-body placeholder-glow">'
                    +       '<span class="placeholder col-7 mb-2"></span>'
                    +       '<span class="placeholder col-10 mb-2"></span>'
                    +       '<span class="placeholder col-5"></span>'
                    +     '</div>'
                    +   '</div>'
                    + '</div>';
            }

            function showSkeleton() {
                var html = '';
                for (var i = 0; i < 8; i++) { html += skeletonCard(); }
                $list.html(html);
            }

            function cardHtml(r) {
                var link = menuUrl + '?slug=' + r.slug + '&tab=overview';
                var img = assetBase + (r.logo ? r.logo : (r.banner ? r.banner : 'front/assets/images/product/vp-1.png'));
                var place = r.city ? r.city : '';
                var dist = (r.distance_km !== null && r.distance_km !== undefined)
                    ? parseFloat(r.distance_km).toFixed(1) + ' km' : '—';
                var time = (r.estimated_delivery_time !== null && r.estimated_delivery_time !== undefined)
                    ? r.estimated_delivery_time + ' min' : '30 min';
                var desc = r.description ? r.description : 'Delicious dining near you';
                var rating = (r.rating !== null && r.rating !== undefined)
                    ? '<div class="product-rating-badge"><i class="ri-star-fill"></i> ' + parseFloat(r.rating).toFixed(1) + '</div>'
                    : '';
                var cost = (r.cost_for_two !== null && r.cost_for_two !== undefined)
                    ? '₹' + parseFloat(r.cost_for_two).toFixed(0) + ' for two'
                    : '₹0 for two';

                var statusBadge = '';
                if (r.is_open) {
                    statusBadge = '<div class="product-status-badge open"><span class="dot"></span> Open now</div>';
                } else if (r.opening_time) {
                    statusBadge = '<div class="product-status-badge closed"><span class="dot"></span> Opens at ' + r.opening_time + '</div>';
                }

                var openAt = (r.opening_time)
                    ? '<h6 class="product-open-at"><i class="ri-time-line"></i> Open at ' + r.opening_time
                        + (r.closing_time ? ' – ' + r.closing_time : '') + '</h6>'
                    : '';

                return ''
                    + '<div class="col-xl-3 col-lg-4 col-md-6">'
                    +   '<div class="vertical-product-box">'
                    +     '<div class="vertical-product-box-img">'
                    +       '<a href="' + link + '"><img class="product-img-top w-100 bg-img" src="' + img + '" alt="' + escapeHtml(r.name) + '"></a>'
                    +       rating
                    +       statusBadge
                    +     '</div>'
                    +     '<div class="vertical-product-body">'
                    +       '<div class="d-flex align-items-center justify-content-between mt-sm-3 mt-2">'
                    +         '<a href="' + link + '"><h4 class="vertical-product-title">' + escapeHtml(r.name) + '</h4></a>'
                    +       '</div>'
                    +       '<h5 class="product-items">' + escapeHtml(desc) + '</h5>'
                    +       '<div class="location-distance d-flex align-items-center justify-content-between pt-sm-3 pt-2">'
                    +         '<h5 class="place"><i class="ri-map-pin-line me-1"></i>' + escapeHtml(place) + '</h5>'
                    +         '<ul class="distance">'
                    +           '<li><i class="ri-map-pin-fill icon"></i> ' + dist + '</li>'
                    +           '<li><i class="ri-time-fill icon"></i> ' + time + '</li>'
                    +         '</ul>'
                    +       '</div>'
                    +       (cost ? '<h6 class="product-cost mt-1 fw-semibold theme-color">' + cost + '</h6>' : '')
                    +       openAt
                    +     '</div>'
                    +   '</div>'
                    + '</div>';
            }

            function render(list) {
                if (!list || !list.length) {
                    $list.html('<div class="col-12"><div class="text-center py-5 text-muted">'
                        + 'No dining restaurants found for this location or filter criteria.</div></div>');
                    return;
                }
                var html = '';
                $.each(list, function (i, r) { html += cardHtml(r); });
                $list.html(html);
            }

            var initialLocation = '{{ $location ?? 'Lucknow' }}';
            var state = { location: initialLocation };

            // Active filter state
            var filters = {
                sort: 'popularity',
                cuisines: [],
                rating: null,
                min_cost: null,
                max_cost: null,
                offers: {{ $presetOffers ? 'true' : 'false' }},
                more: {}
            };

            function getFilters() {
                var f = {};
                f.sort = filters.sort;
                if (filters.cuisines.length) { f.cuisines = filters.cuisines.slice(); }
                if (filters.rating) { f.rating = filters.rating; }
                if (filters.offers) { f.offers = 1; }
                if (filters.min_cost !== null) { f.min_cost = filters.min_cost; }
                if (filters.max_cost !== null) { f.max_cost = filters.max_cost; }
                $.each(filters.more, function (k, v) { if (v) { f[k] = 1; } });
                return f;
            }

            function syncQuickChips() {
                $('.quick-filter').each(function () {
                    var key = $(this).data('qf');
                    var active = key === 'rating' ? !!filters.rating
                        : key === 'offers' ? !!filters.offers
                        : !!filters.more[key];
                    $(this).toggleClass('active', active);
                });
            }

            function loadRestaurants() {
                showSkeleton();
                var data = $.extend({}, state || {}, getFilters());
                var url = '{{ route('public.restaurants.nearby') }}';
                var hasLocParams = data.city_id || data.state_id || data.country_id || data.location;
                if (hasLocParams) {
                    url = '{{ route('public.restaurants.search-location') }}';
                }
                $.ajax({
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function (res) { render(res.restaurants || []); },
                    error: function () {
                        $list.html('<div class="col-12"><div class="text-center py-5 text-muted">'
                            + 'Unable to load restaurants. Please try again.</div></div>');
                    }
                });
            }

            function fetchNearby(lat, lng) {
                state = { lat: lat, lng: lng };
                loadRestaurants();
            }

            function fetchByLocation(params) {
                state = params;
                locationModal.hide();
                loadRestaurants();
            }

            // ---- Recent locations (localStorage) ----
            function getRecent() {
                try { return JSON.parse(localStorage.getItem(RECENT_KEY)) || []; }
                catch (e) { return []; }
            }
            function saveRecent(item) {
                var list = getRecent().filter(function (x) { return x.id !== item.id || x.type !== item.type; });
                list.unshift(item);
                localStorage.setItem(RECENT_KEY, JSON.stringify(list.slice(0, 5)));
                renderRecent();
            }
            function renderRecent() {
                var list = getRecent();
                var $box = $('#recent_locations').empty();
                if (!list.length) {
                    $box.html('<p class="text-muted small mb-0">No recent locations yet.</p>');
                    return;
                }
                $.each(list, function (i, loc) {
                    var sub = loc.state ? loc.state : 'Saved location';
                    $box.append(
                        '<a href="#!" class="recent-location recent-item" '
                        + 'data-type="' + loc.type + '" data-id="' + loc.id + '">'
                        +   '<div class="recant-address">'
                        +     '<i class="ri-map-pin-line theme-color"></i>'
                        +     '<div><h5>' + escapeHtml(loc.name) + '</h5>'
                        +     '<h6>' + escapeHtml(sub) + '</h6></div>'
                        +   '</div>'
                        + '</a>'
                    );
                });
            }

            function selectSuggestion(s) {
                saveRecent(s);
                fetchByLocation({ city_id: s.id, location: s.name });
            }

            // ---- Search suggestions ----
            var suggestTimer = null;
            $('#location_search_input').on('input', function () {
                var q = $(this).val().trim();
                $('#location_suggestions').empty();
                if (q.length < 2) return;
                clearTimeout(suggestTimer);
                suggestTimer = setTimeout(function () {
                    $.ajax({
                        url: '{{ route('public.restaurants.location-suggest') }}',
                        data: { q: q },
                        dataType: 'json',
                        success: function (res) {
                            var items = res.suggestions || [];
                            var $box = $('#location_suggestions').empty();
                            if (!items.length) {
                                $box.html('<div class="suggestion-empty">No matching places found.</div>');
                                return;
                            }
                            $.each(items, function (i, s) {
                                var $a = $('<a href="#!" class="recent-location d-block p-2 text-decoration-none"></a>');
                                $a.html('<i class="ri-map-pin-line me-2 theme-color"></i><strong>'
                                    + escapeHtml(s.name) + '</strong>'
                                    + (s.state ? ' <small class="text-muted">(' + escapeHtml(s.state) + ')</small>' : ''));
                                $a.on('click', function (e) {
                                    e.preventDefault();
                                    selectSuggestion(s);
                                });
                                $box.append($a);
                            });
                        }
                    });
                }, 250);
            });

            $('#recent_locations').on('click', '.recent-item', function (e) {
                e.preventDefault();
                var type = $(this).data('type');
                var id = $(this).data('id');
                var name = $(this).find('h5').text();
                if (type === 'city') fetchByLocation({ city_id: id, location: name });
                else if (type === 'state') fetchByLocation({ state_id: id, location: name });
                else if (type === 'country') fetchByLocation({ country_id: id, location: name });
            });

            // ---- Current location (GPS) ----
            $('#use_current_location').on('click', function () {
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser.');
                    return;
                }
                var $btn = $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Locating...');
                navigator.geolocation.getCurrentPosition(
                    function (pos) {
                        $btn.prop('disabled', false).html('<i class="ri-crosshair-2-line"></i> Use Current Location');
                        locationModal.hide();
                        fetchNearby(pos.coords.latitude, pos.coords.longitude);
                    },
                    function (err) {
                        $btn.prop('disabled', false).html('<i class="ri-crosshair-2-line"></i> Use Current Location');
                        alert('Unable to retrieve location: ' + err.message);
                    },
                    { timeout: 10000 }
                );
            });

            // Quick filter chips toggle
            $('.quick-filter').on('click', function () {
                var key = $(this).data('qf');
                if (key === 'rating') {
                    filters.rating = filters.rating ? null : 4.5;
                } else if (key === 'offers') {
                    filters.offers = !filters.offers;
                } else {
                    filters.more[key] = !filters.more[key];
                }
                syncQuickChips();
                loadRestaurants();
            });

            // Filter modal open
            $('#openFilterModal').on('click', function () {
                filterModal.show();
            });

            $('.filter-nav-item').on('click', function () {
                $('.filter-nav-item').removeClass('active');
                $(this).addClass('active');
                var target = $(this).data('target');
                $('.filter-panel').addClass('d-none');
                $('#' + target).removeClass('d-none');
            });

            // Cuisine search inside modal
            $('#cuisineSearch').on('input', function () {
                var q = $(this).val().toLowerCase().trim();
                $('.cuisine-item').each(function () {
                    var name = $(this).data('name') || '';
                    $(this).toggle(name.indexOf(q) !== -1);
                });
            });

            $('#applyFiltersBtn').on('click', function () {
                filters.sort = $('input[name="f_sort"]:checked').val() || 'popularity';
                filters.rating = $('input[name="f_rating"]:checked').val() || null;
                filters.offers = $('input[name="f_offers"]').is(':checked');

                var cuisines = [];
                $('input[name="f_cuisine"]:checked').each(function () { cuisines.push($(this).val()); });
                filters.cuisines = cuisines;

                var costVal = $('input[name="f_cost"]:checked').val();
                if (costVal) {
                    var parts = costVal.split('-');
                    filters.min_cost = parts[0] ? parseFloat(parts[0]) : null;
                    filters.max_cost = parts[1] ? parseFloat(parts[1]) : null;
                } else {
                    filters.min_cost = null;
                    filters.max_cost = null;
                }

                filters.more = {};
                $('input[name="f_more"]:checked').each(function () {
                    filters.more[$(this).val()] = true;
                });

                syncQuickChips();
                filterModal.hide();
                loadRestaurants();
            });

            $('#clearAllFilters').on('click', function () {
                filters = { sort: 'popularity', cuisines: [], rating: null, min_cost: null, max_cost: null, offers: false, more: {} };
                $('input[name="f_sort"][value="popularity"]').prop('checked', true);
                $('input[name="f_rating"][value=""]').prop('checked', true);
                $('input[name="f_cost"][value=""]').prop('checked', true);
                $('input[name="f_offers"]').prop('checked', false);
                $('input[name="f_cuisine"]').prop('checked', false);
                $('input[name="f_more"]').prop('checked', false);
                syncQuickChips();
                filterModal.hide();
                loadRestaurants();
            });

            syncQuickChips();
            loadRestaurants();
        });
    </script>
@endpush
