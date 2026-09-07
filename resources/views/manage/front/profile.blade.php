@extends('layouts.front.main')
@section('content')
    <!-- page head section starts -->
    <section class="page-head-section">
        <div class="container page-heading">
            <h2 class="h3 mb-3 text-white text-center">Profile</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb flex-lg-nowrap justify-content-center justify-content-lg-star">
                    <li class="breadcrumb-item">
                        <a href="index.html"><i class="ri-home-line"></i>Home</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
            </nav>
        </div>
    </section>
    <!-- page head section end -->

    <!-- profile section starts -->
    <section class="profile-section section-b-space">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row g-3">
                @include('manage.front.partials.profile-sidebar')
                <div class="col-lg-9">
                    <div class="change-profile-content">
                        <div class="title">
                            <div class="loader-line"></div>
                            <h3>Change Profile</h3>
                        </div>
                        <ul class="profile-details-list">
                            <li>
                                <div class="profile-content">
                                    <div class="d-flex align-items-center gap-sm-2 gap-1">
                                        <i class="ri-user-3-fill"></i>
                                        <span>Name :</span>
                                    </div>
                                    <h6>{{ auth()->user()->name }}</h6>
                                </div>
                                <a href="#name" class="btn theme-outline" data-bs-toggle="modal" data-bs-target="#name">Edit</a>
                            </li>
                            <li>
                                <div class="profile-content">
                                    <div class="d-flex align-items-center gap-sm-2 gap-1">
                                        <i class="ri-mail-fill"></i>
                                        <span>Email :</span>
                                    </div>
                                    <h6>{{ auth()->user()->email }}</h6>
                                </div>
                                <a href="#email" class="btn theme-outline" data-bs-toggle="modal" data-bs-target="#email">Change</a>
                            </li>
                            <li>
                                <div class="profile-content">
                                    <div class="d-flex align-items-center gap-sm-2 gap-1">
                                        <i class="ri-phone-fill"></i>
                                        <span>Phone Number :</span>
                                    </div>
                                    <h6>{{ auth()->user()->phone }}</h6>
                                </div>
                                <a href="#number" class="btn theme-outline mt-0" data-bs-toggle="modal" data-bs-target="#number">Change</a>
                            </li>
                            <li>
                                <div class="profile-content">
                                    <div class="d-flex align-items-center gap-sm-2 gap-1">
                                        <i class="ri-lock-2-fill"></i>
                                        <span>Password :</span>
                                    </div>
                                    <h6>********</h6>
                                </div>
                                <a href="#password" class="btn theme-outline mt-0" data-bs-toggle="modal" data-bs-target="#password">Change</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- profile section end -->

    <!-- location offcanvas start -->
    <div class="modal fade location-modal" id="location" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h5 class="fw-semibold">Select a Location</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="search-section">
                        <form class="form_search" role="form">
                            <input type="search" placeholder="Search Location" class="nav-search nav-search-field">
                        </form>
                    </div>
                    <a href="#!" class="current-location">
                        <div class="current-address">
                            <i class="ri-focus-3-line focus"></i>
                            <div>
                                <h5>Use current-location</h5>
                                <h6>Wellington St., Ottawa, Ontario, Canada</h6>
                            </div>
                        </div>
                        <i class="ri-arrow-right-s-line arrow"></i>
                    </a>
                    <h5 class="mt-sm-3 mt-2 fw-medium recent-title dark-text">
                        Recent Location
                    </h5>
                    <a href="#!" class="recent-location">
                        <div class="recant-address">
                            <i class="ri-map-pin-line theme-color"></i>
                            <div>
                                <h5>Bayshore</h5>
                                <h6>kingston St., Ottawa, Ontario, Canada</h6>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn gray-btn" data-bs-dismiss="modal">Close</a>
                    <a href="#" class="btn theme-btn mt-0" data-bs-dismiss="modal">Save</a>
                </div>
            </div>
        </div>
    </div>
    <!-- location offcanvas end -->

    <!-- edit name modal starts -->
    <div class="modal profile-modal fade" id="name" aria-hidden="true" aria-labelledby="exampleModalToggleName"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.update-name') }}">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalToggleName">Name</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputName" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="inputName"
                                name="name" value="{{ old('name', auth()->user()->name) }}"
                                placeholder="Enter your name">
                            @error('name') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn theme-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- edit name modal end -->

    <!-- edit email modal starts -->
    <div class="modal profile-modal fade" id="email" aria-hidden="true" aria-labelledby="exampleModalToggleEmail"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.update-email') }}">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalToggleEmail">Email</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputEmail" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="inputEmail"
                                name="email" value="{{ old('email', auth()->user()->email) }}"
                                placeholder="Enter your email">
                            @error('email') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn theme-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- edit email modal end -->

    <!-- edit phone number modal starts -->
    <div class="modal profile-modal fade" id="number" aria-hidden="true" aria-labelledby="exampleModalToggleCall"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.update-phone') }}">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalToggleCall">
                            Phone Number
                        </h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputNumber" class="form-label">Phone Number</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="inputNumber"
                                name="phone" value="{{ old('phone', auth()->user()->phone) }}"
                                placeholder="Enter your number">
                            @error('phone') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn theme-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- edit phone number modal end -->

    <!-- edit password number modal starts -->
    <div class="modal profile-modal fade" id="password" aria-hidden="true" aria-labelledby="exampleModalTogglePass"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.update-password') }}">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalTogglePass">Password</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputcurrentPassword" class="form-label">
                                Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                id="inputcurrentPassword" name="current_password"
                                placeholder="Enter your current password">
                            @error('current_password') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mt-2">
                            <label for="inputnewPassword" class="form-label">
                                New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="inputnewPassword" name="password"
                                placeholder="Enter your new password">
                            @error('password') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-group mt-2">
                            <label for="inputconfirmPassword" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="inputconfirmPassword"
                                name="password_confirmation"
                                placeholder="Enter your confirm password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn theme-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- edit password number modal end -->

    <!-- change profile picture modal starts -->
    <div class="modal profile-modal fade" id="change-profile-pic" aria-hidden="true"
        aria-labelledby="exampleModalToggleProfilePic" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('profile.update-image') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalToggleProfilePic">Change Profile Picture</h1>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="inputProfileImage" class="form-label">Choose Profile Picture</label>
                            <input type="file"
                                class="form-control @error('profile_image') is-invalid @enderror"
                                id="inputProfileImage" name="profile_image" accept="image/*">
                            @error('profile_image') <div class="text-danger mt-1 small">{{ $message }}</div> @enderror
                        </div>
                        <div class="profile-preview mt-3 text-center" id="profilePreview" style="display:none;">
                            <img id="profilePreviewImg" class="img-fluid rounded-circle"
                                style="max-width:150px;" alt="preview">
                            <div class="mt-2">
                                <button type="button" class="btn theme-outline" id="removeProfileImage">Remove</button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn theme-btn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- change profile picture modal end -->

    <!-- logout modal starts -->
    <div class="modal address-details-modal fade" id="log-out" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Logging Out</h1>
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

    @if(session('modal'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var el = document.getElementById('{{ session('modal') }}');
                if (el) {
                    new bootstrap.Modal(el).show();
                }
            });
        </script>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var input = document.getElementById('inputProfileImage');
            var preview = document.getElementById('profilePreview');
            var previewImg = document.getElementById('profilePreviewImg');
            var removeBtn = document.getElementById('removeProfileImage');
            if (input && preview && previewImg && removeBtn) {
                input.addEventListener('change', function () {
                    var file = this.files && this.files[0];
                    if (file) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            previewImg.src = e.target.result;
                            preview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.style.display = 'none';
                        previewImg.src = '';
                    }
                });
                removeBtn.addEventListener('click', function () {
                    input.value = '';
                    preview.style.display = 'none';
                    previewImg.src = '';
                });
            }
        });
    </script>

@endsection