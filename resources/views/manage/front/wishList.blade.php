@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Wishlist</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="{{ url('/') }}"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Wishlist</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- popular restaurant section starts -->
    <section class="restaurant-list section-b-space">
        <div class="container">
            @if ($wishlists->isEmpty())
                <div class="row">
                    <div class="col-12 text-center py-5">
                        <i class="ri-heart-line" style="font-size: 3rem;"></i>
                        <h4 class="mt-3">Your wishlist is empty</h4>
                        <p class="text-muted">Browse restaurants and save your favourite items here.</p>
                        <a href="{{ route('public.restaurants.index') }}" class="btn btn-solid">Explore Restaurants</a>
                    </div>
                </div>
            @else
                <div class="row g-4 ratio2_3">
                    @foreach ($wishlists as $item)
                        @php
                            $food = $item->food;
                            $restaurant = $food?->restaurant ?? $item->restaurant;
                            $image = $food && $food->image
                                ? asset($food->image)
                                : ($restaurant && $restaurant->logo ? asset($restaurant->logo) : asset('front/assets/images/product/vp-1.png'));
                            $title = $food ? $food->name : ($restaurant ? $restaurant->restaurant_name : 'Item');
                            $rating = $restaurant && $restaurant->reviews_avg_restaurant_rating
                                ? number_format($restaurant->reviews_avg_restaurant_rating, 1)
                                : null;
                            $place = $restaurant && $restaurant->city ? $restaurant->city->name : ($restaurant->address ?? '');
                            $time = $restaurant && $restaurant->estimated_delivery_time ? $restaurant->estimated_delivery_time . ' min' : null;
                            $link = $restaurant ? route('menu.list', ['slug' => $restaurant->restaurant_slug]) : '#';
                            $hasDiscount = $food && $food->discount_price && $food->base_price > $food->discount_price;
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-sm-6 trash">
                            <div class="vertical-product-box">
                                <div class="vertical-product-box-img">
                                    <a href="{{ $link }}">
                                        <img class="product-img-top w-100 bg-img" src="{{ $image }}"
                                            alt="{{ $title }}">
                                    </a>
                                    @if ($hasDiscount)
                                        @php
                                            $off = round((1 - $food->discount_price / $food->base_price) * 100);
                                            $save = number_format($food->base_price - $food->discount_price, 2);
                                        @endphp
                                        <div class="offers">
                                            <h6>upto ${{ $save }}</h6>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <h4>{{ $off }}% OFF</h4>
                                            </div>
                                        </div>
                                    @endif
                                    <a href="#delete" data-bs-toggle="modal" data-id="{{ $item->id }}"
                                        class="wishlist-close">
                                        <i class="ri-close-line"></i>
                                    </a>
                                </div>
                                <div class="vertical-product-body">
                                    <div class="d-flex align-items-center justify-content-between mt-sm-3 mt-2">
                                        <a href="{{ $link }}">
                                            <h4 class="vertical-product-title">{{ $title }}</h4>
                                        </a>
                                        @if ($rating)
                                            <h6 class="rating-star">
                                                <span class="star"><i class="ri-star-s-fill"></i></span>{{ $rating }}
                                            </h6>
                                        @endif
                                    </div>
                                    <h5 class="product-items">
                                        {{ $food && $food->short_description ? \Illuminate\Support\Str::limit($food->short_description, 60) : 'Saved for later' }}
                                    </h5>
                                    <div
                                        class="location-distance d-flex align-items-center justify-content-between pt-sm-3 pt-2">
                                        <h5 class="place">{{ $place }}</h5>
                                        <ul class="distance">
                                            @if ($time)
                                                <li><i class="ri-time-fill icon"></i> {{ $time }}</li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
    <!-- popular restaurant section end -->

    <!-- delete modal starts -->
    <div class="modal fade" id="delete" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="ri-delete-bin-6-line" style="font-size: 2.5rem;"></i>
                    <h4 class="mt-3">Remove from wishlist?</h4>
                    <p class="text-muted">This item will be removed from your saved list.</p>
                    <form id="deleteForm" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-solid">Remove</button>
                        <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Cancel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- delete modal end -->

    @push('scripts')
        <script>
            document.querySelectorAll('.wishlist-close').forEach(function (el) {
                el.addEventListener('click', function () {
                    var id = el.getAttribute('data-id');
                    document.getElementById('deleteForm').action = "{{ url('/wish-list') }}/" + id;
                });
            });
        </script>
    @endpush
@endsection
