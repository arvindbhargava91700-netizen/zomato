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
                        <a class="nxl-head-link me-3" data-bs-toggle="dropdown" href="#" role="button" data-bs-auto-close="outside">
                            <i class="feather-bell"></i>
                            <span class="badge bg-danger nxl-h-badge">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-notifications-menu">
                            <div class="d-flex justify-content-between align-items-center notifications-head">
                                <h6 class="fw-bold text-dark mb-0">Notifications</h6>
                                <a href="javascript:void(0);" class="fs-11 text-success text-end ms-auto" data-bs-toggle="tooltip" title="Make as Read">
                                    <i class="feather-check"></i>
                                    <span>Make as Read</span>
                                </a>
                            </div>
                            <div class="notifications-item">
                                <img src="assets/images/avatar/2.png" alt="" class="rounded me-3 border" />
                                <div class="notifications-desc">
                                    <a href="javascript:void(0);" class="font-body text-truncate-2-line"> <span class="fw-semibold text-dark">Malanie Hanvey</span> We should talk about that at lunch!</a>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="notifications-date text-muted border-bottom border-bottom-dashed">2 minutes ago</div>
                                        <div class="d-flex align-items-center float-end gap-2">
                                            <a href="javascript:void(0);" class="d-block wd-8 ht-8 rounded-circle bg-gray-300" data-bs-toggle="tooltip" title="Make as Read"></a>
                                            <a href="javascript:void(0);" class="text-danger" data-bs-toggle="tooltip" title="Remove">
                                                <i class="feather-x fs-12"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="text-center notifications-footer">
                                <a href="javascript:void(0);" class="fs-13 fw-semibold text-dark">Alls Notifications</a>
                            </div>
                        </div>
                    </div>
                   
                    <!--! [Start] Delivery Partner Wallet Display Widget !-->
                    <div class="dropdown nxl-h-item me-2 d-none d-sm-flex align-items-center">
                        <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside" class="d-inline-flex align-items-center text-decoration-none rounded-pill px-3" style="height: 38px; background: #ecfdf5; border: 1.5px solid #a7f3d0; box-shadow: 0 2px 6px rgba(16, 185, 129, 0.12); transition: all 0.2s ease;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-2 text-white shadow-sm flex-shrink-0" style="width: 26px; height: 26px; background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                                <i class="feather-credit-card" style="font-size: 13px;"></i>
                            </div>
                            <div class="d-flex flex-column text-start justify-content-center me-1">
                                <span style="font-size: 8.5px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; line-height: 1;">Wallet</span>
                                <span style="font-size: 13px; font-weight: 800; color: #065f46; line-height: 1.2;">{{ $currencySymbol }}{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</span>
                            </div>
                            <i class="feather-chevron-down text-muted ms-1" style="font-size: 11px;"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown shadow-lg border-0 rounded-4 p-3" style="width: 320px; max-width: 90vw; right: 0 !important; left: auto !important; z-index: 1060; margin-top: 5px;">
                            <div class="d-flex align-items-center justify-content-between pb-2 mb-2 border-bottom">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="rounded-circle p-1 bg-soft-success text-success d-flex align-items-center justify-content-center" style="width: 26px; height: 26px;">
                                        <i class="feather-pocket fs-12"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-0 fs-13">Courier Partner Wallet</h6>
                                </div>
                                <span class="badge bg-soft-success text-success fs-10 px-2 py-1 rounded-pill">Active</span>
                            </div>

                            <div class="p-3 rounded-3 text-white mb-3" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); box-shadow: 0 4px 14px rgba(16, 185, 129, 0.3);">
                                <div class="fs-11 text-white-50 text-uppercase fw-semibold mb-1">Available Delivery Payouts</div>
                                <div class="fs-22 fw-bold text-white tracking-tight">{{ $currencySymbol }}{{ number_format(auth()->user()->wallet_balance ?? 0, 2) }}</div>
                                <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-white border-opacity-25 fs-11">
                                    <span class="text-white-50">Auto-credited on delivery</span>
                                    <span class="badge bg-white text-success fw-bold rounded-pill">Live</span>
                                </div>
                            </div>

                            <div class="text-center pt-1">
                                <a href="{{ route('delivery-partner.earnings.index') }}" class="btn btn-sm btn-outline-success w-100 rounded-pill fw-semibold">
                                    <i class="feather-dollar-sign me-1"></i> View Earnings Summary
                                </a>
                            </div>
                        </div>
                    </div>
                    <!--! [End] Delivery Partner Wallet Display Widget !-->

                    <div class="dropdown nxl-h-item">
                        <a href="javascript:void(0);" data-bs-toggle="dropdown" role="button" data-bs-auto-close="outside">
                            <img src="{{asset('admin/assets/images/avatar/1.png')}}" alt="user-image" class="img-fluid user-avtar me-0" style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%;" />
                        </a>
                        <div class="dropdown-menu dropdown-menu-end nxl-h-dropdown nxl-user-dropdown">
                            <div class="dropdown-header">
                                <div class="d-flex align-items-center">
                                    <img src="{{asset('admin/assets/images/avatar/1.png')}}" alt="user-image" class="img-fluid user-avtar" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;" />
                                    <div>
                                        <h6 class="text-dark mb-0">{{ auth()->user()->name ?? 'Courier Partner' }} <span class="badge bg-soft-success text-success ms-1">{{ str_replace('_', ' ', auth()->user()->role->name ?? 'Courier') }}</span></h6>
                                        <span class="fs-12 fw-medium text-muted">{{ auth()->user()->email ?? '' }}</span>
                                    </div>
                                </div>
                            </div>
                           
                            <a href="{{ route('delivery-partner.profile-detail') }}" class="dropdown-item">
                                <i class="feather-user"></i>
                                <span>Profile Details</span>
                            </a>
                            <a href="{{ route('delivery-partner.profile') }}" class="dropdown-item">
                                <i class="feather-lock"></i>
                                <span>Change Password</span>
                            </a>
                            <!-- <a href="{{ route('delivery-partner.account.settings') }}" class="dropdown-item">
                                <i class="feather-settings"></i>
                                <span>Account Settings</span>
                            </a> -->
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