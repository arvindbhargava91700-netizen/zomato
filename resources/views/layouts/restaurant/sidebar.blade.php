<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header d-flex align-items-center px-4 py-3 border-bottom">
            <a href="{{ route('restaurant.dashboard') }}" class="b-brand text-decoration-none d-flex align-items-center">
                <span class="fs-4 fw-bold tracking-wide" style="color: #cb202d; font-family: sans-serif;">ZOMATO</span>
                <span class="badge bg-soft-warning text-warning ms-2 fs-10 text-uppercase">Partner</span>
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Main Menu</label>
                </li>
                <!-- Dashboard -->
                <li class="nxl-item {{ request()->routeIs('restaurant.dashboard') ? 'active' : '' }}">
                    <a href="{{ route('restaurant.dashboard') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-airplay"></i>
                        </span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>
                                <!-- ============================================= -->
                <!-- Restaurants -->
                <!-- ============================================= -->
                <li class="nxl-item nxl-hasmenu {{ request()->routeIs('restaurant.restaurants.*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-shopping-bag"></i>
                        </span>
                        <span class="nxl-mtext">Restaurants</span>
                        <span class="nxl-arrow">
                            <i class="feather-chevron-right"></i>
                        </span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item {{ request()->routeIs('restaurant.restaurants.index') ? 'active' : '' }}">
                            <a href="{{ route('restaurant.restaurants.index') }}" class="nxl-link">All Restaurants</a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('restaurant.restaurants.create') ? 'active' : '' }}">
                            <a href="{{ route('restaurant.restaurants.create') }}" class="nxl-link">Add Restaurant</a>
                        </li>
                    </ul>
                </li>

                <!-- ============================================= -->
                <!-- Orders -->
                <!-- ============================================= -->
                <li class="nxl-item {{ request()->routeIs('restaurant.orders.*') ? 'active' : '' }}">
                    <a href="{{ route('restaurant.orders.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-shopping-cart"></i>
                        </span>
                        <span class="nxl-mtext">Orders</span>
                    </a>
                </li>



                <!-- ============================================= -->
                <!-- Cuisines -->
                <!-- ============================================= -->
                <li class="nxl-item {{ request()->routeIs('restaurant.cuisines.*') ? 'active' : '' }}">
                    <a href="{{ route('restaurant.cuisines.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-coffee"></i>
                        </span>
                        <span class="nxl-mtext">Cuisines</span>
                    </a>
                </li>

                <!-- ============================================= -->
                <!-- Foods -->
                <!-- ============================================= -->
                <li class="nxl-item nxl-hasmenu {{ request()->routeIs('restaurant.food-categories.*', 'restaurant.foods.*', 'restaurant.food-variants.*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-grid"></i>
                        </span>
                        <span class="nxl-mtext">Foods</span>
                        <span class="nxl-arrow">
                            <i class="feather-chevron-right"></i>
                        </span>
                    </a>
                    <ul class="nxl-submenu">
                        <!-- <li class="nxl-item {{ request()->routeIs('restaurant.food-categories.*') ? 'active' : '' }}">
                            <a href="{{ route('restaurant.food-categories.index') }}" class="nxl-link">Food Categories</a>
                        </li> -->
                        <li class="nxl-item {{ request()->routeIs('restaurant.foods.index') ? 'active' : '' }}">
                            <a href="{{ route('restaurant.foods.index') }}" class="nxl-link">All Food Items</a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('restaurant.foods.create') ? 'active' : '' }}">
                            <a href="{{ route('restaurant.foods.create') }}" class="nxl-link">Add Food Item</a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('restaurant.food-variants.*') ? 'active' : '' }}">
                            <a href="{{ route('restaurant.food-variants.index') }}" class="nxl-link">Food Variants</a>
                        </li>
                    </ul>
                </li>

                <!-- ============================================= -->
                <!-- Earnings -->
                <!-- ============================================= -->
                <li class="nxl-item {{ request()->routeIs('restaurant.earnings.*') ? 'active' : '' }}">
                    <a href="{{ route('restaurant.earnings.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-dollar-sign"></i>
                        </span>
                        <span class="nxl-mtext">Earnings</span>
                        @if($restaurantEarnings > 0)
                            <span class="badge bg-soft-success text-success rounded-pill ms-auto">{{ $currencySymbol }}{{ number_format($restaurantEarnings, 2) }}</span>
                        @endif
                    </a>
                </li>

                <!-- ============================================= -->
                <!-- Dining Setup & Tables -->
                <!-- ============================================= -->
                <li class="nxl-item {{ request()->routeIs('restaurant.dining-setup.*') ? 'active' : '' }}">
                    <a href="{{ route('restaurant.dining-setup.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-grid"></i>
                        </span>
                        <span class="nxl-mtext">Dining & Tables</span>
                    </a>
                </li>

                <!-- ============================================= -->
                <!-- Dining Offers -->
                <!-- ============================================= -->
                <li class="nxl-item {{ request()->routeIs('restaurant.dining-offers.*') ? 'active' : '' }}">
                    <a href="{{ route('restaurant.dining-offers.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-tag"></i>
                        </span>
                        <span class="nxl-mtext">Dining Offers</span>
                    </a>
                </li>

                <!-- Table Bookings -->
                <li class="nxl-item {{ request()->routeIs('restaurant.bookings.*') ? 'active' : '' }}">
                    <a href="{{ route('restaurant.bookings.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-calendar"></i>
                        </span>
                        <span class="nxl-mtext">Table Bookings</span>
                    </a>
                </li>

                <!-- ============================================= -->
                <!-- Blogs & Stories -->
                <!-- ============================================= -->
                <li class="nxl-item nxl-hasmenu {{ request()->routeIs('restaurant.blogs.*') ? 'active' : '' }}">
                    <a href="javascript:void(0);" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-edit-3"></i>
                        </span>
                        <span class="nxl-mtext">Blogs</span>
                        <span class="nxl-arrow">
                            <i class="feather-chevron-right"></i>
                        </span>
                    </a>
                    <ul class="nxl-submenu">
                        <li class="nxl-item {{ request()->routeIs('restaurant.blogs.index') ? 'active' : '' }}">
                            <a href="{{ route('restaurant.blogs.index') }}" class="nxl-link">All Blogs</a>
                        </li>
                        <li class="nxl-item {{ request()->routeIs('restaurant.blogs.create') ? 'active' : '' }}">
                            <a href="{{ route('restaurant.blogs.create') }}" class="nxl-link">Add Blog</a>
                        </li>
                    </ul>
                </li>



                <!-- Support Tickets -->
                <li class="nxl-item {{ request()->routeIs('restaurant.tickets.*') ? 'active' : '' }}">
                    <a href="{{ route('restaurant.tickets.index') }}" class="nxl-link">
                        <span class="nxl-micon">
                            <i class="feather-life-buoy"></i>
                        </span>
                        <span class="nxl-mtext">Support Tickets</span>
                        @php $myOpenTickets = \App\Models\Ticket::where('user_type', 'restaurant')->where('user_id', auth()->id())->where('status', 'open')->count(); @endphp
                        @if($myOpenTickets > 0)
                            <span class="badge bg-soft-danger text-danger rounded-pill ms-auto">{{ $myOpenTickets }}</span>
                        @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>