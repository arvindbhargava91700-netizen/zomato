<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header d-flex align-items-center px-4 py-3 border-bottom">
            <a href="{{ route('admin.dashboard') }}" class="b-brand text-decoration-none d-flex align-items-center">
                <span class="fs-4 fw-bold tracking-wide" style="color: #cb202d; font-family: sans-serif;">ZOMATO</span>
                <span class="badge bg-soft-danger text-danger ms-2 fs-10 text-uppercase">Admin</span>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">

                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                <!-- ============================================= -->
                <!-- Dashboard -->
                <!-- ============================================= -->
                <li class="nxl-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('admin.dashboard') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-airplay"></i>
                        </span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>

                <!-- ============================================= -->
                <!-- Restaurants Management -->
                <!-- ============================================= -->
                <li class="nxl-item nxl-hasmenu {{ request()->routeIs('admin.restaurants.*', 'admin.dining-offers.*', 'admin.restaurant-offers.*', 'admin.restaurant-blogs.*', 'admin.food-categories.*', 'admin.promo-codes.*', 'admin.brands.*', 'admin.nightlife-banners.*') ? 'active nxl-trigger' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-shopping-bag"></i>
                        </span>
                        <span class="nxl-mtext">Restaurants Management</span>
                        <span class="nxl-arrow">
                            <i class="feather-chevron-right"></i>
                        </span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item {{ request()->routeIs('admin.restaurants.index') && !request('approval_status') ? 'active' : '' }}">
                            <a href="{{ route('admin.restaurants.index') }}" class="nxl-link">
                                <i class="feather-layers me-2"></i> All Restaurants
                            </a>
                        </li>
                        <li class="nxl-item {{ request('approval_status') === 'pending' ? 'active' : '' }}">
                            <a href="{{ route('admin.restaurants.index', ['approval_status' => 'pending']) }}" class="nxl-link">
                                <i class="feather-clock me-2"></i> Pending Restaurants
                            </a>
                        </li>
                        <li class="nxl-item {{ request('approval_status') === 'approved' ? 'active' : '' }}">
                            <a href="{{ route('admin.restaurants.index', ['approval_status' => 'approved']) }}" class="nxl-link">
                                <i class="feather-check-circle me-2"></i> Approved Restaurants
                            </a>
                        </li>
                        <li class="nxl-item {{ request('approval_status') === 'rejected' ? 'active' : '' }}">
                            <a href="{{ route('admin.restaurants.index', ['approval_status' => 'rejected']) }}" class="nxl-link">
                                <i class="feather-x-circle me-2"></i> Rejected Restaurants
                            </a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('admin.brands.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.brands.index') }}" class="nxl-link">
                                <i class="feather-award me-2"></i> Brands
                            </a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('admin.restaurant-features.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.restaurant-features.index') }}" class="nxl-link">
                                <i class="feather-star me-2"></i> Restaurant Features
                            </a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('admin.nightlife-banners.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.nightlife-banners.index') }}" class="nxl-link">
                                <i class="feather-moon me-2"></i> Nightlife Banners
                            </a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('admin.dining-offers.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.dining-offers.index') }}" class="nxl-link d-flex align-items-center justify-content-between">
                                <span><i class="feather-tag me-2"></i> Dining Offers</span>
                                @if($pendingDiningOffersCount > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $pendingDiningOffersCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('admin.restaurant-offers.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.restaurant-offers.index') }}" class="nxl-link d-flex align-items-center justify-content-between">
                                <span><i class="feather-percent me-2"></i> Today's Deals</span>
                                @if($pendingRestaurantOffersCount > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $pendingRestaurantOffersCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('admin.restaurant-blogs.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.restaurant-blogs.index') }}" class="nxl-link d-flex align-items-center justify-content-between">
                                <span><i class="feather-edit-3 me-2"></i> Restaurant Blogs</span>
                                @if($pendingRestaurantBlogsCount > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $pendingRestaurantBlogsCount }}</span>
                                @endif
                            </a>
                        </li>

                        <li class="nxl-item {{ request()->routeIs('admin.food-categories.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.food-categories.index') }}" class="nxl-link">
                                <i class="feather-folder me-2"></i> Food Categories
                            </a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('admin.promo-codes.*') ? 'active' : '' }}">
                            <a href="{{ route('admin.promo-codes.index') }}" class="nxl-link">
                                <i class="feather-gift me-2"></i> Promo Codes
                            </a>
                        </li>
                    </ul>
                </li>




                                    <!-- ============================================= -->
                    <!-- User Management (Dedicated Section) -->
                    <!-- ============================================= -->
                    <li class="nxl-item nxl-hasmenu {{ request()->routeIs('admin.users.*', 'admin.delivery-partners.*') ? 'active nxl-trigger' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-users"></i></span>
                            <span class="nxl-mtext">User Management</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item {{ request()->routeIs('admin.users.*') && request('type') === 'customer' ? 'active' : '' }}">
                                <a class="nxl-link" href="{{ route('admin.users.index', ['type' => 'customer']) }}">
                                    <i class="feather-user me-2"></i>Customer List
                                </a>
                            </li>
                            <li class="nxl-item {{ request()->routeIs('admin.users.*') && request('type') === 'restaurant_owner' ? 'active' : '' }}">
                                <a class="nxl-link" href="{{ route('admin.users.index', ['type' => 'restaurant_owner']) }}">
                                    <i class="feather-shopping-bag me-2"></i>Vendor List
                                </a>
                            </li>
                            <li class="nxl-item {{ request()->routeIs('admin.users.*') && request('type') === 'delivery_partner' ? 'active' : '' }} ">
                                <a class="nxl-link" href="{{ route('admin.users.index', ['type' => 'delivery_partner']) }}">
                                    <i class="feather-truck me-2"></i>Delivery Partner List
                                </a>
                            </li>
                        </ul>
                    </li>




                                    <li class="nxl-item nxl-hasmenu {{ request()->routeIs('admin.settings.*', 'admin.email-configuration.*', 'admin.sms-configuration.*', 'admin.email-templates.*') ? 'active nxl-trigger' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-award"></i></span>
                            <span class="nxl-mtext">Super Admin</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">

                            <li class="nxl-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.settings.index') }}"><i class="feather-settings me-2"></i>Company Settings</a></li>
                            <li class="nxl-item {{ request()->routeIs('admin.email-configuration.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.email-configuration.edit') }}"><i class="feather-mail me-2"></i>Email Configuration</a></li>
                            <li class="nxl-item {{ request()->routeIs('admin.sms-configuration.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.sms-configuration.edit') }}"><i class="feather-message-square me-2"></i>SMS Configuration</a></li>
                            <li class="nxl-item {{ request()->routeIs('admin.email-templates.*') ? 'active' : '' }}"><a class="nxl-link" href="{{ route('admin.email-templates.index') }}"><i class="feather-file-text me-2"></i>Email Templates</a></li>
                        </ul>
                    </li>


                        <li class="nxl-item nxl-hasmenu">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-award"></i></span>
                            <span class="nxl-mtext">Roles & Permissions</span><span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">

                            <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}"><i class="feather-user me-2"></i>Roles list</a></li>
                            <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('admin.sub-admins.*') ? 'active' : '' }}" href="{{ route('admin.sub-admins.index') }}"><i class="feather-user-plus me-2"></i>Sub Admins</a></li>
                            <li class="nxl-item"><a class="nxl-link {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" href="{{ route('admin.logs.index') }}"><i class="feather-activity me-2"></i>Admin Logs</a></li>
</ul>
                  
                        </li>

                    <!-- ============================================= -->
                    <!-- Payment Gateways (Dedicated Section) -->
                    <!-- ============================================= -->
                    <li class="nxl-item nxl-hasmenu {{ request()->routeIs('admin.payment-gateways.*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon">
                                <i class="feather-credit-card"></i>
                            </span>
                            <span class="nxl-mtext">Payment Gateways</span>
                            <span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item {{ request()->routeIs('admin.payment-gateways.index') ? 'active' : '' }}">
                                <a href="{{ route('admin.payment-gateways.index') }}" class="nxl-link">
                                    <i class="feather-layers me-2"></i> All Gateways
                                </a>
                            </li>
                            @php
                                $sidebarGateways = \App\Models\PaymentGateway::orderBy('sort_order')->get();
                            @endphp
                            @foreach($sidebarGateways as $sgw)
                                <li class="nxl-item {{ request()->is('admin/payment-gateways/' . $sgw->gateway_key . '*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.payment-gateways.edit', $sgw->gateway_key) }}" class="nxl-link d-flex align-items-center justify-content-between">
                                        <span>
                                            @if($sgw->gateway_key === 'razorpay')
                                                <i class="feather-zap me-2 text-warning"></i>
                                            @elseif($sgw->gateway_key === 'phonepe')
                                                <i class="feather-smartphone me-2" style="color: #5f259f;"></i>
                                            @elseif($sgw->gateway_key === 'paytm')
                                                <i class="feather-credit-card me-2" style="color: #00b9f5;"></i>
                                            @elseif($sgw->gateway_key === 'paypal')
                                                <i class="feather-globe me-2" style="color: #003087;"></i>
                                            @else
                                                <i class="feather-shield me-2 text-success"></i>
                                            @endif
                                            {{ $sgw->name }}
                                        </span>
                                        @if($sgw->is_active)
                                            <span class="badge bg-soft-success text-success fs-10">Live</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                    </li>


                    <!-- ============================================= -->
                    <!-- Delivery Partner Management -->
                    <!-- ============================================= -->
                    <li class="nxl-item nxl-hasmenu {{ request()->routeIs('admin.delivery-partners.*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon">
                                <i class="feather-truck"></i>
                            </span>
                            <span class="nxl-mtext">Delivery Partners</span>
                            <span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item {{ request()->routeIs('admin.delivery-partners.index') && !request('kyc_status') ? 'active' : '' }}">
                                <a href="{{ route('admin.delivery-partners.index') }}" class="nxl-link">
                                    <i class="feather-users me-2"></i> All Partners
                                </a>
                            </li>
                            <li class="nxl-item {{ request('kyc_status') === 'pending' ? 'active' : '' }}">
                                <a href="{{ route('admin.delivery-partners.index', ['kyc_status' => 'pending']) }}" class="nxl-link">
                                    <i class="feather-clock me-2"></i> Pending KYC
                                </a>
                            </li>
                            <li class="nxl-item {{ request('kyc_status') === 'approved' ? 'active' : '' }}">
                                <a href="{{ route('admin.delivery-partners.index', ['kyc_status' => 'approved']) }}" class="nxl-link">
                                    <i class="feather-check-circle me-2"></i> Approved
                                </a>
                            </li>
                            <li class="nxl-item {{ request('kyc_status') === 'rejected' ? 'active' : '' }}">
                                <a href="{{ route('admin.delivery-partners.index', ['kyc_status' => 'rejected']) }}" class="nxl-link">
                                    <i class="feather-x-circle me-2"></i> Rejected
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- ============================================= -->
                    <!-- Payment Transactions -->
                    <!-- ============================================= -->
                    <li class="nxl-item {{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.transactions.index') }}" class="nxl-link">
                            <span class="nxl-micon">
                                <i class="feather-credit-card"></i>
                            </span>
                            <span class="nxl-mtext">Payment Transactions</span>
                        </a>
                    </li>
                      <!-- ============================================= -->
                    <!-- Wallet Transactions -->
                    <!-- ============================================= -->
                    <li class="nxl-item {{ request()->routeIs('admin.wallet-transactions.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.wallet-transactions.index') }}" class="nxl-link">
                            <span class="nxl-micon">
                                <i class="feather-dollar-sign"></i>
                            </span>
                            <span class="nxl-mtext">Wallet Transactions</span>
                        </a>
                    </li>

                    <!-- ============================================= -->
                    <!-- Withdrawal Requests -->
                    <!-- ============================================= -->
                    <li class="nxl-item {{ request()->routeIs('admin.withdrawals.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.withdrawals.index') }}" class="nxl-link d-flex align-items-center justify-content-between">
                            <span class="d-flex align-items-center">
                                <span class="nxl-micon">
                                    <i class="feather-arrow-up-circle"></i>
                                </span>
                                <span class="nxl-mtext">Withdrawals</span>
                            </span>
                            @php
                                $pendingWithdrawalsCount = \App\Models\Withdrawal::where('status', 'pending')->count();
                            @endphp
                            @if($pendingWithdrawalsCount > 0)
                                <span class="badge bg-danger rounded-pill">{{ $pendingWithdrawalsCount }}</span>
                            @endif
                        </a>
                    </li>


                    <!-- ============================================= -->
                    <!-- Earnings -->
                    <!-- ============================================= -->
                    <li class="nxl-item nxl-hasmenu {{ request()->routeIs('admin.earnings.*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon">
                                <i class="feather-bar-chart-2"></i>
                            </span>
                            <span class="nxl-mtext">Earnings</span>
                            <span class="nxl-arrow">
                                <i class="feather-chevron-right"></i>
                            </span>
                        </a>
                        <ul class="nxl-submenu">
                            <li class="nxl-item {{ request()->routeIs('admin.earnings.commission') ? 'active' : '' }}">
                                <a href="{{ route('admin.earnings.commission') }}" class="nxl-link">
                                    <i class="feather-percent me-2"></i> Restaurant Commission
                                    <span class="badge bg-soft-primary text-primary rounded-pill ms-auto">{{ $currencySymbol }}{{ number_format($adminEarnings['commission'], 2) }}</span>
                                </a>
                            </li>
                            <li class="nxl-item {{ request()->routeIs('admin.earnings.tax') ? 'active' : '' }}">
                                <a href="{{ route('admin.earnings.tax') }}" class="nxl-link">
                                    <i class="feather-file-text me-2"></i> Tax / GST
                                    <span class="badge bg-soft-warning text-warning rounded-pill ms-auto">{{ $currencySymbol }}{{ number_format($adminEarnings['tax'], 2) }}</span>
                                </a>
                            </li>
                            <li class="nxl-item {{ request()->routeIs('admin.earnings.platform') ? 'active' : '' }}">
                                <a href="{{ route('admin.earnings.platform') }}" class="nxl-link">
                                    <i class="feather-share-2 me-2"></i> Platform Share
                                    <span class="badge bg-soft-success text-success rounded-pill ms-auto">{{ $currencySymbol }}{{ number_format($adminEarnings['platform'], 2) }}</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                <!-- Support Tickets -->
                <li class="nxl-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.tickets.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-life-buoy"></i></span>
                        <span class="nxl-mtext">Support Tickets</span>
                        @php $openTickets = \App\Models\Ticket::where('status', 'open')->count(); @endphp
                        @if($openTickets > 0)
                            <span class="badge bg-soft-danger text-danger rounded-pill ms-auto">{{ $openTickets }}</span>
                        @endif
                    </a>
                </li>

                <!-- Contact Inquiries -->
                <li class="nxl-item {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.contact-messages.index') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-mail"></i></span>
                        <span class="nxl-mtext">Contact Inquiries</span>
                        @php $unreadContactMsgCount = \App\Models\ContactMessage::where('status', 'unread')->count(); @endphp
                        @if($unreadContactMsgCount > 0)
                            <span class="badge bg-danger text-white rounded-pill ms-auto">{{ $unreadContactMsgCount }}</span>
                        @endif
                    </a>
                </li>

            </ul>
                <div class="sidebar-footer border-top mt-auto p-3">
                    <div class="dropdown w-100">
                        <a href="javascript:void(0);" class="btn btn-light-brand w-100 d-flex align-items-center justify-content-between" data-bs-toggle="dropdown">
                            <span class="hstack gap-2">
                                <i class="feather-user me-1"></i>
                                <span class="text-truncate">{{ auth('admin')->user()->name ?? 'Admin' }}</span>
                            </span>
                            <i class="feather-chevron-down"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end w-100">
                            <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                <i class="feather-user"></i>
                                <span>Profile Details</span>
                            </a>
                            <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                <i class="feather-settings"></i>
                                <span>Change Password</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="" method="POST" id="logoutFormSidebar">
                                @csrf
                            </form>

                            <a href="javascript:void(0);" class="dropdown-item text-danger"
                            onclick="document.getElementById('logoutFormSidebar').submit();">
                                <i class="feather-log-out"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</nav>