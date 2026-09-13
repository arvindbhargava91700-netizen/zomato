    <!-- footer section starts -->
    <footer class="footer-section section-t-space">
        <div class="subscribe-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="subscribe-part">
                            <h5>
                                Don't pass up our fantastic discounts. email offers from all
                                of our best eateries
                            </h5>
                            <div class="position-relative w-100">
                                <input type="email" class="form-control subscribe-form-control"
                                    placeholder="Enter your Email">
                                <a href="#" class="btn theme-btn subscribe-btn mt-0">Subscribe Now</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="main-footer">
                <div class="row g-3">
                    <div class="col-xl-4 col-lg-12">
                        <div class="footer-logo-part">
                            <img class="img-fluid logo" src="{{ asset($companyLogo) }}" alt="{{ $companyName }}">
                            <p>
                                Welcome to our online order website! Here, you can browse our
                                wide selection of products and place orders from the comfort
                                of your own home.
                            </p>
                            <div class="social-media-part">
                                <ul class="social-icon">
                                    @if(!empty($socialLinks['facebook']))
                                        <li>
                                            <a href="{{ $socialLinks['facebook'] }}" target="_blank" rel="noopener noreferrer" title="Facebook">
                                                <i class="ri-facebook-fill icon"></i>
                                            </a>
                                        </li>
                                    @endif
                                    @if(!empty($socialLinks['twitter']))
                                        <li>
                                            <a href="{{ $socialLinks['twitter'] }}" target="_blank" rel="noopener noreferrer" title="Twitter / X">
                                                <i class="ri-twitter-fill icon"></i>
                                            </a>
                                        </li>
                                    @endif
                                    @if(!empty($socialLinks['linkedin']))
                                        <li>
                                            <a href="{{ $socialLinks['linkedin'] }}" target="_blank" rel="noopener noreferrer" title="LinkedIn">
                                                <i class="ri-linkedin-fill icon"></i>
                                            </a>
                                        </li>
                                    @endif
                                    @if(!empty($socialLinks['instagram']))
                                        <li>
                                            <a href="{{ $socialLinks['instagram'] }}" target="_blank" rel="noopener noreferrer" title="Instagram">
                                                <i class="ri-instagram-fill icon"></i>
                                            </a>
                                        </li>
                                    @endif
                                    @if(!empty($socialLinks['youtube']))
                                        <li>
                                            <a href="{{ $socialLinks['youtube'] }}" target="_blank" rel="noopener noreferrer" title="YouTube">
                                                <i class="ri-youtube-fill icon"></i>
                                            </a>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-8">
                        <div class="row g-3">
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                                <div>
                                    <h5 class="footer-title">Company</h5>
                                    <ul class="content">
                                        <li>
                                            <a class="nav-links" href="about.html">
                                                <h6>About us</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="contact.html">
                                                <h6>Contact us</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="offer.html">
                                                <h6>Offer</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="faq.html">
                                                <h6>FAQs</h6>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                                <div>
                                    <h5 class="footer-title">Account</h5>
                                    <ul class="content">
                                        <li>
                                            <a class="nav-links" href="my-order.html">
                                                <h6>My orders</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="wishlist.html">
                                                <h6>Wishlist</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="checkout.html">
                                                <h6>Shopping Cart</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="saved-address.html">
                                                <h6>Saved Address</h6>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                                <div>
                                    <h5 class="footer-title">Useful links</h5>
                                    <ul class="content">
                                        <li>
                                            <a class="nav-links" href="{{ route('blog.list') }}">
                                                <h6>Blogs</h6>
                                            </a>
                                        </li>

                                        <li>
                                            <a class="nav-links" href="signin.html">
                                                <h6>Login</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="signup.html">
                                                <h6>Register</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="profile.html">
                                                <h6>Profile</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="setting.html">
                                                <h6>Settings</h6>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                                <div>
                                    <h5 class="footer-title">Top Brands</h5>
                                    <ul class="content">
                                        <li>
                                            <a class="nav-links" href="menu-listing.html">
                                                <h6>PizzaBoy</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="menu-listing.html">
                                                <h6>Saladish</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="menu-listing.html">
                                                <h6>IcePops</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="menu-listing.html">
                                                <h6>Maxican Hoy</h6>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="nav-links" href="menu-listing.html">
                                                <h6>La Foodie</h6>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bottom-footer-part">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <h6>@ Copyright 2024 {{ $companyName }}. All rights Reserved.</h6>
                    <img class="img-fluid cards" src="{{ asset('front/assets/images/icons/footer-card.png') }}" alt="card">
                </div>
            </div>
        </div>
    </footer>
    <!-- footer section end -->

    <!-- mobile fix menu start -->
    <div class="mobile-menu d-md-none d-block mobile-cart">
        <ul>
            <li class="active">
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
            <li>
                <a href="{{ route('profile') }}" class="menu-box">
                    <i class="ri-user-line"></i>
                    <span>Profile</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- mobile fix menu end -->

    <!-- theme btn start -->
    <div class="theme-btns">
        <button type="button" class="btntheme mode-change-button">
            <i id="themeIcon" class="ri-moon-line icon mode-icon"></i>
            <span class="text-value">Dark</span>
        </button>
        <button type="button" id="rtl-btn" class="btntheme rtlBtnEl" style="display:none;">
            <i class="ri-repeat-line icon"></i>
            <span class="text-value">RTL</span>
        </button>
    </div>
    <!-- theme btn end -->

    <!-- tap to top start -->
    <button class="scroll scroll-to-top">
        <i class="ri-arrow-up-s-line arrow"></i>
    </button>
    <!-- tap to top end -->

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

    <!-- responsive space -->
    <div class="responsive-space"></div>
    <!-- responsive space -->

