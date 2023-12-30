<nav class="navbar navbar-expand-lg">
    <div class="container p-0">
        <a class="navbar-brand fw-bolder" href="#"><h5 class="mb-1 d-flex align-items-center"><img src="{{ asset('images/logo.jpg') }}" alt="Logo" width="50"> <span>&nbsp; Sepakat Morrobati Gede</span></h5></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto ms-4 mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link @isset($page) {{ $page == "dashboard" ? 'active fw-medium' : '' }} @endisset" aria-current="page" href="{{ route('w-dashboard.index') }}">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @isset($page) {{ in_array($page, ["users", "barang", "customer"]) ? 'fw-medium' : '' }} @endisset" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Data Master
                    </a>
                    <ul class="dropdown-menu">  
                        <li><a class="dropdown-item @isset($page) {{ $page == "barang" ? 'fw-medium' : '' }} @endisset" href="{{ route('m-barang.index') }}">Master Barang</a></li>
                        <li><a class="dropdown-item @isset($page) {{ $page == "customer" ? 'fw-medium' : '' }} @endisset" href="{{ route('m-customer.index') }}">Master Pelanggan</a></li>
                        <li><a class="dropdown-item @isset($page) {{ $page == "users" ? 'fw-medium' : '' }} @endisset" href="{{ route('m-users.index') }}">Master User</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link @isset($page) {{ $page == "manual" ? 'active fw-medium' : '' }} @endisset" aria-current="page" href="{{ route('w-cek-manual.index') }}">Surat Jalan</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle @isset($page) {{ in_array($page, ["report_barang", "report_customer", "report_checker", "report_kendaraan"]) ? 'fw-medium' : '' }} @endisset" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Laporan
                    </a>
                    <ul class="dropdown-menu">  
                        <li><a class="dropdown-item @isset($page) {{ $page == "report_barang" ? 'fw-medium' : '' }} @endisset" href="{{ route('r-barang.index') }}">Laporan Barang</a></li>
                        <li><a class="dropdown-item @isset($page) {{ $page == "report_kendaraan" ? 'fw-medium' : '' }} @endisset" href="{{ route('r-kendaraan.index') }}">Laporan Kendaraan</a></li>
                        <li><a class="dropdown-item @isset($page) {{ $page == "report_customer" ? 'fw-medium' : '' }} @endisset" href="{{ route('r-customer.index') }}">Laporan Pelanggan</a></li>
                        <li><a class="dropdown-item @isset($page) {{ $page == "report_checker" ? 'fw-medium' : '' }} @endisset" href="{{ route('r-checker.index') }}">Laporan Scan Timbangan</a></li>
                    </ul>
                </li>
            </ul>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <img src="{{ asset('images/dummy.jpg') }}" class="rounded float-end" width="40" alt="...">
            </a> 
        </div>
    </div>
</nav>