<nav class="navbar sidebar navbar-expand-xl navbar-light">
    <div class="d-flex align-items-center">
        <a href="{{ route('admin.dashboard') }}" class="navbar-brand">
            <img src="{{ asset('assets/images/logo/logo.png') }}" alt="logo" class="light-mode-item navbar-brand-item h-40px">
            <img src="{{ asset('assets/images/logo/logo-light.png') }}" alt="logo" class="dark-mode-item navbar-brand-item h-40px">
        </a>
    </div>
    <div class="offcanvas offcanvas-start flex-row custom-scrollbar h-100" data-bs-backdrop="true" tabindex="-1" id="offcanvasSidebar">
        <div class="offcanvas-body sidebar-content d-flex flex-column pt-4">
            <ul class="navbar-nav flex-column" id="navbar-sidebar">
                <li class="nav-item fw-normal small ms-2 my-2"> MENU </li>
                <li class="nav-item"> <a href="{{ route('admin.dashboard') }}" class="nav-link @yield('active-home-dashboard')">BERANDA</a> </li>
                <li class="nav-item fw-normal small ms-2 my-2"> HALAMAN </li>
                <li class="nav-item"> <a href="{{ route('admin.package') }}" class="nav-link @yield('active-page-package')">PAKET</a> </li>
                <li class="nav-item"> <a href="{{ route('admin.owner') }}" class="nav-link @yield('active-page-owner')">PEMILIK</a> </li>
                <li class="nav-item"> <a href="{{ route('admin.employee') }}" class="nav-link @yield('active-page-employee')">KARYAWAN</a> </li>
                <li class="nav-item"> <a href="{{ route('admin.customer') }}" class="nav-link @yield('active-page-customer')">PELANGGAN</a> </li>
                <li class="nav-item fw-normal small ms-2 my-2"> DATA </li>
                <li class="nav-item"> <a href="{{ route('admin.transaction') }}" class="nav-link @yield('active-data-transaction')">TRANSAKSI</a> </li>
                <li class="nav-item"> <a href="{{ route('admin.report') }}" class="nav-link @yield('active-data-report')">LAPORAN</a> </li>
                <li class="nav-item"> <a href="{{ route('admin.coupon') }}" class="nav-link @yield('active-data-coupon')">KUPON</a> </li>
                <li class="nav-item fw-normal small ms-2 my-2"> LAINNYA </li>
                <li class="nav-item"> <a href="{{ route('admin.review') }}" class="nav-link @yield('active-other-review')">ULASAN</a> </li>
            </ul>
        </div>
    </div>
</nav>
