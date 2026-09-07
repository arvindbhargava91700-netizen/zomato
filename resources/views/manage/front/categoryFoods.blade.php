@extends('layouts.front.main')

@section('title', $category->name . ' - Food Categories')

@section('content')
    <style>
        .filter-section {
            background: #fff;
            border: 1px solid #f0f0f0;
            border-radius: 12px;
            padding: 14px 18px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        }
        .filter-bar {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-end;
            gap: 16px;
        }
        .filter-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }
        .filter-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #9b9b9b;
            margin: 0;
        }
        .filter-select {
            min-width: 170px;
            border-radius: 8px;
            border: 1px solid #e6e6e6;
            padding: 8px 12px;
            font-size: 14px;
            color: #222;
            background-color: #fafafa;
        }
        .filter-select:focus {
            border-color: #ff8d2f;
            box-shadow: 0 0 0 .15rem rgba(255, 141, 47, .15);
        }
        .filter-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #e6e6e6;
            background: #fafafa;
            color: #555;
            border-radius: 30px;
            padding: 9px 16px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            user-select: none;
            transition: all .15s ease;
        }
        .filter-toggle input {
            display: none;
        }
        .filter-toggle i {
            color: #bbb;
            font-size: 16px;
        }
        .filter-toggle.active {
            background: #e8f8ee;
            border-color: #34c759;
            color: #1f9d4d;
        }
        .filter-toggle.active i {
            color: #34c759;
        }
        .clear-filter {
            margin-left: auto;
            align-self: center;
            color: #ff5a5f;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
        }
        .clear-filter:hover {
            text-decoration: underline;
        }

        /* searchable select */
        .search-select {
            position: relative;
            min-width: 200px;
        }
        .ss-toggle {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 8px;
            border: 1px solid #e6e6e6;
            background: #fafafa;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 14px;
            color: #222;
            cursor: pointer;
            transition: border-color .15s ease, box-shadow .15s ease;
        }
        .ss-toggle:hover {
            border-color: #ff8d2f;
        }
        .search-select.open .ss-toggle {
            border-color: #ff8d2f;
            box-shadow: 0 0 0 .15rem rgba(255, 141, 47, .15);
        }
        .ss-value {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .ss-caret {
            transition: transform .2s ease;
            color: #999;
        }
        .search-select.open .ss-caret {
            transform: rotate(180deg);
        }
        .ss-menu {
            position: absolute;
            top: calc(100% + 6px);
            left: 0;
            right: 0;
            background: #fff;
            border: 1px solid #eee;
            border-radius: 10px;
            box-shadow: 0 10px 34px rgba(0, 0, 0, .14);
            z-index: 60;
            display: none;
            overflow: hidden;
        }
        .search-select.open .ss-menu {
            display: block;
        }
        .ss-search-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 12px;
            border-bottom: 1px solid #f0f0f0;
        }
        .ss-search-wrap i {
            color: #bbb;
        }
        .ss-search {
            flex: 1;
            border: none;
            outline: none;
            font-size: 14px;
            background: transparent;
        }
        .ss-options {
            list-style: none;
            margin: 0;
            padding: 6px;
            max-height: 240px;
            overflow: auto;
        }
        .ss-option {
            padding: 9px 10px;
            border-radius: 7px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            color: #333;
        }
        .ss-option:hover {
            background: #fff3e6;
        }
        .ss-option.selected {
            background: #fff3e6;
            color: #ff8d2f;
            font-weight: 600;
        }
        .ss-option.selected::after {
            content: '\2713';
            color: #ff8d2f;
            font-weight: 700;
        }
        .ss-empty {
            padding: 10px;
            color: #aaa;
            font-size: 13px;
            text-align: center;
        }
    </style>

    <!-- banner section starts -->
    <section class="product-banner-section">
        <div class="container">
            <div class="restaurant-box">
                <div class="restaurant-image">
                    @if($category->image)
                        <img class="img-fluid img" src="{{ asset($category->image) }}" alt="{{ $category->name }}">
                    @else
                        <img class="img-fluid img" src="{{ asset('front/assets/images/icons/brand13.png') }}" alt="{{ $category->name }}">
                    @endif
                </div>
                <div class="restaurant-details">
                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                        <div>
                            <h2 class="restaurant-name">{{ $category->name }}</h2>
                            <h4 class="restaurant-place">Browse all dishes in this category</h4>
                        </div>
                        <div class="restaurant-description">
                            <div class="categories-icon">
                                <a href="{{ route('index') }}">
                                    <i class="ri-arrow-left-line icon text-white"></i>
                                </a>
                                <a href="#!" class="like-btn animate inactive">
                                    <i class="ri-heart-3-fill fill-icon"></i>
                                    <i class="ri-heart-3-line text-white outline-icon"></i>
                                    <div class="effect-group">
                                        <span class="effect"></span>
                                        <span class="effect"></span>
                                        <span class="effect"></span>
                                        <span class="effect"></span>
                                        <span class="effect"></span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner section end -->

    <!-- foods grid starts -->
    <section class="section-b-space">
        <div class="container">
            <div class="title mt-4 mb-4">
                <h2>Foods in "{{ $category->name }}"</h2>
                <div class="loader-line"></div>
            </div>

            <!-- filter bar starts -->
            <div class="filter-section mb-4">
                <form method="GET" action="{{ route('category.foods') }}" class="filter-form">
                    <div class="filter-bar">
                        <!-- category (searchable) -->
                        <div class="filter-item">
                            <label class="filter-label"><i class="ri-restaurant-2-line"></i> Category</label>
                            <div class="search-select" data-name="category_id">
                                <button type="button" class="ss-toggle filter-select">
                                    <span class="ss-value">{{ $category->name }}</span>
                                    <i class="ri-arrow-down-s-line ss-caret"></i>
                                </button>
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <div class="ss-menu">
                                    <div class="ss-search-wrap">
                                        <i class="ri-search-line"></i>
                                        <input type="text" class="ss-search" placeholder="Search category...">
                                    </div>
                                    <ul class="ss-options">
                                        @foreach($categories as $cat)
                                            <li class="ss-option {{ $cat->id == $category->id ? 'selected' : '' }}"
                                                data-value="{{ $cat->id }}">{{ $cat->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- cuisine (searchable) -->
                        <div class="filter-item">
                            <label class="filter-label"><i class="ri-bowl-line"></i> Cuisine</label>
                            <div class="search-select" data-name="cuisine">
                                <button type="button" class="ss-toggle filter-select">
                                    <span class="ss-value">{{ ($cuisines->firstWhere('id', request('cuisine'))?->name) ?: 'All Cuisines' }}</span>
                                    <i class="ri-arrow-down-s-line ss-caret"></i>
                                </button>
                                <input type="hidden" name="cuisine" value="{{ request('cuisine') }}">
                                <div class="ss-menu">
                                    <div class="ss-search-wrap">
                                        <i class="ri-search-line"></i>
                                        <input type="text" class="ss-search" placeholder="Search cuisine...">
                                    </div>
                                    <ul class="ss-options">
                                        <li class="ss-option {{ !request('cuisine') ? 'selected' : '' }}" data-value="">All Cuisines</li>
                                        @foreach($cuisines as $c)
                                            <li class="ss-option {{ request('cuisine') == $c->id ? 'selected' : '' }}"
                                                data-value="{{ $c->id }}">{{ $c->name }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- pure veg toggle -->
                        <div class="filter-item">
                            <label class="filter-toggle {{ request('veg') ? 'active' : '' }}">
                                <input type="checkbox" name="veg" value="1" class="veg-check"
                                    {{ request('veg') ? 'checked' : '' }} onchange="this.form.submit()">
                                <i class="ri-radio-button-line"></i> Pure Veg
                            </label>
                        </div>

                        @if(request('veg') || request('cuisine'))
                            <a href="{{ route('category.foods', ['category_id' => $category->id]) }}" class="clear-filter">
                                <i class="ri-close-circle-line"></i> Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>
            <!-- filter bar end -->

            @if($foods->isEmpty())
                <div class="alert alert-info mb-4">
                    No foods are available in this category right now. Please check back later.
                </div>
            @else
                <div class="row g-4 ratio_square">
                    @foreach($foods as $food)
                        @php
                            $firstVariant = $food->variants->first();
                            if ($firstVariant) {
                                $salePrice = $firstVariant->sale_price;
                                $price = $firstVariant->price;
                            } else {
                                $salePrice = $food->discount_price;
                                $price = $food->base_price;
                            }
                            $displayPrice = $salePrice ?: $price;
                            $strikePrice = $salePrice ? $price : null;
                            $image = $food->image ? asset($food->image) : asset('front/assets/images/menu/13.jpg');
                            $menuUrl = $food->restaurant && $food->restaurant->restaurant_slug
                                ? route('menu.list', ['slug' => $food->restaurant->restaurant_slug])
                                : '#!';
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                            <div class="vertical-product-box">
                                <div class="vertical-product-box-img">
                                    <a href="{{ $menuUrl }}">
                                        <img class="product-img-top w-100 bg-img" src="{{ $image }}" alt="{{ $food->name }}" style="aspect-ratio:1/1;object-fit:cover;">
                                    </a>
                                    @if($strikePrice)
                                        <div class="offers">
                                            <h6>Save {{ $currencySymbol }}{{ number_format($price - $displayPrice, 2) }}</h6>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h4>{{ round((1 - $displayPrice / $price) * 100) }}% OFF</h4>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="vertical-product-body">
                                    <div class="d-flex align-items-center justify-content-between mt-sm-3 mt-2">
                                        <a href="{{ $menuUrl }}">
                                            <h4 class="vertical-product-title">{{ $food->name }}</h4>
                                        </a>
                                        <h6 class="rating-star">
                                            <span class="star"><i class="ri-star-s-fill"></i></span>4.0
                                        </h6>
                                    </div>
                                    <h5 class="product-items">{{ Str::limit($food->short_description ?: $food->description, 55) ?: 'Delicious ' . $category->name . ' dish' }}</h5>
                                    <div class="location-distance d-flex align-items-center justify-content-between pt-sm-3 pt-2">
                                        <h5 class="place">{{ $food->restaurant->restaurant_name ?? 'Restaurant' }}</h5>
                                        <div class="product-box-price">
                                            <h5 class="theme-color fw-semibold mb-0">
                                                {{ $currencySymbol }}{{ number_format($displayPrice, 2) }}
                                                @if($strikePrice)
                                                    <del class="text-muted fs-13 ms-1">{{ $currencySymbol }}{{ number_format($strikePrice, 2) }}</del>
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($foods->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $foods->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>
    <!-- foods grid end -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.search-select').forEach(function (ss) {
                var toggle = ss.querySelector('.ss-toggle');
                var menu = ss.querySelector('.ss-menu');
                var search = ss.querySelector('.ss-search');
                var options = ss.querySelectorAll('.ss-option');
                var hidden = ss.querySelector('input[type="hidden"]');
                var valueLabel = ss.querySelector('.ss-value');
                var form = ss.closest('form');

                toggle.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var isOpen = ss.classList.contains('open');
                    document.querySelectorAll('.search-select.open').forEach(function (o) {
                        o.classList.remove('open');
                    });
                    if (!isOpen) {
                        ss.classList.add('open');
                        search.value = '';
                        filterOptions('');
                        setTimeout(function () { search.focus(); }, 50);
                    }
                });

                function filterOptions(q) {
                    q = q.toLowerCase();
                    var anyVisible = false;
                    options.forEach(function (opt) {
                        var match = opt.textContent.toLowerCase().indexOf(q) !== -1;
                        opt.style.display = match ? '' : 'none';
                        if (match) anyVisible = true;
                    });
                    var empty = menu.querySelector('.ss-empty');
                    if (!anyVisible && !empty) {
                        empty = document.createElement('li');
                        empty.className = 'ss-empty';
                        empty.textContent = 'No results found';
                        menu.querySelector('.ss-options').appendChild(empty);
                    } else if (anyVisible && empty) {
                        empty.remove();
                    }
                }

                search.addEventListener('input', function () {
                    filterOptions(search.value);
                });
                search.addEventListener('click', function (e) { e.stopPropagation(); });

                options.forEach(function (opt) {
                    opt.addEventListener('click', function (e) {
                        e.stopPropagation();
                        options.forEach(function (o) { o.classList.remove('selected'); });
                        opt.classList.add('selected');
                        hidden.value = opt.getAttribute('data-value');
                        valueLabel.textContent = opt.textContent.trim();
                        ss.classList.remove('open');
                        if (form) form.submit();
                    });
                });
            });

            document.addEventListener('click', function () {
                document.querySelectorAll('.search-select.open').forEach(function (o) {
                    o.classList.remove('open');
                });
            });
        });
    </script>
@endsection
