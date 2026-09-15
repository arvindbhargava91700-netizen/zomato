<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header d-flex align-items-center px-4 py-3 border-bottom">
            <a href="{{ route('delivery-partner.dashboard') }}" class="b-brand text-decoration-none d-flex align-items-center">
                <span class="fs-4 fw-bold tracking-wide" style="color: #cb202d; font-family: sans-serif;">ZOMATO</span>
                <span class="badge bg-soft-success text-success ms-2 fs-10 text-uppercase">Delivery</span>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Main Menu</label>
                </li>
                <!-- Dashboard -->
                <li class="nxl-item {{ request()->routeIs('delivery-partner.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('delivery-partner.dashboard') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-airplay"></i>
                        </span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                 <!-- KYC -->
                <li class="nxl-item {{ request()->routeIs('delivery-partner.kyc') ? 'active' : '' }}">
                    <a href="{{ route('delivery-partner.kyc') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-shield"></i>
                        </span>
                        <span class="nxl-mtext">KYC</span>
                    </a>
                </li>
                 @if(auth()->user()->status === 'active')
                <!-- My Deliveries -->
                <li class="nxl-item {{ request()->routeIs('delivery-partner.orders.deliveries') ? 'active' : '' }}">
                    <a href="{{ route('delivery-partner.orders.deliveries') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-truck"></i>
                        </span>
                        <span class="nxl-mtext">My Deliveries</span>
                    </a>
                </li>
                <!-- New Deliveries -->
                <li class="nxl-item {{ request()->routeIs('delivery-partner.orders.available') ? 'active' : '' }}">
                    <a href="{{ route('delivery-partner.orders.available') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-bell"></i>
                        </span>
                        <span class="nxl-mtext">New Deliveries</span>
                    </a>
                </li>
                <!-- Earnings -->
                <li class="nxl-item {{ request()->routeIs('delivery-partner.earnings.*') ? 'active' : '' }}">
                    <a href="{{ route('delivery-partner.earnings.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-dollar-sign"></i>
                        </span>
                        <span class="nxl-mtext">Earnings</span>
                        @if($deliveryPartnerEarnings > 0)
                            <span class="badge bg-soft-success text-success rounded-pill ms-auto">{{ $currencySymbol }}{{ number_format($deliveryPartnerEarnings, 2) }}</span>
                        @endif
                    </a>
                </li>
                                <!-- Transactions -->
                <li class="nxl-item {{ request()->routeIs('delivery-partner.transactions.*') ? 'active' : '' }}">
                    <a href="{{ route('delivery-partner.transactions.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-credit-card"></i>
                        </span>
                        <span class="nxl-mtext">Transactions</span>
                    </a>
                </li>

                <!-- Withdrawals -->
                <li class="nxl-item {{ request()->routeIs('delivery-partner.withdrawals.*') ? 'active' : '' }}">
                    <a href="{{ route('delivery-partner.withdrawals.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-arrow-up-circle"></i>
                        </span>
                        <span class="nxl-mtext">Withdrawals</span>
                    </a>
                </li>

                <!-- Support Tickets -->
                <li class="nxl-item {{ request()->routeIs('delivery-partner.tickets.*') ? 'active' : '' }}">
                    <a href="{{ route('delivery-partner.tickets.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-life-buoy"></i>
                        </span>
                        <span class="nxl-mtext">Support Tickets</span>
                        @php $myOpenTickets = \App\Models\Ticket::where('user_type', 'delivery_partner')->where('user_id', auth()->id())->where('status', 'open')->count(); @endphp
                        @if($myOpenTickets > 0)
                            <span class="badge bg-soft-danger text-danger rounded-pill ms-auto">{{ $myOpenTickets }}</span>
                        @endif
                    </a>
                </li>

                <!-- Notifications -->
                <li class="nxl-item {{ request()->routeIs('delivery-partner.notifications.*') ? 'active' : '' }}">
                    <a href="{{ route('delivery-partner.notifications.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-bell"></i>
                        </span>
                        <span class="nxl-mtext">Notifications</span>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge bg-soft-danger text-danger rounded-pill ms-auto">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </a>
                </li>

                 <!-- Account & Bank Settings -->
                <li class="nxl-item {{ request()->routeIs('delivery-partner.account.settings*') ? 'active' : '' }}">
                    <a href="{{ route('delivery-partner.account.settings') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-settings"></i>
                        </span>
                        <span class="nxl-mtext">Account Settings</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>
</nav>
