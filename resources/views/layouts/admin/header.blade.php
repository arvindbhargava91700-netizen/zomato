<header class="nxl-header">
        <div class="header-wrapper">
            <!--! [Start] Header Left !-->
            <div class="header-left d-flex align-items-center gap-4">
                <!--! [Start] nxl-head-mobile-toggler !-->
                <a href="javascript:void(0);" class="nxl-head-mobile-toggler" id="mobile-collapse">
                    <div class="hamburger hamburger--arrowturn">
                        <div class="hamburger-box">
                            <div class="hamburger-inner"></div>
                        </div>
                    </div>
                </a>
                <!--! [Start] nxl-head-mobile-toggler !-->
                <!--! [Start] nxl-navigation-toggle !-->
                <div class="nxl-navigation-toggle">
                    <a href="javascript:void(0);" id="menu-mini-button">
                        <i class="feather-align-left"></i>
                    </a>
                    <a href="javascript:void(0);" id="menu-expend-button" style="display: none">
                        <i class="feather-arrow-right"></i>
                    </a>
                </div>
                <!--! [End] nxl-navigation-toggle !-->
                
                
            </div>
            <!--! [End] Header Left !-->
            <!--! [Start] Header Right !-->
            <div class="header-right ms-auto">
                <div class="d-flex align-items-center">
                    
                    
                    <div class="nxl-h-item d-none d-sm-flex">
                        <div class="full-screen-switcher">
                            <a href="javascript:void(0);" class="nxl-head-link me-0" onclick="$('body').fullScreenHelper('toggle');">
                                <i class="feather-maximize maximize"></i>
                                <i class="feather-minimize minimize"></i>
                            </a>
                        </div>
                    </div>
                    <div class="nxl-h-item dark-light-theme">
                        <a href="javascript:void(0);" class="nxl-head-link me-0 dark-button">
                            <i class="feather-moon"></i>
                        </a>
                        <a href="javascript:void(0);" class="nxl-head-link me-0 light-button" style="display: none">
                            <i class="feather-sun"></i>
                        </a>
                    </div>
                   
                    <div class="dropdown nxl-h-item">
                        @php
                            $admin = auth('admin')->user();
                            $adminNotifications = $admin ? $admin->notifications()->take(5)->get() : collect();
                            $adminUnreadCount = $admin ? $admin->unreadNotifications->count() : 0;
                        @endphp
                        <a class="nxl-head-link me-3" data-bs-toggle="dropdown" href="#" role="button" data-bs-auto-close="outside">
                            <i class="feather-bell"></i>
                            @if($adminUnreadCount > 0)
                                <span class="badge bg-danger nxl-h-badge">{{ $adminUnreadCount }}</span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-notifications-menu">
                            <div class="d-flex justify-content-between align-items-center notifications-head">
                                <h6 class="fw-bold text-dark mb-0">Notifications</h6>
                                <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                                    @csrf
                                    <button type="submit" class="fs-11 text-success text-end ms-auto border-0 bg-transparent" title="Make all as Read">
                                        <i class="feather-check"></i>
                                        <span>Make as Read</span>
                                    </button>
                                </form>
                            </div>
                            @forelse($adminNotifications as $notification)
                                @php $nData = $notification->data; @endphp
                                <a href="{{ route('admin.notifications.show', $notification->id) }}" class="notifications-item text-decoration-none">
                                    <div class="notifications-desc">
                                        <div class="font-body text-truncate-2-line">
                                            <span class="fw-semibold text-dark">{{ $nData['title'] ?? 'Notification' }}</span>
                                            {{ $nData['message'] ?? '' }}
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="notifications-date text-muted border-bottom border-bottom-dashed">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </div>
                                            @if($notification->read_at === null)
                                                <span class="badge bg-primary">New</span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @empty
                                <div class="text-center py-4 text-muted fs-13">
                                    <i class="feather-bell d-block mb-1 text-secondary"></i>
                                    No notifications
                                </div>
                            @endforelse

                            <div class="text-center notifications-footer">
                                <a href="{{ route('admin.notifications.index') }}" class="fs-13 fw-semibold text-dark">All Notifications</a>
                            </div>
                        </div>
                    </div>
                   
                    <div class="dropdown nxl-h-item">
                        <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                            <img src="{{asset('admin/assets/images/avatar/1.png')}}" alt="user-image" class="img-fluid user-avtar me-0" style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%;" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <img src="{{asset('admin/assets/images/avatar/1.png')}}" alt="user-image" class="img-fluid user-avtar" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;" />
                                    <div>
                                        <h6 class="text-dark mb-0">{{ auth('admin')->user()->name ?? 'Admin' }} <span class="badge bg-soft-success text-success ms-1">{{ str_replace('_', ' ', auth('admin')->user()->role ?? 'admin') }}</span></h6>
                                        <span class="fs-12 fw-medium text-muted">{{ auth('admin')->user()->email ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                           
                            <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                <i class="feather-user"></i>
                                <span>Profile Details</span>
                            </a>
                            <a href="{{ route('admin.account-setting.index') }}" class="dropdown-item">
                                <i class="feather-credit-card"></i>
                                <span>Account Setting</span>
                            </a>
                            <a href="{{ route('admin.profile') }}" class="dropdown-item">
                                <i class="feather-lock"></i>
                                <span>Change Password</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="javascript:void(0);" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#globalLogoutModal">
                                <i class="feather-log-out"></i>
                                <span>Logout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!--! [End] Header Right !-->
        </div>
    </header>