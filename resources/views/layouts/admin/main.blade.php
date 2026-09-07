{{-- resources/views/admin/layouts/main.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard -Property Management Software')</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/logo.jpeg') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/css/vendors.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/css/daterangepicker.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/theme.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/theme.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/vendors/css/dataTables.bs5.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.0.0/fonts/remixicon.css" />

    <!-- <style>
        body{
            background-color: rgb(243, 252, 255) ;
        }
       
        .nxl-navigation {
            background: linear-gradient(135deg, #dcfce7 0%, #dbeafe 100%) ;
        }

        .nxl-navigation .m-header{
            background: transparent ;
        }

        .nxl-navbar>.nxl-item.active>.nxl-link,
        .nxl-navbar>.nxl-item:hover>.nxl-link{
            background: linear-gradient(135deg, #c2fcd7 0%, #b3d0f7 100%) ;
            color: green;
        }

        .nxl-navigation .navbar-content .nxl-item.active>.nxl-link{
            background: linear-gradient(135deg, #c2fcd7 0%, #b3d0f7 100%);
        }
        .nxl-navigation .navbar-content .nxl-item:hover>.nxl-link{
            background: linear-gradient(135deg, #c2fcd7 0%, #b3d0f7 100%);
        }

        .nxl-navbar>.nxl-item .nxl-link{
            color: green;
        }
                
        .nxl-header{
            top:0;
            /* background: linear-gradient(135deg, #DCF6EF 95%, #c2fcd7 100%) ; */
        }
        /* .page-header{
            margin-top: -23px;
        } */
    </style> -->

    @stack('styles')
</head>
<body>

    @include('layouts.admin.sidebar')
    @include('layouts.admin.header')

    <main class="nxl-container">
        <div class="nxl-content">
            @yield('content')
        </div>
        @include('layouts.admin.footer')
    </main>

    <div class="theme-customizer">
        <div class="customizer-handle">
            <a href="javascript:void(0);" class="cutomizer-open-trigger bg-primary">
                <i class="feather-settings"></i>
            </a>
        </div>
        <div class="customizer-sidebar-wrapper">
            <div class="customizer-sidebar-header px-4 ht-80 border-bottom d-flex align-items-center justify-content-between">
                <h5 class="mb-0">Theme Settings</h5>
                <a href="javascript:void(0);" class="cutomizer-close-trigger d-flex">
                    <i class="feather-x"></i>
                </a>
            </div>
            <div class="customizer-sidebar-body position-relative p-4" data-scrollbar-target="#psScrollbarInit">
                <!--! BEGIN: [Navigation] !-->
                <div class="position-relative px-3 pb-3 pt-4 mt-3 mb-5 border border-gray-2 theme-options-set">
                    <label class="py-1 px-2 fs-8 fw-bold text-uppercase text-muted text-spacing-2 bg-white border border-gray-2 position-absolute rounded-2 options-label" style="top: -12px">Navigation</label>
                    <div class="row g-2 theme-options-items app-navigation" id="appNavigationList">
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-navigation-light" name="app-navigation" value="1" data-app-navigation="app-navigation-light" checked />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-navigation-light">Light</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-navigation-dark" name="app-navigation" value="2" data-app-navigation="app-navigation-dark" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-navigation-dark">Dark</label>
                        </div>
                    </div>
                </div>
                <!--! END: [Navigation] !-->
                <!--! BEGIN: [Header] !-->
                <div class="position-relative px-3 pb-3 pt-4 mt-3 mb-5 border border-gray-2 theme-options-set mt-5">
                    <label class="py-1 px-2 fs-8 fw-bold text-uppercase text-muted text-spacing-2 bg-white border border-gray-2 position-absolute rounded-2 options-label" style="top: -12px">Header</label>
                    <div class="row g-2 theme-options-items app-header" id="appHeaderList">
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-header-light" name="app-header" value="1" data-app-header="app-header-light" checked />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-header-light">Light</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-header-dark" name="app-header" value="2" data-app-header="app-header-dark" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-header-dark">Dark</label>
                        </div>
                    </div>
                </div>
                <!--! END: [Header] !-->
                <!--! BEGIN: [Skins] !-->
                <div class="position-relative px-3 pb-3 pt-4 mt-3 mb-5 border border-gray-2 theme-options-set">
                    <label class="py-1 px-2 fs-8 fw-bold text-uppercase text-muted text-spacing-2 bg-white border border-gray-2 position-absolute rounded-2 options-label" style="top: -12px">Skins</label>
                    <div class="row g-2 theme-options-items app-skin" id="appSkinList">
                        <div class="col-6 text-center position-relative single-option light-button active">
                            <input type="radio" class="btn-check" id="app-skin-light" name="app-skin" value="1" data-app-skin="app-skin-light" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-skin-light">Light</label>
                        </div>
                        <div class="col-6 text-center position-relative single-option dark-button">
                            <input type="radio" class="btn-check" id="app-skin-dark" name="app-skin" value="2" data-app-skin="app-skin-dark" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-skin-dark">Dark</label>
                        </div>
                    </div>
                </div>
                <!--! END: [Skins] !-->
                <!--! BEGIN: [Typography] !-->
                <div class="position-relative px-3 pb-3 pt-4 mt-3 mb-0 border border-gray-2 theme-options-set">
                    <label class="py-1 px-2 fs-8 fw-bold text-uppercase text-muted text-spacing-2 bg-white border border-gray-2 position-absolute rounded-2 options-label" style="top: -12px">Typography</label>
                    <div class="row g-2 theme-options-items font-family" id="fontFamilyList">
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-lato" name="font-family" value="1" data-font-family="app-font-family-lato" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-lato">Lato</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-rubik" name="font-family" value="2" data-font-family="app-font-family-rubik" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-rubik">Rubik</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-inter" name="font-family" value="3" data-font-family="app-font-family-inter" checked />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-inter">Inter</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-cinzel" name="font-family" value="4" data-font-family="app-font-family-cinzel" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-cinzel">Cinzel</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-nunito" name="font-family" value="6" data-font-family="app-font-family-nunito" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-nunito">Nunito</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-roboto" name="font-family" value="7" data-font-family="app-font-family-roboto" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-roboto">Roboto</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-ubuntu" name="font-family" value="8" data-font-family="app-font-family-ubuntu" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-ubuntu">Ubuntu</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-poppins" name="font-family" value="9" data-font-family="app-font-family-poppins" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-poppins">Poppins</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-raleway" name="font-family" value="10" data-font-family="app-font-family-raleway" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-raleway">Raleway</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-system-ui" name="font-family" value="11" data-font-family="app-font-family-system-ui" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-system-ui">System UI</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-noto-sans" name="font-family" value="12" data-font-family="app-font-family-noto-sans" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-noto-sans">Noto Sans</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-fira-sans" name="font-family" value="13" data-font-family="app-font-family-fira-sans" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-fira-sans">Fira Sans</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-work-sans" name="font-family" value="14" data-font-family="app-font-family-work-sans" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-work-sans">Work Sans</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-open-sans" name="font-family" value="15" data-font-family="app-font-family-open-sans" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-open-sans">Open Sans</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-maven-pro" name="font-family" value="16" data-font-family="app-font-family-maven-pro" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-maven-pro">Maven Pro</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-quicksand" name="font-family" value="17" data-font-family="app-font-family-quicksand" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-quicksand">Quicksand</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-montserrat" name="font-family" value="18" data-font-family="app-font-family-montserrat" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-montserrat">Montserrat</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-josefin-sans" name="font-family" value="19" data-font-family="app-font-family-josefin-sans" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-josefin-sans">Josefin Sans</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-ibm-plex-sans" name="font-family" value="20" data-font-family="app-font-family-ibm-plex-sans" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-ibm-plex-sans">IBM Plex Sans</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-source-sans-pro" name="font-family" value="5" data-font-family="app-font-family-source-sans-pro" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-source-sans-pro">Source Sans Pro</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-montserrat-alt" name="font-family" value="21" data-font-family="app-font-family-montserrat-alt" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-montserrat-alt">Montserrat Alt</label>
                        </div>
                        <div class="col-6 text-center single-option">
                            <input type="radio" class="btn-check" id="app-font-family-roboto-slab" name="font-family" value="22" data-font-family="app-font-family-roboto-slab" />
                            <label class="py-2 fs-9 fw-bold text-dark text-uppercase text-spacing-1 border border-gray-2 w-100 h-100 c-pointer position-relative options-label" for="app-font-family-roboto-slab">Roboto Slab</label>
                        </div>
                    </div>
                </div>
                <!--! END: [Typography] !-->
            </div>
            <div class="customizer-sidebar-footer px-4 ht-60 border-top d-flex align-items-center gap-2">
                <div class="flex-fill w-100">
                    <a href="javascript:void(0);" class="btn btn-danger" data-style="reset-all-common-style">Reset</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Delete Confirmation Modal -->
    <div class="modal fade" id="globalDeleteModal" tabindex="-1" aria-labelledby="globalDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: #ffffff;">
                <!-- Header Gradient -->
                <div style="height: 6px; background: linear-gradient(90deg, #ef4444 0%, #f97316 100%);"></div>
                <div class="modal-body text-center p-5">
                    <!-- Warning/Trash Icon -->
                    <div class="d-inline-flex align-items-center justify-content-center mb-4 rounded-circle" 
                         style="width: 80px; height: 80px; background: rgba(239, 68, 68, 0.1); border: 4px solid rgba(239, 68, 68, 0.05); animation: pulseDanger 2s infinite;">
                        <i class="feather-trash-2 text-danger" style="font-size: 36px;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2" id="globalDeleteModalTitle">Confirm Deletion</h4>
                    <p class="text-muted mb-4 px-3" id="globalDeleteModalMessage">Are you sure you want to delete this item? This action cannot be undone.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <button type="button" class="btn btn-light px-4 py-2 fw-semibold text-secondary" data-bs-dismiss="modal" 
                                style="border-radius: 10px; border: 1px solid #e2e8f0; transition: all 0.2s ease;">
                            Cancel
                        </button>
                        <button type="button" id="globalDeleteConfirmBtn" class="btn text-white px-4 py-2 fw-semibold" 
                                style="border-radius: 10px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25); border: none; transition: all 0.2s ease;">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Logout Confirmation Modal -->
    <div class="modal fade" id="globalLogoutModal" tabindex="-1" aria-labelledby="globalLogoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: #ffffff;">
                <!-- Header Gradient -->
                <div style="height: 6px; background: linear-gradient(90deg, #3b82f6 0%, #10b981 100%);"></div>
                <div class="modal-body text-center p-5">
                    <!-- Logout Icon -->
                    <div class="d-inline-flex align-items-center justify-content-center mb-4 rounded-circle" 
                         style="width: 80px; height: 80px; background: rgba(59, 130, 246, 0.1); border: 4px solid rgba(59, 130, 246, 0.05); animation: pulseLogout 2s infinite;">
                        <i class="feather-log-out text-primary" style="font-size: 36px;"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">Confirm Logout</h4>
                    <p class="text-muted mb-4 px-3">Are you sure you want to logout? You will need to login again to access the dashboard.</p>
                    <div class="d-flex gap-3 justify-content-center">
                        <button type="button" class="btn btn-light px-4 py-2 fw-semibold text-secondary" data-bs-dismiss="modal" 
                                style="border-radius: 10px; border: 1px solid #e2e8f0; transition: all 0.2s ease;">
                            Cancel
                        </button>
                        <button type="button" id="globalLogoutConfirmBtn" class="btn text-white px-4 py-2 fw-semibold" 
                                style="border-radius: 10px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); border: none; transition: all 0.2s ease;">
                            Yes, Logout
                        </button>
                    </div>
                    <form id="logout-form" method="POST" action="{{ route('admin.logout') }}" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Specific Modals (Direct children of body for correct stacking context) -->
    @stack('modals')

    <style>
        @keyframes pulseDanger {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
        @keyframes pulseLogout {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            70% { box-shadow: 0 0 0 15px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }
        #globalDeleteModal .modal-content, #globalLogoutModal .modal-content {
            transform: scale(0.95);
            transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        #globalDeleteModal.show .modal-content, #globalLogoutModal.show .modal-content {
            transform: scale(1);
        }
        #globalDeleteModal .btn-light:hover, #globalLogoutModal .btn-light:hover {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
            border-color: #cbd5e1 !important;
        }
        #globalDeleteConfirmBtn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(239, 68, 68, 0.35) !important;
            filter: brightness(1.05);
        }
        #globalLogoutConfirmBtn:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(59, 130, 246, 0.35) !important;
            filter: brightness(1.05);
        }
        .modal-backdrop.show {
            background-color: rgba(15, 23, 42, 0.5) !important;
            opacity: 0.5 !important;
        }
    </style>

    <script src="{{ asset('admin/assets/vendors/js/vendors.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/assets/vendors/js/circle-progress.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/common-init.min.js') }}"></script>


    <!-- vendors.min.js {always must need to be top} -->
    <script src="{{asset('admin/assets/vendors/js/dataTables.min.js')}}"></script>
    <script src="{{asset('admin/assets/vendors/js/dataTables.bs5.min.js')}}"></script>

    @if(Route::currentRouteName() !== 'admin.dashboard')
    <script src="{{ asset('admin/assets/js/dashboard-init.min.js') }}"></script>
    @endif
    <script src="{{ asset('admin/assets/js/theme-customizer-init.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/customers-init.min.js') }}"></script>

    @include('layouts.admin.scripts')

    @vite(['resources/js/echo.js'])

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoutBtn = document.getElementById('globalLogoutConfirmBtn');
            if (logoutBtn) {
                logoutBtn.addEventListener('click', function () {
                    const logoutForm = document.getElementById('logout-form');
                    if (logoutForm) {
                        logoutForm.submit();
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>