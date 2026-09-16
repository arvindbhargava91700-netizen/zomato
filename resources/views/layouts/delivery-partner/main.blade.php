<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Delivery Partner Dashboard - Zomato')</title>

    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/logo.jpeg') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/vendors/css/vendors.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('admin/assets/css/theme.min.css') }}" />
     <link rel="stylesheet" type="text/css" href="{{asset('admin/assets/vendors/css/dataTables.bs5.min.css')}}">

    @stack('styles')
</head>
<body>

    @include('layouts.delivery-partner.sidebar')
    @include('layouts.delivery-partner.header')

    <main class="nxl-container">
        <div class="nxl-content">
            @yield('content')
        </div>
        @include('layouts.delivery-partner.footer')
    </main>

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
                                style="border-radius: 10px; background: linear-gradient(135deg, #3b82f6 0%, #10b981 100%); box-shadow: 0 4px 12px rgba(59, 130, 246, 0.25); border: none; transition: all 0.2s ease;">
                            Yes, Logout
                        </button>
                    </div>
                    <form id="logout-form" method="POST" action="{{ route('delivery-partner.logout') }}" style="display: none;">
                        @csrf @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>

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

    @vite(['resources/js/echo.js'])

    <!-- Live Location Tracker -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (navigator.geolocation) {
                // Fetch and send location every 30 seconds (30000 ms)
                setInterval(() => {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            
                            fetch("{{ route('delivery-partner.profile.update-location') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                },
                                body: JSON.stringify({
                                    live_lat: lat,
                                    live_lng: lng
                                })
                            }).catch(err => console.error("Error updating location:", err));
                        },
                        (error) => {
                            console.warn("Location access denied or unavailable.");
                        },
                        { enableHighAccuracy: true, maximumAge: 10000, timeout: 5000 }
                    );
                }, 30000);
            }
        });
    </script>

    @stack('scripts')
</body>
</html>