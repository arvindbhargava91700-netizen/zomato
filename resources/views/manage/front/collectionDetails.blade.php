@extends('layouts.front.main')

@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Collections</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('collections.index') }}">Collections</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        {{ $collection->title }}
                    </li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <section class="collection-page-header pt-5 pb-2 bg-white">
        <div class="container">

            <!-- Collection Banner -->
            <div class="collection-hero-banner position-relative" style="height: 320px; border-radius: 12px; overflow: hidden; background-image: url('{{ $collection->banner ? asset($collection->banner) : asset('front/assets/images/banner/banner1.jpg') }}'); background-size: cover; background-position: center;">
                
                <!-- Left Gradient Overlay -->
                <div class="collection-hero-overlay" style="position: absolute; top: 0; left: 0; bottom: 0; width: 60%; background: linear-gradient(to right, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.6) 60%, transparent 100%); padding: 40px; display: flex; flex-direction: column; justify-content: flex-end;">
                    <p class="text-white-50 text-uppercase mb-2" style="font-size: 13px; letter-spacing: 0.5px; font-weight: 500;">Zomato Collections</p>
                    <h1 class="text-white fw-bold mb-3" style="font-size: 40px; letter-spacing: -0.5px;">{{ $collection->title }}</h1>
                    <p class="text-white-50 mb-4" style="font-size: 15px; max-width: 480px; line-height: 1.6;">{{ $collection->description ?? 'We\'ve curated the best places to help you build a picture-perfect feed. After all, if it isn\'t caught on camera, did it happen at all?' }}</p>
                    <div class="text-white">
                        <span style="font-size: 14px;">{{ $collection->restaurants_count }} Places</span>
                    </div>
                </div>

                <!-- Right Action Buttons -->
                <div class="position-absolute d-flex gap-2" style="top: 20px; right: 20px;">
                    <button class="btn border-0 text-white d-flex align-items-center" style="background-color: rgba(30,30,30,0.7); border-radius: 20px; padding: 6px 16px; font-size: 14px; backdrop-filter: blur(4px);"><i class="ri-add-line me-1" style="font-size: 16px;"></i> Save Collection</button>
                    <button class="btn border-0 text-white d-flex align-items-center justify-content-center" style="background-color: rgba(30,30,30,0.7); border-radius: 50%; width: 34px; height: 34px; padding: 0; backdrop-filter: blur(4px);"><i class="ri-link" style="font-size: 16px;"></i></button>
                </div>
            </div>
        </div>
    </section>

    <!-- Collection Restaurants -->
    <section class="collection-restaurants section-b-space bg-white pt-4">
        <div class="container">
            <div class="row g-md-4 g-4">
                @forelse($restaurants as $restaurant)
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="restaurant-grid-card">
                            <a href="{{ url('menu-listing') }}?slug={{ $restaurant->restaurant_slug }}" class="d-block position-relative mb-2" style="border-radius: 16px; overflow: hidden; height: 210px;">
                                <img src="{{ $restaurant->logo ? asset($restaurant->logo) : asset('front/assets/images/product/vp-1.png') }}" alt="{{ $restaurant->restaurant_name }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;" class="hover-zoom">
                                
                                <!-- Offer Badge -->
                                <div class="position-absolute" style="bottom: 0; left: 0; right: 0; background: linear-gradient(to right, #256fef 0%, rgba(37,111,239,0.8) 40%, transparent 100%); color: white; padding: 6px 12px; font-size: 13px; font-weight: 700; display: flex; align-items: center;">
                                    <div style="background: white; color: #256fef; border-radius: 50%; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; margin-right: 6px;">
                                        <i class="ri-percent-line" style="font-size: 10px;"></i>
                                    </div>
                                    Flat 20% OFF
                                </div>
                            </a>
                            
                            <div class="restaurant-card-info mt-2 px-1">
                                <a href="{{ url('menu-listing') }}?slug={{ $restaurant->restaurant_slug }}" class="text-dark fw-bold d-block text-truncate mb-1" style="font-size: 19px; text-decoration: none;">
                                    {{ $restaurant->restaurant_name }}
                                </a>
                                
                                <div class="d-flex align-items-center mb-1">
                                    <div style="background: #24963f; color: #fff; padding: 1px 6px; border-radius: 6px; font-size: 12px; font-weight: 700; display: inline-flex; align-items: center; gap: 2px;">
                                        {{ $restaurant->rating ? number_format($restaurant->rating, 1) : '4.0' }} <i class="ri-star-fill" style="font-size: 10px;"></i>
                                    </div>
                                    <span class="text-muted ms-2" style="font-size: 12px; font-weight: 500; letter-spacing: 0.5px;">DINING</span>
                                </div>
                                
                                <div class="text-muted text-truncate mb-1" style="font-size: 14.5px;">
                                    {{ Str::limit(strip_tags($restaurant->description ?? 'North Indian, Chinese, Fast Food, Beverages'), 40) }}
                                </div>
                                
                                <div class="text-muted text-truncate" style="font-size: 14.5px;">
                                    {{ $restaurant->city->name ?? 'Lucknow' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h4 class="text-muted"><i class="ri-store-2-line mb-3 d-block" style="font-size: 40px;"></i> No places found in this collection.</h4>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $restaurants->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </section>

    <style>
        .hover-zoom:hover {
            transform: scale(1.05);
        }
    </style>
@endsection
