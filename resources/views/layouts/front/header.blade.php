   
   
 
   
   <!-- Header section start -->
    <header>
        <div class="container">
            <nav class="navbar navbar-expand-lg p-0">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#offcanvasNavbar">
                    <span class="navbar-toggler-icon">
                        <i class="ri-menu-line"></i>
                    </span>
                </button>
                <a href="{{route('index')}}">
                    <img class="img-fluid logo" src="{{ asset($companyLogo) }}" alt="{{ $companyName }}">
                </a>
                <a target="_blank" href="#!" data-bs-toggle="modal" data-bs-target="#location"
                    class="btn btn-sm theme-btn location-btn mt-0 ms-3 d-flex align-content-center gap-1">
                    <i class="ri-map-pin-line"></i> Location
                </a>
                <div class="nav-option order-md-2">
                    @auth
                    <!-- Theme-Matched Wallet Widget -->
                    <div class="profile-part dropdown-button wallet-header-part order-md-2 d-none d-sm-flex align-items-center me-2" style="cursor: pointer;" onclick="window.location.href='{{ route('wallet') }}'">
                        <div class="d-flex align-items-center justify-content-center text-white me-2 flex-shrink-0" style="width: 36px; height: 36px; border-radius: 100%; background: linear-gradient(to right, rgba(var(--theme-color), 1), rgba(var(--theme-color2), 1)); box-shadow: 0 4px 10px rgba(var(--theme-color), 0.25);">
                            <i class="ri-wallet-3-line" style="font-size: 16px;"></i>
                        </div>
                        <div class="text-start">
                            <h6 class="fw-normal mb-0" style="font-size: 11px; color:#fff; line-height: 1.1;">Wallet</h6>
                            <h5 class="fw-medium theme-color mb-0" style="font-size: 13.5px; line-height: 1.2;">{{ $currencySymbol }}{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</h5>
                        </div>

                        <div class="onhover-box onhover-sm p-3 shadow-sm border-0" style="width: 290px;" onclick="event.stopPropagation();">
                            <div class="d-flex align-items-center justify-content-between pb-2 mb-2" style="border-bottom: 1px solid rgba(var(--dashed-line), 1);">
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-wallet-3-fill theme-color fs-18"></i>
                                    <h6 class="fw-semibold mb-0" style="color: rgba(var(--dark-text), 1); font-size: 13px;">My Wallet</h6>
                                </div>
                                <span class="badge" style="background-color: rgba(var(--theme-color), 0.12); color: rgba(var(--theme-color), 1); font-size: 10.5px; padding: 4px 8px; border-radius: 20px;">Active</span>
                            </div>

                            <div class="p-3 text-white mb-2" style="background: linear-gradient(to right, rgba(var(--theme-color), 1), rgba(var(--theme-color2), 1)); border-radius: 10px; box-shadow: 0 4px 12px rgba(var(--theme-color), 0.25);">
                                <div class="text-white-50 fw-normal mb-1" style="font-size: 11px;">Available Balance</div>
                                <div class="fw-bold text-white" style="font-size: 20px; line-height: 1.2;">{{ $currencySymbol }}{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</div>
                                <div class="d-flex justify-content-between align-items-center mt-2 pt-2" style="border-top: 1px solid rgba(255, 255, 255, 0.25); font-size: 10.5px;">
                                    <span class="text-white-50"><i class="ri-shield-check-line me-1"></i>1-Click Checkout</span>
                                    <span class="badge bg-white theme-color fw-bold px-2 py-1" style="border-radius: 12px;">Instant</span>
                                </div>
                            </div>

                            @php
                                $recentUserWalletTxns = auth()->user()->walletTransactions()->latest()->take(3)->get();
                            @endphp

                            @if($recentUserWalletTxns->count() > 0)
                                <div class="fw-semibold text-muted text-uppercase mb-2" style="font-size: 10.5px;">Recent Activity</div>
                                <div class="d-flex flex-column gap-2 mb-2">
                                    @foreach($recentUserWalletTxns as $uwtx)
                                        <div class="p-2 rounded d-flex align-items-center justify-content-between" style="background-color: rgba(var(--white), 1); border: 1px solid rgba(var(--dashed-line), 1);">
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="{{ $uwtx->type === 'credit' ? 'ri-arrow-down-circle-fill text-success' : 'ri-arrow-up-circle-fill text-danger' }}" style="font-size: 16px;"></i>
                                                <div class="text-start">
                                                    <h6 class="fw-medium mb-0" style="color: rgba(var(--dark-text), 1); font-size: 11.5px; max-width: 130px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                        {{ $uwtx->description ?: ucfirst(str_replace('_', ' ', $uwtx->source)) }}
                                                    </h6>
                                                    <small class="text-muted" style="font-size: 9.5px;">{{ $uwtx->created_at ? $uwtx->created_at->diffForHumans() : '' }}</small>
                                                </div>
                                            </div>
                                            <span class="fw-bold {{ $uwtx->type === 'credit' ? 'text-success' : 'text-danger' }}" style="font-size: 11.5px;">
                                                {{ $uwtx->type === 'credit' ? '+' : '-' }}{{ $currencySymbol }}{{ number_format($uwtx->amount, 2) }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <div class="button-group d-flex flex-column gap-1 mt-2">
                                <a href="{{ route('wallet') }}" class="btn btn-sm theme-btn w-100 d-block text-center fw-semibold">
                                    <i class="ri-wallet-3-line me-1"></i> Manage Wallet &amp; Top-Up
                                </a>
                            </div>
                        </div>
                    </div>
                    @endauth

                    <div class="dropdown-button">
                        <div class="cart-button">
                            <span id="cart-count">0</span>
                            <i class="ri-shopping-cart-line text-white cart-bag"></i>
                        </div>
                        <div class="onhover-box">
                            <ul class="cart-list" id="header-cart-list"></ul>
                            <div class="price-box">
                                <h5>Total :</h5>
                                <h4 class="theme-color fw-semibold" id="header-cart-total">{{ $currencySymbol }}0.00</h4>
                            </div>
                            <div class="button-group">
                                <a href="{{ route('checkout') }}" class="btn btn-sm theme-btn w-100 d-block rounded-2">View
                                    Cart</a>
                            </div>
                        </div>
                    </div>
                    @auth
                    <div class="profile-part dropdown-button order-md-2">
                        <img class="img-fluid profile-pic" src="{{ auth()->user()->profile_image ? asset(auth()->user()->profile_image) : asset('front/assets/images/icons/p5.png') }}" alt="profile">
                        <div>
                            <h6 class="fw-normal">Hi, {{ Auth::user()->name }}</h6>
                            <h5 class="fw-medium">My Account</h5>
                        </div>
                        <div class="onhover-box onhover-sm">
                            <ul class="menu-list">
                                <li>
                                    <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('wallet') }}">
                                        <span class="d-flex align-items-center"><i class="ri-wallet-3-line me-2 text-success"></i>My Wallet</span>
                                        <span class="badge bg-soft-success text-success fw-bold">{{ $currencySymbol }}{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                                        <i class="ri-user-3-line me-2 text-muted"></i>Profile
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('my.orders') }}">
                                        <i class="ri-shopping-bag-3-line me-2 text-muted"></i>My Orders
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('help') }}">
                                        <i class="ri-customer-service-2-line me-2 text-muted"></i>Support
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('saved.address') }}">
                                        <i class="ri-map-pin-line me-2 text-muted"></i>Saved Address
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('saved.card') }}">
                                        <i class="ri-bank-card-line me-2 text-muted"></i>Saved Cards
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('setting') }}">
                                        <i class="ri-settings-3-line me-2 text-muted"></i>Settings
                                    </a>
                                </li>
                            </ul>
                            <div class="bottom-btn">
                                <a href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"  class="theme-color fw-medium d-flex"><i
                                        class="ri-login-box-line me-2"></i>Logout</a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                            </div>
                        </div>
                    </div>
                    @endauth
                    @guest
                        <div class="profile-part dropdown-button order-md-2">
                            <img class="img-fluid profile-pic"
                                src="{{ asset('front/assets/images/icons/p5.png') }}"
                                alt="profile">

                            <div>
                                <h6 class="fw-normal">Welcome</h6>
                                <h5 class="fw-medium">Login / Sign Up</h5>
                            </div>

                            <div class="onhover-box onhover-sm">
                                <ul class="menu-list">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('login') }}">
                                            <i class="ri-login-box-line me-2"></i>Login
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('register') }}">
                                            <i class="ri-user-add-line me-2"></i>Sign Up
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('vendor.register') }}">
                                            <i class="ri-restaurant-2-line me-2"></i>Become a Vendor
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('delivery-partner.register') }}">
                                            <i class="ri-bike-line me-2"></i>Become a Delivery Partner
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    @endguest

                </div>
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                        <button class="navbar-toggler btn-close" id="offcanvas-close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav justify-content-center flex-grow-1">
                            <li class="nav-item mega-menu">
                                <a class="nav-link" href="{{route('index')}}" id="accountDropdown" role="button"
                                     aria-expanded="false">Home</a>
                                
                            </li>
                            <li class="nav-item">
                                <a class="nav-link " href="{{ route('menu.list') }}" id="orderMenu" role="button"
                                     aria-expanded="false">Order</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('blog.list')}}" id="blogMenu" role="button"
                                    >Blog</a>
                               
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#!" id="pagesMenu" role="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">Pages</a>
                                <ul class="dropdown-menu mt-0 border-0" aria-labelledby="pagesMenu">
                                    <!-- <li><a class="dropdown-item" href="404.html">404</a></li>
                                    <li>
                                        <a class="dropdown-item" href="coming-soon.html">Coming soon</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="contact.html">Contact</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="empty-cart.html">Empty Cart</a>
                                    </li> -->
                                    <li><a class="dropdown-item" href="{{ route('faq.list') }}">FAQs</a></li>
                                    <li><a class="dropdown-item" href="{{ route('testomonial.list') }}">Testimonial</a></li>
                                    <li><a class="dropdown-item" href="{{route('wish.list')}}">Wishlist</a></li>
                                    <!-- <li>
                                        <a class="dropdown-item" href="signin.html">Sign in</a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="signup.html">Sign up</a>
                                    </li> -->
                                    
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('contact') }}">Contact</a>
                            </li>
                            @auth
                                <li class="nav-item d-lg-none w-100 px-3 mt-4 mb-4">
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();" class="btn w-100 d-flex align-items-center justify-content-center gap-2 rounded-3" style="background-color: #feeceb; color: #cb202d; font-weight: 600; padding: 12px; border: 1px solid #f8d7da;">
                                        <i class="ri-logout-box-r-line fs-5"></i> Logout
                                    </a>
                                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            @else
                                <li class="nav-item d-lg-none mt-4 w-100 px-3">
                                    <h6 class="text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Account & Registration</h6>
                                </li>
                                <li class="nav-item d-lg-none w-100 px-3 mt-2">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('login') }}" class="btn w-100 d-flex align-items-center justify-content-center gap-2 rounded-3" style="background-color: rgba(var(--theme-color), 0.1); color: rgba(var(--theme-color), 1); font-weight: 600; padding: 10px;">
                                            <i class="ri-login-box-line fs-5"></i> Login
                                        </a>
                                        <a href="{{ route('register') }}" class="btn theme-btn w-100 d-flex align-items-center justify-content-center gap-2 rounded-3 m-0" style="padding: 10px;">
                                            <i class="ri-user-add-line fs-5"></i> Sign Up
                                        </a>
                                    </div>
                                </li>
                                <li class="nav-item d-lg-none w-100 px-3 mt-4">
                                    <h6 class="text-muted text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Partner with us</h6>
                                </li>
                                <li class="nav-item d-lg-none w-100 px-3 mt-2 mb-4">
                                    <div class="d-flex flex-column gap-2">
                                        <a href="{{ route('vendor.register') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background: #f8f9fa; border: 1px solid #eee; transition: all 0.3s;">
                                            <div class="icon-box d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; background: #e3f2fd; color: #1976d2;">
                                                <i class="ri-restaurant-2-line fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="m-0 text-dark" style="font-size: 14px; font-weight: 600;">Become a Vendor</h6>
                                                <p class="m-0 text-muted" style="font-size: 11px;">Register your restaurant</p>
                                            </div>
                                        </a>
                                        <a href="{{ route('delivery-partner.register') }}" class="d-flex align-items-center gap-3 p-3 rounded-3 text-decoration-none" style="background: #f8f9fa; border: 1px solid #eee; transition: all 0.3s;">
                                            <div class="icon-box d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; background: #fbe9e7; color: #d84315;">
                                                <i class="ri-bike-line fs-5"></i>
                                            </div>
                                            <div>
                                                <h6 class="m-0 text-dark" style="font-size: 14px; font-weight: 600;">Delivery Partner</h6>
                                                <p class="m-0 text-muted" style="font-size: 11px;">Earn by delivering food</p>
                                            </div>
                                        </a>
                                    </div>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- Header Section end -->

