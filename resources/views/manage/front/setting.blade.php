@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Setting</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="index.html"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Setting</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- profile section starts -->
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
                    <div class="setting-content">
                        <div class="title">
                            <h3>Setting</h3>
                        </div>
                        <form id="notification-form" method="POST" action="{{ route('profile.notification-update') }}">
                            @csrf
                            <ul class="notification-setting">
                                <li>
                                    <div class="notification pt-0">
                                        <h6 class="fw-normal dark-text">Offer Update</h6>
                                        <div class="switch-btn">
                                            <input type="checkbox" name="notify_offer"
                                                {{ auth()->user()->notify_offer ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification">
                                        <h6 class="fw-normal dark-text">Order Update</h6>
                                        <div class="switch-btn">
                                            <input type="checkbox" name="notify_order"
                                                {{ auth()->user()->notify_order ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="notification pb-0">
                                        <h6 class="fw-normal dark-text">New Update</h6>
                                        <div class="switch-btn">
                                            <input type="checkbox" name="notify_new"
                                                {{ auth()->user()->notify_new ? 'checked' : '' }}>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </form>
                        <div class="delete-account">
                            <h3 class="fw-medium dark-text">Delete Your Account</h3>
                            <p class="content-color">
                                Hi <span class="dark-text fw-medium">{{ auth()->user()->name }}</span>,
                            </p>
                            <p class="content-color">
                                We are sorry to here you would like to delete your account.
                            </p>
                            <h6 class="dark-text fw-medium mt-sm-3 mt-2 mb-2">Note :</h6>
                            <p class="content-color">
                                Deleting your account will permanently remove your profile,
                                personal settings, and all other associated information. once
                                your account is deleted, you will be logged out and will be
                                unable to log back in.
                            </p>
                            <p class="content-color mt-2">
                                If you understand and agree to the above statement, and would
                                still like to delete your account, than click below
                            </p>
                            <a href="#delete-account" class="btn theme-btn delete-btn mt-3"
                                data-bs-toggle="modal" data-bs-target="#delete-account">Delete Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- profile section end -->

    <!-- logout modal starts -->
    <div class="modal address-details-modal fade" id="log-out" tabindex="-1" aria-labelledby="login-out"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="login-out">Logging Out</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you Sure, You are logging out</p>
                </div>
                <div class="modal-footer">
                    <a href="index.html" class="btn gray-btn mt-0" data-bs-dismiss="modal">CANCEL</a>
                    <a href="index.html" class="btn theme-btn mt-0">Log Out</a>
                </div>
            </div>
        </div>
    </div>
    <!-- logout modal end -->

    <!-- delete account modal starts -->
    <div class="modal address-details-modal fade" id="delete-account" tabindex="-1" aria-labelledby="deleteAccount"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteAccount">Delete Account</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete your account? This action cannot be undone and you will be
                        logged out.</p>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn gray-btn mt-0" data-bs-dismiss="modal">CANCEL</a>
                    <form method="POST" action="{{ route('profile.delete-account') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn theme-outline mt-0">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- delete account modal end -->

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('notification-form');
            if (form) {
                form.querySelectorAll('input[type="checkbox"]').forEach(function (el) {
                    el.addEventListener('change', function () {
                        form.submit();
                    });
                });
            }
        });
    </script>
@endsection