@extends('layouts.front.main')

@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Explore Restaurants</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ route('index') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Restaurants
                    </li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <section class="section-b-space pt-5 bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <p class="text-muted">Order delicious food from the best approved restaurants near you</p>

                <form action="{{ route('public.restaurants.index') }}" method="GET" id="restaurantSearchForm" class="mx-auto mt-4" style="max-width: 650px;">
                    <div class="search-box d-flex align-items-center" style="background: #ffffff; border-radius: 50px; padding: 6px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid #f1f1f1;">
                        <i class="ri-search-line ms-3 fs-5 text-muted"></i>
                        <input type="text" name="search" class="form-control border-0 shadow-none bg-transparent ms-2 text-dark" placeholder="Search restaurant or cuisine..." value="{{ request('search') }}" style="height: 48px; font-size: 15px; font-weight: 500;">
                        <button type="submit" class="btn theme-btn m-0 rounded-pill" id="searchSubmitBtn" style="padding: 0 40px; height: 48px; display: flex; align-items: center; justify-content: center; min-width: 130px;">
                            <span class="btn-text">Search</span>
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true" id="searchSpinner"></span>
                        </button>
                    </div>
                </form>
            </div>

            <div class="row g-4">
                @forelse($restaurants as $restaurant)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                        <a href="{{ route('menu.list', ['slug' => $restaurant->restaurant_slug]) }}" class="collection-card" style="display: block; position: relative; border-radius: 8px; overflow: hidden; height: 250px; text-decoration: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                            <div class="collection-img-wrap" style="width: 100%; height: 100%; position: relative;">
                                @if($restaurant->banner)
                                    <img src="{{ asset($restaurant->banner) }}" alt="Banner" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                @else
                                    <div class="bg-danger d-flex align-items-center justify-content-center text-white" style="width: 100%; height: 100%; background-color: #cb202d !important; transition: transform 0.3s ease;">
                                        <i class="ri-restaurant-2-fill display-5"></i>
                                    </div>
                                @endif
                                <div class="collection-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; height: 100%; background: linear-gradient(0deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 40%, rgba(0,0,0,0.05) 100%);"></div>
                            </div>
                            <div class="collection-content" style="position: absolute; bottom: 0; left: 0; right: 0; padding: 15px; color: #fff;">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    @if($restaurant->logo)
                                        <img src="{{ asset($restaurant->logo) }}" alt="Logo" class="rounded-circle border border-white" style="width: 35px; height: 35px; object-fit: cover; z-index: 1;">
                                    @endif
                                    <h6 style="color: #fff; margin-bottom: 0; font-size: 16px; font-weight: 500;">{{ $restaurant->restaurant_name }}</h6>
                                </div>
                                <div class="d-flex align-items-center gap-2 mt-2">
                                    @if($restaurant->is_pure_veg)
                                        <span class="badge bg-success fs-10" style="padding: 3px 6px;">Pure Veg</span>
                                    @endif
                                    <span style="font-size: 12px; color: #ddd;"><i class="ri-time-line me-1"></i>{{ $restaurant->estimated_delivery_time }}m</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 text-muted">
                        <i class="ri-inbox-line display-5 d-block mb-2 text-secondary"></i>
                        No restaurants available right now. Please check back later.
                    </div>
                @endforelse
            </div>

            @if($restaurants->hasPages())
                <div class="d-flex justify-content-center mt-5">
                    {{ $restaurants->links() }}
                </div>
            @endif
        </div>
    </section>

    <style>
        .collection-card:hover .collection-img-wrap img,
        .collection-card:hover .collection-img-wrap div.bg-danger {
            transform: scale(1.05);
        }
    </style>

    <script>
        document.getElementById('restaurantSearchForm').addEventListener('submit', function() {
            var btnText = document.querySelector('.btn-text');
            var spinner = document.getElementById('searchSpinner');
            var submitBtn = document.getElementById('searchSubmitBtn');
            
            btnText.classList.add('d-none');
            spinner.classList.remove('d-none');
            submitBtn.setAttribute('disabled', 'disabled');
        });
    </script>
@endsection
