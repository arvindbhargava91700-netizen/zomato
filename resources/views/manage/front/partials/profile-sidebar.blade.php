<div class="col-lg-3">
    <div class="profile-sidebar sticky-top">
        <div class="profile-cover">
            <img class="img-fluid profile-pic"
                src="{{ auth()->user()->profile_image ? asset(auth()->user()->profile_image) : asset('front/assets/images/icons/p5.png') }}"
                alt="profile">
        </div>
        <div class="profile-name">
            <h5 class="user-name">{{ auth()->user()->name }}</h5>
            <h6>{{ auth()->user()->email }}</h6>
        </div>
        <ul class="profile-list">
            <li class="{{ request()->routeIs('profile') ? 'active' : '' }}">
                <i class="ri-user-3-line"></i>
                @if(request()->routeIs('profile'))
                    <a href="#change-profile-pic" data-bs-toggle="modal"
                        data-bs-target="#change-profile-pic">Change Profile</a>
                @else
                    <a href="{{ route('profile') }}">Change Profile</a>
                @endif
            </li>
            <li class="{{ request()->routeIs('wallet') ? 'active' : '' }}">
                <i class="ri-wallet-3-line"></i>
                <a href="{{ route('wallet') }}">My Wallet</a>
            </li>
            <li class="{{ request()->routeIs('my.transactions') ? 'active' : '' }}">
                <i class="ri-search-line"></i>
                <a href="{{ route('my.transactions') }}">My Transactions</a>
            </li>
            <li class="{{ request()->routeIs('my.orders') ? 'active' : '' }}">
                <i class="ri-shopping-bag-3-line"></i>
                <a href="{{ route('my.orders') }}">My Order</a>
            </li>
            <li class="{{ request()->routeIs('my.feedback') ? 'active' : '' }}">
                <i class="ri-star-line"></i>
                <a href="{{ route('my.feedback') }}">My Feedback</a>
            </li>
            <li class="{{ request()->routeIs('saved.address') ? 'active' : '' }}">
                <i class="ri-map-pin-line"></i>
                <a href="{{ route('saved.address') }}">Saved Address</a>
            </li>
            <li class="{{ request()->routeIs('saved.card') ? 'active' : '' }}">
                <i class="ri-bank-card-line"></i>
                <a href="{{ route('saved.card') }}">Saved Card</a>
            </li>
            <li class="{{ request()->routeIs('faq.list') ? 'active' : '' }}">
                <i class="ri-question-line"></i>
                <a href="{{ route('faq.list') }}">Help</a>
            </li>
            <li class="{{ request()->routeIs('setting') ? 'active' : '' }}">
                <i class="ri-settings-3-line"></i>
                <a href="{{ route('setting') }}">Setting</a>
            </li>
            <li>
                <i class="ri-logout-box-r-line"></i>
                <a href="#log-out" data-bs-toggle="modal">Log Out</a>
            </li>
        </ul>
    </div>
</div>
