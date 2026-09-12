@extends('layouts.front.main')

@section('skeleton')
    @include('layouts.front.skeleton')
@endsection

@section('content')
    <!-- home section start -->
    <section id="home" class="home-wrapper section-b-space overflow-hidden">
        <div class="background-effect">
            <div class="main-circle">
                <div class="main-circle circle-1">
                    <div class="main-circle circle-2"></div>
                </div>
            </div>
        </div>
        <div class="container text-center position-relative">
            <h1>Zomo</h1>
            <h2>Discover restaurants that deliver near you</h2>
            <div class="search-section">
                <form class="auth-form search-head" target="_blank">
                    <div class="form-group">
                        <div class="form-input mb-0">
                            <input type="search" class="form-control search" id="inputusername"
                                placeholder="Search for Restaurant">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                </form>
                <a class="btn theme-btn mt-0" href="#" role="button">Search</a>
            </div>
            <ul class="home-features-list d-md-flex d-none">
                <li>
                    <a href="{{ route('dining.out', ['location' => $location ?? 'Lucknow']) }}" class="home-features-box" id="dinningOut">
                        <img class="img-fluid icon" src="front/assets/images/svg/routing.svg" alt="routing">
                        <h6>Dinning Out</h6>
                    </a>
                </li>
    
                <li>
                    <div class="home-features-box " id="delivery">
                        <img class="img-fluid icon" src="front/assets/images/svg/truck.svg" alt="truck">
                        <h6>Delivery</h6>
                    </div>
                </li>
                 <li>
                    <div class="home-features-box night" id="nightlife">
                            <img class="img-fluid icon" src="front/assets/images/svg/3d-rotate.svg" alt="3d-rotate">
                            <h6>NightLife</h6>
                        </div>
                </li>
            </ul>
        </div>
    </section>
    <!-- home section end -->

    <!-- categories section starts -->
    <section id="home-categories" class="categories-section section-b-space">
        <img src="front/assets/images/scooter.png" class="scooter-img img-fluid d-md-inline-block d-none"
            alt="animation-scooter">
        <div class="container">
            <div class="title">
                <h2>Categories</h2>
                <div class="loader-line"></div>
                <div class="sub-title">
                    <p>
                        Browse out top categories here to discover different food cuision.
                    </p>
                </div>
            </div>
            <div class="theme-arrow">
                <div class="swiper categories-slider categories-style">
                    <div class="swiper-wrapper">
                        @forelse($categories as $category)
                            <div class="swiper-slide">
                                <a href="{{ route('category.foods', ['category_id' => $category->id]) }}" class="food-categories">
                                    @if($category->image)
                                        <img class="img-fluid categories-img" src="{{ asset($category->image) }}" alt="{{ $category->name }}">
                                    @else
                                        <img class="img-fluid categories-img" src="front/assets/images/product/p-1.png" alt="{{ $category->name }}">
                                    @endif
                                    <h4 class="dark-text">{{ $category->name }}</h4>
                                </a>
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <div class="food-categories">
                                    <img class="img-fluid categories-img" src="front/assets/images/product/p-1.png" alt="No categories">
                                    <h4 class="dark-text">No Categories</h4>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="swiper-button-next categories-next"></div>
                <div class="swiper-button-prev categories-prev"></div>
            </div>
        </div>
    </section>
    <!-- categories section end -->

    <!-- banner section starts -->
    <section id="home-banners" class="banner-section section-b-space">
        <div class="container">
            <div class="title">
                <h2>Today’s Deal</h2>
                <div class="loader-line"></div>
                <div class="sub-title">
                    <p>Take a benefit from our latest dining offers.</p>
                </div>
            </div>

            <div class="position-relative">
                <div class="swiper banner1-slider">
                    <div class="swiper-wrapper" id="deals-slider-wrapper">
                        @forelse($deals as $deal)
                            @php
                                $dealBanner = $deal->restaurant?->banner ? asset($deal->restaurant->banner) : ($deal->restaurant?->logo ? asset($deal->restaurant->logo) : asset('front/assets/images/banner/banner1.jpg'));
                                $discountDisplay = $deal->discount_type === 'percentage'
                                    ? rtrim(rtrim(number_format((float)$deal->discount_value, 2), '0'), '.') . '% OFF'
                                    : $currencySymbol . number_format((float)$deal->discount_value, 2) . ' OFF';
                                
                                $validityText = 'Always Active';
                                if ($deal->start_date && $deal->end_date) {
                                    $validityText = $deal->start_date->format('d M Y') . ' – ' . $deal->end_date->format('d M Y');
                                } elseif ($deal->start_date) {
                                    $validityText = 'From ' . $deal->start_date->format('d M Y');
                                } elseif ($deal->end_date) {
                                    $validityText = 'Valid till ' . $deal->end_date->format('d M Y');
                                }
                                $dealCity = $deal->restaurant?->city?->name ?? ($location ?? 'Lucknow');
                                $dealUrl = route('dining.out', ['location' => $dealCity, 'offers' => 1]);
                            @endphp

                            <div class="swiper-slide">
                                <div class="banner-part">
                                    <a href="{{ $dealUrl }}">
                                        <div class="banner-image-wrapper">
                                            <img class="img-fluid banner-img" src="{{ $dealBanner }}"
                                                alt="{{ $deal->title }}">
                                            @if($deal->restaurant?->city)
                                                <div class="banner-city-badge">
                                                    <i class="ri-map-pin-line"></i> {{ $deal->restaurant->city->name }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="banner-overlay">
                                            <div class="banner-caption">
                                                <h5 class="banner-title">{{ $deal->title }}</h5>
                                                <div class="banner-restaurant">
                                                    {{ $deal->restaurant?->restaurant_name ?? 'Restaurant' }}
                                                    @if($deal->restaurant?->city)
                                                        <span class="text-white-50"> • {{ $deal->restaurant->city->name }}</span>
                                                    @endif
                                                </div>
                                                <div class="banner-discount">
                                                    <span>{{ $discountDisplay }}</span>
                                                    @if($deal->coupon_code)
                                                        <span class="banner-coupon"><i class="ri-coupon-3-line"></i> {{ $deal->coupon_code }}</span>
                                                    @endif
                                                </div>
                                                <div class="banner-validity">
                                                    <i class="ri-calendar-line"></i> {{ $validityText }}
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="swiper-slide w-100">
                                <div class="banner-part text-center py-5">
                                    <h5 class="banner-title text-muted">No dining deals currently available for this location</h5>
                                    <p class="text-muted small mb-0">Check back later or explore our popular restaurants below.</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner section end -->

    <style>
        .banner-section .swiper {
            margin: 0 -15px;
        }
        .banner-section .swiper-slide {
            padding: 0 15px;
        }
        .banner-part {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .banner-part:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }
        .banner-image-wrapper {
            height: 240px;
            overflow: hidden;
            position: relative;
        }
        .banner-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .banner-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 16px 18px;
            background: linear-gradient(transparent, rgba(0,0,0,0.85) 55%);
            color: #fff;
        }
        .banner-caption {
            max-width: 100%;
        }
        .banner-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 4px;
            color: #fff;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-line-clamp: 1;
            overflow: hidden;
        }
        .banner-restaurant {
            font-size: 13px;
            opacity: 0.95;
            margin-bottom: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .banner-discount {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 6px;
        }
        .banner-discount span {
            background: #fc8019;
            color: #fff;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .banner-discount span.banner-coupon {
            background: rgba(255, 255, 255, 0.25);
            color: #fff;
            border: 1px dashed rgba(255, 255, 255, 0.7);
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .banner-validity {
            font-size: 11px;
            opacity: 0.85;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .banner-city-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(4px);
            color: #fff;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            z-index: 2;
            box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        }
        @media (max-width: 768px) {
            .banner-caption {
                max-width: 100%;
            }
            .banner-title {
                font-size: 15px;
            }
        }
    </style>

    <!-- brand section starts -->
    <section id="home-brands" class="brand-section section-b-space">
        <img class="img-fluid item-4" src="front/assets/images/svg/item4.svg" alt="item-4">
        <div class="container">
            <div class="title">
                <h2>Brand For You</h2>
                <div class="loader-line"></div>
                <div class="sub-title">
                    <p>
                        Browse out top brands here to discover different food cuision.
                    </p>
                </div>
            </div>
            <div class="theme-arrow">
                <div class="swiper brands-logo">
                    <div class="swiper-wrapper">
                        @forelse($brands as $brand)
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => $brand->slug]) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ $brand->logo ? asset($brand->logo) : asset('front/assets/images/icons/brand1.png') }}" alt="{{ $brand->name }}">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => $brand->slug]) }}">
                                        <h4>{{ $brand->name }}</h4>
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => 'la-pinoz']) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ asset('front/assets/images/icons/brand1.png') }}" alt="brand1">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => 'la-pinoz']) }}">
                                        <h4>La Pino’z</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => 'mcdonalds']) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ asset('front/assets/images/icons/brand2.png') }}" alt="brand2">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => 'mcdonalds']) }}">
                                        <h4>Mc'd</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => 'starbucks']) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ asset('front/assets/images/icons/brand3.png') }}" alt="brand3">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => 'starbucks']) }}">
                                        <h4>Starbucks</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => 'pizza-hut']) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ asset('front/assets/images/icons/brand4.png') }}" alt="brand4">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => 'pizza-hut']) }}">
                                        <h4>Pizza Hut</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => 'wendys']) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ asset('front/assets/images/icons/brand5.png') }}" alt="brand5">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => 'wendys']) }}">
                                        <h4>Wendy's</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => 'burger-king']) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ asset('front/assets/images/icons/brand6.png') }}" alt="brand6">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => 'burger-king']) }}">
                                        <h4>Burger King</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => 'subway']) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ asset('front/assets/images/icons/brand7.png') }}" alt="brand7">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => 'subway']) }}">
                                        <h4>Subway</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => 'dominos']) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ asset('front/assets/images/icons/brand8.png') }}" alt="brand8">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => 'dominos']) }}">
                                        <h4>Domino's</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => 'taco-bell']) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ asset('front/assets/images/icons/brand9.png') }}" alt="brand9">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => 'taco-bell']) }}">
                                        <h4>Taco Bell</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => 'chipotle']) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ asset('front/assets/images/icons/brand10.png') }}" alt="brand10">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => 'chipotle']) }}">
                                        <h4>Chipotle</h4>
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-slide">
                                <div class="brand-box">
                                    <a href="{{ route('menu.list', ['brand' => 'kfc']) }}" class="food-brands">
                                        <img class="img-fluid brand-img" src="{{ asset('front/assets/images/icons/brand11.png') }}" alt="brand11">
                                    </a>
                                    <a href="{{ route('menu.list', ['brand' => 'kfc']) }}">
                                        <h4>KFC</h4>
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
                <div class="swiper-button-next brand-next"></div>
                <div class="swiper-button-prev brand-prev"></div>
            </div>
        </div>
    </section>
    <!-- brand section end -->

    <!-- popular restaurant section starts -->
    <section id="home-popular" class="popular-restaurant banner-section section-b-space ratio3_2 overflow-hidden">
        <img class="img-fluid item-5" src="front/assets/images/svg/item5.svg" alt="item-5">
        <div class="container">
            <div class="title">
                <h2>Popular Restaurants</h2>
                <div class="loader-line"></div>
                <div class="sub-title">
                    <p>Find nearby popular Restaurants. Restaurants will display here.</p>
                </div>
            </div>
            <div class="restaurant-filter-bar d-flex align-items-center flex-wrap gap-2 mb-sm-4 mb-3">
                <button type="button" class="filter-chip" id="openFilterModal">
                    <i class="ri-equalizer-line"></i> Filters
                </button>
                <button type="button" class="filter-chip quick-filter" data-qf="open_now"><i class="ri-time-line"></i> Open Now</button>
                <button type="button" class="filter-chip quick-filter" data-qf="offers"><i class="ri-discount-percent-line"></i> Offers</button>
                <button type="button" class="filter-chip quick-filter" data-qf="rating"><i class="ri-star-line"></i> Rating: 4.5+</button>
                <button type="button" class="filter-chip quick-filter" data-qf="pet_friendly"><i class="ri-paw-line"></i> Pet friendly</button>
                <button type="button" class="filter-chip quick-filter" data-qf="outdoor_seating"><i class="ri-umbrella-line"></i> Outdoor seating</button>
                <button type="button" class="filter-chip quick-filter" data-qf="serves_alcohol"><i class="ri-cup-line"></i> Serves Alcohol</button>
            </div>
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

    <!-- nightlife section starts -->
    <section id="home-nightlife" class="nightlife-section section-b-space d-none">
        <div class="container">
            <div class="title">
                <h2><i class="ri-moon-line"></i> Nightlife Near You</h2>
                <div class="loader-line"></div>
                <div class="sub-title">
                    <p>Discover the best pubs, bars, and nightlife venues around you.</p>
                </div>
            </div>
            <div class="nightlife-filter-bar d-flex align-items-center flex-wrap gap-2 mb-sm-4 mb-3">
                <span class="nightlife-filter-label"><i class="ri-magic-fill"></i> Nightlife Filters</span>
                <span class="nightlife-filter-divider"></span>
                <button type="button" class="filter-chip nightlife-chip" data-qf="open_now"><i class="ri-time-line"></i><span class="chip-label">Open Now</span></button>
                <button type="button" class="filter-chip nightlife-chip" data-qf="serves_alcohol"><i class="ri-cup-line"></i><span class="chip-label">Serves Alcohol</span></button>
                <button type="button" class="filter-chip nightlife-chip" data-qf="pubs_bars"><i class="ri-store-2-line"></i><span class="chip-label">Pubs & Bars</span></button>
                <button type="button" class="filter-chip nightlife-chip" data-qf="happy_hours"><i class="ri-goblet-line"></i><span class="chip-label">Happy Hours</span></button>
                <button type="button" class="filter-chip nightlife-chip" data-qf="fine_dining"><i class="ri-restaurant-line"></i><span class="chip-label">Fine Dining</span></button>
            </div>
            <div class="row g-md-4 g-3" id="nightlife-list">
                @for ($i = 0; $i < 8; $i++)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="nightlife-card">
                            <div class="nightlife-card-img placeholder-glow">
                                <span class="w-100 d-block" style="height:180px;background:linear-gradient(135deg,#1a1a2e,#16213e);border-radius:12px 12px 0 0;"></span>
                            </div>
                            <div class="nightlife-card-body placeholder-glow">
                                <span class="placeholder col-7 mb-2"></span>
                                <span class="placeholder col-10 mb-2"></span>
                                <span class="placeholder col-5"></span>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
            <div class="text-center mt-4 d-none" id="nightlife-empty">
                <div class="nightlife-empty-state">
                    <i class="ri-moon-line"></i>
                    <h4>No nightlife venues found nearby</h4>
                    <p>Try adjusting your location or check back later for new venues.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- nightlife section end -->

    <style>
        /* ===== Nightlife Section ===== */
        .nightlife-section {
            background: linear-gradient(180deg, #0f0c29 0%, #1a1a2e 40%, #16213e 100%);
            padding-top: 50px;
            position: relative;
            overflow: hidden; 
        }
        .nightlife-section::before {
            content: '';
            position: absolute;
            top: -60%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .nightlife-section::after {
            content: '';
            position: absolute;
            bottom: -40%;
            right: -5%;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(236, 72, 153, 0.12) 0%, transparent 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .nightlife-section .title h2 {
            color: #fff;
            text-shadow: 0 0 20px rgba(139, 92, 246, 0.5);
        }
        .nightlife-section .title h2 i {
            color: #a78bfa;
        }
        .nightlife-section .title .loader-line {
            background: linear-gradient(90deg, #8b5cf6, #ec4899, #8b5cf6);
            background-size: 200% 100%;
            animation: nightlifeShimmer 2s ease-in-out infinite;
        }
        @keyframes nightlifeShimmer {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .nightlife-section .title .sub-title p {
            color: rgba(255,255,255,0.6);
        }

        /* Nightlife filter bar */
        .nightlife-filter-bar {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 18px;
            padding: 14px 16px;
            backdrop-filter: blur(14px);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.06), 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        .nightlife-filter-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            border-radius: 999px;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.3), rgba(236, 72, 153, 0.25));
            border: 1px solid rgba(139, 92, 246, 0.45);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.4px;
            white-space: nowrap;
            box-shadow: 0 4px 16px rgba(139, 92, 246, 0.25);
        }
        .nightlife-filter-label i {
            color: #f0abfc;
            font-size: 16px;
            animation: labelGlow 2.4s ease-in-out infinite;
        }
        @keyframes labelGlow {
            0%, 100% { text-shadow: 0 0 6px rgba(240, 171, 252, 0.6); transform: rotate(0deg); }
            50% { text-shadow: 0 0 16px rgba(240, 171, 252, 0.95); transform: rotate(-8deg) scale(1.08); }
        }
        .nightlife-filter-divider {
            width: 1px;
            height: 28px;
            background: linear-gradient(180deg, transparent, rgba(139, 92, 246, 0.5), transparent);
            margin: 0 4px;
            flex-shrink: 0;
        }

        /* Nightlife filter chips */
        .nightlife-filter-bar .nightlife-chip {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 15px 6px 6px;
            border-radius: 999px;
            border: 1px solid rgba(139, 92, 246, 0.3);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.09), rgba(255, 255, 255, 0.03));
            color: rgba(255, 255, 255, 0.88);
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            backdrop-filter: blur(6px);
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
        }
        .nightlife-filter-bar .nightlife-chip i {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            font-size: 14px;
            flex-shrink: 0;
            transition: all 0.3s ease;
            background: rgba(139, 92, 246, 0.18);
            color: #a78bfa;
        }
        .nightlife-chip[data-qf="open_now"] i { background: rgba(56, 189, 248, 0.18); color: #38bdf8; }
        .nightlife-chip[data-qf="serves_alcohol"] i { background: rgba(251, 191, 36, 0.18); color: #fbbf24; }
        .nightlife-chip[data-qf="pubs_bars"] i { background: rgba(52, 211, 153, 0.18); color: #34d399; }
        .nightlife-chip[data-qf="happy_hours"] i { background: rgba(244, 114, 182, 0.18); color: #f472b6; }
        .nightlife-chip[data-qf="fine_dining"] i { background: rgba(129, 140, 248, 0.18); color: #818cf8; }

        .nightlife-filter-bar .nightlife-chip:hover {
            transform: translateY(-3px);
            border-color: rgba(139, 92, 246, 0.75);
            background: rgba(139, 92, 246, 0.22);
            color: #fff;
            box-shadow: 0 8px 24px rgba(139, 92, 246, 0.4);
        }
        .nightlife-filter-bar .nightlife-chip:hover i {
            color: #fff;
            background: rgba(139, 92, 246, 0.45);
            transform: rotate(-8deg) scale(1.12);
        }
        .nightlife-filter-bar .nightlife-chip.active {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            border-color: transparent;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 10px 26px rgba(236, 72, 153, 0.45);
        }
        .nightlife-filter-bar .nightlife-chip.active i {
            background: rgba(255, 255, 255, 0.22);
            color: #fff;
        }
        .nightlife-filter-bar .nightlife-chip.active::after {
            content: '\eb7b';
            font-family: 'remixicon';
            font-size: 11px;
            width: 16px;
            height: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.25);
            flex-shrink: 0;
            animation: chipCheckIn 0.3s ease;
        }
        @keyframes chipCheckIn {
            from { transform: scale(0) rotate(-90deg); opacity: 0; }
            to { transform: scale(1) rotate(0deg); opacity: 1; }
        }

        /* Nightlife cards */
        .nightlife-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.35s ease;
            backdrop-filter: blur(10px);
        }
        .nightlife-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 35px rgba(139, 92, 246, 0.25);
            border-color: rgba(139, 92, 246, 0.3);
        }
        .nightlife-card-img {
            position: relative;
            overflow: hidden;
        }
        .nightlife-card-img img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .nightlife-card:hover .nightlife-card-img img {
            transform: scale(1.05);
        }
        .nightlife-card-rating {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 9px;
            border-radius: 8px;
            background: rgba(139, 92, 246, 0.9);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(4px);
        }
        .nightlife-card-status {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        .nightlife-card-status .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #fff;
        }
        .nightlife-card-status.open {
            background: linear-gradient(135deg, #10b981, #059669);
            animation: pulseOpen 1.8s infinite;
        }
        .nightlife-card-status.closed {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        .nightlife-card-type {
            position: absolute;
            bottom: 10px;
            left: 10px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 3px 8px;
            border-radius: 6px;
            background: rgba(0, 0, 0, 0.65);
            backdrop-filter: blur(4px);
            color: #e0d4fc;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .nightlife-card-body {
            padding: 14px 16px;
        }
        .nightlife-card-name {
            font-size: 16px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-decoration: none;
            transition: color 0.2s;
        }
        .nightlife-card-name:hover {
            color: #c4b5fd;
        }
        .nightlife-card-desc {
            font-size: 12px;
            color: rgba(255,255,255,0.5);
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 1;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .nightlife-card-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 6px;
        }
        .nightlife-card-meta .meta-item {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            color: rgba(255,255,255,0.55);
        }
        .nightlife-card-meta .meta-item i {
            color: #8b5cf6;
            font-size: 14px;
        }
        .nightlife-card-cost {
            margin-top: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #c4b5fd;
        }

        /* Nightlife empty state */
        .nightlife-empty-state {
            padding: 40px 20px;
            color: rgba(255,255,255,0.5);
        }
        .nightlife-empty-state i {
            font-size: 48px;
            color: rgba(139, 92, 246, 0.4);
            margin-bottom: 12px;
        }
        .nightlife-empty-state h4 {
            color: rgba(255,255,255,0.7);
            font-weight: 600;
        }
        .nightlife-empty-state p {
            color: rgba(255,255,255,0.4);
            font-size: 14px;
        }

        /* Stars on nightlife cards */
        .nightlife-stars {
            color: #facc15;
            font-size: 11px;
            letter-spacing: 1px;
        }

        @media (max-width: 768px) {
            .nightlife-section {
                padding-top: 30px;
            }
            .nightlife-card-name {
                font-size: 14px;
            }
        }
    </style>


    <section id="home-app" class="app-section">
        <div class="container">
            <div class="d-flex align-items-center">
                <div class="app-img">
                    <img class="img-fluid phone" src="front/assets/images/service-phone.png" alt="app-phone">
                </div>
                <div class="app-content">
                    <h2>Zomo App : Online & Mobile Ordering</h2>
                    <h5>
                        Get the app for free and place takeout orders online whenever you
                        want.
                    </h5>
                    <div class="app-buttons d-flex align-items-center gap-3">
                        <a href="https://www.apple.com/in/app-store/">
                            <img class="img-fluid app-btn" src="front/assets/images/svg/app-store.svg" alt="app-store">
                        </a>
                        <a href="https://play.google.com/store/apps">
                            <img class="img-fluid app-btn" src="front/assets/images/svg/google-play.svg" alt="google-play">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- app section end -->

    <style>
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

        /* Zomato-style restaurant filters */
        .restaurant-filter-bar {
            flex-wrap: wrap;
        }
        .restaurant-filter-bar .filter-bar-label {
            align-items: center;
            gap: 6px;
            font-weight: 700;
            font-size: 13px;
            color: rgba(var(--dark-text), 1);
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

        /* Active filter chips on the index page */
        #activeFilterChips .filter-chip {
            background: rgba(var(--theme-color), 0.12);
            border-color: rgba(var(--theme-color), 0.4);
            color: rgba(var(--theme-color2), 1);
        }
        #activeFilterChips .filter-chip .rm {
            margin-left: 6px;
            font-weight: 700;
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
    </style>

    <!-- Location modal (opened by .location-btn) -->
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

    <!-- Filters modal (Zomato style) -->
    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered filter-modal-dialog">
            <div class="modal-content filter-modal">
                <div class="modal-header filter-modal-header">
                    <h5 class="modal-title fw-semibold">Filters</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body filter-modal-body p-0">
                    <div class="row g-0">
                        <div class="col-4 filter-nav">
                            <button type="button" class="filter-nav-item active" data-panel="sort">Sort by <br><span class="nav-count" id="navSortCount"></span></button>
                            <button type="button" class="filter-nav-item" data-panel="cuisines">Cuisines <br> <span class="nav-count" id="navCuisineCount"></span></button>
                            <button type="button" class="filter-nav-item" data-panel="rating">Rating <br> <span class="nav-count" id="navRatingCount"></span></button>
                            <button type="button" class="filter-nav-item" data-panel="cost">Cost for two <br> <span class="nav-count" id="navCostCount"></span></button>
                            <button type="button" class="filter-nav-item" data-panel="more">More filters</button>
                        </div>
                        <div class="col-8 filter-panels p-3">
                            <!-- Sort by -->
                            <div class="filter-panel" id="panel-sort">
                                <label class="filter-option"><input type="radio" name="f_sort" value="popularity" checked> Popularity</label>
                                <label class="filter-option"><input type="radio" name="f_sort" value="rating_high"> Rating: High to Low</label>
                                <label class="filter-option"><input type="radio" name="f_sort" value="cost_low"> Cost: Low to High</label>
                                <label class="filter-option"><input type="radio" name="f_sort" value="cost_high"> Cost: High to Low</label>
                                <label class="filter-option"><input type="radio" name="f_sort" value="distance"> Distance</label>
                            </div>
                            <!-- Cuisines -->
                            <div class="filter-panel d-none" id="panel-cuisines">
                                <input type="search" id="cuisineSearch" class="form-control mb-2" placeholder="Search cuisines">
                                <div class="cuisine-list">
                                    @foreach ($cuisines as $c)
                                        <label class="filter-option cuisine-item">
                                            <input type="checkbox" class="cuisine-check" value="{{ $c->id }}"> {{ $c->name }}
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Rating -->
                            <div class="filter-panel d-none" id="panel-rating">
                                <label class="filter-option"><input type="radio" name="f_rating" value="4.5"> 4.5+</label>
                                <label class="filter-option"><input type="radio" name="f_rating" value="4.0"> 4.0+</label>
                                <label class="filter-option"><input type="radio" name="f_rating" value="3.5"> 3.5+</label>
                                <label class="filter-option"><input type="radio" name="f_rating" value="3.0"> 3.0+</label>
                            </div>
                            <!-- Cost for two -->
                            <div class="filter-panel d-none" id="panel-cost">
                                <div class="cost-range">
                                    <input type="range" id="minCost" min="0" max="2000" step="50" value="0">
                                    <input type="range" id="maxCost" min="0" max="2000" step="50" value="2000">
                                </div>
                                <div class="cost-range-label text-center fw-semibold mt-2">
                                    ₹<span id="minCostVal">0</span> – ₹<span id="maxCostVal">2000</span>
                                </div>
                            </div>
                            <!-- More filters -->
                            <div class="filter-panel d-none" id="panel-more">
                                <label class="filter-option"><input type="checkbox" name="f_more" value="credit_card"> Credit card</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="buffet"> Buffet</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="happy_hours"> Happy hours</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="serves_alcohol"> Serves Alcohol</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="open_now"> Open Now</label>
                                <label class="filter-option"><input type="checkbox" name="f_more" value="pubs_bars"> Pubs &amp; Bars</label>
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
            var $dealsWrapper = $('#deals-slider-wrapper');
            var baseUrl = '{{ url('/restaurants') }}';
            var menuUrl = '{{ route('menu.list') }}';
            var dealsUrl = '{{ route('public.dining-offers.location') }}';
            var assetBase = '{{ asset('') }}';
            var locationModal = new bootstrap.Modal(document.getElementById('location'));
            var RECENT_KEY = 'recent_locations';

            function escapeHtml(str) {
                if (str === null || str === undefined) return '';
                return String(str).replace(/[&<>"']/g, function (m) {
                    return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m];
                });
            }

            function dealSlideHtml(deal) {
                var cityName = (deal.restaurant && deal.restaurant.city) ? deal.restaurant.city : 'Lucknow';
                var dealUrl = deal.dining_out_url || ('{{ route('dining.out') }}?location=' + encodeURIComponent(cityName) + '&offers=1');
                var restName = deal.restaurant ? deal.restaurant.name : 'Restaurant';
                var cityBadge = cityName ? '<div class="banner-city-badge"><i class="ri-map-pin-line"></i> ' + escapeHtml(cityName) + '</div>' : '';
                var citySubtitle = cityName ? '<span class="text-white-50"> • ' + escapeHtml(cityName) + '</span>' : '';
                var couponHtml = deal.coupon_code ? '<span class="banner-coupon"><i class="ri-coupon-3-line"></i> ' + escapeHtml(deal.coupon_code) + '</span>' : '';

                return ''
                    + '<div class="swiper-slide">'
                    +   '<div class="banner-part">'
                    +     '<a href="' + dealUrl + '">'
                    +       '<div class="banner-image-wrapper">'
                    +         '<img class="img-fluid banner-img" src="' + escapeHtml(deal.banner_url) + '" alt="' + escapeHtml(deal.title) + '">'
                    +         cityBadge
                    +       '</div>'
                    +       '<div class="banner-overlay">'
                    +         '<div class="banner-caption">'
                    +           '<h5 class="banner-title">' + escapeHtml(deal.title) + '</h5>'
                    +           '<div class="banner-restaurant">' + escapeHtml(restName) + citySubtitle + '</div>'
                    +           '<div class="banner-discount">'
                    +             '<span>' + escapeHtml(deal.discount_display) + '</span>'
                    +             couponHtml
                    +           '</div>'
                    +           '<div class="banner-validity">'
                    +             '<i class="ri-calendar-line"></i> ' + escapeHtml(deal.validity)
                    +           '</div>'
                    +         '</div>'
                    +       '</div>'
                    +     '</a>'
                    +   '</div>'
                    + '</div>';
            }

            function loadDeals(params) {
                $.ajax({
                    url: dealsUrl,
                    data: params || {},
                    dataType: 'json',
                    success: function (res) {
                        var deals = (res && res.deals) ? res.deals : [];
                        if (deals.length > 0) {
                            var html = '';
                            $.each(deals, function (i, deal) {
                                html += dealSlideHtml(deal);
                            });
                            $dealsWrapper.html(html);
                        } else {
                            var locName = params && (params.location || '') ? ' for ' + escapeHtml(params.location) : '';
                            $dealsWrapper.html(
                                '<div class="swiper-slide w-100">'
                                +   '<div class="banner-part text-center py-5">'
                                +     '<h5 class="banner-title text-muted">No dining deals currently available' + locName + '</h5>'
                                +     '<p class="text-muted small mb-0">Check back later or explore our popular restaurants below.</p>'
                                +   '</div>'
                                + '</div>'
                            );
                        }
                        if (window.sliderOne && typeof window.sliderOne.update === 'function') {
                            window.sliderOne.update();
                        }
                    },
                    error: function () {
                        // Keep whatever is currently rendered
                    }
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
                var link = menuUrl + '?slug=' + r.slug;
                var img = assetBase + (r.logo ? r.logo : 'front/assets/images/product/vp-1.png');
                var place = r.city ? r.city : '';
                var dist = (r.distance_km !== null && r.distance_km !== undefined)
                    ? parseFloat(r.distance_km).toFixed(1) + ' km' : '—';
                var time = (r.estimated_delivery_time !== null && r.estimated_delivery_time !== undefined)
                    ? r.estimated_delivery_time + ' min' : '—';
                var desc = r.description ? r.description : 'Delicious food near you';
                var rating = (r.rating !== null && r.rating !== undefined)
                    ? '<div class="product-rating-badge"><i class="ri-star-fill"></i> ' + parseFloat(r.rating).toFixed(1) + '</div>'
                    : '';
                var cost = (r.cost_for_two !== null && r.cost_for_two !== undefined)
                    ? '₹' + parseFloat(r.cost_for_two).toFixed(0) + ' for two'
                    : '';

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
                        +         '<h5 class="place">' + escapeHtml(place) + '</h5>'
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
                        + 'No restaurants found for this location.</div></div>');
                    return;
                }
                var html = '';
                $.each(list, function (i, r) { html += cardHtml(r); });
                $list.html(html);
            }

            var state = null;

            // Active filter state (set via the Filters modal)
            var filters = {
                sort: 'popularity',
                cuisines: [],
                rating: null,
                min_cost: null,
                max_cost: null,
                offers: false,
                more: {}
            };
            var cuisineMap = @json($cuisines->pluck('name', 'id'));
            var moreLabels = {
                credit_card: 'Credit card', buffet: 'Buffet', happy_hours: 'Happy hours',
                serves_alcohol: 'Serves Alcohol', open_now: 'Open Now', pubs_bars: 'Pubs & Bars',
                fine_dining: 'Fine Dining', wifi: 'Wifi', outdoor_seating: 'Outdoor seating',
                cafes: 'Cafés', hygiene_rated: 'Hygiene Rated', online_bookings: 'Online bookings',
                pure_veg: 'Pure veg'
            };
            var sortLabels = {
                popularity: 'Popularity', rating_high: 'Rating: High to Low',
                cost_low: 'Cost: Low to High', cost_high: 'Cost: High to Low', distance: 'Distance'
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
                loadDeals(state);
            }

            function fetchByLocation(params) {
                state = params;
                locationModal.hide();
                loadRestaurants();
                loadDeals(state);
                if (params && params.location) {
                    $('#dinningOut').attr('href', '{{ route('dining.out') }}?location=' + encodeURIComponent(params.location));
                } else if (params && params.city_id) {
                    $('#dinningOut').attr('href', '{{ route('dining.out') }}?city_id=' + encodeURIComponent(params.city_id));
                }
            }

            // ============================================================
            // Nightlife section
            // ============================================================
            window.$nightlifeList = $('#nightlife-list');
            window.$nightlifeEmpty = $('#nightlife-empty');
            window.nightlifeState = null;
            window.nightlifeFilters = { open_now: false, serves_alcohol: false, pubs_bars: false, happy_hours: false, fine_dining: false };

            function nightlifeSkeletonCard() {
                return ''
                    + '<div class="col-xl-3 col-lg-4 col-md-6">'
                    +   '<div class="nightlife-card">'
                    +     '<div class="nightlife-card-img placeholder-glow">'
                    +       '<span class="w-100 d-block" style="height:180px;background:linear-gradient(135deg,#1a1a2e,#16213e);"></span>'
                    +     '</div>'
                    +     '<div class="nightlife-card-body placeholder-glow">'
                    +       '<span class="placeholder col-7 mb-2"></span>'
                    +       '<span class="placeholder col-10 mb-2"></span>'
                    +       '<span class="placeholder col-5"></span>'
                    +     '</div>'
                    +   '</div>'
                    + '</div>';
            }

            function showNightlifeSkeleton() {
                var html = '';
                for (var i = 0; i < 8; i++) { html += nightlifeSkeletonCard(); }
                $nightlifeList.html(html);
                $nightlifeEmpty.addClass('d-none');
            }

            function nightlifeCardHtml(r) {
                var link = menuUrl + '?slug=' + r.slug;
                var img = assetBase + (r.logo ? r.logo : 'front/assets/images/product/vp-1.png');
                var place = r.city ? r.city : '';
                var dist = (r.distance_km !== null && r.distance_km !== undefined)
                    ? parseFloat(r.distance_km).toFixed(1) + ' km' : '—';
                var time = (r.estimated_delivery_time !== null && r.estimated_delivery_time !== undefined)
                    ? r.estimated_delivery_time + ' min' : '—';
                var desc = r.description ? r.description : 'Great nightlife experience';
                var rating = (r.rating !== null && r.rating !== undefined)
                    ? '<div class="nightlife-card-rating"><i class="ri-star-fill"></i> ' + parseFloat(r.rating).toFixed(1) + '</div>'
                    : '';
                var cost = (r.cost_for_two !== null && r.cost_for_two !== undefined)
                    ? '₹' + parseFloat(r.cost_for_two).toFixed(0) + ' for two'
                    : '';

                var statusBadge = '';
                if (r.is_open) {
                    statusBadge = '<div class="nightlife-card-status open"><span class="dot"></span> Open now</div>';
                } else if (r.opening_time) {
                    statusBadge = '<div class="nightlife-card-status closed"><span class="dot"></span> Opens at ' + r.opening_time + '</div>';
                }

                var typeBadge = '<div class="nightlife-card-type"><i class="ri-moon-line"></i> Nightlife</div>';

                return ''
                    + '<div class="col-xl-3 col-lg-4 col-md-6">'
                    +   '<div class="nightlife-card">'
                    +     '<div class="nightlife-card-img">'
                    +       '<a href="' + link + '"><img src="' + img + '" alt="' + escapeHtml(r.name) + '"></a>'
                    +       rating
                    +       statusBadge
                    +       typeBadge
                    +     '</div>'
                    +     '<div class="nightlife-card-body">'
                    +       '<a href="' + link + '" class="nightlife-card-name">' + escapeHtml(r.name) + '</a>'
                    +       '<div class="nightlife-card-desc">' + escapeHtml(desc) + '</div>'
                    +       '<div class="nightlife-card-meta">'
                    +         '<span class="meta-item"><i class="ri-map-pin-line"></i> ' + escapeHtml(place) + '</span>'
                    +         '<span class="meta-item"><i class="ri-map-pin-distance-line"></i> ' + dist + '</span>'
                    +         '<span class="meta-item"><i class="ri-time-line"></i> ' + time + '</span>'
                    +       '</div>'
                    +       (cost ? '<div class="nightlife-card-cost">' + cost + '</div>' : '')
                    +     '</div>'
                    +   '</div>'
                    + '</div>';
            }

            function renderNightlife(list) {
                if (!list || !list.length) {
                    $nightlifeList.html('');
                    $nightlifeEmpty.removeClass('d-none');
                    return;
                }
                $nightlifeEmpty.addClass('d-none');
                var html = '';
                $.each(list, function (i, r) { html += nightlifeCardHtml(r); });
                $nightlifeList.html(html);
            }

            function loadNightlife() {
                showNightlifeSkeleton();
                var data = $.extend({}, nightlifeState || {});
                if (nightlifeFilters.open_now) data.open_now = 1;
                if (nightlifeFilters.serves_alcohol) data.serves_alcohol = 1;
                if (nightlifeFilters.pubs_bars) data.pubs_bars = 1;
                if (nightlifeFilters.happy_hours) data.happy_hours = 1;
                if (nightlifeFilters.fine_dining) data.fine_dining = 1;
                var url = '{{ route('public.restaurants.nightlife') }}';
                var hasLocParams = data.city_id || data.state_id || data.country_id || data.location;
                if (hasLocParams) {
                    url = '{{ route('public.restaurants.search-location') }}';
                    data.restaurant_type = 'nightlife';
                }
                $.ajax({
                    url: url,
                    data: data,
                    dataType: 'json',
                    success: function (res) { renderNightlife(res.restaurants || []); },
                    error: function () {
                        $nightlifeList.html('<div class="col-12"><div class="text-center py-5" style="color:rgba(255,255,255,0.5);">'
                            + '<i class="ri-error-warning-line" style="font-size:32px;"></i>'
                            + '<p class="mt-2">Unable to load nightlife venues. Please try again.</p></div></div>');
                    }
                });
            }

            function fetchNightlifeNearby(lat, lng) {
                nightlifeState = { lat: lat, lng: lng };
                loadNightlife();
            }

            function fetchNightlifeByLocation(params) {
                nightlifeState = params;
                loadNightlife();
            }

            // Sync the main location state to nightlife when location changes
            var origFetchNearby = fetchNearby;
            fetchNearby = function(lat, lng) {
                origFetchNearby(lat, lng);
                fetchNightlifeNearby(lat, lng);
            };
            var origFetchByLocation = fetchByLocation;
            fetchByLocation = function(params) {
                origFetchByLocation(params);
                fetchNightlifeByLocation(params);
            };

            // Nightlife filter chips
            $('.nightlife-chip').on('click', function () {
                var key = $(this).data('qf');
                nightlifeFilters[key] = !nightlifeFilters[key];
                $(this).toggleClass('active', nightlifeFilters[key]);
                if (nightlifeState) {
                    loadNightlife();
                } else {
                    showNightlifeSkeleton();
                }
            });

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
                fetchByLocation({ city_id: s.id });
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
                            var $box = $('#location_suggestions').empty();
                            var sugs = res.suggestions || [];
                            if (!sugs.length) {
                                $box.html('<div class="suggestion-empty">No matching location found.</div>');
                                return;
                            }
                            $.each(sugs, function (i, s) {
                                var sub = s.state ? s.state : 'City';
                                $box.append(
                                    '<a href="#!" class="recent-location suggestion-item" '
                                    + 'data-type="' + s.type + '" data-id="' + s.id + '" data-name="' + escapeHtml(s.name) + '">'
                                    +   '<div class="recant-address">'
                                    +     '<i class="ri-map-pin-line theme-color"></i>'
                                    +     '<div><h5>' + escapeHtml(s.name) + '</h5>'
                                    +     '<h6>' + escapeHtml(sub) + '</h6></div>'
                                    +   '</div>'
                                    + '</a>'
                                );
                            });
                        }
                    });
                }, 300);
            });

            $('#location_suggestions').on('click', '.suggestion-item', function () {
                var s = {
                    type: $(this).data('type'),
                    id: $(this).data('id'),
                    name: $(this).data('name'),
                    state: $(this).find('h6').text()
                };
                selectSuggestion(s);
            });

            $('#location_search_form').on('submit', function (e) {
                e.preventDefault();
                var first = $('#location_suggestions .suggestion-item').first();
                if (first.length) {
                    first.trigger('click');
                } else {
                    var q = $('#location_search_input').val().trim();
                    if (q) { fetchByLocation({ location: q }); }
                }
            });

            $('#recent_locations').on('click', '.recent-item', function () {
                var type = $(this).data('type');
                var id = $(this).data('id');
                var params = {};
                params[type + '_id'] = id;
                fetchByLocation(params);
            });

            // ---- Reverse geocode coordinates to a readable address ----
            function reverseGeocode(lat, lng, cb) {
                var url = 'https://nominatim.openstreetmap.org/reverse?format=json&zoom=18&addressdetails=1&lat=' + lat + '&lon=' + lng;
                $.ajax({
                    url: url,
                    dataType: 'json',
                    success: function (res) {
                        var addr = (res && res.address) ? res.address : {};
                        var display = (res && res.display_name) ? res.display_name : '';
                        if (!display) {
                            var parts = [addr.road, addr.city || addr.town || addr.village || addr.county, addr.state, addr.country]
                                .filter(Boolean);
                            display = parts.join(', ');
                        }
                        cb(display, addr);
                    },
                    error: function () {
                        cb('', {});
                    }
                });
            }

            function setCurrentLocationText(display) {
                if (display) {
                    $('#currentLocation').text(display);
                }
            }

            // ---- Use current location ----
            function useCurrentLocation() {
                if (!navigator.geolocation) { return; }
                navigator.geolocation.getCurrentPosition(function (position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    reverseGeocode(lat, lng, function (display, addr) {
                        setCurrentLocationText(display);
                        var cityName = addr.city || addr.town || addr.village || addr.county || '';
                        if (cityName) {
                            fetchByLocation({ location: cityName });
                        } else {
                            fetchNearby(lat, lng);
                        }
                    });
                }, function () {
                    alert('Could not get your location.');
                });
            }

            $('#use_current_location').on('click', function (e) {
                e.preventDefault();
                useCurrentLocation();
            });

            $('#location_save').on('click', function () {
                var q = $('#location_search_input').val().trim();
                if (q) { fetchByLocation({ location: q }); }
            });

            // ---- Filters modal (Zomato style) ----
            var $filterModal = $('#filterModal');

            $('.filter-nav-item').on('click', function () {
                $('.filter-nav-item').removeClass('active');
                $(this).addClass('active');
                var panel = $(this).data('panel');
                $('.filter-panel').addClass('d-none');
                $('#panel-' + panel).removeClass('d-none');
            });

            function filterCuisineItems() {
                var q = $('#cuisineSearch').val().toLowerCase();
                $('.cuisine-item').each(function () {
                    var name = $(this).text().toLowerCase();
                    $(this).toggle(name.indexOf(q) !== -1);
                });
                updateCuisineCount();
            }
            function updateCuisineCount() {
                var n = $('.cuisine-check:checked').length;
                $('#navCuisineCount').text(n ? n + (n === 1 ? ' cuisine selected' : ' cuisines selected') : '');
            }
            function updateSortCount() {
                $('#navSortCount').text(sortLabels[filters.sort] || '');
            }
            function updateRatingCount() {
                $('#navRatingCount').text(filters.rating ? 'Rating: ' + filters.rating + '+' : '');
            }
            function updateCostCount() {
                var lo = parseInt($('#minCost').val(), 10);
                var hi = parseInt($('#maxCost').val(), 10);
                if (lo > hi) { var t = lo; lo = hi; hi = t; }
                if (lo <= 0 && hi >= 2000) {
                    $('#navCostCount').text('');
                } else {
                    $('#navCostCount').text('₹' + lo + ' – ₹' + hi);
                }
            }
            $('#cuisineSearch').on('input', filterCuisineItems);
            $('.cuisine-check').on('change', filterCuisineItems);
            $('input[name="f_sort"]').on('change', function () {
                filters.sort = $(this).val();
                updateSortCount();
            });
            $('input[name="f_rating"]').on('change', function () {
                var r = $('input[name="f_rating"]:checked').val();
                filters.rating = r ? parseFloat(r) : null;
                updateRatingCount();
            });
            $('#minCost, #maxCost').on('input', function () {
                var lo = parseInt($('#minCost').val(), 10);
                var hi = parseInt($('#maxCost').val(), 10);
                if (lo > hi) {
                    if (this.id === 'minCost') { $('#maxCost').val(lo); }
                    else { $('#minCost').val(hi); }
                }
                syncCostLabels();
                filters.min_cost = parseInt($('#minCost').val(), 10);
                filters.max_cost = parseInt($('#maxCost').val(), 10);
                if (filters.min_cost <= 0) { filters.min_cost = null; }
                if (filters.max_cost >= 2000) { filters.max_cost = null; }
                updateCostCount();
            });

            function syncCostLabels() {
                var min = parseInt($('#minCost').val(), 10);
                var max = parseInt($('#maxCost').val(), 10);
                if (min > max) { var t = min; min = max; max = t; }
                $('#minCostVal').text(min);
                $('#maxCostVal').text(max);
            }
            $('#minCost, #maxCost').on('input', syncCostLabels);

            $filterModal.on('show.bs.modal', function () {
                $('input[name="f_sort"][value="' + filters.sort + '"]').prop('checked', true);
                if (filters.rating) {
                    $('input[name="f_rating"][value="' + filters.rating + '"]').prop('checked', true);
                } else {
                    $('input[name="f_rating"]').prop('checked', false);
                }
                $('.cuisine-check').each(function () {
                    $(this).prop('checked', filters.cuisines.indexOf($(this).val()) !== -1);
                });
                $('input[name="f_more"]').each(function () {
                    $(this).prop('checked', !!filters.more[$(this).val()]);
                });
                filterCuisineItems();
                updateSortCount();
                updateRatingCount();
                updateCostCount();
                if (filters.min_cost !== null) { $('#minCost').val(filters.min_cost); }
                if (filters.max_cost !== null) { $('#maxCost').val(filters.max_cost); }
                syncCostLabels();
                $('.filter-nav-item').removeClass('active').first().addClass('active');
                $('.filter-panel').addClass('d-none');
                $('#panel-sort').removeClass('d-none');
            });

            $('#applyFiltersBtn').on('click', function () {
                filters.sort = $('input[name="f_sort"]:checked').val();
                updateSortCount();
                var r = $('input[name="f_rating"]:checked').val();
                filters.rating = r ? parseFloat(r) : null;
                updateRatingCount();
                filters.cuisines = $('.cuisine-check:checked').map(function () { return $(this).val(); }).get();
                filters.more = {};
                $('input[name="f_more"]:checked').each(function () { filters.more[$(this).val()] = true; });
                filters.min_cost = parseInt($('#minCost').val(), 10);
                filters.max_cost = parseInt($('#maxCost').val(), 10);
                if (filters.min_cost <= 0) { filters.min_cost = null; }
                if (filters.max_cost >= 2000) { filters.max_cost = null; }
                updateCostCount();
                $filterModal.modal('hide');
                renderActiveChips();
                if (state) {
                    loadRestaurants();
                } else {
                    showSkeleton();
                    locationModal.show();
                }
            });

            $('#clearAllFilters').on('click', function () {
                filters = { sort: 'popularity', cuisines: [], rating: null, min_cost: null, max_cost: null, offers: false, more: {} };
                $('input[name="f_sort"][value="popularity"]').prop('checked', true);
                $('input[name="f_rating"]').prop('checked', false);
                $('.cuisine-check, input[name="f_more"]').prop('checked', false);
                $('#minCost').val(0);
                $('#maxCost').val(2000);
                syncCostLabels();
                syncQuickChips();
                updateSortCount();
                updateRatingCount();
                updateCostCount();
                updateCuisineCount();
                renderActiveChips();
            });

            // ---- Quick filter chips (original bar) ----
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
                renderActiveChips();
                if (state) {
                    loadRestaurants();
                } else {
                    showSkeleton();
                    locationModal.show();
                }
            });

            $('#openFilterModal').on('click', function () { $filterModal.modal('show'); });

            // ---- Active filter chips (kept hidden; only syncs quick chips) ----
            function renderActiveChips() {
                syncQuickChips();
            }
            renderActiveChips();

            // Auto-fetch location on first load
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var lat = position.coords.latitude;
                    var lng = position.coords.longitude;
                    reverseGeocode(lat, lng, function (display) {
                        setCurrentLocationText(display);
                    });
                    fetchNearby(lat, lng);
                }, function () {
                    showSkeleton();
                    locationModal.show();
                });
            } else {
                showSkeleton();
                locationModal.show();
            }

            renderRecent();
        });

        // ============================================================
        // Home feature tabs (Dinning Out / Delivery / NightLife)
        // ============================================================
        var $featCats = $('#home-categories'),
            $featBanners = $('#home-banners'),
            $featBrands = $('#home-brands'),
            $featPopular = $('#home-popular'),
            $featNight = $('#home-nightlife'),
            $featApp = $('#home-app');

        function applyFeature(feature) {
            $('.home-features-box').removeClass('active');

            // Add the animated "running" underline to the active box (like the
            // section loader-line), remove it from the others. The gradient
            // background fills the whole box (li) for the active feature.
            $('.home-features-box').each(function () {
                var isActive = $(this).attr('id') === feature;
                $(this).toggleClass('active', isActive);
                $(this).closest('li').toggleClass('active', isActive);
                $(this).find('.feature-active-line').remove();
                if (isActive) {
                    $(this).append('<span class="feature-active-line"></span>');
                }
            });

            var showAll = feature === 'delivery';
            var showNightlife = feature === 'nightlife';

            // Dinning Out: hide categories & brand, keep banners + popular
            $featCats.toggleClass('d-none', !showAll);
            $featBrands.toggleClass('d-none', !showAll);

            // NightLife: only the nightlife section stays visible
            $featBanners.toggleClass('d-none', showNightlife);
            $featPopular.toggleClass('d-none', showNightlife);
            $featApp.toggleClass('d-none', showNightlife);
            $featNight.toggleClass('d-none', !showNightlife);

            // Load nightlife data when tab is first shown
            if (showNightlife && window.$nightlifeList && window.$nightlifeList.children().length === 0) {
                if (window.nightlifeState) {
                    loadNightlife();
                }
            }

            setTimeout(function () {
                if (window.sliderTwo) sliderTwo.update();
                if (window.sliderFour) sliderFour.update();
            }, 60);
        }

        $('#dinningOut, #delivery, #nightlife').on('click', function () {
            applyFeature($(this).attr('id'));
        });

        // Dinning Out is active by default on page load
        applyFeature('dinningOut');

        // Show already-selected filter names on initial load
        updateSortCount();
        updateRatingCount();
        updateCostCount();
        updateCuisineCount();
    </script>
@endpush

