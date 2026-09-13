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
                    <li class="breadcrumb-item active" aria-current="page">
                        Collections
                    </li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <section class="section-b-space pt-5 bg-white">
        <div class="container">
            <div class="title text-start mb-4">
                <h2 class="text-dark fw-bold mb-1" style="font-size: 32px;">Collections</h2>
                <div class="loader-line" style="margin-left: 0;"></div>
                <div class="sub-title text-start">
                    <p class="text-muted" style="font-size: 16px;">Explore curated lists of top restaurants, cafes, pubs, and bars in your city, based on trends.</p>
                </div>
            </div>

            <div class="row g-4">
                @forelse($collections as $collection)
                    <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                        <a href="{{ route('collections.show', ['nightlife' => $collection->slug]) }}" class="collection-card" style="display: block; position: relative; border-radius: 8px; overflow: hidden; height: 250px; text-decoration: none; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                            <div class="collection-img-wrap" style="width: 100%; height: 100%; position: relative;">
                                <img src="{{ $collection->banner ? asset($collection->banner) : asset('front/assets/images/banner/banner1.jpg') }}" alt="{{ $collection->title }}" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                                <div class="collection-overlay" style="position: absolute; bottom: 0; left: 0; right: 0; height: 100%; background: linear-gradient(0deg, rgba(0,0,0,0.9) 0%, rgba(0,0,0,0.4) 40%, rgba(0,0,0,0.05) 100%);"></div>
                            </div>
                            <div class="collection-content" style="position: absolute; bottom: 0; left: 0; right: 0; padding: 15px; color: #fff;">
                                <h6 style="color: #fff; margin-bottom: 2px; font-size: 16px; font-weight: 500;">{{ $collection->title }}</h6>
                                <span style="font-size: 13px; display: flex; align-items: center;">{{ $collection->restaurants_count }} Places <i class="ri-arrow-right-s-fill ms-1"></i></span>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <h4 class="text-white-50"><i class="ri-moon-clear-line"></i> No collections available at the moment.</h4>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <style>
        .collection-card:hover .collection-img-wrap img {
            transform: scale(1.05);
        }
    </style>
@endsection
