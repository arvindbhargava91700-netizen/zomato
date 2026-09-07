{{-- resources/views/admin/layouts/scripts.blade.php --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Submenu toggle
        document.querySelectorAll('.nxl-hasmenu > a').forEach(function(link) {
            link.addEventListener('click', function() {
                const parentLi = this.closest('.nxl-hasmenu');

                document.querySelectorAll('.nxl-hasmenu.nxl-trigger').forEach(function(el) {
                    if (el !== parentLi) {
                        el.classList.remove('nxl-trigger');
                    }
                });

                parentLi.classList.toggle('nxl-trigger');
            });
        });

        // Mobile sidebar toggle
        const mobileCollapse = document.getElementById('mobile-collapse');
        const body = document.body;

        if (mobileCollapse) {
            mobileCollapse.addEventListener('click', function(e) {
                e.preventDefault();
                body.classList.toggle('nxl-navigation-show');

                if (window.innerWidth < 1200) {
                    if (body.classList.contains('nxl-navigation-show')) {
                        const overlay = document.createElement('div');
                        overlay.className = 'nxl-navigation-overlay';
                        overlay.style.cssText = `
                            position: fixed; top: 0; left: 0;
                            width: 100%; height: 100%;
                            background: rgba(0,0,0,0.5);
                            z-index: 999; cursor: pointer;
                        `;
                        body.appendChild(overlay);
                        overlay.addEventListener('click', function() {
                            body.classList.remove('nxl-navigation-show');
                            overlay.remove();
                        });
                    } else {
                        const overlay = document.querySelector('.nxl-navigation-overlay');
                        if (overlay) overlay.remove();
                    }
                }
            });
        }

        // Desktop mini sidebar toggle
        const menuMiniButton = document.getElementById('menu-mini-button');
        const menuExpendButton = document.getElementById('menu-expend-button');

        if (menuMiniButton) {
            menuMiniButton.addEventListener('click', function(e) {
                e.preventDefault();
                body.classList.toggle('nxl-navbar-hide');
                if (menuExpendButton) {
                    menuMiniButton.style.display = 'none';
                    menuExpendButton.style.display = 'block';
                }
            });
        }

        if (menuExpendButton) {
            menuExpendButton.addEventListener('click', function(e) {
                e.preventDefault();
                body.classList.toggle('nxl-navbar-hide');
                menuExpendButton.style.display = 'none';
                if (menuMiniButton) {
                    menuMiniButton.style.display = 'block';
                }
            });
        }

    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.nxl-hasmenu > .nxl-link').forEach(function(link){

        link.addEventListener('click', function(e){

            e.preventDefault();

            this.closest('.nxl-hasmenu').classList.toggle('nxl-trigger');

        });

    });

});

document.addEventListener('DOMContentLoaded', function () {

    const logoutBtn = document.getElementById('globalLogoutConfirmBtn');

    if (logoutBtn) {
        logoutBtn.addEventListener('click', function () {
            document.getElementById('logout-form').submit();
        });
    }

});
</script>

