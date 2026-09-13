@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Saved Card</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="index.html"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        Saved Card
                    </li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- card section starts -->
    <section class="profile-section section-b-space">
        <div class="container">
            <div class="row g-3">
                @include('manage.front.partials.profile-sidebar')
                <div class="col-lg-9">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="cards-content">
                        <div class="title">
                            <div class="loader-line"></div>
                            <h3>Saved Card</h3>
                        </div>
                        <div class="row g-4">
                            @foreach($cards as $card)
                                @include('manage.front.partials.card', ['color' => 'color-' . (($loop->index % 3) + 1)])
                                @include('manage.front.partials.card-edit-modal')
                                @include('manage.front.partials.card-delete-modal')
                            @endforeach
                            <div class="col-xl-4 col-lg-6 col-sm-6 col-12">
                                <div class="add-card">
                                    <div data-bs-toggle="modal" data-bs-target="#add-card" class="card-details">
                                        <div>
                                            <i class="ri-add-line add-icon"></i>
                                            <h5 class="fw-normal dark-text">Add New Card</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- profile section end -->

      <!-- mobile fix menu start -->
    <div class="mobile-menu d-md-none d-block mobile-cart">
        <ul>
            <li>
                <a href="{{ route('index') }}" class="menu-box">
                    <i class="ri-home-4-line"></i>
                    <span>Home</span>
                </a>
            </li>
            <li>
                <a href="{{ route('wish.list') }}" class="menu-box">
                    <i class="ri-heart-3-line"></i>
                    <span>Wishlist</span>
                </a>
            </li>
            <li>
                <a href="{{ route('checkout') }}" class="menu-box">
                    <i class="ri-shopping-cart-2-line"></i>
                    <span>Cart</span>
                </a>
            </li>
            <li class="active">
                <a href="{{ route('profile') }}" class="menu-box">
                    <i class="ri-user-line"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- mobile fix menu end -->

    <!-- add-card modal starts -->
    <div class="modal address-details-modal fade" id="add-card" tabindex="-1" aria-labelledby="addCard"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addCard">Add New Card</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('card.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="cardname" class="form-label">Card Holder Name</label>
                                <input type="text" class="form-control" id="cardname" name="holder_name"
                                    placeholder="Enter your last name">
                            </div>
                            <div class="col-md-12">
                                <label for="cardnumber" class="form-label">Card Number</label>
                                <input type="number" class="form-control" id="cardnumber" name="card_number"
                                    placeholder="Enter card number">
                            </div>
                            <div class="col-md-8">
                                <label for="carddate" class="form-label">Exp. Date</label>
                                <input type="date" class="form-control" id="carddate" name="exp_date">
                            </div>
                            <div class="col-md-4">
                                <label for="cardcvv" class="form-label">CVV</label>
                                <input type="number" class="form-control" id="cardcvv" name="cvv"
                                    placeholder="Enter your cvv">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="#" class="btn gray-btn mt-0" data-bs-dismiss="modal">CANCEL</a>
                        <button type="submit" class="btn theme-btn mt-0">Add Card</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- add-card modal end -->

    <!-- logout modal starts -->
    <div class="modal address-details-modal fade" id="log-out" tabindex="-1" aria-labelledby="login-out"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="login-out">Logging Outhh</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you Sure, You are logging out</p>
                </div>
                <div class="modal-footer">
                    <a href="saved-card.html" class="btn gray-btn mt-0" data-bs-dismiss="modal">CANCEL</a>
                    <a href="index.html" class="btn theme-btn mt-0">Log Out</a>
                </div>
            </div>
        </div>
    </div>
    <!-- logout modal end -->

@endsection